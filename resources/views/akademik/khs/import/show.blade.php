@extends('layouts.index')
@section('title', 'Hasil Import Nilai KHS')

@php
    $status = (string) ($batch['status'] ?? 'uploaded');
    $summary = $batch['summary'] ?? [];
    $semester = $batch['semester'] ?? [];
    $tahunAkademik = $semester['tahun_akademik'] ?? $semester['tahunAkademik'] ?? [];
    $semesterLabel = trim((string) (($semester['nama_semester'] ?? '-') . ' ' . ($tahunAkademik['tahun_akademik'] ?? '')));
    $batchErrors = $batch['errors'] ?? [];
    $revisions = $batch['revisions'] ?? [];
    $processedKhsItems = collect($summary['processed_khs_items'] ?? [])->values()->all();
    $processedKhsFinalCount = collect($processedKhsItems)->where('is_final', true)->count();
    $processedKhsDraftCount = max(count($processedKhsItems) - $processedKhsFinalCount, 0);
    $processedKrsDetailIds = collect($summary['processed_krs_detail_ids'] ?? [])->filter()->values();
    $krsDetailSnapshots = collect($summary['krs_detail_snapshots'] ?? [])->values();
    $processedKrsMahasiswaCount = $krsDetailSnapshots->pluck('id_krs')->filter()->unique()->count();
    $statusLabels = [
        'uploaded' => 'Baru Diunggah',
        'previewed' => 'Sudah Dicek',
        'processed' => 'Selesai Diproses',
        'failed' => 'Gagal Diproses',
        'rolled_back' => 'Sudah Dibatalkan',
    ];
    $errorTypeLabels = [
        'warning' => 'Peringatan',
        'validation' => 'Validasi',
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
                linear-gradient(135deg, #0f172a 0%, #1d4ed8 58%, #2563eb 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
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
            color: #0f172a;
            line-height: 1;
        }

        .khs-note-box {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
            padding: 1rem;
            height: 100%;
        }

        .khs-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
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
            color: #0f172a;
            line-height: 1;
        }

        .khs-khs-list {
            display: grid;
            gap: 0.75rem;
            max-height: 380px;
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

        @media (max-width: 991.98px) {
            .khs-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .khs-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Hasil Import Nilai KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.history') }}">Riwayat Import KHS</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.show', $batch['id'] ?? '') }}">Hasil Import</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        @if (request()->boolean('legacy'))
            <div class="alert alert-info border mb-4">
                Halaman ini menampilkan ringkasan hasil proses import. Untuk memilih mahasiswa yang akan difinalisasi,
                gunakan tombol <strong>Buka Pilihan Finalisasi</strong>.
            </div>
        @endif

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <span class="khs-chip">
                            <i class="fas fa-file-check"></i>
                            Hasil proses import
                        </span>
                        <h2 class="fw-bold mt-3 mb-2">{{ $batch['file_name'] ?? 'Import Nilai KHS' }}</h2>
                        <p class="mb-0 text-white-50">{{ $semesterLabel ?: '-' }}</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @include('layouts.partials.status-badge', ['value' => $status, 'label' => $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status))])
                        <a href="{{ route('akademik.khs.import.preview', ['batch' => $batch['id'], 'legacy' => 1]) }}" class="btn btn-light btn-sm">Buka Pilihan Finalisasi</a>
                        @if ($status === 'processed')
                            <form method="POST" action="{{ route('akademik.khs.import.rollback', $batch['id']) }}" onsubmit="return confirm('Rollback hasil proses ini sekarang?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">Rollback</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card khs-shell-card mb-4">
            <div class="khs-shell-header">
                <h4 class="fw-bold mb-1">Ringkasan Proses</h4>
                <p class="text-muted mb-0">Bagian ini merangkum hasil utama import agar operator cepat tahu apa yang berhasil diproses dan apa yang masih perlu dicek.</p>
            </div>
            <div class="khs-shell-body">
                <div class="khs-summary-grid">
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Total Baris</div>
                        <div class="khs-summary-value">{{ $batch['total_rows'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Siap Diproses</div>
                        <div class="khs-summary-value text-success">{{ $batch['total_success'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Perlu Perbaikan</div>
                        <div class="khs-summary-value text-danger">{{ $batch['total_failed'] ?? 0 }}</div>
                    </div>
                    <div class="khs-summary-card">
                        <div class="khs-summary-label">Perlu Dicek</div>
                        <div class="khs-summary-value text-warning">{{ $summary['total_warning'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-lg-6">
                        <div class="khs-note-box">
                            <div class="fw-semibold mb-2">Informasi proses</div>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="35%">Pengunggah</th>
                                    <td>{{ $batch['uploader']['name'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Upload</th>
                                    <td>{{ $formatDateTime($batch['created_at'] ?? null) }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Proses</th>
                                    <td>{{ $formatDateTime($batch['processed_at'] ?? null) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="khs-note-box">
                            <div class="fw-semibold mb-2">Catatan pengecekan</div>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="45%">Keterangan berbeda</th>
                                    <td>{{ $summary['total_keterangan_mismatch'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Mutu berbeda</th>
                                    <td>{{ $summary['total_mutu_mismatch'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Mata kuliah berbeda</th>
                                    <td>{{ $summary['total_mk_mismatched'] ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($processedKrsDetailIds->isNotEmpty())
            <div class="card khs-shell-card mb-4">
                <div class="khs-shell-header">
                    <h4 class="fw-bold mb-1">Sinkronisasi Nilai ke KRS</h4>
                    <p class="text-muted mb-0">Bagian ini membantu Anda melihat seberapa banyak detail KRS yang diperbarui sebelum KHS dibentuk.</p>
                </div>
                <div class="khs-shell-body">
                    <div class="khs-summary-grid">
                        <div class="khs-summary-card">
                            <div class="khs-summary-label">KRS Detail Tersinkron</div>
                            <div class="khs-summary-value text-primary">{{ $processedKrsDetailIds->count() }}</div>
                        </div>
                        <div class="khs-summary-card">
                            <div class="khs-summary-label">Mahasiswa Terdampak</div>
                            <div class="khs-summary-value">{{ $processedKrsMahasiswaCount }}</div>
                        </div>
                        <div class="khs-summary-card">
                            <div class="khs-summary-label">Snapshot Tersimpan</div>
                            <div class="khs-summary-value">{{ $krsDetailSnapshots->count() }}</div>
                        </div>
                        <div class="khs-summary-card">
                            <div class="khs-summary-label">Mode Proses</div>
                            <div class="khs-summary-value" style="font-size:1rem;">KRS lalu KHS</div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-lg-7">
                            <div class="khs-note-box">
                                <div class="fw-semibold mb-2">Yang dilakukan sistem</div>
                                <div class="small text-muted">
                                    Nilai dari file import disimpan dulu ke <code>KRS Detail</code> yang cocok. Setelah itu sistem membentuk ulang <code>KHS</code> dan <code>KHS Detail</code> dari data final tersebut.
                                </div>
                            </div>
                        </div>
                    <div class="col-lg-5">
                        <div class="khs-note-box">
                            <div class="fw-semibold mb-2">Bila perlu dibatalkan</div>
                            <div class="small text-muted">
                                Sistem masih menyimpan data pendukung agar rollback bisa mengembalikan kondisi sebelum import jika memang dibutuhkan.
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        @endif

        @if (!empty($processedKhsItems))
            @if ($status === 'processed')
                <div class="card khs-shell-card mb-4">
                    <div class="khs-shell-body">
                        <div class="khs-finalize-spotlight">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                <div>
                                    <span class="khs-chip">
                                        <i class="fas fa-lock"></i>
                                        Finalisasi Hasil KHS
                                    </span>
                                    <h4 class="fw-bold mt-3 mb-2">Sahkan hasil nilai dalam satu tempat</h4>
                                    <p class="text-muted mb-0">
                                        Anda bisa langsung mengesahkan semua hasil KHS dari proses ini, atau memilih mahasiswa tertentu
                                        pada daftar di bawah.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('akademik.khs.import.finalize', $batch['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Finalisasi semua KHS hasil import ini sekarang?')">
                                        <i class="fas fa-lock me-1"></i> Finalisasi Semua KHS
                                    </button>
                                </form>
                            </div>
                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <div class="khs-finalize-stat">
                                        <div class="label">Total Data Mahasiswa</div>
                                        <div class="value">{{ count($processedKhsItems) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="khs-finalize-stat">
                                        <div class="label">Belum Final</div>
                                        <div class="value text-warning">{{ $processedKhsDraftCount }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="khs-finalize-stat">
                                        <div class="label">Sudah Final</div>
                                        <div class="value text-success">{{ $processedKhsFinalCount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card khs-shell-card mb-4">
                <div class="khs-shell-header">
                    <h4 class="fw-bold mb-1">Hasil KHS dari Import Ini</h4>
                    <p class="text-muted mb-0">Cari mahasiswa, centang yang ingin disahkan, lalu kirim sekali. Pencarian berjalan langsung di halaman ini.</p>
                </div>
                <div class="khs-shell-body">
                    <div class="khs-khs-picker">
                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-lg-5">
                                <label for="batchKhsSearch" class="form-label">Cari mahasiswa</label>
                                <input type="text" id="batchKhsSearch" class="form-control" placeholder="Ketik nama atau NIM mahasiswa">
                            </div>
                            <div class="col-lg-7">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="selectVisibleKhsBtn">Pilih yang tampil</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearVisibleKhsBtn">Batalkan pilihan yang tampil</button>
                                </div>
                            </div>
                        </div>

                        @if ($status === 'processed')
                            <form method="POST" action="{{ route('akademik.khs.import.finalize', $batch['id']) }}">
                                @csrf
                                <div class="khs-khs-list mb-3" id="batchKhsList">
                                    @foreach ($processedKhsItems as $item)
                                        @php
                                            $isItemFinal = !empty($item['is_final']);
                                            $searchText = strtolower(trim(($item['nim'] ?? '') . ' ' . ($item['nama_mahasiswa'] ?? '')));
                                        @endphp
                                        <div class="khs-khs-item {{ $isItemFinal ? 'is-final' : '' }}" data-search="{{ $searchText }}">
                                            <div class="d-flex flex-wrap justify-content-between gap-3">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input khs-finalize-checkbox"
                                                        type="checkbox"
                                                        name="khs_ids[]"
                                                        value="{{ $item['id'] }}"
                                                        id="khs-{{ $item['id'] }}"
                                                        {{ $isItemFinal ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="khs-{{ $item['id'] }}">
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
                                    <i class="fas fa-lock me-1"></i> Sahkan KHS Terpilih
                                </button>
                            </form>
                        @else
                            <div class="khs-khs-list" id="batchKhsList">
                                @foreach ($processedKhsItems as $item)
                                    @php
                                        $searchText = strtolower(trim(($item['nim'] ?? '') . ' ' . ($item['nama_mahasiswa'] ?? '')));
                                    @endphp
                                    <div class="khs-khs-item" data-search="{{ $searchText }}">
                                        <div class="d-flex flex-wrap justify-content-between gap-3">
                                            <div>
                                                <div class="fw-semibold">{{ $item['nama_mahasiswa'] ?? 'Mahasiswa' }}</div>
                                                <div class="small text-muted">{{ $item['nim'] ?? '-' }}</div>
                                            </div>
                                            <a href="{{ route('akademik.khs.show', $item['id']) }}" class="btn btn-outline-primary btn-sm">
                                                Buka KHS
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card khs-shell-card h-100">
                    <div class="khs-shell-header">
                        <h4 class="fw-bold mb-1">Riwayat Perubahan</h4>
                        <p class="text-muted mb-0">Menampilkan catatan perubahan saat hasil import ini memperbarui KHS yang sudah ada sebelumnya.</p>
                    </div>
                    <div class="khs-shell-body">
                        @if (empty($revisions))
                            <div class="text-muted">Belum ada catatan perubahan pada hasil import ini.</div>
                        @else
                            <div class="khs-table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Revision</th>
                                                <th>Pembuat</th>
                                                <th>Alasan</th>
                                                <th>Dibuat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($revisions as $item)
                                                <tr>
                                                    <td>#{{ $item['revision_number'] ?? '-' }}</td>
                                                    <td>{{ $item['creator']['name'] ?? '-' }}</td>
                                                    <td>{{ $item['reason'] ?? '-' }}</td>
                                                    <td>{{ $formatDateTime($item['created_at'] ?? null) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card khs-shell-card h-100">
                    <div class="khs-shell-header">
                        <h4 class="fw-bold mb-1">Catatan yang Perlu Dicek</h4>
                        <p class="text-muted mb-0">Bagian ini membantu Anda melihat masalah yang ditemukan saat pengecekan data.</p>
                    </div>
                    <div class="khs-shell-body">
                        @if (empty($batchErrors))
                            <div class="text-muted">Belum ada masalah yang tersimpan pada hasil import ini.</div>
                        @else
                            <div class="khs-table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Baris</th>
                                                <th>NIM</th>
                                                <th>Tipe</th>
                                                <th>Pesan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($batchErrors as $item)
                                                <tr>
                                                    <td>{{ $item['row_number'] ?? '-' }}</td>
                                                    <td>{{ $item['nim'] ?? '-' }}</td>
                                                    <td>@include('layouts.partials.status-badge', ['value' => $item['error_type'] ?? 'warning', 'label' => $errorTypeLabels[$item['error_type'] ?? 'warning'] ?? ucfirst($item['error_type'] ?? 'warning')])</td>
                                                    <td>{{ $item['message'] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
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

@push('scripts-custom')
    <script>
        (function() {
            const searchInput = document.getElementById('batchKhsSearch');
            const list = document.getElementById('batchKhsList');
            const selectVisibleBtn = document.getElementById('selectVisibleKhsBtn');
            const clearVisibleBtn = document.getElementById('clearVisibleKhsBtn');

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
                    const checkbox = item.querySelector('.khs-finalize-checkbox:not(:disabled)');
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });

            clearVisibleBtn?.addEventListener('click', function() {
                getVisibleItems().forEach((item) => {
                    const checkbox = item.querySelector('.khs-finalize-checkbox:not(:disabled)');
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                });
            });
        })();
    </script>
@endpush
