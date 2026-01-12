@extends('layouts.index')
@section('title', 'Dashboard Dosen Pengampu')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard Dosen Pengampu</h3>
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
                    <a href="#">Dashboard Dosen Pengampu</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Total Kelas Ampu -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-chalkboard-teacher"></i> <!-- Icon untuk Kelas Ampu -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kelas Ampu</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pengampu['total_kelas_ampu'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Jadwal Ampu -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-calendar-alt"></i> <!-- Icon untuk Jadwal -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Jadwal Mengajar</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pengampu['total_jadwal_ampu'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Presensi Input -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-clipboard-list"></i> <!-- Icon untuk Presensi -->
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Presensi Input</p>
                                    <h4 class="card-title">{{ $dashboard_dosen_pengampu['total_presensi_input'] ?? 0 }}</h4>
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
                                    <h4 class="card-title">{{ $dashboard_dosen_pengampu['total_nilai_input'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress and Charts Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Aktivitas Mengajar</div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h4>{{ $dashboard_dosen_pengampu['total_presensi_input'] ?? 0 }}</h4>
                                <p class="text-muted">Presensi</p>
                            </div>
                            <div class="col-6">
                                <h4>{{ $dashboard_dosen_pengampu['total_nilai_input'] ?? 0 }}</h4>
                                <p class="text-muted">Nilai</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="progress-container">
                                <span class="progress-badge">Rasio Input Nilai</span>
                                @php
                                    $totalKelas = $dashboard_dosen_pengampu['total_kelas_ampu'] ?? 0;
                                    $totalNilai = $dashboard_dosen_pengampu['total_nilai_input'] ?? 0;
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
                                <span class="progress-badge">Rasio Input Presensi</span>
                                @php
                                    $totalPresensi = $dashboard_dosen_pengampu['total_presensi_input'] ?? 0;
                                    $rasioPresensi =
                                        $totalKelas > 0 ? round(($totalPresensi / $totalKelas) * 100, 2) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $rasioPresensi }}%" aria-valuenow="{{ $rasioPresensi }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ $rasioPresensi }}%
                                    </div>
                                </div>
                                <small>{{ $totalPresensi }} dari {{ $totalKelas }} kelas telah diinput
                                    presensinya</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Aktivitas</div>
                    </div>
                    <div class="card-body">
                        <canvas id="aktivitasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Classes Table -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Kelas Terbaru</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Kelas</th>
                                        <th scope="col">Mata Kuliah</th>
                                        <th scope="col">Jumlah Mahasiswa</th>
                                        <th scope="col">Presensi</th>
                                        <th scope="col">Nilai</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i <= min(5, $dashboard_dosen_pengampu['total_kelas_ampu'] ?? 0); $i++)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>Kelas {{ $i }}</td>
                                            <td>Mata Kuliah {{ $i }}</td>
                                            <td>{{ rand(20, 40) }}</td>
                                            <td>{{ rand(10, 16) }}/16</td>
                                            <td>
                                                <span class="badge badge-{{ rand(0, 1) ? 'success' : 'warning' }}">
                                                    {{ rand(0, 1) ? 'Sudah' : 'Belum' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">Aktif</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Detail</button>
                                            </td>
                                        </tr>
                                    @endfor
                                    @if (($dashboard_dosen_pengampu['total_kelas_ampu'] ?? 0) == 0)
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data kelas yang diampu</td>
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
            const ctx = document.getElementById('aktivitasChart').getContext('2d');

            const totalKelas = {{ $dashboard_dosen_pengampu['total_kelas_ampu'] ?? 0 }};
            const totalPresensi = {{ $dashboard_dosen_pengampu['total_presensi_input'] ?? 0 }};
            const totalNilai = {{ $dashboard_dosen_pengampu['total_nilai_input'] ?? 0 }};

            const aktivitasChart = new Chart(ctx, {
                type: 'radar',
                {
                    labels: ['Kelas Ampu', 'Jadwal', 'Presensi Input', 'Nilai Input'],
                    datasets: [{
                        label: 'Aktivitas',
                        data: [totalKelas,
                            {{ $dashboard_dosen_pengampu['total_jadwal_ampu'] ?? 0 }},
                            totalPresensi, totalNilai
                        ],
                        backgroundColor: 'rgba(25, 118, 210, 0.2)',
                        borderColor: 'rgba(25, 118, 210, 1)',
                        pointBackgroundColor: 'rgba(25, 118, 210, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(25, 118, 210, 1)'
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
                            text: 'Ringkasan Aktivitas Mengajar'
                        }
                    },
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: Math.max(totalKelas,
                                {{ $dashboard_dosen_pengampu['total_jadwal_ampu'] ?? 0 }},
                                totalPresensi, totalNilai) + 5
                        }
                    }
                }
            });
        });
    </script>
@endpush
