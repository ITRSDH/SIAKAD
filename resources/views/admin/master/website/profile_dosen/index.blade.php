@extends('layouts.index')
@section('title', 'Profile Dosen')
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
            <h3 class="fw-bold mb-3">Profile Dosen</h3>
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
                    <a href="{{ route('profile-dosen.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile-dosen.index') }}">Profile Dosen</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseProfileDosenForm" aria-expanded="true"
                        aria-controls="collapseProfileDosenForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Profile Dosen
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseProfileDosenForm">
                        <div class="card-body">
                            <form id="profileDosenForm" name="profileDosenForm" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="profile_dosen_id">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nama" class="form-label">Nama Dosen <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                placeholder="Masukkan nama dosen">
                                            <div class="text-danger error-text" id="nama_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nidn" class="form-label">NIDN <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nidn" name="nidn"
                                                placeholder="Masukkan NIDN">
                                            <div class="text-danger error-text" id="nidn_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="id_prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                            <select class="form-select" id="id_prodi" name="id_prodi">
                                                <option value="">Pilih Program Studi</option>
                                                @foreach ($prodi as $p)
                                                    @php
                                                        $namaJenjang = $p['jenjang_pendidikan'] ?? '';
                                                    @endphp
                                                    <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}@if ($namaJenjang)
                                                            ({{ $namaJenjang }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger error-text" id="id_prodi_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="">Pilih Status</option>
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                            </select>
                                            <div class="text-danger error-text" id="status_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="biografi" class="form-label">Biografi <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="biografi" name="biografi" rows="8" placeholder="Masukkan biografi dosen"></textarea>
                                            <div class="text-danger error-text" id="biografi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="foto" class="form-label">Foto Dosen</label>
                                            <input type="file" class="form-control" id="foto" name="foto"
                                                accept="image/jpeg, image/jpg, image/png, image/webp">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP.
                                                Maksimal 2MB.</small>
                                            <div class="text-danger error-text" id="foto_error"></div>
                                            <div id="preview-container" class="image-preview-container mt-2"
                                                style="display: none;">
                                                <img id="image-preview" src="" alt="Preview"
                                                    class="image-preview">
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
                            <i class="fas fa-list text-primary me-2"></i>Data Profile Dosen
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="profile-dosen-table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 8%;">Gambar</th>
                                        <th style="width: 18%;">Nama Dosen</th>
                                        <th style="width: 18%;">Program Studi</th>
                                        <th style="width: 10%;">NIDN</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 26%;">Biografi</th>
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
                    <h5 class="modal-title" id="imageModalTitle">Gambar Ormawa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageView" src="" alt="Ormawa" class="img-fluid"
                        style="max-height: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalProfileDosen" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeadingProfileDosen"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="profileDosenFormModal" name="profileDosenFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="profile_dosen_id_modal">

                        <div class="form-group mb-3">
                            <label for="nama_modal" class="form-label">Nama Dosen <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_modal" name="nama"
                                placeholder="Masukkan nama dosen">
                            <div class="text-danger error-text" id="nama_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nidn_modal" class="form-label">NIDN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nidn_modal" name="nidn"
                                        placeholder="Masukkan NIDN">
                                    <div class="text-danger error-text" id="nidn_modal_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_modal" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_modal" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                    <div class="text-danger error-text" id="status_modal_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="id_prodi_modal" class="form-label">Program Studi <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_prodi_modal" name="id_prodi">
                                <option value="">Pilih Program Studi</option>
                                @foreach ($prodi as $p)
                                    @php
                                        $namaJenjang = $p['jenjang_pendidikan'] ?? '';
                                    @endphp
                                    <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}@if ($namaJenjang)
                                            ({{ $namaJenjang }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-text" id="id_prodi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="biografi_modal" class="form-label">Biografi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="biografi_modal" name="biografi" rows="6"
                                placeholder="Masukkan biografi dosen"></textarea>
                            <div class="text-danger error-text" id="biografi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="foto_modal" class="form-label">Foto Dosen</label>
                            <input type="file" class="form-control" id="foto_modal" name="foto"
                                accept="image/jpeg, image/jpg, image/png, image/webp">
                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP. Maksimal
                                2MB.</small>
                            <div class="text-danger error-text" id="foto_modal_error"></div>
                            <div id="preview-container-modal" class="image-preview-container mt-2"
                                style="display: none;">
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
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var profileDosenData = @json($profileDosen ?? []);
            var prodiData = @json($prodi ?? []);
            // Ambil storage URL API dari config
            var apiStorageUrl = '{{ config('api.storage_url') }}';

            const prodiMap = {};
            if (Array.isArray(prodiData)) {
                prodiData.forEach(function(p) {
                    prodiMap[String(p.id)] = p.nama_prodi;
                });
            }

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#profile-dosen-table').DataTable({
                data: profileDosenData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            // Kolom No (indeks baris + 1)
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'foto',
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
                                        <img src="${imageUrl}" alt="Foto ${row.nama || ''}"
                                             class="table-image"
                                             onclick="showImageModal('${imageUrl}', '${row.nama || 'Dosen'}')"
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
                        data: 'nama',
                        render: function(data, type, row) {
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        data: 'id_prodi',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">-</span>';
                            const namaProdi = prodiMap[String(data)] || '-';
                            return `<span>${namaProdi}</span>`;
                        }
                    },
                    {
                        data: 'nidn',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            if (data === 'Aktif') return '<span class="badge bg-success">Aktif</span>';
                            if (data === 'Tidak Aktif') return '<span class="badge bg-secondary">Tidak Aktif</span>';
                            return '<span class="badge bg-light text-dark">-</span>';
                        }
                    },
                    {
                        data: 'biografi',
                        render: function(data) {
                            if (data && data.length > 100) {
                                return '<div class="content-preview" title="' + data + '">' + data
                                    .substring(0, 100) + '...</div>';
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning edit-btn"
                                        data-id="${row.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="${row.id}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
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
                $('#profileDosenForm')[0].reset();
                $('#profile_dosen_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#preview-container').hide();
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#profileDosenForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Validasi required fields
                const nama = $('#nama').val().trim();
                const idProdi = $('#id_prodi').val();
                const nidn = $('#nidn').val().trim();
                const status = $('#status').val();
                const biografi = $('#biografi').val().trim();
                if (!nama || !idProdi || !nidn || !status || !biografi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Gunakan FormData untuk mengirim file
                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                const $saveBtn = $('#saveBtn');
                const originalSaveBtnHtml = $saveBtn.html();
                $saveBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...'
                );

                $.ajax({
                    url: "{{ route('profile-dosen.store') }}",
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
                            table.row.add(response.data).draw();
                            // Reset form
                            $('#profileDosenForm')[0].reset();
                            $('#preview-container').hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Profile dosen berhasil ditambahkan.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
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
                                        0
                                    ]);
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
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
                        } else if (xhr.responseText) {
                            // Tampilkan response text jika tidak ada JSON
                            errorMessage = 'Error: ' + xhr.responseText.substring(0, 200) +
                                '...';
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
                                $('#' + key + '_error').text(xhr.responseJSON.errors[
                                    key][0]);
                            });
                        }
                    },
                    complete: function() {
                        // Aktifkan kembali tombol setelah permintaan selesai
                        $saveBtn.prop('disabled', false).html(originalSaveBtnHtml);
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                // Reset modal sebelum mengisi data
                $('#profileDosenFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();

                // Ambil data berita spesifik dari API
                $.get("{{ route('profile-dosen.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#profile_dosen_id_modal').val(data.data.id);
                            $('#nama_modal').val(data.data.nama);
                            $('#nidn_modal').val(data.data.nidn);
                            $('#status_modal').val(data.data.status);
                            $('#id_prodi_modal').val(data.data.id_prodi);
                            $('#biografi_modal').val(data.data.biografi);

                            // Handle gambar jika ada
                            if (data.data.foto) {
                                let imageUrl = data.data.foto;

                                // Handle different URL formats
                                if (data.data.foto.startsWith('http://') || data.data.foto
                                    .startsWith('https://')) {
                                    // Absolute URL - use as is
                                    imageUrl = data.data.foto;
                                } else if (data.data.foto.startsWith('/')) {
                                    // Relative URL starting with / - could be from API server
                                    if (data.data.foto.startsWith('/storage/')) {
                                        imageUrl = data.data.foto; // Local storage
                                    } else {
                                        // Assume it's from API server
                                        imageUrl = apiStorageUrl.replace('/storage/', '') + data.data
                                            .foto;
                                    }
                                } else {
                                    // Plain filename or relative path
                                    if (data.data.foto.includes('/')) {
                                        // Has path separators, likely from API storage
                                        imageUrl = apiStorageUrl + data.data.foto;
                                    } else {
                                        // Plain filename, try local storage first
                                        imageUrl = '/storage/' + data.data.foto;
                                    }
                                }

                                $('#preview-container-modal').show();
                                $('#image-preview-modal').attr('src', imageUrl);
                            } else {
                                $('#preview-container-modal').hide();
                            }

                            $('#modelHeadingProfileDosen').text('Edit Profile Dosen');
                            $('#modalProfileDosen').modal('show');
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
            $('#profileDosenFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#profile_dosen_id_modal').val();

                const nama = $('#nama_modal').val().trim();
                const nidn = $('#nidn_modal').val().trim();
                const status = $('#status_modal').val();
                const idProdi = $('#id_prodi_modal').val();
                const biografi = $('#biografi_modal').val().trim();

                if (!id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'ID profile dosen tidak ditemukan!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (!nama || !nidn || !status || !idProdi || !biografi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                const $saveBtnModal = $('#saveBtnModal');
                const originalSaveBtnModalHtml = $saveBtnModal.html();
                $saveBtnModal.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...'
                );

                $.ajax({
                    url: "{{ route('profile-dosen.update', '') }}/" + id,
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
                            var rowIndex = table.row(function(idx, data, node) {
                                return data.id == id;
                            }).index();
                            if (rowIndex !== undefined && response.data) {
                                table.row(rowIndex).data(response.data).draw();
                            }
                            // Tutup modal
                            $('#modalProfileDosen').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Profile dosen berhasil diperbarui.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat memperbarui data.',
                                confirmButtonText: 'OK'
                            });
                            // Tampilkan error spesifik jika ada
                            if (response.errors) {
                                Object.keys(response.errors).forEach(function(key) {
                                    $('#' + key + '_modal_error').text(response.errors[
                                        key][0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
                        } else if (xhr.responseText) {
                            errorMessage = 'Error: ' + xhr.responseText.substring(0, 200) +
                                '...';
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
                                $('#' + key + '_modal_error').text(xhr.responseJSON
                                    .errors[key][0]);
                            });
                        }
                    },
                    complete: function() {
                        $saveBtnModal.prop('disabled', false).html(originalSaveBtnModalHtml);
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                // Ganti confirm dengan SweetAlert2
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Profile dosen ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('profile-dosen.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    var rowIndex = table.row(function(idx, data, node) {
                                        return data.id == id;
                                    }).index();
                                    if (rowIndex !== undefined) {
                                        table.row(rowIndex).remove().draw();
                                    }
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'Profile dosen berhasil dihapus.',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menghapus data.',
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
            $('#foto').on('change', function(e) {
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
            $('#foto_modal').on('change', function(e) {
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
            $('#modalProfileDosen').on('hidden.bs.modal', function() {
                $('#profileDosenFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();
                $('#profile_dosen_id_modal').val('');
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
