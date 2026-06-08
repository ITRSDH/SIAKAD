@extends('layouts.index')
@section('title', 'Administrasi Studi Mahasiswa')

@php
    $summaryCards = $workspaceSummary['summary_cards'] ?? [];
    $semesterOptions = $filters['semester'] ?? [];
    $prodiOptions = $filters['prodi'] ?? [];
    $semesterKeOptions = $filters['semester_ke_options'] ?? [];
    $formatDateTime = function ($value) {
        if (!$value) {
            return '-';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)
                ->timezone(config('app.timezone'))
                ->translatedFormat('d M Y H:i');
        } catch (\Throwable $exception) {
            return $value;
        }
    };
@endphp

@push('styles-custom')
    <style>
        .study-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.18), transparent 28%),
                linear-gradient(135deg, #0f3d3e 0%, #16697a 52%, #f4a261 100%);
            color: #fff;
        }

        .study-shell,
        .study-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 16px 38px rgba(15, 23, 42, 0.06);
        }

        .study-tab-card {
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 1rem;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            height: 100%;
            min-width: 0;
            overflow: hidden;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .study-tab-card>* {
            min-width: 0;
        }

        .study-tab-card .d-flex,
        .study-tab-card .d-grid,
        .study-tab-card .row,
        .study-tab-card [class*="col-"] {
            min-width: 0;
        }

        .study-tab-card .btn {
            white-space: normal;
        }

        .study-tab-card code,
        .study-tab-card strong,
        .study-tab-card span,
        .study-tab-card div,
        .study-tab-card p {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #studyHistoricalBuildModeHelper,
        #studyHistoricalMutationSelectionSummary,
        #khsPreviewHelper,
        #khsPreviewPanel {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .study-stat {
            border-radius: 20px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            height: 100%;
        }

        .study-stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .study-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            padding: .45rem .8rem;
            font-size: .82rem;
        }

        .study-mini-list {
            display: grid;
            gap: .75rem;
        }

        .study-mini-item {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: .9rem 1rem;
            background: #fff;
        }

        .study-bridge {
            border: 1px solid #c7d2fe;
            background: linear-gradient(180deg, #f8faff 0%, #eef4ff 100%);
            border-radius: 20px;
            padding: 1rem;
        }

        .study-iframe {
            width: 100%;
            min-height: 840px;
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: #fff;
        }

        .study-native-panel {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }

        .study-collective-hero {
            border: 1px solid #bfdbfe;
            border-radius: 24px;
            padding: 1.2rem;
            background:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
        }

        .study-collective-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: .82rem;
            font-weight: 700;
        }

        .study-step-board {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }

        .study-step-card {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.82);
            padding: 1rem;
        }

        .study-step-card .step-no {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            background: #dbeafe;
            color: #1d4ed8;
            margin-bottom: .7rem;
        }

        .study-section-shell {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.05);
        }

        .study-section-shell .section-head {
            padding: 1rem 1.15rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .study-section-shell .section-body {
            padding: 1rem 1.15rem 1.15rem;
        }

        .study-section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .32rem .65rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .study-action-note {
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
            padding: .85rem 1rem;
        }

        .study-collective-table thead th {
            background: #f8fafc;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #475569;
        }

        .study-collective-table-wrap {
            max-height: 560px;
            overflow: auto;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
            transition: scrollbar-color 0.2s ease;
        }

        .study-collective-table-wrap:hover {
            scrollbar-color: rgba(148, 163, 184, 0.9) transparent;
        }

        .study-collective-table-wrap::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .study-collective-table-wrap::-webkit-scrollbar-track {
            background: transparent;
        }

        .study-collective-table-wrap::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .study-collective-table-wrap:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.9);
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .study-filter-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: .9rem;
        }

        .study-filter-strip .summary {
            color: #64748b;
            font-size: .88rem;
        }

        .study-pager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: .9rem;
        }

        .study-pager .info {
            color: #64748b;
            font-size: .88rem;
        }

        .study-collective-side {
            display: grid;
            gap: 1rem;
        }

        .study-soft-box {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            padding: 1rem;
        }

        .study-course-row {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: .75rem;
            background: #fcfdff;
        }

        .study-builder-card {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            padding: 1rem;
        }

        .study-builder-shell {
            display: grid;
            grid-template-columns: minmax(260px, 1fr) minmax(0, 2fr);
            gap: 1rem;
            align-items: start;
        }

        .study-builder-nav {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: #fff;
            padding: .85rem;
            position: sticky;
            top: 1rem;
        }

        .study-builder-nav-list {
            display: grid;
            gap: .65rem;
            max-height: 640px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
            transition: scrollbar-color 0.2s ease;
        }

        .study-builder-nav-list:hover {
            scrollbar-color: rgba(148, 163, 184, 0.9) transparent;
        }

        .study-builder-nav-list::-webkit-scrollbar {
            width: 10px;
        }

        .study-builder-nav-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .study-builder-nav-list::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .study-builder-nav-list:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.9);
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .study-builder-nav-item {
            width: 100%;
            text-align: left;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            padding: .8rem .9rem;
        }

        .study-builder-nav-item.active {
            border-color: #93c5fd;
            background: #eff6ff;
            box-shadow: inset 0 0 0 1px #bfdbfe;
        }

        .study-builder-nav-item .meta {
            color: #64748b;
            font-size: .82rem;
        }

        .study-builder-stage {
            display: grid;
            gap: 1rem;
        }

        .study-builder-card.d-none {
            display: none !important;
        }

        .study-builder-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: .9rem;
        }

        .study-builder-toolbar .summary {
            color: #64748b;
            font-size: .88rem;
        }

        .study-preview-item {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: .9rem 1rem;
            background: #fff;
        }

        .study-preview-item.ready,
        .study-preview-item.executed {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }

        .study-preview-item.skipped {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .study-preview-item.failed {
            border-color: #fecaca;
            background: #fef2f2;
        }

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

        @media (max-width: 991.98px) {
            .study-step-board {
                grid-template-columns: 1fr;
            }

            .study-builder-shell {
                grid-template-columns: 1fr;
            }

            .study-builder-nav {
                position: static;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Administrasi Studi Mahasiswa</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi
                        Mahasiswa</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card study-hero mb-4">
            <div class="card-body p-4">
                <div class="study-badge">
                    <i class="fas fa-layer-group"></i>
                    <span>Satu workspace untuk KRS, nilai, dan KHS administratif</span>
                </div>
                <div class="row g-4 align-items-end mt-1">
                    <div class="col-xl-8">
                        <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 3vw, 2.7rem); line-height: 1.08;">
                            Kelola administrasi studi mahasiswa tanpa berpindah-pindah modul.
                        </h1>
                        <p class="mb-0 text-white-50">
                            Halaman ini dipakai untuk mengatur KRS bersama-sama, mengelola riwayat studi,
                            memasukkan nilai, lalu menyiapkan KHS dari data yang sudah selesai diperiksa.
                        </p>
                    </div>
                    <div class="col-xl-4">
                        <div class="study-shell bg-white bg-opacity-10 border-0 p-3">
                            <div class="small text-white-50 mb-2">Langkah yang bisa dilakukan</div>
                            <div class="small">1. Buat KRS saja</div>
                            <div class="small">2. Buat KRS, masukkan nilai, lalu buat KHS</div>
                            <div class="small">3. Masukkan nilai ke KRS yang sudah ada, lalu buat KHS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card study-shell mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select select2" id="studySemesterId">
                            <option value="">Semua semester</option>
                            @foreach ($semesterOptions as $item)
                                @php
                                    $tahun =
                                        $item['tahun_akademik']['tahun_akademik'] ??
                                        ($item['tahunAkademik']['tahun_akademik'] ?? '');
                                    $label = trim(($item['nama_semester'] ?? 'Semester') . ' ' . $tahun);
                                @endphp
                                <option value="{{ $item['id'] }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select select2" id="studyProdiId">
                            <option value="">Semua prodi</option>
                            @foreach ($prodiOptions as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] ?? 'Program Studi' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Angkatan</label>
                        <input type="number" id="studyAngkatan" class="form-control" placeholder="Contoh 2024">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semester Ke</label>
                        <select class="form-select select2" id="studySemesterKe">
                            <option value="">Semua</option>
                            @foreach ($semesterKeOptions as $item)
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" id="refreshStudySummaryBtn">
                            <i class="fas fa-arrows-rotate me-1"></i> Perbarui Ringkasan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4" id="studySummaryCards">
            @foreach ($summaryCards as $card)
                <div class="col-md-6 col-xl-3">
                    <div class="study-stat">
                        <div class="small text-muted text-uppercase mb-2">{{ $card['label'] ?? '-' }}</div>
                        <div class="study-stat-value text-{{ $card['tone'] ?? 'primary' }}">{{ $card['value'] ?? 0 }}
                        </div>
                        <div class="small text-muted mt-2">{{ $card['description'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card study-shell mb-4">
            <div class="card-header border-0 pt-4 px-4">
                <h4 class="mb-1">Tab Workspace</h4>
                <p class="text-muted mb-0">Pilih tab sesuai kebutuhan. Semua proses utama administrasi studi mahasiswa ada
                    di halaman ini.</p>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-pills gap-2 mb-4" id="studyTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'konteks' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-konteks" type="button"
                            data-tab-key="konteks">Ringkasan & Konteks</button></li>
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'krs' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-krs" type="button" data-tab-key="krs">KRS
                            Kolektif</button></li>
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'riwayat' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-riwayat" type="button"
                            data-tab-key="riwayat">Riwayat Studi</button></li>
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'import' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-import" type="button"
                            data-tab-key="import">Import Nilai</button></li>
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'khs' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-khs" type="button" data-tab-key="khs">Generate
                            KHS</button></li>
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'batch' ? 'active' : '' }}"
                            data-bs-toggle="pill" data-bs-target="#tab-batch" type="button"
                            data-tab-key="batch">Riwayat Proses</button></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade {{ $activeTab === 'konteks' ? 'show active' : '' }}" id="tab-konteks">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="study-tab-card">
                                    <div class="fw-semibold mb-2">Mulai dari data yang sudah ada</div>
                                    <div class="text-muted small">Anda tidak harus selalu mulai dari pembuatan KRS. Halaman
                                        ini membantu melihat data yang sudah siap dan menentukan langkah berikutnya.</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="study-tab-card">
                                    <div class="fw-semibold mb-2">Satu filter untuk semua tab</div>
                                    <div class="text-muted small">Filter semester, program studi, angkatan, dan semester
                                        ke di bagian atas akan dipakai bersama di seluruh tab workspace ini.</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="study-tab-card">
                                    <div class="fw-semibold mb-2">Ringkasan untuk membantu memilih proses</div>
                                    <div class="text-muted small">Status mahasiswa membantu Anda menentukan apakah perlu
                                        lanjut ke KRS Kolektif, Import Nilai, atau Generate KHS.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $activeTab === 'krs' ? 'show active' : '' }}" id="tab-krs">
                        <div class="row g-4">
                            <div class="col-xl-12">
                                <div class="study-collective-side">
                                    <div class="study-collective-hero">
                                        <div class="study-collective-badge">
                                            <i class="fas fa-users-rectangle"></i>
                                            <span>KRS Kolektif Historis</span>
                                        </div>
                                        <h4 class="fw-bold mt-3 mb-2">Bangun KRS semester lampau dengan alur yang lebih terarah.</h4>
                                        <p class="text-muted small mb-0">
                                            Tab ini paling cocok saat Anda perlu mendaftarkan banyak mahasiswa sekaligus,
                                            lalu melengkapi kelas dan nilai historisnya tanpa pindah halaman.
                                        </p>
                                    </div>
                                    <div class="study-bridge">
                                        <div class="fw-semibold mb-2">Sebelum mulai</div>
                                        <div class="small text-muted mb-2">Isi filter bagian atas agar hasil yang dimuat sesuai konteks kerja Anda.</div>
                                        <div class="small mb-1">1. Pilih semester target</div>
                                        <div class="small mb-1">2. Pilih program studi</div>
                                        <div class="small mb-1">3. Isi angkatan bila perlu</div>
                                        <div class="small">4. Tentukan semester ke</div>
                                    </div>
                                    <div class="study-bridge">
                                        <div class="fw-semibold mb-2">Setelah selesai</div>
                                        <div class="small text-muted mb-3">Jika KRS historis sudah terbentuk dan perlu tindakan lanjutan, Anda bisa pindah ke tab riwayat studi.</div>
                                        <a href="{{ route('akademik.administrasi-studi.index', ['tab' => 'riwayat']) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-arrow-right me-1"></i> Ke Tab Riwayat
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="study-collective-hero mb-3">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="study-collective-badge">
                                                <i class="fas fa-route"></i>
                                                <span>Alur kerja tab ini</span>
                                            </div>
                                            <div class="small text-muted mt-3">
                                                Ikuti urutan berikut agar proses terasa lebih ringan dan tidak perlu bolak-balik.
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary"
                                            id="studyHistoricalLoadBtn">
                                            <i class="fas fa-book-open me-1"></i> Muat Data Historis
                                        </button>
                                    </div>
                                    <div class="study-step-board mt-3">
                                        <div class="study-step-card">
                                            <div class="step-no">1</div>
                                            <div class="fw-semibold mb-1">Muat konteks</div>
                                            <div class="small text-muted">Ambil mahasiswa dan kelas sesuai filter semester, prodi, dan semester ke.</div>
                                        </div>
                                        <div class="study-step-card">
                                            <div class="step-no">2</div>
                                            <div class="fw-semibold mb-1">Pilih mahasiswa</div>
                                            <div class="small text-muted">Tentukan siapa saja yang akan dibuatkan KRS semester lampau.</div>
                                        </div>
                                        <div class="study-step-card">
                                            <div class="step-no">3</div>
                                            <div class="fw-semibold mb-1">Siapkan kelas dan nilai</div>
                                            <div class="small text-muted">Pilih mode build lalu isi kelas atau nilai historis bila memang dibutuhkan.</div>
                                        </div>
                                        <div class="study-step-card">
                                            <div class="step-no">4</div>
                                            <div class="fw-semibold mb-1">Preview dan simpan</div>
                                            <div class="small text-muted">Periksa hasilnya dulu, lalu jalankan proses jika sudah sesuai.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="study-section-shell mb-3">
                                    <div class="section-head d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="study-section-kicker">Tahap 1</div>
                                            <div class="fw-semibold mt-2">Kelas paket semester</div>
                                            <div class="small text-muted">Daftar kelas di bawah ini akan menjadi referensi utama untuk pembentukan KRS historis.</div>
                                        </div>
                                    </div>
                                    <div class="section-body">
                                        <div class="study-action-note mb-3">
                                            Sebelum memulai, isi minimal <code>Semester</code>, <code>Program Studi</code>, dan <code>Semester Ke</code> pada filter di atas.
                                        </div>
                                        <div id="studyHistoricalPackageClasses" class="d-grid gap-2">
                                            <div class="text-muted">Daftar kelas belum ditampilkan.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="study-section-shell mb-3">
                                    <div class="section-head d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="study-section-kicker">Tahap 2</div>
                                            <div class="fw-semibold mt-2">Pilih mahasiswa yang akan diproses</div>
                                            <div class="small text-muted">Centang mahasiswa yang benar-benar ingin dibentuk KRS historisnya.</div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            id="studyHistoricalPrepareBuilderBtn">
                                            <i class="fas fa-pen-ruler me-1"></i> Siapkan Form KRS
                                        </button>
                                    </div>
                                    <div class="section-body">
                                        <div class="study-filter-strip">
                                            <div class="summary" id="studyHistoricalEligibleSummary">Belum ada mahasiswa yang dimuat.</div>
                                            <div style="width: min(320px, 100%);">
                                                <input type="text" class="form-control form-control-sm" id="studyHistoricalEligibleSearch"
                                                    placeholder="Cari nama atau NIM mahasiswa">
                                            </div>
                                        </div>
                                        <div class="study-collective-table-wrap">
                                            <table class="table table-bordered align-middle mb-0 study-collective-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 42px;"><input type="checkbox"
                                                                id="studyHistoricalSelectAll"></th>
                                                        <th>Mahasiswa</th>
                                                        <th>Semester Target</th>
                                                        <th>KRS Saat Ini</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="studyHistoricalEligibleTableBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-4">Belum ada
                                                            mahasiswa yang dimuat.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="study-pager">
                                            <div class="info" id="studyHistoricalEligiblePagerInfo">Menampilkan halaman 1.</div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalEligiblePrevPage">
                                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalEligibleNextPage">
                                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="study-section-shell mb-3">
                                    <div class="section-head d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="study-section-kicker">Tahap 3</div>
                                            <div class="fw-semibold mt-2">Atur bentuk build dan pengisian</div>
                                            <div class="small text-muted">Pilih apakah Anda ingin membuat KRS saja atau sekaligus mengisi nilai historis.</div>
                                        </div>
                                        <div class="small text-muted" id="studyHistoricalSelectionSummary">Belum ada
                                            mahasiswa yang dipilih.</div>
                                    </div>
                                    <div class="section-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-lg-6">
                                                <label class="form-label">Mode Build KRS</label>
                                                <select class="form-select" id="studyHistoricalBuildMode">
                                                    <option value="krs_only">Daftarkan KRS Saja</option>
                                                    <option value="krs_with_scores">Daftarkan KRS + Nilai Historis</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="study-action-note h-100 d-flex align-items-center small text-muted" id="studyHistoricalBuildModeHelper">
                                                    Mode ini akan membuat KRS dan detail KRS tanpa harus mengisi nilai akhir atau catatan setiap mata kuliah.
                                                </div>
                                            </div>
                                        </div>
                                        <div id="studyHistoricalBuilderCards" class="d-grid gap-3">
                                            <div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik
                                                <strong>Siapkan Form KRS</strong>.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="study-section-shell">
                                    <div class="section-head">
                                        <div class="study-section-kicker">Tahap 4</div>
                                        <div class="fw-semibold mt-2">Periksa hasil lalu jalankan proses</div>
                                        <div class="small text-muted">Bagian ini membantu memastikan hasilnya sudah aman sebelum KRS historis dibentuk.</div>
                                    </div>
                                    <div class="section-body">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-8">
                                                <label class="form-label">Catatan Proses</label>
                                                <input type="text" class="form-control" id="studyHistoricalBuildNotes"
                                                    placeholder="Opsional, misalnya histori semester 2 angkatan 2023">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="button" class="btn btn-outline-primary"
                                                        id="studyHistoricalPreviewBuildBtn">
                                                        <i class="fas fa-magnifying-glass me-1"></i> Lihat Pratinjau
                                                    </button>
                                                    <button type="button" class="btn btn-success"
                                                        id="studyHistoricalExecuteBuildBtn" disabled>
                                                        <i class="fas fa-play me-1"></i> Jalankan Proses
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="studyHistoricalBuildPreviewResults" class="d-grid gap-2 mt-3">
                                            <div class="text-muted">Belum ada hasil pratinjau yang ditampilkan.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $activeTab === 'riwayat' ? 'show active' : '' }}" id="tab-riwayat">
                        <div class="row g-4">
                            <div class="col-xl-4">
                                <div class="study-bridge">
                                    <div class="fw-semibold mb-2">Koreksi Riwayat Studi</div>
                                    <div class="text-muted small mb-3">
                                        Tab ini dipakai untuk membuka ulang data, mengosongkan isi riwayat,
                                        memfinalisasi ulang, atau membuat KHS dari data semester lampau yang sudah ada.
                                    </div>
                                    <div class="small text-muted mb-3">
                                        Pilihan tindakan yang tersedia:
                                    </div>
                                    <div class="small mb-1">1. Buka ulang riwayat</div>
                                    <div class="small mb-1">2. Reset isi riwayat</div>
                                    <div class="small mb-1">3. Finalisasi ulang</div>
                                    <div class="small mb-3">4. Buat KHS semester lampau</div>
                                    <a href="{{ route('akademik.administrasi-studi.batches') }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Bila Perlu
                                    </a>
                                </div>
                                <div class="study-bridge mt-3">
                                    <div class="fw-semibold mb-2">Langkah di tab ini</div>
                                    <div class="small text-muted mb-2">Anda bisa menjalankan koreksi riwayat studi langsung
                                        dari halaman ini:</div>
                                    <div class="small mb-1">1. Lihat pratinjau buka ulang, finalisasi ulang, atau reset
                                    </div>
                                    <div class="small mb-1">2. Jalankan proses koreksi</div>
                                    <div class="small mb-1">3. Lihat pratinjau pembuatan KHS semester lampau</div>
                                    <div class="small">4. Buka detail hasil proses</div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="study-native-panel mb-3">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Pilih Tindakan</label>
                                            <select class="form-select" id="studyHistoricalMutationAction">
                                                <option value="reopen_historical_krs">Buka Ulang Riwayat</option>
                                                <option value="refinalize_historical_krs">Finalisasi Ulang Riwayat</option>
                                                <option value="reset_historical_krs">Reset Isi Riwayat</option>
                                                <option value="generate_khs">Generate KHS Historis</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label">Catatan Proses</label>
                                            <input type="text" class="form-control" id="studyHistoricalMutationNotes"
                                                placeholder="Opsional, misalnya untuk koreksi nilai semester lampau">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="studyRiwayatLoadBtn">
                                                    <i class="fas fa-users me-1"></i> Tampilkan Mahasiswa Sesuai Filter
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="studyHistoricalPreviewMutationBtn">
                                                    <i class="fas fa-magnifying-glass me-1"></i> Lihat Pratinjau
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    id="studyHistoricalExecuteMutationBtn" disabled>
                                                    <i class="fas fa-play me-1"></i> Jalankan Proses
                                                </button>
                                                <a href="{{ route('akademik.administrasi-studi.batches') }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Bila Perlu
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-3" id="studyHistoricalMutationSelectionSummary">
                                        Belum ada mahasiswa yang dipilih. Pilih mahasiswa di tab `KRS Kolektif`, atau muat
                                        ulang data dengan tombol di atas.
                                    </div>
                                    <div class="study-soft-box mt-3 d-none" id="studyHistoricalMutationManualIpkPanel">
                                        <div class="fw-semibold mb-2">IPK Manual untuk Generate KHS Historis</div>
                                        <div class="small text-muted mb-3">
                                            Semester 1 akan mengikuti IPS. Jika Anda memilih aksi
                                            <code>Generate KHS Historis</code> untuk semester berikutnya, isi IPK manual
                                            per mahasiswa di sini.
                                        </div>
                                        <div id="studyHistoricalMutationManualIpkList" class="d-grid gap-2">
                                            <div class="text-muted">Belum ada mahasiswa yang dipilih.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="study-native-panel">
                                    <div class="fw-semibold mb-3">Hasil Preview Riwayat Studi</div>
                                    <div id="studyHistoricalMutationPreviewResults" class="d-grid gap-2">
                                        <div class="text-muted">Belum ada hasil pratinjau tindakan yang ditampilkan.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $activeTab === 'import' ? 'show active' : '' }}" id="tab-import">
                        <div class="row g-4">
                            <div class="col-xl-4">
                                <div class="study-bridge">
                                    <div class="fw-semibold mb-2">Import Nilai ke KRS Detail</div>
                                    <div class="text-muted small mb-3">
                                        Tab ini dipakai untuk mengunduh template nilai, mengunggah file Excel,
                                        memeriksa hasil pengecekan, lalu menyimpan nilai ke detail KRS.
                                    </div>
                                    <div class="small text-muted mb-3">
                                        Langkah yang tersedia:
                                    </div>
                                    <div class="small mb-1">1. Pilih filter akademik</div>
                                    <div class="small mb-1">2. Unduh template nilai</div>
                                    <div class="small mb-1">3. Unggah file lalu cek hasilnya</div>
                                    <div class="small mb-1">4. Simpan nilai ke `KRSDetail`</div>
                                    <div class="small mb-3">5. Lanjut membuat KHS</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('akademik.administrasi-studi.index', ['tab' => 'khs']) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-arrow-right me-1"></i> Ke Generate KHS
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="study-tab-card h-100">
                                    <div class="fw-semibold mb-3">Mulai Input Nilai dari Halaman Ini</div>
                                    <p class="text-muted small mb-4">
                                        Form ini mengikuti filter yang dipilih di bagian atas. Anda bisa mengunduh template
                                        dan mengunggah file nilai langsung dari sini,
                                        lalu hasilnya akan dibuka pada halaman hasil pengecekan.
                                    </p>

                                    <form method="GET"
                                        action="{{ route('akademik.administrasi-studi.import.template-export') }}"
                                        class="mb-4" id="studyImportTemplateForm">
                                        <input type="hidden" name="angkatan" id="studyImportTemplateAngkatan">
                                        <input type="hidden" name="id_prodi" id="studyImportTemplateProdi">
                                        <input type="hidden" name="id_semester" id="studyImportTemplateSemester">
                                        <input type="hidden" name="semester_ke" id="studyImportTemplateSemesterKe">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="submit" class="btn btn-success" id="studyExportTemplateBtn">
                                                <i class="fas fa-file-export me-1"></i> Unduh Template
                                            </button>
                                        </div>
                                        <div class="form-text mt-2">
                                            Template akan mengikuti `angkatan`, `prodi`, `semester`, dan `semester ke` yang
                                            dipilih pada filter di atas. Semester 1 memakai kolom gabungan
                                            <code>IP/IPK</code>, sedangkan semester berikutnya memakai kolom
                                            <code>IP</code> dan <code>IPK</code> terpisah.
                                        </div>
                                    </form>

                                    <form method="POST"
                                        action="{{ route('akademik.administrasi-studi.import.upload') }}"
                                        enctype="multipart/form-data" id="studyImportUploadForm">
                                        @csrf
                                        <input type="hidden" name="id_semester" id="studyImportUploadSemester">
                                        <div class="mb-3">
                                            <label class="form-label">File Excel Nilai</label>
                                            <input type="file" name="file" class="form-control"
                                                accept=".xlsx,.xls" required>
                                            <div class="form-text">Gunakan file <code>.xlsx</code> atau <code>.xls</code>
                                                dengan ukuran maksimal 10MB. Kolom <code>IPK</code> semester di atas 1
                                                diisi manual dari Excel, tidak dihitung otomatis oleh frontend.</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="studyUploadImportBtn">
                                            <i class="fas fa-cloud-upload-alt me-1"></i> Unggah File Nilai
                                        </button>
                                    </form>

                                    <hr class="my-4">

                                    <div class="study-mini-item">
                                        <div class="fw-semibold mb-2">Butuh melihat proses lama?</div>
                                        <div class="small text-muted mb-3">
                                            Riwayat proses tetap tersedia untuk audit, tetapi tidak diperlukan untuk langkah kerja harian.
                                        </div>
                                        <a href="{{ route('akademik.administrasi-studi.batches') }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            Lihat Riwayat Bila Perlu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $activeTab === 'khs' ? 'show active' : '' }}" id="tab-khs">
                        <div class="row g-4">
                            <div class="col-xl-4">
                                <div class="study-bridge">
                                    <div class="fw-semibold mb-2">Generate KHS</div>
                                    <div class="text-muted small mb-3">
                                        Tab ini dipakai untuk menampilkan mahasiswa yang siap diproses, melihat ringkasan
                                        semester,
                                        lalu membuat KHS langsung dari halaman ini.
                                    </div>
                                    <div class="small text-muted mb-3">
                                        Syarat utama:
                                    </div>
                                    <div class="small mb-1">1. KRS semester sudah approved</div>
                                    <div class="small mb-1">2. KRS sudah dikunci</div>
                                    <div class="small mb-1">3. Nilai akhir pada detail sudah final</div>
                                    <div class="small mb-3">4. Data lulus valid untuk pembentukan hasil studi</div>
                                    <button type="button" class="btn btn-primary btn-sm" id="loadReadyKhsBtn">
                                        <i class="fas fa-list-check me-1"></i> Muat Mahasiswa Siap
                                    </button>
                                </div>

                                <div class="study-bridge mt-3">
                                    <div class="fw-semibold mb-2">Preview Semester</div>
                                    <div class="small text-muted mb-3" id="khsPreviewHelper">
                                        Pilih satu mahasiswa yang siap diproses, lalu klik `Preview` untuk melihat ringkasan
                                        KHS sebelum dibuat.
                                    </div>
                                    <div id="khsPreviewPanel" class="small text-muted">Belum ada preview yang dimuat.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="study-tab-card h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <div class="fw-semibold">Mahasiswa Siap Generate</div>
                                            <div class="small text-muted">Data diambil dari konteks filter yang sedang
                                                aktif.</div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Mahasiswa</th>
                                                    <th>Status</th>
                                                    <th>Final Detail</th>
                                                    <th>KHS Saat Ini</th>
                                                    <th>IPK Manual</th>
                                                    <th style="width: 180px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="readyKhsTableBody">
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data
                                                        yang dimuat.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $activeTab === 'batch' ? 'show active' : '' }}" id="tab-batch">
                        <div class="row g-4">
                            <div class="col-xl-4">
                                <div class="study-bridge">
                                    <div class="fw-semibold mb-2">Riwayat Semua Proses</div>
                                    <div class="text-muted small mb-3">
                                        Tab ini hanya dipakai saat Anda perlu menelusuri proses lama atau audit hasil kerja sebelumnya.
                                    </div>
                                    <a href="{{ route('akademik.administrasi-studi.batches') }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-layer-group me-1"></i> Buka Riwayat Bila Perlu
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="study-mini-item h-100">
                                    <div class="fw-semibold mb-2">Kapan tab ini dipakai?</div>
                                    <div class="small text-muted mb-2">1. Saat Anda ingin melacak siapa yang menjalankan proses.</div>
                                    <div class="small text-muted mb-2">2. Saat perlu membuka ulang hasil lama untuk audit.</div>
                                    <div class="small text-muted">3. Saat ada masalah dan Anda perlu meninjau proses sebelumnya.</div>
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
    <script>
        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function renderSummaryCards(cards) {
            const html = (cards || []).map((card) => `
                <div class="col-md-6 col-xl-3">
                    <div class="study-stat">
                        <div class="small text-muted text-uppercase mb-2">${card.label ?? '-'}</div>
                        <div class="study-stat-value text-${card.tone ?? 'primary'}">${card.value ?? 0}</div>
                        <div class="small text-muted mt-2">${card.description ?? ''}</div>
                    </div>
                </div>
            `).join('');

            $('#studySummaryCards').html(html ||
                '<div class="col-12"><div class="text-muted">Tidak ada ringkasan yang dapat ditampilkan.</div></div>');
        }

        $('#refreshStudySummaryBtn').on('click', function() {
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

            $.get('{{ route('akademik.administrasi-studi.summary') }}', {
                id_semester: $('#studySemesterId').val(),
                id_prodi: $('#studyProdiId').val(),
                angkatan: $('#studyAngkatan').val(),
                semester_ke: $('#studySemesterKe').val()
            }).done(function(response) {
                renderSummaryCards(response.data?.summary_cards ?? []);
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.message ?? 'Gagal memuat ringkasan administrasi studi.');
            }).always(function() {
                $button.prop('disabled', false).html(
                    '<i class="fas fa-arrows-rotate me-1"></i> Perbarui Ringkasan');
            });
        });

        function syncImportFormsFromWorkspaceContext() {
            $('#studyImportTemplateAngkatan').val($('#studyAngkatan').val());
            $('#studyImportTemplateProdi').val($('#studyProdiId').val());
            $('#studyImportTemplateSemester').val($('#studySemesterId').val());
            $('#studyImportTemplateSemesterKe').val($('#studySemesterKe').val());
            $('#studyImportUploadSemester').val($('#studySemesterId').val());
        }

        let historicalEligibleStudents = [];
        let historicalPackageClasses = [];
        let historicalRepeatCandidatesByStudent = {};
        let historicalBuilderActiveStudentId = null;
        let historicalEligiblePage = 1;
        let historicalBuilderPage = 1;
        const historicalPageSize = 10;
        let readyKhsRows = [];

        function formatGpaValue(value) {
            if (value === null || value === undefined || value === '') {
                return '-';
            }

            const number = Number(value);
            return Number.isFinite(number) ? number.toFixed(2) : escapeHtml(value);
        }

        function formatKurikulumIndukLabel(induk) {
            if (!induk) {
                return '-';
            }

            return [
                induk.kode_kurikulum,
                induk.nama_kurikulum,
                induk.jenis_kurikulum?.kode_jenis
            ].filter(Boolean).join(' | ') || '-';
        }

        function getHistoricalContext() {
            return {
                id_semester: $('#studySemesterId').val(),
                id_prodi: $('#studyProdiId').val(),
                angkatan: $('#studyAngkatan').val(),
                semester_ke: $('#studySemesterKe').val()
            };
        }

        function getHistoricalBuildMode() {
            return $('#studyHistoricalBuildMode').val() || 'krs_only';
        }

        function getHistoricalMutationAction() {
            return $('#studyHistoricalMutationAction').val() || 'reopen_historical_krs';
        }

        function syncHistoricalBuildModeHelper() {
            const buildMode = getHistoricalBuildMode();
            $('#studyHistoricalBuildModeHelper').text(
                buildMode === 'krs_only' ?
                'Mode ini akan membuat KRS dan detail KRS tanpa harus mengisi nilai akhir atau catatan setiap mata kuliah.' :
                'Mode ini akan membuat KRS sekaligus menyimpan nilai akhir semester lampau pada mata kuliah yang dipilih.'
            );
        }

        function validateHistoricalContext(options = {}) {
            const context = getHistoricalContext();

            if (!context.id_semester) {
                alert('Pilih semester pada filter konteks terlebih dahulu.');
                return null;
            }

            if (options.requireProdi && !context.id_prodi) {
                alert('Pilih program studi pada filter konteks terlebih dahulu.');
                return null;
            }

            if (options.requireSemesterKe && !context.semester_ke) {
                alert('Pilih semester ke pada filter konteks terlebih dahulu.');
                return null;
            }

            return context;
        }

        function getSelectedHistoricalStudentIds() {
            return $('.study-historical-student-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        function getSelectedHistoricalStudents() {
            const selectedIds = getSelectedHistoricalStudentIds();
            return historicalEligibleStudents.filter((row) => selectedIds.includes(row.id));
        }

        function syncHistoricalMutationManualIpkPanel() {
            const selectedStudents = getSelectedHistoricalStudents();
            const shouldShow = getHistoricalMutationAction() === 'generate_khs';
            const $panel = $('#studyHistoricalMutationManualIpkPanel');
            const $list = $('#studyHistoricalMutationManualIpkList');
            const existingValues = {};

            $('.study-historical-manual-ipk').each(function() {
                existingValues[$(this).data('id-mahasiswa')] = $(this).val();
            });

            $panel.toggleClass('d-none', !shouldShow);

            if (!shouldShow) {
                return;
            }

            if (!selectedStudents.length) {
                $list.html('<div class="text-muted">Belum ada mahasiswa yang dipilih.</div>');
                return;
            }

            const html = selectedStudents.map((student) => `
                <div class="border rounded-3 p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa ?? '-')}</div>
                            <div class="small text-muted">${escapeHtml(student.nim ?? '-')}</div>
                        </div>
                        <span class="badge bg-light text-dark">Semester Target ${escapeHtml(student.semester_target ?? '-')}</span>
                    </div>
                    <div class="mt-3">
                        <label class="form-label small">IPK Manual</label>
                        <input
                            type="number"
                            min="0"
                            max="4"
                            step="0.01"
                            class="form-control form-control-sm study-historical-manual-ipk"
                            data-id-mahasiswa="${escapeHtml(student.id)}"
                            value="${escapeHtml(existingValues[student.id] ?? '')}"
                            placeholder="Isi jika semester di atas 1">
                        <div class="form-text">Semester 1 akan tetap mengikuti IPS. Isi nilai ini untuk semester berikutnya.</div>
                    </div>
                </div>
            `).join('');

            $list.html(html);
        }

        function buildHistoricalGenerateKhsPayload() {
            return getSelectedHistoricalStudents().map((student) => {
                const inputValue = $(`.study-historical-manual-ipk[data-id-mahasiswa="${student.id}"]`).val();
                return {
                    id_mahasiswa: student.id,
                    ipk: inputValue === '' ? null : inputValue
                };
            });
        }

        function renderHistoricalSelectionSummary() {
            const selectedStudents = getSelectedHistoricalStudents();

            if (!selectedStudents.length) {
                $('#studyHistoricalSelectionSummary').text('Belum ada mahasiswa yang dipilih.');
                $('#studyHistoricalMutationSelectionSummary').text(
                    'Belum ada mahasiswa yang dipilih. Pilih mahasiswa di tab `KRS Kolektif`, atau muat ulang data dengan tombol di atas.'
                );
                syncHistoricalMutationManualIpkPanel();
                return;
            }

            const names = selectedStudents.slice(0, 3).map((row) => row.nama_mahasiswa).join(', ');
            const extra = selectedStudents.length > 3 ? ` +${selectedStudents.length - 3} mahasiswa` : '';
            const summary = `${selectedStudents.length} mahasiswa dipilih: ${names}${extra}`;

            $('#studyHistoricalSelectionSummary').text(summary);
            $('#studyHistoricalMutationSelectionSummary').text(summary);
            syncHistoricalMutationManualIpkPanel();
        }

        function renderHistoricalPackageClasses(rows) {
            if (!rows || !rows.length) {
                $('#studyHistoricalPackageClasses').html(
                    '<div class="text-muted">Daftar kelas belum tersedia untuk pilihan filter ini.</div>');
                return;
            }

            const html = rows.map((row) => `
                <div class="study-course-row">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(row.mata_kuliah?.kode_mk ?? '-')} - ${escapeHtml(row.mata_kuliah?.nama_mk ?? 'Mata kuliah')}</div>
                            <div class="small text-muted">
                                <div><strong>Induk:</strong> ${escapeHtml(formatKurikulumIndukLabel(row.kurikulum_context?.kurikulum_induk))}</div>
                            </div>
                            <div class="small text-muted">${escapeHtml(row.nama_kelas ?? 'Kelas')} • ${escapeHtml(row.nama_struktur_operasional ?? row.nama_kurikulum ?? 'Struktur Operasional')} • Semester ${row.semester_ke ?? '-'}</div>
                        </div>
                        <span class="badge bg-light text-dark">${row.mata_kuliah?.sks ?? 0} SKS</span>
                    </div>
                </div>
            `).join('');

            $('#studyHistoricalPackageClasses').html(html);
        }

        function renderHistoricalEligibleStudents(rows) {
            if (!rows || !rows.length) {
                $('#studyHistoricalEligibleSummary').text('Tidak ada mahasiswa yang ditemukan untuk konteks ini.');
                $('#studyHistoricalEligibleTableBody').html(
                    '<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada mahasiswa yang ditemukan untuk konteks ini.</td></tr>'
                );
                $('#studyHistoricalEligiblePagerInfo').text('Belum ada halaman untuk ditampilkan.');
                $('#studyHistoricalEligiblePrevPage, #studyHistoricalEligibleNextPage').prop('disabled', true);
                renderHistoricalSelectionSummary();
                return;
            }

            const keyword = ($('#studyHistoricalEligibleSearch').val() || '').toLowerCase().trim();
            const filteredRows = rows.filter((row) => {
                if (!keyword) {
                    return true;
                }

                return `${row.nama_mahasiswa ?? ''} ${row.nim ?? ''}`.toLowerCase().includes(keyword);
            });
            const totalPages = Math.max(1, Math.ceil(filteredRows.length / historicalPageSize));
            historicalEligiblePage = Math.min(Math.max(1, historicalEligiblePage), totalPages);
            const startIndex = (historicalEligiblePage - 1) * historicalPageSize;
            const currentRows = filteredRows.slice(startIndex, startIndex + historicalPageSize);

            $('#studyHistoricalEligibleSummary').text(
                keyword ?
                `Menampilkan ${filteredRows.length} dari ${rows.length} mahasiswa yang cocok dengan pencarian.` :
                `Menampilkan ${rows.length} mahasiswa. Gunakan pencarian jika daftarnya panjang.`
            );
            $('#studyHistoricalEligiblePagerInfo').text(
                `Halaman ${historicalEligiblePage} dari ${totalPages} • Menampilkan ${currentRows.length} mahasiswa`
            );
            $('#studyHistoricalEligiblePrevPage').prop('disabled', historicalEligiblePage <= 1);
            $('#studyHistoricalEligibleNextPage').prop('disabled', historicalEligiblePage >= totalPages);

            const html = currentRows.map((row) => {
                const existing = row.existing_historical_krs ?
                    `<span class="badge ${row.existing_historical_krs.is_locked ? 'bg-primary' : 'bg-warning text-dark'}">${escapeHtml(row.existing_historical_krs.status_approval ?? 'Draft')}</span>` :
                    '<span class="text-muted">Belum ada</span>';
                const statusBadge = row.default_action === 'ready' ?
                    '<span class="badge bg-success">Siap</span>' :
                    row.default_action === 'skipped' ?
                    '<span class="badge bg-warning text-dark">Lewati</span>' :
                    '<span class="badge bg-danger">Perlu Cek</span>';

                return `
                    <tr data-search="${escapeHtml(`${row.nama_mahasiswa ?? ''} ${row.nim ?? ''}`.toLowerCase())}">
                        <td><input type="checkbox" class="study-historical-student-checkbox" value="${escapeHtml(row.id)}"></td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa)}</div>
                            <div class="small text-muted mt-1">
                                <div><strong>Induk:</strong> ${escapeHtml(formatKurikulumIndukLabel(row.kurikulum_context?.kurikulum_induk))}</div>
                                <div><strong>Operasional:</strong> ${escapeHtml(row.kurikulum_context?.struktur_operasional?.nama_struktur_mk ?? '-')}</div>
                            </div>
                            <div class="small text-muted">${escapeHtml(row.nim)}${row.prodi?.nama_prodi ? ' • ' + escapeHtml(row.prodi.nama_prodi) : ''}</div>
                            <div class="small text-muted mt-1">${escapeHtml(row.message ?? '')}</div>
                        </td>
                        <td>${row.semester_target ?? '-'}</td>
                        <td>${existing}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            }).join('');

            $('#studyHistoricalEligibleTableBody').html(html);
            $('#studyHistoricalSelectAll').prop('checked', false);
            renderHistoricalSelectionSummary();
        }

        function filterHistoricalEligibleStudents() {
            historicalEligiblePage = 1;
            renderHistoricalEligibleStudents(historicalEligibleStudents);
        }

        function renderHistoricalBuilderCards() {
            const selectedStudents = getSelectedHistoricalStudents();
            const buildMode = getHistoricalBuildMode();

            if (!selectedStudents.length) {
                $('#studyHistoricalBuilderCards').html(
                    '<div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form KRS</strong>.</div>'
                );
                return;
            }

            if (!historicalPackageClasses.length) {
                $('#studyHistoricalBuilderCards').html(
                    '<div class="text-muted">Daftar kelas belum ditampilkan. Lengkapi program studi dan semester ke, lalu muat ulang data historis.</div>'
                );
                return;
            }

            const cards = selectedStudents.map((student) => {
                const courseRows = historicalPackageClasses.map((course, index) => `
                    <div class="study-course-row">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-5">
                                <div class="form-check">
                                    <input class="form-check-input study-course-include" type="checkbox" checked>
                                    <label class="form-check-label small fw-semibold">
                                        ${escapeHtml(course.mata_kuliah?.kode_mk ?? '-')} - ${escapeHtml(course.mata_kuliah?.nama_mk ?? 'Mata kuliah')}
                                    </label>
                                </div>
                                <div class="small text-muted mt-1">${escapeHtml(course.nama_kelas ?? 'Kelas')} • ${course.mata_kuliah?.sks ?? 0} SKS</div>
                                <input type="hidden" class="study-course-class-id" value="${escapeHtml(course.id)}">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label small">Nilai Akhir</label>
                                <input type="number" class="form-control form-control-sm study-course-score" min="0" max="100" step="0.01" placeholder="${buildMode === 'krs_with_scores' ? '0 - 100' : 'Opsional'}" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label small">Catatan</label>
                                <input type="text" class="form-control form-control-sm study-course-note" placeholder="Opsional" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                        </div>
                    </div>
                `).join('');

                return `
                    <div class="study-builder-card study-student-builder-card" data-id-mahasiswa="${escapeHtml(student.id)}">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                                <div class="small text-muted">${escapeHtml(student.nim)}${student.prodi?.nama_prodi ? ' • ' + escapeHtml(student.prodi.nama_prodi) : ''}</div>
                            </div>
                            <span class="badge bg-light text-dark">Semester Target ${student.semester_target ?? '-'}</span>
                        </div>
                        <div class="d-grid gap-2">${courseRows}</div>
                    </div>
                `;
            }).join('');

            $('#studyHistoricalBuilderCards').html(cards);
        }

        function loadHistoricalRepeatCandidates(selectedStudentIds = []) {
            historicalRepeatCandidatesByStudent = {};

            if (!selectedStudentIds.length) {
                return $.Deferred().resolve().promise();
            }

            const context = getHistoricalContext();
            if (!context.id_semester || !context.semester_ke) {
                return $.Deferred().resolve().promise();
            }

            return $.get('{{ route('akademik.administrasi-studi.historical.repeat-candidates') }}', {
                id_semester: context.id_semester,
                semester_ke: context.semester_ke,
                id_mahasiswa: selectedStudentIds
            }).done(function(response) {
                (response.data ?? []).forEach(function(row) {
                    historicalRepeatCandidatesByStudent[row.id_mahasiswa] = row.courses ?? [];
                });
            }).fail(function(xhr) {
                historicalRepeatCandidatesByStudent = {};
                alert(xhr.responseJSON?.message ?? 'Gagal memuat pilihan ulang mata kuliah gagal.');
            });
        }

        function renderHistoricalBuilderCards() {
            const selectedStudents = getSelectedHistoricalStudents();
            const buildMode = getHistoricalBuildMode();

            if (!selectedStudents.length) {
                $('#studyHistoricalBuilderCards').html(
                    '<div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form KRS</strong>.</div>'
                );
                return;
            }

            if (!historicalPackageClasses.length) {
                $('#studyHistoricalBuilderCards').html(
                    '<div class="text-muted">Daftar kelas belum ditampilkan. Lengkapi program studi dan semester ke, lalu muat ulang data historis.</div>'
                );
                return;
            }

            const selectedIds = selectedStudents.map((student) => student.id);
            if (!historicalBuilderActiveStudentId || !selectedIds.includes(historicalBuilderActiveStudentId)) {
                historicalBuilderActiveStudentId = selectedIds[0];
            }
            const totalBuilderPages = Math.max(1, Math.ceil(selectedStudents.length / historicalPageSize));
            const activeStudentIndex = selectedIds.indexOf(historicalBuilderActiveStudentId);
            if (activeStudentIndex >= 0) {
                historicalBuilderPage = Math.floor(activeStudentIndex / historicalPageSize) + 1;
            }
            historicalBuilderPage = Math.min(Math.max(1, historicalBuilderPage), totalBuilderPages);
            const navStart = (historicalBuilderPage - 1) * historicalPageSize;
            const navStudents = selectedStudents.slice(navStart, navStart + historicalPageSize);

            const navigationItems = navStudents.map((student, navIndex) => {
                const isActive = student.id === historicalBuilderActiveStudentId;
                const repeatCount = (historicalRepeatCandidatesByStudent[student.id] ?? []).length;
                const actualIndex = navStart + navIndex;

                return `
                    <button type="button"
                        class="study-builder-nav-item ${isActive ? 'active' : ''}"
                        data-id-mahasiswa="${escapeHtml(student.id)}">
                        <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                        <div class="meta">${escapeHtml(student.nim)}${student.prodi?.nama_prodi ? ' • ' + escapeHtml(student.prodi.nama_prodi) : ''}</div>
                        <div class="meta mt-1">Urutan ${actualIndex + 1} dari ${selectedStudents.length} • Semester ${student.semester_target ?? '-'}</div>
                        ${repeatCount ? `<div class="meta mt-1 text-warning">Ulang gagal tersedia: ${repeatCount}</div>` : ''}
                    </button>
                `;
            }).join('');

            const cards = selectedStudents.map((student) => {
                const repeatCandidates = historicalRepeatCandidatesByStudent[student.id] ?? [];
                const isActive = student.id === historicalBuilderActiveStudentId;
                const activeIndex = selectedIds.indexOf(student.id);
                const prevDisabled = activeIndex <= 0 ? 'disabled' : '';
                const nextDisabled = activeIndex >= selectedIds.length - 1 ? 'disabled' : '';

                const packageRows = historicalPackageClasses.map((course) => `
                    <div class="study-course-row">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-5">
                                <div class="form-check">
                                    <input class="form-check-input study-course-include" type="checkbox" checked>
                                    <label class="form-check-label small fw-semibold">
                                        ${escapeHtml(course.mata_kuliah?.kode_mk ?? '-')} - ${escapeHtml(course.mata_kuliah?.nama_mk ?? 'Mata kuliah')}
                                    </label>
                                </div>
                                <div class="small text-muted mt-1">${escapeHtml(course.nama_kelas ?? 'Kelas')} • ${course.mata_kuliah?.sks ?? 0} SKS • Paket semester</div>
                                <input type="hidden" class="study-course-class-id" value="${escapeHtml(course.id)}">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label small">Nilai Akhir</label>
                                <input type="number" class="form-control form-control-sm study-course-score" min="0" max="100" step="0.01" placeholder="${buildMode === 'krs_with_scores' ? '0 - 100' : 'Opsional'}" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label small">Catatan</label>
                                <input type="text" class="form-control form-control-sm study-course-note" placeholder="Opsional" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                        </div>
                    </div>
                `).join('');

                const repeatRows = repeatCandidates.map((course) => {
                    const options = (course.available_classes ?? []).map((item) =>
                        `<option value="${escapeHtml(item.id)}">${escapeHtml(item.nama_kelas ?? 'Kelas')} • ${item.mata_kuliah?.sks ?? 0} SKS</option>`
                    ).join('');
                    const failedGrade = course.riwayat_terakhir?.nilai_huruf ? `Nilai ${escapeHtml(course.riwayat_terakhir.nilai_huruf)}` : 'Belum lulus';
                    const failedSemester = course.riwayat_terakhir?.semester_label ? ` • ${escapeHtml(course.riwayat_terakhir.semester_label)}` : '';

                    return `
                        <div class="study-course-row border-warning-subtle bg-warning-subtle">
                            <div class="row g-2 align-items-end">
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input study-course-include" type="checkbox">
                                        <label class="form-check-label small fw-semibold">
                                            ${escapeHtml(course.kode_mk ?? '-')} - ${escapeHtml(course.nama_mk ?? 'Mata kuliah ulang')}
                                        </label>
                                    </div>
                                    <div class="small text-muted mt-1">Ulang matkul gagal • ${course.sks ?? 0} SKS</div>
                                    <div class="small text-muted">${failedGrade}${failedSemester}</div>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small">Kelas Ulang</label>
                                    <select class="form-select form-select-sm study-course-class-id">
                                        ${options}
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Nilai Akhir</label>
                                    <input type="number" class="form-control form-control-sm study-course-score" min="0" max="100" step="0.01" placeholder="${buildMode === 'krs_with_scores' ? '0 - 100' : 'Opsional'}" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small">Catatan</label>
                                    <input type="text" class="form-control form-control-sm study-course-note" placeholder="Opsional" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                const repeatSection = repeatRows ? `
                    <div class="study-soft-box mt-3">
                        <div class="fw-semibold mb-2">Pilihan Ulang Matkul Gagal</div>
                        <div class="small text-muted mb-3">Bagian ini menampilkan mata kuliah yang sebelumnya tidak lulus dan tersedia lagi di semester target.</div>
                        <div class="d-grid gap-2">${repeatRows}</div>
                    </div>
                ` : '';

                return `
                    <div class="study-builder-card study-student-builder-card ${isActive ? '' : 'd-none'}" data-id-mahasiswa="${escapeHtml(student.id)}">
                        <div class="study-builder-toolbar">
                            <div class="summary">Form mahasiswa ${activeIndex + 1} dari ${selectedStudents.length}</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm study-builder-prev" data-id-mahasiswa="${escapeHtml(student.id)}" ${prevDisabled}>
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm study-builder-next" data-id-mahasiswa="${escapeHtml(student.id)}" ${nextDisabled}>
                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                                <div class="small text-muted">${escapeHtml(student.nim)}${student.prodi?.nama_prodi ? ' • ' + escapeHtml(student.prodi.nama_prodi) : ''}</div>
                            </div>
                            <span class="badge bg-light text-dark">Semester Target ${student.semester_target ?? '-'}</span>
                        </div>
                        <div class="d-grid gap-2">${packageRows}</div>
                        ${repeatSection}
                    </div>
                `;
            }).join('');

            $('#studyHistoricalBuilderCards').html(`
                <div class="study-builder-shell">
                    <div class="study-builder-nav">
                        <div class="fw-semibold mb-2">Daftar Mahasiswa Dipilih</div>
                        <div class="small text-muted mb-3">Klik nama mahasiswa untuk berpindah form tanpa scroll panjang.</div>
                        <div class="study-builder-nav-list">${navigationItems}</div>
                        <div class="study-pager mt-3">
                            <div class="info">Halaman ${historicalBuilderPage} dari ${totalBuilderPages}</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalBuilderPrevPage" ${historicalBuilderPage <= 1 ? 'disabled' : ''}>
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalBuilderNextPage" ${historicalBuilderPage >= totalBuilderPages ? 'disabled' : ''}>
                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="study-builder-stage">${cards}</div>
                </div>
            `);
        }

        function buildHistoricalStudentsPayload() {
            const buildMode = getHistoricalBuildMode();
            const payload = [];

            $('.study-student-builder-card').each(function() {
                const $card = $(this);
                const studentId = $card.data('id-mahasiswa');
                const courses = [];
                let hasInvalidScore = false;

                $card.find('.study-course-row').each(function() {
                    const $row = $(this);
                    const isIncluded = $row.find('.study-course-include').is(':checked');
                    const score = $row.find('.study-course-score').val();

                    if (!isIncluded) {
                        return;
                    }

                    if (buildMode === 'krs_with_scores' && (score === '' || Number(score) < 0 || Number(
                            score) > 100)) {
                        hasInvalidScore = true;
                        return false;
                    }

                    const coursePayload = {
                        id_kelas_kuliah: $row.find('.study-course-class-id').val(),
                        catatan: $row.find('.study-course-note').val()
                    };

                    if (buildMode === 'krs_with_scores') {
                        coursePayload.nilai_akhir = score;
                    }

                    courses.push(coursePayload);
                });

                if (hasInvalidScore) {
                    throw new Error(
                        'Pastikan setiap mata kuliah yang dicentang memiliki nilai akhir antara 0 sampai 100.');
                }

                if (courses.length) {
                    payload.push({
                        id_mahasiswa: studentId,
                        build_mode: buildMode,
                        courses: courses
                    });
                }
            });

            return payload;
        }

        function renderHistoricalPreviewResults(targetSelector, rows, emptyMessage) {
            if (!rows || !rows.length) {
                $(targetSelector).html(`<div class="text-muted">${escapeHtml(emptyMessage)}</div>`);
                return;
            }

            const html = rows.map((row) => `
                <div class="study-preview-item ${escapeHtml(row.status ?? '')}">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa ?? '-')}</div>
                            <div class="small text-muted">${escapeHtml(row.nim ?? '-')}</div>
                        </div>
                        <span class="badge ${row.status === 'ready' || row.status === 'executed' ? 'bg-success' : row.status === 'skipped' ? 'bg-warning text-dark' : 'bg-danger'}">${escapeHtml(row.status ?? '-')}</span>
                    </div>
                    <div class="small mt-2">${escapeHtml(row.message ?? '-')}</div>
                    ${row.meta?.total_courses ? `<div class="small text-muted mt-2">Mata kuliah: ${row.meta.total_courses} • Total SKS: ${row.meta.total_sks ?? 0}</div>` : ''}
                </div>
            `).join('');

            $(targetSelector).html(html);
        }

        function resetHistoricalExecuteButtons() {
            $('#studyHistoricalExecuteBuildBtn').prop('disabled', true);
            $('#studyHistoricalExecuteMutationBtn').prop('disabled', true);
        }

        function loadHistoricalWorkspaceData(triggerButtonSelector) {
            const context = validateHistoricalContext();
            if (!context) {
                return;
            }

            const $button = $(triggerButtonSelector);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');
            resetHistoricalExecuteButtons();

            $.get('{{ route('akademik.administrasi-studi.historical.eligible') }}', {
                id_semester: context.id_semester,
                id_prodi: context.id_prodi,
                angkatan: context.angkatan
            }).done(function(response) {
                historicalEligibleStudents = response.data ?? [];
                renderHistoricalEligibleStudents(historicalEligibleStudents);
                $('#studyHistoricalBuilderCards').html(
                    '<div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form KRS</strong>.</div>'
                );
                renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', [],
                    'Belum ada hasil pratinjau yang ditampilkan.');
                renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', [],
                    'Belum ada hasil pratinjau tindakan yang ditampilkan.');
            }).fail(function(xhr) {
                historicalEligibleStudents = [];
                renderHistoricalEligibleStudents([]);
                alert(xhr.responseJSON?.message ?? 'Data mahasiswa semester lampau gagal dimuat.');
            }).always(function() {
                $button.prop('disabled', false).html($button.is('#studyRiwayatLoadBtn') ?
                    '<i class="fas fa-users me-1"></i> Tampilkan Mahasiswa Sesuai Filter' :
                    '<i class="fas fa-book-open me-1"></i> Muat Data Historis');
            });

            if (context.id_prodi && context.semester_ke) {
                $.get('{{ route('akademik.administrasi-studi.historical.package-classes') }}', {
                    id_semester: context.id_semester,
                    id_prodi: context.id_prodi,
                    semester_ke: context.semester_ke
                }).done(function(response) {
                    historicalPackageClasses = response.data ?? [];
                    renderHistoricalPackageClasses(historicalPackageClasses);
                }).fail(function(xhr) {
                    historicalPackageClasses = [];
                    renderHistoricalPackageClasses([]);
                    alert(xhr.responseJSON?.message ?? 'Daftar kelas semester lampau gagal dimuat.');
                });
            } else {
                historicalPackageClasses = [];
                renderHistoricalPackageClasses([]);
            }
        }

        function submitHistoricalPreview(action, targetSelector, executeButtonSelector) {
            const context = validateHistoricalContext({
                requireProdi: action === 'build_historical_krs',
                requireSemesterKe: action === 'build_historical_krs',
            });
            if (!context) {
                return;
            }

            const selectedIds = getSelectedHistoricalStudentIds();
            if (!selectedIds.length) {
                alert('Pilih minimal satu mahasiswa terlebih dahulu.');
                return;
            }

            const payload = {
                _token: '{{ csrf_token() }}',
                action_type: action,
                id_semester: context.id_semester,
                id_prodi: context.id_prodi,
                angkatan: context.angkatan,
                semester_ke: context.semester_ke,
                build_mode: getHistoricalBuildMode(),
                selected_mahasiswa_ids: selectedIds,
                notes: action === 'build_historical_krs' ?
                    $('#studyHistoricalBuildNotes').val() : $('#studyHistoricalMutationNotes').val()
            };

            if (action === 'build_historical_krs') {
                try {
                    payload.students_payload = buildHistoricalStudentsPayload();
                } catch (error) {
                    alert(error.message);
                    return;
                }

                if (!payload.students_payload.length) {
                    alert('Siapkan minimal satu form mata kuliah mahasiswa sebelum melihat pratinjau.');
                    return;
                }
            } else if (action === 'generate_khs') {
                payload.students_payload = buildHistoricalGenerateKhsPayload();
            }

            $.post('{{ route('akademik.administrasi-studi.historical.preview') }}', payload)
                .done(function(response) {
                    const rows = response.data ?? [];
                    renderHistoricalPreviewResults(targetSelector, rows, 'Pratinjau tidak menghasilkan data.');
                    $(executeButtonSelector).prop('disabled', !rows.length);
                })
                .fail(function(xhr) {
                    $(executeButtonSelector).prop('disabled', true);
                    alert(xhr.responseJSON?.message ?? 'Pratinjau riwayat studi gagal dijalankan.');
                });
        }

        function submitHistoricalExecute(action, targetSelector, executeButtonSelector) {
            const context = validateHistoricalContext({
                requireProdi: action === 'build_historical_krs',
                requireSemesterKe: action === 'build_historical_krs',
            });
            if (!context) {
                return;
            }

            const selectedIds = getSelectedHistoricalStudentIds();
            if (!selectedIds.length) {
                alert('Pilih minimal satu mahasiswa terlebih dahulu.');
                return;
            }

            if (!confirm('Apakah Anda ingin menjalankan proses riwayat studi ini sekarang?')) {
                return;
            }

            const payload = {
                _token: '{{ csrf_token() }}',
                action_type: action,
                id_semester: context.id_semester,
                id_prodi: context.id_prodi,
                angkatan: context.angkatan,
                semester_ke: context.semester_ke,
                build_mode: getHistoricalBuildMode(),
                selected_mahasiswa_ids: selectedIds,
                notes: action === 'build_historical_krs' ?
                    $('#studyHistoricalBuildNotes').val() : $('#studyHistoricalMutationNotes').val()
            };

            if (action === 'build_historical_krs') {
                try {
                    payload.students_payload = buildHistoricalStudentsPayload();
                } catch (error) {
                    alert(error.message);
                    return;
                }
            } else if (action === 'generate_khs') {
                payload.students_payload = buildHistoricalGenerateKhsPayload();
            }

            const $button = $(executeButtonSelector);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');

            $.post('{{ route('akademik.administrasi-studi.historical.execute') }}', payload)
                .done(function(response) {
                    const rows = response.data?.results ?? [];
                    renderHistoricalPreviewResults(targetSelector, rows, 'Proses selesai tanpa detail hasil.');
                    alert(response.message ?? 'Proses riwayat studi selesai dijalankan.');

                    if (response.data?.redirect_url) {
                        window.open(response.data.redirect_url, '_blank');
                    }
                })
                .fail(function(xhr) {
                    alert(xhr.responseJSON?.message ?? 'Proses riwayat studi gagal dijalankan.');
                })
                .always(function() {
                    $button.prop('disabled', false).html(action === 'build_historical_krs' ?
                        '<i class="fas fa-play me-1"></i> Jalankan Proses' :
                        '<i class="fas fa-play me-1"></i> Jalankan Proses');
                });
        }

        $('#studyHistoricalLoadBtn').on('click', function() {
            loadHistoricalWorkspaceData('#studyHistoricalLoadBtn');
        });

        $('#studyRiwayatLoadBtn').on('click', function() {
            loadHistoricalWorkspaceData('#studyRiwayatLoadBtn');
        });

        $('#studyHistoricalSelectAll').on('change', function() {
            $('.study-historical-student-checkbox').prop('checked', $(this).is(':checked'));
            renderHistoricalSelectionSummary();
            resetHistoricalExecuteButtons();
        });

        $(document).on('change', '.study-historical-student-checkbox', function() {
            const total = $('.study-historical-student-checkbox').length;
            const checked = $('.study-historical-student-checkbox:checked').length;
            $('#studyHistoricalSelectAll').prop('checked', total > 0 && total === checked);
            renderHistoricalSelectionSummary();
            resetHistoricalExecuteButtons();
        });

        $('#studyHistoricalEligibleSearch').on('input', function() {
            filterHistoricalEligibleStudents();
        });

        $('#studyHistoricalEligiblePrevPage').on('click', function() {
            if (historicalEligiblePage > 1) {
                historicalEligiblePage -= 1;
                renderHistoricalEligibleStudents(historicalEligibleStudents);
            }
        });

        $('#studyHistoricalEligibleNextPage').on('click', function() {
            historicalEligiblePage += 1;
            renderHistoricalEligibleStudents(historicalEligibleStudents);
        });

        $(document).on('click', '.study-builder-nav-item', function() {
            historicalBuilderActiveStudentId = $(this).data('id-mahasiswa');
            renderHistoricalBuilderCards();
        });

        $(document).on('click', '.study-builder-prev', function() {
            const selectedIds = getSelectedHistoricalStudentIds();
            const currentId = $(this).data('id-mahasiswa');
            const currentIndex = selectedIds.indexOf(currentId);
            if (currentIndex > 0) {
                historicalBuilderActiveStudentId = selectedIds[currentIndex - 1];
                renderHistoricalBuilderCards();
            }
        });

        $(document).on('click', '.study-builder-next', function() {
            const selectedIds = getSelectedHistoricalStudentIds();
            const currentId = $(this).data('id-mahasiswa');
            const currentIndex = selectedIds.indexOf(currentId);
            if (currentIndex >= 0 && currentIndex < selectedIds.length - 1) {
                historicalBuilderActiveStudentId = selectedIds[currentIndex + 1];
                renderHistoricalBuilderCards();
            }
        });

        $(document).on('click', '#studyHistoricalBuilderPrevPage', function() {
            if (historicalBuilderPage > 1) {
                historicalBuilderPage -= 1;
                const selectedStudents = getSelectedHistoricalStudents();
                const startIndex = (historicalBuilderPage - 1) * historicalPageSize;
                historicalBuilderActiveStudentId = selectedStudents[startIndex]?.id ?? historicalBuilderActiveStudentId;
                renderHistoricalBuilderCards();
            }
        });

        $(document).on('click', '#studyHistoricalBuilderNextPage', function() {
            const selectedStudents = getSelectedHistoricalStudents();
            const totalPages = Math.max(1, Math.ceil(selectedStudents.length / historicalPageSize));
            if (historicalBuilderPage < totalPages) {
                historicalBuilderPage += 1;
                const startIndex = (historicalBuilderPage - 1) * historicalPageSize;
                historicalBuilderActiveStudentId = selectedStudents[startIndex]?.id ?? historicalBuilderActiveStudentId;
                renderHistoricalBuilderCards();
            }
        });

        $('#studyHistoricalPrepareBuilderBtn').on('click', function() {
            const selectedIds = getSelectedHistoricalStudentIds();

            if (!selectedIds.length) {
                renderHistoricalBuilderCards();
                return;
            }

            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyiapkan...');

            loadHistoricalRepeatCandidates(selectedIds).always(function() {
                renderHistoricalBuilderCards();
                $button.prop('disabled', false).html('<i class="fas fa-pen-ruler me-1"></i> Siapkan Form KRS');
            });
        });

        $('#studyHistoricalBuildMode').on('change', function() {
            syncHistoricalBuildModeHelper();
            if ($('.study-student-builder-card').length) {
                renderHistoricalBuilderCards();
            }
            resetHistoricalExecuteButtons();
        });

        syncHistoricalBuildModeHelper();

        $('#studyHistoricalPreviewBuildBtn').on('click', function() {
            submitHistoricalPreview('build_historical_krs', '#studyHistoricalBuildPreviewResults',
                '#studyHistoricalExecuteBuildBtn');
        });

        $('#studyHistoricalExecuteBuildBtn').on('click', function() {
            submitHistoricalExecute('build_historical_krs', '#studyHistoricalBuildPreviewResults',
                '#studyHistoricalExecuteBuildBtn');
        });

        $('#studyHistoricalPreviewMutationBtn').on('click', function() {
            submitHistoricalPreview($('#studyHistoricalMutationAction').val(),
                '#studyHistoricalMutationPreviewResults', '#studyHistoricalExecuteMutationBtn');
        });

        $('#studyHistoricalExecuteMutationBtn').on('click', function() {
            submitHistoricalExecute($('#studyHistoricalMutationAction').val(),
                '#studyHistoricalMutationPreviewResults', '#studyHistoricalExecuteMutationBtn');
        });

        $('#studyHistoricalMutationAction').on('change', function() {
            syncHistoricalMutationManualIpkPanel();
            resetHistoricalExecuteButtons();
        });

        $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', function() {
            historicalEligibleStudents = [];
            historicalPackageClasses = [];
            historicalRepeatCandidatesByStudent = {};
            historicalBuilderActiveStudentId = null;
            historicalEligiblePage = 1;
            historicalBuilderPage = 1;
            $('#studyHistoricalEligibleSummary').text('Belum ada mahasiswa yang dimuat.');
            $('#studyHistoricalEligiblePagerInfo').text('Belum ada halaman untuk ditampilkan.');
            $('#studyHistoricalEligibleSearch').val('');
            $('#studyHistoricalEligibleTableBody').html(
                '<tr><td colspan="5" class="text-center text-muted py-4">Filter berubah. Muat ulang data historis untuk melihat data terbaru.</td></tr>'
            );
            $('#studyHistoricalPackageClasses').html(
                '<div class="text-muted">Filter berubah. Muat ulang data historis untuk menampilkan daftar kelas.</div>'
            );
            $('#studyHistoricalBuilderCards').html(
                '<div class="text-muted">Filter berubah. Siapkan kembali form nilai setelah data historis dimuat ulang.</div>'
            );
            renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', [],
                'Filter berubah. Jalankan pratinjau lagi untuk melihat hasil sesuai filter terbaru.');
            renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', [],
                'Filter berubah. Jalankan pratinjau lagi untuk melihat hasil aksi riwayat sesuai filter terbaru.'
            );
            renderHistoricalSelectionSummary();
            resetHistoricalExecuteButtons();
        });

        $('#studyExportTemplateBtn').on('click', function(event) {
            syncImportFormsFromWorkspaceContext();

            if (!$('#studyImportTemplateAngkatan').val() || !$('#studyImportTemplateProdi').val() || !$(
                    '#studyImportTemplateSemester').val() || !$('#studyImportTemplateSemesterKe').val()) {
                event.preventDefault();
                alert(
                    'Lengkapi angkatan, program studi, semester, dan semester ke pada filter di atas sebelum mengunduh template.'
                );
            }
        });

        $('#studyImportUploadForm').on('submit', function(event) {
            syncImportFormsFromWorkspaceContext();

            if (!$('#studyImportUploadSemester').val()) {
                event.preventDefault();
                alert('Pilih semester pada filter di atas sebelum mengunggah file nilai.');
            }
        });

        function renderReadyKhsRows(rows) {
            readyKhsRows = rows || [];

            if (!rows || !rows.length) {
                $('#readyKhsTableBody').html(
                    '<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada mahasiswa yang bisa ditampilkan untuk konteks ini.</td></tr>'
                );
                return;
            }

            const html = rows.map((row) => {
                const isReady = row.status === 'ready';
                const statusBadge = isReady ?
                    '<span class="badge bg-success">Siap</span>' :
                    '<span class="badge bg-secondary">Belum Siap</span>';
                const existingKhsBadge = row.existing_khs ?
                    `<span class="badge ${row.existing_khs_is_final ? 'bg-primary' : 'bg-warning text-dark'}">${row.existing_khs_is_final ? 'KHS Final' : 'KHS Draft'}</span>` :
                    '<span class="text-muted">Belum ada</span>';

                return `
                    <tr>
                        <td>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa)}</div>
                            <div class="small text-muted">${escapeHtml(row.nim)}${row.prodi ? ' • ' + escapeHtml(row.prodi) : ''}</div>
                            <div class="small text-muted mt-1">${escapeHtml(row.message ?? '')}</div>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${row.final_detail ?? 0} / ${row.total_detail ?? 0}</td>
                        <td>${existingKhsBadge}</td>
                        <td>
                            <input
                                type="number"
                                min="0"
                                max="4"
                                step="0.01"
                                class="form-control form-control-sm ready-khs-manual-ipk"
                                data-id-mahasiswa="${escapeHtml(row.id_mahasiswa)}"
                                placeholder="Isi jika semester > 1">
                            <div class="form-text">Semester 1 tetap ikut IPS.</div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm ready-khs-preview-btn" data-id-mahasiswa="${escapeHtml(row.id_mahasiswa)}" ${isReady ? '' : 'disabled'}>
                                    Preview
                                </button>
                                <button type="button" class="btn btn-primary btn-sm ready-khs-generate-btn" data-id-mahasiswa="${escapeHtml(row.id_mahasiswa)}" ${isReady ? '' : 'disabled'}>
                                    Generate
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            $('#readyKhsTableBody').html(html);
        }

        function getReadyKhsManualIpk(mahasiswaId) {
            const inputValue = $(`.ready-khs-manual-ipk[data-id-mahasiswa="${mahasiswaId}"]`).val();
            return inputValue === '' ? null : inputValue;
        }

        function renderKhsPreview(preview) {
            if (!preview || !preview.summary) {
                $('#khsPreviewPanel').html('<div class="text-muted">Preview tidak tersedia.</div>');
                return;
            }

            const summary = preview.summary;
            const details = preview.details || [];
            const semesterKe = preview.semester_ke ?? '-';
            const requiresManualIpk = Boolean(preview.requires_manual_ipk);
            const ipkLabel = requiresManualIpk ? 'IPK Manual' : 'IPK';
            const helperCopy = requiresManualIpk ?
                'Semester ini memakai IPK manual. Pastikan nilai pada kolom IPK sudah diisi sebelum generate.' :
                'Semester 1 tetap memakai IPK yang mengikuti IPS semester tersebut.';

            $('#khsPreviewPanel').html(`
                <div class="mb-2"><strong>Semester Ke:</strong> ${semesterKe}</div>
                <div class="mb-2"><strong>Total SKS Diambil:</strong> ${summary.total_sks_diambil ?? 0}</div>
                <div class="mb-2"><strong>Total SKS Lulus:</strong> ${summary.total_sks_lulus ?? 0}</div>
                <div class="mb-2"><strong>IPS:</strong> ${formatGpaValue(summary.ips)}</div>
                <div class="mb-2"><strong>${ipkLabel}:</strong> ${formatGpaValue(summary.ipk)}</div>
                <div class="mb-3"><strong>Keterangan:</strong> ${escapeHtml(summary.keterangan ?? '-')}</div>
                <div class="small text-muted mb-2">${escapeHtml(helperCopy)}</div>
                <div class="small text-muted">Total mata kuliah yang masuk ke preview: ${details.length}</div>
            `);
        }

        $('#loadReadyKhsBtn').on('click', function() {
            const semesterId = $('#studySemesterId').val();
            if (!semesterId) {
                alert('Pilih semester pada filter di atas sebelum menampilkan mahasiswa yang siap dibuatkan KHS.');
                return;
            }

            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

            $.get('{{ route('akademik.administrasi-studi.ready-khs') }}', {
                id_semester: semesterId,
                id_prodi: $('#studyProdiId').val(),
                angkatan: $('#studyAngkatan').val()
            }).done(function(response) {
                renderReadyKhsRows(response.data ?? []);
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.message ?? 'Data mahasiswa yang siap dibuatkan KHS gagal dimuat.');
            }).always(function() {
                $button.prop('disabled', false).html(
                    '<i class="fas fa-list-check me-1"></i> Muat Mahasiswa Siap');
            });
        });

        $(document).on('click', '.ready-khs-preview-btn', function() {
            const mahasiswaId = $(this).data('id-mahasiswa');
            const semesterId = $('#studySemesterId').val();
            const manualIpk = getReadyKhsManualIpk(mahasiswaId);

            $.get('{{ route('akademik.administrasi-studi.generate-khs.preview') }}', {
                id_mahasiswa: mahasiswaId,
                id_semester: semesterId,
                ipk: manualIpk
            }).done(function(response) {
                renderKhsPreview(response.data ?? {});
            }).fail(function(xhr) {
                const errorData = xhr.responseJSON?.data ?? {};
                const semesterKe = errorData.semester_ke ? `<div class="small mt-2">Semester ke: ${escapeHtml(errorData.semester_ke)}</div>` : '';
                $('#khsPreviewPanel').html(
                    `<div class="text-danger">${escapeHtml(xhr.responseJSON?.message ?? 'Pratinjau KHS gagal dimuat.')}</div>${semesterKe}`
                );
            });
        });

        $(document).on('click', '.ready-khs-generate-btn', function() {
            const mahasiswaId = $(this).data('id-mahasiswa');
            const semesterId = $('#studySemesterId').val();
            const manualIpk = getReadyKhsManualIpk(mahasiswaId);
            const $button = $(this);

            if (!confirm('Apakah Anda ingin membuat KHS untuk mahasiswa ini sekarang?')) {
                return;
            }

            $button.prop('disabled', true).text('Memproses...');

            $.post('{{ route('akademik.administrasi-studi.generate-khs.execute') }}', {
                _token: '{{ csrf_token() }}',
                id_mahasiswa: mahasiswaId,
                id_semester: semesterId,
                ipk: manualIpk
            }).done(function(response) {
                const khsId = response.data?.id;
                alert(response.message ?? 'KHS berhasil dibuat.');

                if (khsId) {
                    window.open(`{{ url('akademik/khs') }}/${khsId}`, '_blank');
                }
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.message ?? 'KHS gagal dibuat.');
            }).always(function() {
                $button.prop('disabled', false).text('Generate');
            });
        });

        syncHistoricalMutationManualIpkPanel();

        $('#studyTabs [data-bs-toggle="pill"]').on('shown.bs.tab', function() {
            const tabKey = $(this).data('tab-key');
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabKey);
            window.history.replaceState({}, '', url);
        });
    </script>
@endpush
