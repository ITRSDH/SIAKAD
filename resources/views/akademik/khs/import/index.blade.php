@extends('layouts.index')
@section('title', 'Import KHS')

@php
    $defaultSemester = collect($semesterOptions ?? [])->firstWhere('is_active', true)['id'] ?? '';
    $historyCollection = collect($historyItems ?? []);
    $latestBatch = $historyCollection->first();
    $processedCount = $historyCollection->filter(fn($item) => ($item['status'] ?? '') === 'processed')->count();
    $warningCount = $historyCollection->sum(fn($item) => (int) ($item['summary']['total_warning'] ?? 0));
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
        'failed' => 'Gagal Diproses',
        'rolled_back' => 'Sudah Dibatalkan',
    ];
    $formatDateTime = function ($value) {
        if (blank($value)) {
            return '-';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->locale('id')->translatedFormat('d M Y, H:i');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    };
@endphp

@push('styles-custom')
    <style>
        .khs-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.28), transparent 28%),
                linear-gradient(135deg, #0f766e 0%, #1d4ed8 52%, #0f172a 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.82rem;
            letter-spacing: 0.02em;
        }

        .khs-hero-title {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            line-height: 1.1;
            max-width: 14ch;
            margin: 1rem 0 0.75rem;
        }

        .khs-hero-copy {
            max-width: 60ch;
            color: rgba(255, 255, 255, 0.84);
            margin-bottom: 1.25rem;
        }

        .khs-step-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .khs-step-card {
            border-radius: 18px;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
        }

        .khs-step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.18);
            margin-bottom: 0.75rem;
        }

        .khs-panel {
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .khs-panel-header {
            padding: 1.25rem 1.5rem 0;
        }

        .khs-panel-body {
            padding: 1.5rem;
        }

        .khs-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .khs-section-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .khs-section-copy {
            color: #64748b;
            margin-bottom: 0;
        }

        .khs-soft-box {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 1rem;
            height: 100%;
        }

        .khs-soft-box.tint {
            background: linear-gradient(180deg, #f8fffc 0%, #effaf6 100%);
            border-color: #d4f3e6;
        }

        .khs-soft-box .label {
            font-size: 0.78rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.55rem;
        }

        .khs-stat {
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 1rem;
            height: 100%;
        }

        .khs-stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .khs-stat-label {
            margin-top: 0.55rem;
            color: #64748b;
            font-size: 0.88rem;
        }

        .khs-upload-zone {
            border: 1px dashed #94a3b8;
            border-radius: 20px;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.05), transparent 30%),
                #f8fafc;
            padding: 1rem;
        }

        .khs-timeline {
            display: grid;
            gap: 0.9rem;
        }

        .khs-batch-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }

        .khs-batch-meta {
            color: #64748b;
            font-size: 0.85rem;
        }

        .khs-mini-note {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 1rem;
        }

        .khs-progress-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #cbd5e1;
            display: inline-block;
        }

        .khs-progress-dot.active {
            background: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
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
            .khs-step-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Import Nilai KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.index') }}">Import KHS</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="khs-hero-kicker">
                    <i class="fas fa-layer-group"></i>
                    <span>Alur kerja BAAK yang lebih sederhana</span>
                </div>
                <div class="row g-4 align-items-end">
                    <div class="col-xl-7">
                        <h1 class="khs-hero-title">Siapkan template, isi nilai, lalu unggah kembali.</h1>
                        <p class="khs-hero-copy">
                            Halaman ini saya sederhanakan agar mudah dipahami. Anda cukup memilih data akademik,
                            mengunduh template Excel, mengisi nilai angka, lalu mengunggah file untuk dicek sebelum nilai
                            disimpan ke KRS dan KHS dibentuk.
                        </p>
                    </div>
                    <div class="col-xl-5">
                        <div class="khs-step-grid">
                            <div class="khs-step-card">
                                <div class="khs-step-number">1</div>
                                <div class="fw-semibold mb-1">Pilih data</div>
                                <div class="small text-white-50">Tentukan angkatan, prodi, semester akademik, dan semester
                                    ke.</div>
                            </div>
                            <div class="khs-step-card">
                                <div class="khs-step-number">2</div>
                                <div class="fw-semibold mb-1">Unduh template</div>
                                <div class="small text-white-50">File Excel akan menyesuaikan mahasiswa dan mata kuliah dari
                                    filter yang dipilih.</div>
                            </div>
                            <div class="khs-step-card">
                                <div class="khs-step-number">3</div>
                                <div class="fw-semibold mb-1">Unggah untuk dicek</div>
                                <div class="small text-white-50">Sistem membuat preview lebih dulu agar data bisa diperiksa
                                    sebelum nilai disimpan dan KHS dibentuk.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card khs-panel">
                    <div class="khs-panel-header">
                        <span class="khs-chip">
                            <i class="fas fa-wand-magic-sparkles"></i>
                            Halaman kerja utama
                        </span>
                        <h4 class="khs-section-title mt-3">Mulai dari filter data akademik</h4>
                        <p class="khs-section-copy">
                            Isi filter di bawah ini, lalu pilih apakah Anda ingin mengunduh template atau langsung
                            mengunggah file yang sudah diisi.
                        </p>
                    </div>
                    <div class="khs-panel-body">
                        <form id="khsBatchForm" method="POST" action="{{ route('akademik.khs.import.upload') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-xl-3">
                                    <label class="form-label">Angkatan</label>
                                    <input type="number" name="angkatan" id="angkatan" class="form-control"
                                        value="{{ old('angkatan', date('Y')) }}" min="1990" max="{{ date('Y') + 10 }}"
                                        required>
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label class="form-label">Program Studi</label>
                                    <select name="id_prodi" id="id_prodi" class="form-select select2" required>
                                        <option value="">Pilih program studi</option>
                                        @foreach ($prodiOptions as $item)
                                            <option value="{{ $item['id'] ?? '' }}" @selected(old('id_prodi') == ($item['id'] ?? ''))>
                                                {{ $item['nama_prodi'] ?? 'Program Studi' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label class="form-label">Semester Akademik</label>
                                    <select name="id_semester" id="id_semester" class="form-select select2" required>
                                        <option value="">Pilih semester</option>
                                        @foreach ($semesterOptions as $item)
                                            <option value="{{ $item['id'] }}" @selected(old('id_semester', $defaultSemester) == $item['id'])>
                                                {{ $item['label'] }}{{ !empty($item['is_active']) ? ' (Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <label class="form-label">Semester Ke</label>
                                    <select name="semester_ke" id="semester_ke" class="form-select select2" required>
                                        <option value="">Pilih semester</option>
                                        @foreach (range(1, 14) as $i)
                                            <option value="{{ $i }}" @selected(old('semester_ke') == $i)>
                                                {{ $i }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <div class="khs-soft-box tint">
                                        <div class="label">Langkah 1</div>
                                        <h5 class="fw-bold mb-2">Unduh template Excel</h5>
                                        <p class="text-muted small mb-3">
                                            Template akan berisi daftar mahasiswa dan mata kuliah sesuai filter yang
                                            dipilih. Bagian yang diisi cukup nilai angka.
                                        </p>
                                        <button type="button" class="btn btn-success w-100" id="exportTemplateBtn">
                                            <i class="fas fa-file-export me-1"></i> Unduh Template
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="khs-soft-box">
                                        <div class="label">Langkah 2</div>
                                        <h5 class="fw-bold mb-2">Unggah file yang sudah diisi</h5>
                                        <p class="text-muted small mb-3">
                                            Setelah nilai diisi di Excel, unggah file di sini agar sistem membuat preview,
                                            lalu menyimpan nilai ke KRS dan membentuk KHS dari data final itu.
                                        </p>
                                        <div class="khs-upload-zone">
                                            <input type="file" name="file" id="file" class="form-control"
                                                accept=".xlsx,.xls" required>
                                            <div class="form-text mt-2">Format yang didukung: <code>.xlsx</code> atau
                                                <code>.xls</code>, maksimal 10MB.</div>
                                            <div id="filePreview" class="small text-muted mt-2">Belum ada file yang
                                                dipilih.</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-3" id="uploadPreviewBtn">
                                            <i class="fas fa-cloud-upload-alt me-1"></i> Upload dan Cek Hasil
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="khs-mini-note">
                                <div class="fw-semibold mb-2">Yang perlu Anda ketahui</div>
                                <div class="row g-3 small text-muted">
                                    <div class="col-md-4">
                                        Template mengikuti data mahasiswa dan KRS yang valid pada semester terpilih.
                                    </div>
                                    <div class="col-md-4">
                                        Semester 1 tetap memakai kolom gabungan <code>IP/IPK</code>, sedangkan semester
                                        berikutnya memakai kolom <code>IP</code> dan <code>IPK</code> terpisah. Kolom
                                        <code>IPK</code> semester di atas 1 diisi manual dari Excel.
                                    </div>
                                    <div class="col-md-4">
                                        Jika ada data yang bermasalah, sistem akan menampilkan preview dulu sebelum nilai
                                        disimpan ke KRS.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <a href="{{ route('akademik.khs.import.history') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Bila Perlu
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card khs-panel">
                            <div class="khs-panel-header">
                                <span class="khs-chip">
                                    <i class="fas fa-circle-info"></i>
                                    Bantuan singkat
                                </span>
                                <h4 class="khs-section-title mt-3">Fokus ke proses utama</h4>
                                <p class="khs-section-copy">Bagian samping ini saya buat lebih ringan agar operator cukup
                                    fokus ke unggah, cek, lalu proses hasil nilai.</p>
                            </div>
                            <div class="khs-panel-body">
                                <div class="khs-mini-note mb-3">
                                    <div class="fw-semibold mb-2">Urutan kerja yang disarankan</div>
                                    <div class="small text-muted mb-1">1. Tentukan semester, prodi, angkatan, dan semester ke.</div>
                                    <div class="small text-muted mb-1">2. Unduh template sesuai konteks yang dipilih.</div>
                                    <div class="small text-muted mb-1">3. Isi nilai di Excel lalu unggah untuk dicek.</div>
                                    <div class="small text-muted">4. Proses hasil hanya jika preview sudah sesuai.</div>
                                </div>

                                @if (empty($latestBatch))
                                    <div class="text-muted">Belum ada proses import yang tersimpan.</div>
                                @else
                                    @php
                                        $latestStatus = (string) ($latestBatch['status'] ?? 'uploaded');
                                        $latestSummary = $latestBatch['summary'] ?? [];
                                        $latestSemester = $latestBatch['semester'] ?? [];
                                        $latestTahunAkademik =
                                            $latestSemester['tahun_akademik'] ??
                                            ($latestSemester['tahunAkademik'] ?? []);
                                        $latestSemesterLabel = trim(
                                            (string) (($latestSemester['nama_semester'] ?? '-') .
                                                ' ' .
                                                ($latestTahunAkademik['tahun_akademik'] ?? '')),
                                        );
                                    @endphp
                                    <div class="khs-batch-card">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $latestBatch['file_name'] ?? 'Import Nilai Terakhir' }}
                                                </div>
                                                <div class="khs-batch-meta mt-1">{{ $latestSemesterLabel ?: '-' }}</div>
                                            </div>
                                            @include('layouts.partials.status-badge', [
                                                'value' => $latestStatus,
                                                'label' =>
                                                    $statusLabels[$latestStatus] ??
                                                    ucfirst(str_replace('_', ' ', $latestStatus)),
                                            ])
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            <a href="{{ route('akademik.khs.import.show', ['batch' => $latestBatch['id'], 'legacy' => 1]) }}"
                                                class="btn btn-outline-primary btn-sm">Buka Hasil Terakhir</a>
                                            @if (in_array($latestStatus, ['uploaded', 'previewed', 'failed'], true))
                                                <a href="{{ route('akademik.khs.import.preview', ['batch' => $latestBatch['id'], 'legacy' => 1]) }}"
                                                    class="btn btn-primary btn-sm">Lanjutkan Pengecekan</a>
                                            @elseif ($latestStatus === 'processed')
                                                <a href="{{ route('akademik.khs.import.preview', ['batch' => $latestBatch['id'], 'legacy' => 1]) }}"
                                                    class="btn btn-success btn-sm">Pilih Finalisasi KHS</a>
                                            @endif
                                            <a href="{{ route('akademik.khs.import.history') }}"
                                                class="btn btn-outline-secondary btn-sm">Lihat Riwayat Bila Perlu</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card khs-panel">
                            <div class="khs-panel-header">
                                <h4 class="khs-section-title">Riwayat hanya bila perlu</h4>
                                <p class="khs-section-copy">Bagian ini bersifat opsional dan hanya dipakai jika Anda ingin
                                    menelusuri proses lama.</p>
                            </div>
                            <div class="khs-panel-body">
                                @if ($historyCollection->isEmpty())
                                    <div class="text-muted">Belum ada riwayat proses import.</div>
                                @else
                                    <div class="khs-timeline">
                                        @foreach ($historyCollection->take(3) as $item)
                                            @php
                                                $status = (string) ($item['status'] ?? 'uploaded');
                                                $semester = $item['semester'] ?? [];
                                                $tahunAkademik =
                                                    $semester['tahun_akademik'] ?? ($semester['tahunAkademik'] ?? []);
                                                $semesterLabel = trim(
                                                    (string) (($semester['nama_semester'] ?? '-') .
                                                        ' ' .
                                                        ($tahunAkademik['tahun_akademik'] ?? '')),
                                                );
                                            @endphp
                                            <div class="khs-batch-card">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $item['file_name'] ?? 'Import Nilai' }}</div>
                                                        <div class="khs-batch-meta">{{ $semesterLabel ?: '-' }}</div>
                                                        <div class="khs-batch-meta">Upload:
                                                            {{ $formatDateTime($item['created_at'] ?? null) }}</div>
                                                    </div>
                                                    @include('layouts.partials.status-badge', [
                                                        'value' => $status,
                                                        'label' =>
                                                            $statusLabels[$status] ??
                                                            ucfirst(str_replace('_', ' ', $status)),
                                                    ])
                                                </div>
                                                <div class="small text-muted mt-2">
                                                    Siap {{ $item['total_success'] ?? 0 }} • Perlu Perbaikan
                                                    {{ $item['total_failed'] ?? 0 }} • Perlu Dicek
                                                    {{ $item['summary']['total_warning'] ?? 0 }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('akademik.khs.import.history') }}"
                                        class="btn btn-outline-secondary btn-sm mt-3">
                                        Buka Riwayat Bila Perlu
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="khsProgressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Memproses Permintaan KHS</h5>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div>
                            <div class="fw-semibold" id="progressTitle">Menyiapkan proses...</div>
                            <div class="text-muted small" id="progressDescription">Mohon tunggu beberapa saat.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small text-muted">
                        <div class="text-center flex-fill">
                            <span class="khs-progress-dot" data-step="filter"></span>
                            <div class="mt-2">Filter</div>
                        </div>
                        <div class="text-center flex-fill">
                            <span class="khs-progress-dot" data-step="export"></span>
                            <div class="mt-2">Export</div>
                        </div>
                        <div class="text-center flex-fill">
                            <span class="khs-progress-dot" data-step="upload"></span>
                            <div class="mt-2">Upload</div>
                        </div>
                        <div class="text-center flex-fill">
                            <span class="khs-progress-dot" data-step="preview"></span>
                            <div class="mt-2">Preview</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const progressModal = new bootstrap.Modal(document.getElementById('khsProgressModal'));

        function buildTemplateUrl() {
            const params = new URLSearchParams({
                angkatan: $('#angkatan').val(),
                id_prodi: $('#id_prodi').val(),
                id_semester: $('#id_semester').val(),
                semester_ke: $('#semester_ke').val()
            });

            return `{{ route('akademik.khs.import.template-export') }}?${params.toString()}`;
        }

        function validateFilterFields() {
            if (!$('#angkatan').val() || !$('#id_prodi').val() || !$('#id_semester').val() || !$('#semester_ke').val()) {
                alert('Lengkapi angkatan, program studi, semester akademik, dan semester ke terlebih dahulu.');
                return false;
            }

            return true;
        }

        function showProgressModal(mode) {
            $('[data-step]').removeClass('active');

            if (mode === 'export') {
                $('#progressTitle').text('Menyiapkan template Excel');
                $('#progressDescription').text('Sistem sedang menyusun file template sesuai filter yang Anda pilih.');
                $('[data-step="filter"], [data-step="export"]').addClass('active');
            } else {
                $('#progressTitle').text('Mengunggah file untuk preview');
                $('#progressDescription').text(
                    'File sedang dikirim agar sistem bisa mengecek data sebelum menyimpan nilai ke KRS dan membentuk KHS.'
                    );
                $('[data-step="filter"], [data-step="upload"], [data-step="preview"]').addClass('active');
            }

            progressModal.show();
        }

        $('#exportTemplateBtn').on('click', function() {
            if (!validateFilterFields()) {
                return;
            }

            showProgressModal('export');
            window.location.href = buildTemplateUrl();
        });

        $('#file').on('change', function() {
            const file = this.files[0];
            if (!file) {
                $('#filePreview').text('Belum ada file yang dipilih.');
                return;
            }

            const sizeMb = (file.size / 1024 / 1024).toFixed(2);
            $('#filePreview').html(
                `<span class="text-success">File siap diunggah:</span> ${file.name} (${sizeMb} MB)`);
        });

        $('#khsBatchForm').on('submit', function() {
            showProgressModal('upload');
            $('#uploadPreviewBtn').prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i> Mengunggah...');
            $('#exportTemplateBtn').prop('disabled', true);
        });
    </script>
@endpush
