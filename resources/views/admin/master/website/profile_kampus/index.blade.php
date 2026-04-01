@extends('layouts.index')
@section('title', 'Profile Kampus')
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
            <h3 class="fw-bold mb-3">Profile Kampus</h3>
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
                    <a href="{{ route('profile-kampus.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile-kampus.index') }}">Profile Kampus</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Landing Content -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseProfileForm" aria-expanded="true"
                        aria-controls="collapseProfileForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-university text-primary me-2"></i>Pengaturan Profile Kampus
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <div class="collapse show" id="collapseProfileForm">
                        <div class="card-body">
                            <div id="formLoader" class="loader-overlay hidden">
                                <div class="loader-spinner"></div>
                            </div>

                            <form id="profileKampusForm" name="profileKampusForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="profile_id" value="1">

                                <!-- Profile Section -->
                                <div class="section-title">
                                    <i class="fas fa-university text-primary me-2"></i>Profil Umum
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Profile Kampus</label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul profile kampus">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi Kampus</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi kampus"></textarea>
                                            <div class="text-danger error-text" id="deskripsi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visi Misi Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-eye text-success me-2"></i>Visi & Misi
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="visi" class="form-label">Visi</label>
                                                <textarea class="form-control" id="visi" name="visi" rows="6" placeholder="Masukkan visi kampus"></textarea>
                                                <div class="text-danger error-text" id="visi_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="misi" class="form-label">Misi</label>
                                                <textarea class="form-control" id="misi" name="misi" rows="6" placeholder="Masukkan misi kampus"></textarea>
                                                <div class="text-danger error-text" id="misi_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Struktur Organisasi Section -->
                                {{-- <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-sitemap text-warning me-2"></i>Struktur Organisasi
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="struktur_image" class="form-label">Gambar Struktur
                                                    Organisasi</label>
                                                <input type="file" class="form-control" id="struktur_image"
                                                    name="struktur_image" accept="image/*">
                                                <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG.
                                                    Maksimal 2MB.</small>
                                                <div class="text-danger error-text" id="struktur_image_error"></div>
                                                <div id="struktur-preview-container" class="image-preview-container mt-2"
                                                    style="display: none;">
                                                    <img id="struktur-preview" src="" alt="Preview"
                                                        class="image-preview">
                                                    <p class="text-muted small mb-0">Preview Struktur Organisasi</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- Fasilitas Section -->
                                <div class="section-divider">
                                    <div class="section-title">
                                        <i class="fas fa-building text-info me-2"></i>Fasilitas
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="fasilitas" class="form-label">Fasilitas Kampus</label>
                                                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="6"
                                                    placeholder="Masukkan fasilitas yang tersedia di kampus"></textarea>
                                                <div class="text-danger error-text" id="fasilitas_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button type="button" id="testApiBtn" class="btn btn-info btn-lg px-5 me-3">
                                        <i class="fas fa-link"></i> Test API Connection
                                    </button>
                                    <button type="submit" id="saveBtn" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save"></i> Simpan Profile Kampus
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
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Load existing data jika ada
            loadProfileKampus();

            // Preview struktur image saat file dipilih
            $('#struktur_image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#struktur-preview').attr('src', e.target.result);
                        $('#struktur-preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#struktur-preview-container').hide();
                }
            });

            // Load data profile kampus yang sudah ada
            function loadProfileKampus() {
                @if ($profileKampus)
                    const data = @json($profileKampus);

                    // Populate form fields
                    $('#judul').val(data.judul || '');
                    $('#deskripsi').val(data.deskripsi || '');
                    $('#visi').val(data.visi || '');
                    $('#misi').val(data.misi || '');
                    $('#fasilitas').val(data.fasilitas || '');

                    // Show existing images
                    var apiStorageUrl = '{{ config('api.storage_url') }}';

                    if (data.struktur_image) {
                        let strukturImageUrl = data.struktur_image;
                        if (!strukturImageUrl.startsWith('http')) {
                            strukturImageUrl = apiStorageUrl + data.struktur_image;
                        }
                        $('#struktur-preview').attr('src', strukturImageUrl);
                        $('#struktur-preview-container').show();
                    }
                @endif
            }

            // Test API Connection
            $('#testApiBtn').click(function() {
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Testing...');

                $.ajax({
                    url: "{{ route('profile-kampus.show', 1) }}",
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
                    $('#profileKampusForm')[0].reset();
                    $('.error-text').text('');
                    $('#struktur-preview-container').hide();
                    loadProfileKampus(); // Reload original data
                }
            });

            // Submit form
            $('#profileKampusForm').on('submit', function(e) {
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
                    url: "{{ route('profile-kampus.store') }}",
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
                                    'Profile kampus berhasil disimpan.',
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
                            '<i class="fas fa-save"></i> Simpan Profile Kampus');
                    }
                });
            });
        });
    </script>
@endpush
