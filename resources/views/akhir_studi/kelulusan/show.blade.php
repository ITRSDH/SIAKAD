@extends('layouts.index')
@section('title', 'Detail Kelulusan')

@php
    $mahasiswa = $kelulusan['mahasiswa'] ?? [];
    $yudisium = $kelulusan['yudisium'] ?? [];
    $transkrip = $yudisium['transkrip'] ?? [];
    $status = $kelulusan['status'] ?? 'draft';
@endphp

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Akhir Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('kelulusan.index') }}">Kelulusan</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('kelulusan.show', $kelulusan['id'] ?? '') }}">Detail</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">{{ $mahasiswa['nama_mahasiswa'] ?? 'Detail Kelulusan' }}</h4>
                            <small class="text-muted">{{ $mahasiswa['nim'] ?? '-' }}</small>
                        </div>
                        @include('layouts.partials.status-badge', ['value' => $status, 'label' => ucfirst($status)])
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Lulus</div>
                                <div>{{ !empty($kelulusan['tanggal_lulus']) ? \Carbon\Carbon::parse($kelulusan['tanggal_lulus'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Yudisium</div>
                                <div>{{ !empty($yudisium['tanggal_yudisium']) ? \Carbon\Carbon::parse($yudisium['tanggal_yudisium'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Nomor SK</div>
                                <div>{{ $kelulusan['nomor_sk'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Nomor Ijazah</div>
                                <div>{{ $kelulusan['nomor_ijazah'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Status Yudisium</div>
                                <div>@include('layouts.partials.status-badge', ['value' => $yudisium['status'] ?? 'draft'])</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Predikat Lulus</div>
                                <div>{{ $yudisium['predikat_lulus'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Total SKS Lulus</div>
                                <div>{{ $transkrip['total_sks_lulus'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">IPK</div>
                                <div>{{ $transkrip['ipk'] ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Catatan</div>
                                <div>{{ $kelulusan['catatan'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Navigasi Cepat</h4>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('kelulusan.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
