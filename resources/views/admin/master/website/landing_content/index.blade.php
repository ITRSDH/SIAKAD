@extends('layouts.index')
@section('title', 'Landing Content')
@push('styles-custom')
    <style>
        /* Gaya untuk loader */
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: inherit;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .loader-overlay.hidden {
            display: none;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
        }

        /* Style untuk preview gambar */
        .image-preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .image-preview {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            margin: 10px 0;
        }

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 2rem 0;
            padding-top: 2rem;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #007bff;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Landing Content</h3>
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
                    <a href="{{ route('landing-content.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('landing-content.index') }}">Landing Content</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Landing Content -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseLandingForm" aria-expanded="true"
                        aria-controls="collapseLandingForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-cog text-primary me-2"></i>Pengaturan Landing Page
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <div class="collapse show" id="collapseLandingForm">
                        <div class="card-body">
                            <div id="formLoader" class="loader-overlay hidden">
                                <div class="loader-spinner"></div>
                            </div>

                            <form id="landingContentForm" name="landingContentForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="landing_id" value="1">

                                <!-- Hero Section -->
                                <div class="section-title">
                                    <i class="fas fa-image text-primary me-2"></i>Hero Section
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="hero_title" class="form-label">Hero Title</label>
                                            <input type="text" class="form-control" id="hero_title" name="hero_title"
                                                placeholder="Masukkan judul hero">
                                            <div class="text-danger error-text" id="hero_title_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="hero_subtitle" class="form-label">Hero Subtitle</label>
                                            <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3"
                                                placeholder="Masukkan subtitle hero"></textarea>
                                            <div class="text-danger error-text" id="hero_subtitle_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="hero_background" class="form-label">Hero Background</label>
                                            <input type="file" class="form-control" id="hero_background"
                                                name="hero_background" accept="image/jpeg, image/jpg, image/png, image/webp">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP.
                                                Maksimal 2MB.</small>
                                            <div class="text-danger error-text" id="hero_background_error"></div>
                                            <div id="hero-preview-container" class="image-preview-container mt-2"
                                                style="display: none;">
                                                <img id="hero-preview" src="" alt="Preview" class="image-preview">
                                                <p class="text-muted small mb-0">Preview Hero Background</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistics Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-chart-bar text-success me-2"></i>Statistik
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="jumlah_program_studi" class="form-label">Jumlah Program
                                                    Studi</label>
                                                <input type="number" class="form-control" id="jumlah_program_studi"
                                                    name="jumlah_program_studi" placeholder="0" min="0">
                                                <div class="text-danger error-text" id="jumlah_program_studi_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="jumlah_mahasiswa" class="form-label">Jumlah Mahasiswa</label>
                                                <input type="number" class="form-control" id="jumlah_mahasiswa"
                                                    name="jumlah_mahasiswa" placeholder="0" min="0">
                                                <div class="text-danger error-text" id="jumlah_mahasiswa_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="jumlah_dosen" class="form-label">Jumlah Dosen</label>
                                                <input type="number" class="form-control" id="jumlah_dosen"
                                                    name="jumlah_dosen" placeholder="0" min="0">
                                                <div class="text-danger error-text" id="jumlah_dosen_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="jumlah_mitra" class="form-label">Jumlah Mitra</label>
                                                <input type="number" class="form-control" id="jumlah_mitra"
                                                    name="jumlah_mitra" placeholder="0" min="0">
                                                <div class="text-danger error-text" id="jumlah_mitra_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keunggulan Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-star text-warning me-2"></i>Keunggulan
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="keunggulan" class="form-label">Keunggulan</label>
                                                <textarea class="form-control" id="keunggulan" name="keunggulan" rows="6"
                                                    placeholder="Masukkan keunggulan institusi"></textarea>
                                                <div class="text-danger error-text" id="keunggulan_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Branding Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-palette text-info me-2"></i>Branding
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nama_aplikasi" class="form-label">Nama Aplikasi</label>
                                                <input type="text" class="form-control" id="nama_aplikasi"
                                                    name="nama_aplikasi" placeholder="Masukkan nama aplikasi">
                                                <div class="text-danger error-text" id="nama_aplikasi_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="logo" class="form-label">Logo</label>
                                                <input type="file" class="form-control" id="logo" name="logo"
                                                    accept="image/jpeg, image/jpg, image/png, image/webp">
                                                <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP.
                                                    Maksimal 2MB.</small>
                                                <div class="text-danger error-text" id="logo_error"></div>
                                                <div id="logo-preview-container" class="image-preview-container mt-2"
                                                    style="display: none;">
                                                    <img id="logo-preview" src="" alt="Preview"
                                                        class="image-preview">
                                                    <p class="text-muted small mb-0">Preview Logo</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="deskripsi_footer" class="form-label">Deskripsi Footer</label>
                                                <textarea class="form-control" id="deskripsi_footer" name="deskripsi_footer" rows="4"
                                                    placeholder="Masukkan deskripsi footer"></textarea>
                                                <div class="text-danger error-text" id="deskripsi_footer_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Media Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-share-alt text-primary me-2"></i>Media Sosial
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="facebook" class="form-label">Facebook</label>
                                                <input type="url" class="form-control" id="facebook"
                                                    name="facebook" placeholder="https://facebook.com/username">
                                                <div class="text-danger error-text" id="facebook_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="twitter" class="form-label">Twitter</label>
                                                <input type="url" class="form-control" id="twitter" name="twitter"
                                                    placeholder="https://twitter.com/username">
                                                <div class="text-danger error-text" id="twitter_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="instagram" class="form-label">Instagram</label>
                                                <input type="url" class="form-control" id="instagram"
                                                    name="instagram" placeholder="https://instagram.com/username">
                                                <div class="text-danger error-text" id="instagram_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="linkedin" class="form-label">LinkedIn</label>
                                                <input type="url" class="form-control" id="linkedin"
                                                    name="linkedin" placeholder="https://linkedin.com/company/name">
                                                <div class="text-danger error-text" id="linkedin_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="youtube" class="form-label">YouTube</label>
                                                <input type="url" class="form-control" id="youtube" name="youtube"
                                                    placeholder="https://youtube.com/channel/id">
                                                <div class="text-danger error-text" id="youtube_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-phone text-success me-2"></i>Kontak
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                                <div class="text-danger error-text" id="alamat_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="telepon" class="form-label">Telepon</label>
                                                <input type="tel" class="form-control" id="telepon" name="telepon"
                                                    placeholder="Contoh: +62 21 1234567">
                                                <div class="text-danger error-text" id="telepon_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="contoh@domain.com">
                                                <div class="text-danger error-text" id="email_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button type="button" id="testApiBtn" class="btn btn-info btn-lg px-5 me-3">
                                        <i class="fas fa-link"></i> Test API Connection
                                    </button>
                                    <button type="submit" id="saveBtn" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save"></i> Simpan Pengaturan
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary btn-lg px-5 ms-3">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Load existing data jika ada
            loadLandingContent();

            // Preview hero background saat file dipilih
            $('#hero_background').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#hero-preview').attr('src', e.target.result);
                        $('#hero-preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#hero-preview-container').hide();
                }
            });

            // Preview logo saat file dipilih
            $('#logo').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logo-preview').attr('src', e.target.result);
                        $('#logo-preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#logo-preview-container').hide();
                }
            });

            // Load data landing content yang sudah ada
            function loadLandingContent() {
                @if ($landingContent)
                    const data = @json($landingContent);

                    // Populate form fields
                    $('#hero_title').val(data.hero_title || '');
                    $('#hero_subtitle').val(data.hero_subtitle || '');
                    $('#jumlah_program_studi').val(data.jumlah_program_studi || '');
                    $('#jumlah_mahasiswa').val(data.jumlah_mahasiswa || '');
                    $('#jumlah_dosen').val(data.jumlah_dosen || '');
                    $('#jumlah_mitra').val(data.jumlah_mitra || '');
                    $('#keunggulan').val(data.keunggulan || '');
                    $('#nama_aplikasi').val(data.nama_aplikasi || '');
                    $('#deskripsi_footer').val(data.deskripsi_footer || '');
                    $('#facebook').val(data.facebook || '');
                    $('#twitter').val(data.twitter || '');
                    $('#instagram').val(data.instagram || '');
                    $('#linkedin').val(data.linkedin || '');
                    $('#youtube').val(data.youtube || '');
                    $('#alamat').val(data.alamat || '');
                    $('#telepon').val(data.telepon || '');
                    $('#email').val(data.email || '');

                    // Show existing images
                    var apiStorageUrl = '{{ config('api.storage_url') }}';

                    if (data.hero_background) {
                        let heroImageUrl = data.hero_background;
                        if (!heroImageUrl.startsWith('http')) {
                            heroImageUrl = apiStorageUrl + data.hero_background;
                        }
                        $('#hero-preview').attr('src', heroImageUrl);
                        $('#hero-preview-container').show();
                    }

                    if (data.logo) {
                        let logoImageUrl = data.logo;
                        if (!logoImageUrl.startsWith('http')) {
                            logoImageUrl = apiStorageUrl + data.logo;
                        }
                        $('#logo-preview').attr('src', logoImageUrl);
                        $('#logo-preview-container').show();
                    }
                @endif
            }

            // Test API Connection
            $('#testApiBtn').click(function() {
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Testing...');

                $.ajax({
                    url: "{{ route('landing-content.show', 1) }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('API Test Success:', response);
                        Swal.fire({
                            icon: 'success',
                            title: 'API Connection OK!',
                            text: 'Koneksi ke API berhasil.',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        console.error('API Test Error:', xhr);
                        let errorMsg = 'API Connection Failed';
                        if (xhr.responseJSON) {
                            errorMsg += ': ' + (xhr.responseJSON.message || 'Unknown error');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'API Test Failed!',
                            html: `<pre>${JSON.stringify(xhr.responseJSON || xhr.responseText, null, 2)}</pre>`,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        $('#testApiBtn').prop('disabled', false).html(
                            '<i class="fas fa-link"></i> Test API Connection');
                    }
                });
            });

            // Reset form
            $('#resetBtn').click(function() {
                if (confirm('Apakah Anda yakin ingin mereset semua data?')) {
                    $('#landingContentForm')[0].reset();
                    $('.error-text').text('');
                    $('#hero-preview-container').hide();
                    $('#logo-preview-container').hide();
                    loadLandingContent(); // Reload original data
                }
            });

            // Submit form
            $('#landingContentForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Gunakan FormData untuk mengirim file
                const formData = new FormData(this);

                // Debug: tampilkan data yang akan dikirim
                console.log('Form Data yang akan dikirim:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ', pair[1]);
                }

                // Show loader
                $('#formLoader').removeClass('hidden');
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: "{{ route('landing-content.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Landing content berhasil disimpan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload page to show updated data
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat menyimpan data.',
                                confirmButtonText: 'OK'
                            });

                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_error').text(response.errors[key][
                                        0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        console.error('Response Status:', xhr.status);
                        console.error('Response Text:', xhr.responseText);

                        let errorMessage = 'Gagal menyimpan data.';
                        let debugInfo = null;

                        if (xhr.responseJSON) {
                            console.error('Response JSON:', xhr.responseJSON);

                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            if (xhr.responseJSON.debug) {
                                debugInfo = xhr.responseJSON.debug;
                                console.error('Debug Info:', debugInfo);
                            }

                            // Tampilkan error spesifik jika ada
                            if (xhr.responseJSON.errors) {
                                Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                    if (Array.isArray(xhr.responseJSON.errors[key])) {
                                        $('#' + key + '_error').text(xhr.responseJSON
                                            .errors[key][0]);
                                    } else {
                                        $('#' + key + '_error').text(xhr.responseJSON
                                            .errors[key]);
                                    }
                                });
                            }
                        } else if (xhr.responseText) {
                            errorMessage = 'Server Error: ' + xhr.responseText.substring(0,
                                200) + '...';
                        }

                        // Tampilkan error dengan detail debugging
                        let errorText = errorMessage;
                        if (debugInfo) {
                            errorText += '\n\nDebug Info: ' + JSON.stringify(debugInfo, null,
                            2);
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorText,
                            confirmButtonText: 'OK',
                            customClass: {
                                content: 'text-left'
                            }
                        });
                    },
                    complete: function() {
                        // Hide loader
                        $('#formLoader').addClass('hidden');
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan Pengaturan');
                    }
                });
            });
        });
    </script>
@endpush
