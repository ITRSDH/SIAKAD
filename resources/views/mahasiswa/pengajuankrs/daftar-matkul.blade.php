@extends('layouts.index')
@section('title', 'Daftar Mata Kuliah KRS')
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

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .matkul-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: white;
        }

        .matkul-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #007bff;
        }

        .matkul-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            border-radius: 15px 15px 0 0;
        }

        .matkul-body {
            padding: 20px;
        }

        .kelas-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .kelas-item:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-color: #007bff;
        }

        .badge-sks {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .badge-kuota {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #badce3 100%);
            border: 1px solid #bee5eb;
            color: #0c5460;
            border-radius: 12px;
            padding: 15px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .matkul-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 20px;
        }

        .icon-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .matkul-grid {
                grid-template-columns: 1fr;
            }

            .summary-stats {
                grid-template-columns: 1fr;
            }

            .btn-submit {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Mata Kuliah KRS</h3>
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
                    <a href="#">Daftar Mata Kuliah KRS</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Informasi Semester dan Kurikulum -->
            <div class="col-md-12">
                <div class="summary-card">
                    <h4 class="mb-3">
                        <i class="fas fa-calendar-alt me-2"></i>Informasi Semester dan Kurikulum
                    </h4>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $jumlah_matkul_tersedia }}</div>
                            <div class="stat-label">Mata Kuliah Tersedia</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $semester_aktif['nama_semester'] ?? 'N/A' }}
                                {{ $semester_aktif['tahun_akademik'] ?? 'N/A' }}</div>
                            <div class="stat-label">Semester Aktif</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $kurikulum['nama_kurikulum'] ?? 'N/A' }}</div>
                            <div class="stat-label">Kurikulum</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengajuan KRS -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <i class="fas fa-book-open me-2"></i>Daftar Mata Kuliah yang Tersedia
                            <p class="card-category">Silakan periksa daftar mata kuliah yang tersedia untuk semester ini</p>
                        </h4>
                        <a href="{{ route('mahasiswa.pengajuan-krs.status') }}" class="btn btn-sm btn-primary">
                            Status Pengajuan KRS
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="alert alert-info">
                            <div class="icon-section">
                                <i class="fas fa-info-circle text-info fs-4"></i>
                                <div>
                                    <strong>Petunjuk:</strong> Semua mata kuliah yang tersedia akan otomatis terpilih sesuai
                                    dengan paket kurikulum Anda.
                                </div>
                            </div>
                        </div>

                        <div class="matkul-grid">
                            @forelse ($matkul_pilihan as $matkul)
                                <div class="matkul-card">
                                    <div class="matkul-header">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">
                                                    <i class="fas fa-book text-primary me-2"></i>{{ $matkul['nama_mk'] }}
                                                </h5>
                                                <small class="text-muted">{{ $matkul['kode_mk'] }}</small>
                                            </div>
                                            <span class="badge-sks">
                                                <i class="fas fa-graduation-cap me-1"></i>{{ $matkul['sks'] }} SKS
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-layer-group me-1"></i>Semester Rekomendasi:
                                            {{ $matkul['semester_rekomendasi'] }}
                                        </small>
                                    </div>

                                    <div class="matkul-body">
                                        <h6 class="section-title">
                                            <i class="fas fa-users me-2"></i>Kelas Tersedia
                                        </h6>

                                        @foreach ($matkul['kelas_tersedia'] as $kelas)
                                            <div class="kelas-item" data-class-id="{{ $kelas['id'] }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong class="d-block">
                                                            <i
                                                                class="fas fa-chalkboard-teacher me-1"></i>{{ $kelas['kode_kelas_mk'] }}
                                                        </strong>
                                                        <small class="text-muted d-block">
                                                            <i
                                                                class="fas fa-user me-1"></i>{{ $kelas['dosen']['nama_dosen'] ?? 'Dosen belum ditentukan' }}
                                                        </small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge-kuota">
                                                            <i
                                                                class="fas fa-users me-1"></i>{{ $kelas['tersisa'] }}/{{ $kelas['kuota'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="text-center mt-3">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Tersedia
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-folder-open fa-5x text-muted mb-4"></i>
                                        <h5 class="text-muted">Tidak Ada Mata Kuliah Tersedia</h5>
                                        <p class="text-muted">Belum ada mata kuliah yang tersedia untuk kelas pararel Anda
                                            di semester ini.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- @if ($krs['is_locked'])
                            <div class="mt-4 text-center">
                                <span class="badge bg-warning fs-6 px-4 py-3">
                                    <i class="fas fa-clock me-2"></i>
                                    KRS {{ $krs['status'] }}
                                </span>
                            </div>
                        @else
                            @if (count($matkul) > 0)
                                <div class="mt-4 text-center">
                                    <button class="btn btn-submit px-4 py-3" id="btnSubmitKrs">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Ajukan KRS
                                    </button>
                                </div>
                            @endif
                        @endif --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSubmit = document.getElementById('btnSubmitKrs');

            // Submit KRS with SweetAlert2
            btnSubmit?.addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Pengajuan KRS',
                    text: 'Apakah Anda yakin ingin mengajukan KRS ini ke dosen wali?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitKrs();
                    }
                });
            });

            function submitKrs() {
                // Show loading state
                document.getElementById('tableLoader').classList.remove('hidden');

                // Disable button during processing
                if (btnSubmit) btnSubmit.disabled = true;

                // Prepare form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'));

                // Collect all class IDs from the displayed classes
                const allClassIds = [];

                // Loop through all matkul cards and their kelas items
                document.querySelectorAll('.matkul-card').forEach(matkulCard => {
                    matkulCard.querySelectorAll('.kelas-item').forEach(kelasItem => {
                        const classId = kelasItem.getAttribute('data-class-id');
                        if (classId) {
                            allClassIds.push(classId);
                        }
                    });
                });

                // Add class IDs to form data
                allClassIds.forEach((classId, index) => {
                    formData.append(`kelas_mk_ids[${index}]`, classId);
                });

                const routeUrl = "{{ route('mahasiswa.pengajuan-krs.store') }}";

                // Send the request
                fetch(routeUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset UI
                        document.getElementById('tableLoader').classList.add('hidden');
                        if (btnSubmit) btnSubmit.disabled = false;

                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'KRS berhasil diajukan!',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('mahasiswa.pengajuan-krs.status') }}";
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan');
                        }
                    })
                    .catch(error => {
                        // Handle error response
                        console.error('Error:', error);

                        // Reset UI
                        document.getElementById('tableLoader').classList.add('hidden');
                        if (btnSubmit) btnSubmit.disabled = false;

                        // Try to extract error message from response
                        let errorMessage = error.message || 'Terjadi kesalahan saat mengirim data';

                        if (error.errors) {
                            if (typeof error.errors === 'string') {
                                errorMessage = error.errors;
                            } else if (Array.isArray(error.errors)) {
                                errorMessage = error.errors.join(', ');
                            } else if (typeof error.errors === 'object') {
                                errorMessage = Object.values(error.errors).flat().join(', ');
                            }
                        }

                        Swal.fire({
                            title: 'Gagal!',
                            text: errorMessage,
                            icon: 'error'
                        });
                    });
            }

            // Hide loader initially
            document.getElementById('tableLoader').classList.add('hidden');
        });
    </script>
@endpush
