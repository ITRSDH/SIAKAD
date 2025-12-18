@extends('admin.layouts.index')
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
                            <form id="galeriForm" name="galeriForm">
                                @csrf
                                <input type="hidden" name="id" id="galeri_id">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Galeri <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul galeri">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="tanggal" class="form-label">Tanggal <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                            <div class="text-danger error-text" id="tanggal_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" placeholder="Masukkan deskripsi galeri"></textarea>
                                            <div class="text-danger error-text" id="deskripsi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="gambar" class="form-label">Gambar Galeri</label>
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
                            <table id="galeri-table" class="table table-striped table-bordered w-100">
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

                        <div class="form-group mb-3">
                            <label for="tanggal_modal" class="form-label">Tanggal <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_modal" name="tanggal">
                            <div class="text-danger error-text" id="tanggal_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi_modal" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi_modal" name="deskripsi" rows="6"
                                placeholder="Masukkan deskripsi galeri"></textarea>
                            <div class="text-danger error-text" id="deskripsi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar_modal" class="form-label">Gambar Galeri</label>
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
            var apiStorageUrl = '{{ config('api.storage_url') }}';

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#galeri-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('galeri.datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'gambar',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data) {
                                let imageUrl = data;
                                // Handle different URL formats
                                if (!data.startsWith('http://') && !data.startsWith('https://')) {
                                    imageUrl = apiStorageUrl + data;
                                }
                                return '<img src="' + imageUrl + '" alt="Gambar" class="table-image" onclick="showImageModal(\'' + imageUrl + '\', \'' + (row.judul || 'Galeri') + '\')" style="cursor: pointer;">';
                            }
                            return '<span class="badge bg-secondary">No Image</span>';
                        }
                    },
                    {
                        data: 'judul'
                    },
                    {
                        data: 'kategori',
                        render: function(data, type, row) {
                            const kategoriMap = {
                                'kegiatan': '<span class="badge bg-primary">Kegiatan</span>',
                                'acara': '<span class="badge bg-success">Acara</span>',
                                'wisuda': '<span class="badge bg-info">Wisuda</span>',
                                'seminar': '<span class="badge bg-warning">Seminar</span>',
                                'kompetisi': '<span class="badge bg-danger">Kompetisi</span>',
                                'lainnya': '<span class="badge bg-secondary">Lainnya</span>',
                                'umum': '<span class="badge bg-light text-dark">Umum</span>'
                            };
                            return kategoriMap[data] || '<span class="badge bg-light text-dark">-</span>';
                        }
                    },
                    {
                        data: 'tanggal',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'deskripsi',
                        render: function(data, type, row) {
                            if (data && data.length > 50) {
                                return data.substring(0, 50) + '...';
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
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                }
            });

            // Reset form
            $('#resetBtn').click(function() {
                $('#galeriForm')[0].reset();
                $('#galeri_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#preview-container').hide();
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create  
            $('#galeriForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Validasi required fields
                const judul = $('#judul').val();
                const kategori = $('#kategori').val();
                const tanggal = $('#tanggal').val();
                const gambar = $('#gambar')[0].files[0];

                if (!judul || !kategori || !tanggal || !gambar) {
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
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('galeri.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reload DataTable untuk refresh data
                            table.ajax.reload();
                            // Reset form
                            $('#galeriForm')[0].reset();
                            $('#preview-container').hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Galeri berhasil ditambahkan.',
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
                $('#galeriFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();

                // Ambil data galeri spesifik dari API
                $.get("{{ route('galeri.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#galeri_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            
                            // Set kategori dengan normalisasi (lowercase & trim)
                            const kategori = (data.data.kategori || '').toString().toLowerCase().trim();
                            console.log('Setting kategori:', kategori);
                            $('#kategori_modal').val(kategori);

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

                            $('#deskripsi_modal').val(data.data.deskripsi);

                            // Handle gambar jika ada
                            if (data.data.gambar) {
                                let imageUrl = data.data.gambar;

                                // Handle different URL formats
                                if (!data.data.gambar.startsWith('http://') && !data.data.gambar
                                    .startsWith('https://')) {
                                    // Bukan absolute URL, tambahkan base storage URL
                                    imageUrl = apiStorageUrl + data.data.gambar;
                                }

                                console.log('Edit Modal Image URL:', imageUrl);

                                $('#preview-container-modal').show();
                                $('#image-preview-modal').attr('src', imageUrl);
                            } else {
                                $('#preview-container-modal').hide();
                            }

                            $('#modelHeading').text('Edit Galeri');
                            $('#modalGaleri').modal('show');
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
                        console.error('Error fetching data for edit:', xhr);
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
            $('#galeriFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#galeri_id_modal').val();

                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('galeri.update', '') }}/" + id,
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
                            // Reload DataTable untuk refresh data
                            table.ajax.reload();
                            // Tutup modal
                            $('#modalGaleri').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Galeri berhasil diperbarui.',
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
                        console.error('AJAX Error:', xhr);
                        console.error('Response Text:', xhr.responseText);
                        console.error('Status:', xhr.status);

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
                    text: "Galeri ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('galeri.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Reload DataTable untuk refresh data
                                    table.ajax.reload();
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'Galeri berhasil dihapus.',
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
                                console.error('AJAX Error:', xhr);
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
            $('#modalGaleri').on('hidden.bs.modal', function() {
                $('#galeriFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();
                $('#galeri_id_modal').val('');
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
