@extends('layouts.index')
@section('title', $pageTitle ?? 'Program Studi')
@push('styles-custom')
    <style>
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: inherit;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .loader-overlay.hidden {
            display: none;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
        }

        .error-text {
            font-size: 0.875em;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 1.5rem;
            padding-left: 0;
            padding-right: 1.5rem;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
            right: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">{{ $pageHeading ?? ($pageTitle ?? 'Program Studi') }}</h3>
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
                    <a
                        href="{{ request()->routeIs('aktor-akademik.*') ? route('aktor-akademik.kaprodi') : route('prodi.index') }}">{{ $pageTitle ?? 'Program Studi' }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ request()->routeIs('aktor-akademik.*') ? route('aktor-akademik.kaprodi') : route('prodi.index') }}">{{ $pageCrumbLabel ?? 'List Program Studi' }}</a>
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
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <div class="collapse show" id="collapseProdiForm">
                        <div class="card-body">
                            @if (!empty($pageDescription))
                                <div class="alert alert-info">{{ $pageDescription }}</div>
                            @endif

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
                                    <label for="jenjang_pendidikan" class="col-sm-3 col-form-label text-end">
                                        Jenjang Penididikan <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="jenjang_pendidikan"
                                            name="jenjang_pendidikan" placeholder="Contoh: D3, S1, S2, S3" required>
                                        <small class="form-text text-muted">Jenjang pendidikan dari Program Studi.</small>
                                        <span id="jenjang_pendidikan_error" class="text-danger error-text"></span>
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
                                        <th>Jenjang</th>
                                        <th>Nama</th>
                                        <th>Akreditasi</th>
                                        <th>Gelar</th>
                                        <th>Kaprodi</th> <!-- 🔥 Kolom baru -->
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
    <div class="modal fade" id="modalProdi" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data"
                                type="button" role="tab">Data Prodi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="kaprodi-tab" data-bs-toggle="tab" data-bs-target="#kaprodi"
                                type="button" role="tab">Kaprodi</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="myTabContent">
                        <!-- Tab Data Prodi -->
                        <div class="tab-pane fade show active" id="data" role="tabpanel">
                            <form id="prodiFormModal" name="prodiFormModal" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="prodi_id_modal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="kode_prodi_modal" class="col-form-label">Kode Program
                                                Studi</label>
                                            <input type="text" class="form-control" id="kode_prodi_modal"
                                                name="kode_prodi" required>
                                            <span class="text-danger error-text kode_prodi_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nama_prodi_modal" class="col-form-label">Nama Program
                                                Studi</label>
                                            <input type="text" class="form-control" id="nama_prodi_modal"
                                                name="nama_prodi" required>
                                            <span class="text-danger error-text nama_prodi_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="jenjang_pendidikan_modal" class="col-form-label">Jenjang
                                                Pendidikan</label>
                                            <input type="text" class="form-control" id="jenjang_pendidikan_modal"
                                                name="jenjang_pendidikan" required>
                                            <span class="text-danger error-text jenjang_pendidikan_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="akreditasi_modal" class="col-form-label">Akreditasi</label>
                                            <select class="form-control select2" id="akreditasi_modal" name="akreditasi">
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
                                            <label for="gelar_lulusan_modal" class="col-form-label">Gelar Lulusan</label>
                                            <input type="text" class="form-control" id="gelar_lulusan_modal"
                                                name="gelar_lulusan">
                                            <span class="text-danger error-text gelar_lulusan_error"></span>
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

                        <!-- Tab Kaprodi -->
                        <div class="tab-pane fade" id="kaprodi" role="tabpanel">
                            <form id="kaprodiFormModal" name="kaprodiFormModal" class="form-horizontal">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="nama_prodi_kaprodi_modal" class="col-form-label">Nama Program
                                        Studi</label>
                                    <input type="text" class="form-control" id="nama_prodi_kaprodi_modal" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="id_kaprodi_modal" class="col-form-label">Pilih Kaprodi</label>
                                    <select class="form-control select2" id="id_kaprodi_modal" name="id_kaprodi">
                                        <option value="">-- Pilih Dosen --</option>
                                    </select>
                                    <span class="text-danger error-text id_kaprodi_error"></span>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-success" id="saveKaprodiBtn">
                                        <i class="fas fa-save"></i> Simpan Kaprodi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari PHP
            var prodiData = @json($prodi);
            function formatDosenOption(dosen) {
                const namaDosen = dosen?.nama_dosen || 'Dosen';
                const nidn = dosen?.nidn || dosen?.nup || 'NIDN belum tersedia';

                return `${namaDosen} (${nidn})`;
            }

            function initializeSelect2() {
                $('#akreditasi_modal').select2({
                    width: '100%',
                    dropdownParent: $('#modalProdi'),
                    placeholder: 'Pilih Akreditasi...'
                });

                $('#id_kaprodi_modal').select2({
                    width: '100%',
                    dropdownParent: $('#modalProdi'),
                    placeholder: '-- Pilih Dosen --',
                    allowClear: true,
                    matcher: function(params, data) {
                        const keyword = $.trim(params.term || '').toLowerCase();

                        if (keyword === '') {
                            return data;
                        }

                        const text = String(data.text || '').toLowerCase();
                        return text.includes(keyword) ? data : null;
                    }
                });
            }
            var dosenList = @json($dosenList); // 🔥 Ditambahkan

            // Isi dropdown kaprodi
            function fillDosenOptions() {
                const select = $('#id_kaprodi_modal');
                select.empty().append('<option value="">-- Pilih Dosen --</option>');
                if (dosenList && Array.isArray(dosenList)) {
                    $.each(dosenList, function(index, dosen) {
                        select.append(
                            `<option value="${dosen.id}">${formatDosenOption(dosen)}</option>`);
                    });
                }

                select.trigger('change.select2');
            }

            initializeSelect2();

            function populateKaprodiTab(prodi) {
                fillDosenOptions();
                $('#nama_prodi_kaprodi_modal').val(prodi?.nama_prodi || '');
                $('#id_kaprodi_modal').val(prodi?.id_kaprodi || '').trigger('change');
            }

            // DataTables
            var table = $('#prodi-table').DataTable({
                data: prodiData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_prodi'
                    },
                    {
                        data: 'jenjang_pendidikan',
                    },
                    {
                        data: 'nama_prodi'
                    },
                    {
                        data: 'akreditasi',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'gelar_lulusan'
                    },
                    {
                        data: 'id_kaprodi',
                        render: function(data, type, row) {
                            if (!row.kaprodi) {
                                return '-';
                            }

                            const identifier = row.kaprodi.identifier || row.kaprodi.nidn || row.kaprodi.nup ||
                                'NIDN/NUP belum tersedia';

                            return `
                                <div class="fw-semibold">${row.kaprodi.nama_dosen || '-'}</div>
                                <small class="text-muted">${identifier}</small>
                            `;
                        }
                    }, // 🔥 Kolom kaprodi
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${data.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
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
                drawCallback: function() {
                    $('#tableLoader').addClass('hidden');
                }
            });

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
                                })
                                .then(() => location.reload());
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) errorMessage = xhr
                            .responseJSON.message;
                        else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan');
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get("{{ route('prodi.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Program Studi');
                            $('#prodi_id_modal').val(data.data.id);
                            $('#kode_prodi_modal').val(data.data.kode_prodi);
                            $('#nama_prodi_modal').val(data.data.nama_prodi);
                            $('#jenjang_pendidikan_modal').val(data.data.jenjang_pendidikan);
                            $('#akreditasi_modal').val(data.data.akreditasi);
                            $('#tahun_berdiri_modal').val(data.data.tahun_berdiri);
                            $('#gelar_lulusan_modal').val(data.data.gelar_lulusan);
                            populateKaprodiTab(data.data);
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
                                })
                                .then(() => location.reload());
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) errorMessage = xhr
                            .responseJSON.message;
                        else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
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
                                        })
                                        .then(() => location.reload());
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
                                if (xhr.responseJSON && xhr.responseJSON.message)
                                    errorMessage = xhr.responseJSON.message;
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

            // Load dosen options saat tab kaprodi aktif
            $(document).on('shown.bs.tab', 'button[data-bs-target="#kaprodi"]', function() {
                const id = $('#prodi_id_modal').val();
                if (id) {
                    $.get("{{ route('prodi.show', '') }}/" + id)
                        .done(function(response) {
                            if (response.success && response.data) {
                                populateKaprodiTab(response.data);
                            }
                        });
                }
            });

            // Simpan kaprodi
            $('#kaprodiFormModal').on('submit', function(e) {
                e.preventDefault();
                const id = $('#prodi_id_modal').val();
                const formData = $(this).serialize();
                $('#saveKaprodiBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('prodi.updateKaprodi', ':id') }}".replace(':id', id),
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                })
                                .then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Gagal menyimpan kaprodi.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.errors.error ||
                            'Gagal menyimpan kaprodi.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: message,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
