@extends('layouts.index')
@section('title', 'Riwayat Proses Administrasi Studi')

@php
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
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
        'partial' => 'Sebagian Berhasil',
        'failed' => 'Gagal Diproses',
        'rolled_back' => 'Sudah Dibatalkan',
    ];
    $normalizeRouteParam = function ($value): ?string {
        if (is_scalar($value) || $value instanceof \Stringable) {
            $value = trim((string) $value);

            return $value !== '' ? $value : null;
        }

        if (is_array($value)) {
            foreach (['id', 'value', 'uuid'] as $key) {
                if (isset($value[$key]) && (is_scalar($value[$key]) || $value[$key] instanceof \Stringable)) {
                    $candidate = trim((string) $value[$key]);

                    if ($candidate !== '') {
                        return $candidate;
                    }
                }
            }
        }

        return null;
    };
@endphp

@push('styles-custom')
    <style>
        .study-history-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 30%),
                linear-gradient(135deg, #0f3d3e 0%, #155e75 54%, #f59e0b 100%);
            color: #fff;
        }
        .study-history-hero .card-body {
            padding: 1.75rem;
        }
        .study-history-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            font-size: .82rem;
        }
        .study-history-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }
        .study-history-pill {
            border-radius: 18px;
            padding: 1rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .16);
        }
        .study-batch-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }
        .study-batch-card .card-header,
        .study-batch-card .card-body {
            padding: 1.35rem 1.5rem;
        }
        .study-batch-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            vertical-align: middle;
        }
        .study-batch-table td {
            vertical-align: middle;
        }
        .study-history-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
            margin-bottom: 1.25rem;
        }
        .study-history-stat {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            padding: 1rem;
        }
        .study-history-stat .label {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            margin-bottom: .4rem;
        }
        .study-history-stat .value {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
            color: #0f172a;
        }
        .study-batch-table td:first-child {
            min-width: 124px;
        }
        .study-batch-table td:nth-child(4) {
            min-width: 260px;
        }
        .study-batch-table td:nth-child(6) {
            min-width: 150px;
        }
        @media (max-width: 991.98px) {
            .study-history-grid,
            .study-history-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Riwayat Proses Administrasi Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi Mahasiswa</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.batches') }}">Riwayat Proses</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card study-history-hero mb-4">
            <div class="card-body">
                <div class="study-history-kicker">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>Riwayat hanya bila diperlukan</span>
                </div>
                <div class="row g-4 align-items-end mt-1">
                    <div class="col-xl-8">
                        <h2 class="fw-bold mb-2">Lihat proses lama tanpa tenggelam di detail teknis.</h2>
                        <p class="mb-0 text-white-50">
                            Halaman ini saya rapikan agar operator bisa cepat membedakan proses yang selesai,
                            masih perlu dicek, atau gagal, lalu langsung membuka ringkasan yang relevan.
                        </p>
                    </div>
                    <div class="col-xl-4">
                        <div class="study-history-grid">
                            <div class="study-history-pill">
                                <div class="small text-white-50 mb-1">1</div>
                                <div class="fw-semibold">Lihat tanggal proses</div>
                            </div>
                            <div class="study-history-pill">
                                <div class="small text-white-50 mb-1">2</div>
                                <div class="fw-semibold">Baca status hasil</div>
                            </div>
                            <div class="study-history-pill">
                                <div class="small text-white-50 mb-1">3</div>
                                <div class="fw-semibold">Buka ringkasannya</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card study-batch-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-1">Riwayat proses yang pernah dijalankan</h4>
                    <p class="text-muted mb-0">Halaman ini hanya dipakai saat Anda perlu melacak proses lama. Untuk kerja harian, gunakan halaman utama administrasi studi.</p>
                </div>
                <a href="{{ route('akademik.administrasi-studi.index', ['tab' => 'batch']) }}" class="btn btn-outline-secondary btn-sm">
                    Kembali ke Halaman Utama
                </a>
            </div>
            <div class="card-body">
                @php
                    $totalBatches = count($batches ?? []);
                    $successfulBatches = collect($batches ?? [])->filter(fn ($item) => ($item['status'] ?? '') === 'processed')->count();
                    $attentionBatches = collect($batches ?? [])->filter(fn ($item) => in_array(($item['status'] ?? ''), ['failed', 'partial'], true))->count();
                @endphp
                <div class="study-history-summary">
                    <div class="study-history-stat">
                        <div class="label">Total Proses</div>
                        <div class="value">{{ $totalBatches }}</div>
                    </div>
                    <div class="study-history-stat">
                        <div class="label">Selesai</div>
                        <div class="value text-success">{{ $successfulBatches }}</div>
                    </div>
                    <div class="study-history-stat">
                        <div class="label">Perlu Perhatian</div>
                        <div class="value text-danger">{{ $attentionBatches }}</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped study-batch-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Proses</th>
                                <th>Status</th>
                                <th>Ringkasan</th>
                                <th>Operator</th>
                                <th>Hasil</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batches as $batch)
                                @php
                                    $batchSource = $normalizeRouteParam($batch['source'] ?? null);
                                    $batchId = $normalizeRouteParam($batch['id'] ?? null);
                                @endphp
                                <tr>
                                    <td>{{ $formatDateTime($batch['executed_at'] ?? null) }}</td>
                                    <td>
                                        <span class="badge {{ ($batch['source'] ?? '') === 'historical' ? 'bg-primary' : 'bg-success' }}">
                                            {{ ($batch['source'] ?? '') === 'historical' ? 'Historis' : 'Import Nilai' }}
                                        </span>
                                    </td>
                                    <td>
                                        @include('layouts.partials.status-badge', ['value' => $batch['status'] ?? 'uploaded', 'label' => $statusLabels[$batch['status'] ?? 'uploaded'] ?? ucfirst(str_replace('_', ' ', (string) ($batch['status'] ?? 'uploaded')))])
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $batch['title'] ?? '-' }}</div>
                                        <div class="small text-muted">{{ $batch['subtitle'] ?? '-' }}</div>
                                    </td>
                                    <td>{{ $batch['operator_name'] ?? '-' }}</td>
                                    <td>
                                        <div class="small">Total {{ $batch['summary']['total'] ?? 0 }}</div>
                                        <div class="small text-success">Berhasil {{ $batch['summary']['executed'] ?? 0 }}</div>
                                        @if (($batch['summary']['failed'] ?? 0) > 0)
                                            <div class="small text-danger">Gagal {{ $batch['summary']['failed'] ?? 0 }}</div>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if ($batchSource && $batchId)
                                            <a href="{{ route('akademik.administrasi-studi.batches.show', ['source' => $batchSource, 'id' => $batchId]) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                Lihat Ringkasan
                                            </a>
                                        @else
                                            <span class="text-muted small">ID proses tidak valid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat proses yang bisa ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
