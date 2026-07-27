@extends('layouts.index')
@section('title', 'Sertifikat Akreditasi')
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
            <h3 class="fw-bold mb-3">Sertifikat Akreditasi</h3>
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
                    <a href="{{ route('sertifikat-akreditasi.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sertifikat-akreditasi.index') }}">Sertifikat Akreditasi</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseSertifikatAkreditasiForm" aria-expanded="true"
                        aria-controls="collapseSertifikatAkreditasiForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Sertifikat Akreditasi
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseSertifikatAkreditasiForm">
                        <div class="card-body">
                            <form id="sertifikatAkreditasiForm" name="sertifikatAkreditasiForm" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="sertifikat_akreditasi_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="nama" class="form-label">Nama Sertifikat <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                placeholder="Masukkan nama sertifikat">
                                            <div class="text-danger error-text" id="nama_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="8" placeholder="Masukkan deskripsi"></textarea>
                                            <div class="text-danger error-text" id="deskripsi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="foto_sertifikat" class="form-label">Foto Sertifikat</label>
                                            <input
                                                type="file"
                                                class="form-control"
                                                id="fotos"
                                                name="fotos[]"
                                                multiple
                                                accept="image/jpeg,image/jpg,image/png,image/webp">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP.
                                                Maksimal 2MB.</small>
                                            <div class="text-danger error-text" id="fotos_error"></div>
                                            <div
                                                id="preview-container"
                                                class="row mt-3"
                                                style="display:none;">
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
                            <i class="fas fa-list text-primary me-2"></i>Data Sertifikat Akreditasi
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="sertifikat-akreditasi-table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Foto</th>
                                        <th style="width: 30%;">Nama</th>
                                        <th style="width: 46%;">Deskripsi</th>
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
    <div class="modal fade" id="modalSertifikatAkreditasi" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeadingSertifikatAkreditasi"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sertifikatAkreditasiFormModal" name="sertifikatAkreditasiFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="sertifikat_akreditasi_id_modal">

                        <div class="form-group mb-3">
                            <label for="nama_modal" class="form-label">Nama Sertifikat <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_modal" name="nama"
                                placeholder="Masukkan nama sertifikat">
                            <div class="text-danger error-text" id="nama_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi_modal" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi_modal" name="deskripsi" rows="6"
                                placeholder="Masukkan deskripsi"></textarea>
                            <div class="text-danger error-text" id="deskripsi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="foto_sertifikat_modal" class="form-label">Foto Sertifikat</label>
                            <input type="file" class="form-control" id="fotos_modal"
                                name="fotos[]"
                                multiple
                                accept="image/jpeg, image/jpg, image/png, image/webp">
                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP. Maksimal
                                2MB.</small>
                            <div id="fotos_modal_error"></div>
                            <div
                                id="preview-container-modal"
                                class="row mt-3"
                                style="display:none;">
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
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var sertifikatAkreditasiData = @json($sertifikatAkreditasi ?? []);
            // Ambil storage URL API dari config
            var apiStorageUrl = '{{ config('api.storage_url') }}';

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#sertifikat-akreditasi-table').DataTable({
                data: sertifikatAkreditasiData,
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
                        data: 'fotos',
                        render: function(data, type, row) {
                    
                            if (data && data.length > 0) {
                    
                                let imageUrl = data[0].foto;
                    
                                // Handle different URL formats
                                if (
                                    imageUrl.startsWith('http://') ||
                                    imageUrl.startsWith('https://')
                                ) {
                    
                                    // gunakan apa adanya
                    
                                } else if (imageUrl.startsWith('/')) {
                    
                                    if (!imageUrl.startsWith('/storage/')) {
                                        imageUrl = apiStorageUrl.replace('/storage/', '') + imageUrl;
                                    }
                    
                                } else {
                    
                                    imageUrl = apiStorageUrl + imageUrl;
                    
                                }
                    
                                return `
                                    <div class="text-center">
                                        <img
                                            src="${imageUrl}"
                                            alt="${row.nama}"
                                            class="table-image"
                                            onclick="showImageModal('${imageUrl}', '${row.nama}')"
                                            title="Klik untuk memperbesar"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    
                                        <div style="display:none;" class="text-center">
                                            <span class="badge bg-warning">Image Error</span>
                                        </div>
                    
                                        <div class="mt-1">
                                            <small class="text-muted">${data.length} Foto</small>
                                        </div>
                                    </div>
                                `;
                    
                            }
                    
                            return `
                                <div class="text-center">
                                    <span class="badge bg-secondary">No Image</span>
                                </div>
                            `;
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
                        data: 'deskripsi',
                        render: function(data, type, row) {
                            // Batasi panjang isi deskripsi dan tambahkan ellipsis jika terlalu panjang
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
                $('#sertifikatAkreditasiForm')[0].reset();
                $('#sertifikat_akreditasi_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#preview-container').empty().hide();
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#sertifikatAkreditasiForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Validasi required fields
                const nama = $('#nama').val().trim();
                if (!nama) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib (Nama)!',
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
                    url: "{{ route('sertifikat-akreditasi.store') }}",
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
                            table.row.add(response.data).draw(false);
                            // Reset form
                            $('#sertifikatAkreditasiForm')[0].reset();
                            $('#preview-container').hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Sertifikat berhasil ditambahkan.',
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
                $('#sertifikatAkreditasiFormModal')[0].reset();
                $('.error-text').text('');
            
                $('#preview-container-modal').empty().hide();
            
                // Ambil data sertifikat dari API
                $.get("{{ route('sertifikat-akreditasi.show', '') }}/" + id)
                    .done(function(data) {
            
                        if (data && data.data) {
            
                            $('#sertifikat_akreditasi_id_modal').val(data.data.id);
                            $('#nama_modal').val(data.data.nama);
                            $('#deskripsi_modal').val(data.data.deskripsi);
            
                            // ===========================
                            // Tampilkan semua foto lama
                            // ===========================
                            if (data.data.fotos && data.data.fotos.length > 0) {
            
                                data.data.fotos.forEach(function(item) {
            
                                    let imageUrl = item.foto;
            
                                    // Absolute URL
                                    if (
                                        imageUrl.startsWith('http://') ||
                                        imageUrl.startsWith('https://')
                                    ) {
            
                                        // gunakan apa adanya
            
                                    }
                                    // Local storage
                                    else if (imageUrl.startsWith('/storage/')) {
            
                                        // gunakan apa adanya
            
                                    }
                                    // Relative path dari API
                                    else {
            
                                        imageUrl = apiStorageUrl + imageUrl;
            
                                    }
            
                                    $('#preview-container-modal').append(`
                                        <div class="col-md-3 mb-3">
                                            <img
                                                src="${imageUrl}"
                                                class="img-fluid rounded border"
                                                style="height:180px;width:100%;object-fit:cover;">
                                        </div>
                                    `);
            
                                });
            
                                $('#preview-container-modal').show();
            
                            } else {
            
                                $('#preview-container-modal').hide();
            
                            }
            
                            $('#modelHeadingSertifikatAkreditasi').text('Edit Sertifikat Akreditasi');
                            $('#modalSertifikatAkreditasi').modal('show');
            
                        } else {
            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Data tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
            
                        }
            
                    })
                    .fail(function() {
            
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
            
                    });
            
            });

            // Submit edit via modal
            $('#sertifikatAkreditasiFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#sertifikat_akreditasi_id_modal').val();

                // Validasi required fields untuk modal
                const nama = $('#nama_modal').val().trim();

                if (!id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'ID sertifikat tidak ditemukan!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (!nama) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: 'Harap isi semua field yang wajib (Nama)!',
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
                    url: "{{ route('sertifikat-akreditasi.update', '') }}/" + id,
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
                                table.row(rowIndex).data(response.data).invalidate().draw(false);
                            }
                            // Tutup modal
                            $('#modalSertifikatAkreditasi').modal('hide');
                            $('#preview-container-modal').empty().hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Sertifikat berhasil diperbarui.',
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
                    text: "Sertifikat ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('sertifikat-akreditasi.destroy', '') }}/" + id,
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
                                        table.row(rowIndex).remove().draw(false);
                                    }
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'Sertifikat berhasil dihapus.',
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
           

            // Modal close handlers untuk reset form
            $('#modalSertifikatAkreditasi').on('hidden.bs.modal', function() {
                $('#sertifikatAkreditasiFormModal')[0].reset();
                $('.error-text').text('');
                $('#preview-container-modal').hide();
                $('#sertifikat_akreditasi_id_modal').val('');
            });
        });

        // Function untuk menampilkan modal gambar
        function showImageModal(imageUrl, title) {
            $('#modalImageView').attr('src', imageUrl);
            $('#imageModalTitle').text(title);
            $('#modalViewImage').modal('show');
        }
        
        function previewImages(input, container) {

            container.empty();
        
            const files = input.files;
        
            if (!files.length) {
                container.hide();
                return;
            }
        
            container.show();
        
            Array.from(files).forEach(file => {
        
                const reader = new FileReader();
        
                reader.onload = function(e){
        
                    container.append(`
                        <div class="col-md-3 mb-3">
                            <img
                                src="${e.target.result}"
                                class="img-fluid rounded border"
                                style="height:180px;width:100%;object-fit:cover;">
                        </div>
                    `);
        
                };
        
                reader.readAsDataURL(file);
        
            });
        
        }

        // Function untuk debug gambar yang gagal load
        $(document).on('error', 'img.table-image', function() {
            // Image failed to load, error already handled by onerror attribute
        });
    </script>
@endpush
