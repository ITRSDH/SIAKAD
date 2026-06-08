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
                        <form id="form-kelas-kuliah" action="{{ route('kelas-kuliah.update', $kelaskuliah['id']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- PRODI -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value=""></option>
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
                                        <label for="id_semester" class="form-label">Semester</label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value=""></option>
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
                                <!-- MATA KULIAH -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_mata_kuliah" class="form-label">Mata Kuliah</label>
                                        <select class="form-select select2" id="id_kurikulum_mata_kuliah"
                                            name="id_kurikulum_mata_kuliah" required>
                                            <option value=""></option>
                                            @foreach ($kurikulum_matakuliah as $mk)
                                                <option value="{{ $mk['id'] }}"
                                                    {{ old('id_kurikulum_mata_kuliah', $kelaskuliah['id_kurikulum_mata_kuliah'] ?? '') == $mk['id'] ? 'selected' : '' }}>
                                                    {{ $mk['matakuliah'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- NAMA KELAS -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                            value="{{ old('nama_kelas', $kelaskuliah['nama_kelas'] ?? '') }}" required>
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
                                            <span class="badge bg-primary px-3 py-2"
                                                id="pesertaKrsCountBadge">{{ count($krsCandidates ?? []) }} Mahasiswa</span>
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
                                                    <input type="text" class="form-control peserta-krs-search"
                                                        id="pesertaKrsSearch" placeholder="Cari mahasiswa atau NIM...">
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
                                                                data-nama="{{ strtolower((string) ($row['nama_mahasiswa'] ?? '')) }}">
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
                                                                        data-nama="{{ strtolower((string) ($row['nama_mahasiswa'] ?? '')) }}">
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

                $pesertaSelectableRows.each(function() {
                    const $row = $(this);
                    const nim = String($row.data('nim') || '');
                    const nama = String($row.data('nama') || '');
                    const isMatch = !keyword || nim.includes(keyword) || nama.includes(keyword);

                    $row.data('matched', isMatch);
                    if ($row.hasClass('peserta-krs-repeat-row')) {
                        $row.toggleClass('d-none', !isMatch);
                    }
                });

                pesertaCurrentPage = 1;
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
    </script>

    @include('masterdata.kelaskuliah.scripts.dosen-pengajar-script')
    @include('masterdata.kelaskuliah.scripts.jadwal-script')
@endpush
