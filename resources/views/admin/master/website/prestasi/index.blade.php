@extends('layouts.index')
@section('title', 'Prestasi')
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
            <h3 class="fw-bold mb-3">Prestasi</h3>
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
                    <a href="{{ route('prestasi.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('prestasi.index') }}">Prestasi</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapsePrestasiForm" aria-expanded="true"
                        aria-controls="collapsePrestasiForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Prestasi
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapsePrestasiForm">
                        <div class="card-body">
                            <form id="prestasiForm" name="prestasiForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="prestasi_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama_mahasiswa"
                                                name="nama_mahasiswa" placeholder="Masukkan nama mahasiswa">
                                            <div class="text-danger error-text" id="nama_mahasiswa_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="program_studi" class="form-label">Program Studi <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="program_studi" name="id_prodi">
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
                                            <div class="text-danger error-text" id="program_studi_error"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul_prestasi" class="form-label">Judul Prestasi<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul_prestasi"
                                                name="judul_prestasi" placeholder="Masukkan judul prestasi">
                                            <div class="text-danger error-text" id="judul_prestasi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="tingkat" class="form-label">Tingkat Prestasi<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="tingkat" name="tingkat">
                                                <option value="">Pilih Tingkat</option>
                                                <option value="kampus">Kampus</option>
                                                <option value="nasional">Nasional</option>
                                                <option value="internasional">Internasional</option>
                                            </select>
                                            <div class="text-danger error-text" id="tingkat_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="tahun" class="form-label">Tahun<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="tahun" name="tahun">
                                                <option value="">Pilih Tahun</option>
                                                @for ($year = date('Y'); $year <= date('Y') + 5; $year++)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor
                                            </select>
                                            <div class="text-danger error-text" id="tahun_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi<span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6"
                                                placeholder="Masukkan deskripsi prestasi"></textarea>
                                            <div class="text-danger error-text" id="deskripsi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="gambar" class="form-label">Gambar Prestasi</label>
                                            <input type="file" class="form-control" id="gambar" name="gambar"
                                                accept="image/jpeg, image/jpg, image/png, image/webp">
                                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP.
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
                        <h3 class="card-title mb-0">Data Prestasi</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100" id="prestasiTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Judul Prestasi</th>
                                        <th>Tingkat</th>
                                        <th>Tahun</th>
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
                    <h5 class="modal-title" id="imageModalTitle">Gambar Prestasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageView" src="" alt="Prestasi" class="img-fluid"
                        style="max-height: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalPrestasi" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prestasiFormModal" name="prestasiFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="prestasi_id_modal">

                        <div class="form-group mb-3">
                            <label for="nama_mahasiswa_modal" class="form-label">Nama Mahasiswa <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_mahasiswa_modal" name="nama_mahasiswa"
                                placeholder="Masukkan nama mahasiswa">
                            <div class="text-danger error-text" id="nama_mahasiswa_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="program_studi_modal" class="form-label">Program Studi <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="program_studi_modal" name="id_prodi">
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
                            <div class="text-danger error-text" id="program_studi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="judul_prestasi_modal" class="form-label">Judul Prestasi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_prestasi_modal" name="judul_prestasi"
                                placeholder="Masukkan judul prestasi">
                            <div class="text-danger error-text" id="judul_prestasi_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tingkat_modal" class="form-label">Tingkat Prestasi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="tingkat_modal" name="tingkat">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="kampus">Kampus</option>
                                        <option value="nasional">Nasional</option>
                                        <option value="internasional">Internasional</option>
                                    </select>
                                    <div class="text-danger error-text" id="tingkat_modal_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_modal" class="form-label">Tahun <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="tahun_modal" name="tahun">
                                        <option value="">Pilih Tahun</option>
                                        @for ($year = date('Y'); $year <= date('Y') + 5; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    <div class="text-danger error-text" id="tahun_modal_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi_modal" class="form-label">Deskripsi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="deskripsi_modal" name="deskripsi" rows="4"
                                placeholder="Masukkan deskripsi prestasi"></textarea>
                            <div class="text-danger error-text" id="deskripsi_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar_modal" class="form-label">Gambar Prestasi</label>
                            <input type="file" class="form-control" id="gambar_modal" name="gambar"
                                accept="image/jpeg, image/jpg, image/png, image/webp">
                            <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP. Maksimal
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
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view (pola seperti Pengumuman/FAQ)
            var prestasiData = @json($prestasi);

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

            // Initialize DataTable client-side dari data PHP
            const table = $('#prestasiTable').DataTable({
                data: prestasiData,
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
                            if (!data) return '<span class="badge bg-secondary">No Image</span>';
                            const title = (row.judul_prestasi ?? 'Prestasi').replace(/"/g, '&quot;');
                            const imageUrl = apiStorageUrl + data;
                            return `<img src="${imageUrl}" alt="${title}" class="table-image" onclick="showImageModal('${imageUrl}', '${title}')">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mahasiswa',
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.prodi?.nama_prodi ?? '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'judul_prestasi',
                        defaultContent: '-'
                    },
                    {
                        data: 'tingkat',
                        render: function(data) {
                            if (data === 'kampus') return '<span class="badge bg-primary">Kampus</span>';
                            if (data === 'nasional') return '<span class="badge bg-success">Nasional</span>';
                            if (data === 'internasional') return '<span class="badge bg-warning">Internasional</span>';
                            return '<span class="badge bg-light text-dark">-</span>';
                        }
                    },
                    {
                        data: 'tahun',
                        defaultContent: '-'
                    },
                    {
                        data: 'deskripsi',
                        render: function(data) {
                            const plain = stripHtml(data);
                            const short = truncateText(plain, 80);
                            const escapedTitle = String(plain).replace(/"/g, '&quot;');
                            return `<span class="text-truncate d-inline-block" style="max-width: 220px;" title="${escapedTitle}">${short}</span>`;
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

            // Reset form
            $('#resetBtn').click(function() {
                $('#prestasiForm')[0].reset();
                $('#prestasi_id').val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#preview-container').hide();
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#prestasiForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Validasi field yang wajib diisi
                const namaMahasiswa = $('#nama_mahasiswa').val().trim();
                const idProdi = $('#program_studi').val();
                const judulPrestasi = $('#judul_prestasi').val().trim();
                const tingkat = $('#tingkat').val();
                const tahun = $('#tahun').val();
                const deskripsi = $('#deskripsi').val().trim();

                let hasError = false;
                let errorMessage = 'Silakan isi semua field yang wajib:\n';

                if (!namaMahasiswa) {
                    $('#nama_mahasiswa_error').text('Nama mahasiswa harus diisi');
                    hasError = true;
                    errorMessage += '• Nama Mahasiswa\n';
                }
                if (!idProdi) {
                    $('#program_studi_error').text('Program studi harus dipilih');
                    hasError = true;
                    errorMessage += '• Program Studi\n';
                }
                if (!judulPrestasi) {
                    $('#judul_prestasi_error').text('Judul prestasi harus diisi');
                    hasError = true;
                    errorMessage += '• Judul Prestasi\n';
                }
                if (!tingkat) {
                    $('#tingkat_error').text('Tingkat prestasi harus dipilih');
                    hasError = true;
                    errorMessage += '• Tingkat Prestasi\n';
                }
                if (!tahun) {
                    $('#tahun_error').text('Tahun harus dipilih');
                    hasError = true;
                    errorMessage += '• Tahun\n';
                }
                if (!deskripsi) {
                    $('#deskripsi_error').text('Deskripsi harus diisi');
                    hasError = true;
                    errorMessage += '• Deskripsi\n';
                }

                if (hasError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Gunakan FormData untuk mengirim file
                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('prestasi.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reset form
                            $('#prestasiForm')[0].reset();
                            $('#preview-container').hide();
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Prestasi berhasil ditambahkan.',
                                confirmButtonText: 'OK'
                            });

                            if (response.data) {
                                // Pastikan data yang ditambahkan ke tabel sudah membawa relasi prodi
                                // (kadang response create hanya mengembalikan field inti, sehingga prodi kosong sampai refresh)
                                if (!response.data.prodi && response.data.id) {
                                    $.get("{{ route('prestasi.show', '') }}/" + response.data.id)
                                        .done(function(detail) {
                                            if (detail && detail.data) {
                                                table.row.add(detail.data).draw(false);
                                            } else {
                                                table.row.add(response.data).draw(false);
                                            }
                                        })
                                        .fail(function() {
                                            table.row.add(response.data).draw(false);
                                        });
                                } else {
                                    table.row.add(response.data).draw(false);
                                }
                            }
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
                                        0]);
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
                // Ambil data prestasi spesifik dari API
                $.get("{{ route('prestasi.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#prestasi_id_modal').val(data.data.id);
                            $('#nama_mahasiswa_modal').val(data.data.nama_mahasiswa);

                            // Ambil id_prodi dari object prodi atau dari id_prodi langsung
                            if (data.data.prodi && typeof data.data.prodi === 'object') {
                                $('#program_studi_modal').val(data.data.prodi.id);
                            } else if (data.data.id_prodi) {
                                $('#program_studi_modal').val(data.data.id_prodi);
                            }

                            $('#judul_prestasi_modal').val(data.data.judul_prestasi);
                            $('#tingkat_modal').val(data.data.tingkat);
                            $('#tahun_modal').val(data.data.tahun);
                            $('#deskripsi_modal').val(data.data.deskripsi);

                            // Handle gambar jika ada
                            if (data.data.gambar) {
                                let imageUrl = data.data.gambar;

                                // Handle different URL formats
                                if (data.data.gambar.startsWith('http://') || data.data.gambar
                                    .startsWith('https://')) {
                                    // Absolute URL - use as is
                                    imageUrl = data.data.gambar;
                                } else if (data.data.gambar.startsWith('/')) {
                                    // Relative URL starting with / - could be from API server
                                    if (data.data.gambar.startsWith('/storage/')) {
                                        imageUrl = data.data.gambar; // Local storage
                                    } else {
                                        // Assume it's from API server
                                        imageUrl = apiStorageUrl.replace('/storage/', '') + data.data
                                            .gambar;
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

                                console.log('Edit modal - Original image data:', data.data.gambar);
                                console.log('Edit modal - Processed image URL:', imageUrl);

                                $('#preview-container-modal').show();
                                $('#image-preview-modal').attr('src', imageUrl);
                            } else {
                                $('#preview-container-modal').hide();
                            }

                            $('#modelHeading').text('Edit Prestasi');
                            $('.error-text').text(''); // Hapus pesan error
                            $('#modalPrestasi').modal('show');
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
            $('#prestasiFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#prestasi_id_modal').val();
                const formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('prestasi.update', '') }}/" + id,
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
                            // Tutup modal
                            $('#modalPrestasi').modal('hide');
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Prestasi berhasil diperbarui.',
                                confirmButtonText: 'OK'

                            });

                            // Update baris di DataTable
                            if (response.data) {
                                const row = table.row($('button[data-id="' + id + '"]').closest('tr'));
                                row.data(response.data).invalidate().draw(false);
                            }
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
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
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
                    text: "Prestasi ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('prestasi.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: response.message ||
                                            'Prestasi berhasil dihapus.',
                                        confirmButtonText: 'OK'
                                    });

                                    // Remove dari DataTable
                                    table.row($('button[data-id="' + id + '"]').closest('tr')).remove().draw(false);
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
        });

        // Function untuk menampilkan modal gambar
        function showImageModal(imageUrl, title) {
            console.log('Loading image URL:', imageUrl); // Debug URL
            $('#modalImageView').attr('src', imageUrl);
            $('#imageModalTitle').text(title);
            $('#modalViewImage').modal('show');
        }

        // Function untuk debug gambar yang gagal load
        $(document).on('error', 'img.table-image', function() {
            console.error('Failed to load image:', $(this).attr('src'));
        });
    </script>
@endpush
