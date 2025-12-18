@extends('admin.layouts.index')
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .table-image:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
                            <form id="beritaForm" name="beritaForm">
                                @csrf
                                <input type="hidden" name="id" id="berita_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul" 
                                                placeholder="Masukkan judul berita">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="form-label">Kategori</label>
                                            <select class="form-select" id="kategori" name="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <option value="akademik">Akademik</option>
                                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                                <option value="pengumuman">Pengumuman</option>
                                                <option value="kegiatan">Kegiatan</option>
                                                <option value="berita_umum">Berita Umum</option>
                                            </select>
                                            <div class="text-danger error-text" id="kategori_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Berita</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                            <div class="text-danger error-text" id="tanggal_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="isi" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="isi" name="isi" rows="8" 
                                                placeholder="Masukkan isi berita"></textarea>
                                            <div class="text-danger error-text" id="isi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="gambar" class="form-label">Gambar Berita</label>
                                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                                            <div class="text-danger error-text" id="gambar_error"></div>
                                            <div id="preview-container" class="image-preview-container mt-2" style="display: none;">
                                                <img id="image-preview" src="" alt="Preview" class="image-preview">
                                                <p class="text-muted small mb-0">Preview Gambar</p>
                                            </div>
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
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>Data Berita
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="berita-table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 8%;">Gambar</th>
                                        <th style="width: 25%;">Judul Berita</th>
                                        <th style="width: 12%;">Kategori</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th style="width: 33%;">Isi Berita</th>
                                        <th style="width: 8%;">Aksi</th>
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
                    <img id="modalImageView" src="" alt="Berita" class="img-fluid" style="max-height: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
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
                            <label for="judul_modal" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_modal" name="judul" 
                                placeholder="Masukkan judul berita">
                            <div class="text-danger error-text" id="judul_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kategori_modal" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori_modal" name="kategori">
                                        <option value="">Pilih Kategori</option>
                                        <option value="akademik">Akademik</option>
                                        <option value="kemahasiswaan">Kemahasiswaan</option>
                                        <option value="pengumuman">Pengumuman</option>
                                        <option value="kegiatan">Kegiatan</option>
                                        <option value="berita_umum">Berita Umum</option>
                                    </select>
                                    <div class="text-danger error-text" id="kategori_modal_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_modal" class="form-label">Tanggal Berita</label>
                                    <input type="date" class="form-control" id="tanggal_modal" name="tanggal">
                                    <div class="text-danger error-text" id="tanggal_modal_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="isi_modal" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_modal" name="isi" rows="6" 
                                placeholder="Masukkan isi berita"></textarea>
                            <div class="text-danger error-text" id="isi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar_modal" class="form-label">Gambar Berita</label>
                            <input type="file" class="form-control" id="gambar_modal" name="gambar" accept="image/*">
                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                            <div class="text-danger error-text" id="gambar_modal_error"></div>
                            <div id="preview-container-modal" class="image-preview-container mt-2" style="display: none;">
                                <img id="image-preview-modal" src="" alt="Preview" class="image-preview">
                                <p class="text-muted small mb-0">Preview Gambar</p>
                            </div>
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
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil storage URL API dari config
            var apiStorageUrl = '{{ config("api.storage_url") }}';

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#berita-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('berita.datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'gambar',
                        render: function(data, type, row) {
                            if (data) {
                                let imageUrl = data;
                                
                                // Handle different URL formats
                                if (data.startsWith('http://') || data.startsWith('https://')) {
                                    // Absolute URL - use as is
                                    imageUrl = data;
                                } else if (data.startsWith('/')) {
                                    // Relative URL starting with / - could be from API server
                                    if (data.startsWith('/storage/')) {
                                        imageUrl = data; // Local storage
                                    } else {
                                        // Assume it's from API server
                                        imageUrl = apiStorageUrl.replace('/storage/', '') + data;
                                    }
                                } else {
                                    // Plain filename or relative path
                                    // Check if it contains folder or similar pattern
                                    if (data.includes('/')) {
                                        // Has path separators, likely from API storage
                                        imageUrl = apiStorageUrl + data;
                                    } else {
                                        // Plain filename, try local storage first, fallback to API
                                        imageUrl = '/storage/' + data;
                                    }
                                }
                                
                                return `
                                    <div class="text-center">
                                        <img src="${imageUrl}" alt="Berita ${row.judul || ''}" 
                                             class="table-image" 
                                             onclick="showImageModal('${imageUrl}', '${row.judul || 'Berita'}')" 
                                             title="Klik untuk memperbesar"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div style="display:none;" class="text-center">
                                            <span class="badge bg-warning">Image Error</span>
                                        </div>
                                    </div>
                                `;
                            }
                            return '<div class="text-center"><span class="badge bg-secondary">No Image</span></div>';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul'
                    },
                    {
                        data: 'kategori',
                        render: function(data, type, row) {
                            const kategoriMap = {
                                'akademik': '<span class="badge bg-primary">Akademik</span>',
                                'kemahasiswaan': '<span class="badge bg-success">Kemahasiswaan</span>',
                                'pengumuman': '<span class="badge bg-info">Pengumuman</span>',
                                'kegiatan': '<span class="badge bg-warning">Kegiatan</span>',
                                'berita_umum': '<span class="badge bg-secondary">Berita Umum</span>'
                            };
                            return kategoriMap[data] || '<span class="badge bg-light text-dark">-</span>';
                        }
                    },
                    {
                        data: 'tanggal',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('id-ID');
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'isi',
                        render: function(data, type, row) {
                            // Batasi panjang isi berita dan tambahkan ellipsis jika terlalu panjang
                            if (data && data.length > 100) {
                                return '<div class="content-preview" title="' + data + '">' + data.substring(0, 100) + '...</div>';
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json' // Bahasa Indonesia
                },
                drawCallback: function(settings) {
                    // Sembunyikan loader setelah tabel selesai digambar
                    $('#tableLoader').addClass('hidden');
                }
            });

            // Reset form
            $('#resetBtn').click(function() {
                $('#beritaForm')[0].reset();
                $('#berita_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#preview-container').hide();
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create  
            $('#beritaForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Validasi required fields
                const judul = $('#judul').val().trim();
                const isi = $('#isi').val().trim();
                
                if (!judul || !isi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib (Judul dan Isi Berita)!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Gunakan FormData untuk mengirim file
                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('berita.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Tambah data ke tabel
                            table.ajax.reload();
                            // Reset form
                            $('#beritaForm')[0].reset();
                            $('#preview-container').hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Berita berhasil ditambahkan.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                                confirmButtonText: 'OK'
                            });
                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_error').text(response.errors[key][0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        console.error('Response Text:', xhr.responseText);
                        console.error('Status:', xhr.status);
                        
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                        } else if (xhr.responseText) {
                            // Tampilkan response text jika tidak ada JSON
                            errorMessage = 'Error: ' + xhr.responseText.substring(0, 200) + '...';
                        }
                        
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        
                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                $('#' + key + '_error').text(xhr.responseJSON.errors[key][0]);
                            });
                        }
                    },
                    complete: function() {
                        // Aktifkan kembali tombol setelah permintaan selesai
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
                        );
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                
                // Reset modal sebelum mengisi data
                $('#beritaFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();
                
                // Ambil data berita spesifik dari API
                $.get("{{ route('berita.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#berita_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            $('#kategori_modal').val(data.data.kategori);
                            $('#isi_modal').val(data.data.isi);
                            
                            // Format tanggal untuk input date
                            let tanggalValue = '';
                            if (data.data.tanggal) {
                                // Convert tanggal ke format YYYY-MM-DD untuk input date
                                const tanggalDate = new Date(data.data.tanggal);
                                if (!isNaN(tanggalDate.getTime())) {
                                    // Format ke YYYY-MM-DD
                                    const year = tanggalDate.getFullYear();
                                    const month = String(tanggalDate.getMonth() + 1).padStart(2, '0');
                                    const day = String(tanggalDate.getDate()).padStart(2, '0');
                                    tanggalValue = `${year}-${month}-${day}`;
                                }
                            }
                            $('#tanggal_modal').val(tanggalValue);
                            
                            // Handle gambar jika ada
                            if (data.data.gambar) {
                                let imageUrl = data.data.gambar;
                                
                                // Handle different URL formats
                                if (data.data.gambar.startsWith('http://') || data.data.gambar.startsWith('https://')) {
                                    // Absolute URL - use as is
                                    imageUrl = data.data.gambar;
                                } else if (data.data.gambar.startsWith('/')) {
                                    // Relative URL starting with / - could be from API server
                                    if (data.data.gambar.startsWith('/storage/')) {
                                        imageUrl = data.data.gambar; // Local storage
                                    } else {
                                        // Assume it's from API server
                                        imageUrl = apiStorageUrl.replace('/storage/', '') + data.data.gambar;
                                    }
                                } else {
                                    // Plain filename or relative path
                                    if (data.data.gambar.includes('/')) {
                                        // Has path separators, likely from API storage
                                        imageUrl = apiStorageUrl + data.data.gambar;
                                    } else {
                                        // Plain filename, try local storage first
                                        imageUrl = '/storage/' + data.data.gambar;
                                    }
                                }
                                
                                $('#preview-container-modal').show();
                                $('#image-preview-modal').attr('src', imageUrl);
                            } else {
                                $('#preview-container-modal').hide();
                            }
                            
                            $('#modelHeading').text('Edit Berita');
                            $('#modalBerita').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Data tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .fail(function(xhr) {
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal
            $('#beritaFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#berita_id_modal').val();
                
                // Validasi required fields untuk modal
                const judul = $('#judul_modal').val().trim();
                const isi = $('#isi_modal').val().trim();
                
                if (!id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'ID berita tidak ditemukan!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                if (!judul || !isi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib (Judul dan Isi Berita)!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('berita.update', '') }}/" + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update data di tabel dengan cara mencari row dan memperbarui data
                            table.ajax.reload(null, false); // Reload tanpa reset paging
                            // Tutup modal
                            $('#modalBerita').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Berita berhasil diperbarui.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat memperbarui data.',
                                confirmButtonText: 'OK'
                            });
                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_modal_error').text(response.errors[key][0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                        } else if (xhr.responseText) {
                            errorMessage = 'Error: ' + xhr.responseText.substring(0, 200) + '...';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });

                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                $('#' + key + '_modal_error').text(xhr.responseJSON.errors[key][0]);
                            });
                        }
                    },
                    complete: function() {
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Update'
                        );
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                // Ganti confirm dengan SweetAlert2
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Berita ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('berita.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    table.ajax.reload(null, false); // Reload tanpa reset paging
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message || 'Berita berhasil dihapus.',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message || 'Terjadi kesalahan saat menghapus data.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Gagal menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Preview gambar saat file dipilih
            $('#gambar').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#preview-container').hide();
                }
            });

            // Preview gambar modal saat file dipilih
            $('#gambar_modal').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview-modal').attr('src', e.target.result);
                        $('#preview-container-modal').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#preview-container-modal').hide();
                }
            });

            // Modal close handlers untuk reset form
            $('#modalBerita').on('hidden.bs.modal', function() {
                $('#beritaFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();
                $('#berita_id_modal').val('');
            });
        });

        // Function untuk menampilkan modal gambar
        function showImageModal(imageUrl, title) {
            $('#modalImageView').attr('src', imageUrl);
            $('#imageModalTitle').text(title);
            $('#modalViewImage').modal('show');
        }

        // Function untuk debug gambar yang gagal load
        $(document).on('error', 'img.table-image', function() {
            // Image failed to load, error already handled by onerror attribute
        });
    </script>
@endpush
