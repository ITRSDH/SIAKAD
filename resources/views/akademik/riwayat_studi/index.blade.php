@extends('layouts.index')
@section('title', 'Administrasi Riwayat Studi Historis')

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
    $tahunAkademikOptions = $filters['tahun_akademik'] ?? [];
    $semesterOptions = $filters['semester'] ?? [];
    $prodiOptions = $filters['prodi'] ?? [];
    $semesterKeOptions = $filters['semester_ke_options'] ?? [];
    $latestBatch = collect($recentBatches ?? [])->first();
    $semesterIndex = collect($semesterOptions)
        ->mapWithKeys(function ($item) {
            return [
                $item['id'] => [
                    'id_tahun_akademik' => $item['id_tahun_akademik'] ?? null,
                    'tahun_akademik' =>
                        $item['tahun_akademik']['tahun_akademik'] ?? ($item['tahunAkademik']['tahun_akademik'] ?? ''),
                ],
            ];
        })
        ->all();
@endphp

@push('styles-custom')
    <style>
        .historical-hero {
            border: 0;
            border-radius: 26px;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.20), transparent 30%),
                linear-gradient(135deg, #0b3c5d 0%, #116466 50%, #e27d60 100%);
            color: #fff;
        }

        .historical-hero .card-body {
            padding: 1.8rem;
        }

        .historical-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 0.82rem;
        }

        .historical-title {
            font-size: clamp(1.8rem, 3vw, 2.7rem);
            font-weight: 800;
            line-height: 1.08;
            max-width: 14ch;
            margin: 1rem 0 0.75rem;
        }

        .historical-copy {
            max-width: 65ch;
            color: rgba(255, 255, 255, 0.86);
            margin-bottom: 0;
        }

        .historical-card {
            border: 1px solid #dbe4f0;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .historical-card .card-body,
        .historical-card .card-header {
            padding: 1.35rem 1.5rem;
        }

        .historical-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: #eef7f6;
            color: #116466;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .historical-soft {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 1rem;
        }

        .historical-guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.9rem;
        }

        .historical-guide-item {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
        }

        .historical-guide-number {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #fff;
            background: #116466;
            margin-bottom: 0.75rem;
        }

        .historical-helper-list {
            display: grid;
            gap: 0.75rem;
        }

        .historical-helper-item {
            border-left: 4px solid #116466;
            background: #f8fafc;
            border-radius: 14px;
            padding: 0.85rem 1rem;
        }

        .historical-action-note {
            border-radius: 16px;
            border: 1px solid #dbe4f0;
            background: #f8fafc;
            padding: 1rem;
        }

        .historical-json-note {
            border-left: 4px solid #e27d60;
            background: #fff8f4;
            border-radius: 14px;
            padding: 0.9rem 1rem;
            color: #7c2d12;
        }

        .historical-builder-card {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            background: #fff;
            overflow: hidden;
        }

        .historical-builder-header {
            padding: 0.9rem 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .historical-builder-body {
            padding: 1rem;
        }

        .historical-course-row {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.85rem;
            background: #fcfdff;
        }

        .historical-course-row.is-clickable {
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }

        .historical-course-row.is-clickable:hover {
            transform: translateY(-1px);
            border-color: #93c5fd;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.10);
        }

        .historical-course-row.is-active {
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.12);
        }

        .historical-step {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f172a;
        }

        .historical-step-badge {
            width: 1.9rem;
            height: 1.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #0b3c5d;
            color: #fff;
            font-size: 0.82rem;
        }

        .historical-generate-panel {
            border-radius: 18px;
            border: 1px solid #c7e0f7;
            background: linear-gradient(180deg, #f7fbff 0%, #eef6ff 100%);
            padding: 1rem;
        }

        .historical-table td,
        .historical-table th {
            vertical-align: middle;
        }

        .historical-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .historical-student-row {
            transition: background-color 0.15s ease, transform 0.15s ease;
        }

        .historical-student-row:hover {
            background: #f8fbff;
        }

        .historical-student-row.is-disabled {
            opacity: 0.82;
            background: #fcfcfd;
        }

        .historical-student-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            margin-top: 0.35rem;
        }

        .historical-mini-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 0.2rem 0.55rem;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .historical-status-stack {
            display: grid;
            gap: 0.2rem;
        }

        .historical-status-note {
            font-size: 0.75rem;
            color: #64748b;
        }

        .historical-results {
            display: grid;
            gap: 0.9rem;
        }

        .historical-result {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .historical-result.ready {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }

        .historical-result.skipped {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .historical-result.failed {
            border-color: #fecaca;
            background: #fef2f2;
        }

        .historical-result.executed {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .historical-result-head {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 0.9rem;
            align-items: start;
        }

        .historical-result-icon {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 800;
            background: #e2e8f0;
            color: #334155;
        }

        .historical-result.ready .historical-result-icon,
        .historical-result.executed .historical-result-icon {
            background: #dcfce7;
            color: #166534;
        }

        .historical-result.skipped .historical-result-icon {
            background: #fef3c7;
            color: #92400e;
        }

        .historical-result.failed .historical-result-icon {
            background: #fee2e2;
            color: #991b1b;
        }

        .historical-result-body {
            margin-top: 0.85rem;
            display: grid;
            gap: 0.5rem;
        }

        .historical-result-helper {
            font-size: 0.78rem;
            color: #475569;
        }

        .historical-result-summary {
            border-top: 1px dashed #dbe4f0;
            padding-top: 0.65rem;
            font-size: 0.8rem;
            color: #475569;
            display: grid;
            gap: 0.18rem;
        }

        .historical-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .historical-badge.ready,
        .historical-badge.executed {
            background: #dcfce7;
            color: #166534;
        }

        .historical-badge.skipped {
            background: #fef3c7;
            color: #92400e;
        }

        .historical-badge.failed {
            background: #fee2e2;
            color: #991b1b;
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Administrasi Riwayat Studi Historis</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.riwayat-studi.index') }}">Riwayat Studi Historis</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card historical-hero mb-4">
            <div class="card-body">
                <div class="historical-kicker">
                    <i class="fas fa-history"></i>
                    <span>Workspace administratif untuk semester yang belum terbentuk</span>
                </div>
                <div class="row g-4 align-items-end">
                    <div class="col-xl-8">
                        <h1 class="historical-title">Lengkapi jejak studi lama sebelum flow reguler berjalan penuh.</h1>
                        <p class="historical-copy">
                            Gunakan halaman ini untuk membentuk KRS historis, membuka ulang koreksi, memfinalisasi ulang,
                            dan menjalankan generate KHS kolektif per semester historis.
                        </p>
                    </div>
                    {{-- <div class="col-xl-4">
                        <div class="historical-soft bg-white bg-opacity-10 border-0 text-white">
                            <div class="small text-white-50 mb-2">Use case utama</div>
                            <div class="fw-semibold mb-2">Mahasiswa sudah aktif di semester 4</div>
                            <div class="small text-white-75">Tetapi riwayat semester 1 sampai 3 belum ada di sistem baru. Workspace ini dipakai untuk membentuk data historis itu secara terkontrol.</div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="card historical-card mb-4">
            <div class="card-header border-0">
                <h4 class="mb-1">Cara Pakai Singkat</h4>
                <p class="text-muted mb-0">Ikuti alur ini dari kiri ke kanan. Operator awam cukup fokus pada empat langkah
                    berikut.</p>
            </div>
            <div class="card-body pt-0 mt-4">
                <div class="historical-guide-grid">
                    <div class="historical-guide-item">
                        <div class="historical-guide-number">1</div>
                        <div class="fw-semibold mb-1">Pilih semester riwayat</div>
                        <div class="small text-muted">Tentukan semester historis, prodi, angkatan, dan semester paket.</div>
                    </div>
                    <div class="historical-guide-item">
                        <div class="historical-guide-number">2</div>
                        <div class="fw-semibold mb-1">Muat mahasiswa</div>
                        <div class="small text-muted">Klik tombol muat data untuk menampilkan mahasiswa yang bisa diproses.
                        </div>
                    </div>
                    <div class="historical-guide-item">
                        <div class="historical-guide-number">3</div>
                        <div class="fw-semibold mb-1">Isi kelas dan nilai</div>
                        <div class="small text-muted">Pilih mahasiswa, lalu susun kelas historis dan nilai akhirnya di
                            builder.</div>
                    </div>
                    <div class="historical-guide-item">
                        <div class="historical-guide-number">4</div>
                        <div class="fw-semibold mb-1">Periksa lalu simpan</div>
                        <div class="small text-muted">Lihat hasil preview. Jika sudah benar, simpan proses dan lanjut ke
                            hasil studi.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card historical-card mb-4">
                    <div class="card-header border-0">
                        <div class="historical-step">
                            <span class="historical-step-badge">1</span>
                            <span>Pilih periode dan paket semester</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Tentukan semester riwayat yang ingin dibentuk, program studi,
                            angkatan, dan semester paket mata kuliahnya.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        <form id="historicalFilterForm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Semester Historis</label>
                                    <select class="form-select select2" id="historicalSemesterId"
                                        name="historicalSemesterId" required>
                                        <option value="">Pilih semester</option>
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
                                <div class="col-md-4">
                                    <label class="form-label">Tahun Akademik</label>
                                    <select class="form-select" id="historicalAcademicYearDisplay" disabled>
                                        <option value="">Pilih semester historis dulu</option>
                                        @foreach ($tahunAkademikOptions as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['tahun_akademik'] ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Program Studi</label>
                                    <select class="form-select select2" id="id_prodi" name="id_prodi">
                                        <option value="">Semua prodi</option>
                                        @foreach ($prodiOptions as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] ?? 'Program Studi' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Angkatan</label>
                                    <input type="number" class="form-control" id="angkatan" name="angkatan"
                                        placeholder="Contoh: 2023">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Semester Paket</label>
                                    <select class="form-select select2" id="semesterKe" name="semesterKe">
                                        <option value="">Pilih semester paket</option>
                                        @foreach ($semesterKeOptions as $item)
                                            <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-primary" id="loadEligibleBtn">
                                    <i class="fas fa-book-open me-1"></i> Muat Mahasiswa dan Kelas
                                </button>
                                <a href="{{ route('akademik.riwayat-studi.batches') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Proses
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card historical-card mb-4">
                    <div class="card-header border-0">
                        <div class="historical-step">
                            <span class="historical-step-badge">2</span>
                            <span>Tentukan aksi dan referensi kelas</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Daftar kelas di bawah membantu operator mengenali penawaran kelas
                            historis. Payload final tetap disusun pada builder mahasiswa.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Aksi</label>
                                <select class="form-select select2" id="historicalActionSelector">
                                    <option value="build_historical_krs">Bentuk KRS Historis</option>
                                    <option value="reopen_historical_krs">Buka Ulang Riwayat</option>
                                    <option value="refinalize_historical_krs">Finalisasi Ulang Riwayat</option>
                                    <option value="reset_historical_krs">Reset Isi Riwayat</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catatan Batch</label>
                                <input type="text" class="form-control" id="historicalBatchNotes"
                                    placeholder="Opsional, misalnya: histori semester 1 angkatan 2023">
                            </div>
                        </div>

                        <div class="historical-action-note mt-3" id="historicalActionNote">
                            <div class="fw-semibold mb-1">Membentuk Riwayat Pengambilan Mata Kuliah</div>
                            <div class="small text-muted mb-0">Tahap ini membentuk KRS historis sekaligus menyimpan nilai
                                final per mata kuliah ke detail KRS agar hasil studi dapat digenerate dengan konsisten.
                            </div>
                        </div>

                        <div class="historical-json-note mt-3" id="buildPayloadNote">
                            <div class="fw-semibold mb-1">Sistem menyiapkan daftar kelas historis sebagai referensi
                                builder.</div>
                            <div class="small">
                                Muat mahasiswa eligible terlebih dahulu, lalu gunakan builder untuk memilih kelas yang
                                benar-benar diambil mahasiswa dan isi nilai akhirnya langsung pada form.
                            </div>
                        </div>

                        <div class="mt-3" id="studentsPayloadWrapper">
                            <div class="historical-soft mb-3">
                                <div class="fw-semibold mb-2">Referensi Kelas Historis</div>
                                <div class="small text-muted mb-3">Gunakan daftar ini sebagai acuan saat mengisi builder
                                    mahasiswa. Anda tidak wajib memilih satu kelas tunggal sebelum memuat mahasiswa.</div>
                                <div class="small text-primary mb-3" id="historicalClassSelectionInfo">Belum ada kelas
                                    referensi yang dipilih.</div>
                                <div id="historicalPackageClassesPanel" class="d-grid gap-2">
                                    <div class="text-muted">Pilih prodi dan semester paket untuk menampilkan daftar kelas
                                        historis.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-outline-primary" id="previewHistoricalBtn">
                                <i class="fas fa-magnifying-glass me-1"></i> Periksa Dulu
                            </button>
                            <button type="button" class="btn btn-success" id="executeHistoricalBtn" disabled>
                                <i class="fas fa-play me-1"></i> Simpan Proses
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card historical-card mb-4">
                    <div class="card-header border-0">
                        <div class="historical-step">
                            <span class="historical-step-badge">3</span>
                            <span>Pilih mahasiswa yang akan dimasukkan</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Setelah filter semester historis siap, centang mahasiswa yang ingin
                            dimasukkan ke riwayat semester ini lalu bangun payload kelas dan nilai pada builder.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered historical-table mb-0" id="historicalStudentTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 42px;">
                                            <input type="checkbox" id="selectAllHistoricalStudents">
                                        </th>
                                        <th>Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Semester Target</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Gunakan tombol muat data
                                            untuk menampilkan mahasiswa eligible sesuai filter yang dipilih.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="historical-helper-list mt-3">
                            <div class="historical-helper-item small">
                                <strong>Belum melihat mahasiswa?</strong> Pastikan semester historis, prodi, dan semester
                                paket sudah dipilih lalu klik <strong>Muat Mahasiswa dan Kelas</strong>.
                            </div>
                            <div class="historical-helper-item small">
                                <strong>Untuk proses build:</strong> setelah mencentang mahasiswa, klik tombol
                                <strong>Siapkan Form Kelas dan Nilai</strong> di bawah lalu isi kelas yang diambil dan nilai
                                akhirnya.
                            </div>
                        </div>
                        <div class="historical-soft mt-3" id="historicalBuilderWorkspace">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <div class="fw-semibold">Form Kelas dan Nilai Mahasiswa</div>
                                    <div class="small text-muted">Bagian ini dipakai untuk menyusun mata kuliah historis
                                        dan nilai akhir per mahasiswa. Sistem akan menghitung huruf mutu dan status
                                        kelulusan otomatis.</div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        id="generateSelectedStudentsBuilderBtn">
                                        <i class="fas fa-pen-ruler me-1"></i> Siapkan Form Kelas dan Nilai
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="addManualStudentBuilderBtn">
                                        <i class="fas fa-user-plus me-1"></i> Tambah Mahasiswa Manual
                                    </button>
                                </div>
                            </div>
                            <div id="historicalStudentsBuilder" class="d-grid gap-3">
                                <div class="text-muted">Pilih mahasiswa terlebih dahulu, lalu klik <strong>Siapkan Form
                                        Kelas dan Nilai</strong>.</div>
                            </div>
                            <textarea id="studentsPayload" class="d-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card historical-card" id="historicalGenerateSection">
                    <div class="card-header border-0">
                        <span class="historical-chip"><i class="fas fa-file-signature"></i> Langkah Lanjutan</span>
                        <h4 class="mt-3 mb-1">Buat Hasil Studi</h4>
                        <p class="text-muted mb-0">Gunakan bagian ini setelah riwayat pengambilan mata kuliah selesai
                            disimpan dan nilainya sudah lengkap.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        <div class="historical-soft mb-3 d-none" id="historicalBuildQuickAction">
                            <div class="fw-semibold mb-1">Riwayat pengambilan mata kuliah berhasil disimpan</div>
                            <div class="small text-muted mb-3">Lanjutkan ke langkah berikutnya untuk memeriksa kesiapan
                                mahasiswa dan membuat hasil studi, tanpa perlu memilih kelas lagi.</div>
                            <div class="d-flex flex-wrap gap-2" id="historicalBuildQuickActionButtons"></div>
                        </div>
                        <div class="historical-generate-panel">
                            <div class="fw-semibold mb-1">Bagian ini dipakai setelah proses pembentukan atau perbaikan
                                riwayat selesai.</div>
                            <div class="small text-muted mb-3">
                                Sistem akan mengecek siapa saja yang sudah siap dibuatkan hasil studinya, siapa yang masih
                                harus diperbaiki, dan siapa yang belum bisa diproses.
                            </div>
                            <div class="small text-primary mb-3">
                                Setelah hasil studi berhasil dibuat, buka <strong>detail proses</strong> lalu klik
                                <strong>Lihat Hasil Studi</strong> untuk memeriksa hasil pembentukan KHS historis.
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary" id="previewGenerateKhsBtn">
                                    <i class="fas fa-magnifying-glass me-1"></i> Periksa Hasil Studi
                                </button>
                                <button type="button" class="btn btn-primary" id="executeGenerateKhsBtn" disabled>
                                    <i class="fas fa-bolt me-1"></i> Buat Hasil Studi
                                </button>
                            </div>
                            <div class="historical-soft mt-3 d-none" id="historicalKhsQuickAction">
                                <div class="fw-semibold mb-1">Hasil studi berhasil dibuat</div>
                                <div class="small text-muted mb-3">Lanjutkan langsung ke halaman hasil studi untuk
                                    memeriksa hasil KHS historis yang sudah terbentuk.</div>
                                <div class="d-flex flex-wrap gap-2" id="historicalKhsQuickActionButtons"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card historical-card mb-4">
                    <div class="card-header border-0">
                        <div class="historical-step">
                            <span class="historical-step-badge">4</span>
                            <span>Periksa lalu simpan</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Setelah memilih mahasiswa, periksa hasilnya di sini. Jika sudah
                            benar, lanjutkan simpan proses.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="small text-muted">Centang baris yang sudah siap diproses. Fokus pada baris
                                berstatus <strong>Siap</strong>.</div>
                            <label class="small mb-0">
                                <input type="checkbox" id="selectAllPreviewReady"> Pilih semua yang siap
                            </label>
                        </div>
                        <div id="historicalPreviewPanel" class="historical-results">
                            <div class="text-muted">Hasil pemeriksaan akan muncul di sini.</div>
                        </div>
                    </div>
                </div>

                <div class="card historical-card">
                    <div class="card-header border-0">
                        <h4 class="mb-1">Riwayat Proses Terakhir</h4>
                        <p class="text-muted mb-0">Bagian ini membantu operator melihat proses terakhir yang sudah
                            tersimpan.</p>
                    </div>
                    <div class="card-body pt-0 mt-4">
                        @if (!$latestBatch)
                            <div class="text-muted">Belum ada proses yang tercatat.</div>
                        @else
                            @php
                                $summary = $latestBatch['summary'] ?? [];
                                $semester = $latestBatch['semester'] ?? [];
                                $tahun =
                                    $semester['tahun_akademik']['tahun_akademik'] ??
                                    ($semester['tahunAkademik']['tahun_akademik'] ?? '');
                            @endphp
                            <div class="historical-soft mb-3">
                                <div class="fw-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $latestBatch['action_type'] ?? 'batch')) }}</div>
                                <div class="small text-muted mt-1">
                                    {{ ($semester['nama_semester'] ?? 'Semester') . ' ' . $tahun }}</div>
                                <div class="small text-muted mt-1">
                                    {{ $formatDateTime($latestBatch['executed_at'] ?? ($latestBatch['created_at'] ?? null)) }}
                                </div>
                                <div class="small text-muted mt-2">
                                    Total {{ $summary['total'] ?? 0 }} | Berhasil {{ $summary['executed'] ?? 0 }} |
                                    Dilewati {{ $summary['skipped'] ?? 0 }} | Gagal {{ $summary['failed'] ?? 0 }}
                                </div>
                            </div>
                            <a href="{{ route('akademik.riwayat-studi.batches.show', $latestBatch['id']) }}"
                                class="btn btn-outline-secondary btn-sm">
                                Lihat Detail Proses
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const semesterIndex = @json($semesterIndex);
        const khsShowUrlTemplate = @json(route('akademik.khs.show', ['khsId' => '__KHS__']));
        let eligibleStudents = [];
        let previewResults = [];
        let builderState = [];
        let classOptionsCache = {};
        let packageClasses = [];
        let currentPreviewAction = 'build_historical_krs';
        const historicalActionMeta = {
            build_historical_krs: {
                title: 'Bentuk KRS Historis',
                description: 'Gunakan aksi ini untuk membentuk KRS administratif pada semester historis yang dipilih sekaligus menyimpan nilai final pada detail KRS.',
                confirm: 'Lanjutkan membentuk KRS historis untuk mahasiswa yang dipilih?'
            },
            reopen_historical_krs: {
                title: 'Buka Ulang Riwayat',
                description: 'Gunakan hanya bila data final historis perlu dikoreksi. Setelah dibuka ulang, KRS menjadi editable secara administratif.',
                confirm: 'Lanjutkan membuka ulang riwayat historis yang dipilih?'
            },
            refinalize_historical_krs: {
                title: 'Finalisasi Ulang Riwayat',
                description: 'Gunakan setelah koreksi selesai agar KRS historis kembali ke kondisi approved dan locked.',
                confirm: 'Lanjutkan finalisasi ulang riwayat historis yang dipilih?'
            },
            reset_historical_krs: {
                title: 'Reset Isi Riwayat',
                description: 'Aksi ini menghapus detail KRS historis pada mahasiswa terpilih. Jalankan dengan hati-hati.',
                confirm: 'Lanjutkan reset isi riwayat historis yang dipilih?'
            }
        };

        const generateKhsMeta = {
            title: 'Generate KHS Kolektif',
            description: 'Gunakan setelah KRS historis valid. Hanya mahasiswa dengan KRS final yang siap akan bisa diproses.',
            confirm: 'Lanjutkan generate KHS kolektif untuk mahasiswa yang dipilih?'
        };

        function getSelectionActionContext() {
            return currentPreviewAction === 'generate_khs' ?
                'generate_khs' :
                ($('#historicalActionSelector').val() || 'build_historical_krs');
        }

        function isRowSelectable(row, action) {
            if (action === 'build_historical_krs') {
                return !!row.is_ready;
            }

            if (action === 'generate_khs' || action === 'reopen_historical_krs' || action === 'refinalize_historical_krs' ||
                action === 'reset_historical_krs') {
                return !!row.existing_historical_krs;
            }

            return true;
        }

        function resolveRowStatusLabel(row, action) {
            if (action === 'build_historical_krs') {
                return row.default_action ?? '-';
            }

            if (!row.existing_historical_krs) {
                return 'belum ada riwayat';
            }

            return 'siap dipilih';
        }

        function formatHistoricalStatusLabel(status) {
            const labels = {
                ready: 'Siap',
                executed: 'Berhasil',
                skipped: 'Dilewati',
                failed: 'Gagal',
                'siap dipilih': 'Siap Dipilih',
                'belum ada riwayat': 'Belum Ada Riwayat'
            };

            return labels[status] ?? status ?? '-';
        }

        function resolveStatusIcon(status) {
            const icons = {
                ready: 'fa-check',
                executed: 'fa-circle-check',
                skipped: 'fa-forward',
                failed: 'fa-triangle-exclamation',
                'siap dipilih': 'fa-hand-pointer',
                'belum ada riwayat': 'fa-clock'
            };

            return icons[status] ?? 'fa-info';
        }

        function resolvePreviewHelperText(status) {
            const notes = {
                ready: 'Data sudah aman untuk diproses pada langkah berikutnya.',
                executed: 'Proses untuk mahasiswa ini sudah berhasil dijalankan.',
                skipped: 'Mahasiswa ini sengaja dilewati atau tidak perlu diproses ulang.',
                failed: 'Periksa pesan error, lalu perbaiki data sebelum mencoba lagi.'
            };

            return notes[status] ?? 'Periksa detail status mahasiswa ini.';
        }

        function getSelectedEligibleMahasiswaIds() {
            return $('.historical-student-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
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

        function getSelectedPreviewMahasiswaIds() {
            return $('.historical-preview-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        function renderEligibleStudents(rows) {
            const $tbody = $('#historicalStudentTable tbody');
            const action = getSelectionActionContext();

            if (!rows.length) {
                $tbody.html(
                    '<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada mahasiswa eligible untuk filter ini.</td></tr>'
                );
                return;
            }

            const html = rows.map((row) => {
                const selectable = isRowSelectable(row, action);
                const statusLabel = resolveRowStatusLabel(row, action);
                const statusText = formatHistoricalStatusLabel(statusLabel);
                const checked = selectable ? '' : 'disabled';
                const badgeClass = statusLabel === 'ready' || statusLabel === 'siap dipilih' ?
                    'ready' :
                    (statusLabel === 'skipped' ? 'skipped' : 'failed');
                const helperMessage = action === 'build_historical_krs' ?
                    (row.message ?? '-') :
                    (row.existing_historical_krs ?
                        'Mahasiswa ini sudah memiliki riwayat pada semester tersebut dan bisa dipilih untuk proses lanjutan.' :
                        'Mahasiswa ini belum memiliki riwayat pada semester tersebut.');
                const induk = row.kurikulum_context?.kurikulum_induk || {};
                const struktur = row.kurikulum_context?.struktur_operasional || {};
                const kurikulumIndukLabel = formatKurikulumIndukLabel(induk);
                const strukturOperasionalLabel = [struktur.nama_kurikulum, struktur.nama_struktur_mk]
                    .filter(Boolean).join(' | ') || '-';

                return `
                    <tr class="historical-student-row ${selectable ? '' : 'is-disabled'}">
                        <td><input type="checkbox" class="historical-student-checkbox" value="${row.id}" ${checked}></td>
                        <td>
                            <div class="fw-semibold">${row.nama_mahasiswa}</div>
                            <div class="small text-muted">${row.nim}</div>
                            <div class="historical-student-meta">
                                ${row.angkatan ? `<span class="historical-mini-badge"><i class="fas fa-user-graduate"></i> Angkatan ${row.angkatan}</span>` : ''}
                                ${row.available_class_count ? `<span class="historical-mini-badge"><i class="fas fa-book"></i> ${row.available_class_count} kelas tersedia</span>` : ''}
                            </div>
                            <div class="small text-muted mt-2">
                                <div><strong>Induk:</strong> ${kurikulumIndukLabel}</div>
                                <div><strong>Operasional:</strong> ${strukturOperasionalLabel}</div>
                            </div>
                        </td>
                        <td>${row.prodi?.nama_prodi ?? '-'}</td>
                        <td>${row.semester_target ?? '-'}</td>
                        <td>
                            <div class="historical-status-stack">
                                <span class="historical-badge ${badgeClass}">${statusText}</span>
                                <span class="historical-status-note">${selectable ? 'Bisa dipilih' : 'Perlu perhatian'}</span>
                            </div>
                        </td>
                        <td class="small text-muted">${helperMessage}</td>
                    </tr>
                `;
            }).join('');

            $tbody.html(html);
        }

        function renderPreviewResults(rows) {
            const $panel = $('#historicalPreviewPanel');

            if (!rows.length) {
                $panel.html('<div class="text-muted">Belum ada hasil preview.</div>');
                $('#executeHistoricalBtn').prop('disabled', true);
                return;
            }

            const html = rows.map((row) => {
                const summary = row.meta?.summary ?? {};
                const showSummary = currentPreviewAction === 'generate_khs' && row.status === 'ready' && Object
                    .keys(summary).length > 0;

                return `
                <div class="historical-result ${row.status}">
                    <div class="historical-result-head">
                        <div class="historical-result-icon">
                            <i class="fas ${resolveStatusIcon(row.status)}"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">${row.nama_mahasiswa ?? '-'}</div>
                            <div class="small text-muted">${row.nim ?? '-'}</div>
                        </div>
                        <div class="text-end">
                            ${row.status === 'ready' ? `<input type="checkbox" class="historical-preview-checkbox" value="${row.id_mahasiswa}" checked>` : ''}
                            <div class="mt-2"><span class="historical-badge ${row.status}">${formatHistoricalStatusLabel(row.status)}</span></div>
                        </div>
                    </div>
                    <div class="historical-result-body">
                        <div class="small">${row.message ?? '-'}</div>
                        <div class="historical-result-helper">${resolvePreviewHelperText(row.status)}</div>
                        ${showSummary ? `
                                                                <div class="historical-result-summary">
                                                                    <div>IPS: ${summary.ips ?? '-'}</div>
                                                                    <div>IPK: ${summary.ipk ?? '-'}</div>
                                                                    <div>Keterangan KHS: ${summary.keterangan ?? '-'}</div>
                                                                </div>
                                                            ` : ''}
                    </div>
                </div>
                `;
            }).join('');

            $panel.html(html);

            const hasExecutable = rows.some((row) => row.status === 'ready');
            $('#executeHistoricalBtn').prop('disabled', !(hasExecutable && currentPreviewAction !== 'generate_khs'));
            $('#executeGenerateKhsBtn').prop('disabled', !(hasExecutable && currentPreviewAction === 'generate_khs'));
        }

        function syncBuildPayloadVisibility() {
            const action = $('#historicalActionSelector').val();
            const isBuild = action === 'build_historical_krs';
            const meta = historicalActionMeta[action];

            $('#studentsPayloadWrapper, #buildPayloadNote').toggle(isBuild);

            if (meta) {
                $('#historicalActionNote').html(`
                    <div class="fw-semibold mb-1">${meta.title}</div>
                    <div class="small text-muted mb-0">${meta.description}</div>
                `);
            }

            renderEligibleStudents(eligibleStudents);
        }

        function renderPackageClasses(rows) {
            const $panel = $('#historicalPackageClassesPanel');

            if (!$panel.length) {
                return;
            }

            if (!rows.length) {
                $panel.html(
                    '<div class="text-muted">Belum ada paket kelas untuk kombinasi semester historis, prodi, dan semester paket ini.</div>'
                );
                $('#historicalClassSelectionInfo').text('Belum ada kelas referensi yang dipilih.');
                return;
            }

            const html = rows.map((row) => `
                <div class="historical-course-row is-clickable" data-class-id="${row.id}">
                    <div class="fw-semibold">${row.mata_kuliah?.kode_mk ?? '-'} - ${row.mata_kuliah?.nama_mk ?? 'Mata Kuliah'}</div>
                    <div class="small text-muted">
                        <div><strong>Induk:</strong> ${formatKurikulumIndukLabel(row.kurikulum_context?.kurikulum_induk)}</div>
                        <div><strong>Operasional:</strong> ${row.nama_struktur_operasional ?? row.nama_kurikulum ?? 'Struktur Operasional'} | ${row.nama_kelas ?? 'Kelas'} | ${row.mata_kuliah?.sks ?? 0} SKS</div>
                    </div>
                    <div class="small text-primary mt-2">Klik untuk menjadikan kelas ini sebagai referensi cepat saat menyusun builder mahasiswa.</div>
                </div>
            `).join('');

            $panel.html(html);
        }

        function resetKhsQuickAction() {
            $('#historicalKhsQuickAction').addClass('d-none');
            $('#historicalKhsQuickActionButtons').html('');
        }

        function resetBuildQuickAction() {
            $('#historicalBuildQuickAction').addClass('d-none');
            $('#historicalBuildQuickActionButtons').html('');
        }

        function prepareGenerateKhsFlow(scrollToSection = false) {
            currentPreviewAction = 'generate_khs';
            previewResults = [];
            packageClasses = [];
            $('#selectAllHistoricalStudents, #selectAllPreviewReady').prop('checked', false);
            $('#historicalClassSelectionInfo').text('Referensi kelas tidak diperlukan untuk membuat hasil studi.');
            renderPackageClasses(packageClasses);
            renderPreviewResults(previewResults);
            loadEligibleStudents();

            if (scrollToSection) {
                const section = document.getElementById('historicalGenerateSection');
                if (section) {
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        }

        function showBuildQuickAction(batchId) {
            const buttons = [
                `<button type="button" class="btn btn-primary btn-sm" id="continueToGenerateKhsBtn"><i class="fas fa-arrow-right me-1"></i> Lanjut ke Buat Hasil Studi</button>`
            ];

            if (batchId) {
                buttons.push(
                    `<a href="{{ url('akademik/riwayat-studi/batches') }}/${batchId}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-list-check me-1"></i> Lihat Detail Proses</a>`
                );
            }

            $('#historicalBuildQuickActionButtons').html(buttons.join(''));
            $('#historicalBuildQuickAction').removeClass('d-none');
        }

        function showKhsQuickAction(results, batchId) {
            const executedKhs = (results ?? []).filter((item) => item?.status === 'executed' && item?.meta?.id_khs);
            if (!executedKhs.length) {
                resetKhsQuickAction();
                return;
            }

            const firstKhsId = executedKhs[0].meta.id_khs;
            const khsUrl = khsShowUrlTemplate.replace('__KHS__', firstKhsId);
            const buttons = [
                `<a href="${khsUrl}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-up-right-from-square me-1"></i> Buka Hasil Studi</a>`
            ];

            if (batchId) {
                buttons.push(
                    `<a href="{{ url('akademik/riwayat-studi/batches') }}/${batchId}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-list-check me-1"></i> Lihat Detail Proses</a>`
                );
            }

            $('#historicalKhsQuickActionButtons').html(buttons.join(''));
            $('#historicalKhsQuickAction').removeClass('d-none');
        }

        function loadPackageClasses() {
            const semesterId = $('#historicalSemesterId').val();
            const idProdi = $('#id_prodi').val();
            const semesterKe = $('#semesterKe').val();

            if (!semesterId || !idProdi || !semesterKe) {
                packageClasses = [];
                renderPackageClasses(packageClasses);
                return $.Deferred().resolve([]);
            }

            return $.get('{{ route('akademik.riwayat-studi.package-classes') }}', {
                id_semester: semesterId,
                id_prodi: idProdi,
                semester_ke: semesterKe
            }).done(function(response) {
                packageClasses = response.data ?? [];
                renderPackageClasses(packageClasses);
            }).fail(function(xhr) {
                packageClasses = [];
                renderPackageClasses(packageClasses);
                alert(xhr.responseJSON?.message ?? 'Gagal memuat paket kelas historis.');
            });
        }

        function loadEligibleStudents() {
            const semesterId = $('#historicalSemesterId').val();

            $.get('{{ route('akademik.riwayat-studi.eligible') }}', {
                id_semester: semesterId,
                id_prodi: $('#id_prodi').val(),
                angkatan: $('#angkatan').val()
            }).done(function(response) {
                eligibleStudents = response.data ?? [];
                builderState = [];
                classOptionsCache = {};
                renderEligibleStudents(eligibleStudents);
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.message ?? 'Gagal memuat daftar mahasiswa.');
            });
        }

        function loadStudentsForCurrentAction() {
            const action = getSelectionActionContext();

            if (!$('#historicalSemesterId').val()) {
                alert('Pilih semester historis terlebih dahulu.');
                return;
            }

            if (action === 'build_historical_krs') {
                if (!$('#id_prodi').val()) {
                    alert('Pilih program studi terlebih dahulu.');
                    return;
                }

                if (!$('#semesterKe').val()) {
                    alert('Pilih semester paket terlebih dahulu.');
                    return;
                }

                loadPackageClasses();
                loadEligibleStudents();
                return;
            }

            packageClasses = [];
            renderPackageClasses(packageClasses);
            $('#historicalClassSelectionInfo').text('Referensi kelas tidak diperlukan untuk aksi ini.');
            loadEligibleStudents();
        }

        function runHistoricalPreview(action) {
            const selectedIds = getSelectedEligibleMahasiswaIds();

            if (!$('#historicalSemesterId').val()) {
                alert('Pilih semester historis terlebih dahulu.');
                return;
            }

            if (action === 'build_historical_krs' && !$('#semesterKe').val()) {
                alert('Pilih semester paket terlebih dahulu.');
                return;
            }

            if (!selectedIds.length) {
                alert('Pilih minimal satu mahasiswa untuk preview.');
                return;
            }

            currentPreviewAction = action;

            const $button = action === 'generate_khs' ? $('#previewGenerateKhsBtn') : $('#previewHistoricalBtn');
            const loadingLabel = action === 'generate_khs' ?
                '<i class="fas fa-spinner fa-spin me-1"></i> Memeriksa Hasil Studi...' :
                '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
            const idleLabel = action === 'generate_khs' ?
                '<i class="fas fa-magnifying-glass me-1"></i> Periksa Hasil Studi' :
                '<i class="fas fa-magnifying-glass me-1"></i> Periksa Dulu';

            $button.prop('disabled', true).html(loadingLabel);

            $.post({
                url: '{{ route('akademik.riwayat-studi.preview') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    ...buildRequestPayload(false),
                    action_type: action,
                    selected_mahasiswa_ids: selectedIds
                }
            }).done(function(response) {
                previewResults = response.data ?? [];
                renderPreviewResults(previewResults);
            }).fail(function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    alert(Object.values(errors).flat().join('\n'));
                    return;
                }

                alert(xhr.responseJSON?.message ?? 'Gagal menjalankan preview historical.');
            }).always(function() {
                $button.prop('disabled', false).html(idleLabel);
            });
        }

        function runHistoricalExecute(action) {
            const selectedIds = getSelectedPreviewMahasiswaIds();
            const meta = action === 'generate_khs' ? generateKhsMeta : historicalActionMeta[action];
            resetBuildQuickAction();
            resetKhsQuickAction();

            if (!selectedIds.length) {
                alert('Pilih minimal satu hasil preview berstatus ready untuk dieksekusi.');
                return;
            }

            if (!confirm(meta?.confirm ?? 'Lanjutkan execute batch riwayat studi historis?')) {
                return;
            }

            const $button = action === 'generate_khs' ? $('#executeGenerateKhsBtn') : $('#executeHistoricalBtn');
            const loadingLabel = action === 'generate_khs' ?
                '<i class="fas fa-spinner fa-spin me-1"></i> Membuat Hasil Studi...' :
                '<i class="fas fa-spinner fa-spin me-1"></i> Menjalankan...';
            const idleLabel = action === 'generate_khs' ?
                '<i class="fas fa-bolt me-1"></i> Buat Hasil Studi' :
                '<i class="fas fa-play me-1"></i> Simpan Proses';

            $button.prop('disabled', true).html(loadingLabel);

            $.post({
                url: '{{ route('akademik.riwayat-studi.execute') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    ...buildRequestPayload(true),
                    action_type: action,
                    selected_mahasiswa_ids: selectedIds
                }
            }).done(function(response) {
                const summary = response.data?.summary ?? {};
                const batchId = response.data?.batch_id;
                const results = response.data?.results ?? [];
                const message = [
                    response.message ?? 'Batch selesai diproses.',
                    `Target: ${summary.total ?? 0}`,
                    `Berhasil: ${summary.executed ?? 0}`,
                    `Dilewati: ${summary.skipped ?? 0}`,
                    `Gagal: ${summary.failed ?? 0}`
                ].join('\n');

                alert(message);

                if (action === 'build_historical_krs') {
                    showBuildQuickAction(batchId);
                    prepareGenerateKhsFlow(true);
                    return;
                }

                if (action === 'generate_khs') {
                    showKhsQuickAction(results, batchId);
                    return;
                }

                if (batchId) {
                    window.location.href = `{{ url('akademik/riwayat-studi/batches') }}/${batchId}`;
                } else {
                    window.location.reload();
                }
            }).fail(function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    alert(Object.values(errors).flat().join('\n'));
                    return;
                }

                alert(xhr.responseJSON?.message ?? 'Gagal mengeksekusi batch historical.');
            }).always(function() {
                $button.prop('disabled', false).html(idleLabel);
            });
        }

        function buildRequestPayload(includeExecuteSelection = false) {
            syncStudentsPayloadFromBuilder();

            return {
                historicalSemesterId: $('#historicalSemesterId').val(),
                id_prodi: $('#id_prodi').val(),
                angkatan: $('#angkatan').val(),
                semesterKe: $('#semesterKe').val(),
                action_type: $('#historicalActionSelector').val(),
                batchNotes: $('#historicalBatchNotes').val(),
                selected_mahasiswa_ids: includeExecuteSelection ? getSelectedPreviewMahasiswaIds() :
                    getSelectedEligibleMahasiswaIds(),
                students_payload: $('#studentsPayload').val()
            };
        }

        function getEligibleStudentById(studentId) {
            return eligibleStudents.find((item) => item.id === studentId) ?? null;
        }

        function createEmptyCourse() {
            return {
                id_kelas_kuliah: '',
                nilai_akhir: '',
                catatan: ''
            };
        }

        function ensureBuilderStudent(studentId, preferredStudent = null) {
            const existing = builderState.find((item) => item.id_mahasiswa === studentId);
            if (existing) {
                return existing;
            }

            const source = preferredStudent ?? getEligibleStudentById(studentId);
            const entry = {
                id_mahasiswa: studentId,
                id_prodi: source?.id_prodi ?? '',
                nim: source?.nim ?? '',
                nama_mahasiswa: source?.nama_mahasiswa ?? 'Mahasiswa',
                courses: [createEmptyCourse()]
            };

            builderState.push(entry);
            return entry;
        }

        function syncStudentsPayloadFromBuilder() {
            const payload = builderState.map((student) => ({
                id_mahasiswa: student.id_mahasiswa,
                courses: (student.courses ?? [])
                    .filter((course) => Object.values(course).some((value) => value !== null && value !== ''))
                    .map((course) => ({
                        id_kelas_kuliah: course.id_kelas_kuliah || '',
                        nilai_akhir: course.nilai_akhir === '' || course.nilai_akhir === null ? '' :
                            Number(course.nilai_akhir),
                        catatan: course.catatan || ''
                    }))
            }));

            $('#studentsPayload').val(JSON.stringify(payload, null, 2));
        }

        function renderStudentsBuilder() {
            const $builder = $('#historicalStudentsBuilder');

            if (!builderState.length) {
                $builder.html(
                    '<div class="text-muted">Form mahasiswa belum dibuat. Pilih mahasiswa lalu klik <strong>Siapkan Form Kelas dan Nilai</strong>.</div>'
                );
                syncStudentsPayloadFromBuilder();
                return;
            }

            const html = builderState.map((student, studentIndex) => {
                const classOptions = getClassOptionsForStudent(student);
                const courseRows = student.courses.map((course, courseIndex) => `
                    <div class="historical-course-row mt-2" data-student-index="${studentIndex}" data-course-index="${courseIndex}">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small">Kelas Kuliah Historis</label>
                                <select class="form-select form-select-sm builder-course-field" data-field="id_kelas_kuliah">
                                    <option value="">Pilih kelas</option>
                                    ${classOptions.map((option) => `
                                                                            <option value="${option.id}" ${course.id_kelas_kuliah === option.id ? 'selected' : ''}>
                                                                                ${option.label}
                                                                            </option>
                                                                        `).join('')}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Nilai Akhir</label>
                                <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm builder-course-field" data-field="nilai_akhir" value="${course.nilai_akhir ?? ''}" placeholder="0-100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Catatan</label>
                                <input type="text" class="form-control form-control-sm builder-course-field" data-field="catatan" value="${course.catatan ?? ''}" placeholder="Opsional">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-course-row-btn">Hapus</button>
                            </div>
                        </div>
                    </div>
                `).join('');

                return `
                    <div class="historical-builder-card" data-student-index="${studentIndex}">
                        <div class="historical-builder-header d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold">${student.nama_mahasiswa}</div>
                                <div class="small text-muted">${student.nim || student.id_mahasiswa}</div>
                                <div class="small text-muted">${student.id_prodi ? 'Prodi terdeteksi untuk opsi kelas historis' : 'Prodi belum diketahui, opsi kelas mungkin kosong'}</div>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-student-builder-btn">Hapus Mahasiswa</button>
                        </div>
                        <div class="historical-builder-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small text-muted">Isi daftar kelas yang diambil mahasiswa ini pada semester historis beserta nilai akhirnya. Sistem akan mengonversi nilai menjadi huruf, mutu, dan status kelulusan secara otomatis.</div>
                                <button type="button" class="btn btn-outline-primary btn-sm add-course-row-btn">Tambah Mata Kuliah</button>
                            </div>
                            ${courseRows}
                        </div>
                    </div>
                `;
            }).join('');

            $builder.html(html);
            syncStudentsPayloadFromBuilder();
        }

        function getClassOptionsForStudent(student) {
            const key = `${$('#historicalSemesterId').val() || ''}:${student.id_prodi || ''}`;
            return classOptionsCache[key] ?? [];
        }

        function loadHistoricalClasses(idProdi) {
            const semesterId = $('#historicalSemesterId').val();

            if (!semesterId || !idProdi) {
                return $.Deferred().resolve([]);
            }

            const cacheKey = `${semesterId}:${idProdi}`;
            if (classOptionsCache[cacheKey]) {
                return $.Deferred().resolve(classOptionsCache[cacheKey]);
            }

            return $.get('{{ route('akademik.riwayat-studi.classes') }}', {
                id_semester: semesterId,
                id_prodi: idProdi
            }).then(function(response) {
                const items = (response.data ?? []).map((item) => ({
                    id: item.id,
                    label: `${item.mata_kuliah?.kode_mk ?? '-'} - ${item.mata_kuliah?.nama_mk ?? 'Mata Kuliah'} | ${item.nama_kelas ?? 'Kelas'}`
                }));

                classOptionsCache[cacheKey] = items;
                return items;
            });
        }

        function syncAcademicYearDisplay() {
            const semesterId = $('#historicalSemesterId').val();
            const semester = semesterIndex[semesterId];

            if (!semester) {
                $('#historicalAcademicYearDisplay').val('');
                return;
            }

            $('#historicalAcademicYearDisplay').val(semester.id_tahun_akademik);
        }

        $('#loadEligibleBtn').on('click', function() {
            loadStudentsForCurrentAction();
        });

        $('#previewHistoricalBtn').on('click', function() {
            runHistoricalPreview($('#historicalActionSelector').val());
        });

        $('#executeHistoricalBtn').on('click', function() {
            runHistoricalExecute($('#historicalActionSelector').val());
        });

        $('#previewGenerateKhsBtn').on('click', function() {
            runHistoricalPreview('generate_khs');
        });

        $('#executeGenerateKhsBtn').on('click', function() {
            runHistoricalExecute('generate_khs');
        });

        $(document).on('click', '#continueToGenerateKhsBtn', function() {
            prepareGenerateKhsFlow(true);
        });

        $(document).on('click', '.historical-course-row.is-clickable', function() {
            $('.historical-course-row.is-clickable').removeClass('is-active');
            $(this).addClass('is-active');
            const classId = $(this).data('class-id');
            const selectedClass = packageClasses.find((item) => item.id === classId);
            $('#historicalClassSelectionInfo').text(
                selectedClass ?
                `Kelas referensi: ${selectedClass.mata_kuliah?.kode_mk ?? '-'} - ${selectedClass.mata_kuliah?.nama_mk ?? 'Mata Kuliah'} (${selectedClass.nama_kelas ?? 'Kelas'})` :
                'Belum ada kelas referensi yang dipilih.'
            );
        });

        $('#selectAllHistoricalStudents').on('change', function() {
            $('.historical-student-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
        });

        $('#selectAllPreviewReady').on('change', function() {
            $('.historical-preview-checkbox').prop('checked', $(this).is(':checked'));
        });

        $('#generateSelectedStudentsBuilderBtn').on('click', function() {
            const selectedIds = getSelectedEligibleMahasiswaIds();

            if (!selectedIds.length) {
                alert('Pilih minimal satu mahasiswa, lalu siapkan form kelas dan nilainya.');
                return;
            }

            const requests = selectedIds.map((studentId) => {
                const student = ensureBuilderStudent(studentId);
                return loadHistoricalClasses(student.id_prodi);
            });

            $.when.apply($, requests).always(function() {
                renderStudentsBuilder();
            });
        });

        $('#addManualStudentBuilderBtn').on('click', function() {
            const studentId = prompt('Masukkan UUID mahasiswa yang ingin ditambahkan ke form kelas dan nilai:');

            if (!studentId) {
                return;
            }

            ensureBuilderStudent(studentId.trim(), {
                id: studentId.trim(),
                id_prodi: $('#id_prodi').val(),
                nim: '',
                nama_mahasiswa: 'Mahasiswa Manual'
            });

            loadHistoricalClasses($('#id_prodi').val()).always(function() {
                renderStudentsBuilder();
            });
        });

        $(document).on('click', '.add-course-row-btn', function() {
            const studentIndex = $(this).closest('.historical-builder-card').data('student-index');
            builderState[studentIndex].courses.push(createEmptyCourse());
            renderStudentsBuilder();
        });

        $(document).on('click', '.remove-course-row-btn', function() {
            const $row = $(this).closest('.historical-course-row');
            const studentIndex = $row.data('student-index');
            const courseIndex = $row.data('course-index');

            builderState[studentIndex].courses.splice(courseIndex, 1);

            if (!builderState[studentIndex].courses.length) {
                builderState[studentIndex].courses.push(createEmptyCourse());
            }

            renderStudentsBuilder();
        });

        $(document).on('click', '.remove-student-builder-btn', function() {
            const studentIndex = $(this).closest('.historical-builder-card').data('student-index');
            builderState.splice(studentIndex, 1);
            renderStudentsBuilder();
        });

        $(document).on('input change', '.builder-course-field', function() {
            const $row = $(this).closest('.historical-course-row');
            const studentIndex = $row.data('student-index');
            const courseIndex = $row.data('course-index');
            const field = $(this).data('field');

            builderState[studentIndex].courses[courseIndex][field] = field === 'nilai_akhir' ?
                ($(this).val() === '' ? '' : Number($(this).val())) :
                $(this).val();
            syncStudentsPayloadFromBuilder();
        });

        $('#historicalSemesterId').on('change', syncAcademicYearDisplay);
        $('#historicalSemesterId, #id_prodi, #semesterKe, #angkatan').on('change', function() {
            eligibleStudents = [];
            resetBuildQuickAction();
            resetKhsQuickAction();
            renderEligibleStudents([]);
            if (getSelectionActionContext() === 'build_historical_krs') {
                loadPackageClasses();
            } else {
                packageClasses = [];
                renderPackageClasses(packageClasses);
                $('#historicalClassSelectionInfo').text('Referensi kelas tidak diperlukan untuk aksi ini.');
            }
        });
        $('#historicalActionSelector').on('change', function() {
            syncBuildPayloadVisibility();
            resetBuildQuickAction();
            if ($(this).val() === 'build_historical_krs') {
                $('#historicalClassSelectionInfo').text('Belum ada kelas referensi yang dipilih.');
                renderEligibleStudents([]);
            } else {
                $('#historicalClassSelectionInfo').text('Referensi kelas tidak diperlukan untuk aksi ini.');
                if ($('#historicalSemesterId').val()) {
                    loadEligibleStudents();
                }
            }
        });
        syncAcademicYearDisplay();
        syncBuildPayloadVisibility();
        renderStudentsBuilder();
        renderPackageClasses(packageClasses);
    </script>
@endpush
