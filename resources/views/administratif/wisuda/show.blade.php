@extends('layouts.index')
@section('title', 'Detail Periode Wisuda')

@php
    $status = $periode['status'] ?? 'draft';
    $pesertaCount = count($pesertaWisuda);
    $terverifikasiCount = collect($pesertaWisuda)->where('status', 'terverifikasi')->count();
    $hadirCount = collect($pesertaWisuda)->where('status', 'hadir')->count();
    $validAdministrasiCount = collect($pesertaWisuda)->where('status_validasi_administrasi', 'memenuhi')->count();
@endphp

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Administratif</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('wisuda.periode.index') }}">Wisuda</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('wisuda.periode.show', $periode['id'] ?? '') }}">Detail Periode</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">{{ $periode['nama_periode'] ?? 'Detail Periode Wisuda' }}</h4>
                            <small class="text-muted">{{ $periode['lokasi'] ?? 'Lokasi belum diatur' }}</small>
                        </div>
                        @include('layouts.partials.status-badge', ['value' => $status])
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Mulai Pendaftaran</div>
                                <div>{{ !empty($periode['tanggal_mulai_pendaftaran']) ? \Carbon\Carbon::parse($periode['tanggal_mulai_pendaftaran'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Selesai Pendaftaran</div>
                                <div>{{ !empty($periode['tanggal_selesai_pendaftaran']) ? \Carbon\Carbon::parse($periode['tanggal_selesai_pendaftaran'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Wisuda</div>
                                <div>{{ !empty($periode['tanggal_wisuda']) ? \Carbon\Carbon::parse($periode['tanggal_wisuda'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Jumlah Peserta</div>
                                <div>{{ $pesertaCount }} peserta</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Catatan</div>
                                <div>{{ $periode['catatan'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Peserta Wisuda</h4>
                        <small class="text-muted">Ringkasan peserta terdaftar pada periode ini.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Status Peserta</th>
                                        <th>Administrasi</th>
                                        <th>Kelulusan</th>
                                        <th>Nomor Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesertaWisuda as $peserta)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $peserta['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $peserta['mahasiswa']['nim'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @include('layouts.partials.status-badge', ['value' => $peserta['status'] ?? 'draft'])
                                            </td>
                                            <td>
                                                @include('layouts.partials.status-badge', ['value' => $peserta['status_validasi_administrasi'] ?? 'belum'])
                                            </td>
                                            <td>
                                                <div>{{ ucfirst(str_replace('_', ' ', $peserta['kelulusan']['status'] ?? 'draft')) }}</div>
                                                <small class="text-muted">{{ $peserta['kelulusan']['nomor_ijazah'] ?? 'Ijazah belum ada' }}</small>
                                            </td>
                                            <td>{{ $peserta['nomor_peserta'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada peserta pada periode wisuda ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="fw-semibold text-muted mb-1">Peserta Terverifikasi</div>
                                <div class="display-6 fw-bold">{{ $terverifikasiCount }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="fw-semibold text-muted mb-1">Administrasi Memenuhi</div>
                                <div class="display-6 fw-bold">{{ $validAdministrasiCount }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="fw-semibold text-muted mb-1">Peserta Hadir</div>
                                <div class="display-6 fw-bold">{{ $hadirCount }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-1">Navigasi Cepat</h4>
                            </div>
                            <div class="card-body d-grid gap-2">
                                <a href="{{ route('wisuda.peserta.index', $periode['id'] ?? '') }}" class="btn btn-primary">
                                    <i class="fas fa-users me-1"></i> Kelola Peserta
                                </a>
                                <a href="{{ route('wisuda.periode.index') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
