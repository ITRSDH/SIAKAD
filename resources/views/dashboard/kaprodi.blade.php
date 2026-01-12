@extends('layouts.index')
@section('title', 'Dashboard Kaprodi')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard Kaprodi</h3>
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
                    <a href="#">Dashboard Kaprodi</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Total Dosen -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-chalkboard-teacher"></i> <!-- Icon untuk Dosen -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Dosen</p>
                                    <h4 class="card-title">{{ $dashboard_kaprodi['total_dosen'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Mahasiswa -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-graduation-cap"></i> <!-- Icon untuk Mahasiswa -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Mahasiswa</p>
                                    <h4 class="card-title">{{ $dashboard_kaprodi['total_mahasiswa'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kelas Mata Kuliah -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-book-open"></i> <!-- Icon untuk Kelas MK -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kelas MK</p>
                                    <h4 class="card-title">{{ $dashboard_kaprodi['total_kelas_mk'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Input -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-star-half-alt"></i> <!-- Icon untuk Nilai -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Nilai Input</p>
                                    <h4 class="card-title">{{ $dashboard_kaprodi['total_nilai_input'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bars and Charts -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Prodi</div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h4>{{ $dashboard_kaprodi['total_mahasiswa'] ?? 0 }}</h4>
                                <p class="text-muted">Mahasiswa</p>
                            </div>
                            <div class="col-6">
                                <h4>{{ $dashboard_kaprodi['total_dosen'] ?? 0 }}</h4>
                                <p class="text-muted">Dosen</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="progress-container">
                                <span class="progress-badge">Rasio Input Nilai</span>
                                @php
                                    $totalKelas = $dashboard_kaprodi['total_kelas_mk'] ?? 0;
                                    $totalNilai = $dashboard_kaprodi['total_nilai_input'] ?? 0;
                                    $rasio = $totalKelas > 0 ? round(($totalNilai / $totalKelas) * 100, 2) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $rasio }}%" aria-valuenow="{{ $rasio }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ $rasio }}%
                                    </div>
                                </div>
                                <small>{{ $totalNilai }} dari {{ $totalKelas }} kelas telah diinput nilainya</small>
                            </div>

                            <div class="progress-container mt-3">
                                <span class="progress-badge">Rasio Dosen:Mahasiswa</span>
                                @php
                                    $totalDosen = $dashboard_kaprodi['total_dosen'] ?? 0;
                                    $totalMahasiswa = $dashboard_kaprodi['total_mahasiswa'] ?? 0;
                                    $rasioDosen = $totalDosen > 0 ? round($totalMahasiswa / $totalDosen, 2) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ min($rasioDosen * 5, 100) }}%"
                                        aria-valuenow="{{ min($rasioDosen * 5, 100) }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $rasioDosen }}:1
                                    </div>
                                </div>
                                <small>Rata-rata {{ $rasioDosen }} mahasiswa per dosen</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Akademik</div>
                    </div>
                    <div class="card-body">
                        <canvas id="statistikAkademikChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Overview Table -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Overview Akademik</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Total Mahasiswa</td>
                                        <td><strong>{{ $dashboard_kaprodi['total_mahasiswa'] ?? 0 }}</strong></td>
                                        <td>Jumlah mahasiswa aktif di prodi</td>
                                        <td>
                                            <span class="badge badge-success">Normal</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Total Dosen</td>
                                        <td><strong>{{ $dashboard_kaprodi['total_dosen'] ?? 0 }}</strong></td>
                                        <td>Jumlah dosen aktif di prodi</td>
                                        <td>
                                            <span class="badge badge-info">Normal</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Kelas MK</td>
                                        <td><strong>{{ $dashboard_kaprodi['total_kelas_mk'] ?? 0 }}</strong></td>
                                        <td>Jumlah kelas mata kuliah aktif</td>
                                        <td>
                                            <span class="badge badge-warning">Perlu Diperhatikan</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Lihat</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Nilai Input</td>
                                        <td><strong>{{ $dashboard_kaprodi['total_nilai_input'] ?? 0 }}</strong></td>
                                        <td>Jumlah nilai yang telah diinput</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ ($dashboard_kaprodi['total_kelas_mk'] ?? 0) > 0 && ($dashboard_kaprodi['total_nilai_input'] ?? 0) >= ($dashboard_kaprodi['total_kelas_mk'] ?? 0) ? 'success' : 'danger' }}">
                                                {{ ($dashboard_kaprodi['total_kelas_mk'] ?? 0) > 0 && ($dashboard_kaprodi['total_nilai_input'] ?? 0) >= ($dashboard_kaprodi['total_kelas_mk'] ?? 0) ? 'Selesai' : 'Belum Selesai' }}
                                            </span>
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

        <!-- Quick Actions -->
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
                                    <i class="fas fa-users mr-2"></i>Data Dosen
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-graduation-cap mr-2"></i>Data Mahasiswa
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-book mr-2"></i>Kurikulum
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <a href="#" class="btn btn-light btn-block mb-2">
                                    <i class="fas fa-chart-line mr-2"></i>Laporan
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
            const ctx = document.getElementById('statistikAkademikChart').getContext('2d');

            const totalDosen = {{ $dashboard_kaprodi['total_dosen'] ?? 0 }};
            const totalMahasiswa = {{ $dashboard_kaprodi['total_mahasiswa'] ?? 0 }};
            const totalKelas = {{ $dashboard_kaprodi['total_kelas_mk'] ?? 0 }};
            const totalNilai = {{ $dashboard_kaprodi['total_nilai_input'] ?? 0 }};

            const statistikAkademikChart = new Chart(ctx, {
                type: 'line',
                {
                    labels: ['Dosen', 'Mahasiswa', 'Kelas MK', 'Nilai Input'],
                    datasets: [{
                        label: 'Statistik Prodi',
                        data: [totalDosen, totalMahasiswa, totalKelas, totalNilai],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
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
                            text: 'Trend Statistik Prodi'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
