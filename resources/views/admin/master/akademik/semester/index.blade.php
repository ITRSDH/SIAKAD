@extends('admin.layouts.index')
@section('title', 'Semester')
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
            <h3 class="fw-bold mb-3">Semester</h3>
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
                    <a href="{{ route('semester.index') }}">Semester</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('semester.index') }}">List Semester</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12"> <!-- Diubah dari col-md-4 ke col-md-12 untuk ruang horizontal -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseSemesterForm" aria-expanded="true"
                        aria-controls="collapseSemesterForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Semester
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseSemesterForm">
                        <div class="card-body">
                            <form id="semesterForm" name="semesterForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="semester_id">

                                <div class="form-group row mb-3">
                                    <label for="id_tahun_akademik" class="col-sm-3 col-form-label text-end">
                                        Tahun Akademik <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="id_tahun_akademik" name="id_tahun_akademik"
                                            required>
                                            <option value="">Pilih Tahun Akademik...</option>
                                            <!-- Opsi akan diisi oleh AJAX -->
                                        </select>
                                        <small class="form-text text-muted">Pilih tahun akademik tempat semester ini
                                            berada.</small>
                                        <span id="id_tahun_akademik_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="nama_semester_modal" class="col-sm-3 col-form-label text-end">
                                        Nama Semester <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="nama_semester_modal" name="nama_semester" required>
                                            <option value="">Pilih Semester (Ganjil/Genap)...</option>
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                        </select>
                                        <small class="form-text text-muted">Pilih jenis semester.</small>
                                        <span id="nama_semester_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="kode_semester" class="col-sm-3 col-form-label text-end">
                                        Kode Semester <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="kode_semester" name="kode_semester"
                                            required>
                                        <small class="form-text text-muted">Kode unik untuk semester ini (misalnya 20251
                                            untuk Ganjil 2025/2026).</small>
                                        <span id="kode_semester_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="status" class="col-sm-3 col-form-label text-end">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">Pilih Status Semester...</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Akan Datang">Akan Datang</option>
                                        </select>
                                        <small class="form-text text-muted">Status siklus semester (aktif, selesai, atau
                                            akan datang).</small>
                                        <span id="status_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tanggal_mulai" class="col-sm-3 col-form-label text-end">
                                        Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                            required>
                                        <small class="form-text text-muted">Tanggal awal semester dimulai.</small>
                                        <span id="tanggal_mulai_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tanggal_selesai" class="col-sm-3 col-form-label text-end">
                                        Tanggal Selesai <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_selesai"
                                            name="tanggal_selesai" required>
                                        <small class="form-text text-muted">Tanggal akhir semester.</small>
                                        <span id="tanggal_selesai_error" class="text-danger error-text"></span>
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
                            <i class="fas fa-list me-2"></i>Data Semester
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="semester-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>kode Semester</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Tahun Akademik</th>
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
    <div class="modal fade" id="modalSemester" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="semesterFormModal" name="semesterFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="semester_id_modal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_tahun_akademik_modal" class="col-form-label">Tahun Akademik</label>
                                    <select class="form-control" id="id_tahun_akademik_modal" name="id_tahun_akademik"
                                        required>
                                        <option value="">Pilih Tahun Akademik...</option>
                                        <!-- Opsi akan diisi oleh AJAX -->
                                    </select>
                                    <span class="text-danger error-text id_tahun_akademik_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_semester_modal" class="col-form-label">Nama Semester</label>
                                    <select class="form-control" id="nama_semester_modal" name="nama_semester" required>
                                        <option value="Ganjil">Ganjil</option>
                                        <option value="Genap">Genap</option>
                                    </select>
                                    <span class="text-danger error-text nama_semester_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode_semester_modal" class="col-form-label">Kode Semester</label>
                                    <input type="text" class="form-control" id="kode_semester_modal"
                                        name="kode_semester" required>
                                    <span class="text-danger error-text kode_semester_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_modal" class="col-form-label">Status</label>
                                    <select class="form-control" id="status_modal" name="status" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Akan Datang">Akan Datang</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_mulai_modal" class="col-form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai_modal"
                                        name="tanggal_mulai" required>
                                    <span class="text-danger error-text tanggal_mulai_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_selesai_modal" class="col-form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai_modal"
                                        name="tanggal_selesai" required>
                                    <span class="text-danger error-text tanggal_selesai_error"></span>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var semesterData = @json($semester);
            var tahunAkademik = @json($tahunAkademik);

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#semester-table').DataTable({
                data: semesterData, // Gunakan data dari PHP
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
                        data: 'kode_semester'
                    },
                    {
                        data: 'nama_semester'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            // Tambahkan badge berdasarkan status
                            let badgeClass = '';
                            switch (data) {
                                case 'Aktif':
                                    badgeClass = 'badge bg-success';
                                    break;
                                case 'Selesai':
                                    badgeClass = 'badge bg-secondary';
                                    break;
                                case 'Akan Datang':
                                    badgeClass = 'badge bg-warning';
                                    break;
                                default:
                                    badgeClass = 'badge bg-secondary';
                            }
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'id_tahun_akademik',
                        render: function(data, type, row) {
                            // Cari nama tahun akademik berdasarkan ID
                            const ta = tahunAkademik.find(t => t.id === data);
                            return ta ? ta.tahun_akademik : 'N/A';
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

            // Isi opsi tahun akademik dari data PHP
            fillTahunAkademikOptions();

            // Fungsi untuk mengisi opsi tahun akademik
            function fillTahunAkademikOptions() {
                const createSelect = $('#id_tahun_akademik');
                const editSelect = $('#id_tahun_akademik_modal');

                // Kosongkan dropdown
                createSelect.empty().append('<option value="">Pilih Tahun Akademik...</option>');
                editSelect.empty().append('<option value="">Pilih Tahun Akademik...</option>');

                // Isi dengan data dari PHP
                if (tahunAkademik && Array.isArray(tahunAkademik)) {
                    $.each(tahunAkademik, function(index, ta) {
                        const option =
                            `<option value="${ta.id}">${ta.tahun_akademik}</option>`;
                        createSelect.append(option);
                        editSelect.append(option);
                    });
                }
            }

            // Reset form
            $('#resetBtn').click(function() {
                $('#semesterForm')[0].reset();
                $('#semester_id').val('');
                $('.error-text').text('');
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            });

            // Submit form create
            $('#semesterForm').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                const formData = $(this).serialize();
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('semester.store') }}", // Sesuaikan route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#semesterForm')[0]
                                    .reset(); // Reset form setelah sukses
                                location
                                    .reload(); // Reload halaman untuk menampilkan data baru
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
                        console.error('AJAX Error:', xhr);
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

            // Edit button click (gunakan event delegation karena elemen dibuat oleh DataTables)
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                // Ambil data semester spesifik dari API
                $.get("{{ route('semester.show', '') }}/" + id) // Sesuaikan route
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Semester');
                            $('#semester_id_modal').val(data.data.id);
                            $('#id_tahun_akademik_modal').val(data.data.id_tahun_akademik);
                            $('#nama_semester_modal').val(data.data.nama_semester);
                            $('#kode_semester_modal').val(data.data.kode_semester);
                            $('#status_modal').val(data.data.status);
                            $('#tanggal_mulai_modal').val(data.data.tanggal_mulai.split('T')[0]);
                            $('#tanggal_selesai_modal').val(data.data.tanggal_selesai.split('T')[0]);
                            $('.error-text').text('');
                            $('#modalSemester').modal('show');
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
                        console.error('Error fetching data for edit:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal
            $('#semesterFormModal').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                const id = $('#semester_id_modal').val();
                const formData = $(this).serialize();
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('semester.update', '') }}/" +
                        id, // Sesuaikan route
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#modalSemester').modal('hide');
                                location
                                    .reload(); // Reload halaman untuk menampilkan data terbaru
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
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan');
                    }
                });
            });

            // Delete button click (gunakan event delegation)
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data semester ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#tableLoader').removeClass('hidden'); // Tampilkan loader saat menghapus
                        $.ajax({
                            url: "{{ route('semester.destroy', '') }}/" +
                                id, // Sesuaikan route
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
                                            .reload(); // Reload halaman setelah sukses
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
                                console.error('AJAX Error:', xhr);
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
                                $('#tableLoader').addClass(
                                    'hidden'); // Sembunyikan loader setelah selesai
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
