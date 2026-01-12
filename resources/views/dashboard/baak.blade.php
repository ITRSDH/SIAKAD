@extends('layouts.index')
@section('title', 'Dashboard BAAK')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard BAAK</h3>
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
                    <a href="#">Dashboard BAAK</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Total Pembayaran -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-file-invoice-dollar"></i> <!-- Icon untuk Total Pembayaran -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pembayaran</p>
                                    <h4 class="card-title">{{ $dashboard_baak['total_pembayaran'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Jenis Pembayaran -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-list-alt"></i> <!-- Icon untuk Jenis Pembayaran -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Jenis Pembayaran</p>
                                    <h4 class="card-title">{{ $dashboard_baak['total_jenis_pembayaran'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran Lunas -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i> <!-- Icon untuk Pembayaran Lunas -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Pembayaran Lunas</p>
                                    <h4 class="card-title">{{ $dashboard_baak['total_pembayaran_lunas'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran Belum Lunas -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-exclamation-triangle"></i> <!-- Icon untuk Pembayaran Belum Lunas -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Belum Lunas</p>
                                    <h4 class="card-title">{{ $dashboard_baak['total_pembayaran_belum_lunas'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Ringkasan Pembayaran</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="progress-container">
                                    <span class="progress-badge">Tingkat Pelunasan</span>
                                    @php
                                        $total = $dashboard_baak['total_pembayaran'] ?? 0;
                                        $lunas = $dashboard_baak['total_pembayaran_lunas'] ?? 0;
                                        $persentase = $total > 0 ? round(($lunas / $total) * 100, 2) : 0;
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $persentase }}%"
                                            aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $persentase }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-block">
                                    <canvas id="paymentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentChart').getContext('2d');

            const totalLunas = {{ $dashboard_baak['total_pembayaran_lunas'] ?? 0 }};
            const totalBelumLunas = {{ $dashboard_baak['total_pembayaran_belum_lunas'] ?? 0 }};

            const paymentChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Lunas', 'Belum Lunas'],
                    datasets: [{
                        data: [totalLunas, totalBelumLunas],
                        backgroundColor: [
                            '#28a745', // Hijau untuk Lunas
                            '#ffc107' // Kuning untuk Belum Lunas
                        ],
                        borderColor: [
                            '#28a745',
                            '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Status Pembayaran'
                        }
                    }
                }
            });
        });
    </script>
@endpush
