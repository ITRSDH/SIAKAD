@extends('layouts.index')
@section('title', 'FAQ')
@push('styles-custom')
    <style>
        /* Gaya untuk loader */
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            /* Latar belakang transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            /* Pastikan loader muncul di atas konten */
            border-radius: inherit;
            /* Membuat sudut tetap jika card memiliki border-radius */
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            /* Warna spinner */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Pastikan card body memiliki posisi relatif agar loader muncul di dalamnya */
        .card-body {
            position: relative;
        }

        /* Sembunyikan loader secara default */
        .loader-overlay.hidden {
            display: none;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
            /* Berputar 180 derajat saat dibuka */
        }

        .content-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">FAQ</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('faq.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('faq.index') }}">FAQ</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseFaqForm" aria-expanded="true"
                        aria-controls="collapseFaqForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah FAQ
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseFaqForm">
                        <div class="card-body">
                            <form id="faqForm" name="faqForm">
                                @csrf
                                <input type="hidden" name="id" id="faq_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="pertanyaan" class="form-label">Pertanyaan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="pertanyaan" name="pertanyaan"
                                                placeholder="Masukkan pertanyaan">
                                            <div class="text-danger error-text" id="pertanyaan_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="jawaban" class="form-label">Jawaban <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="jawaban" name="jawaban" rows="6" placeholder="Masukkan jawaban"></textarea>
                                            <div class="text-danger error-text" id="jawaban_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" id="saveBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary ms-2">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>Data FAQ
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="faq-table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 40%;">Pertanyaan</th>
                                        <th style="width: 40%;">Jawaban</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th style="width: 5%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalFaq" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="faqFormModal" name="faqFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="faq_id_modal">

                        <div class="form-group mb-3">
                            <label for="pertanyaan_modal" class="form-label">Pertanyaan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pertanyaan_modal" name="pertanyaan"
                                placeholder="Masukkan pertanyaan">
                            <div class="text-danger error-text" id="pertanyaan_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jawaban_modal" class="form-label">Jawaban <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="jawaban_modal" name="jawaban" rows="6" placeholder="Masukkan jawaban"></textarea>
                            <div class="text-danger error-text" id="jawaban_modal_error"></div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" id="saveBtnModal" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var faqData = @json($faq);

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#faq-table').DataTable({
                data: faqData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            // Kolom No (indeks baris + 1)
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pertanyaan'
                    },
                    {
                        data: 'jawaban',
                        render: function(data, type, row) {
                            // Batasi panjang jawaban dan tambahkan ellipsis jika terlalu panjang
                            if (data && data.length > 100) {
                                return '<div class="content-preview" title="' + data + '">' + data
                                    .substring(0, 100) + '...</div>';
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('id-ID');
                            }
                            return '-';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning edit-btn"
                                        data-id="${row.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="${row.id}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json' // Bahasa Indonesia
                },
                drawCallback: function(settings) {
                    // Sembunyikan loader setelah tabel selesai digambar
                    $('#tableLoader').addClass('hidden');
                }
            });

            // Reset form
            $('#resetBtn').click(function() {
                $('#faqForm')[0].reset();
                $('#faq_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#faqForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('faq.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Tambah data ke tabel
                            table.row.add(response.data).draw();
                            // Reset form
                            $('#faqForm')[0].reset();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'FAQ berhasil ditambahkan.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat menyimpan data.',
                                confirmButtonText: 'OK'
                            });
                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_error').text(response.errors[key][
                                        0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
                        }
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                $('#' + key + '_error').text(xhr.responseJSON.errors[
                                    key][0]);
                            });
                        }
                    },
                    complete: function() {
                        // Aktifkan kembali tombol setelah permintaan selesai
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
                        );
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                // Ambil data FAQ spesifik dari API
                $.get("{{ route('faq.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#faq_id_modal').val(data.data.id);
                            $('#pertanyaan_modal').val(data.data.pertanyaan);
                            $('#jawaban_modal').val(data.data.jawaban);
                            $('#modelHeading').text('Edit FAQ');
                            $('.error-text').text(''); // Hapus pesan error
                            $('#modalFaq').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Data tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .fail(function(xhr) {
                        console.error('Error fetching data for edit:', xhr);
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal
            $('#faqFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#faq_id_modal').val();
                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('faq.update', '') }}/" + id,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Update data di tabel dengan cara mencari row dan memperbarui data
                            var rowIndex = table.row(function(idx, data, node) {
                                return data.id == id;
                            }).index();
                            if (rowIndex !== undefined) {
                                table.row(rowIndex).data(response.data).draw();
                            }
                            // Tutup modal
                            $('#modalFaq').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'FAQ berhasil diperbarui.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat memperbarui data.',
                                confirmButtonText: 'OK'
                            });
                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_modal_error').text(response.errors[
                                        key][0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });

                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                $('#' + key + '_modal_error').text(xhr.responseJSON
                                    .errors[key][0]);
                            });
                        }
                    },
                    complete: function() {
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Update'
                        );
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                // Ganti confirm dengan SweetAlert2
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "FAQ ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('faq.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    var rowIndex = table.row(function(idx, data, node) {
                                        return data.id == id;
                                    }).index();
                                    if (rowIndex !== undefined) {
                                        table.row(rowIndex).remove().draw();
                                    }
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'FAQ berhasil dihapus.',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menghapus data.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error('AJAX Error:', xhr);
                                let errorMessage = 'Gagal menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
