@extends('layouts.index')
@section('title', 'Mahasiswa Management')

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
            z-index: 5;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #007bff;
            border-top: 4px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none !important;
        }

        .modal-xxl {
            max-width: 95% !important;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Mahasiswa Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Data Mahasiswa</h3>
                        <div class="d-flex gap-2">
                            <button id="importMahasiswaBtn" class="btn btn-success btn-sm">
                                <i class="fas fa-file-import me-1"></i> Import/Template
                            </button>
                            <button id="addMahasiswaBtn" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="mahasiswa-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Status</th>
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

    {{-- Modal Create/Edit --}}
    <div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="mahasiswaForm">
                        @csrf
                        <input type="hidden" id="mahasiswaId">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nama Mahasiswa</label>
                                <input type="text" id="nama_mahasiswa" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>NIM</label>
                                <input type="text" id="nim" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>NIK</label>
                                <input type="text" id="nik" class="form-control" max="16" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Password</label>
                                <input type="password" id="password" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Program Studi</label>
                                <select id="id_prodi" class="form-control" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Kurikulum Aktif</label>
                                <select id="id_kurikulum" class="form-control">
                                    <option value="">-- Biarkan Sistem Menentukan --</option>
                                </select>
                                <small class="text-muted">Opsional. Jika dikosongkan, sistem akan menentukan kurikulum aktif berdasarkan program studi, angkatan, tanggal masuk, atau riwayat kurikulum aktif.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Jenis Kelamin</label>
                                <select id="jenis_kelamin" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Alamat</label>
                                <textarea id="alamat" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Agama</label>
                                <select id="agama" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select id="status" class="form-control" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="DO">Drop Out</option>
                                    <option value="Lulus">Lulus</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Angkatan</label>
                                <input type="number" id="angkatan" class="form-control" min="1990"
                                    max="{{ date('Y') + 10 }}" required>
                            </div>

                            <div class="col-12">
                                <small class="text-muted">
                                    Jika kurikulum aktif dikosongkan, sistem akan menentukan otomatis berdasarkan prodi,
                                    angkatan, tanggal masuk, semester masuk, jenis intake, dan semester akademik aktif.
                                </small>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-1"></i>Batal</button>
                    <button type="button" class="btn btn-primary" id="submitMahasiswaBtn"><i
                            class="fas fa-save me-1"></i>Simpan</button>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="importMahasiswaModal" tabindex="-1" aria-labelledby="importMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <form id="importMahasiswaForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importMahasiswaModalLabel">
                            <i class="fas fa-file-import me-2"></i>Import Data Mahasiswa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Petunjuk Import:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pilih Program Studi terlebih dahulu</li>
                                <li>Download template dengan klik tombol "Download Template"</li>
                                <li>Isi data sesuai format yang tersedia</li>
                                <li>File yang diperbolehkan: .xlsx, .xls, .csv (maksimal 10MB)</li>
                                <li>Pada Saat Menggunakan Fitur ini, Akan Otomatis Membuatkan Akun User Mahasiswa dengan
                                    Password Default "Tanggal Lahir Mahasiswa" dengan format tanggal-bulan-tahun (contoh:
                                    01122000)</li>
                                <li>Bisa Dihimbau Mahasiswa Untuk Mengganti Password nya setelah pertama kali login</li>
                            </ul>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="importProdi" class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>Program Studi
                                </label>
                                <select id="importProdi" class="form-control" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" id="downloadTemplateBtn" class="btn btn-info w-100" disabled>
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="importFile" class="form-label">
                                <i class="fas fa-file-excel me-1"></i>Pilih File Import
                            </label>
                            <input type="file" class="form-control" id="importFile" name="file"
                                accept=".xlsx,.xls,.csv" disabled>
                            <div class="form-text">
                                Format file: .xlsx, .xls, .csv (maksimal 10MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-eye me-1"></i>Preview Data
                            </label>
                            <div id="importPreview" class="border rounded p-3 bg-light"
                                style="max-height: 200px; overflow-y: auto;">
                                <p class="text-muted text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Pilih Program Studi terlebih dahulu
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success" id="submitImportBtn">
                            <i class="fas fa-upload me-1"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Hasil Import --}}
    <div class="modal fade" id="importResultModal" tabindex="-1" aria-labelledby="importResultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importResultModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Hasil Import
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="importResultContent">
                        <!-- Content akan diisi via JavaScript -->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-check me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="riwayatKurikulumModal" tabindex="-1" aria-labelledby="riwayatKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="riwayatKurikulumModalLabel">
                        <i class="fas fa-history me-2"></i>Riwayat Kurikulum Mahasiswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="riwayatKurikulumContent">
                        <p class="text-muted text-center mb-0">Memuat data riwayat kurikulum...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="migrasiKurikulumModal" tabindex="-1" aria-labelledby="migrasiKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="migrasiKurikulumForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="migrasiKurikulumModalLabel">
                            <i class="fas fa-exchange-alt me-2"></i>Migrasi Kurikulum Mahasiswa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="migrasiMahasiswaId">
                        <div class="alert alert-warning">
                            Migrasi kurikulum akan menutup kurikulum aktif lama dan membuka riwayat kurikulum baru.
                            Pastikan aturan konversi mata kuliah sudah disiapkan terlebih dahulu bila diperlukan.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mahasiswa</label>
                            <input type="text" id="migrasiMahasiswaLabel" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kurikulum Aktif Saat Ini</label>
                            <input type="text" id="migrasiCurrentKurikulumLabel" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kurikulum Tujuan</label>
                            <select id="migrasi_id_kurikulum_tujuan" class="form-control" required>
                                <option value="">-- Pilih Kurikulum Tujuan --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai Berlaku</label>
                            <input type="date" id="migrasi_tanggal_mulai" class="form-control">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Catatan Migrasi</label>
                            <textarea id="migrasi_catatan" class="form-control" rows="3"
                                placeholder="Contoh: Migrasi kurikulum karena perubahan kurikulum angkatan 2023"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitMigrasiBtn">
                            <i class="fas fa-save me-1"></i>Proses Migrasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {

            const modal = new bootstrap.Modal('#mahasiswaModal');
            const importModal = new bootstrap.Modal('#importMahasiswaModal');
            const resultModal = new bootstrap.Modal('#importResultModal');
            const riwayatModal = new bootstrap.Modal('#riwayatKurikulumModal');
            const migrasiModal = new bootstrap.Modal('#migrasiKurikulumModal');
            const mahasiswa = @json($mahasiswa ?? []);
            const kurikulumList = @json($kurikulum ?? []);

            const table = $('#mahasiswa-table').DataTable({
                data: mahasiswa,
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'nama_mahasiswa'
                    },
                    {
                        data: 'nim'
                    },
                    {
                        data: null,
                        render: row => row.user?.email ?? '-'
                    },
                    {
                        data: null,
                        render: row => row.prodi?.nama_prodi ?? '-'
                    },
                    {
                        data: 'status',
                        render: status => {
                            const badgeClass = {
                                'Aktif': 'success',
                                'Cuti': 'warning',
                                'DO': 'danger',
                                'Lulus': 'info'
                            } [status] || 'secondary';
                            return `<span class="badge bg-${badgeClass}">${status}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: row => `
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-info btn-sm history-btn" data-id="${row.id}" title="Riwayat Kurikulum">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm migrate-btn" data-id="${row.id}" title="Migrasi Kurikulum">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>`
                    }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                },
                drawCallback: () => $('#tableLoader').addClass('hidden')
            });

            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);

            // Tambah
            $('#addMahasiswaBtn').click(() => {
                $('#mahasiswaForm')[0].reset();
                clearFormErrors('#mahasiswaForm');
                $('#mahasiswaId').val('');
                $('#modalTitle').text('Tambah Mahasiswa');
                populateKurikulumOptions('', '');
                // Reset password field untuk tambah data (wajib diisi)
                $('#password').prop('required', true).attr('placeholder', '');
                modal.show();
            });

            function formatKurikulumLabel(item) {
                const tahun = item?.semester_mulai?.tahun_akademik?.tahun_akademik ?? '';
                const semester = item?.semester_mulai?.nama_semester ?? '';
                const kode = item?.kode_kurikulum ?? '';
                const nama = item?.nama_kurikulum ?? 'Kurikulum';
                const parts = [kode, nama, tahun, semester].filter(Boolean);

                return parts.join(' | ');
            }

            function getActiveKurikulumId(row = {}) {
                if (row?.id_kurikulum) {
                    return row.id_kurikulum;
                }

                const activeHistory = (row?.riwayat_kurikulum || []).find(item => item?.is_active);

                return activeHistory?.id_kurikulum || '';
            }

            function populateKurikulumOptions(prodiId, selectedId = '') {
                const select = $('#id_kurikulum');
                select.empty().append('<option value="">-- Biarkan Sistem Menentukan --</option>');

                if (!prodiId) {
                    return;
                }

                const filtered = kurikulumList.filter(item => item.id_prodi === prodiId);

                filtered.forEach(item => {
                    select.append(
                        `<option value="${item.id}">${formatKurikulumLabel(item)}</option>`
                    );
                });

                if (selectedId && filtered.some(item => item.id === selectedId)) {
                    select.val(selectedId);
                    return;
                }
            }

            function clearFormErrors(formSelector) {
                const form = $(formSelector);
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback.dynamic-error').remove();
            }

            function applyFormErrors(fieldMap, errors = {}) {
                Object.entries(errors).forEach(([field, messages]) => {
                    const selector = fieldMap[field];
                    if (!selector) {
                        return;
                    }

                    const input = $(selector);
                    if (!input.length) {
                        return;
                    }

                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback.dynamic-error').remove();
                    input.after(
                        `<div class="invalid-feedback dynamic-error">${Array.isArray(messages) ? messages[0] : messages}</div>`
                    );
                });
            }

            $('#id_prodi').on('change', function() {
                populateKurikulumOptions($(this).val(), '');
            });

            function renderRiwayatKurikulum(items = []) {
                if (!items.length) {
                    return `
                        <div class="text-center text-muted py-4">
                            Belum ada riwayat kurikulum untuk mahasiswa ini.
                        </div>
                    `;
                }

                return `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kurikulum</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${items.map(item => {
                                    const kurikulum = item.kurikulum || {};
                                    const periode = [item.tanggal_mulai || '-', item.tanggal_selesai || 'sekarang'].join(' s.d. ');
                                    const badge = item.is_active ? 'success' : 'secondary';
                                    const tahun = kurikulum.semester_mulai?.tahun_akademik?.tahun_akademik || '';
                                    const semester = kurikulum.semester_mulai?.nama_semester || '';
                                    const label = [kurikulum.kode_kurikulum, kurikulum.nama_kurikulum, tahun, semester].filter(Boolean).join(' | ');

                                    return `
                                        <tr>
                                            <td>${label || '-'}</td>
                                            <td>${periode}</td>
                                            <td><span class="badge bg-${badge}">${item.is_active ? 'Aktif' : 'Riwayat'}</span></td>
                                            <td>${item.catatan || '-'}</td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            // Simpan / Update
            $('#submitMahasiswaBtn').on('click', function(e) {
                e.preventDefault();

                const id = $('#mahasiswaId').val();
                const url = id ?
                    "{{ route('mahasiswa.update', ':id') }}".replace(':id', id) :
                    "{{ route('mahasiswa.store') }}";
                const method = 'POST';

                const submitBtn = $(this);
                const originalHtml = submitBtn.html();
                clearFormErrors('#mahasiswaForm');

                // Disable button dan tampilkan loading
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');

                $.ajax({
                    url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        ...(id ? {
                            _method: 'PUT'
                        } : {}),
                        nim: $('#nim').val(),
                        nik: $('#nik').val(),
                        nama_mahasiswa: $('#nama_mahasiswa').val(),
                        id_prodi: $('#id_prodi').val(),
                        id_kurikulum: $('#id_kurikulum').val(),
                        jenis_kelamin: $('#jenis_kelamin').val(),
                        tempat_lahir: $('#tempat_lahir').val(),
                        tanggal_lahir: $('#tanggal_lahir').val(),
                        tanggal_masuk: $('#tanggal_masuk').val(),
                        alamat: $('#alamat').val(),
                        agama: $('#agama').val(),
                        status: $('#status').val(),
                        angkatan: $('#angkatan').val(),
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: res => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message ?? 'Data disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        modal.hide();
                        location.reload();
                    },
                    error: err => {
                        const response = err.responseJSON || {};
                        const backendErrors = response.errors?.errors || response.errors || {};
                        applyFormErrors({
                            nim: '#nim',
                            nik: '#nik',
                            nama_mahasiswa: '#nama_mahasiswa',
                            id_prodi: '#id_prodi',
                            id_kurikulum: '#id_kurikulum',
                            jenis_kelamin: '#jenis_kelamin',
                            tempat_lahir: '#tempat_lahir',
                            tanggal_lahir: '#tanggal_lahir',
                            tanggal_masuk: '#tanggal_masuk',
                            alamat: '#alamat',
                            agama: '#agama',
                            status: '#status',
                            angkatan: '#angkatan',
                            email: '#email',
                            password: '#password'
                        }, backendErrors);
                        Swal.fire('Gagal', response.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
                    },
                    complete: () => {
                        // Enable button kembali
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.show', ':id') }}".replace(':id', id);

                $.get(url, res => {
                    clearFormErrors('#mahasiswaForm');
                    const m = res.data ?? res;
                    $('#mahasiswaId').val(m.id);
                    $('#nama_mahasiswa').val(m.nama_mahasiswa);
                    $('#email').val(m.user?.email ?? '');
                    $('#nim').val(m.nim);
                    $('#nik').val(m.nik);
                    $('#id_prodi').val(m.id_prodi);
                    populateKurikulumOptions(m.id_prodi, getActiveKurikulumId(m));
                    $('#jenis_kelamin').val(m.jenis_kelamin);
                    $('#tempat_lahir').val(m.tempat_lahir);
                    $('#tanggal_lahir').val(m.tanggal_lahir?.split('T')[0] ?? '');
                    $('#tanggal_masuk').val(m.tanggal_masuk?.split('T')[0] ?? '');
                    $('#alamat').val(m.alamat);
                    $('#agama').val(m.agama);
                    $('#status').val(m.status);
                    $('#angkatan').val(m.angkatan);

                    // Untuk edit, password tidak wajib diisi
                    $('#password').prop('required', false).attr('placeholder',
                        'Kosongkan jika tidak ingin mengubah password');

                    $('#modalTitle').text('Edit Mahasiswa');
                    modal.show();

                }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data', 'error'));
            });

            $(document).on('click', '.history-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.riwayat-kurikulum', ':id') }}".replace(':id', id);

                $('#riwayatKurikulumContent').html('<p class="text-muted text-center mb-0">Memuat data riwayat kurikulum...</p>');
                riwayatModal.show();

                $.get(url, res => {
                    const payload = res.data || {};
                    const mahasiswaData = payload.mahasiswa || {};
                    const riwayat = payload.riwayat_kurikulum || [];
                    $('#riwayatKurikulumModalLabel').html(
                        `<i class="fas fa-history me-2"></i>Riwayat Kurikulum - ${mahasiswaData.nama_mahasiswa || 'Mahasiswa'}`
                    );
                    $('#riwayatKurikulumContent').html(renderRiwayatKurikulum(riwayat));
                }).fail(xhr => {
                    $('#riwayatKurikulumContent').html(
                        `<div class="alert alert-danger mb-0">${xhr.responseJSON?.message || 'Gagal memuat riwayat kurikulum.'}</div>`
                    );
                });
            });

            $(document).on('click', '.migrate-btn', function() {
                const id = $(this).data('id');
                const row = mahasiswa.find(item => item.id === id);
                const prodiId = row?.id_prodi || '';
                const currentKurikulumId = getActiveKurikulumId(row);

                $('#migrasiKurikulumForm')[0].reset();
                clearFormErrors('#migrasiKurikulumForm');
                $('#migrasiMahasiswaId').val(id);
                $('#migrasiMahasiswaLabel').val(`${row?.nama_mahasiswa || '-'} (${row?.nim || '-'})`);
                $('#migrasiCurrentKurikulumLabel').val(
                    formatKurikulumLabel(
                        kurikulumList.find(item => item.id === currentKurikulumId) || {}
                    ) || 'Belum terdeteksi'
                );
                $('#migrasi_tanggal_mulai').val(new Date().toISOString().split('T')[0]);

                const select = $('#migrasi_id_kurikulum_tujuan');
                const submitBtn = $('#submitMigrasiBtn');
                select.empty().append('<option value="">-- Pilih Kurikulum Tujuan --</option>');
                const kandidatKurikulum = kurikulumList
                    .filter(item => item.id_prodi === prodiId && item.id !== currentKurikulumId);
                kandidatKurikulum.forEach(item => {
                    select.append(`<option value="${item.id}">${formatKurikulumLabel(item)}</option>`);
                });

                if (!kandidatKurikulum.length) {
                    select.append('<option value="">Tidak ada kurikulum tujuan yang tersedia</option>');
                    submitBtn.prop('disabled', true);
                } else {
                    submitBtn.prop('disabled', false);
                }

                migrasiModal.show();
            });

            $('#migrasiKurikulumForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#migrasiMahasiswaId').val();
                const submitBtn = $('#submitMigrasiBtn');
                const originalHtml = submitBtn.html();
                clearFormErrors('#migrasiKurikulumForm');

                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Memproses...');

                $.ajax({
                    url: "{{ route('mahasiswa.migrasi-kurikulum', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_kurikulum_tujuan: $('#migrasi_id_kurikulum_tujuan').val(),
                        tanggal_mulai: $('#migrasi_tanggal_mulai').val(),
                        catatan: $('#migrasi_catatan').val()
                    },
                    success: res => {
                        Swal.fire('Berhasil', res.message || 'Migrasi kurikulum berhasil diproses.', 'success');
                        migrasiModal.hide();
                        location.reload();
                    },
                    error: xhr => {
                        const response = xhr.responseJSON || {};
                        const backendErrors = response.errors?.errors || response.errors || {};
                        applyFormErrors({
                            id_kurikulum_tujuan: '#migrasi_id_kurikulum_tujuan',
                            tanggal_mulai: '#migrasi_tanggal_mulai',
                            catatan: '#migrasi_catatan'
                        }, backendErrors);
                        const message = response.message || 'Migrasi kurikulum gagal diproses.';
                        Swal.fire('Gagal', message, 'error');
                    },
                    complete: () => {
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Hapus
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Hapus mahasiswa ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: res => {
                                Swal.fire('Berhasil', res.message ?? 'Data dihapus',
                                    'success');
                                table.rows((i, d) => d.id === id).remove().draw();
                            },
                            error: () => Swal.fire('Gagal', 'Tidak dapat menghapus data.',
                                'error')
                        });
                    }
                });
            });

            // Import Mahasiswa
            $('#importMahasiswaBtn').click(() => {
                $('#importMahasiswaForm')[0].reset();
                $('#importProdi').val('').trigger('change');
                $('#importPreview').html(`
                                    <p class="text-muted text-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pilih Program Studi terlebih dahulu
                                    </p>
                                `);
                importModal.show();
            });

            // Handle pemilihan prodi
            $('#importProdi').on('change', function() {
                const prodiId = $(this).val();
                const downloadBtn = $('#downloadTemplateBtn');
                const fileInput = $('#importFile');
                const preview = $('#importPreview');

                if (prodiId) {
                    // Enable download template button
                    downloadBtn.prop('disabled', false).html(`
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    `);

                    // Enable file input
                    fileInput.prop('disabled', false);

                    // Update preview
                    preview.html(`
                                        <p class="text-muted text-center">
                                            <i class="fas fa-check-circle me-2 text-success"></i>
                                            Program Studi dipilih. Silakan download template atau pilih file import.
                                        </p>
                                    `);
                } else {
                    // Disable buttons
                    downloadBtn.prop('disabled', true).html(`
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    `);
                    fileInput.prop('disabled', true);

                    // Reset preview
                    preview.html(`
                                        <p class="text-muted text-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Pilih Program Studi terlebih dahulu
                                        </p>
                                    `);
                }
            });

            // Download template
            $('#downloadTemplateBtn').on('click', function() {
                const prodiId = $('#importProdi').val();

                if (!prodiId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pilih Program Studi terlebih dahulu!'
                    });
                    return;
                }

                // Show loading
                const btn = $(this);
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Mengunduh...');

                // Download template
                const templateUrl = `/mahasiswa/export/template/${prodiId}`;
                window.open(templateUrl, '_blank');

                // Reset button after 1 second
                setTimeout(() => {
                    btn.prop('disabled', false).html(originalHtml);
                }, 1000);
            });

            // File preview saat file dipilih
            $('#importFile').on('change', function() {
                const file = this.files[0];
                const preview = $('#importPreview');

                if (file) {
                    // Validasi file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        preview.html(
                            '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Ukuran file terlalu besar! Maksimal 10MB</div>'
                        );
                        $(this).val('');
                        return;
                    }

                    // Validasi file type
                    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv'
                    ];
                    if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                        preview.html(
                            '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Format file tidak didukung! Gunakan .xlsx, .xls, atau .csv</div>'
                        );
                        $(this).val('');
                        return;
                    }

                    // Tampilkan info file
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    preview.html(`
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>File Siap Diimport:</strong><br>
                                            Nama: ${file.name}<br>
                                            Ukuran: ${fileSize} MB<br>
                                            Tipe: ${file.type || 'Unknown'}
                                        </div>
                                    `);
                } else {
                    preview.html('<p class="text-muted text-center">Belum ada file yang dipilih</p>');
                }
            });

            // Submit import form
            $('#importMahasiswaForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $('#submitImportBtn');

                // Disable button dan tampilkan loading
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Mengimport...');

                $.ajax({
                    url: "{{ route('mahasiswa.import', ':id_prodi') }}".replace(':id_prodi', $(
                        '#importProdi').val()),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            // Tampilkan hasil import
                            showImportResult(res.data.data);
                            importModal.hide();
                            resultModal.show();

                            // Tangani event ketika modal ditutup (misalnya dengan tombol close atau klik di luar modal)
                            $(resultModal._element).one('hidden.bs.modal', function() {
                                // Refresh halaman ketika modal ditutup
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Import Gagal',
                                text: res.message || 'Terjadi kesalahan saat import',
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat import';

                        if (xhr.responseJSON) {
                            const errors = xhr.responseJSON.errors;
                            if (errors && Object.keys(errors).length > 0) {
                                errorMessage = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Import Gagal',
                            html: errorMessage,
                            timer: 5000
                        });
                    },
                    complete: function() {
                        // Enable button kembali
                        submitBtn.prop('disabled', false).html(
                            '<i class="fas fa-upload me-1"></i>Import Data');
                    }
                });
            });

            // Fungsi untuk menampilkan hasil import
            function showImportResult(data) {
                const resultContent = $('#importResultContent');

                let html = `
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">${data.total_rows || 0}</h5>
                                                    <p class="card-text">Total Data</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-success">${data.success_count || 0}</h5>
                                                    <p class="card-text">Berhasil</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-danger">${data.error_count || 0}</h5>
                                                    <p class="card-text">Gagal</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                // Tampilkan error jika ada
                if (data.errors && data.errors.length > 0) {
                    html += `
                                        <div class="mt-3">
                                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Detail Error:</h6>
                                            <div class="alert alert-warning" style="max-height: 300px; overflow-y: auto;">
                                                <ul class="mb-0">
                                                    ${data.errors.map(error => `<li>${error}</li>`).join('')}
                                                </ul>
                                            </div>
                                        </div>
                                    `;
                }

                resultContent.html(html);
            }

        });
    </script>
@endpush
