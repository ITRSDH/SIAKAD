@extends('layouts.index')
@section('title', 'Status Pengajuan KRS')
@push('styles-custom')
    <style>
        .status-card {
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .status-header {
            padding: 25px;
            border-radius: 15px 15px 0 0;
        }

        .status-draft {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .status-menunggu {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        }

        .status-disetujui {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .status-ditolak {
            background: linear-gradient(135deg, #dc3545 0%, #6f42c1 100%);
        }

        .status-selesai {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .status-body {
            padding: 25px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
            color: #007bff;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th,
        .detail-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-action {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white !important;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
        }

        .btn-resubmit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .info-section {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 20px;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: #e9ecef;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-container {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            background-color: inherit;
        }

        .timeline-container::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -17px;
            background-color: white;
            border: 4px solid #007bff;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left {
            left: 0;
        }

        .right {
            left: 50%;
        }

        .right::after {
            left: -16px;
        }

        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }

            .timeline-container {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .timeline-container::after {
                left: 15px;
            }

            .left,
            .right {
                left: 0;
            }

            .summary-stats {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Status Pengajuan KRS</h3>
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
                    <a href="#">Status Pengajuan KRS</a>
                </li>
            </ul>
        </div>

        <div class="row">
            @if ($krs)
                <!-- Status Card -->
                <div class="col-md-12">
                    <div class="status-card">
                        <div class="status-header status-{{ strtolower(str_replace(' ', '-', $krs['status'])) }}">
                            <h4 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>Status KRS: {{ $krs['status'] }}
                            </h4>
                            <p class="mb-0 mt-2">Tanggal Pengisian:
                                {{ \Carbon\Carbon::parse($krs['tanggal_pengisian'])->timezone('Asia/Jakarta')->format('d M Y') }}
                            </p>
                        </div>

                        <div class="status-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h6><i class="fas fa-chalkboard-teacher me-2"></i>Dosen Wali</h6>
                                        <p class="mb-1"><strong>Nama:</strong>
                                            {{ $krs['dosen_wali']['nama_dosen'] ?? '-' }}</p>
                                        <p class="mb-0"><strong>NUP:</strong> {{ $krs['dosen_wali']['nup'] ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="summary-stats">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $krs['jumlah_sks_diambil'] ?? 0 }}</div>
                                            <div class="stat-label">Total SKS</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $krs['jumlah_matkul_diambil'] ?? 0 }}</div>
                                            <div class="stat-label">Jumlah MK</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">{{ count($krs['detail'] ?? []) }}</div>
                                            <div class="stat-label">Kelas Diambil</div>
                                        </div>
                                    </div>

                                    @if ($krs['tanggal_verifikasi'])
                                        <div class="info-section">
                                            <h6><i class="fas fa-check-circle me-2"></i>Verifikasi</h6>
                                            <p class="mb-0">Tanggal Verifikasi:
                                                {{ \Carbon\Carbon::parse($krs['tanggal_verifikasi'])->timezone('Asia/Jakarta')->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                @if ($krs['status'] == 'Menunggu Verifikasi')
                                    <button type="button" class="btn btn-action btn-cancel delete-btn"
                                        data-id="{{ $krs['id'] }}">
                                        <i class="fas fa-times me-2"></i>Batalkan KRS
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Mata Kuliah -->
                <div class="col-md-12 mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-list me-2"></i>Detail Mata Kuliah
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="detail-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode MK</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th>SKS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($krs['detail'] ?? [] as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $detail['mata_kuliah']['kode_mk'] ?? '-' }}</td>
                                                <td>{{ $detail['mata_kuliah']['nama_mk'] ?? '-' }}</td>
                                                <td>{{ $detail['kode_kelas_mk'] ?? '-' }}</td>
                                                <td>{{ $detail['mata_kuliah']['sks'] ?? 0 }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data mata kuliah</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Status -->
                <div class="col-md-12 mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-timeline me-2"></i>Timeline Proses KRS
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-container left">
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-file-medical text-primary me-2"></i>Pengisian KRS</h6>
                                        <p class="mb-0">KRS dibuat pada
                                            {{ \Carbon\Carbon::parse($krs['tanggal_pengisian'])->timezone('Asia/Jakarta')->format('d M Y') }}
                                        </p>
                                        <span class="badge badge-light">Status: {{ $krs['status'] }}</span>
                                    </div>
                                </div>

                                @if ($krs['tanggal_verifikasi'])
                                    <div class="timeline-container right">
                                        <div class="timeline-content">
                                            <h6><i class="fas fa-check-circle text-success me-2"></i>Verifikasi Selesai</h6>
                                            <p class="mb-0">Diverifikasi pada
                                                {{ \Carbon\Carbon::parse($krs['tanggal_verifikasi'])->timezone('Asia/Jakarta')->format('d M Y') }}
                                            </p>
                                            <span class="badge badge-success">Status: {{ $krs['status'] }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="timeline-container right">
                                        <div class="timeline-content">
                                            <h6><i class="fas fa-hourglass-half text-warning me-2"></i>Menunggu Verifikasi
                                            </h6>
                                            <p class="mb-0">Menunggu persetujuan dari dosen wali</p>
                                            <span class="badge badge-warning">Status: Menunggu Verifikasi</span>
                                        </div>
                                    </div>
                                @endif
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
                            <h5 class="text-muted">Belum Ada Pengajuan KRS</h5>
                            <p class="text-muted">Anda belum memiliki pengajuan KRS untuk semester aktif saat ini.</p>
                            <a href="{{ route('mahasiswa.pengajuan-krs.daftar-matkul') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Buat KRS Baru
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.pengajuan-krs.batal', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Konfirmasi Pembatalan',
                    text: 'Apakah Anda yakin ingin membatalkan KRS ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message || res
                                        .success,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('mahasiswa.pengajuan-krs.daftar-matkul') }}";
                                });
                            },
                            error: () => Swal.fire('Gagal', 'Tidak dapat membatalkan KRS.',
                                'error')

                        });
                    }
                });
            });
        });
    </script>
@endpush
