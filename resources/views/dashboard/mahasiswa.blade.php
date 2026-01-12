@extends('layouts.index')
@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard Mahasiswa</h3>
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
                    <a href="#">Dashboard Mahasiswa</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Total KRS -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-clipboard-list"></i> <!-- Icon untuk KRS -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total KRS</p>
                                    <h4 class="card-title">{{ $dashboard_mahasiswa['total_krs'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-money-bill-wave"></i> <!-- Icon untuk Pembayaran -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pembayaran</p>
                                    <h4 class="card-title">{{ $dashboard_mahasiswa['total_pembayaran'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Presensi Hadir -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i> <!-- Icon untuk Presensi Hadir -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Presensi Hadir</p>
                                    <h4 class="card-title">{{ $dashboard_mahasiswa['total_presensi_hadir'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IPK Terakhir -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-star"></i> <!-- Icon untuk IPK -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">IPK Terakhir</p>
                                    <h4 class="card-title">
                                        {{ $dashboard_mahasiswa['khs_terakhir']['ipk'] ?? '0.00' }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Summary -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Informasi Akademik</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h5>Semester Saat Ini</h5>
                                    <p class="text-muted">
                                        {{ $dashboard_mahasiswa['khs_terakhir']['id_semester'] ?? '-' }}
                                    </p>
                                </div>
                                <div class="info-card mt-3">
                                    <h5>Total SKS</h5>
                                    <p class="text-muted">
                                        {{ $dashboard_mahasiswa['khs_terakhir']['total_sks'] ?? '0' }} SKS
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h5>Status Pembayaran</h5>
                                    <p class="text-muted">
                                        @php
                                            $totalPembayaran = $dashboard_mahasiswa['total_pembayaran'] ?? 0;
                                            $statusPembayaran = $totalPembayaran > 0 ? 'Lunas' : 'Belum Lunas';
                                        @endphp
                                        <span class="badge badge-{{ $totalPembayaran > 0 ? 'success' : 'danger' }}">
                                            {{ $statusPembayaran }}
                                        </span>
                                    </p>
                                </div>
                                <div class="info-card mt-3">
                                    <h5>Persentase Kehadiran</h5>
                                    <p class="text-muted">
                                        @php
                                            $totalPresensi = $dashboard_mahasiswa['total_presensi_hadir'] ?? 0;
                                            $persentaseKehadiran =
                                                $totalPresensi > 0
                                                    ? round(($totalPresensi / max($totalPresensi, 1)) * 100, 2)
                                                    : 0;
                                        @endphp
                                        {{ $persentaseKehadiran }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Kehadiran</div>
                    </div>
                    <div class="card-body">
                        <canvas id="kehadiranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
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
                                        <th scope="col">Aktivitas</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Detail</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Kartu Rencana Studi (KRS)</td>
                                        <td>{{ now()->format('d M Y') }}</td>
                                        <td>{{ $dashboard_mahasiswa['total_krs'] ?? 0 }} kali</td>
                                        <td>
                                            <span class="badge badge-success">Disetujui</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Pembayaran Semester</td>
                                        <td>{{ now()->subDays(2)->format('d M Y') }}</td>
                                        <td>{{ $dashboard_mahasiswa['total_pembayaran'] ?? 0 }} pembayaran</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ ($dashboard_mahasiswa['total_pembayaran'] ?? 0) > 0 ? 'success' : 'danger' }}">
                                                {{ ($dashboard_mahasiswa['total_pembayaran'] ?? 0) > 0 ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Presensi Kuliah</td>
                                        <td>{{ now()->subDays(1)->format('d M Y') }}</td>
                                        <td>{{ $dashboard_mahasiswa['total_presensi_hadir'] ?? 0 }} hadir</td>
                                        <td>
                                            <span class="badge badge-info">Aktif</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Kartu Hasil Studi</td>
                                        <td>{{ $dashboard_mahasiswa['khs_terakhir']['created_at'] ?? now()->subWeek()->format('d M Y') }}
                                        </td>
                                        <td>IPK: {{ $dashboard_mahasiswa['khs_terakhir']['ipk'] ?? '0.00' }}</td>
                                        <td>
                                            <span class="badge badge-warning">Terakhir</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Akses Cepat</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-clipboard-list mr-2"></i>KRS
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-book mr-2"></i>KHS
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Pembayaran
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-chart-line mr-2"></i>Nilai
                                </a>
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
            const ctx = document.getElementById('kehadiranChart').getContext('2d');

            const totalPresensi = {{ $dashboard_mahasiswa['total_presensi_hadir'] ?? 0 }};
            const totalMungkin = 100; // Asumsi total pertemuan dalam semester

            const kehadiranChart = new Chart(ctx, {
                type: 'doughnut',
                {
                    labels: ['Hadir', 'Tidak Hadir'],
                    datasets: [{
                        data: [totalPresensi, totalMungkin - totalPresensi],
                        backgroundColor: [
                            '#28a745', // Hijau untuk Hadir
                            '#dc3545' // Merah untuk Tidak Hadir
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
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Statistik Kehadiran ({{ $dashboard_mahasiswa['total_presensi_hadir'] ?? 0 }}/{{ 100 }})'
                        }
                    }
                }
            });
        });
    </script>
@endpush
