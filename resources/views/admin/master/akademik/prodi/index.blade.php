@extends('admin.layouts.index')
@section('title', 'Program Studi')
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
            <h3 class="fw-bold mb-3">Program Studi</h3>
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
                    <a href="{{ route('prodi.index') }}">Program Studi</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('prodi.index') }}">List Program Studi</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseProdiForm" aria-expanded="true"
                        aria-controls="collapseProdiForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Program Studi
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseProdiForm">
                        <div class="card-body">
                            <form id="prodiForm" name="prodiForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="prodi_id">

                                <div class="form-group row mb-3">
                                    <label for="kode_prodi" class="col-sm-3 col-form-label text-end">
                                        Kode Program Studi <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="kode_prodi" name="kode_prodi"
                                            placeholder="Contoh: D3-ANM, S1-FARMASI" required>
                                        <small class="form-text text-muted">Kode unik untuk Program Studi ini (misalnya
                                            D3-ANM).</small>
                                        <span id="kode_prodi_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="nama_prodi" class="col-sm-3 col-form-label text-end">
                                        Nama Program Studi <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nama_prodi" name="nama_prodi"
                                            placeholder="Contoh: Diploma III Keperawatan, Sarjana Fisioterapi" required>
                                        <small class="form-text text-muted">Nama lengkap dari Program Studi.</small>
                                        <span id="nama_prodi_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="id_jenjang_pendidikan" class="col-sm-3 col-form-label text-end">
                                        Jenjang Pendidikan <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="id_jenjang_pendidikan" name="id_jenjang_pendidikan"
                                            required>
                                            <option value="">Pilih Jenjang Pendidikan...</option>
                                            <!-- Opsi akan diisi oleh AJAX -->
                                        </select>
                                        <small class="form-text text-muted">Pilih tingkat pendidikan jurusan ini (Diploma,
                                            Sarjana, Pascasarjana).</small>
                                        <span id="id_jenjang_pendidikan_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="akreditasi" class="col-sm-3 col-form-label text-end">Akreditasi</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="akreditasi" name="akreditasi">
                                            <option value="">Pilih Nilai Akreditasi...</option>
                                            <option value="A">A (Unggul)</option>
                                            <option value="B">B (Baik)</option>
                                            <option value="C">C (Cukup)</option>
                                            <option value="Unggul">Unggul (Khusus)</option>
                                        </select>
                                        <small class="form-text text-muted">Nilai akreditasi jurusan dari BAN-PT.</small>
                                        <span id="akreditasi_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tahun_berdiri" class="col-sm-3 col-form-label text-end">Tahun
                                        Berdiri</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="tahun_berdiri" name="tahun_berdiri"
                                            placeholder="Contoh: 2010" min="1900" max="{{ date('Y') }}">
                                        <small class="form-text text-muted">Tahun jurusan ini didirikan (antara 1900 dan
                                            {{ date('Y') }}).</small>
                                        <span id="tahun_berdiri_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="kuota" class="col-sm-3 col-form-label text-end">Kuota Mahasiswa</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="kuota" name="kuota"
                                            placeholder="Contoh: 50" min="0">
                                        <small class="form-text text-muted">Jumlah kuota mahasiswa baru per tahun.</small>
                                        <span id="kuota_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="gelar_lulusan" class="col-sm-3 col-form-label text-end">Gelar
                                        Lulusan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="gelar_lulusan"
                                            name="gelar_lulusan" placeholder="Contoh: S.Kep., S.Gz., S.Farm">
                                        <small class="form-text text-muted">Gelar akademik yang diperoleh lulusan jurusan
                                            ini.</small>
                                        <span id="gelar_lulusan_error" class="text-danger error-text"></span>
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
                            <i class="fas fa-list me-2"></i>Data Program Studi
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="prodi-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Jenjang</th>
                                        <th>Akreditasi</th>
                                        <th>Gelar</th>
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
    <div class="modal fade" id="modalProdi" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prodiFormModal" name="prodiFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="prodi_id_modal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode_prodi_modal" class="col-form-label">Kode Program Studi</label>
                                    <input type="text" class="form-control" id="kode_prodi_modal" name="kode_prodi"
                                        required>
                                    <span class="text-danger error-text kode_prodi_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_prodi_modal" class="col-form-label">Nama Program Studi</label>
                                    <input type="text" class="form-control" id="nama_prodi_modal" name="nama_prodi"
                                        required>
                                    <span class="text-danger error-text nama_prodi_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_jenjang_pendidikan_modal" class="col-form-label">Jenjang
                                        Pendidikan</label>
                                    <select class="form-control" id="id_jenjang_pendidikan_modal"
                                        name="id_jenjang_pendidikan" required>
                                        <option value="">Pilih Jenjang...</option>
                                        <!-- Opsi akan diisi oleh AJAX -->
                                    </select>
                                    <span class="text-danger error-text id_jenjang_pendidikan_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="akreditasi_modal" class="col-form-label">Akreditasi</label>
                                    <select class="form-control" id="akreditasi_modal" name="akreditasi">
                                        <option value="">Pilih Akreditasi...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="Unggul">Unggul</option>
                                    </select>
                                    <span class="text-danger error-text akreditasi_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_berdiri_modal" class="col-form-label">Tahun Berdiri</label>
                                    <input type="number" class="form-control" id="tahun_berdiri_modal"
                                        name="tahun_berdiri" min="1900" max="{{ date('Y') }}">
                                    <span class="text-danger error-text tahun_berdiri_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kuota_modal" class="col-form-label">Kuota</label>
                                    <input type="number" class="form-control" id="kuota_modal" name="kuota"
                                        min="0">
                                    <span class="text-danger error-text kuota_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="gelar_lulusan_modal" class="col-form-label">Gelar Lulusan</label>
                            <input type="text" class="form-control" id="gelar_lulusan_modal" name="gelar_lulusan">
                            <span class="text-danger error-text gelar_lulusan_error"></span>
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
            var prodiData = @json($prodi);
            var jenjangPendidikan = @json($jenjangPendidikan);

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#prodi-table').DataTable({
                data: prodiData, // Gunakan data dari PHP
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
                        data: 'kode_prodi'
                    },
                    {
                        data: 'nama_prodi'
                    },
                    {
                        data: 'id_jenjang_pendidikan',
                        render: function(data, type, row) {
                            // Cari nama jenjang berdasarkan ID
                            const jenjang = jenjangPendidikan.find(j => j.id === data);
                            return jenjang ? jenjang.nama_jenjang : 'N/A';
                        }
                    },
                    {
                        data: 'akreditasi',
                        render: function(data, type, row) {
                            // Tampilkan '-' jika akreditasi kosong
                            return data || '-';
                        }
                    },
                    {
                        data: 'gelar_lulusan'
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

            // Isi opsi jenjang pendidikan dari data PHP
            fillJenjangOptions();

            // Fungsi untuk mengisi opsi jenjang pendidikan
            function fillJenjangOptions() {
                const createSelect = $('#id_jenjang_pendidikan');
                const editSelect = $('#id_jenjang_pendidikan_modal');

                // Kosongkan dropdown
                createSelect.empty().append('<option value="">Pilih Jenjang...</option>');
                editSelect.empty().append('<option value="">Pilih Jenjang...</option>');

                // Isi dengan data dari PHP
                if (jenjangPendidikan && Array.isArray(jenjangPendidikan)) {
                    $.each(jenjangPendidikan, function(index, jenjang) {
                        const option =
                            `<option value="${jenjang.id}">${jenjang.kode_jenjang} - ${jenjang.nama_jenjang}</option>`;
                        createSelect.append(option);
                        editSelect.append(option);
                    });
                }
            }

            // Reset form
            $('#resetBtn').click(function() {
                $('#prodiForm')[0].reset();
                $('#prodi_id').val('');
                $('.error-text').text('');
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            });

            // Submit form create
            $('#prodiForm').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                const formData = $(this).serialize();
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('prodi.store') }}",
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
                                $('#prodiForm')[0].reset(); // Reset form setelah sukses
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
                // Ambil data prodi spesifik dari API
                $.get("{{ route('prodi.show', '') }}/" + id) // Pastikan route ini sesuai
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Program Studi');
                            $('#prodi_id_modal').val(data.data.id);
                            $('#kode_prodi_modal').val(data.data.kode_prodi);
                            $('#nama_prodi_modal').val(data.data.nama_prodi);
                            $('#id_jenjang_pendidikan_modal').val(data.data.id_jenjang_pendidikan);
                            $('#akreditasi_modal').val(data.data.akreditasi);
                            $('#tahun_berdiri_modal').val(data.data.tahun_berdiri);
                            $('#kuota_modal').val(data.data.kuota);
                            $('#gelar_lulusan_modal').val(data.data.gelar_lulusan);
                            $('.error-text').text('');
                            $('#modalProdi').modal('show');
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
            $('#prodiFormModal').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                const id = $('#prodi_id_modal').val();
                const formData = $(this).serialize();
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('prodi.update', '') }}/" + id,
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
                                $('#modalProdi').modal('hide');
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
                    text: "Data ini akan dihapus secara permanen!",
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
                            url: "{{ route('prodi.destroy', '') }}/" + id,
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
