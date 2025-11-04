@extends('admin.layouts.index')
@section('title', 'Kurikulum')
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
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Kurikulum</h3>
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
                    <a href="{{ route('kurikulum.index') }}">Kurikulum</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kurikulum.index') }}">List Kurikulum</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseKurikulumForm" aria-expanded="true"
                        aria-controls="collapseKurikulumForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Kurikulum
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseKurikulumForm">
                        <div class="card-body">
                            <form id="kurikulumForm" name="kurikulumForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="kurikulum_id">

                                <div class="form-group row mb-3">
                                    <label for="id_prodi" class="col-sm-3 col-form-label text-end">
                                        Prodi <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="id_prodi" name="id_prodi" required>
                                            <option value="">Pilih Program Studi...</option>
                                            <!-- Opsi akan diisi oleh AJAX -->
                                        </select>
                                        <small class="form-text text-muted">Pilih program studi yang terkait.</small>
                                        <span id="id_prodi_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="nama_kurikulum" class="col-sm-3 col-form-label text-end">
                                        Nama Kurikulum <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nama_kurikulum" name="nama_kurikulum"
                                            placeholder="Contoh: Kurikulum 2020" required>
                                        <small class="form-text text-muted">Nama resmi dari kurikulum.</small>
                                        <span id="nama_kurikulum_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tahun_kurikulum" class="col-sm-3 col-form-label text-end">
                                        Tahun Kurikulum <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="tahun_kurikulum"
                                            name="tahun_kurikulum" placeholder="Contoh: 2020" min="2000"
                                            max="{{ date('Y') + 10 }}" required>
                                        <small class="form-text text-muted">Tahun berlakunya kurikulum (antara 2000 dan
                                            {{ date('Y') + 10 }}).</small>
                                        <span id="tahun_kurikulum_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label text-end">Status Aktif</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status"
                                                value="1">
                                            <label class="form-check-label" for="status">Kurikulum Aktif</label>
                                        </div>
                                        <small class="form-text text-muted">Centang jika kurikulum ini sedang
                                            berlaku.</small>
                                        <span id="status_error" class="text-danger error-text"></span>
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
                            <i class="fas fa-list me-2"></i>Data Kurikulum
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="kurikulum-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Prodi</th>
                                        <th>Nama Kurikulum</th>
                                        <th>Tahun Kurikulum</th>
                                        <th>Status</th>
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
    <div class="modal fade" id="modalKurikulum" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kurikulumFormModal" name="kurikulumFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="kurikulum_id_modal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_prodi_modal" class="col-form-label">Prodi</label>
                                    <select class="form-control" id="id_prodi_modal" name="id_prodi" required>
                                        <option value="">Pilih Prodi...</option>
                                        <!-- Opsi akan diisi oleh AJAX -->
                                    </select>
                                    <span class="text-danger error-text id_prodi_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_modal" class="col-form-label">Status Aktif</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status_modal" name="status"
                                            value="1">
                                        <label class="form-check-label" for="status_modal">Aktif</label>
                                    </div>
                                    <span class="text-danger error-text status_error"></span> <!-- Tetap pakai class -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_kurikulum_modal" class="col-form-label">Nama Kurikulum</label>
                                    <input type="text" class="form-control" id="nama_kurikulum_modal"
                                        name="nama_kurikulum" placeholder="Kurikulum: 2020" required>
                                    <span class="text-danger error-text nama_kurikulum_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_kurikulum_modal" class="col-form-label">Tahun Kurikulum</label>
                                    <input type="number" class="form-control" id="tahun_kurikulum_modal"
                                        name="tahun_kurikulum" placeholder="Contoh: 2020" min="2000"
                                        max="{{ date('Y') + 10 }}" required>
                                    <span class="text-danger error-text tahun_kurikulum_error"></span>
                                </div>
                            </div>
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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Perbaiki URL -->
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var kurikulumData = @json($kurikulum ?? []);
            var prodiList = @json($prodi ?? []);

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#kurikulum-table').DataTable({
                data: kurikulumData, // Gunakan data dari PHP
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_prodi',
                        render: function(data, type, row) {
                            // Cari nama prodi berdasarkan ID
                            const prodi = prodiList.find(p => p.id === data);
                            return prodi ? prodi.kode_prodi + ' - ' + prodi.nama_prodi : 'N/A';
                        }
                    },
                    {
                        data: 'nama_kurikulum',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'tahun_kurikulum',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            if (data == 1) return 'Aktif';
                            if (data == 0) return 'Tidak-Aktif';
                            return '-';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
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
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                },
                drawCallback: function(settings) {
                    $('#tableLoader').addClass('hidden');
                }
            });

            // Isi opsi prodi dari data PHP
            fillProdiOptions();

            function fillProdiOptions() {
                const createSelect = $('#id_prodi');
                const editSelect = $('#id_prodi_modal');
                createSelect.empty().append('<option value="">Pilih Prodi...</option>');
                editSelect.empty().append('<option value="">Pilih Prodi...</option>');
                if (prodiList && Array.isArray(prodiList)) {
                    $.each(prodiList, function(index, prodi) {
                        const option =
                            `<option value="${prodi.id}">${prodi.kode_prodi} - ${prodi.nama_prodi}</option>`;
                        createSelect.append(option);
                        editSelect.append(option);
                    });
                }
            }

            // Reset form
            $('#resetBtn').click(function() {
                $('#kurikulumForm')[0].reset();
                $('#kurikulum_id').val('');
                $('.error-text').text('');
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            });

            // Submit form create - Perbaikan untuk menangani checkbox
            $('#kurikulumForm').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                // Ambil data form secara manual
                const formData = $(this).serializeArray();
                // Ambil status checkbox
                const statusChecked = $('#status').is(':checked');
                // Tambahkan status ke formData
                formData.push({
                    name: 'status',
                    value: statusChecked ? 1 : 0
                });

                // Buat object dari array untuk dikirim ke AJAX
                const finalData = {};
                $.each(formData, function(i, field) {
                    finalData[field.name] = field.value;
                });

                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('kurikulum.store') }}",
                    type: 'POST',
                    data: finalData, // Kirim object finalData
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#kurikulumForm')[0].reset();
                                location
                            .reload(); // Refresh untuk menampilkan data baru
                            });
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
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
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
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
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan');
                    }
                });
            });

            // Edit button click - Perbaikan utama di sini
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get("{{ route('kurikulum.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Kurikulum');
                            $('#kurikulum_id_modal').val(data.data.id);
                            $('#id_prodi_modal').val(data.data.id_prodi);
                            $('#nama_kurikulum_modal').val(data.data.nama_kurikulum);
                            $('#tahun_kurikulum_modal').val(data.data.tahun_kurikulum);
                            // Perbaikan: Set checkbox status berdasarkan nilai dari server
                            $('#status_modal').prop('checked', data.data.status == 1);
                            $('.error-text', '#kurikulumFormModal').text(
                            ''); // Bersihkan error di modal
                            $('#modalKurikulum').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data yang Anda cari tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal - Perbaikan untuk menangani checkbox
            $('#kurikulumFormModal').on('submit', function(e) {
                e.preventDefault();
                // Kosongkan semua span error di modal
                $('.error-text', '#kurikulumFormModal').text('');
                const id = $('#kurikulum_id_modal').val();
                // Ambil data form secara manual
                const formData = $(this).serializeArray();
                // Ambil status checkbox dari modal
                const statusChecked = $('#status_modal').is(':checked');
                // Tambahkan status ke formData
                formData.push({
                    name: 'status',
                    value: statusChecked ? 1 : 0
                });

                // Buat object dari array untuk dikirim ke AJAX
                const finalData = {};
                $.each(formData, function(i, field) {
                    finalData[field.name] = field.value;
                });

                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    // Perbaikan: Pastikan URL update benar
                    url: "{{ route('kurikulum.update', '') }}/" + id,
                    type: 'PUT',
                    data: finalData, // Kirim object finalData
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#modalKurikulum').modal('hide');
                                location
                            .reload(); // Refresh untuk menampilkan perubahan
                            });
                        } else {
                            if (response.errors) {
                                // Loop melalui error dan isi span yang sesuai di modal
                                $.each(response.errors, function(key, value) {
                                    // Target span error di dalam form modal berdasarkan class
                                    $('.error-text.' + key + '_error',
                                        '#kurikulumFormModal').text(value[0]);
                                });
                            } else {
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
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Loop melalui error dan isi span yang sesuai di modal
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                // Target span error di dalam form modal berdasarkan class
                                $('.error-text.' + key + '_error',
                                    '#kurikulumFormModal').text(value[0]);
                            });
                        }
                    },
                    complete: function() {
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan');
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
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
                        $('#tableLoader').removeClass('hidden');
                        $.ajax({
                            url: "{{ route('kurikulum.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location
                                    .reload(); // Refresh untuk menampilkan perubahan
                                    });
                                } else {
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
                                let errorMessage = 'Gagal menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            },
                            complete: function() {
                                $('#tableLoader').addClass('hidden');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
