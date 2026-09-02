@extends('layouts.index')
@section('title', 'Cek Hasil Import KHS')

@php
    $summary = $preview['summary'] ?? [];
    $rows = $preview['rows'] ?? [];
    $batchStatus = (string) ($batch['status'] ?? 'uploaded');
    $canProcess = ($summary['total_valid'] ?? 0) > 0 && in_array($batchStatus, ['uploaded', 'previewed', 'failed'], true);
    $processedKhsItems = collect($batch['summary']['processed_khs_items'] ?? [])->values()->all();
    $processedKhsFinalCount = collect($processedKhsItems)->where('is_final', true)->count();
    $processedKhsDraftCount = max(count($processedKhsItems) - $processedKhsFinalCount, 0);
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
        'failed' => 'Gagal Diproses',
        'rolled_back' => 'Sudah Dibatalkan',
    ];
@endphp

@push('styles-custom')
    <style>
        .khs-shell-card {
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .khs-shell-header {
            padding: 1.4rem 1.5rem 0;
        }

        .khs-shell-body {
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

        .khs-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 25%),
                linear-gradient(135deg, #0f172a 0%, #1d4ed8 58%, #0891b2 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-hero-copy {
            color: rgba(255, 255, 255, 0.82);
            max-width: 60ch;
        }

        .khs-summary-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .khs-summary-card {
            border-radius: 18px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            height: 100%;
        }

        .khs-summary-label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.45rem;
        }

        .khs-summary-value {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            color: #0f172a;
        }

        .khs-note {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
            padding: 1rem;
        }

        .khs-process-guide {
            border: 1px solid #dbeafe;
            border-radius: 18px;
            background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
            padding: 1rem;
        }

        .khs-process-guide-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
            margin-top: 0.9rem;
        }

        .khs-process-guide-item {
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #dbeafe;
            padding: 0.9rem;
            height: 100%;
        }

        .khs-process-guide-step {
            width: 1.9rem;
            height: 1.9rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
            margin-bottom: 0.7rem;
        }

        .khs-row-card {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: #fff;
            overflow: hidden;
        }

        .khs-row-card.error {
            border-color: #fecaca;
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.08);
        }

        .khs-row-card.warning {
            border-color: #fde68a;
            box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.08);
        }

        .khs-row-header {
            padding: 1rem 1.2rem;
            background: #fff;
        }

        .khs-row-body {
            padding: 0 1.2rem 1.2rem;
        }

        .khs-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.6rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .khs-pill.success {
            background: #dcfce7;
            color: #166534;
        }

        .khs-pill.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .khs-pill.danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .khs-stats-inline {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 0.9rem;
        }

        .khs-stats-inline .item {
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.8rem;
        }

        .khs-stats-inline .item .label {
            color: #64748b;
            font-size: 0.76rem;
            margin-bottom: 0.25rem;
        }

        .khs-stats-inline .item .value {
            font-weight: 700;
            color: #0f172a;
        }

        .khs-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .khs-table-wrap table {
            margin-bottom: 0;
        }

        .khs-khs-picker {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 1rem;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        }

        .khs-finalize-spotlight {
            border: 1px solid #bfdbfe;
            border-radius: 24px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.65), transparent 24%),
                linear-gradient(135deg, #eff6ff 0%, #dbeafe 52%, #e0f2fe 100%);
            padding: 1.25rem;
        }

        .khs-finalize-stat {
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(191, 219, 254, 0.95);
            padding: 0.85rem 1rem;
        }

        .khs-finalize-stat .label {
            font-size: 0.78rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.25rem;
        }

        .khs-finalize-stat .value {
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1;
            color: #0f172a;
        }

        .khs-khs-list {
            display: grid;
            gap: 0.75rem;
            max-height: 360px;
            overflow: auto;
        }

        .khs-khs-item {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #fff;
            padding: 0.9rem 1rem;
        }

        .khs-khs-item.is-final {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        @media (max-width: 1199.98px) {
            .khs-summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .khs-summary-grid,
            .khs-stats-inline {
                grid-template-columns: 1fr;
            }

            .khs-process-guide-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Cek Data Nilai KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.index') }}">Import KHS</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.preview', $batch['id'] ?? '') }}">Hasil Pengecekan</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <span class="khs-chip">
                            <i class="fas fa-magnifying-glass-chart"></i>
                            Langkah pengecekan data
                        </span>
                        <h2 class="fw-bold mt-3 mb-2">{{ $batch['file_name'] ?? 'Import Nilai KHS' }}</h2>
                        <p class="khs-hero-copy mb-0">
                            Di halaman ini Anda bisa melihat data yang sudah siap disimpan ke KRS lalu dibentuk menjadi KHS, serta data yang masih perlu diperbaiki.
                        </p>
                    </div>
                    <div class="text-md-end">
                        <div class="small text-white-50 mb-2">Tahap saat ini</div>
                        @include('layouts.partials.status-badge', ['value' => $batchStatus, 'label' => $statusLabels[$batchStatus] ?? ucfirst(str_replace('_', ' ', $batchStatus))])
                        <div class="mt-3">
                            <a href="{{ route('akademik.administrasi-studi.nilai') }}"
                                class="btn btn-light btn-sm">
                                Kembali ke Input Nilai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (request()->boolean('legacy'))
            <div class="alert alert-info border mb-4">
                Halaman ini dipakai untuk <strong>memilih KHS per mahasiswa</strong> yang ingin difinalisasi.
                Jika Anda datang dari workspace Administrasi Studi Mahasiswa, lanjutkan pilihan finalisasi di sini lalu kembali lagi ke workspace bila perlu.
            </div>
        @endif

        @if (in_array(($batch['status'] ?? ''), ['previewed', 'processed'], true))
            <div class="card khs-shell-card mb-4">
                <div class="khs-shell-body">
                    <div class="khs-finalize-spotlight">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                            <div>
                                <span class="khs-chip">
                                    <i class="fas fa-lock"></i>
                                    Finalisasi Hasil KHS
                                </span>
                                @if (($batch['status'] ?? '') === 'processed')
                                    <h4 class="fw-bold mt-3 mb-2">Sahkan hasil nilai mahasiswa</h4>
                                    <p class="text-muted mb-0">
                                        Hasil ini sudah berhasil diproses. Nilai sudah disimpan ke KRS dan KHS sudah dibentuk.
                                        Anda bisa langsung memfinalisasi semua KHS sekaligus, atau
                                        <strong>memilih mahasiswa tertentu pada daftar di bawah</strong>.
                                    </p>
                                @else
                                    <h4 class="fw-bold mt-3 mb-2">Finalisasi muncul setelah data diproses</h4>
                                    <p class="text-muted mb-0">
                                        Saat ini data masih tahap pengecekan. Langkah berikutnya adalah
                                        klik <strong>Simpan Nilai dan Generate KHS</strong>. Setelah itu Anda bisa memfinalisasi
                                        seluruh hasil atau memilih mahasiswa tertentu.
                                    </p>
                                @endif
                            </div>
                            @if (($batch['status'] ?? '') === 'processed')
                                <form method="POST" action="{{ route('akademik.khs.import.finalize', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Finalisasi semua KHS dari hasil ini sekarang?')">
                                        <i class="fas fa-lock me-1"></i> Finalisasi Semua KHS Sekaligus
                                    </button>
                                </form>
                            @elseif ($canProcess)
                                <form method="POST" action="{{ route('akademik.khs.import.process', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Simpan nilai import ke KRS lalu bentuk KHS dari hasil ini sekarang?')">
                                        <i class="fas fa-play me-1"></i> Simpan Nilai dan Generate KHS
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <div class="khs-finalize-stat">
                                    <div class="label">Total Data Mahasiswa</div>
                                    <div class="value">{{ ($batch['status'] ?? '') === 'processed' ? count($processedKhsItems) : ($summary['total_valid'] ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="khs-finalize-stat">
                                    <div class="label">{{ ($batch['status'] ?? '') === 'processed' ? 'Belum Final' : 'Siap Diproses' }}</div>
                                    <div class="value text-warning">{{ ($batch['status'] ?? '') === 'processed' ? $processedKhsDraftCount : ($summary['total_valid'] ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="khs-finalize-stat">
                                    <div class="label">{{ ($batch['status'] ?? '') === 'processed' ? 'Sudah Final' : 'Perlu Diperbaiki' }}</div>
                                    <div class="value {{ ($batch['status'] ?? '') === 'processed' ? 'text-success' : 'text-danger' }}">{{ ($batch['status'] ?? '') === 'processed' ? $processedKhsFinalCount : ($summary['total_error'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card khs-shell-card mb-4">
            <div class="khs-shell-header">
                <h4 class="fw-bold mb-1">Ringkasan Data</h4>
                <p class="text-muted mb-0">Bagian ini membantu Anda melihat cepat mana data yang siap diproses dan mana yang masih perlu dibenahi.</p>
            </div>
            <div class="khs-shell-body">
                <div class="khs-process-guide mb-4">
                    <div class="fw-semibold mb-2">Apa yang akan dilakukan sistem saat tombol proses ditekan?</div>
                    <div class="text-muted small">
                        Sistem tidak langsung menulis ke hasil KHS. Sistem akan memproses data secara bertahap agar lebih aman dan mudah dilacak.
                    </div>
                    <div class="khs-process-guide-grid">
                        <div class="khs-process-guide-item">
                            <div class="khs-process-guide-step">1</div>
                            <div class="fw-semibold mb-1">Simpan nilai ke KRS</div>
                            <div class="small text-muted">Nilai final dari file import disinkronkan dulu ke data KRS mahasiswa yang cocok.</div>
                        </div>
                        <div class="khs-process-guide-item">
                            <div class="khs-process-guide-step">2</div>
                            <div class="fw-semibold mb-1">Bentuk hasil KHS</div>
                            <div class="small text-muted">Setelah nilai KRS lengkap, sistem membentuk atau memperbarui KHS dan detail hasil studinya.</div>
                        </div>
                        <div class="khs-process-guide-item">
                            <div class="khs-process-guide-step">3</div>
                            <div class="fw-semibold mb-1">Riwayat proses tetap tersimpan</div>
                            <div class="small text-muted">Sistem tetap mencatat proses ini agar riwayat, pengecekan, dan rollback bisa dilakukan bila diperlukan.</div>
                        </div>
                    </div>
                </div>

                <div class="khs-summary-grid">
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Total Baris</div>
                        <div class="khs-summary-value">{{ $summary['total_rows'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Siap Diproses</div>
                        <div class="khs-summary-value text-success">{{ $summary['total_valid'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Perlu Perbaikan</div>
                        <div class="khs-summary-value text-danger">{{ $summary['total_error'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Perlu Dicek</div>
                        <div class="khs-summary-value text-warning">{{ $summary['total_warning'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Keterangan Berbeda</div>
                        <div class="khs-summary-value">{{ $summary['total_keterangan_mismatch'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Mata Kuliah Berbeda</div>
                        <div class="khs-summary-value">{{ $summary['total_mk_mismatched'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-lg-7">
                        <div class="khs-note h-100">
                            <div class="fw-semibold mb-2">Informasi file</div>
                            <div class="small text-muted mb-2">
                                <strong>Keterangan file:</strong> {{ $metadata['raw'] ?? '-' }}
                            </div>
                            <div class="small text-muted">
                                <strong>Mata kuliah yang terbaca:</strong> {{ count($subjects) }} mata kuliah
                            </div>
                            <div class="small text-muted mt-2">
                                Catatan error dan warning sudah ditampilkan langsung di halaman ini agar operator tidak perlu membuka layar teknis tambahan.
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-wrap gap-2 h-100 align-items-center justify-content-lg-end">
                            <a href="{{ route('akademik.administrasi-studi.nilai') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Input Nilai
                            </a>
                            <a href="{{ route('akademik.khs.import.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Bila Perlu
                            </a>
                            @if ($canProcess)
                                <form method="POST" action="{{ route('akademik.khs.import.process', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan nilai import ke KRS lalu bentuk KHS dari hasil ini sekarang?')">
                                        <i class="fas fa-play me-1"></i> Simpan Nilai dan Generate KHS
                                    </button>
                                </form>
                            @endif
                            @if (($batch['status'] ?? '') === 'processed' && !empty($processedKhsItems))
                                <form method="POST" action="{{ route('akademik.khs.import.finalize', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Finalisasi semua KHS dari hasil ini sekarang?')">
                                        <i class="fas fa-lock me-1"></i> Finalisasi Semua KHS Sekaligus
                                    </button>
                                </form>
                            @endif
                            @if (($batch['status'] ?? '') === 'processed')
                                <form method="POST" action="{{ route('akademik.khs.import.rollback', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Batalkan hasil proses import ini?')">
                                        <i class="fas fa-rotate-left me-1"></i> Batalkan Hasil
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card khs-shell-card mb-4">
            <div class="khs-shell-header d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h4 class="fw-bold mb-1">Hasil per Mahasiswa</h4>
                    <p class="text-muted mb-0">Bagian ini menampilkan hasil baca data untuk tiap mahasiswa dengan bahasa yang lebih sederhana.</p>
                </div>
                <div>
                    <label for="rowFilter" class="small text-muted d-block mb-1">Filter tampilan</label>
                    <select id="rowFilter" class="form-select select2">
                        <option value="all">Semua data</option>
                        <option value="error">Hanya yang bermasalah</option>
                        <option value="warning">Hanya yang perlu dicek</option>
                        <option value="valid">Hanya yang valid</option>
                    </select>
                </div>
            </div>
            <div class="khs-shell-body">
                @if (empty($rows))
                    <div class="text-muted">Belum ada baris hasil pengecekan untuk proses ini.</div>
                @else
                    <div class="d-grid gap-3">
                        @foreach ($rows as $row)
                            @php
                                $rowStatus = !empty($row['errors']) ? 'error' : (!empty($row['warnings']) ? 'warning' : 'valid');
                            @endphp
                            <div class="khs-row-card {{ $rowStatus }} row-preview-item" data-row-status="{{ $rowStatus }}">
                                <div class="khs-row-header">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="fw-bold">{{ $row['nim'] ?? '-' }} - {{ $row['nama'] ?? ($row['mahasiswa']['nama_mahasiswa'] ?? 'Mahasiswa') }}</div>
                                            <div class="small text-muted">Baris file {{ $row['row_number'] ?? '-' }}</div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if (!empty($row['errors']))
                                                <span class="khs-pill danger">Masalah {{ count($row['errors']) }}</span>
                                            @endif
                                            @if (!empty($row['warnings']))
                                                <span class="khs-pill warning">Perlu dicek {{ count($row['warnings']) }}</span>
                                            @endif
                                            @if (empty($row['errors']) && empty($row['warnings']))
                                                <span class="khs-pill success">Siap diproses</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="khs-stats-inline">
                                        <div class="item">
                                            <div class="label">IPS di file</div>
                                            <div class="value">{{ $row['ips_excel'] ?? '-' }}</div>
                                        </div>
                                        <div class="item">
                                            <div class="label">IPS hasil sistem</div>
                                            <div class="value">{{ $row['summary']['ips'] ?? '-' }}</div>
                                        </div>
                                        <div class="item">
                                            <div class="label">Keterangan di file</div>
                                            <div class="value">{{ $row['keterangan'] ?? '-' }}</div>
                                        </div>
                                        <div class="item">
                                            <div class="label">Keterangan hasil sistem</div>
                                            <div class="value">{{ $row['summary']['keterangan'] ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="khs-row-body">
                                    @if (!empty($row['errors']))
                                        <div class="alert alert-danger py-2">
                                            <div class="fw-semibold small mb-1">Yang perlu diperbaiki</div>
                                            <ul class="mb-0 small ps-3">
                                                @foreach ($row['errors'] as $message)
                                                    <li>{{ $message }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (!empty($row['warnings']))
                                        <div class="alert alert-warning py-2">
                                            <div class="fw-semibold small mb-1">Yang perlu dicek</div>
                                            <ul class="mb-0 small ps-3">
                                                @foreach ($row['warnings'] as $message)
                                                    <li>{{ $message }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="khs-table-wrap">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Kode MK</th>
                                                        <th>Mata Kuliah</th>
                                                        <th>SKS</th>
                                                        <th>NA</th>
                                                        <th>NH</th>
                                                        <th>Bobot</th>
                                                        <th>Mutu</th>
                                                        <th>KRS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($row['subjects'] ?? [] as $subject)
                                                        <tr>
                                                            <td>{{ $subject['kode_mk'] ?? '-' }}</td>
                                                            <td>{{ $subject['nama_mk'] ?? '-' }}</td>
                                                            <td class="text-center">{{ $subject['sks'] ?? '-' }}</td>
                                                            <td class="text-center">{{ $subject['nilai_akhir'] ?? '-' }}</td>
                                                            <td class="text-center">{{ $subject['nilai_huruf'] ?? '-' }}</td>
                                                            <td class="text-center">{{ $subject['bobot_nilai'] ?? '-' }}</td>
                                                            <td class="text-center">{{ $subject['mutu'] ?? '-' }}</td>
                                                            <td class="text-center">
                                                                @if (!empty($subject['matched']))
                                                                    <span class="badge bg-success">Cocok</span>
                                                                @else
                                                                    <span class="badge bg-danger">Tidak cocok</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if (!empty($processedKhsItems))
            <div class="card khs-shell-card">
                <div class="khs-shell-header">
                    <h4 class="fw-bold mb-1">Hasil KHS yang Sudah Dibuat</h4>
                    <p class="text-muted mb-0">Cari mahasiswa, centang yang ingin difinalisasi, lalu kirim sekali. Inilah area pilihan finalisasi per mahasiswa.</p>
                </div>
                <div class="khs-shell-body">
                    <div class="khs-khs-picker">
                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-lg-5">
                                <label for="previewKhsSearch" class="form-label">Cari mahasiswa</label>
                                <input type="text" id="previewKhsSearch" class="form-control" placeholder="Ketik nama atau NIM mahasiswa">
                            </div>
                            <div class="col-lg-7">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="selectVisiblePreviewKhsBtn">Pilih yang tampil</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearVisiblePreviewKhsBtn">Batalkan pilihan yang tampil</button>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('akademik.khs.import.finalize', $batch['id']) }}">
                            @csrf
                            <div class="khs-khs-list mb-3" id="previewKhsList">
                                @foreach ($processedKhsItems as $item)
                                    @php
                                        $isItemFinal = !empty($item['is_final']);
                                        $searchText = strtolower(trim(($item['nim'] ?? '') . ' ' . ($item['nama_mahasiswa'] ?? '')));
                                    @endphp
                                    <div class="khs-khs-item {{ $isItemFinal ? 'is-final' : '' }}" data-search="{{ $searchText }}">
                                        <div class="d-flex flex-wrap justify-content-between gap-3">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input preview-khs-finalize-checkbox"
                                                    type="checkbox"
                                                    name="khs_ids[]"
                                                    value="{{ $item['id'] }}"
                                                    id="preview-khs-{{ $item['id'] }}"
                                                    {{ $isItemFinal ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="preview-khs-{{ $item['id'] }}">
                                                    <div class="fw-semibold">{{ $item['nama_mahasiswa'] ?? 'Mahasiswa' }}</div>
                                                    <div class="small text-muted">{{ $item['nim'] ?? '-' }}</div>
                                                </label>
                                            </div>
                                            <div class="text-end">
                                                <div class="small text-muted">IPS {{ $item['ips'] ?? '0.00' }} • IPK {{ $item['ipk'] ?? '0.00' }}</div>
                                                <div class="mt-2 d-flex flex-wrap gap-2 justify-content-end">
                                                    @if ($isItemFinal)
                                                        <span class="badge bg-success">Sudah final</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Belum disahkan</span>
                                                    @endif
                                                    <a href="{{ route('akademik.khs.show', $item['id']) }}" class="btn btn-outline-primary btn-sm">
                                                        Buka KHS
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-success" onclick="return confirm('Finalisasi semua KHS yang dicentang sekarang?')">
                                <i class="fas fa-lock me-1"></i> Finalisasi KHS Terpilih
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts-custom')
    <script>
        $('#rowFilter').on('change', function() {
            const value = $(this).val();
            $('.row-preview-item').each(function() {
                const rowStatus = $(this).data('row-status');
                const visible = value === 'all' || rowStatus === value;
                $(this).toggle(visible);
            });
        });

        (function() {
            const searchInput = document.getElementById('previewKhsSearch');
            const list = document.getElementById('previewKhsList');
            const selectVisibleBtn = document.getElementById('selectVisiblePreviewKhsBtn');
            const clearVisibleBtn = document.getElementById('clearVisiblePreviewKhsBtn');

            if (!searchInput || !list) {
                return;
            }

            function getVisibleItems() {
                return Array.from(list.querySelectorAll('.khs-khs-item')).filter((item) => item.style.display !== 'none');
            }

            function filterItems() {
                const keyword = searchInput.value.trim().toLowerCase();
                list.querySelectorAll('.khs-khs-item').forEach((item) => {
                    const haystack = item.dataset.search || '';
                    item.style.display = keyword === '' || haystack.includes(keyword) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterItems);

            selectVisibleBtn?.addEventListener('click', function() {
                getVisibleItems().forEach((item) => {
                    const checkbox = item.querySelector('.preview-khs-finalize-checkbox:not(:disabled)');
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });

            clearVisibleBtn?.addEventListener('click', function() {
                getVisibleItems().forEach((item) => {
                    const checkbox = item.querySelector('.preview-khs-finalize-checkbox:not(:disabled)');
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                });
            });
        })();
    </script>
@endpush
