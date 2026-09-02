@extends('layouts.index')
@section('title', 'Cek Hasil Import Nilai')

@php
    $summary = $preview['summary'] ?? [];
    $rows = $preview['rows'] ?? [];
    $batchStatus = (string) ($batch['status'] ?? 'uploaded');
    $semester = $batch['semester'] ?? [];
    $tahun = $semester['tahun_akademik']['tahun_akademik'] ?? ($semester['tahunAkademik']['tahun_akademik'] ?? '');
    $semesterLabel = trim((string) (($semester['nama_semester'] ?? '-') . ' ' . $tahun));
    $canProcess =
        ($summary['total_valid'] ?? 0) > 0 && in_array($batchStatus, ['uploaded', 'previewed', 'failed'], true);
    $processedKhsItems = collect($batch['summary']['processed_khs_items'] ?? [])
        ->values()
        ->all();
    $processedKhsFinalCount = collect($processedKhsItems)->where('is_final', true)->count();
    $processedKhsDraftCount = max(count($processedKhsItems) - $processedKhsFinalCount, 0);
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
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
    $batchId = $normalizeRouteParam($batch['id'] ?? null);
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
        .study-import-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
                linear-gradient(135deg, #0f766e 0%, #1d4ed8 56%, #f59e0b 100%);
            color: #fff;
        }

        .study-import-hero .card-body {
            padding: 1.75rem;
        }

        .study-import-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .study-import-card .card-header,
        .study-import-card .card-body {
            padding: 1.35rem 1.5rem;
        }

        .study-import-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .9rem;
        }

        .study-import-stat {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }

        .study-import-stat .label {
            font-size: .78rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .35rem;
        }

        .study-import-stat .value {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
        }

        .study-import-row {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }

        .study-import-row.error {
            border-color: #fecaca;
            background: #fef2f2;
        }

        .study-import-row.warning {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .study-import-row.valid {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }

        .study-import-table th,
        .study-import-table td {
            vertical-align: middle;
        }

        .study-import-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .study-import-table .status-badge {
            min-width: 92px;
            display: inline-block;
            text-align: center;
        }

        .study-step-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }

        .study-step-item {
            border-radius: 18px;
            padding: 1rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .16);
        }

        @media (max-width: 991.98px) {
            .study-import-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .study-step-strip {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .study-import-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Cek Hasil Import Nilai</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a
                        href="{{ route('akademik.administrasi-studi.nilai') }}">Input
                        Nilai</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a
                        href="{{ $batchId ? route('akademik.administrasi-studi.import.preview', $batchId) : '#' }}">Hasil
                        Pengecekan</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card study-import-hero mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-end">
                    <div class="col-xl-8">
                        <h2 class="fw-bold mb-2">{{ $batch['file_name'] ?? 'Import Nilai' }}</h2>
                        <p class="mb-0 text-white-50">
                            Halaman ini saya rapikan agar operator bisa membaca hasil pengecekan dengan urutan yang jelas:
                            lihat ringkasan, tentukan langkah berikutnya, lalu cek baris yang bermasalah.
                        </p>
                    </div>
                    <div class="col-xl-4 text-xl-end">
                        @include('layouts.partials.status-badge', [
                            'value' => $batchStatus,
                            'label' => $statusLabels[$batchStatus] ?? ucfirst(str_replace('_', ' ', $batchStatus)),
                        ])
                    </div>
                </div>
                <div class="study-step-strip">
                    <div class="study-step-item">
                        <div class="small text-white-50 mb-1">1</div>
                        <div class="fw-semibold">Baca ringkasan hasil</div>
                    </div>
                    <div class="study-step-item">
                        <div class="small text-white-50 mb-1">2</div>
                        <div class="fw-semibold">Pilih proses atau finalisasi</div>
                    </div>
                    <div class="study-step-item">
                        <div class="small text-white-50 mb-1">3</div>
                        <div class="fw-semibold">Cek baris yang warning atau error</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card study-import-card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h4 class="mb-1">Ringkasan hasil pengecekan</h4>
                    <div class="text-muted small">{{ $semesterLabel ?: '-' }}</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('akademik.administrasi-studi.nilai') }}"
                        class="btn btn-outline-secondary btn-sm">Kembali ke Input Nilai</a>
                </div>
            </div>
            <div class="card-body">
                <div class="study-import-grid">
                    <div class="study-import-stat">
                        <div class="label">Total Baris</div>
                        <div class="value">{{ $summary['total_rows'] ?? 0 }}</div>
                    </div>
                    <div class="study-import-stat">
                        <div class="label">Valid</div>
                        <div class="value text-success">{{ $summary['total_valid'] ?? 0 }}</div>
                    </div>
                    <div class="study-import-stat">
                        <div class="label">Error</div>
                        <div class="value text-danger">{{ $summary['total_error'] ?? 0 }}</div>
                    </div>
                    <div class="study-import-stat">
                        <div class="label">Warning</div>
                        <div class="value text-warning">{{ $summary['total_warning'] ?? 0 }}</div>
                    </div>
                    <div class="study-import-stat">
                        <div class="label">MK Dilewati (tidak diambil)</div>
                        <div class="value text-muted">{{ $summary['total_mk_skipped'] ?? 0 }}</div>
                    </div>
                </div>

                @if (!empty($summary['total_error']) || !empty($summary['total_warning']))
                    <div class="alert alert-light border mt-3 mb-0">
                        Catatan rinci error dan warning sudah ditampilkan langsung di daftar baris preview di bawah, jadi
                        operator tidak perlu membuka halaman teknis lain.
                    </div>
                @endif

                <div class="row g-3 mt-1">
                    <div class="col-lg-7">
                        <div class="study-import-row">
                            <div class="fw-semibold mb-2">Langkah berikutnya</div>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($canProcess && $batchId)
                                    <form method="POST"
                                        action="{{ route('akademik.administrasi-studi.import.process', $batchId) }}"
                                        class="study-confirm-form" data-confirm-title="Proses import sekarang?"
                                        data-confirm-text="Simpan nilai dari file ini sekarang dan lanjutkan membuat KHS?"
                                        data-confirm-icon="question" data-confirm-button="Ya, proses">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-play me-1"></i> Proses Import
                                        </button>
                                    </form>
                                @endif

                                @if ($batchStatus === 'processed' && $batchId)
                                    <a href="{{ route('akademik.khs.import.preview', ['batch' => $batchId, 'legacy' => 1]) }}"
                                        class="btn btn-success">
                                        <i class="fas fa-list-check me-1"></i> Pilih Finalisasi KHS
                                    </a>

                                    <form method="POST"
                                        action="{{ route('akademik.administrasi-studi.import.rollback', $batchId) }}"
                                        class="study-confirm-form" data-confirm-title="Rollback hasil proses?"
                                        data-confirm-text="Batalkan hasil proses ini sekarang?" data-confirm-icon="warning"
                                        data-confirm-button="Ya, rollback">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-rotate-left me-1"></i> Rollback
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="small text-muted mt-3">
                                Halaman ini dipakai untuk memastikan hasil prosesnya sudah benar. Jika KHS sudah terbentuk,
                                gunakan tombol finalisasi untuk membuka halaman pilihan finalisasi per mahasiswa.
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="study-import-row">
                            <div class="fw-semibold mb-2">Ringkasan hasil proses</div>
                            <div class="small mb-1">Total KHS hasil proses:
                                <strong>{{ count($processedKhsItems) }}</strong></div>
                            <div class="small mb-1">Belum final: <strong>{{ $processedKhsDraftCount }}</strong></div>
                            <div class="small mb-1">Sudah final: <strong>{{ $processedKhsFinalCount }}</strong></div>
                            <div class="small text-muted mt-2">Update terakhir:
                                {{ $formatDateTime($batch['processed_at'] ?? ($batch['created_at'] ?? null)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card study-import-card mb-4">
            <div class="card-header">
                <h4 class="card-title mb-1">Referensi Mata Kuliah</h4>
                <p class="text-muted mb-0">Daftar mata kuliah yang berhasil dibaca dari file nilai.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped study-import-table" id="subjectReferenceTable">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th style="width: 180px;">Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subject['kode_mk'] ?? '-' }}</td>
                                    <td>{{ $subject['nama_mk'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada mata kuliah yang terbaca
                                        dari file ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card study-import-card">
            <div class="card-header">
                <h4 class="card-title mb-1">Baris Preview</h4>
                <p class="text-muted mb-0">Ringkasan setiap baris data setelah file diperiksa oleh sistem.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped study-import-table" id="rowPreviewTable">
                        <thead>
                            <tr>
                                <th style="width: 90px;">Baris</th>
                                <th style="width: 130px;">Status</th>
                                <th style="width: 160px;">NIM</th>
                                <th>Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th>Pesan</th>
                                <th>Error</th>
                                <th>Warning</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                @php
                                    $rowStatus = !empty($row['errors'])
                                        ? 'error'
                                        : (!empty($row['warnings'])
                                            ? 'warning'
                                            : ((($row['is_valid'] ?? false)) ? 'valid' : 'error'));
                                    $rowClass = ['valid' => 'valid', 'error' => 'error', 'warning' => 'warning'][$rowStatus] ?? 'warning';
                                    $rowStatusLabel = ['valid' => 'Valid', 'error' => 'Error', 'warning' => 'Warning'][$rowStatus] ?? 'Warning';
                                    $subjectLabels = collect($row['subjects'] ?? [])
                                        ->map(function ($subject) {
                                            return trim(
                                                (string) (($subject['kode_mk'] ?? '-') .
                                                    ' - ' .
                                                    ($subject['nama_mk'] ?? '')),
                                            );
                                        })
                                        ->filter()
                                        ->values()
                                        ->all();
                                    $skippedCount = collect($row['subjects'] ?? [])->where('skipped', true)->count();
                                @endphp
                                <tr>
                                    <td>{{ $row['row_number'] ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge status-badge {{ $rowClass === 'valid' ? 'bg-success' : ($rowClass === 'error' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ $rowStatusLabel }}
                                        </span>
                                    </td>
                                    <td>{{ $row['nim'] ?? '-' }}</td>
                                    <td>{{ $row['nama'] ?? 'Mahasiswa' }}</td>
                                    <td>
                                        <div>{{ !empty($subjectLabels) ? implode(', ', $subjectLabels) : '-' }}</div>
                                        @if ($skippedCount > 0)
                                            <div class="small text-muted mt-1">{{ $skippedCount }} MK tidak diambil (dilewati)</div>
                                        @endif
                                    </td>
                                    <td>{{ $row['message'] ?? '-' }}</td>
                                    <td class="text-danger">
                                        {{ !empty($row['errors']) ? collect($row['errors'])->pluck('message')->implode(' | ') : '-' }}
                                    </td>
                                    <td class="text-warning">
                                        {{ !empty($row['warnings']) ? collect($row['warnings'])->pluck('message')->implode(' | ') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data preview yang bisa
                                        ditampilkan.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.study-confirm-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: form.dataset.confirmTitle || 'Lanjutkan proses?',
                        text: form.dataset.confirmText ||
                            'Pastikan tindakan ini sudah sesuai.',
                        icon: form.dataset.confirmIcon || 'question',
                        showCancelButton: true,
                        confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            if ($.fn.DataTable) {
                $('#subjectReferenceTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    ordering: true,
                    order: [
                        [1, 'asc']
                    ],
                    language: {
                        url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                    }
                });

                $('#rowPreviewTable').DataTable({
                    pageLength: 25,
                    lengthMenu: [10, 25, 50, 100],
                    ordering: true,
                    order: [
                        [0, 'asc']
                    ],
                    language: {
                        url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                    }
                });
            }
        });
    </script>
@endpush
