@extends('layouts.index')
@section('title', 'Koreksi & Finalisasi Riwayat — Administrasi Studi')

@php
    $summaryCards = $workspaceSummary['summary_cards'] ?? [];
    $semesterOptions = $filters['semester'] ?? [];
    $prodiOptions = $filters['prodi'] ?? [];
    $semesterKeOptions = $filters['semester_ke_options'] ?? [];
@endphp

@push('styles-custom')
    <link rel="stylesheet" href="{{ asset('css/admin-studi.css') }}">
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Koreksi & Finalisasi Riwayat Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item active">Koreksi & Finalisasi</li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        {{-- Filter konteks bersama --}}
        <div class="card study-shell mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select select2" id="studySemesterId">
                            <option value="">Semua semester</option>
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
                    <div class="col-md-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select select2" id="studyProdiId">
                            <option value="">Semua prodi</option>
                            @foreach ($prodiOptions as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] ?? 'Program Studi' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Angkatan</label>
                        <input type="number" id="studyAngkatan" class="form-control" placeholder="Contoh 2024">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semester Ke</label>
                        <select class="form-select select2" id="studySemesterKe">
                            <option value="">Semua</option>
                            @foreach ($semesterKeOptions as $item)
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="studyRiwayatLoadBtn">
                            <i class="fas fa-users me-1"></i> Tampilkan Mahasiswa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan konteks --}}
        <div class="row g-3 mb-4" id="studySummaryCards">
            @foreach ($summaryCards as $card)
                <div class="col-md-6 col-xl-3">
                    <div class="study-stat">
                        <div class="small text-muted text-uppercase mb-2">{{ $card['label'] ?? '-' }}</div>
                        <div class="study-stat-value text-{{ $card['tone'] ?? 'primary' }}">{{ $card['value'] ?? 0 }}
                        </div>
                        <div class="small text-muted mt-2">{{ $card['description'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="study-bridge">
                    <div class="fw-semibold mb-2">Koreksi & Finalisasi Riwayat Studi</div>
                    <div class="text-muted small mb-3">
                        Halaman ini dipakai untuk membuka ulang data, mengosongkan isi riwayat,
                        memfinalisasi ulang, atau membuat KHS dari data semester lampau yang sudah ada.
                    </div>
                    <div class="small text-muted mb-2">Tindakan yang tersedia:</div>
                    <div class="small mb-1">1. Buka ulang riwayat</div>
                    <div class="small mb-1">2. Reset isi riwayat</div>
                    <div class="small mb-1">3. Finalisasi ulang</div>
                    <div class="small mb-3">4. Generate KHS historis</div>
                    <a href="{{ route('akademik.administrasi-studi.batches') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-clock-rotate-left me-1"></i> Lihat Riwayat Bila Perlu
                    </a>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="study-native-panel mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Pilih Tindakan</label>
                            <select class="form-select" id="studyHistoricalMutationAction">
                                <option value="reopen_historical_krs">Buka Ulang Riwayat</option>
                                <option value="refinalize_historical_krs">Finalisasi Ulang Riwayat</option>
                                <option value="reset_historical_krs">Reset Isi Riwayat</option>
                                <option value="generate_khs">Generate KHS Historis</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Catatan Proses</label>
                            <input type="text" class="form-control" id="studyHistoricalMutationNotes"
                                placeholder="Opsional, misalnya untuk koreksi nilai semester lampau">
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    id="studyHistoricalPreviewMutationBtn">
                                    <i class="fas fa-magnifying-glass me-1"></i> Lihat Pratinjau
                                </button>
                                <button type="button" class="btn btn-success btn-sm"
                                    id="studyHistoricalExecuteMutationBtn" disabled>
                                    <i class="fas fa-play me-1"></i> Jalankan Proses
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted mt-3" id="studyHistoricalMutationSelectionSummary">
                        Belum ada mahasiswa yang dipilih. Klik <strong>Tampilkan Mahasiswa</strong> untuk memuat daftar.
                    </div>
                    <div class="mt-3">
                        <div class="fw-semibold mb-2">Pilih mahasiswa</div>
                        <div id="studyHistoricalMutationSelectionList" class="study-riwayat-select-list">
                            <div class="text-muted small">Belum ada mahasiswa yang dimuat.</div>
                        </div>
                    </div>
                    <div class="study-soft-box mt-3 d-none" id="studyHistoricalMutationManualIpkPanel">
                        <div class="fw-semibold mb-2">IPK Manual untuk Generate KHS Historis</div>
                        <div class="small text-muted mb-3">
                            Semester 1 akan mengikuti IPS. Jika Anda memilih aksi
                            <code>Generate KHS Historis</code> untuk semester berikutnya, isi IPK manual per mahasiswa di sini.
                        </div>
                        <div id="studyHistoricalMutationManualIpkList" class="d-grid gap-2">
                            <div class="text-muted">Belum ada mahasiswa yang dipilih.</div>
                        </div>
                    </div>
                </div>
                <div class="study-native-panel">
                    <div class="fw-semibold mb-3">Hasil Preview Riwayat Studi</div>
                    <div id="studyHistoricalMutationPreviewResults" class="d-grid gap-2">
                        <div class="text-muted">Belum ada hasil pratinjau tindakan yang ditampilkan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.studyConfig = {
            csrfToken: '{{ csrf_token() }}',
            summaryRoute: '{{ route('akademik.administrasi-studi.summary') }}',
            historicalEligibleRoute: '{{ route('akademik.administrasi-studi.historical.eligible') }}',
            historicalPreviewRoute: '{{ route('akademik.administrasi-studi.historical.preview') }}',
            historicalExecuteRoute: '{{ route('akademik.administrasi-studi.historical.execute') }}',
            khsUrl: '{{ url('akademik/khs') }}',
        };
    </script>
    <script src="{{ asset('js/admin-studi-common.js') }}"></script>
    <script src="{{ asset('js/admin-studi-riwayat.js') }}"></script>
@endpush
