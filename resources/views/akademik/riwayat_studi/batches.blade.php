@extends('layouts.index')
@section('title', 'Histori Batch Riwayat Studi Historis')

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
@endphp

@push('styles-custom')
    <style>
        .historical-page-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .historical-page-card .card-header,
        .historical-page-card .card-body {
            padding: 1.35rem 1.5rem;
        }

        .historical-batch-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            vertical-align: middle;
        }

        .historical-batch-table td {
            vertical-align: middle;
        }

        .historical-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.28rem 0.72rem;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .historical-status-pill.success {
            background: #dcfce7;
            color: #166534;
        }

        .historical-status-pill.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .historical-status-pill.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .historical-status-pill.info {
            background: #e0f2fe;
            color: #075985;
        }

        .historical-action-title {
            font-weight: 700;
            color: #0f172a;
        }

        .historical-action-subtitle {
            font-size: 0.78rem;
            color: #64748b;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Histori Batch Riwayat Studi Historis</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.riwayat-studi.index') }}">Riwayat Studi Historis</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.riwayat-studi.batches') }}">Histori Batch</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card historical-page-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Batch</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped historical-batch-table" id="historicalBatchTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                                <th>Semester</th>
                                <th>Operator</th>
                                <th>Target</th>
                                <th>Executed</th>
                                <th>Skipped</th>
                                <th>Failed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batches as $batch)
                                @php
                                    $semester = $batch['semester'] ?? [];
                                    $tahun = $semester['tahun_akademik']['tahun_akademik'] ?? ($semester['tahunAkademik']['tahun_akademik'] ?? '');
                                    $summary = $batch['summary'] ?? [];
                                    $actionLabel = match ($batch['action_type'] ?? null) {
                                        'build_historical_krs' => 'Bentuk KRS Historis',
                                        'reopen_historical_krs' => 'Buka Ulang Riwayat',
                                        'refinalize_historical_krs' => 'Finalisasi Ulang',
                                        'reset_historical_krs' => 'Reset Isi Riwayat',
                                        'generate_khs' => 'Generate KHS',
                                        default => ucfirst(str_replace('_', ' ', $batch['action_type'] ?? '-')),
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $formatDateTime($batch['executed_at'] ?? $batch['created_at'] ?? null) }}</td>
                                    <td>
                                        <div class="historical-action-title">{{ $actionLabel }}</div>
                                        <div class="historical-action-subtitle">{{ $batch['notes'] ?? 'Tanpa catatan batch' }}</div>
                                    </td>
                                    <td>{{ ($semester['nama_semester'] ?? 'Semester') . ' ' . $tahun }}</td>
                                    <td>{{ $batch['creator']['name'] ?? '-' }}</td>
                                    <td><span class="historical-status-pill info"><i class="fas fa-bullseye"></i> {{ $summary['total'] ?? 0 }}</span></td>
                                    <td><span class="historical-status-pill success"><i class="fas fa-circle-check"></i> {{ $summary['executed'] ?? 0 }}</span></td>
                                    <td><span class="historical-status-pill warning"><i class="fas fa-forward"></i> {{ $summary['skipped'] ?? 0 }}</span></td>
                                    <td><span class="historical-status-pill danger"><i class="fas fa-triangle-exclamation"></i> {{ $summary['failed'] ?? 0 }}</span></td>
                                    <td>
                                        <a href="{{ route('akademik.riwayat-studi.batches.show', $batch['id']) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Belum ada histori batch.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(function() {
            $('#historicalBatchTable').DataTable({
                pageLength: 25
            });
        });
    </script>
@endpush
