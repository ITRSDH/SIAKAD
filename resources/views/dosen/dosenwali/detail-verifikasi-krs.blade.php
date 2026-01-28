@extends('layouts.index')
@section('title', 'Detail KRS Mahasiswa')

@push('styles-custom')
    <style>
        .info-card {
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }

        .table-detail th {
            width: 30%;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.875rem;
            color: white;
        }

        .status-disetujui {
            background-color: #28a745;
        }

        .status-menunggu {
            background-color: #ffc107;
            color: black;
        }

        .status-ditolak {
            background-color: #dc3545;
        }

        .btn-back {
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail KRS Mahasiswa</h3>
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
                    <a href="{{ route('dosen-verifikasi-krs.daftar-verifikasi') }}">Verifikasi KRS</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail KRS</a>
                </li>
            </ul>
        </div>
        @if ($detail_krs)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informasi KRS</h4>
                        </div>
                        <div class="card-body">
                            <!-- Info Mahasiswa -->
                            <div class="info-card">
                                <h5>Informasi Mahasiswa</h5>
                                <table class="table table-borderless table-detail">
                                    <tr>
                                        <th>NIM</th>
                                        <td>{{ $detail_krs['mahasiswa']['nim'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Mahasiswa</th>
                                        <td>{{ $detail_krs['mahasiswa']['nama_mahasiswa'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kelas Pararel</th>
                                        <td>{{ $detail_krs['mahasiswa']['kelas_pararel']['nama_kelas'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>HP</th>
                                        <td>{{ $detail_krs['mahasiswa']['no_hp'] ?? '-' }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th>SKS Maksimal</th>
                                        <td>{{ $detail_krs['mahasiswa']['jumlah_sks_max'] ?? 24 }}</td>
                                    </tr> --}}
                                </table>
                            </div>

                            <!-- Info Dosen Wali -->
                            <div class="info-card">
                                <h5>Dosen Wali</h5>
                                <table class="table table-borderless table-detail">
                                    <tr>
                                        <th>Nama Dosen</th>
                                        <td>{{ $detail_krs['dosen_wali']['nama_dosen'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
                                        <td>{{ $detail_krs['dosen_wali']['nup'] ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Info KRS -->
                            <div class="info-card">
                                <h5>Informasi KRS</h5>
                                <table class="table table-borderless table-detail">
                                    <tr>
                                        <th>Semester</th>
                                        <td>{{ $detail_krs['semester']['nama_semester'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pengisian</th>
                                        <td>{{ \Carbon\Carbon::parse($detail_krs['tanggal_pengisian'])->timezone('Asia/Jakarta')->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span
                                                class="status-badge
                                        @if ($detail_krs['status'] === 'Disetujui') status-disetujui
                                        @elseif($detail_krs['status'] === 'Menunggu Verifikasi') status-menunggu
                                        @elseif($detail_krs['status'] === 'Ditolak') status-ditolak @endif">
                                                {{ $detail_krs['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah SKS Diambil</th>
                                        <td>{{ $detail_krs['jumlah_sks_diambil'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Mata Kuliah</th>
                                        <td>{{ $detail_krs['jumlah_matkul_diambil'] }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Detail Mata Kuliah -->
                            <div class="info-card">
                                <h5>Daftar Mata Kuliah</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode MK</th>
                                                <th>Nama MK</th>
                                                <th>SKS</th>
                                                <th>Kelas</th>
                                                <th>Jenis Kelas</th>
                                                <th>Dosen Pengampu</th>
                                                <th>Kuota</th>
                                                <th>Terisi</th>
                                                <th>Sisa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($detail_krs['detail'] as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item['mata_kuliah']['kode_mk'] }}</td>
                                                    <td>{{ $item['mata_kuliah']['nama_mk'] }}</td>
                                                    <td>{{ $item['mata_kuliah']['sks'] }}</td>
                                                    <td>{{ $item['kelas_info']['kelas_pararel']['nama_kelas'] ?? '-' }}
                                                    </td>
                                                    <td>{{ $item['kelas_info']['jenis_kelas']['nama_kelas'] ?? '-' }}</td>
                                                    <td>{{ $item['kelas_info']['dosen_pengampu']['nama_dosen'] ?? '-' }}
                                                    </td>
                                                    <td>{{ $item['kelas_info']['kuota'] }}</td>
                                                    <td>{{ $item['kelas_info']['jumlah_terisi'] }}</td>
                                                    <td>{{ $item['kelas_info']['tersisa'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">Tidak ada mata kuliah
                                                        di
                                                        KRS ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tombol Kembali -->
                            <a href="{{ route('dosen-verifikasi-krs.daftar-verifikasi') }}"
                                class="btn btn-secondary btn-back">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar KRS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No KRS Found -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-5x text-muted mb-4"></i>
                        <h5 class="text-muted">Tidak ada pengajuan KRS</h5>
                        <a href="{{ route('dosen-verifikasi-krs.daftar-verifikasi') }}" class="btn btn-primary">
                            Kembali ke Daftar KRS
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts-custom')
@endpush
