@extends('layouts.index')
@section('title', 'Galeri')
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
            /* Latar belakang transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            /* Pastikan loader muncul di atas konten */
            border-radius: inherit;
            /* Membuat sudut tetap jika card memiliki border-radius */
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            /* Warna spinner */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Pastikan card body memiliki posisi relatif agar loader muncul di dalamnya */
        .card-body {
            position: relative;
        }

        /* Sembunyikan loader secara default */
        .loader-overlay.hidden {
            display: none;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
            /* Berputar 180 derajat saat dibuka */
        }

        .content-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Style untuk gambar di tabel */
        .table-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .table-image:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Style untuk preview container */
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Galeri</h3>
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
                    <a href="{{ route('galeri.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('galeri.index') }}">Galeri</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseGaleriForm" aria-expanded="true"
                        aria-controls="collapseGaleriForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Galeri
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseGaleriForm">
                        <div class="card-body">
                            <form id="galeriForm" name="galeriForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="galeri_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Galeri <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul galeri">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="form-label">Kategori <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="kategori" name="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <option value="kegiatan">Kegiatan</option>
                                                <option value="acara">Acara</option>
                                                <option value="wisuda">Wisuda</option>
                                                <option value="seminar">Seminar</option>
                                                <option value="kompetisi">Kompetisi</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                            <div class="text-danger error-text" id="kategori_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal" class="form-label">Tanggal <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                            <div class="text-danger error-text" id="tanggal_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="gambar" class="form-label">Gambar <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="gambar" name="gambar"
                                                accept="image/*">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF.
                                                Maksimal 2MB.</small>
                                            <div class="text-danger error-text" id="gambar_error"></div>
                                            <div id="preview-container" class="image-preview-container mt-2"
                                                style="display: none;">
                                                <img id="image-preview" src="" alt="Preview"
                                                    class="image-preview">
                                                <p class="text-muted small mb-0">Preview Gambar</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" placeholder="Masukkan deskripsi galeri"></textarea>
                                            <div class="text-danger error-text" id="deskripsi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" id="saveBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary ms-2">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Data Galeri</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="galeriTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($galeri as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if($item['gambar'])
                                                    <img src="{{ config('api.storage_url') . $item['gambar'] }}" 
                                                         alt="{{ $item['judul'] }}" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 80px; max-height: 60px; cursor: pointer;"
                                                         onclick="viewImage('{{ config('api.storage_url') . $item['gambar'] }}', '{{ $item['judul'] }}')">
                                                @else
                                                    <span class="text-muted">Tidak ada gambar</span>
                                                @endif
                                            </td>
                                            <td>{{ $item['judul'] }}</td>
                                            <td>
                                                @if($item['kategori'])
                                                    <span class="badge bg-info">{{ ucfirst($item['kategori']) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item['tanggal'])
                                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="{{ $item['deskripsi'] }}">
                                                    {{ Str::limit($item['deskripsi'], 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-warning btn-icon edit-btn" 
                                                            data-id="{{ $item['id'] }}" 
                                                            title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger btn-icon delete-btn" 
                                                            data-id="{{ $item['id'] }}" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-inbox"></i> Tidak ada data galeri
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Gambar -->
    <div class="modal fade" id="modalViewImage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Gambar Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageView" src="" alt="Galeri" class="img-fluid"
                        style="max-height: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalGaleri" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="galeriFormModal" name="galeriFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="galeri_id_modal">

                        <div class="form-group mb-3">
                            <label for="judul_modal" class="form-label">Judul Galeri <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_modal" name="judul"
                                placeholder="Masukkan judul galeri">
                            <div class="text-danger error-text" id="judul_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori_modal" class="form-label">Kategori <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="kategori_modal" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="acara">Acara</option>
                                <option value="wisuda">Wisuda</option>
                                <option value="seminar">Seminar</option>
                                <option value="kompetisi">Kompetisi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <div class="text-danger error-text" id="kategori_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_modal" class="form-label">Tanggal <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_modal" name="tanggal">
                                    <div class="text-danger error-text" id="tanggal_modal_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gambar_modal" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar_modal" name="gambar"
                                        accept="image/*">
                                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF. Maksimal
                                        2MB.</small>
                                    <div class="text-danger error-text" id="gambar_modal_error"></div>
                                    <div id="preview-container-modal" class="image-preview-container mt-2"
                                        style="display: none;">
                                        <img id="image-preview-modal" src="" alt="Preview" class="image-preview">
                                        <p class="text-muted small mb-0">Preview Gambar</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi_modal" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi_modal" name="deskripsi" rows="6" placeholder="Masukkan deskripsi galeri"></textarea>
                            <div class="text-danger error-text" id="deskripsi_modal_error"></div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" id="saveBtnModal" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Datatable
            $('#galeriTable').DataTable();

            // Ambil storage URL API dari config
            var apiStorageUrl = '{{ config('api.storage_url') }}';

            // View Image Function
            window.viewImage = function(imageSrc, title) {
                $('#modalImageView').attr('src', imageSrc);
                $('#imageModalTitle').text(title);
                $('#modalViewImage').modal('show');
            };

            // Edit Function - Use event delegation for dynamic content
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                $.get("/galeri/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Galeri');
                            $('#galeri_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            $('#kategori_modal').val(data.data.kategori);
                            $('#deskripsi_modal').val(data.data.deskripsi);
                            
                            // Format tanggal untuk input date (YYYY-MM-DD)
                            if (data.data.tanggal) {
                                var tanggalDate = new Date(data.data.tanggal);
                                var formattedDate = tanggalDate.toISOString().split('T')[0];
                                $('#tanggal_modal').val(formattedDate);
                            }
                            
                            // Show existing image if exists
                            if (data.data.gambar) {
                                $('#image-preview-modal').attr('src', apiStorageUrl + data.data.gambar);
                                $('#preview-container-modal').show();
                            } else {
                                $('#preview-container-modal').hide();
                            }
                            
                            $('#modalGaleri').modal('show');
                        } else {
                            Swal.fire('Error', 'Gagal mengambil data galeri', 'error');
                        }
                    })
                    .fail(function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                    });
            });

            // Delete Function - Use event delegation for dynamic content
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data galeri akan dihapus permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/galeri/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', 'Data galeri berhasil dihapus', 'success');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    Swal.fire('Error', 'Gagal menghapus data galeri', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                            }
                        });
                    }
                });
            });

            // Store/Update Form Submit
            $('#galeriForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('galeri.store') }}";
                
                // Add CSRF token to FormData
                formData.append('_token', '{{ csrf_token() }}');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data galeri berhasil ditambahkan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // Reset form
                            $('#galeriForm')[0].reset();
                            $('#galeri_id').val('');
                            
                            // Reload page untuk melihat data baru
                            setTimeout(() => {
                                location.reload();
                            }, 1600);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    var fieldId = key + '_error';
                                    $('#' + fieldId).text(value[0]);
                                });
                            }
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    }
                });
            });

            // Update Form Submit (Modal)
            $('#galeriFormModal').submit(function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var id = $('#galeri_id_modal').val();
                var url = '/galeri/' + id;
                
                // Add CSRF token to FormData
                formData.append('_token', '{{ csrf_token() }}');
                
                // For PUT method with file, we need to use POST with _method parameter
                if ($('#gambar_modal')[0].files.length > 0) {
                    formData.append('_method', 'PUT');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            handleUpdateResponse(response);
                        },
                        error: function(xhr) {
                            handleUpdateError(xhr);
                        }
                    });
                } else {
                    // No file, use PUT
                    var data = {
                        'judul': $('#judul_modal').val(),
                        'kategori': $('#kategori_modal').val(),
                        'deskripsi': $('#deskripsi_modal').val(),
                        'tanggal': $('#tanggal_modal').val(),
                        '_token': '{{ csrf_token() }}',
                        '_method': 'PUT'
                    };
                    
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            handleUpdateResponse(response);
                        },
                        error: function(xhr) {
                            handleUpdateError(xhr);
                        }
                    });
                }
                
                function handleUpdateResponse(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data galeri berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        $('#modalGaleri').modal('hide');
                        
                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        if (response.errors) {
                            $('.error-text').text('');
                            $.each(response.errors, function(key, value) {
                                var fieldId = key + '_modal_error';
                                $('#' + fieldId).text(value[0]);
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                }
                
                function handleUpdateError(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        $('.error-text').text('');
                        $.each(response.errors, function(key, value) {
                            var fieldId = key + '_modal_error';
                            $('#' + fieldId).text(value[0]);
                        });
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan saat memperbarui data', 'error');
                    }
                }
            });

            // Reset Form
            $('#resetBtn').click(function() {
                $('#galeriForm')[0].reset();
                $('#preview-container').hide();
                $('.error-text').text('');
            });

            // Image Preview
            $('#gambar, #gambar_modal').change(function() {
                var preview = $(this).attr('id') === 'gambar' ? '#image-preview' : '#image-preview-modal';
                var container = $(this).attr('id') === 'gambar' ? '#preview-container' : '#preview-container-modal';
                
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(preview).attr('src', e.target.result);
                        $(container).show();
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $(container).hide();
                }
            });
        });
    </script>
@endpush
