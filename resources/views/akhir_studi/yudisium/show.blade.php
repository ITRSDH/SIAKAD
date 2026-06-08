@extends('layouts.index')
@section('title', 'Detail Yudisium')

@php
    $mahasiswa = $yudisium['mahasiswa'] ?? [];
    $kurikulum = $yudisium['kurikulum'] ?? [];
    $kurikulumContext = $yudisium['kurikulum_context'] ?? [];
    $kurikulumInduk = $kurikulumContext['kurikulum_induk'] ?? [];
    $strukturOperasional = $kurikulumContext['struktur_operasional'] ?? [];
    $transkrip = $yudisium['transkrip'] ?? [];
    $details = $transkrip['details'] ?? [];
    $status = $yudisium['status'] ?? 'belum_memenuhi';
    $formatKurikulumIndukLabel = static function (array $induk): string {
        return collect([
            $induk['kode_kurikulum'] ?? null,
            $induk['nama_kurikulum'] ?? null,
            $induk['jenis_kurikulum']['kode_jenis'] ?? null,
        ])->filter()->implode(' | ') ?: '-';
    };
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
                <li class="nav-item"><a href="{{ route('yudisium.index') }}">Yudisium</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('yudisium.show', $yudisium['id'] ?? '') }}">Detail</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">{{ $mahasiswa['nama_mahasiswa'] ?? 'Detail Yudisium' }}</h4>
                            <small class="text-muted">{{ $mahasiswa['nim'] ?? '-' }}</small>
                        </div>
                        @include('layouts.partials.status-badge', ['value' => $status])
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Tahun Kurikulum</div>
                                <div>{{ $formatKurikulumIndukLabel($kurikulumInduk) }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Struktur Operasional</div>
                                <div>{{ $strukturOperasional['nama_struktur_mk'] ?? ($strukturOperasional['nama_kurikulum'] ?? ($kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] ?? '-')) }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Target SKS Lulus</div>
                                <div>{{ $yudisium['target_sks_lulus'] ?? ($kurikulum['jumlah_sks_lulus'] ?? '-') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Total SKS Lulus</div>
                                <div>{{ $yudisium['total_sks_lulus'] ?? ($transkrip['total_sks_lulus'] ?? '-') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">IPK</div>
                                <div>{{ $yudisium['ipk'] ?? ($transkrip['ipk'] ?? '-') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Predikat Lulus</div>
                                <div>{{ $yudisium['predikat_lulus'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Yudisium</div>
                                <div>{{ !empty($yudisium['tanggal_yudisium']) ? \Carbon\Carbon::parse($yudisium['tanggal_yudisium'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Catatan</div>
                                <div>{{ $yudisium['catatan'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Ringkasan Transkrip</h4>
                        <small class="text-muted">Snapshot detail hasil studi yang menjadi dasar penilaian yudisium.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Nilai Huruf</th>
                                        <th>Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($details as $item)
                                        <tr>
                                            <td>{{ $item['nama_mata_kuliah'] ?? ($item['mata_kuliah']['nama_mata_kuliah'] ?? '-') }}</td>
                                            <td>{{ $item['nilai_huruf'] ?? '-' }}</td>
                                            <td>{{ $item['bobot_nilai'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Belum ada detail transkrip pada data yudisium ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                        <a href="{{ route('yudisium.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
