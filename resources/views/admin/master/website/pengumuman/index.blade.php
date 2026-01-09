@extends('layouts.index')
@section('title', 'Pengumuman')
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
            <h3 class="fw-bold mb-3">Pengumuman</h3>
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
                    <a href="{{ route('pengumuman.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengumuman.index') }}">Pengumuman</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapsePengumumanForm" aria-expanded="true"
                        aria-controls="collapsePengumumanForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Pengumuman
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapsePengumumanForm">
                        <div class="card-body">
                            <form id="pengumumanForm" name="pengumumanForm">
                                @csrf
                                <input type="hidden" name="id" id="pengumuman_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Pengumuman <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul pengumuman">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="form-label">Kategori <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="kategori" name="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <option value="akademik">Akademik</option>
                                                <option value="administrasi">Administrasi</option>
                                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                                <option value="umum">Umum</option>
                                            </select>
                                            <div class="text-danger error-text" id="kategori_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="isi" class="form-label">Isi Pengumuman <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="isi" name="isi" rows="6" placeholder="Masukkan isi pengumuman"></textarea>
                                            <div class="text-danger error-text" id="isi_error"></div>
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
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Data Pengumuman</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pengumuman-table" class="table table-striped table-bordered w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Isi</th>
                                        <th>Aksi</th>
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
    <div class="modal fade" id="modalPengumuman" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pengumumanFormModal" name="pengumumanFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="pengumuman_id_modal">

                        <div class="form-group mb-3">
                            <label for="judul_modal" class="form-label">Judul Pengumuman <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_modal" name="judul"
                                placeholder="Masukkan judul pengumuman">
                            <div class="text-danger error-text" id="judul_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori_modal" class="form-label">Kategori <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="kategori_modal" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="akademik">Akademik</option>
                                <option value="administrasi">Administrasi</option>
                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                <option value="umum">Umum</option>
                            </select>
                            <div class="text-danger error-text" id="kategori_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="isi_modal" class="form-label">Isi Pengumuman <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_modal" name="isi" rows="6" placeholder="Masukkan isi pengumuman"></textarea>
                            <div class="text-danger error-text" id="isi_modal_error"></div>
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
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#pengumuman-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pengumuman.datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul'
                    },
                    {
                        data: 'kategori',
                        render: function(data, type, row) {
                            const kategoriMap = {
                                'administrasi': '<span class="badge bg-primary">Administrasi</span>',
                                'akademik': '<span class="badge bg-success">Akademik</span>',
                                'kemahasiswaan': '<span class="badge bg-info">Kemahasiswaan</span>',
                                'umum': '<span class="badge bg-warning">Umum</span>',
                            };
                            return kategoriMap[data] ||
                                '<span class="badge bg-light text-dark">-</span>';
                        }
                    },
                    {
                        data: 'tanggal',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'isi',
                        render: function(data, type, row) {
                            if (data && data.length > 50) {
                                return data.substring(0, 50) + '...';
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                }
            });

            // Reset form
            $('#resetBtn').click(function() {
                $('#pengumumanForm')[0].reset();
                $('#pengumuman_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#pengumumanForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('pengumuman.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Reload DataTable untuk refresh data
                            table.ajax.reload();
                            // Reset form
                            $('#pengumumanForm')[0].reset();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Pengumuman berhasil ditambahkan.',
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
                // Ambil data pengumuman spesifik dari API
                $.get("{{ route('pengumuman.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#pengumuman_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            $('#isi_modal').val(data.data.isi);
                            $('#kategori_modal').val(data.data.kategori);
                            $('#modelHeading').text('Edit Pengumuman');
                            $('.error-text').text(''); // Hapus pesan error
                            $('#modalPengumuman').modal('show');
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
            $('#pengumumanFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#pengumuman_id_modal').val();
                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('pengumuman.update', '') }}/" + id,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Update data di tabel dengan cara mencari row dan memperbarui data
                            table.ajax.reload(null,
                            false); // false untuk mempertahankan halaman saat ini
                            // Tutup modal
                            $('#modalPengumuman').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Pengumuman berhasil diperbarui.',
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
                    text: "Pengumuman ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pengumuman.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    table.ajax.reload(null,
                                    false); // false untuk mempertahankan halaman saat ini
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'Pengumuman berhasil dihapus.',
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
