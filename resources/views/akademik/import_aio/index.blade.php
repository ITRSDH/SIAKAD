@extends('layouts.index')

@section('title', 'Import AIO (All In One)')

@push('styles-custom')
    <link rel="stylesheet" href="{{ asset('css/admin-studi.css') }}">
    <style>
        .aio-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            background-color: #f8fafc;
            padding: 2.25rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .aio-dropzone:hover, .aio-dropzone.dragover {
            border-color: #16697a;
            background-color: #f0fdfa;
        }
        .aio-dropzone-icon {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background-color: #e6fffa;
            color: #0f766e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.6rem;
            transition: transform 0.2s ease;
        }
        .aio-dropzone:hover .aio-dropzone-icon {
            transform: translateY(-2px);
        }
        .aio-file-card {
            border: 1px solid #dbe4f0;
            border-radius: 14px;
            background: #ffffff;
            padding: 1rem 1.25rem;
            transition: box-shadow 0.15s ease;
        }
        .aio-file-card:hover {
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
        }
        .bg-soft-success {
            background-color: #ecfdf5 !important;
            color: #065f46 !important;
            border: 1px solid #a7f3d0;
        }
        .bg-soft-warning {
            background-color: #fffbeb !important;
            color: #92400e !important;
            border: 1px solid #fde68a;
        }
        .bg-soft-primary {
            background-color: #eff6ff !important;
            color: #1e40af !important;
            border: 1px solid #bfdbfe;
        }
        .study-collective-table th {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
<div class="page-inner">
    {{-- Page Header --}}
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Import AIO (All In One)</h3>
            <ul class="breadcrumbs mb-0 ps-0">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item active">Import AIO</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('akademik.import-aio.template') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-none">
                <i class="far fa-file-excel text-success me-1"></i> Download Template Dasar (.xlsx)
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @include('layouts.partials.flash-messages')

    {{-- Workflow Strip --}}
    <div class="study-flow-strip mb-4">
        <div class="study-flow-steps">
            <span class="study-flow-label">Alur Otomasi AIO:</span>
            <div class="study-flow-step">
                <span class="ic"><i class="fas fa-layer-group"></i></span>
                <span>1. Sinkronisasi Kurikulum &amp; Kelas</span>
            </div>
            <span class="study-flow-arrow"><i class="fas fa-chevron-right"></i></span>
            <div class="study-flow-step">
                <span class="ic"><i class="fas fa-user-graduate"></i></span>
                <span>2. KRS &amp; Nilai Mahasiswa</span>
            </div>
            <span class="study-flow-arrow"><i class="fas fa-chevron-right"></i></span>
            <div class="study-flow-step">
                <span class="ic"><i class="fas fa-award"></i></span>
                <span>3. Penerbitan KHS Final</span>
            </div>
        </div>
        <div class="small text-muted d-none d-lg-block">
            <i class="fas fa-check-double text-success me-1"></i>Proses terpadu dari 1 dokumen Excel (2 Sheet)
        </div>
    </div>

    <form id="formImportAio" enctype="multipart/form-data">
        @csrf

        {{-- Filter Konteks --}}
        <div class="card study-shell mb-4">
            <div class="card-header border-0 pt-4 px-4 pb-0 bg-transparent">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="fw-semibold text-dark"><i class="fas fa-filter text-primary me-2"></i>Filter Konteks &amp; Kustomisasi Template</div>
                        <div class="small text-muted">Sesuaikan filter untuk menghasilkan template Excel dengan mahasiswa dan mata kuliah yang otomatis tersusun.</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Semester</label>
                        <select name="id_semester" id="importSemesterId" class="form-select select2">
                            <option value="">Semua semester</option>
                            @foreach ($semesters as $item)
                                @php
                                    $label = $item['nama_semester'] ?? ($item['semester'] ?? 'Semester');
                                    $tahun = $item['tahun_akademik']['tahun_akademik'] ?? ($item['tahunAkademik']['tahun_akademik'] ?? '');
                                    if ($tahun && !str_contains($label, $tahun)) {
                                        $label = trim($label . ' ' . $tahun);
                                    }
                                @endphp
                                <option value="{{ $item['id'] }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Program Studi</label>
                        <select name="id_prodi" id="importProdiId" class="form-select select2">
                            <option value="">Semua prodi</option>
                            @foreach ($prodis as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] ?? ($item['prodi'] ?? 'Program Studi') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Kurikulum</label>
                        <select name="id_kurikulum" id="importKurikulumId" class="form-select select2">
                            <option value="">Semua kurikulum</option>
                            @foreach ($kurikulums as $item)
                                <option value="{{ $item['id'] }}">
                                    {{ $item['nama_struktur_mk'] ?? ($item['nama_kurikulum'] ?? ($item['kurikulum'] ?? 'Kurikulum')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Angkatan</label>
                        <select name="angkatan" id="importAngkatan" class="form-select select2">
                            <option value="">Semua Angkatan</option>
                            @foreach ($angkatans as $thn)
                                <option value="{{ $thn }}">{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Semester Ke</label>
                        <select name="semester_ke" id="importSemesterKe" class="form-select select2">
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $i === 1 ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Jenis Mahasiswa</label>
                        <select name="jenis_pendaftaran" id="importJenisPendaftaran" class="form-select select2">
                            <option value="">Semua (Reguler &amp; RPL)</option>
                            <option value="Reguler">Reguler Saja</option>
                            <option value="RPL">RPL Saja</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-3 border-top">
                    <div class="small text-muted d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-primary flex-shrink-0"></i>
                        <span>Template otomatis menyusun daftar mahasiswa dan mata kuliah sesuai kurikulum aktif.</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" id="btnDownloadFilteredTemplate">
                            <i class="fas fa-download me-1"></i>Unduh Template Sesuai Filter (.xlsx)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Form Upload Excel --}}
            <div class="col-lg-7">
                <div class="card study-shell h-100 mb-0">
                    <div class="card-header border-0 pt-4 px-4 pb-2 bg-transparent">
                        <div class="fw-semibold text-dark"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Unggah Dokumen Excel AIO</div>
                        <div class="small text-muted">Pilih atau seret berkas spreadsheet yang sudah dilengkapi nilai mahasiswa.</div>
                    </div>
                    <div class="card-body p-4">
                        {{-- Hidden real file input --}}
                        <input type="file" name="file" id="fileImportAio" class="d-none" accept=".xlsx, .xls" required>

                        {{-- Interactive Dropzone --}}
                        <div class="aio-dropzone mb-3" id="aioDropzone">
                            <div class="aio-dropzone-icon">
                                <i class="far fa-file-excel"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Pilih atau Seret Berkas Excel ke Sini</h6>
                            <p class="small text-muted mb-3">Mendukung format <code>.xlsx</code> dan <code>.xls</code> (Maksimal 20 MB)</p>
                            <button type="button" class="btn btn-outline-primary btn-sm px-3 shadow-none pointer-events-none">
                                <i class="fas fa-folder-open me-1"></i> Telusuri Berkas
                            </button>
                        </div>

                        {{-- File Selected Preview Card --}}
                        <div class="aio-file-card d-none mb-3" id="aioSelectedFileCard">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                    <div class="bg-soft-success p-2 rounded-3 text-success flex-shrink-0">
                                        <i class="far fa-file-excel fa-2x"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate" id="aioFileName">-</div>
                                        <div class="small text-muted">
                                            <span id="aioFileSize">-</span> &bull; <span class="badge bg-soft-success text-success px-2 py-1">Siap Diproses</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm px-2 shadow-none flex-shrink-0" id="btnRemoveFile" title="Hapus Berkas">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="reset" class="btn btn-outline-secondary btn-sm px-3" id="btnResetImport">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btnSubmitImport">
                                <i class="fas fa-play me-1"></i>Unggah &amp; Proses Import AIO
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panduan Struktur File --}}
            <div class="col-lg-5">
                <div class="card study-shell h-100 mb-0">
                    <div class="card-header border-0 pt-4 px-4 pb-2 bg-transparent">
                        <div class="fw-semibold text-dark"><i class="fas fa-book-open me-2 text-primary"></i>Panduan Format 2 Sheet</div>
                        <div class="small text-muted">Dokumen wajib memuat 2 sheet berikut secara utuh.</div>
                    </div>
                    <div class="card-body p-4">
                        <div class="study-mini-item mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-dark">Sheet 1: Struktur_MK_Kelas</span>
                                <span class="badge bg-soft-primary text-primary px-2 py-1">Kurikulum</span>
                            </div>
                            <p class="small text-muted mb-2">
                                Menghubungkan mata kuliah ke kurikulum dan membentuk kelas kuliah di semester terkait secara otomatis.
                            </p>
                            <div class="small text-secondary">
                                Kolom: <code>kode_mk</code>, <code>nama_mk</code>, <code>semester_paket</code>, <code>nama_kelas</code>, <code>kapasitas</code>
                            </div>
                        </div>

                        <div class="study-mini-item">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-dark">Sheet 2: Nilai_Mahasiswa_KHS</span>
                                <span class="badge bg-soft-success text-success px-2 py-1">Nilai &amp; KHS</span>
                            </div>
                            <p class="small text-muted mb-2">
                                Mendaftarkan KRS mahasiswa (otomatis disetujui &amp; terkunci) dan langsung menerbitkan KHS final.
                            </p>
                            <div class="small text-secondary">
                                Baris 1: Metadata &bull; Baris 2–4: Header MK &bull; Baris 5+: Data NIM &amp; Nilai
                            </div>
                        </div>

                        <div class="mt-3 p-2 bg-light rounded-3 small text-muted border">
                            <i class="fas fa-shield-alt text-primary me-1"></i> Jangan ubah kode metadata pada baris 1 Sheet 2 agar validasi file berjalan mulus.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Hasil Pemrosesan --}}
    <div class="row d-none mt-4" id="sectionHasilImport">
        <div class="col-12">
            <div class="card study-shell border-0 mb-4">
                <div class="card-header border-bottom py-3 px-4 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-success p-2 rounded-3 text-success me-3">
                            <i class="fas fa-check-circle fs-5"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fs-6 fw-bold text-dark">Hasil Pemrosesan Import AIO</h5>
                            <div class="small text-muted">Ringkasan hasil eksekusi data kurikulum, kelas, KRS, dan penerbitan KHS</div>
                        </div>
                    </div>
                    <span class="badge bg-soft-success text-success px-3 py-2 fw-bold rounded-pill" id="badgeHasilStatus">
                        <i class="fas fa-check me-1"></i>Selesai Diproses
                    </span>
                </div>
                <div class="card-body p-4">
                    {{-- Summary Stat Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="study-stat text-center">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Mata Kuliah</div>
                                <div class="study-stat-value text-dark" id="resMkCount">0</div>
                                <div class="small text-muted mt-1">Disinkronkan ke Kurikulum</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="study-stat text-center">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Kelas Kuliah</div>
                                <div class="study-stat-value text-dark" id="resKelasCount">0</div>
                                <div class="small text-muted mt-1">Dibuat / Digenerate</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="study-stat text-center">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Mahasiswa</div>
                                <div class="study-stat-value text-dark" id="resMhsCount">0</div>
                                <div class="small text-muted mt-1">KRS &amp; Nilai Terproses</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="study-stat text-center">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">KHS Diterbitkan</div>
                                <div class="study-stat-value text-dark" id="resKhsCount">0</div>
                                <div class="small text-muted mt-1">Status Final Otomatis</div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter & Search Strip --}}
                    <div class="study-filter-strip">
                        <div class="summary">
                            Menampilkan <span class="fw-bold text-dark" id="countFilteredMhs">0</span> dari <span class="fw-bold text-dark" id="countTotalMhs">0</span> mahasiswa
                        </div>
                        <div class="search-input-wrap">
                            <i class="fas fa-search search-input-icon"></i>
                            <input type="text" id="searchHasilTable" class="form-control form-control-sm" placeholder="Cari NIM atau nama mahasiswa...">
                        </div>
                    </div>

                    {{-- Tabel Mahasiswa Terproses --}}
                    <div class="study-collective-table-wrap">
                        <table class="table study-collective-table table-hover align-middle mb-0" id="tableHasilImport">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 140px;">NIM</th>
                                    <th class="text-start">Nama Mahasiswa</th>
                                    <th style="width: 110px;">Total SKS</th>
                                    <th style="width: 110px;">IPS</th>
                                    <th style="width: 120px;">Status</th>
                                    <th class="text-start">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyHasilImport">
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada data hasil pemrosesan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Atur Urutan Mata Kuliah Template AIO --}}
<div class="modal fade" id="modalOrderMataKuliahAio" tabindex="-1" aria-labelledby="modalOrderMataKuliahAioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 18px;">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="modalOrderMataKuliahAioLabel">
                        <i class="fas fa-sort-amount-down text-primary me-2"></i>Atur Urutan Kolom Mata Kuliah (Sheet 2)
                    </h5>
                    <div class="small text-muted mt-1">
                        Sesuaikan susunan mata kuliah agar urutan kolom di Sheet 2 template Excel persis sama dengan format nilai dosen Anda.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2 small border-0 bg-soft-primary">
                    <i class="fas fa-info-circle text-primary flex-shrink-0"></i>
                    <div>
                        Gunakan tombol <strong><i class="fas fa-arrow-up"></i> Naik</strong> / <strong><i class="fas fa-arrow-down"></i> Turun</strong> untuk memindahkan urutan mata kuliah sebelum mengunduh template.
                    </div>
                </div>

                <div id="aioSubjectOrderLoading" class="text-center py-5">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div class="text-muted small">Memuat daftar mata kuliah kurikulum...</div>
                </div>

                <div id="aioSubjectOrderEmpty" class="text-center py-4 d-none">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <div class="text-muted">Tidak ditemukan data mata kuliah untuk filter yang dipilih. Template akan diunduh dengan kolom default.</div>
                </div>

                <div id="aioSubjectOrderContainer" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="small fw-semibold text-secondary">
                            Susunan Kolom Template (<span id="aioSubjectOrderCount">0</span> Mata Kuliah):
                        </span>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none py-0 text-muted" id="btnResetAioSubjectOrder">
                            <i class="fas fa-undo me-1"></i>Reset Urutan
                        </button>
                    </div>
                    <div class="border rounded-3 shadow-sm" style="max-height: 420px; overflow-y: auto !important; overflow-x: hidden;">
                        <ul class="list-group list-group-flush mb-0" id="aioSubjectOrderList">
                            {{-- Diisi secara dinamis via JavaScript --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm fw-semibold px-4" id="btnConfirmDownloadAioTemplate">
                    <i class="fas fa-download me-1"></i> Unduh Template Excel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-custom')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const allKurikulums = @json($kurikulums);

        // Filter Kurikulum dropdown saat Program Studi berubah
        $('#importProdiId').on('change', function() {
            const selectedProdiId = $(this).val();
            const $kurikulumSelect = $('#importKurikulumId');
            const currentVal = $kurikulumSelect.val();

            $kurikulumSelect.find('option:not([value=""])').remove();

            allKurikulums.forEach(function(k) {
                if (!selectedProdiId || !k.id_prodi || k.id_prodi === selectedProdiId) {
                    const label = k.nama_struktur_mk || k.nama_kurikulum || k.kurikulum || 'Kurikulum';
                    $kurikulumSelect.append(new Option(label, k.id, false, false));
                }
            });

            if (currentVal && $kurikulumSelect.find(`option[value="${currentVal}"]`).length > 0) {
                $kurikulumSelect.val(currentVal).trigger('change.select2');
            } else {
                $kurikulumSelect.val('').trigger('change.select2');
            }
        });

        // Interactive Dropzone Handlers
        const $dropzone = $('#aioDropzone');
        const $fileInput = $('#fileImportAio');
        const $selectedCard = $('#aioSelectedFileCard');
        const $fileName = $('#aioFileName');
        const $fileSize = $('#aioFileSize');
        const $btnRemove = $('#btnRemoveFile');

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function handleFileSelection(file) {
            if (!file) return;
            $fileName.text(file.name);
            $fileSize.text(formatBytes(file.size));
            $dropzone.addClass('d-none');
            $selectedCard.removeClass('d-none');
        }

        function clearFileSelection() {
            $fileInput.val('');
            $selectedCard.addClass('d-none');
            $dropzone.removeClass('d-none');
        }

        $dropzone.on('click', function() {
            $fileInput.trigger('click');
        });

        $fileInput.on('change', function() {
            if (this.files && this.files[0]) {
                handleFileSelection(this.files[0]);
            }
        });

        $dropzone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $dropzone.addClass('dragover');
        });

        $dropzone.on('dragleave dragend drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $dropzone.removeClass('dragover');
        });

        $dropzone.on('drop', function(e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files && files.length > 0) {
                $fileInput[0].files = files;
                handleFileSelection(files[0]);
            }
        });

        $btnRemove.on('click', function(e) {
            e.stopPropagation();
            clearFileSelection();
        });

        $('#btnResetImport').on('click', function() {
            clearFileSelection();
        });

        let aioOriginalSubjectList = [];

        function updateAioOrderBadges() {
            const total = $('#aioSubjectOrderList li').length;
            $('#aioSubjectOrderList li').each(function(index) {
                $(this).find('.order-badge').text(index + 1);
                $(this).find('.btn-order-up').prop('disabled', index === 0);
                $(this).find('.btn-order-down').prop('disabled', index === total - 1);
            });
        }

        function renderAioSubjectOrderList(subjects) {
            const $list = $('#aioSubjectOrderList');
            $list.empty();
            $('#aioSubjectOrderCount').text(subjects.length);

            subjects.forEach(function(subj, idx) {
                const html = `
                    <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-3 border-bottom" data-kode="${subj.kode_mk}">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-soft-primary text-primary rounded-pill order-badge fw-bold" style="min-width: 28px; font-size: 0.85rem;">${idx + 1}</span>
                            <div>
                                <div class="fw-bold text-dark">${subj.nama_mk}</div>
                                <div class="small text-muted"><code class="text-primary fw-semibold">${subj.kode_mk}</code> &bull; ${subj.sks} SKS</div>
                            </div>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary btn-order-up shadow-none" title="Geser ke Atas">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-order-down shadow-none" title="Geser ke Bawah">
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                    </li>
                `;
                $list.append(html);
            });

            updateAioOrderBadges();
        }

        $(document).on('click', '#aioSubjectOrderList .btn-order-up', function() {
            const $li = $(this).closest('li');
            const $prev = $li.prev('li');
            if ($prev.length) {
                $li.insertBefore($prev);
                updateAioOrderBadges();
            }
        });

        $(document).on('click', '#aioSubjectOrderList .btn-order-down', function() {
            const $li = $(this).closest('li');
            const $next = $li.next('li');
            if ($next.length) {
                $li.insertAfter($next);
                updateAioOrderBadges();
            }
        });

        $('#btnResetAioSubjectOrder').on('click', function() {
            if (aioOriginalSubjectList && aioOriginalSubjectList.length > 0) {
                renderAioSubjectOrderList(aioOriginalSubjectList);
            }
        });

        // Trigger Modal saat klik Download Template Sesuai Filter
        $('#btnDownloadFilteredTemplate').on('click', function(e) {
            e.preventDefault();
            const idSemester = $('#importSemesterId').val();
            const idProdi = $('#importProdiId').val();
            const idKurikulum = $('#importKurikulumId').val();
            const angkatan = $('#importAngkatan').val();
            const semesterKe = $('#importSemesterKe').val() || 1;
            const jenisPendaftaran = $('#importJenisPendaftaran').val();

            if (!idProdi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Program Studi',
                    text: 'Silakan pilih Program Studi terlebih dahulu agar data mahasiswa dan mata kuliah dapat disusun otomatis ke dalam template Excel.'
                });
                return;
            }

            const modalEl = document.getElementById('modalOrderMataKuliahAio');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            $('#aioSubjectOrderLoading').removeClass('d-none');
            $('#aioSubjectOrderContainer').addClass('d-none');
            $('#aioSubjectOrderEmpty').addClass('d-none');

            $.ajax({
                url: "{{ route('akademik.import-aio.template-subjects') }}",
                type: 'GET',
                data: {
                    id_prodi: idProdi,
                    id_kurikulum: idKurikulum,
                    semester_ke: semesterKe,
                    jenis_pendaftaran: jenisPendaftaran,
                },
                success: function(res) {
                    $('#aioSubjectOrderLoading').addClass('d-none');
                    if (res.success && res.data && res.data.length > 0) {
                        aioOriginalSubjectList = [...res.data];
                        renderAioSubjectOrderList(res.data);
                        $('#aioSubjectOrderContainer').removeClass('d-none');
                    } else {
                        $('#aioSubjectOrderEmpty').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#aioSubjectOrderLoading').addClass('d-none');
                    $('#aioSubjectOrderEmpty').removeClass('d-none');
                }
            });
        });

        // Download Template setelah konfirmasi urutan
        $('#btnConfirmDownloadAioTemplate').on('click', function() {
            const idSemester = $('#importSemesterId').val();
            const idProdi = $('#importProdiId').val();
            const idKurikulum = $('#importKurikulumId').val();
            const angkatan = $('#importAngkatan').val();
            const semesterKe = $('#importSemesterKe').val() || 1;
            const jenisPendaftaran = $('#importJenisPendaftaran').val();

            const order = $('#aioSubjectOrderList li')
                .map(function() {
                    return $(this).data('kode');
                })
                .get()
                .join(',');

            const queryParams = new URLSearchParams();
            if (idSemester) queryParams.append('id_semester', idSemester);
            if (idProdi) queryParams.append('id_prodi', idProdi);
            if (idKurikulum) queryParams.append('id_kurikulum', idKurikulum);
            if (angkatan) queryParams.append('angkatan', angkatan);
            if (semesterKe) queryParams.append('semester_ke', semesterKe);
            if (jenisPendaftaran) queryParams.append('jenis_pendaftaran', jenisPendaftaran);
            if (order) queryParams.append('subject_order', order);

            const downloadUrl = "{{ route('akademik.import-aio.template-filtered') }}?" + queryParams.toString();

            const modalEl = document.getElementById('modalOrderMataKuliahAio');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            Swal.fire({
                icon: 'info',
                title: 'Menyiapkan Template...',
                text: 'File Excel sedang dipersiapkan dan akan otomatis terunduh.',
                timer: 2000,
                showConfirmButton: false
            });

            window.location.href = downloadUrl;
        });

        // Submit Form Import AIO
        $('#formImportAio').on('submit', function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('fileImportAio');
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Berkas',
                    text: 'Silakan pilih atau seret file Excel AIO terlebih dahulu.'
                });
                return;
            }

            const formData = new FormData(this);

            Swal.fire({
                title: 'Mulai Proses Import AIO?',
                text: 'Sistem akan memproses Sheet 1 (Kurikulum & Kelas) dan Sheet 2 (KRS, Nilai, & KHS).',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-play me-1"></i> Ya, Jalankan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#16697a',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Sedang Memproses Import AIO...',
                    html: 'Mohon tunggu sejenak, data sedang diparsing dan disimpan ke database.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('#btnSubmitImport').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Memproses...');

                $.ajax({
                    url: "{{ route('akademik.import-aio.process') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        $('#btnSubmitImport').prop('disabled', false).html('<i class="fas fa-play me-1"></i>Unggah &amp; Proses Import AIO');

                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Import AIO Berhasil!',
                                text: res.message || 'Semua data berhasil diproses.'
                            });

                            // Render Hasil
                            renderHasilImport(res.data);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Import Gagal',
                                text: res.message || 'Terjadi kesalahan saat memproses data.'
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#btnSubmitImport').prop('disabled', false).html('<i class="fas fa-play me-1"></i>Unggah &amp; Proses Import AIO');
                        const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan server saat memproses file import.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memproses File',
                            text: errorMsg
                        });
                    }
                });
            });
        });

        let currentProcessedStudents = [];

        function filterAndRenderTable(query = '') {
            const $tbody = $('#tbodyHasilImport');
            const q = query.trim().toLowerCase();

            const filtered = currentProcessedStudents.filter(item => {
                if (!q) return true;
                const nim = (item.nim || '').toLowerCase();
                const nama = (item.nama || '').toLowerCase();
                return nim.includes(q) || nama.includes(q);
            });

            $('#countFilteredMhs').text(filtered.length);

            if (filtered.length === 0) {
                $tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data mahasiswa yang sesuai pencarian.</td></tr>');
                return;
            }

            let rowsHtml = '';
            filtered.forEach((item, idx) => {
                const badgeClass = item.status === 'success' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning';
                const statusLabel = item.status === 'success' ? 'Sukses' : 'Dilewati';
                const rawIps = item.ips;
                const formattedIps = (rawIps !== null && rawIps !== undefined && !isNaN(Number(rawIps)))
                    ? Number(rawIps).toFixed(2)
                    : (rawIps || '0.00');

                rowsHtml += `
                    <tr>
                        <td class="text-center">${idx + 1}</td>
                        <td class="fw-semibold text-center"><code>${item.nim || '-'}</code></td>
                        <td>${item.nama || '-'}</td>
                        <td class="text-center">${item.total_sks ?? 0} SKS</td>
                        <td class="text-center fw-bold text-primary">${formattedIps}</td>
                        <td class="text-center"><span class="badge ${badgeClass} px-2 py-1">${statusLabel}</span></td>
                        <td class="small text-muted">${item.message || '-'}</td>
                    </tr>
                `;
            });

            $tbody.html(rowsHtml);
        }

        $('#searchHasilTable').on('input', function() {
            filterAndRenderTable($(this).val());
        });

        function renderHasilImport(data) {
            $('#sectionHasilImport').removeClass('d-none');

            const s1 = data.step1_kurikulum_kelas || {};
            const s2 = data.step2_mahasiswa_khs || {};

            $('#resMkCount').text(s1.synced_mk_count ?? 0);
            $('#resKelasCount').text(s1.created_classes_count ?? 0);
            $('#resMhsCount').text(s2.processed_students_count ?? 0);
            $('#resKhsCount').text(s2.total_khs_created ?? 0);

            currentProcessedStudents = s2.students || [];
            $('#countTotalMhs').text(currentProcessedStudents.length);
            $('#searchHasilTable').val('');

            filterAndRenderTable('');

            // Scroll ke bagian hasil
            $('html, body').animate({
                scrollTop: $('#sectionHasilImport').offset().top - 80
            }, 600);
        }
    });
</script>
@endpush

