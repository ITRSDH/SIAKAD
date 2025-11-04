@extends('admin.layouts.index')
@section('title', 'Tahun Akademik')
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tahun Akademik</h3>
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
                    <a href="{{ route('tahun-akademik.index') }}">Tahun Akademik</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tahun-akademik.index') }}">List Tahun Akademik</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseTahunAkademikForm" aria-expanded="true"
                        aria-controls="collapseTahunAkademikForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Tahun Akademik
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseTahunAkademikForm">
                        <div class="card-body">
                            <form id="tahunAkademikForm" name="tahunAkademikForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="tahun_akademik_id">

                                <div class="form-group row mb-3">
                                    <label for="tahun_akademik" class="col-sm-3 col-form-label text-end">
                                        Tahun Akademik <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik"
                                            placeholder="Contoh: 2025/2026" required>
                                        <small class="form-text text-muted">Format tahun akademik dalam bentuk tahun
                                            awal/tahun akhir (misalnya 2025/2026).</small>
                                        <span id="tahun_akademik_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label text-end">Status Aktif</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status_aktif"
                                                name="status_aktif" value="1">
                                            <label class="form-check-label" for="status_aktif">Tahun Akademik Aktif</label>
                                        </div>
                                        <small class="form-text text-muted">Centang jika tahun akademik ini adalah tahun
                                            yang sedang berjalan.</small>
                                        <span id="status_aktif_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <hr class="mt-0 mb-4">
                                <div class="form-group row mb-0">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary" id="saveBtn">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetBtn">
                                            <i class="fas fa-redo"></i> Reset
                                        </button>
                                    </div>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Tahun Akademik
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="tahun-akademik-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Akademik</th>
                                        <th>Status Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi ini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalTahunAkademik" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tahunAkademikFormModal" name="tahunAkademikFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="tahun_akademik_id_modal">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_akademik_modal" class="col-form-label">Tahun Akademik</label>
                                    <input type="text" class="form-control" id="tahun_akademik_modal"
                                        name="tahun_akademik" required>
                                    <span class="text-danger error-text tahun_akademik_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status_aktif_modal" class="col-form-label">Status Aktif</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status_aktif_modal"
                                    name="status_aktif" value="1">
                                <label class="form-check-label" for="status_aktif_modal">Aktif</label>
                            </div>
                            <span class="text-danger error-text status_aktif_error"></span>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary" id="saveBtnModal">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var tahunAkademikData = @json($tahunAkademik);

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#tahun-akademik-table').DataTable({
                data: tahunAkademikData, // Gunakan data dari PHP
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
                        data: 'tahun_akademik'
                    },
                    {
                        data: 'status_aktif',
                        render: function(data, type, row) {
                            // Tampilkan 'Aktif' atau 'Tidak Aktif'
                            // return data ? '<span class="badge bg-success">Aktif</span>' :
                            //     '<span class="badge bg-secondary">Tidak Aktif</span>';
                            return `
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-aktif-switch" type="checkbox"
                                        id="switch_${row.id}"
                                        data-id="${row.id}"
                                        ${row.status_aktif ? 'checked' : ''}>
                                    <label class="form-check-label" for="switch_${row.id}">
                                        ${row.status_aktif ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>'}
                                    </label>
                                </div>
                            `;
                        }
                    },
                    {
                        data: null, // Tidak ada data spesifik dari API untuk kolom ini
                        render: function(data, type, row) {
                            // Generate tombol aksi berdasarkan ID dari data API
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
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

            // Validasi format tahun akademik
            function validateTahunAkademik(value) {
                const regex = /^\d{4}\/\d{4}$/;
                return regex.test(value);
            }

            // Validasi tanggal mulai < tanggal selesai
            function validateTanggalMulaiSelesai(mulai, selesai) {
                const startDate = new Date(mulai);
                const endDate = new Date(selesai);
                return startDate <= endDate;
            }

            // Reset form
            $('#resetBtn').click(function() {
                $('#tahunAkademikForm')[0].reset();
                $('#tahun_akademik_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#tahunAkademikForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const formData = $(this).serialize();
                const tahunAkademik = $('#tahun_akademik').val();

                // Validasi format tahun akademik
                if (!validateTahunAkademik(tahunAkademik)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tahun Akademik Salah',
                        text: 'Format tahun akademik harus: YYYY/YY (Contoh: 2023/2024)',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('tahun-akademik.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location
                                    .reload(); // Reload halaman setelah SweetAlert ditutup
                            });
                        } else {
                            // Tangani error dari server (jika ada pesan kesalahan spesifik)
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                // Tampilkan pesan error umum jika tidak ada error spesifik
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message ||
                                        'Terjadi kesalahan saat menyimpan data.',
                                    confirmButtonText: 'OK'
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
                            // Tampilkan error pertama dari server
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    },
                    complete: function() { // Aktifkan kembali tombol setelah permintaan selesai
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
                        );
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                // Ambil data tahun akademik spesifik dari API
                $.get("{{ route('tahun-akademik.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Tahun Akademik');
                            $('#tahun_akademik_id_modal').val(data.data.id);
                            $('#tahun_akademik_modal').val(data.data.tahun_akademik);
                            $('#status_aktif_modal').prop('checked', data.data.status_aktif);
                            // Hapus error sebelumnya
                            $('.error-text').text('');
                            $('#modalTahunAkademik').modal('show');
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data yang Anda cari tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .fail(function(xhr) {
                        console.error('Error fetching data for edit:', xhr);
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal
            $('#tahunAkademikFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#tahun_akademik_id_modal').val();
                const formData = $(this).serialize();
                const tahunAkademik = $('#tahun_akademik_modal').val();

                // Validasi format tahun akademik
                if (!validateTahunAkademik(tahunAkademik)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tahun Akademik Salah',
                        text: 'Format tahun akademik harus: YYYY/YY (Contoh: 2023/2024)',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('tahun-akademik.update', '') }}/" + id,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#modalTahunAkademik').modal('hide');
                                location
                                    .reload(); // Reload halaman setelah SweetAlert ditutup
                            });
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                // Tampilkan pesan error umum jika tidak ada error spesifik
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message ||
                                        'Terjadi kesalahan saat memperbarui data.',
                                    confirmButtonText: 'OK'
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
                            // Tampilkan error pertama dari server
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    },
                    complete: function() {
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
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
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loader tabel saat menghapus
                        $('#tableLoader').removeClass('hidden');

                        $.ajax({
                            url: "{{ route('tahun-akademik.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location
                                            .reload(); // Reload halaman setelah SweetAlert ditutup
                                    });
                                } else {
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Gagal menghapus data.',
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
                                // Ganti alert dengan SweetAlert2
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            },
                            complete: function() { // Pastikan loader disembunyikan setelah permintaan selesai
                                $('#tableLoader').addClass('hidden');
                            }
                        });
                    }
                });
            });

            // Atur tahun akademik aktif
            // Toggle status aktif via switch
            $(document).on('change', '.status-aktif-switch', function() {
                const id = $(this).data('id');
                const isActive = $(this).is(':checked');
                const statusText = isActive ? 'aktif' : 'tidak aktif';
                const actionText = isActive ? 'mengaktifkan' : 'menonaktifkan';

                Swal.fire({
                    title: `Anda yakin ingin ${actionText} tahun akademik ini?`,
                    text: "Semua tahun akademik lain akan diubah menjadi tidak aktif.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('tahun-akademik.setAktif', ':id') }}".replace(
                                ':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status_aktif: isActive ? 1 : 0
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload(); // Reload halaman
                                    });
                                } else {
                                    // Kembalikan status switch jika gagal
                                    $(`.status-aktif-switch[data-id="${id}"]`).prop(
                                        'checked', !isActive);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error('AJAX Error:', xhr);
                                let errorMessage =
                                    'Gagal mengatur tahun akademik aktif.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                // Kembalikan status switch jika error
                                $(`.status-aktif-switch[data-id="${id}"]`).prop(
                                    'checked', !isActive);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    } else {
                        // Kembalikan status switch jika dibatalkan
                        $(`.status-aktif-switch[data-id="${id}"]`).prop('checked', !isActive);
                    }
                });
            });
        });
    </script>
@endpush
