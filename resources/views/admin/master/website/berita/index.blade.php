@extends('layouts.index')
@section('title', 'Berita')
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
            <h3 class="fw-bold mb-3">Berita</h3>
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
                    <a href="{{ route('berita.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('berita.index') }}">Berita</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseBeritaForm" aria-expanded="true"
                        aria-controls="collapseBeritaForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Berita
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseBeritaForm">
                        <div class="card-body">
                            <form id="beritaForm" name="beritaForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="berita_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Berita <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul berita">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="form-label">Kategori</label>
                                            <select class="form-select" id="kategori" name="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <option value="akademik">Akademik</option>
                                                <option value="non-akademik">Non-Akademik</option>
                                                <option value="pengumuman">Pengumuman</option>
                                                <option value="kegiatan">Kegiatan</option>
                                                <option value="berita_umum">Berita Umum</option>
                                            </select>
                                            <div class="text-danger error-text" id="kategori_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Berita</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                            <div class="text-danger error-text" id="tanggal_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="gambar" class="form-label">Gambar Berita</label>
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
                                            <label for="isi" class="form-label">Isi Berita <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="isi" name="isi" rows="8" placeholder="Masukkan isi berita"></textarea>
                                            <div class="text-danger error-text" id="isi_error"></div>
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
                        <h3 class="card-title mb-0">Data Berita</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="beritaTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Judul Berita</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Isi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                    <h5 class="modal-title" id="imageModalTitle">Gambar Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageView" src="" alt="Berita" class="img-fluid"
                        style="max-height: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalBerita" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="beritaFormModal" name="beritaFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="berita_id_modal">

                        <div class="form-group mb-3">
                            <label for="judul_modal" class="form-label">Judul Berita <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_modal" name="judul"
                                placeholder="Masukkan judul berita">
                            <div class="text-danger error-text" id="judul_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori_modal" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori_modal" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="akademik">Akademik</option>
                                <option value="non-akademik">Non-Akademik</option>
                                <option value="pengumuman">Pengumuman</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="berita_umum">Berita Umum</option>
                            </select>
                            <div class="text-danger error-text" id="kategori_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_modal" class="form-label">Tanggal Berita</label>
                                    <input type="date" class="form-control" id="tanggal_modal" name="tanggal">
                                    <div class="text-danger error-text" id="tanggal_modal_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gambar_modal" class="form-label">Gambar Berita</label>
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
                            <label for="isi_modal" class="form-label">Isi Berita <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_modal" name="isi" rows="6" placeholder="Masukkan isi berita"></textarea>
                            <div class="text-danger error-text" id="isi_modal_error"></div>
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
            // Ambil data dari variabel PHP yang dilewatkan ke view (pola seperti Prestasi/Pengumuman/FAQ)
            var beritaData = @json($berita);

            // Ambil storage URL API dari config
            var apiStorageUrl = '{{ config('api.storage_url') }}';

            function stripHtml(html) {
                if (!html) return '';
                return $('<div>').html(html).text();
            }

            function truncateText(text, maxLen) {
                if (!text) return '-';
                if (text.length <= maxLen) return text;
                return text.substring(0, maxLen) + '...';
            }

            function formatTanggalIndonesia(dateStr) {
                if (!dateStr) return null;
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return null;
                return d.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Initialize DataTable client-side dari data PHP
            const table = $('#beritaTable').DataTable({
                data: beritaData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'gambar',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">Tidak ada gambar</span>';
                            const title = (row.judul ?? 'Berita').replace(/\"/g, '&quot;');
                            const imageUrl = apiStorageUrl + data;
                            return `<img src="${imageUrl}" alt="${title}" class="img-thumbnail" style="max-width: 80px; max-height: 60px; cursor: pointer;" onclick="viewImage('${imageUrl}', '${title}')">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        defaultContent: '-'
                    },
                    {
                        data: 'kategori',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            const kategori = String(data);
                            return `<span class="badge bg-info">${kategori.charAt(0).toUpperCase() + kategori.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'tanggal',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            const formatted = formatTanggalIndonesia(data);
                            return formatted ?? data;
                        }
                    },
                    {
                        data: 'isi',
                        render: function(data) {
                            const plain = stripHtml(data);
                            const short = truncateText(plain, 50);
                            const escapedTitle = String(plain).replace(/\"/g, '&quot;');
                            return `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${escapedTitle}">${short}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-warning btn-icon edit-btn" data-id="${row.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon delete-btn" data-id="${row.id}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

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
                
                $.get("/berita/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Berita');
                            $('#berita_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            $('#kategori_modal').val(data.data.kategori);
                            $('#isi_modal').val(data.data.isi);
                            
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
                            
                            $('#modalBerita').modal('show');
                        } else {
                            Swal.fire('Error', 'Gagal mengambil data berita', 'error');
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
                    text: 'Data berita akan dihapus permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/berita/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', 'Data berita berhasil dihapus', 'success');
                                    table.row($('button[data-id="' + id + '"]').closest('tr')).remove().draw(false);
                                } else {
                                    Swal.fire('Error', 'Gagal menghapus data berita', 'error');
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
            $('#beritaForm').on('submit', function(e) {
                e.preventDefault();

                const $saveBtn = $('#saveBtn');
                const originalSaveBtnHtml = $saveBtn.html();
                $saveBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...'
                );
                
                var formData = new FormData(this);
                var url = "{{ route('berita.store') }}";
                
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
                                text: 'Data berita berhasil ditambahkan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // Reset form
                            $('#beritaForm')[0].reset();
                            $('#preview-container').hide();

                            if (response.data) {
                                table.row.add(response.data).draw(false);
                            }
                        } else {
                            // Handle validation errors
                            if (response.errors) {
                                // Clear previous errors
                                $('.error-text').text('');
                                
                                // Show validation errors
                                $.each(response.errors, function(key, value) {
                                    var fieldId = key + '_error';
                                    $('#' + fieldId).text(value[0]);
                                });
                            } else {
                                Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
                            }
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.errors) {
                            // Clear previous errors
                            $('.error-text').text('');
                            
                            // Show validation errors
                            $.each(response.errors, function(key, value) {
                                var fieldId = key + '_error';
                                $('#' + fieldId).text(value[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    },
                    complete: function() {
                        $saveBtn.prop('disabled', false).html(originalSaveBtnHtml);
                    }
                });
            });

            // Update Form Submit (Modal)
            $('#beritaFormModal').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var id = $('#berita_id_modal').val();
                var url = '/berita/' + id;
                
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
                        'isi': $('#isi_modal').val(),
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
                            text: 'Data berita berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        if (response.data) {
                            const row = table.row($('button[data-id="' + id + '"]').closest('tr'));
                            row.data(response.data).invalidate().draw(false);
                        }

                        $('#modalBerita').modal('hide');
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
                $('#beritaForm')[0].reset();
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
