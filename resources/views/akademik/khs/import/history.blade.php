@extends('layouts.index')
@section('title', 'Riwayat Import KHS')

@php
    $historyCollection = collect($historyItems ?? []);
    $processedCount = $historyCollection->filter(fn($item) => ($item['status'] ?? '') === 'processed')->count();
    $failedCount = $historyCollection->filter(fn($item) => ($item['status'] ?? '') === 'failed')->count();
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
            return \Illuminate\Support\Carbon::parse($value)
                ->locale('id')
                ->translatedFormat('d M Y, H:i');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    };
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
                linear-gradient(135deg, #1d4ed8 0%, #0f766e 52%, #0f172a 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-hero-copy {
            color: rgba(255, 255, 255, 0.82);
            max-width: 58ch;
        }

        .khs-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .khs-stat-card {
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 1rem;
        }

        .khs-stat-card .value {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
        }

        .khs-stat-card .label {
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.84rem;
        }

        .khs-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
        }

        .khs-table-wrap table {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .khs-stat-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Riwayat Import KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.history') }}">Riwayat Import KHS</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-4">
                    <div>
                        <span class="khs-chip">
                            <i class="fas fa-folder-tree"></i>
                            Arsip proses import
                        </span>
                        <h2 class="fw-bold mt-3 mb-2">Lihat riwayat proses import dengan lebih cepat.</h2>
                        <p class="khs-hero-copy mb-0">
                            Halaman ini membantu melihat proses mana yang sudah menyimpan nilai ke KRS dan membentuk KHS, mana yang masih perlu ditinjau, dan mana yang pernah gagal.
                        </p>
                    </div>
                    <a href="{{ route('akademik.khs.import.index') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i> Mulai Import Baru
                    </a>
                </div>

                <div class="khs-stat-grid mt-4">
                    <div class="khs-stat-card">
                        <div class="value">{{ $historyCollection->count() }}</div>
                        <div class="label">Total proses tersimpan</div>
                    </div>
                    <div class="khs-stat-card">
                        <div class="value">{{ $processedCount }}</div>
                        <div class="label">Proses selesai</div>
                    </div>
                    <div class="khs-stat-card">
                        <div class="value">{{ $failedCount + $warningCount }}</div>
                        <div class="label">Proses yang perlu perhatian</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card khs-shell-card">
            <div class="khs-shell-header d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h4 class="fw-bold mb-1">Daftar proses import</h4>
                    <p class="text-muted mb-0">Setiap baris di bawah ini memudahkan Anda membuka detail, preview, hasil, atau rollback bila diperlukan.</p>
                </div>
            </div>
            <div class="khs-shell-body">
                <div class="khs-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="khsImportHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>File</th>
                                    <th>Semester</th>
                                    <th>Uploader</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Siap</th>
                                    <th>Perlu Perbaikan</th>
                                    <th>Perlu Dicek</th>
                                    <th>Tanggal Upload</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($historyItems as $item)
                                    @php
                                        $status = (string) ($item['status'] ?? 'uploaded');
                                        $summary = $item['summary'] ?? [];
                                        $semester = $item['semester'] ?? [];
                                        $tahunAkademik = $semester['tahun_akademik'] ?? ($semester['tahunAkademik'] ?? []);
                                        $semesterLabel = trim((string) (($semester['nama_semester'] ?? '-') . ' ' . ($tahunAkademik['tahun_akademik'] ?? '')));
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item['file_name'] ?? '-' }}</div>
                                            <small class="text-muted">{{ $item['id'] ?? '-' }}</small>
                                        </td>
                                        <td>{{ $semesterLabel ?: '-' }}</td>
                                        <td>{{ $item['uploader']['name'] ?? '-' }}</td>
                                        <td>@include('layouts.partials.status-badge', ['value' => $status, 'label' => $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status))])</td>
                                        <td>{{ $item['total_rows'] ?? 0 }}</td>
                                        <td>{{ $item['total_success'] ?? 0 }}</td>
                                        <td>{{ $item['total_failed'] ?? 0 }}</td>
                                        <td>{{ $summary['total_warning'] ?? 0 }}</td>
                                        <td>{{ $formatDateTime($item['created_at'] ?? null) }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                <a href="{{ route('akademik.khs.import.show', ['batch' => $item['id'], 'legacy' => 1]) }}" class="btn btn-sm btn-outline-secondary">Buka Hasil</a>
                                                @if (in_array($status, ['uploaded', 'previewed', 'failed'], true))
                                                    <a href="{{ route('akademik.khs.import.preview', ['batch' => $item['id'], 'legacy' => 1]) }}" class="btn btn-sm btn-outline-primary">Lanjutkan Pengecekan</a>
                                                @elseif ($status === 'processed')
                                                    <a href="{{ route('akademik.khs.import.preview', ['batch' => $item['id'], 'legacy' => 1]) }}" class="btn btn-sm btn-success">Pilih Finalisasi KHS</a>
                                                @endif
                                                <a href="{{ route('akademik.khs.import.export-errors', $item['id']) }}" class="btn btn-sm btn-outline-warning">Catatan</a>
                                                <a href="{{ route('akademik.khs.import.export-results', $item['id']) }}" class="btn btn-sm btn-outline-info">Hasil</a>
                                                @if ($status === 'processed')
                                                    <form method="POST" action="{{ route('akademik.khs.import.rollback', $item['id']) }}" onsubmit="return confirm('Batalkan hasil proses ini sekarang?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Rollback</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Belum ada riwayat import KHS.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('#khsImportHistoryTable').DataTable({
                    pageLength: 10,
                    order: [
                        [8, 'desc']
                    ],
                    language: {
                        url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                    },
                });
            }
        });
    </script>
@endpush
