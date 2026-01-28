@extends('layouts.index')
@section('title', 'Daftar KRS Perlu Verifikasi')

@push('styles-custom')
    <style>
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .loader-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .action-buttons .btn {
            margin-bottom: 5px;
        }

        @media (min-width: 768px) {
            .action-buttons .btn {
                margin-bottom: 0;
            }
        }

        .modal textarea {
            resize: vertical;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar KRS Perlu Verifikasi</h3>
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
                    <a href="#">Verifikasi KRS</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">KRS Menunggu Verifikasi</h4>
                        <p class="card-category">Total: {{ $total_krs }} KRS</p>
                    </div>
                    <div class="card-body">
                        <div class="loader-overlay hidden">
                            <div class="loader-spinner"></div>
                        </div>

                        @if ($total_krs > 0)
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Kelas Pararel</th>
                                            <th>Semester</th>
                                            <th>SKS Diambil</th>
                                            <th>Matkul Diambil</th>
                                            <th>Tanggal Pengisian</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($krs_list as $index => $krs)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $krs['mahasiswa']['nama_mahasiswa'] }}</td>
                                                <td>{{ $krs['mahasiswa']['nim'] }}</td>
                                                <td>{{ $krs['mahasiswa']['kelas_pararel']['nama_kelas'] ?? '-' }}</td>
                                                <td>{{ $krs['semester']['nama_semester'] }}
                                                    ({{ $krs['semester']['tahun_akademik'] ?? '-' }})
                                                </td>
                                                <td>{{ $krs['jumlah_sks_diambil'] }}</td>
                                                <td>{{ $krs['jumlah_matkul_diambil'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($krs['tanggal_pengisian'])->format('d/m/Y') }}
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="{{ route('dosen-verifikasi-krs.detail', ['id' => $krs['id']]) }}"
                                                        class="btn btn-info btn-sm">
                                                        Lihat Detail
                                                    </a>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="openApproveModal('{{ $krs['id'] }}')">
                                                        Setujui
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="openRejectModal('{{ $krs['id'] }}')">
                                                        Tolak
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <h5 class="text-muted">Tidak ada KRS yang menunggu verifikasi.</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="approveForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Setujui KRS</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui KRS ini?</p>
                        <label for="approveNote">Catatan (Opsional):</label>
                        <textarea id="approveNote" name="catatan_verifikasi" class="form-control" rows="3"
                            placeholder="Tambahkan catatan..."></textarea>
                        <input type="hidden" id="approveId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="rejectForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak KRS</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan menolak KRS ini. Silakan berikan alasan:</p>
                        <label for="rejectNote">Catatan (Wajib):</label>
                        <textarea id="rejectNote" name="catatan_verifikasi" class="form-control" rows="3"
                            placeholder="Alasan penolakan..." required></textarea>
                        <input type="hidden" id="rejectId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openApproveModal(id) {
            document.getElementById('approveId').value = id;
            document.getElementById('approveNote').value = '';
            $('#approveModal').modal('show');
        }

        function openRejectModal(id) {
            document.getElementById('rejectId').value = id;
            document.getElementById('rejectNote').value = '';
            $('#rejectModal').modal('show');
        }

        document.getElementById('approveForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');

            document.querySelector('.loader-overlay').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('dosen/verifikasi-krs') }}/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire('Berhasil!', result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Gagal!', result.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error!', err.message, 'error');
            } finally {
                document.querySelector('.loader-overlay').classList.add('hidden');
                $('#approveModal').modal('hide');
            }
        });

        document.getElementById('rejectForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');

            document.querySelector('.loader-overlay').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('dosen/verifikasi-krs') }}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire('Berhasil!', result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Gagal!', result.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error!', err.message, 'error');
            } finally {
                document.querySelector('.loader-overlay').classList.add('hidden');
                $('#rejectModal').modal('hide');
            }
        });
    </script>
@endpush
