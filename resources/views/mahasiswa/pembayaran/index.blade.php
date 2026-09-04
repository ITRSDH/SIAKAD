@extends('layouts.index')
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

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRiwayat{{ $bill->id }}">
                            <i class="fas fa-history me-1"></i> Riwayat Pembayaran ({{ $bill->transaksi->count() }})
                        </button>

                        @if(($bill->status ?? '') === 'lunas')
                        <button class="btn btn-success" disabled>
                            <i class="fas fa-check-circle"></i> Tagihan Lunas
                        </button>
                        @elseif($adaMenunggu)
                        <button class="btn btn-info" disabled>
                            <i class="fas fa-hourglass-half"></i> Menunggu Verifikasi
                        </button>
                        @else
                        <a href="{{ route('student.pembayaran.create', $bill->id) }}" class="btn btn-primary">
                            Bayar Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Riwayat Pembayaran untuk Tagihan {{ $bill->id }} -->
        <div class="modal fade" id="modalRiwayat{{ $bill->id }}" tabindex="-1" aria-labelledby="modalRiwayatLabel{{ $bill->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <div>
                            <h5 class="modal-title fw-bold" id="modalRiwayatLabel{{ $bill->id }}">
                                <i class="fas fa-history text-primary me-2"></i>Riwayat Pembayaran
                            </h5>
                            <small class="text-muted">{{ $bill->biaya->nama ?? 'Tagihan' }} (ID: #{{ str_pad($bill->id ?? 0, 5, '0', STR_PAD_LEFT) }})</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Ringkasan Tagihan -->
                        <div class="row g-2 mb-3">
                            <div class="col-sm-4">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block">Total Tagihan</small>
                                    <span class="fw-bold">Rp {{ number_format($bill->total_tagihan ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block">Telah Dibayar (Disetujui)</small>
                                    <span class="fw-bold text-success">Rp {{ number_format($bill->jumlah_dibayar ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block">Sisa Tagihan</small>
                                    <span class="fw-bold text-danger">Rp {{ number_format($bill->sisa_pembayaran ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Transaksi -->
                        @if($bill->transaksi->isEmpty())
                            <div class="alert alert-secondary text-center my-3 py-4">
                                <i class="fas fa-receipt fa-2x mb-2 text-muted d-block"></i>
                                Belum ada riwayat pembayaran yang dikirimkan untuk tagihan ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="text-nowrap text-center">
                                            <th>#</th>
                                            <th>No. Transaksi</th>
                                            <th>Tgl Pembayaran</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Catatan / Keterangan</th>
                                            <th>Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->transaksi as $index => $trx)
                                        @php $trx = (object) $trx; @endphp
                                        <tr>
                                            <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                            <td class="fw-semibold text-nowrap">
                                                {{ $trx->no_transaksi ?? '-' }}
                                            </td>
                                            <td class="text-nowrap small">
                                                @if(!empty($trx->tanggal_pembayaran))
                                                    {{ \Carbon\Carbon::parse($trx->tanggal_pembayaran)->translatedFormat('d M Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-nowrap fw-bold text-end">
                                                Rp {{ number_format($trx->jumlah ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center text-nowrap">
                                                @if(($trx->status ?? '') === 'disetujui')
                                                    <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                                @elseif(($trx->status ?? '') === 'ditolak')
                                                    <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                                @elseif(($trx->status ?? '') === 'menunggu')
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Menunggu Verifikasi</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">{{ ucfirst($trx->status ?? '-') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($trx->catatan))
                                                    <div class="small text-break">{{ $trx->catatan }}</div>
                                                @else
                                                    <span class="text-muted small italic">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center text-nowrap">
                                                @if(!empty($trx->bukti_pembayaran))
                                                    @php
                                                        $apiUrl = config('api.keuangan_url', 'http://localhost:8001');
                                                        $buktiUrl = rtrim($apiUrl, '/') . '/storage/' . $trx->bukti_pembayaran;
                                                    @endphp
                                                    <a href="{{ $buktiUrl }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
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