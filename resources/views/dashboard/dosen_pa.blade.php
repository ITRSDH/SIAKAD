@extends('layouts.index')
@section('title', 'Dashboard Dosen PA')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard Dosen PA</h3>
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
                    <a href="#">Dashboard Dosen PA</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Total Mahasiswa Bimbingan -->
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-user-graduate"></i> <!-- Icon untuk Mahasiswa Bimbingan -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Mahasiswa Bimbingan</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pa['total_mahasiswa_bimbingan'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total KRS Disetujui -->
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i> <!-- Icon untuk KRS Disetujui -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">KRS Disetujui</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pa['total_krs_disetujui'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Perwalian -->
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-users"></i> <!-- Icon untuk Perwalian -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Perwalian</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pa['total_perwalian'] ?? 0 }}</h4>
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
                        <div class="card-title">Statistik Perwalian</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="progress-container">
                                    <span class="progress-badge">Rasio KRS Disetujui</span>
                                    @php
                                        $totalBimbingan = $dashboard_dosen_pa['total_mahasiswa_bimbingan'] ?? 0;
                                        $totalKrs = $dashboard_dosen_pa['total_krs_disetujui'] ?? 0;
                                        $rasio =
                                            $totalBimbingan > 0 ? round(($totalKrs / $totalBimbingan) * 100, 2) : 0;
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $rasio }}%" aria-valuenow="{{ $rasio }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ $rasio }}%
                                        </div>
                                    </div>
                                    <small>{{ $totalKrs }} dari {{ $totalBimbingan }} mahasiswa telah disetujui
                                        KRS-nya</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-block">
                                    <canvas id="perwalianChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Aktivitas Terbaru</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Mahasiswa</th>
                                        <th scope="col">NIM</th>
                                        <th scope="col">Status KRS</th>
                                        <th scope="col">Tanggal Perwalian</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i <= min(5, $dashboard_dosen_pa['total_mahasiswa_bimbingan'] ?? 0); $i++)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>Mahasiswa {{ $i }}</td>
                                            <td>1234567{{ $i }}</td>
                                            <td>
                                                <span class="badge badge-success">Disetujui</span>
                                            </td>
                                            <td>{{ now()->subDays($i)->format('d M Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Detail</button>
                                            </td>
                                        </tr>
                                    @endfor
                                    @if (($dashboard_dosen_pa['total_mahasiswa_bimbingan'] ?? 0) == 0)
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data mahasiswa bimbingan</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
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
            const ctx = document.getElementById('perwalianChart').getContext('2d');

            const totalBimbingan = {{ $dashboard_dosen_pa['total_mahasiswa_bimbingan'] ?? 0 }};
            const totalKrs = {{ $dashboard_dosen_pa['total_krs_disetujui'] ?? 0 }};
            const totalBelum = totalBimbingan - totalKrs;

            const perwalianChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['KRS Disetujui', 'KRS Belum Disetujui'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [totalKrs, totalBelum],
                        backgroundColor: [
                            '#28a745', // Hijau untuk Disetujui
                            '#dc3545' // Merah untuk Belum Disetujui
                        ],
                        borderColor: [
                            '#28a745',
                            '#dc3545'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Statistik KRS Mahasiswa Bimbingan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
