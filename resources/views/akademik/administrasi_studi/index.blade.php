@extends('layouts.index')
@section('title', 'Administrasi Studi Mahasiswa')

@php
    $summaryCards = $workspaceSummary['summary_cards'] ?? [];
@endphp

@push('styles-custom')
    <link rel="stylesheet" href="{{ asset('css/admin-studi.css') }}">
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Administrasi Studi Mahasiswa</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi
                        Mahasiswa</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card study-landing-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h2 class="fw-bold mb-1">Migrasi perjalanan akademik mahasiswa lama</h2>
                        <p class="text-muted mb-0">Pilih satu proses untuk dimulai — setiap langkah ada di halaman masing-masing.</p>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="row g-3">
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('akademik.administrasi-studi.krs') }}" class="study-landing-step text-decoration-none">
                    <div class="study-landing-step-no">1</div>
                    <div class="study-landing-step-icon"><i class="fas fa-book-open"></i></div>
                    <div class="fw-semibold">Daftarkan KRS</div>
                    <div class="small text-muted">Bentuk KRS historis per semester untuk mahasiswa lama.</div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('akademik.administrasi-studi.nilai') }}" class="study-landing-step text-decoration-none">
                    <div class="study-landing-step-no">2</div>
                    <div class="study-landing-step-icon"><i class="fas fa-file-import"></i></div>
                    <div class="fw-semibold">Input Nilai</div>
                    <div class="small text-muted">Isi nilai per kelas atau import dari file Excel.</div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('akademik.administrasi-studi.khs') }}" class="study-landing-step text-decoration-none">
                    <div class="study-landing-step-no">3</div>
                    <div class="study-landing-step-icon"><i class="fas fa-file-invoice"></i></div>
                    <div class="fw-semibold">Generate KHS</div>
                    <div class="small text-muted">Buat KHS dari KRS yang sudah bernilai dan dikunci.</div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('akademik.administrasi-studi.riwayat') }}" class="study-landing-step text-decoration-none">
                    <div class="study-landing-step-no">4</div>
                    <div class="study-landing-step-icon"><i class="fas fa-history"></i></div>
                    <div class="fw-semibold">Koreksi & Finalisasi</div>
                    <div class="small text-muted">Buka ulang, reset, atau finalisasi ulang riwayat studi.</div>
                </a>
            </div>
        </div>
    </div>
@endsection
