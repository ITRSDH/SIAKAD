@extends('layouts.index')
@section('title', 'Detail Kelas Kuliah')

@push('styles-custom')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .modal-xxl {
            max-width: 95% !important;
        }

        .peserta-krs-table td,
        .peserta-krs-table th {
            vertical-align: middle;
        }

        .peserta-krs-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 1rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .peserta-krs-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .peserta-krs-search {
            max-width: 360px;
            width: 100%;
        }

        .peserta-krs-filter {
            width: 180px;
        }

        .peserta-krs-table {
            margin-bottom: 0;
        }

        .peserta-krs-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        .peserta-krs-table tbody tr:hover {
            background: #f8fbff;
        }

        .peserta-krs-nama {
            font-weight: 600;
            color: #0f172a;
        }

        .peserta-krs-meta {
            font-size: 0.82rem;
            color: #64748b;
        }

        .peserta-krs-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .peserta-krs-pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .peserta-krs-page-links {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .peserta-krs-page-info {
            font-size: 0.85rem;
            color: #64748b;
        }

        .peserta-krs-per-page {
            width: auto;
            min-width: 110px;
        }

        .peserta-krs-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .peserta-krs-summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #fff;
            padding: 1rem 1.1rem;
        }

        .peserta-krs-summary-label {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0.35rem;
        }

        .peserta-krs-summary-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
        }

        .peserta-krs-row-disabled {
            background: #f8fafc;
        }

        .peserta-krs-row-disabled td {
            color: #64748b;
        }

        .peserta-krs-state {
            min-width: 150px;
        }

        .peserta-krs-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .peserta-krs-section {
            margin-top: 1.5rem;
            border-top: 1px dashed #cbd5e1;
            padding-top: 1.25rem;
        }

        .peserta-krs-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .peserta-krs-section-copy {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .peserta-krs-selected-count {
            font-size: 0.85rem;
            color: #475569;
        }

        .peserta-krs-filter-summary {
            font-size: 0.85rem;
            color: #64748b;
        }

        @media (max-width: 767.98px) {
            .peserta-krs-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Kelas Kuliah</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Kelas Kuliah</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Detail Kelas Kuliah</a></li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Kelas Kuliah</h4>

                            <div class="d-flex gap-2">
                                <a href="{{ route('kelas-kuliah.create') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>

                                <button type="submit" form="form-kelas-kuliah" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-pen me-1"></i> Ubah
                                </button>

                                <button type="button" class="btn btn-sm btn-outline-primary btn-sync-krs"
                                    data-id="{{ $kelaskuliah['id'] }}"
                                    data-nama="{{ $kelaskuliah['nama_kelas'] ?? '' }}"
                                    title="Periksa daftar kesesuaian kurikulum mahasiswa dan lakukan sinkronisasi">
                                    <i class="fas fa-tasks me-1"></i> Periksa & Sinkronkan Peserta
                                </button>

                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $kelaskuliah['id'] }}" data-nama="{{ $kelaskuliah['nama_kelas'] }}">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>

                                <a href="{{ route('kelas-kuliah.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-bars me-1"></i> Daftar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @php
                            $currentMkId = old('id_kurikulum_mata_kuliah', $kelaskuliah['id_kurikulum_mata_kuliah'] ?? '');
                            $currentKurikulumId = old('id_kurikulum', $kelaskuliah['id_kurikulum'] ?? '');
                            if (! $currentKurikulumId && $currentMkId) {
                                $foundKm = collect($kurikulum_matakuliah ?? [])->firstWhere('id', $currentMkId);
                                $currentKurikulumId = $foundKm['id_kurikulum'] ?? '';
                            }
                        @endphp
                        <form id="form-kelas-kuliah" action="{{ route('kelas-kuliah.update', $kelaskuliah['id']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- PRODI -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value="" disabled>-- Pilih Program Studi --</option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}"
                                                    {{ old('id_prodi', $kelaskuliah['id_prodi'] ?? '') == $p['id'] ? 'selected' : '' }}>
                                                    {{ $p['prodi'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- SEMESTER -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value="" disabled>-- Pilih Semester --</option>
                                            @foreach ($semester as $s)
                                                <option value="{{ $s['id'] }}"
                                                    {{ old('id_semester', $kelaskuliah['id_semester'] ?? '') == $s['id'] ? 'selected' : '' }}>
                                                    {{ $s['semester'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- STRUKTUR KURIKULUM -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kurikulum" class="form-label">
                                            Struktur Kurikulum <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select select2" id="id_kurikulum" required>
                                            <option value="" disabled selected>-- Pilih Program Studi Terlebih Dahulu --</option>
                                        </select>
                                        <small class="text-muted">Pilih kurikulum untuk menyaring mata kuliah yang berlaku.</small>
                                    </div>
                                </div>

                                <!-- NAMA KELAS -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                            value="{{ old('nama_kelas', $kelaskuliah['nama_kelas'] ?? '') }}" required>
                                        <small class="text-muted">Nama kelas dapat sama untuk paralel/sesi yang berbeda.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- MATA KULIAH -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_mata_kuliah" class="form-label">
                                            Mata Kuliah <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select select2" id="id_kurikulum_mata_kuliah"
                                            name="id_kurikulum_mata_kuliah" required>
                                            <option value="" disabled selected>-- Pilih Struktur Kurikulum Terlebih Dahulu --</option>
                                        </select>
                                        <small class="text-muted">Mata kuliah otomatis dikelompokkan berdasarkan semester paket kurikulum.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Card Detail Mata Kuliah -->
                            <div class="row" id="mk-preview-container">
                                <div class="col-md-12">
                                    <div id="mk-preview-card" class="card border border-primary border-opacity-25 bg-light mb-3 d-none shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                    <span class="badge bg-primary fs-6 px-2 py-1" id="preview-kode-mk">-</span>
                                                    <span class="fw-bold fs-6 text-dark" id="preview-nama-mk">-</span>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap gap-1">
                                                    <span class="badge bg-secondary" id="preview-kurikulum-badge">-</span>
                                                    <span class="badge" id="preview-sifat-badge">-</span>
                                                    <span class="badge bg-info text-dark" id="preview-semester-badge">-</span>
                                                    <span class="badge" id="preview-kesesuaian-badge"></span>
                                                </div>
                                            </div>
                                            <div class="row g-2 text-center text-md-start pt-2 border-top">
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Total Bobot SKS</div>
                                                    <div class="fw-bold text-primary fs-5" id="preview-sks-total">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Tatap Muka</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-tm">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Praktikum / Lapangan</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-prak">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Simulasi</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-sim">0 SKS</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks" class="form-label">SKS Mata Kuliah</label>
                                        <input type="number" class="form-control" id="sks" name="sks"
                                            placeholder="0" readonly value="{{ $kelaskuliah['mata_kuliah']['sks'] }}">
                                        <small class="text-muted">(SKS Tatap Muka + SKS Praktikum + SKS Praktik Lapangan +
                                            SKS Simulasi)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_tatap_muka" class="form-label">Bobot SKS Tatap Muka</label>
                                        <input type="number" class="form-control" id="sks_tatap_muka" name="sks_tatap_muka"
                                            placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_tatap_muka'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktikum" class="form-label">Bobot SKS Praktikum</label>
                                        <input type="number" class="form-control" id="sks_praktikum"
                                            name="sks_praktikum" placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_praktikum'] ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktik_lapangan" class="form-label">Bobot SKS Praktik
                                            Lapangan</label>
                                        <input type="number" class="form-control" id="sks_praktik_lapangan"
                                            name="sks_praktik_lapangan" placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_praktik_lapangan'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bahasan" class="form-label">Bahasan</label>
                                        <input type="text" class="form-control" id="bahasan" name="bahasan"
                                            value="{{ old('bahasan', $kelaskuliah['bahasan'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_simulasi" class="form-label">Bobot SKS Simulasi</label>
                                        <input type="number" class="form-control" id="sks_simulasi" name="sks_simulasi"
                                            placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_simulasi'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- LINGKUP -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lingkup" class="form-label">Lingkup</label>
                                        <select class="form-select select2" id="lingkup" name="lingkup">
                                            <option value=""></option>
                                            <option value="internal"
                                                {{ $kelaskuliah['lingkup'] == 'internal' ? 'selected' : '' }}>Internal
                                            </option>
                                            <option value="eksternal"
                                                {{ $kelaskuliah['lingkup'] == 'eksternal' ? 'selected' : '' }}>Eksternal
                                            </option>
                                            <option value="campuran"
                                                {{ $kelaskuliah['lingkup'] == 'campuran' ? 'selected' : '' }}>Campuran
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas_peserta" class="form-label">Kapasitas Peserta</label>
                                        <input type="number" class="form-control" id="kapasitas_peserta"
                                            name="kapasitas_peserta" min="1"
                                            value="{{ old('kapasitas_peserta', $kelaskuliah['kapasitas_peserta'] ?? '') }}">
                                        <small class="text-muted">Digunakan sebagai kuota kelas dan pembanding kapasitas
                                            ruang.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mode_kuliah" class="form-label">Mode Kuliah</label>
                                        <select class="form-select select2" id="mode_kuliah" name="mode_kuliah">
                                            <option value=""></option>
                                            <option value="online"
                                                {{ $kelaskuliah['mode_kuliah'] == 'online' ? 'selected' : '' }}>Online
                                            </option>
                                            <option value="offline"
                                                {{ $kelaskuliah['mode_kuliah'] == 'offline' ? 'selected' : '' }}>Offline
                                            </option>
                                            <option value="campuran"
                                                {{ $kelaskuliah['mode_kuliah'] == 'campuran' ? 'selected' : '' }}>Campuran
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>

                            <div class="row">
                                <!-- TANGGAL MULAI -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai_efektif" class="form-label">Tanggal Mulai
                                            Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_mulai_efektif"
                                            name="tanggal_mulai_efektif"
                                            value="{{ old('tanggal_mulai_efektif', $kelaskuliah['tanggal_mulai_efektif']) }}">
                                    </div>
                                </div>

                                <!-- TANGGAL AKHIR -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_akhir_efektif" class="form-label">Tanggal Akhir
                                            Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_akhir_efektif"
                                            name="tanggal_akhir_efektif"
                                            value="{{ old('tanggal_akhir_efektif', $kelaskuliah['tanggal_akhir_efektif']) }}">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Kapasitas Peserta</div>
                                <div class="fs-4 fw-semibold">{{ $kelaskuliah['kapasitas_peserta'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Mode Kuliah</div>
                                <div class="fs-4 fw-semibold text-capitalize">{{ $kelaskuliah['mode_kuliah'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Lingkup</div>
                                <div class="fs-4 fw-semibold text-capitalize">{{ $kelaskuliah['lingkup'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="dosen-pengajar-tab" data-bs-toggle="pill"
                                    href="#dosen-pengajar" role="tab" aria-controls="dosen-pengajar"
                                    aria-selected="true">Dosen Pengajar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mahasiswa-tab" data-bs-toggle="pill" href="#mahasiswa"
                                    role="tab" aria-controls="mahasiswa" aria-selected="false">Peserta
                                    KRS/Mahasiswa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="jadwal-tab" data-bs-toggle="pill" href="#jadwal" role="tab"
                                    aria-controls="jadwal" aria-selected="false">Jadwal</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3 mb-3" id="line-tabContent">
                            <div class="tab-pane fade show active" id="dosen-pengajar">
                                <div id="content-dosen">
                                    <div class="text-center p-3">Loading...</div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mahasiswa">
                                <div id="content-mahasiswa">
                                    <div class="peserta-krs-wrap">
                                        @php
                                            $regularCandidates = collect($krsCandidates ?? [])->where('is_repeat_candidate', false)->values();
                                            $repeatCandidates = collect($krsCandidates ?? [])->where('is_repeat_candidate', true)->values();
                                        @endphp
                                        <div class="peserta-krs-toolbar">
                                            <div>
                                                <h5 class="mb-1">Kelola Peserta KRS pada Kelas Ini</h5>
                                                <div class="text-muted small">Centang mahasiswa yang masih bisa didaftarkan.
                                                    Mahasiswa yang sudah terdaftar atau belum bisa diproses akan otomatis
                                                    dinonaktifkan.</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm btn-sync-krs"
                                                    data-id="{{ $kelaskuliah['id'] }}"
                                                    data-nama="{{ $kelaskuliah['nama_kelas'] ?? '' }}"
                                                    title="Periksa daftar kesesuaian kurikulum mahasiswa dan lakukan sinkronisasi">
                                                    <i class="fas fa-tasks me-1"></i> Periksa & Sinkronkan Peserta
                                                </button>
                                                <span class="badge bg-primary px-3 py-2"
                                                    id="pesertaKrsCountBadge">{{ count($krsCandidates ?? []) }} Mahasiswa</span>
                                            </div>
                                        </div>

                                        <div class="peserta-krs-summary">
                                            <div class="peserta-krs-summary-card">
                                                <div class="peserta-krs-summary-label">Sudah Terdaftar</div>
                                                <div class="peserta-krs-summary-value">
                                                    {{ $krsCandidateSummary['registered_count'] ?? 0 }}</div>
                                            </div>
                                            <div class="peserta-krs-summary-card">
                                                <div class="peserta-krs-summary-label">Siap Didaftarkan</div>
                                                <div class="peserta-krs-summary-value">
                                                    {{ $krsCandidateSummary['available_count'] ?? 0 }}</div>
                                            </div>
                                            <div class="peserta-krs-summary-card">
                                                <div class="peserta-krs-summary-label">Perlu Tinjauan</div>
                                                <div class="peserta-krs-summary-value">
                                                    {{ $krsCandidateSummary['blocked_count'] ?? 0 }}</div>
                                            </div>
                                            <div class="peserta-krs-summary-card">
                                                <div class="peserta-krs-summary-label">Kandidat Ulang</div>
                                                <div class="peserta-krs-summary-value">
                                                    {{ $krsCandidateSummary['repeat_count'] ?? 0 }}</div>
                                            </div>
                                        </div>

                                        <form method="POST"
                                            action="{{ route('kelas-kuliah.register-krs', $kelaskuliah['id']) }}"
                                            id="pesertaKrsForm">
                                            @csrf

                                            <div class="peserta-krs-actions">
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <select class="form-select peserta-krs-per-page" id="pesertaKrsPerPage">
                                                        <option value="5">5 baris</option>
                                                        <option value="10" selected>10 baris</option>
                                                        <option value="25">25 baris</option>
                                                        <option value="50">50 baris</option>
                                                    </select>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        id="pesertaKrsSelectVisibleBtn">
                                                        Pilih yang Terlihat
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        id="pesertaKrsClearBtn">
                                                        Kosongkan Pilihan
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="peserta-krs-selected-count" id="pesertaKrsSelectedCount">
                                                        0 mahasiswa dipilih
                                                    </span>
                                                    <span class="peserta-krs-filter-summary" id="pesertaKrsFilterSummary">
                                                        Menampilkan semua angkatan.
                                                    </span>
                                                    <select class="form-select peserta-krs-filter" id="pesertaKrsStatusFilter" style="width: auto; min-width: 155px;">
                                                        <option value="aktif" {{ ($statusMahasiswa ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Status: Aktif</option>
                                                        <option value="all" {{ ($statusMahasiswa ?? 'aktif') === 'all' ? 'selected' : '' }}>Status: Semua</option>
                                                        <option value="cuti" {{ ($statusMahasiswa ?? '') === 'cuti' ? 'selected' : '' }}>Status: Cuti</option>
                                                        <option value="lulus" {{ ($statusMahasiswa ?? '') === 'lulus' ? 'selected' : '' }}>Status: Lulus</option>
                                                        <option value="do" {{ ($statusMahasiswa ?? '') === 'do' ? 'selected' : '' }}>Status: DO</option>
                                                    </select>
                                                    <select class="form-select peserta-krs-filter" id="pesertaKrsAngkatanFilter">
                                                        <option value="">Semua angkatan</option>
                                                        @foreach (collect($krsCandidates ?? [])->pluck('angkatan')->filter()->unique()->sortDesc()->values() as $angkatanOption)
                                                            <option value="{{ $angkatanOption }}">{{ $angkatanOption }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control peserta-krs-search"
                                                        id="pesertaKrsSearch" placeholder="Cari mahasiswa atau NIM...">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        id="pesertaKrsResetFilterBtn">
                                                        Reset Filter
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        id="pesertaKrsSubmitBtn">
                                                        <i class="fas fa-user-plus me-1"></i> Daftarkan ke KRS
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover peserta-krs-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 52px;" class="text-center">
                                                                <input type="checkbox" id="pesertaKrsCheckAll">
                                                            </th>
                                                            <th style="width: 160px;">NIM</th>
                                                            <th>Nama Mahasiswa</th>
                                                            <th style="width: 120px;">Angkatan</th>
                                                            <th style="width: 160px;">Status Mahasiswa</th>
                                                            <th style="width: 150px;">Status KRS</th>
                                                            <th style="width: 260px;">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="pesertaKrsTableBody">
                                                        @forelse ($regularCandidates as $row)
                                                            @php
                                                                $badgeClass = match ($row['state_variant'] ?? 'secondary') {
                                                                    'primary' => 'bg-primary',
                                                                    'success' => 'bg-success',
                                                                    'warning' => 'bg-warning text-dark',
                                                                    'danger' => 'bg-danger',
                                                                    default => 'bg-secondary',
                                                                };
                                                                $statusKrsLabel = $row['status_krs']
                                                                    ? ucfirst((string) $row['status_krs'])
                                                                    : 'Belum ada draft';
                                                            @endphp
                                                            <tr class="peserta-krs-row peserta-krs-selectable-row peserta-krs-regular-row {{ !($row['can_register'] ?? false) ? 'peserta-krs-row-disabled' : '' }}"
                                                                data-nim="{{ strtolower((string) ($row['nim'] ?? '')) }}"
                                                                data-nama="{{ strtolower((string) ($row['nama_mahasiswa'] ?? '')) }}"
                                                                data-angkatan="{{ $row['angkatan'] ?? '' }}">
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                        class="peserta-krs-checkbox"
                                                                        name="mahasiswa_ids[]"
                                                                        value="{{ $row['id_mahasiswa'] }}"
                                                                        {{ ($row['can_register'] ?? false) ? '' : 'disabled' }}>
                                                                </td>
                                                                <td>
                                                                    <div class="fw-semibold">{{ $row['nim'] ?? '-' }}</div>
                                                                </td>
                                                                <td>
                                                                    <div class="peserta-krs-nama">
                                                                        {{ $row['nama_mahasiswa'] ?? '-' }}</div>
                                                                    <div class="peserta-krs-meta">
                                                                        {{ ($row['can_register'] ?? false) ? 'Siap ditambahkan ke kelas ini.' : ($row['reason'] ?? 'Belum bisa diproses.') }}
                                                                    </div>
                                                                </td>
                                                                <td>{{ $row['angkatan'] ?? '-' }}</td>
                                                                <td>
                                                                    <span class="badge bg-light text-dark border">
                                                                        {{ $row['status_mahasiswa'] ?? '-' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-light text-dark border">
                                                                        {{ $statusKrsLabel }}
                                                                    </span>
                                                                </td>
                                                                <td class="peserta-krs-state">
                                                                    <div class="mb-1">
                                                                        <span class="badge {{ $badgeClass }}">
                                                                            {{ $row['state_label'] ?? 'Status' }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="peserta-krs-meta">
                                                                        {{ $row['reason'] ?? 'Mahasiswa bisa didaftarkan ke KRS pada kelas ini.' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr id="pesertaKrsEmptyRow">
                                                                <td colspan="7" class="text-center text-muted py-4">
                                                                    Belum ada mahasiswa yang bisa ditampilkan untuk program
                                                                    studi kelas ini.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                        @if ($regularCandidates->count())
                                                            <tr id="pesertaKrsNoResultRow" class="d-none">
                                                                <td colspan="7" class="text-center text-muted py-4">
                                                                    Tidak ada mahasiswa yang cocok dengan pencarian Anda.
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            @if ($regularCandidates->count())
                                                <div class="peserta-krs-footer">
                                                    <div class="peserta-krs-page-info" id="pesertaKrsPageInfo">
                                                        Menampilkan 0 mahasiswa
                                                    </div>
                                                    <div class="peserta-krs-pagination">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                            id="pesertaKrsPrevBtn">
                                                            Sebelumnya
                                                        </button>
                                                        <div class="peserta-krs-page-links" id="pesertaKrsPageLinks"></div>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                            id="pesertaKrsNextBtn">
                                                            Berikutnya
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($repeatCandidates->count())
                                                <div class="peserta-krs-section">
                                                    <div class="peserta-krs-section-title">Daftar Pengulangan Mata Kuliah</div>
                                                    <div class="peserta-krs-section-copy">
                                                        Mahasiswa di bawah ini memiliki histori terakhir matakuliah ini dengan status tidak lulus, sehingga dipisahkan agar lebih mudah ditinjau.
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover peserta-krs-table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 52px;" class="text-center"></th>
                                                                    <th style="width: 160px;">NIM</th>
                                                                    <th>Nama Mahasiswa</th>
                                                                    <th style="width: 120px;">Angkatan</th>
                                                                    <th style="width: 160px;">Nilai Terakhir</th>
                                                                    <th style="width: 180px;">Semester Gagal</th>
                                                                    <th style="width: 260px;">Keterangan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="pesertaKrsRepeatTableBody">
                                                                @foreach ($repeatCandidates as $row)
                                                                    @php
                                                                        $badgeClass = match ($row['state_variant'] ?? 'secondary') {
                                                                            'primary' => 'bg-primary',
                                                                            'success' => 'bg-success',
                                                                            'warning' => 'bg-warning text-dark',
                                                                            'danger' => 'bg-danger',
                                                                            default => 'bg-secondary',
                                                                        };
                                                                        $repeatHistory = $row['repeat_history'] ?? [];
                                                                        $repeatGrade = $repeatHistory['nilai_huruf'] ?? '-';
                                                                        $repeatScore = $repeatHistory['nilai_akhir'] ?? null;
                                                                    @endphp
                                                                    <tr class="peserta-krs-selectable-row peserta-krs-repeat-row {{ !($row['can_register'] ?? false) ? 'peserta-krs-row-disabled' : '' }}"
                                                                        data-nim="{{ strtolower((string) ($row['nim'] ?? '')) }}"
                                                                        data-nama="{{ strtolower((string) ($row['nama_mahasiswa'] ?? '')) }}"
                                                                        data-angkatan="{{ $row['angkatan'] ?? '' }}">
                                                                        <td class="text-center">
                                                                            <input type="checkbox"
                                                                                class="peserta-krs-checkbox"
                                                                                name="mahasiswa_ids[]"
                                                                                value="{{ $row['id_mahasiswa'] }}"
                                                                                {{ ($row['can_register'] ?? false) ? '' : 'disabled' }}>
                                                                        </td>
                                                                        <td>
                                                                            <div class="fw-semibold">{{ $row['nim'] ?? '-' }}</div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="peserta-krs-nama">
                                                                                {{ $row['nama_mahasiswa'] ?? '-' }}</div>
                                                                            <div class="peserta-krs-meta">Mahasiswa ini pernah tidak lulus pada matakuliah yang sama.</div>
                                                                        </td>
                                                                        <td>{{ $row['angkatan'] ?? '-' }}</td>
                                                                        <td>
                                                                            <div class="fw-semibold">{{ $repeatGrade }}</div>
                                                                            <div class="peserta-krs-meta">
                                                                                {{ $repeatScore !== null ? 'Nilai akhir ' . $repeatScore : 'Nilai akhir belum tersedia' }}
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $repeatHistory['semester'] ?? '-' }}</td>
                                                                        <td class="peserta-krs-state">
                                                                            <div class="mb-1">
                                                                                <span class="badge {{ $badgeClass }}">
                                                                                    {{ $row['state_label'] ?? 'Status' }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="peserta-krs-meta">
                                                                                {{ $row['reason'] ?? 'Mahasiswa bisa didaftarkan ulang ke KRS pada kelas ini.' }}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr id="pesertaKrsRepeatNoResultRow" class="d-none">
                                                                    <td colspan="7" class="text-center text-muted py-4">
                                                                        Tidak ada mahasiswa pengulangan yang cocok dengan pencarian Anda.
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="jadwal">
                                <div id="content-jadwal">
                                    <div class="text-center p-3">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pemeriksaan & Sinkronisasi Kurikulum Peserta -->
    <div class="modal fade" id="modalSyncKurikulumPeserta" tabindex="-1" aria-labelledby="modalSyncKurikulumPesertaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalSyncKurikulumPesertaLabel">
                        <i class="fas fa-tasks me-2"></i>Pemeriksaan & Sinkronisasi Kurikulum Peserta Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading State -->
                    <div id="syncModalLoading" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="text-muted fw-bold">Memeriksa data kesesuaian kurikulum peserta...</h6>
                    </div>

                    <!-- Content State -->
                    <div id="syncModalContent" class="d-none">
                        <!-- Info Card -->
                        <div class="card bg-light border mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Target Kurikulum Kelas Saat Ini:</div>
                                        <div class="fw-bold fs-6 text-primary" id="syncModalTargetKurikulum">-</div>
                                        <div class="small text-dark fw-semibold mt-1" id="syncModalTargetMk">-</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                                            <div class="text-center px-3 py-2 bg-white rounded border">
                                                <div class="small text-muted">Total Peserta</div>
                                                <div class="fw-bold fs-5 text-dark" id="syncModalTotalPeserta">0</div>
                                            </div>
                                            <div class="text-center px-3 py-2 bg-white rounded border border-warning">
                                                <div class="small text-warning fw-bold">Perlu Sinkronisasi</div>
                                                <div class="fw-bold fs-5 text-warning" id="syncModalMismatchCount">0</div>
                                            </div>
                                            <div class="text-center px-3 py-2 bg-white rounded border border-success">
                                                <div class="small text-success fw-bold">Sudah Sesuai</div>
                                                <div class="fw-bold fs-5 text-success" id="syncModalMatchedCount">0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alert Notice -->
                        <div id="syncModalAlertBox" class="alert alert-warning d-flex align-items-center mb-3">
                            <i class="fas fa-exclamation-triangle fs-4 me-3 text-warning"></i>
                            <div>
                                <span class="fw-bold">Hasil Pemeriksaan:</span> Terdapat <span class="fw-bold text-danger" id="syncModalAlertMismatchText">0 mahasiswa</span> yang mata kuliah di KRS-nya belum sesuai dengan kurikulum kelas saat ini. Centang mahasiswa yang ingin disinkronkan lalu klik tombol <strong>Eksekusi Sinkronisasi</strong>.
                            </div>
                        </div>

                        <!-- Filter & Search Toolbar -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnSyncModalSelectAllNeedsSync">
                                    <i class="fas fa-check-square me-1"></i>Pilih Semua yang Perlu Sync
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnSyncModalClearAll">
                                    <i class="fas fa-square me-1"></i>Kosongkan Pilihan
                                </button>
                                <span class="badge bg-light text-dark border px-2 py-1" id="syncModalSelectedBadge">0 dipilih</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select form-select-sm" id="syncModalStatusFilter" style="width: auto;">
                                    <option value="all">Semua Status</option>
                                    <option value="needs_sync" selected>Hanya Perlu Sinkronisasi</option>
                                    <option value="matched">Hanya Sudah Sesuai</option>
                                </select>
                                <input type="text" class="form-control form-control-sm" id="syncModalSearch" placeholder="Cari NIM atau Nama..." style="width: 220px;">
                            </div>
                        </div>

                        <!-- Table Mahasiswa -->
                        <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                            <table class="table table-bordered table-hover align-middle mb-0" id="syncModalTable">
                                <thead class="table-light sticky-top">
                                    <tr class="text-center small text-nowrap">
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="syncModalCheckAll">
                                        </th>
                                        <th style="width: 45px;">No</th>
                                        <th style="width: 120px;">NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th style="width: 100px;">Jalur/Tipe</th>
                                        <th>Mata Kuliah di KRS Saat Ini</th>
                                        <th style="width: 170px;">Status Kesesuaian</th>
                                    </tr>
                                </thead>
                                <tbody id="syncModalTableBody">
                                    <!-- Filled dynamically by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Sinkronisasi akan menyelaraskan ID mata kuliah di KRS peserta dengan kurikulum kelas saat ini serta menghitung ulang total SKS.
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary fw-bold" id="btnExecuteSyncModal" disabled>
                            <i class="fas fa-sync-alt me-1"></i> Eksekusi Sinkronisasi (<span id="syncModalBtnCount">0</span> Mahasiswa)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.select2').trigger('change');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const $pesertaSearch = $('#pesertaKrsSearch');
            const $pesertaAngkatanFilter = $('#pesertaKrsAngkatanFilter');
            const $pesertaResetFilterBtn = $('#pesertaKrsResetFilterBtn');
            const $pesertaFilterSummary = $('#pesertaKrsFilterSummary');
            const $pesertaRows = $('.peserta-krs-row');
            const $pesertaRepeatRows = $('.peserta-krs-repeat-row');
            const $pesertaSelectableRows = $('.peserta-krs-selectable-row');
            const $pesertaCountBadge = $('#pesertaKrsCountBadge');
            const $pesertaNoResultRow = $('#pesertaKrsNoResultRow');
            const $pesertaRepeatNoResultRow = $('#pesertaKrsRepeatNoResultRow');
            const $pesertaPerPage = $('#pesertaKrsPerPage');
            const $pesertaPrevBtn = $('#pesertaKrsPrevBtn');
            const $pesertaNextBtn = $('#pesertaKrsNextBtn');
            const $pesertaPageInfo = $('#pesertaKrsPageInfo');
            const $pesertaPageLinks = $('#pesertaKrsPageLinks');
            const $pesertaForm = $('#pesertaKrsForm');
            const $pesertaCheckAll = $('#pesertaKrsCheckAll');
            const $pesertaCheckboxes = $('.peserta-krs-checkbox');
            const $pesertaSelectedCount = $('#pesertaKrsSelectedCount');
            const $pesertaSelectVisibleBtn = $('#pesertaKrsSelectVisibleBtn');
            const $pesertaClearBtn = $('#pesertaKrsClearBtn');
            let pesertaCurrentPage = 1;

            function getMatchedPesertaRows() {
                return $pesertaRows.filter(function() {
                    return $(this).data('matched') === true;
                });
            }

            function getMatchedEnabledCheckboxes() {
                return $pesertaSelectableRows.filter(function() {
                    return $(this).data('matched') === true;
                }).find('.peserta-krs-checkbox:not(:disabled)');
            }

            function updatePesertaRowNumbers() {
                let visibleIndex = 1;
                $('.peserta-krs-row:visible').each(function() {
                    $(this).find('.peserta-krs-no').text(visibleIndex++);
                });
            }

            function updateSelectedCount() {
                const selectedCount = $pesertaCheckboxes.filter(':checked').length;
                $pesertaSelectedCount.text(`${selectedCount} mahasiswa dipilih`);
            }

            function updatePesertaFilterSummary() {
                const keyword = ($pesertaSearch.val() || '').trim();
                const angkatan = ($pesertaAngkatanFilter.val() || '').trim();
                const statusText = $('#pesertaKrsStatusFilter option:selected').text();

                const parts = [];

                if (statusText) {
                    parts.push(statusText);
                }

                if (angkatan) {
                    parts.push(`Angkatan ${angkatan}`);
                }

                if (keyword) {
                    parts.push(`Pencarian "${keyword}"`);
                }

                $pesertaFilterSummary.text(`Filter aktif: ${parts.join(' | ')}`);
            }

            function updateMasterCheckboxState() {
                const $matchedEnabled = getMatchedEnabledCheckboxes();

                if (!$matchedEnabled.length) {
                    $pesertaCheckAll.prop({
                        checked: false,
                        indeterminate: false
                    });
                    return;
                }

                const checkedCount = $matchedEnabled.filter(':checked').length;
                $pesertaCheckAll.prop('checked', checkedCount === $matchedEnabled.length);
                $pesertaCheckAll.prop('indeterminate', checkedCount > 0 && checkedCount < $matchedEnabled.length);
            }

            function renderPesertaPagination() {
                const matchedRows = getMatchedPesertaRows();
                const totalMatched = matchedRows.length;
                const totalMatchedAll = $pesertaSelectableRows.filter(function() {
                    return $(this).data('matched') === true;
                }).length;
                const perPage = Number($pesertaPerPage.val() || 10);
                const totalPages = Math.max(1, Math.ceil(totalMatched / perPage));

                if (pesertaCurrentPage > totalPages) {
                    pesertaCurrentPage = totalPages;
                }

                const startIndex = (pesertaCurrentPage - 1) * perPage;
                const endIndex = startIndex + perPage;

                $pesertaRows.each(function() {
                    const $row = $(this);
                    $row.addClass('d-none');
                });

                matchedRows.slice(startIndex, endIndex).removeClass('d-none');

                updatePesertaRowNumbers();
                updateMasterCheckboxState();
                updateSelectedCount();

                if ($pesertaNoResultRow.length) {
                    $pesertaNoResultRow.toggleClass('d-none', totalMatched > 0);
                }

                if ($pesertaRepeatRows.length && $pesertaRepeatNoResultRow.length) {
                    const totalMatchedRepeat = $pesertaRepeatRows.filter(function() {
                        return $(this).data('matched') === true;
                    }).length;
                    $pesertaRepeatNoResultRow.toggleClass('d-none', totalMatchedRepeat > 0);
                }

                const visibleCount = totalMatched === 0 ? 0 : Math.min(endIndex, totalMatched) - startIndex;
                const startLabel = totalMatched === 0 ? 0 : startIndex + 1;
                const endLabel = totalMatched === 0 ? 0 : startIndex + visibleCount;

                $pesertaCountBadge.text(`${totalMatchedAll} Mahasiswa`);

                if ($pesertaPageInfo.length) {
                    $pesertaPageInfo.text(`Menampilkan ${startLabel}-${endLabel} dari ${totalMatched} mahasiswa reguler`);
                }

                if ($pesertaPageLinks.length) {
                    let paginationHtml = '';
                    const pages = [];

                    if (totalPages <= 7) {
                        for (let page = 1; page <= totalPages; page++) {
                            pages.push(page);
                        }
                    } else {
                        pages.push(1);

                        if (pesertaCurrentPage > 3) {
                            pages.push('ellipsis-start');
                        }

                        const startPage = Math.max(2, pesertaCurrentPage - 1);
                        const endPage = Math.min(totalPages - 1, pesertaCurrentPage + 1);

                        for (let page = startPage; page <= endPage; page++) {
                            pages.push(page);
                        }

                        if (pesertaCurrentPage < totalPages - 2) {
                            pages.push('ellipsis-end');
                        }

                        pages.push(totalPages);
                    }

                    pages.forEach((page) => {
                        if (String(page).startsWith('ellipsis')) {
                            paginationHtml += `<span class="px-1 text-muted">...</span>`;
                            return;
                        }

                        paginationHtml += `
                            <button type="button"
                                class="btn btn-sm ${page === pesertaCurrentPage ? 'btn-primary' : 'btn-outline-secondary'} peserta-krs-page-btn"
                                data-page="${page}">
                                ${page}
                            </button>
                        `;
                    });

                    if (!paginationHtml) {
                        paginationHtml = `
                            <button type="button" class="btn btn-sm btn-primary peserta-krs-page-btn" data-page="1">
                                1
                            </button>
                        `;
                    }

                    $pesertaPageLinks.html(paginationHtml);
                }

                if ($pesertaPrevBtn.length) {
                    $pesertaPrevBtn.prop('disabled', pesertaCurrentPage <= 1 || totalMatched === 0);
                }

                if ($pesertaNextBtn.length) {
                    $pesertaNextBtn.prop('disabled', pesertaCurrentPage >= totalPages || totalMatched === 0);
                }
            }

            function filterPesertaKrs() {
                if (!$pesertaSelectableRows.length) {
                    return;
                }

                const keyword = ($pesertaSearch.val() || '').toLowerCase().trim();
                const angkatan = ($pesertaAngkatanFilter.val() || '').trim();

                $pesertaSelectableRows.each(function() {
                    const $row = $(this);
                    const nim = String($row.data('nim') || '');
                    const nama = String($row.data('nama') || '');
                    const rowAngkatan = String($row.data('angkatan') || '').trim();
                    const keywordMatch = !keyword || nim.includes(keyword) || nama.includes(keyword);
                    const angkatanMatch = !angkatan || rowAngkatan === angkatan;
                    const isMatch = keywordMatch && angkatanMatch;

                    $row.data('matched', isMatch);
                    if ($row.hasClass('peserta-krs-repeat-row')) {
                        $row.toggleClass('d-none', !isMatch);
                    }
                });

                pesertaCurrentPage = 1;
                updatePesertaFilterSummary();
                renderPesertaPagination();
            }

            $pesertaPerPage.on('change', function() {
                pesertaCurrentPage = 1;
                renderPesertaPagination();
            });

            $pesertaCheckAll.on('change', function() {
                const shouldCheck = $(this).is(':checked');

                getMatchedEnabledCheckboxes().prop('checked', shouldCheck);
                updateSelectedCount();
                updateMasterCheckboxState();
            });

            $pesertaCheckboxes.on('change', function() {
                updateSelectedCount();
                updateMasterCheckboxState();
            });

            $pesertaSelectVisibleBtn.on('click', function() {
                $('.peserta-krs-row:visible .peserta-krs-checkbox:not(:disabled)').prop('checked', true);
                updateSelectedCount();
                updateMasterCheckboxState();
            });

            $pesertaClearBtn.on('click', function() {
                $pesertaCheckboxes.prop('checked', false);
                updateSelectedCount();
                updateMasterCheckboxState();
            });

            $pesertaPrevBtn.on('click', function() {
                if (pesertaCurrentPage > 1) {
                    pesertaCurrentPage--;
                    renderPesertaPagination();
                }
            });

            $pesertaNextBtn.on('click', function() {
                const matchedRows = getMatchedPesertaRows();
                const perPage = Number($pesertaPerPage.val() || 10);
                const totalPages = Math.max(1, Math.ceil(matchedRows.length / perPage));

                if (pesertaCurrentPage < totalPages) {
                    pesertaCurrentPage++;
                    renderPesertaPagination();
                }
            });

            $(document).on('click', '.peserta-krs-page-btn', function() {
                const page = Number($(this).data('page') || 1);

                if (page > 0) {
                    pesertaCurrentPage = page;
                    renderPesertaPagination();
                }
            });

            if ($pesertaSelectableRows.length) {
                $pesertaSelectableRows.each(function() {
                    $(this).data('matched', true);
                });

                renderPesertaPagination();
            }

            $pesertaForm.on('submit', function(e) {
                const selectedCount = $pesertaCheckboxes.filter(':checked').length;

                if (selectedCount <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum ada mahasiswa dipilih',
                        text: 'Centang minimal satu mahasiswa yang siap didaftarkan terlebih dahulu.'
                    });
                    return;
                }

                const confirmed = window.confirm(`Daftarkan ${selectedCount} mahasiswa ke kelas ini?`);
                if (!confirmed) {
                    e.preventDefault();
                }
            });

            $pesertaSearch.on('input', filterPesertaKrs);
            $pesertaAngkatanFilter.on('change', filterPesertaKrs);

            $('#pesertaKrsStatusFilter').on('change', function() {
                const selectedStatus = $(this).val();
                const url = new URL(window.location.href);
                url.searchParams.set('status_mahasiswa', selectedStatus);
                url.hash = 'mahasiswa';
                window.location.href = url.toString();
            });

            $pesertaResetFilterBtn.on('click', function() {
                $pesertaSearch.val('');
                $pesertaAngkatanFilter.val('');
                filterPesertaKrs();

                const currentStatus = $('#pesertaKrsStatusFilter').val();
                if (currentStatus !== 'aktif') {
                    const url = new URL(window.location.href);
                    url.searchParams.set('status_mahasiswa', 'aktif');
                    url.hash = 'mahasiswa';
                    window.location.href = url.toString();
                }
            });

            // Aktifkan tab sesuai hash URL jika ada (misal #mahasiswa)
            const currentHash = window.location.hash;
            if (currentHash) {
                const targetTabTrigger = document.querySelector(`a[href="${currentHash}"]`);
                if (targetTabTrigger && window.bootstrap && window.bootstrap.Tab) {
                    const tabInstance = bootstrap.Tab.getOrCreateInstance(targetTabTrigger);
                    tabInstance.show();
                }
            }

            // Simpan hash saat tab diganti
            $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                const targetHref = $(e.target).attr('href');
                if (targetHref && window.history.replaceState) {
                    const url = new URL(window.location.href);
                    url.hash = targetHref;
                    window.history.replaceState(null, '', url.toString());
                }
            });

            // ==========================================
            // Logika Cascading Kurikulum & Preview MK
            // ==========================================
            const rawKurikulum = @json($kurikulum);
            const rawKurikulumMk = @json($kurikulum_matakuliah);

            const $prodi = $('#id_prodi');
            const $semester = $('#id_semester');
            const $kurikulum = $('#id_kurikulum');
            const $mataKuliah = $('#id_kurikulum_mata_kuliah');

            const initialProdi = "{{ old('id_prodi', $kelaskuliah['id_prodi'] ?? '') }}";
            const initialKurikulum = "{{ $currentKurikulumId }}";
            const initialMk = "{{ $currentMkId }}";

            function populateKurikulum(prodiId, selectedKurikulumId = null, selectedMkId = null) {
                $kurikulum.empty();

                if (!prodiId) {
                    $kurikulum.append('<option value="" disabled selected>-- Pilih Program Studi Terlebih Dahulu --</option>');
                    $kurikulum.trigger('change.select2');
                    populateMataKuliah(null, null);
                    return;
                }

                const filtered = rawKurikulum.filter(item => String(item.id_prodi) === String(prodiId));

                if (filtered.length === 0) {
                    $kurikulum.append('<option value="" disabled selected>-- Tidak ada Kurikulum untuk Prodi ini --</option>');
                } else {
                    $kurikulum.append('<option value="" disabled selected>-- Pilih Struktur Kurikulum --</option>');
                    filtered.forEach(k => {
                        const label = k.kurikulum || (k.kode_kurikulum + ' - ' + (k.nama_kurikulum || k.nama_struktur_mk));
                        const isSelected = selectedKurikulumId && String(k.id) === String(selectedKurikulumId);
                        $kurikulum.append(new Option(label, k.id, false, isSelected));
                    });
                }

                if (filtered.length === 1 && !selectedKurikulumId) {
                    $kurikulum.val(filtered[0].id);
                } else if (selectedKurikulumId) {
                    $kurikulum.val(selectedKurikulumId);
                }

                $kurikulum.trigger('change.select2');

                const activeKurId = $kurikulum.val();
                populateMataKuliah(activeKurId, selectedMkId);
            }

            function populateMataKuliah(kurikulumId, selectedMkId = null) {
                $mataKuliah.empty();

                if (!kurikulumId) {
                    $mataKuliah.append('<option value="" disabled selected>-- Pilih Struktur Kurikulum Terlebih Dahulu --</option>');
                    $mataKuliah.trigger('change.select2');
                    updateMkPreview(null);
                    return;
                }

                const filtered = rawKurikulumMk.filter(item => String(item.id_kurikulum) === String(kurikulumId));

                if (filtered.length === 0) {
                    $mataKuliah.append('<option value="" disabled selected>-- Tidak ada Mata Kuliah pada Kurikulum ini --</option>');
                } else {
                    $mataKuliah.append('<option value="" disabled selected>-- Pilih Mata Kuliah --</option>');

                    // Kelompokkan per semester_ke
                    const groups = {};
                    filtered.forEach(mk => {
                        const sem = mk.semester_ke || 'Lainnya';
                        if (!groups[sem]) groups[sem] = [];
                        groups[sem].push(mk);
                    });

                    const sortedSemesters = Object.keys(groups).sort((a, b) => {
                        const numA = parseInt(a), numB = parseInt(b);
                        if (isNaN(numA) && isNaN(numB)) return a.localeCompare(b);
                        if (isNaN(numA)) return 1;
                        if (isNaN(numB)) return -1;
                        return numA - numB;
                    });

                    sortedSemesters.forEach(sem => {
                        const groupLabel = isNaN(parseInt(sem)) ? sem : `Semester ${sem}`;
                        const $optgroup = $(`<optgroup label="${groupLabel}"></optgroup>`);

                        groups[sem].sort((a, b) => (a.kode_mk || '').localeCompare(b.kode_mk || ''));

                        groups[sem].forEach(mk => {
                            const sifat = mk.status_mk ? mk.status_mk.toUpperCase() : (mk.is_wajib ? 'WAJIB' : 'PILIHAN');
                            const label = `[${mk.kode_mk || '-'}] ${mk.nama_mk || '-'} (${mk.sks || 0} SKS) - ${sifat}`;
                            const isSelected = selectedMkId && String(mk.id) === String(selectedMkId);
                            $optgroup.append(new Option(label, mk.id, false, isSelected));
                        });

                        $mataKuliah.append($optgroup);
                    });
                }

                if (selectedMkId) {
                    $mataKuliah.val(selectedMkId);
                }

                $mataKuliah.trigger('change.select2');
                updateMkPreview($mataKuliah.val());
            }

            function updateMkPreview(mkId) {
                if (!mkId) {
                    $('#mk-preview-card').addClass('d-none');
                    return;
                }

                const mk = rawKurikulumMk.find(item => String(item.id) === String(mkId));
                if (!mk) {
                    $('#mk-preview-card').addClass('d-none');
                    return;
                }

                $('#preview-kode-mk').text(mk.kode_mk || '-');
                $('#preview-nama-mk').text(mk.nama_mk || '-');
                $('#preview-kurikulum-badge').text(mk.nama_kurikulum || 'Kurikulum');

                const isWajib = mk.is_wajib || (mk.status_mk && String(mk.status_mk).toLowerCase() === 'wajib');
                $('#preview-sifat-badge')
                    .removeClass('bg-danger bg-info text-dark')
                    .addClass(isWajib ? 'bg-danger' : 'bg-info text-dark')
                    .text(isWajib ? 'Wajib' : 'Pilihan');

                $('#preview-semester-badge').text('Semester Paket ' + (mk.semester_ke || '-'));

                $('#preview-sks-total').text((mk.sks || 0) + ' SKS');
                $('#preview-sks-tm').text((mk.sks_tatap_muka || 0) + ' SKS');
                const prakSks = Number(mk.sks_praktikum || 0) + Number(mk.sks_praktek_lapangan || 0);
                $('#preview-sks-prak').text(prakSks + ' SKS');
                $('#preview-sks-sim').text((mk.sks_simulasi || 0) + ' SKS');

                // Sinkronkan input form SKS readonly
                if ($('#sks').length) $('#sks').val(mk.sks ?? 0);
                if ($('#sks_tatap_muka').length) $('#sks_tatap_muka').val(mk.sks_tatap_muka ?? 0);
                if ($('#sks_praktikum').length) $('#sks_praktikum').val(mk.sks_praktikum ?? 0);
                if ($('#sks_praktik_lapangan').length) $('#sks_praktik_lapangan').val(mk.sks_praktek_lapangan ?? 0);
                if ($('#sks_simulasi').length) $('#sks_simulasi').val(mk.sks_simulasi ?? 0);

                // Validasi kesesuaian periode semester (Ganjil / Genap)
                const semText = $('#id_semester option:selected').text().toLowerCase();
                const mkSem = parseInt(mk.semester_ke);

                if (semText && !isNaN(mkSem)) {
                    const isGanjilSem = semText.includes('ganjil') || semText.includes('gasal');
                    const isGenapSem = semText.includes('genap');
                    const isMkGanjil = (mkSem % 2 !== 0);

                    if ((isGanjilSem && isMkGanjil) || (isGenapSem && !isMkGanjil)) {
                        $('#preview-kesesuaian-badge')
                            .removeClass('d-none bg-warning text-dark')
                            .addClass('badge bg-success')
                            .html('<i class="fas fa-check-circle me-1"></i> Sesuai Periode (' + (isMkGanjil ? 'Ganjil' : 'Genap') + ')');
                    } else if (isGanjilSem || isGenapSem) {
                        $('#preview-kesesuaian-badge')
                            .removeClass('d-none bg-success')
                            .addClass('badge bg-warning text-dark')
                            .html('<i class="fas fa-exclamation-triangle me-1"></i> Beda Periode (MK Semester ' + mkSem + ' di Semester ' + (isGanjilSem ? 'Ganjil' : 'Genap') + ')');
                    } else {
                        $('#preview-kesesuaian-badge').addClass('d-none');
                    }
                } else {
                    $('#preview-kesesuaian-badge').addClass('d-none');
                }

                $('#mk-preview-card').removeClass('d-none');
            }

            // Event Handlers
            $prodi.on('change', function() {
                populateKurikulum($(this).val(), null, null);
            });

            $kurikulum.on('change', function() {
                populateMataKuliah($(this).val(), null);
            });

            $mataKuliah.on('change', function() {
                updateMkPreview($(this).val());
            });

            $semester.on('change', function() {
                if ($mataKuliah.val()) {
                    updateMkPreview($mataKuliah.val());
                }
            });

            // Initial load populate
            if (initialProdi) {
                populateKurikulum(initialProdi, initialKurikulum, initialMk);
            }
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus Kelas Kuliah "${nama}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('kelas-kuliah.destroy', '__ID__') }}`.replace(
                            '__ID__', id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('kelas-kuliah.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // ==========================================
        // FITUR PEMERIKSAAN & SINKRONISASI KURIKULUM PESERTA
        // ==========================================
        let currentSyncData = null;
        let activeKelasIdForSync = null;

        function renderSyncModalTable() {
            if (!currentSyncData || !currentSyncData.peserta) return;

            const tbody = $('#syncModalTableBody');
            tbody.empty();

            const statusFilter = $('#syncModalStatusFilter').val();
            const keyword = ($('#syncModalSearch').val() || '').toLowerCase().trim();

            let visibleCount = 0;

            currentSyncData.peserta.forEach((item, index) => {
                // Filter status
                if (statusFilter === 'needs_sync' && !item.needs_sync) return;
                if (statusFilter === 'matched' && item.needs_sync) return;

                // Filter keyword
                if (keyword) {
                    const matchNim = (item.nim || '').toLowerCase().includes(keyword);
                    const matchNama = (item.nama_mahasiswa || '').toLowerCase().includes(keyword);
                    if (!matchNim && !matchNama) return;
                }

                visibleCount++;

                const badgeJalur = item.jenis_pendaftaran === 'RPL' 
                    ? '<span class="badge bg-warning text-dark">RPL</span>' 
                    : '<span class="badge bg-primary">Reguler</span>';

                let badgeKesesuaian = '';
                if (item.needs_sync) {
                    badgeKesesuaian = '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle me-1"></i>Perlu Sync</span>';
                } else {
                    badgeKesesuaian = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Sudah Sesuai</span>';
                }

                if (item.keterangan_tipe) {
                    badgeKesesuaian += '<br><small class="text-danger fw-semibold">' + item.keterangan_tipe + '</small>';
                }

                const isChecked = item.selected ? 'checked' : '';
                const rowBg = item.needs_sync ? 'table-warning-subtle' : '';

                const tr = $('<tr>').addClass(rowBg).attr('data-id-mhs', item.id_mahasiswa);
                tr.append(
                    $('<td class="text-center">').html(
                        '<input type="checkbox" class="sync-modal-mhs-cb form-check-input" value="' + (item.id_mahasiswa || '') + '" data-detail-id="' + item.id_krs_detail + '" ' + isChecked + '>'
                    ),
                    $('<td class="text-center small text-muted">').text(visibleCount),
                    $('<td>').html('<span class="fw-semibold font-monospace">' + (item.nim || '-') + '</span>'),
                    $('<td>').html('<span class="fw-bold text-dark">' + (item.nama_mahasiswa || '-') + '</span><br><small class="text-muted">Angkatan ' + (item.angkatan || '-') + '</small>'),
                    $('<td class="text-center">').html(badgeJalur),
                    $('<td>').html('<span class="small ' + (item.needs_sync ? 'text-danger fw-semibold' : 'text-dark') + '">' + (item.current_mk_nama || '-') + '</span>'),
                    $('<td class="text-center">').html(badgeKesesuaian)
                );

                tbody.append(tr);
            });

            if (visibleCount === 0) {
                tbody.html('<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-info-circle me-1"></i>Tidak ada data mahasiswa yang sesuai dengan filter.</td></tr>');
            }

            updateSyncModalSelectedSummary();
        }

        function updateSyncModalSelectedSummary() {
            if (!currentSyncData || !currentSyncData.peserta) return;

            const selectedCount = currentSyncData.peserta.filter(p => p.selected).length;
            $('#syncModalSelectedBadge').text(selectedCount + ' dipilih');
            $('#syncModalBtnCount').text(selectedCount);

            if (selectedCount > 0) {
                $('#btnExecuteSyncModal').prop('disabled', false);
            } else {
                $('#btnExecuteSyncModal').prop('disabled', true);
            }

            // Update CheckAll checkbox state
            const visibleCheckboxes = $('.sync-modal-mhs-cb');
            const checkedVisible = $('.sync-modal-mhs-cb:checked');
            if (visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisible.length) {
                $('#syncModalCheckAll').prop('checked', true);
            } else {
                $('#syncModalCheckAll').prop('checked', false);
            }
        }

        // Buka modal saat tombol .btn-sync-krs diklik
        $(document).on('click', '.btn-sync-krs', function(e) {
            e.preventDefault();
            activeKelasIdForSync = $(this).data('id');
            const previewUrl = "{{ url('/kelas-kuliah/detail') }}/" + activeKelasIdForSync + "/sync-krs-preview";

            $('#modalSyncKurikulumPeserta').modal('show');
            $('#syncModalLoading').removeClass('d-none');
            $('#syncModalContent').addClass('d-none');
            $('#btnExecuteSyncModal').prop('disabled', true);

            $.ajax({
                url: previewUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data) {
                        currentSyncData = response.data;
                        const kelasInfo = currentSyncData.kelas || {};
                        const summary = currentSyncData.summary || {};

                        // Tampilkan Info Target Kurikulum & MK
                        const targetKurikulum = (kelasInfo.target_kurikulum && kelasInfo.target_kurikulum.nama) ? kelasInfo.target_kurikulum.nama : '-';
                        const targetMk = (kelasInfo.target_mata_kuliah) ? (kelasInfo.target_mata_kuliah.kode + ' - ' + kelasInfo.target_mata_kuliah.nama + ' (' + kelasInfo.target_mata_kuliah.sks + ' SKS)') : '-';

                        $('#syncModalTargetKurikulum').text(targetKurikulum);
                        $('#syncModalTargetMk').text(targetMk);

                        // Tampilkan Counter
                        $('#syncModalTotalPeserta').text(summary.total_peserta || 0);
                        $('#syncModalMismatchCount').text(summary.mismatch_count || 0);
                        $('#syncModalMatchedCount').text(summary.matched_count || 0);
                        $('#syncModalAlertMismatchText').text((summary.mismatch_count || 0) + ' mahasiswa');

                        if ((summary.mismatch_count || 0) === 0) {
                            $('#syncModalAlertBox').removeClass('alert-warning').addClass('alert-success').html(
                                '<i class="fas fa-check-circle fs-4 me-3 text-success"></i><div><strong>Semua Sesuai:</strong> Seluruh peserta di kelas ini sudah tersinkronkan dengan kurikulum kelas saat ini.</div>'
                            );
                            $('#syncModalStatusFilter').val('all');
                        } else {
                            $('#syncModalAlertBox').removeClass('alert-success').addClass('alert-warning').html(
                                '<i class="fas fa-exclamation-triangle fs-4 me-3 text-warning"></i><div><strong>Hasil Pemeriksaan:</strong> Terdapat <strong>' + summary.mismatch_count + ' mahasiswa</strong> yang mata kuliah di KRS-nya belum sesuai dengan kurikulum kelas saat ini. Centang mahasiswa yang ingin disinkronkan lalu klik tombol <strong>Eksekusi Sinkronisasi</strong>.</div>'
                            );
                            $('#syncModalStatusFilter').val('needs_sync');
                        }

                        // Default: centang semua yang needs_sync
                        if (currentSyncData.peserta) {
                            currentSyncData.peserta.forEach(p => {
                                p.selected = (p.needs_sync === true);
                            });
                        }

                        $('#syncModalLoading').addClass('d-none');
                        $('#syncModalContent').removeClass('d-none');

                        renderSyncModalTable();
                    } else {
                        $('#modalSyncKurikulumPeserta').modal('hide');
                        Swal.fire('Gagal', response.message || 'Gagal memuat data pemeriksaan.', 'error');
                    }
                },
                error: function(xhr) {
                    $('#modalSyncKurikulumPeserta').modal('hide');
                    let msg = 'Terjadi kesalahan saat memeriksa data kurikulum peserta.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        });

        // Event filter & search pada modal
        $(document).on('change', '#syncModalStatusFilter', function() {
            renderSyncModalTable();
        });

        $(document).on('input', '#syncModalSearch', function() {
            renderSyncModalTable();
        });

        // Check/uncheck per baris
        $(document).on('change', '.sync-modal-mhs-cb', function() {
            const mhsId = $(this).val();
            const isChecked = $(this).is(':checked');

            if (currentSyncData && currentSyncData.peserta) {
                const item = currentSyncData.peserta.find(p => p.id_mahasiswa === mhsId);
                if (item) {
                    item.selected = isChecked;
                }
            }

            updateSyncModalSelectedSummary();
        });

        // Check All visible
        $(document).on('change', '#syncModalCheckAll', function() {
            const isChecked = $(this).is(':checked');
            $('.sync-modal-mhs-cb').each(function() {
                const mhsId = $(this).val();
                $(this).prop('checked', isChecked);
                if (currentSyncData && currentSyncData.peserta) {
                    const item = currentSyncData.peserta.find(p => p.id_mahasiswa === mhsId);
                    if (item) {
                        item.selected = isChecked;
                    }
                }
            });
            updateSyncModalSelectedSummary();
        });

        // Tombol Pilih Semua yang Perlu Sync
        $(document).on('click', '#btnSyncModalSelectAllNeedsSync', function() {
            if (!currentSyncData || !currentSyncData.peserta) return;
            currentSyncData.peserta.forEach(p => {
                p.selected = (p.needs_sync === true);
            });
            renderSyncModalTable();
        });

        // Tombol Kosongkan Pilihan
        $(document).on('click', '#btnSyncModalClearAll', function() {
            if (!currentSyncData || !currentSyncData.peserta) return;
            currentSyncData.peserta.forEach(p => {
                p.selected = false;
            });
            renderSyncModalTable();
        });

        // Tombol Eksekusi Sinkronisasi
        $(document).on('click', '#btnExecuteSyncModal', function() {
            if (!currentSyncData || !activeKelasIdForSync) return;

            const selectedMhs = currentSyncData.peserta.filter(p => p.selected);
            if (selectedMhs.length === 0) {
                Swal.fire('Perhatian', 'Pilih minimal satu mahasiswa untuk disinkronkan.', 'warning');
                return;
            }

            const targetMkNama = $('#syncModalTargetMk').text();

            Swal.fire({
                title: 'Eksekusi Sinkronisasi?',
                html: 'Anda akan menyinkronkan <b>' + selectedMhs.length + ' mahasiswa</b> ke mata kuliah:<br><span class="badge bg-primary fs-6 mt-2">' + targetMkNama + '</span><br><br>Sistem akan memperbarui KRS peserta dan menghitung ulang total SKS.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sync-alt me-1"></i> Ya, Eksekusi Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menyinkronkan...',
                        text: 'Mohon tunggu, sedang memperbarui KRS peserta terpilih.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const syncUrl = "{{ url('/kelas-kuliah/detail') }}/" + activeKelasIdForSync + "/sync-krs";
                    const selectedIds = selectedMhs.map(p => p.id_mahasiswa);

                    $.ajax({
                        url: syncUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            mahasiswa_ids: selectedIds
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#modalSyncKurikulumPeserta').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sinkronisasi Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', response.message || 'Gagal menyinkronkan data.', 'error');
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Terjadi kesalahan saat menyinkronkan data peserta.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Gagal', msg, 'error');
                        }
                    });
                }
            });
        });
    </script>

    @include('masterdata.kelaskuliah.scripts.dosen-pengajar-script')
    @include('masterdata.kelaskuliah.scripts.jadwal-script')
@endpush
