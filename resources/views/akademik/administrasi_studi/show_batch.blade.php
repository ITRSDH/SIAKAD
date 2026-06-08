@extends('layouts.index')
@section('title', 'Detail Proses Administrasi Studi')

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
    $semesterLabel = trim((string) (($semester['nama_semester'] ?? '-') . ' ' . $tahun));
    $status = (string) ($batch['status'] ?? 'uploaded');
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
        'partial' => 'Sebagian Berhasil',
        'failed' => 'Gagal Diproses',
        'rolled_back' => 'Sudah Dibatalkan',
    ];
    $canProcessImport = ($batch['source'] ?? '') === 'import'
        && in_array($status, ['uploaded', 'previewed', 'failed'], true)
        && (($batch['summary']['executed'] ?? 0) > 0 || !empty($batch['errors']));
    $canFinalizeImport = ($batch['source'] ?? '') === 'import' && $status === 'processed';
    $canRollbackImport = ($batch['source'] ?? '') === 'import' && $status === 'processed';
    $historicalFilters = $batch['filters'] ?? [];
    $historicalItems = collect($batch['items'] ?? []);
@endphp

@push('styles-custom')
    <style>
        .study-detail-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.18), transparent 28%),
                linear-gradient(135deg, #1e3a8a 0%, #0f766e 56%, #f59e0b 100%);
            color: #fff;
        }
        .study-detail-hero .card-body {
            padding: 1.75rem;
        }
        .study-detail-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            font-size: .82rem;
        }
        .study-detail-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }
        .study-detail-card .card-header,
        .study-detail-card .card-body {
            padding: 1.35rem 1.5rem;
        }
        .study-meta-table th {
            width: 36%;
            color: #475569;
            font-weight: 700;
        }
        .study-meta-table td,
        .study-meta-table th {
            padding: 0.45rem 0;
            vertical-align: top;
        }
        .study-status-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
        }
        .study-status-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }
        .study-status-card .label {
            font-size: .78rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .35rem;
        }
        .study-status-card .value {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1;
        }
        .study-ops-box {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            padding: 1rem;
        }
        .study-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .65rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            background: #eef4ff;
            color: #1d4ed8;
        }
        .study-batch-item {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }
        .study-batch-item.ready,
        .study-batch-item.executed {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }
        .study-batch-item.skipped {
            border-color: #fde68a;
            background: #fffbeb;
        }
        .study-batch-item.failed {
            border-color: #fecaca;
            background: #fef2f2;
        }
        .study-course-table th,
        .study-course-table td {
            vertical-align: middle;
        }
        .study-meta-summary {
            display: grid;
            gap: .85rem;
        }
        .study-meta-chip {
            border: 1px solid #dbe4f0;
            border-radius: 16px;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            padding: .9rem 1rem;
        }
        .study-meta-chip .label {
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            margin-bottom: .25rem;
        }
        .study-section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: .9rem;
        }
        @media (max-width: 991.98px) {
            .study-status-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 575.98px) {
            .study-status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Proses Administrasi Studi</h3>
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

        <div class="card study-detail-hero mb-4">
            <div class="card-body">
                <div class="study-detail-kicker">
                    <i class="fas fa-file-lines"></i>
                    <span>Ringkasan proses yang mudah dibaca</span>
                </div>
                <div class="row g-4 align-items-end mt-1">
                    <div class="col-xl-8">
                        <h2 class="fw-bold mb-2">{{ $batch['title'] ?? 'Detail proses administrasi studi' }}</h2>
                        <p class="mb-0 text-white-50">
                            Fokus utama halaman ini adalah membantu operator melihat apakah proses sudah aman dilanjutkan,
                            perlu dicek lagi, atau cukup dibuka ringkasannya saja.
                        </p>
                    </div>
                    <div class="col-xl-4 text-xl-end">
                        <div class="small text-white-50 mb-2">Status saat ini</div>
                        @include('layouts.partials.status-badge', ['value' => $status, 'label' => $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status))])
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card study-detail-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Informasi Proses</h4>
                    </div>
                    <div class="card-body">
                        <div class="study-meta-summary mb-4">
                            <div class="study-meta-chip">
                                <div class="label">Sumber</div>
                                <div class="fw-semibold">{{ ($batch['source'] ?? '') === 'historical' ? 'Riwayat Studi Historis' : 'Import Nilai KHS' }}</div>
                            </div>
                            <div class="study-meta-chip">
                                <div class="label">Semester</div>
                                <div class="fw-semibold">{{ $semesterLabel ?: '-' }}</div>
                            </div>
                            <div class="study-meta-chip">
                                <div class="label">Operator</div>
                                <div class="fw-semibold">{{ $batch['operator_name'] ?? '-' }}</div>
                            </div>
                        </div>
                        <table class="table table-borderless study-meta-table mb-0">
                            <tbody>
                                <tr>
                                    <th>Judul</th>
                                    <td>{{ $batch['title'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $formatDateTime($batch['executed_at'] ?? null) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @include('layouts.partials.status-badge', ['value' => $status, 'label' => $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status))])
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hasil</th>
                                    <td>
                                        Total {{ $batch['summary']['total'] ?? 0 }},
                                        berhasil {{ $batch['summary']['executed'] ?? 0 }},
                                        catatan {{ $batch['summary']['skipped'] ?? 0 }},
                                        gagal {{ $batch['summary']['failed'] ?? 0 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card study-detail-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Ringkasan Hasil</h4>
                            <p class="text-muted mb-0">Bagian ini dipakai untuk melihat hasil proses dan menentukan langkah berikutnya, tanpa harus membaca detail teknis yang tidak penting.</p>
                        </div>
                        <a href="{{ route('akademik.administrasi-studi.index') }}" class="btn btn-outline-secondary btn-sm">
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                    <div class="card-body">
                        @if (($batch['source'] ?? '') === 'historical')
                            <div class="study-status-grid mb-4">
                                <div class="study-status-card">
                                    <div class="label">Total</div>
                                    <div class="value">{{ $batch['summary']['total'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Berhasil</div>
                                    <div class="value text-success">{{ $batch['summary']['executed'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Catatan</div>
                                    <div class="value text-warning">{{ $batch['summary']['skipped'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Gagal</div>
                                    <div class="value text-danger">{{ $batch['summary']['failed'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="study-ops-box mb-4">
                                <div class="fw-semibold mb-2">Konteks proses historis</div>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @if (!empty($historicalFilters['id_semester']))
                                        <span class="study-chip">Semester Sudah Dipilih</span>
                                    @endif
                                    @if (!empty($historicalFilters['id_prodi']))
                                        <span class="study-chip">Program Studi Spesifik</span>
                                    @endif
                                    @if (!empty($historicalFilters['angkatan']))
                                        <span class="study-chip">Angkatan {{ $historicalFilters['angkatan'] }}</span>
                                    @endif
                                    @if (!empty($historicalFilters['semester_ke']))
                                        <span class="study-chip">Semester Ke {{ $historicalFilters['semester_ke'] }}</span>
                                    @endif
                                    @if (empty($historicalFilters))
                                        <span class="text-muted small">Tidak ada filter tambahan yang tercatat pada proses ini.</span>
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    {{ !empty($batch['notes']) ? $batch['notes'] : 'Tidak ada catatan tambahan untuk proses ini.' }}
                                </div>
                            </div>

                            <div class="study-section-title">
                                <div>
                                    <div class="fw-semibold">Hasil per mahasiswa</div>
                                    <div class="small text-muted">Baris berikut sudah disederhanakan agar operator cukup melihat status, pesan, dan mata kuliah yang terlibat.</div>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                @forelse ($historicalItems as $item)
                                    @php
                                        $meta = $item['meta'] ?? [];
                                        $courses = collect($meta['courses'] ?? []);
                                        $statusApproval = $meta['status_approval'] ?? null;
                                        $lockedLabel = array_key_exists('is_locked', $meta) ? ($meta['is_locked'] ? 'Locked' : 'Unlocked') : null;
                                    @endphp
                                    <div class="study-batch-item {{ $item['status'] ?? '' }}">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <div class="small text-muted">{{ $item['mahasiswa']['nim'] ?? '-' }}</div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge {{ ($item['status'] ?? '') === 'executed' || ($item['status'] ?? '') === 'ready' ? 'bg-success' : (($item['status'] ?? '') === 'skipped' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ ucfirst($item['status'] ?? '-') }}
                                                </span>
                                                @if ($statusApproval)
                                                    <span class="badge bg-light text-dark">{{ strtoupper($statusApproval) }}</span>
                                                @endif
                                                @if ($lockedLabel)
                                                    <span class="badge bg-light text-dark">{{ $lockedLabel }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="small mt-2">{{ $item['message'] ?? '-' }}</div>

                                        @if (isset($meta['total_sks']) || !empty($meta['semester_ke']))
                                            <div class="row g-2 mt-2">
                                                @if (isset($meta['total_sks']))
                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Total SKS</div>
                                                        <div class="small fw-semibold">{{ $meta['total_sks'] }}</div>
                                                    </div>
                                                @endif
                                                @if (!empty($meta['semester_ke']))
                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Semester Ke</div>
                                                        <div class="small fw-semibold">{{ $meta['semester_ke'] }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($courses->isNotEmpty())
                                            <div class="table-responsive mt-3">
                                                <table class="table table-sm table-bordered study-course-table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode MK</th>
                                                            <th>Mata Kuliah</th>
                                                            <th>Kelas</th>
                                                            <th>SKS</th>
                                                            <th>Nilai</th>
                                                            <th>Huruf</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($courses as $course)
                                                            <tr>
                                                                <td>{{ $course['kode_mk'] ?? '-' }}</td>
                                                                <td>{{ $course['nama_mk'] ?? '-' }}</td>
                                                                <td>{{ $course['nama_kelas'] ?? '-' }}</td>
                                                                <td>{{ $course['sks'] ?? 0 }}</td>
                                                                <td>{{ $course['nilai_akhir'] ?? '-' }}</td>
                                                                <td>{{ $course['nilai_huruf'] ?? '-' }}</td>
                                                                <td>{{ ucfirst(str_replace('_', ' ', (string) ($course['status'] ?? '-'))) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">Tidak ada hasil historis yang perlu ditampilkan.</div>
                                @endforelse
                            </div>
                        @else
                            <div class="study-status-grid mb-4">
                                <div class="study-status-card">
                                    <div class="label">Total Baris</div>
                                    <div class="value">{{ $batch['summary']['total'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Siap / Berhasil</div>
                                    <div class="value text-success">{{ $batch['summary']['executed'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Warning</div>
                                    <div class="value text-warning">{{ $batch['summary']['skipped'] ?? 0 }}</div>
                                </div>
                                <div class="study-status-card">
                                    <div class="label">Error</div>
                                    <div class="value text-danger">{{ $batch['summary']['failed'] ?? 0 }}</div>
                                </div>
                            </div>

                            <div class="study-ops-box mb-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                    <div>
                                <div class="fw-semibold mb-1">Aksi Proses Import</div>
                                <div class="small text-muted">
                                            Buka halaman hasil pengecekan untuk melihat rincian yang benar-benar perlu ditindaklanjuti. Dari sana operator bisa melanjutkan proses import tanpa harus berpindah ke halaman teknis lain.
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('akademik.administrasi-studi.import.preview', $batch['id']) }}"
                                            class="btn btn-primary btn-sm">
                                            Buka Hasil Pengecekan
                                        </a>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @if ($canProcessImport)
                                        <form method="POST" action="{{ route('akademik.administrasi-studi.import.process', $batch['id']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Simpan nilai dari hasil pengecekan ini sekarang?')">
                                                <i class="fas fa-play me-1"></i> Proses Import
                                            </button>
                                        </form>
                                    @endif
                                    @if ($canFinalizeImport)
                                        <a href="{{ route('akademik.khs.import.preview', ['batch' => $batch['id'], 'legacy' => 1]) }}"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-list-check me-1"></i> Pilih Finalisasi KHS
                                        </a>
                                    @endif
                                    @if ($canRollbackImport)
                                        <form method="POST" action="{{ route('akademik.administrasi-studi.import.rollback', $batch['id']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Batalkan hasil proses ini sekarang?')">
                                                <i class="fas fa-rotate-left me-1"></i> Rollback
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="study-section-title">
                                    <div>
                                        <div class="fw-semibold">Catatan yang perlu dicek</div>
                                        <div class="small text-muted">Fokus ke baris yang benar-benar bermasalah atau butuh tindak lanjut.</div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Row</th>
                                                <th>NIM</th>
                                                <th>Kode MK</th>
                                                <th>Tipe</th>
                                                <th>Pesan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse (($batch['errors'] ?? []) as $error)
                                                <tr>
                                                    <td>{{ $error['row_number'] ?? '-' }}</td>
                                                    <td>{{ $error['nim'] ?? '-' }}</td>
                                                    <td>{{ $error['kode_mk'] ?? '-' }}</td>
                                                    <td>{{ ucfirst($error['error_type'] ?? '-') }}</td>
                                                    <td>{{ $error['message'] ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">Tidak ada error/warning yang tercatat.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div>
                                <div class="study-section-title">
                                    <div>
                                        <div class="fw-semibold">Riwayat koreksi</div>
                                        <div class="small text-muted">Bagian ini hanya dipakai bila Anda perlu melihat siapa yang pernah memperbarui hasil sebelumnya.</div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Catatan</th>
                                                <th>Operator</th>
                                                <th>Alasan</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse (($batch['revisions'] ?? []) as $revision)
                                                <tr>
                                                    <td>#{{ $revision['revision_number'] ?? '-' }}</td>
                                                    <td>{{ $revision['creator_name'] ?? '-' }}</td>
                                                    <td>{{ $revision['reason'] ?? '-' }}</td>
                                                    <td>{{ $formatDateTime($revision['created_at'] ?? null) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">Belum ada revisi yang tercatat.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
