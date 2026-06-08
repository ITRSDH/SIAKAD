@extends('layouts.index')
@section('title', 'Detail Proses Riwayat Studi Historis')

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
    $semester = $batch['semester'] ?? [];
    $tahun = $semester['tahun_akademik']['tahun_akademik'] ?? ($semester['tahunAkademik']['tahun_akademik'] ?? '');
    $summary = $batch['summary'] ?? [];
    $items = $batch['items'] ?? [];
    $filters = $batch['filters'] ?? [];
    $payload = $batch['payload'] ?? [];
    $buildPayloadItems = collect($payload['students_payload'] ?? [])->keyBy('id_mahasiswa');
    $actionLabel = match ($batch['action_type'] ?? null) {
        'build_historical_krs' => 'Bentuk KRS Historis',
        'reopen_historical_krs' => 'Buka Ulang Riwayat',
        'refinalize_historical_krs' => 'Finalisasi Ulang Riwayat',
        'reset_historical_krs' => 'Reset Isi Riwayat',
        'generate_khs' => 'Generate KHS Kolektif',
        default => ucfirst(str_replace('_', ' ', $batch['action_type'] ?? '-')),
    };
    $statusLabels = [
        'ready' => 'Siap',
        'executed' => 'Berhasil',
        'skipped' => 'Dilewati',
        'failed' => 'Gagal',
    ];
@endphp

@push('styles-custom')
    <style>
        .historical-detail-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .historical-detail-card .card-header,
        .historical-detail-card .card-body {
            padding: 1.35rem 1.5rem;
        }

        .historical-summary-box {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 1rem;
            height: 100%;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .historical-summary-box .label {
            font-size: 0.8rem;
            color: #64748b;
        }

        .historical-summary-box .value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.1;
            margin-top: 0.35rem;
        }

        .historical-meta-table th {
            width: 36%;
            color: #475569;
            font-weight: 700;
        }

        .historical-meta-table td,
        .historical-meta-table th {
            padding: 0.45rem 0;
            vertical-align: top;
        }

        .historical-payload-table thead th,
        .historical-result-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            vertical-align: middle;
        }

        .historical-result-table td,
        .historical-payload-table td {
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Proses Riwayat Studi Historis</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.riwayat-studi.batches') }}">Riwayat Proses</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.riwayat-studi.batches.show', $batch['id']) }}">Detail Proses</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card historical-detail-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Informasi Proses</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless historical-meta-table mb-0">
                            <tbody>
                                <tr>
                                    <th>Aksi</th>
                                    <td>{{ $actionLabel }}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>{{ ($semester['nama_semester'] ?? 'Semester') . ' ' . $tahun }}</td>
                                </tr>
                                <tr>
                                    <th>Operator</th>
                                    <td>{{ $batch['creator']['name'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Proses</th>
                                    <td>{{ $formatDateTime($batch['executed_at'] ?? null) }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $batch['notes'] ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card historical-detail-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Ringkasan Hasil</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="historical-summary-box">
                                    <div class="label">Target</div>
                                    <div class="value">{{ $summary['total'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="historical-summary-box">
                                    <div class="label">Berhasil</div>
                                    <div class="value text-success">{{ $summary['executed'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="historical-summary-box">
                                    <div class="label">Dilewati</div>
                                    <div class="value text-warning">{{ $summary['skipped'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="historical-summary-box">
                                    <div class="label">Gagal</div>
                                    <div class="value text-danger">{{ $summary['failed'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card historical-detail-card mt-4">
            <div class="card-header">
                <h4 class="card-title mb-0">Konteks Batch</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="historical-summary-box">
                            <div class="small text-muted">Semester Paket</div>
                            <div class="fw-semibold">{{ $payload['semester_ke'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="historical-summary-box">
                            <div class="small text-muted">Jumlah Mahasiswa Dipilih</div>
                            <div class="fw-semibold">{{ count($filters['selected_mahasiswa_ids'] ?? []) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="historical-summary-box">
                            <div class="small text-muted">Ada Payload Build</div>
                            <div class="fw-semibold">{{ !empty($payload['students_payload']) ? 'Ya' : 'Tidak' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="historical-summary-box">
                            <div class="small text-muted">Ringkasan Data</div>
                            <div class="fw-semibold">{{ ($summary['executed'] ?? 0) . ' berhasil / ' . ($summary['failed'] ?? 0) . ' gagal' }}</div>
                        </div>
                    </div>
                </div>

                @if (($batch['action_type'] ?? null) === 'generate_khs')
                    <div class="alert alert-info mt-3 mb-0">
                        Pada proses generate KHS historis, sistem mengambil <strong>catatan</strong> dari <strong>KRS Detail</strong>
                        yang sudah diisi otomatis oleh rule akademik, lalu memakainya sebagai <strong>keterangan</strong> pada tabel <strong>KHS</strong>.
                    </div>
                @endif
            </div>
        </div>

        @if ($buildPayloadItems->isNotEmpty())
            <div class="card historical-detail-card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">Payload Nilai Historis</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary">
                        Kolom <strong>Catatan</strong> di bawah ini adalah catatan yang dikirim saat build payload. Setelah nilai historis final diproses,
                        sistem juga dapat mengisi <strong>catatan otomatis</strong> pada <strong>KRS Detail</strong> berdasarkan rule akademik semester tersebut.
                    </div>
                    <div class="accordion" id="historicalBuildPayloadAccordion">
                        @foreach ($items as $index => $item)
                            @php
                                $studentPayload = $buildPayloadItems->get($item['id_mahasiswa'] ?? null);
                                $courses = collect($studentPayload['courses'] ?? []);
                            @endphp
                            @if ($studentPayload && $courses->isNotEmpty())
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="payloadHeading{{ $index }}">
                                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#payloadCollapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="payloadCollapse{{ $index }}">
                                            <span class="fw-semibold">{{ $item['nama_mahasiswa'] ?? ($item['mahasiswa']['nama_mahasiswa'] ?? 'Mahasiswa') }}</span>
                                            <span class="small text-muted ms-2">{{ $item['nim'] ?? ($item['mahasiswa']['nim'] ?? '-') }}</span>
                                        </button>
                                    </h2>
                                    <div id="payloadCollapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="payloadHeading{{ $index }}" data-bs-parent="#historicalBuildPayloadAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered align-middle mb-0 historical-payload-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Kelas Kuliah</th>
                                                            <th>Nilai Akhir</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($courses as $course)
                                                            <tr>
                                                                <td><code>{{ $course['id_kelas_kuliah'] ?? '-' }}</code></td>
                                                                <td>{{ $course['nilai_akhir'] ?? '-' }}</td>
                                                                <td>{{ $course['catatan'] ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card historical-detail-card mt-4">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Mahasiswa</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    Jika hasil studi sudah berhasil dibuat, buka tombol <strong>Lihat Hasil Studi</strong> untuk memeriksa KHS historis yang terbentuk dari KRS detail final, termasuk keterangan akademik yang dibawa dari catatan KRS detail.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped historical-result-table">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Ringkasan</th>
                                <th>ID KRS</th>
                                <th>ID KHS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                        <div class="small text-muted">{{ $item['mahasiswa']['nim'] ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusValue = $item['status'] ?? '-';
                                            $statusClass = match ($statusValue) {
                                                'executed' => 'success',
                                                'skipped' => 'warning',
                                                'failed' => 'danger',
                                                default => 'info',
                                            };
                                        @endphp
                                        <span class="historical-status-pill {{ $statusClass }}">
                                            @if ($statusValue === 'executed')
                                                <i class="fas fa-circle-check"></i>
                                            @elseif ($statusValue === 'skipped')
                                                <i class="fas fa-forward"></i>
                                            @elseif ($statusValue === 'failed')
                                                <i class="fas fa-triangle-exclamation"></i>
                                            @else
                                                <i class="fas fa-info-circle"></i>
                                            @endif
                                            {{ $statusLabels[$statusValue] ?? $statusValue }}
                                        </span>
                                    </td>
                                    <td>{{ $item['message'] ?? '-' }}</td>
                                    <td class="small text-muted">
                                        @php
                                            $meta = $item['meta'] ?? [];
                                        @endphp
                                        @if (!empty($meta['total_sks']) || !empty($meta['detail_count']) || !empty($meta['summary']))
                                            @if (!empty($meta['total_sks']))
                                                <div>Total SKS: {{ $meta['total_sks'] }}</div>
                                            @endif
                                            @if (!empty($meta['detail_count']))
                                                <div>Detail MK: {{ $meta['detail_count'] }}</div>
                                            @endif
                                            @if (!empty($meta['summary']))
                                                <div>IPS: {{ $meta['summary']['ips'] ?? '-' }}</div>
                                                <div>IPK: {{ $meta['summary']['ipk'] ?? '-' }}</div>
                                                <div>Keterangan KHS: {{ $meta['summary']['keterangan'] ?? '-' }}</div>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $item['id_krs'] ?? '-' }}</code></td>
                                    <td><code>{{ $item['id_khs'] ?? '-' }}</code></td>
                                    <td>
                                        @if (!empty($item['id_khs']))
                                            <a href="{{ route('akademik.khs.show', $item['id_khs']) }}" class="btn btn-outline-primary btn-sm">
                                                Lihat Hasil Studi
                                            </a>
                                        @elseif (!empty($item['id_krs']))
                                            <a href="{{ route('akademik.riwayat-studi.index') }}" class="btn btn-outline-secondary btn-sm">
                                                Lanjut Buat Hasil Studi
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum bisa dilanjutkan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data mahasiswa pada proses ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
