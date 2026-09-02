@extends('layouts.index')
@section('title', 'Generate KHS — Administrasi Studi')

@php
    $semesterOptions = $filters['semester'] ?? [];
    $prodiOptions = $filters['prodi'] ?? [];
@endphp

@push('styles-custom')
    <link rel="stylesheet" href="{{ asset('css/admin-studi.css') }}">
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Generate KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item active">Generate KHS</li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        {{-- Filter semester (minimal) --}}
        <div class="card study-shell mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="studySemesterId">
                            <option value="">Pilih semester...</option>
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
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="loadReadyKhsBtn">
                            <i class="fas fa-list-check me-1"></i> Muat Mahasiswa Siap
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel mahasiswa siap --}}
        <div class="card study-shell">
            <div class="card-header border-0 pt-4 px-4 pb-1">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-semibold">Mahasiswa untuk Generate KHS</div>
                        <div class="small text-muted" id="khsListHelper">
                            Pilih semester lalu muat daftar. Centang mahasiswa yang siap, isi IPK manual (opsional untuk semester &gt; 1),
                            lalu satu tombol Generate.
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="khsBatchGenerateBtn" disabled>
                        <i class="fas fa-bolt me-1"></i> Generate KHS (0)
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="study-filter-strip mb-3">
                    <div class="summary" id="khsBatchSearchSummary">
                        Cari mahasiswa berdasarkan <strong>nama</strong> atau <strong>NIM</strong>.
                    </div>
                    <div class="search-input-wrap">
                        <i class="fas fa-search search-input-icon"></i>
                        <input type="text" class="form-control form-control-sm" id="khsBatchSearch"
                            placeholder="Cari nama / NIM mahasiswa...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 42px;"><input type="checkbox" id="khsBatchSelectAll"></th>
                                <th>Mahasiswa</th>
                                <th>Status</th>
                                <th>Final Detail</th>
                                <th>KHS Saat Ini</th>
                                <th>IPK Manual</th>
                                <th style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="readyKhsTableBody">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data yang dimuat.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="study-pager" id="khsBatchPager">
                    <div class="info" id="khsBatchPagerInfo">Belum ada halaman untuk ditampilkan.</div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="khsBatchPrevPage">
                            <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="khsBatchNextPage">
                            Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
                <div id="khsBatchResult" class="d-grid gap-2 mt-3">
                    {{-- Hasil akumulasi generate --}}
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
            readyKhsRoute: '{{ route('akademik.administrasi-studi.ready-khs') }}',
            generateKhsExecuteRoute: '{{ route('akademik.administrasi-studi.generate-khs.execute') }}',
            khsUrl: '{{ url('akademik/khs') }}',
        };
    </script>
    <script src="{{ asset('js/admin-studi-common.js') }}?v={{ filemtime(public_path('js/admin-studi-common.js')) }}"></script>
    <script src="{{ asset('js/admin-studi-khs.js') }}?v={{ filemtime(public_path('js/admin-studi-khs.js')) }}"></script>
@endpush
