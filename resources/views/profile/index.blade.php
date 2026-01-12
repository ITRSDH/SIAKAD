@extends('layouts.index')

@section('title', 'Profile')
@push('styles-custom')
@endpush
@section('content')
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <!-- Header Profile Card -->
                    <div class="card-header bg-primary text-white text-center py-4 position-relative">
                        <!-- Placeholder gambar profil -->
                        <div class="mx-auto bg-light rounded-circle border-4 border-white d-flex align-items-center justify-content-center"
                            style="width: 150px; height: 150px; margin-top: -75px; margin-bottom: 15px;">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                        <h4 class="mb-1">{{ $user['name'] }}</h4>
                        @if ($profile_type === 'dosen')
                            <p class="mb-0">{{ $profile['nama_dosen'] ?? 'N/A' }}</p>
                        @elseif($profile_type === 'mahasiswa')
                            <p class="mb-0">{{ $profile['nama_mahasiswa'] ?? 'N/A' }}</p>
                        @endif
                    </div>

                    <!-- Tab Navigation -->
                    <div class="card-body">
                        <ul class="nav nav-tabs border-bottom mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="pills-account-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-account" type="button" role="tab"
                                    aria-controls="pills-account" aria-selected="true">
                                    Akun
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="pills-profile-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile-info" type="button" role="tab"
                                    aria-controls="pills-profile-info" aria-selected="false">
                                    Profil {{ ucfirst($profile_type) }}
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Tab Akun -->
                            <div class="tab-pane fade show active" id="pills-account" role="tabpanel"
                                aria-labelledby="pills-account-tab">
                                <div class="col-md-12">
                                    <div class="card bg-light border-start border-primary border-4 mb-3 shadow-sm">
                                        <div class="card-body p-0">
                                            <h5 class="card-title text-primary fw-bold border-bottom pb-2 mb-0 px-4 pt-4">
                                                <i class="fas fa-user-circle me-2"></i>Informasi Akun
                                            </h5>
                                            <ul class="list-group list-group-flush list-group-unbordered">
                                                <li
                                                    class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                    <div class="text-muted">Nama</div>
                                                    <span class="fw-semibold">{{ $user['name'] }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                    <div class="text-muted">Alamat Email</div>
                                                    <span class="fw-semibold text-primary">{{ $user['email'] }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                    <div class="text-muted">Status Akun</div>
                                                    <span
                                                        class="badge bg-success fw-normal px-3 py-2">{{ $user['status'] }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Profil Info -->
                            <div class="tab-pane fade" id="pills-profile-info" role="tabpanel"
                                aria-labelledby="pills-profile-info-tab">
                                @if ($profile)
                                    <div class="row">
                                        <!-- Detail Profil -->
                                        <div class="col-md-12">
                                            <div class="card bg-light border-start border-primary border-4 mb-3 shadow-sm">
                                                <div class="card-body p-0">
                                                    <h5
                                                        class="card-title text-primary fw-bold border-bottom pb-2 mb-0 px-4 pt-4">
                                                        <i class="fas fa-user-circle me-2"></i>Detail Profil
                                                        {{ ucfirst($profile_type) }}
                                                    </h5>
                                                    <ul class="list-group list-group-flush list-group-unbordered">
                                                        @if ($profile_type === 'dosen')
                                                            <li
                                                                class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                                <div class="text-muted">Nama Lengkap</div>
                                                                <span
                                                                    class="fw-semibold">{{ $profile['nama_dosen'] }}</span>
                                                            </li>
                                                        @elseif($profile_type === 'mahasiswa')
                                                            <li
                                                                class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                                <div class="text-muted">Nama Lengkap</div>
                                                                <span
                                                                    class="fw-semibold">{{ $profile['nama_mahasiswa'] }}</span>
                                                            </li>
                                                        @endif

                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">Jenis Kelamin</div>
                                                            <span
                                                                class="fw-semibold">{{ $profile['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">Tanggal Lahir</div>
                                                            <span
                                                                class="fw-semibold">{{ $profile['tanggal_lahir'] ? \Carbon\Carbon::parse($profile['tanggal_lahir'])->format('d F Y') : '-' }}</span>
                                                        </li>

                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">Alamat</div>
                                                            <span
                                                                class="fw-semibold">{{ $profile['alamat'] ?? '-' }}</span>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">No. HP</div>
                                                            <span class="fw-semibold">{{ $profile['no_hp'] ?? '-' }}</span>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">Email (Profil)</div>
                                                            <span
                                                                class="fw-semibold text-primary">{{ $profile['email'] ?? '-' }}</span>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="text-muted">
                                                                Status
                                                                {{ $profile_type === 'dosen' ? 'Aktif' : 'Akademik' }}
                                                            </div>
                                                            @php
                                                                // Ambil status berdasarkan tipe profile
                                                                $isActive =
                                                                    $profile_type === 'dosen'
                                                                        ? $profile['status_aktif'] ?? null
                                                                        : $profile['status'] ?? null;
                                                                $badgeClass =
                                                                    ($profile_type === 'dosen' && $isActive === true) ||
                                                                    ($profile_type === 'mahasiswa' &&
                                                                        $isActive === 'Aktif')
                                                                        ? 'bg-success'
                                                                        : 'bg-secondary';
                                                                $statusText =
                                                                    $isActive === true
                                                                        ? 'Aktif'
                                                                        : ($isActive === false
                                                                            ? 'Tidak Aktif'
                                                                            : (is_string($isActive)
                                                                                ? ucfirst($isActive)
                                                                                : 'Tidak Diketahui'));
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }} fw-normal px-3 py-2">
                                                                {{ $statusText }}
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-warning mb-0">
                                                <p class="mb-0 text-center">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Profil {{ ucfirst($profile_type ?? 'terkait') }} tidak ditemukan untuk
                                                    akun ini.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <!-- Jika layout utama Anda tidak menyertakan Bootstrap JS, tambahkan di sini -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
@endpush
