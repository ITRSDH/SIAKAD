@extends('admin.layouts.index')
@section('title', 'Pembayaran Mahasiswa')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pembayaran Mahasiswa</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.pembayaran.index') }}">Pembayaran Mahasiswa</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.pembayaran.index') }}">Tagihan Saya</a>
            </li>
        </ul>
    </div>

    <div class="row">
        @forelse($bills as $bill)
        @php
        // Convert array to object for easier access
        $bill = (object) $bill;
        $bill->biaya = (object) ($bill->biaya ?? []);
        $bill->transaksi = collect($bill->transaksi ?? []);
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">{{ $bill->biaya->nama ?? 'N/A' }}</h4>
                        <small class="text-muted">ID: #{{ str_pad($bill->id ?? 0, 5, '0', STR_PAD_LEFT) }}</small>
                    </div>
                    <div>
                        @if(($bill->status ?? '') === 'lunas')
                        <span class="badge badge-success">Lunas</span>
                        @elseif(($bill->status ?? '') === 'menunggu')
                        <span class="badge badge-info">Verifikasi</span>
                        @elseif(($bill->status ?? '') === 'sebagian')
                        <span class="badge badge-warning">Menunggak</span>
                        @else
                        <span class="badge badge-danger">Belum Bayar</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Tagihan</span>
                            <span class="fw-bold">Rp {{ number_format($bill->total_tagihan ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Telah Dibayar</span>
                            <span class="fw-bold text-success">Rp {{ number_format($bill->jumlah_dibayar ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Sisa Bayar</span>
                            <span class="fw-bold text-danger fs-5">Rp {{ number_format($bill->sisa_pembayaran ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @php
                    $adaMenunggu = $bill->transaksi->contains('status', 'menunggu');
                    @endphp

                    @if(($bill->status ?? '') === 'lunas')
                    <button class="btn btn-success btn-block" disabled>
                        <i class="fas fa-check-circle"></i> Tagihan Lunas
                    </button>
                    @elseif($adaMenunggu)
                    <button class="btn btn-info btn-block" disabled>
                        <i class="fas fa-hourglass-half"></i> Menunggu Verifikasi
                    </button>
                    @else
                    <a href="{{ route('student.pembayaran.create', $bill->id) }}" class="btn btn-primary btn-block">
                        Bayar Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada tagihan yang ditemukan.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection