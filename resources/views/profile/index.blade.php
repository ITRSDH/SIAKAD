@extends('layouts.index')

@section('title', 'Profile')
@push('styles-custom')
    <style>
        .avatar-md {
            width: 48px;
            height: 48px;
        }

        .avatar-xs {
            width: 32px;
            height: 32px;
        }

        .bg-primary-subtle {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .bg-success-subtle {
            background-color: rgba(var(--bs-success-rgb), 0.1) !important;
        }

        .bg-warning-subtle {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }

        .bg-info-subtle {
            background-color: rgba(var(--bs-info-rgb), 0.1) !important;
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .progress-bar-striped {
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        }
    </style>
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="pills-change-password-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-change-password" type="button" role="tab"
                                    aria-controls="pills-change-password" aria-selected="false">
                                    Ubah Password
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

                            <!-- Tab Ubah Password -->
                            <div class="tab-pane fade" id="pills-change-password" role="tabpanel"
                                aria-labelledby="pills-change-password-tab">
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <!-- Main Card -->
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-5">
                                                <div class="mb-5">
                                                    <div class="d-flex align-items-center mb-4">
                                                        <div
                                                            class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class="fas fa-shield-alt text-primary fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="card-title mb-1 fw-bold text-dark">Keamanan Akun
                                                            </h4>
                                                            <p class="card-text mb-0 text-muted">Kelola password untuk
                                                                melindungi akun Anda</p>
                                                        </div>
                                                    </div>

                                                    <!-- Alert Messages -->
                                                    @if (session('error'))
                                                        <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center"
                                                            role="alert">
                                                            <i class="fas fa-exclamation-circle text-danger me-3 fs-5"></i>
                                                            <div>{{ session('error') }}</div>
                                                            <button type="button" class="btn-close ms-auto"
                                                                data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif

                                                    @if (session('success'))
                                                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center"
                                                            role="alert">
                                                            <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                                            <div>{{ session('success') }}</div>
                                                            <button type="button" class="btn-close ms-auto"
                                                                data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif

                                                    <!-- Form Ubah Password -->
                                                    <form action="{{ route('profile.change-password') }}" method="POST"
                                                        id="changePasswordForm">
                                                        @csrf

                                                        <!-- Current Password -->
                                                        <div class="mb-4">
                                                            <label for="current_password"
                                                                class="form-label fw-medium mb-2">
                                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                                <span class="text-dark">Password Saat Ini</span>
                                                            </label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control form-control-lg border-2 @error('current_password') is-invalid @enderror"
                                                                    id="current_password" name="current_password" required
                                                                    placeholder="••••••••">
                                                                <button
                                                                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                                                    type="button"
                                                                    onclick="togglePassword('current_password')">
                                                                    <i class="fas fa-eye text-muted"
                                                                        id="current_password_icon"></i>
                                                                </button>
                                                            </div>
                                                            @error('current_password')
                                                                <div class="invalid-feedback d-block mt-2">
                                                                    <i
                                                                        class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <!-- New Password -->
                                                        <div class="mb-4">
                                                            <label for="new_password" class="form-label fw-medium mb-2">
                                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                                <span class="text-dark">Password Baru</span>
                                                            </label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control form-control-lg border-2 @error('new_password') is-invalid @enderror"
                                                                    id="new_password" name="new_password" required
                                                                    minlength="6" placeholder="••••••••"
                                                                    onkeyup="checkPasswordStrength()">
                                                                <button
                                                                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                                                    type="button"
                                                                    onclick="togglePassword('new_password')">
                                                                    <i class="fas fa-eye text-muted"
                                                                        id="new_password_icon"></i>
                                                                </button>
                                                            </div>
                                                            @error('new_password')
                                                                <div class="invalid-feedback d-block mt-2">
                                                                    <i
                                                                        class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                                </div>
                                                            @enderror

                                                            <!-- Password Strength Indicator -->
                                                            <div class="mt-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-1">
                                                                    <small class="text-muted">Kekuatan password:</small>
                                                                    <small id="passwordStrengthText"
                                                                        class="fw-medium">-</small>
                                                                </div>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div id="passwordStrength"
                                                                        class="progress-bar progress-bar-striped"
                                                                        role="progressbar"
                                                                        style="width: 0%; transition: all 0.3s ease;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Confirm New Password -->
                                                        <div class="mb-4">
                                                            <label for="new_password_confirmation"
                                                                class="form-label fw-medium mb-2">
                                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                                <span class="text-dark">Konfirmasi Password Baru</span>
                                                            </label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control form-control-lg border-2 @error('new_password_confirmation') is-invalid @enderror"
                                                                    id="new_password_confirmation"
                                                                    name="new_password_confirmation" required
                                                                    placeholder="••••••••" onkeyup="checkPasswordMatch()">
                                                                <button
                                                                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                                                    type="button"
                                                                    onclick="togglePassword('new_password_confirmation')">
                                                                    <i class="fas fa-eye text-muted"
                                                                        id="new_password_confirmation_icon"></i>
                                                                </button>
                                                            </div>
                                                            @error('new_password_confirmation')
                                                                <div class="invalid-feedback d-block mt-2">
                                                                    <i
                                                                        class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                                </div>
                                                            @enderror

                                                            <!-- Password Match Indicator -->
                                                            <div id="passwordMatch" class="mt-2"></div>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <div class="d-flex gap-3 flex-wrap">
                                                            <button type="submit"
                                                                class="btn btn-primary btn-lg px-4 py-2 fw-medium flex-fill"
                                                                id="submitBtn" style="min-width: 200px;">
                                                                <i class="fas fa-shield-alt me-2"></i>Ubah Password
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary btn-lg px-4 py-2 fw-medium"
                                                                onclick="resetForm()">
                                                                <i class="fas fa-times me-2"></i>Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sidebar Info -->
                                    <div class="col-lg-4">
                                        <!-- Security Tips Card -->
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-4">
                                                <h5 class="card-title mb-4 fw-bold text-dark">
                                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                                    Tips Keamanan
                                                </h5>

                                                <div class="vstack gap-3">
                                                    <div class="d-flex align-items-start">
                                                        <div
                                                            class="avatar-xs bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3 mt-1">
                                                            <i class="fas fa-check text-success fs-6"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-medium">Minimal 6 karakter</h6>
                                                            <p class="text-muted small mb-0">Semakin panjang password,
                                                                semakin aman</p>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-start">
                                                        <div
                                                            class="avatar-xs bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3 mt-1">
                                                            <i class="fas fa-check text-success fs-6"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-medium">Kombinasi huruf & angka</h6>
                                                            <p class="text-muted small mb-0">Contoh: Student123</p>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-start">
                                                        <div
                                                            class="avatar-xs bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3 mt-1">
                                                            <i class="fas fa-exclamation-triangle text-warning fs-6"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-medium">Hindari informasi pribadi</h6>
                                                            <p class="text-muted small mb-0">Jangan gunakan nama atau
                                                                tanggal lahir</p>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-start">
                                                        <div
                                                            class="avatar-xs bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3 mt-1">
                                                            <i class="fas fa-user-shield text-info fs-6"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-medium">Rahasiakan password</h6>
                                                            <p class="text-muted small mb-0">Jangan berbagi password dengan
                                                                siapapun</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Last Updated Card -->
                                                <div class="card border-0 bg-light-subtle mt-4">
                                                    <div class="card-body text-center py-3">
                                                        <i class="fas fa-clock text-muted mb-2"></i>
                                                        <h6 class="card-title text-muted mb-1">Terakhir Diperbarui</h6>
                                                        <p class="card-text text-muted small mb-0">
                                                            {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            let strength = 0;

            if (password.length >= 6) strength += 25;
            if (password.length >= 10) strength += 25;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;

            strengthBar.style.width = strength + '%';

            // Update progress bar color and text
            strengthBar.className = 'progress-bar';
            if (strength <= 25) {
                strengthBar.classList.add('bg-danger');
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-danger small';
            } else if (strength <= 50) {
                strengthBar.classList.add('bg-warning');
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-warning small';
            } else if (strength <= 75) {
                strengthBar.classList.add('bg-info');
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-info small';
            } else {
                strengthBar.classList.add('bg-success');
                strengthText.textContent = 'Sangat Kuat';
                strengthText.className = 'text-success small';
            }
        }

        // Check password match
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                matchDiv.innerHTML = '';
                return;
            }

            if (newPassword === confirmPassword) {
                matchDiv.innerHTML =
                    '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Password cocok</small>';
            } else {
                matchDiv.innerHTML =
                    '<small class="text-danger"><i class="fas fa-times-circle me-1"></i>Password tidak cocok</small>';
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('changePasswordForm').reset();
            document.getElementById('passwordStrength').style.width = '0%';
            document.getElementById('passwordStrengthText').textContent = '';
            document.getElementById('passwordMatch').innerHTML = '';

            // Reset password visibility
            ['current_password', 'new_password', 'new_password_confirmation'].forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(fieldId + '_icon');
                if (field.type === 'text') {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        // Form validation before submit
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Password baru dan konfirmasi password tidak cocok!');
                return false;
            }

            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password baru minimal 6 karakter!');
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengubah Password...';
            submitBtn.disabled = true;
        });
    </script>
@endpush
