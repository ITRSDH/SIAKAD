@extends('layouts.index')
@section('title', 'Tambah Mata Kuliah')
@push('styles-custom')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .upload-area {
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            background-color: #e9f7ef;
            border-color: #198754;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .file-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Mata Kuliah</h3>
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
                    <a href="{{ route('mata-kuliah.index', $id_prodi) }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mata-kuliah.create', $id_prodi) }}">Tambah Mata Kuliah</a>
                </li>
            </ul>
        </div>

        {{-- INFO NOTE --}}
        <div class="card shadow-sm border">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title"> Informasi Program Studi</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mata-kuliah.index', $id_prodi) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Kode Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['kode_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['nama_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Akreditasi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['akreditasi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Jenjang Pendidikan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['jenjang_pendidikan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Tahun Berdiri
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['tahun_berdiri'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Gelar Lulusan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['gelar_lulusan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Mata Kuliah</h4>
                    </div>
                    <div class="card-body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs nav-line nav-color-secondary" id="matakuliahTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="manual-tab" data-bs-toggle="tab"
                                    data-bs-target="#manual" type="button" role="tab" aria-controls="manual"
                                    aria-selected="true">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Manual
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import"
                                    type="button" role="tab" aria-controls="import" aria-selected="false">
                                    <i class="fas fa-file-excel me-2"></i>Import Excel
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3 mb-3" id="matakuliahTabContent">
                            <!-- Manual Tab -->
                            <div class="tab-pane fade show active" id="manual" role="tabpanel"
                                aria-labelledby="manual-tab">
                                <form id="formTambahMK"
                                    action="{{ route('mata-kuliah.store', ['id_prodi' => $id_prodi]) }}" method="POST">
                                    @csrf
                                    <!-- Form fields will be added here -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="kode_mk" class="form-label">Kode Mata
                                                    Kuliah</label>
                                                <input type="text" class="form-control" id="kode_mk" name="kode_mk"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nama_mk" class="form-label">Nama Mata
                                                    Kuliah</label>
                                                <input type="text" class="form-control" id="nama_mk" name="nama_mk"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="jenis_mk" class="form-label">Jenis Mata
                                                    Kuliah</label>
                                                <select class="form-select select2" id="jenis_mk" name="jenis_mk"
                                                    required>
                                                    <option value="" disabled selected>Pilih Jenis
                                                        Mata Kuliah</option>
                                                    <option value="wajib_prodi">Wajib Program Studi
                                                    </option>
                                                    <option value="wajib_nasional">Wajib Nasional</option>
                                                    <option value="pilihan">Pilihan</option>
                                                    <option value="peminatan">Peminatan</option>
                                                    <option value="tugas_akhir/skripsi/tesis/disertasi">
                                                        Tugas
                                                        Akhir/Skripsi/Tesis/Disertasi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="kelompok_mk" class="form-label">Kelompok Mata
                                                    Kuliah</label>
                                                <select class="form-select select2" id="kelompok_mk" name="kelompok_mk"
                                                    required>
                                                    <option value="" disabled selected>Pilih Kelompok
                                                        Mata Kuliah</option>
                                                    <option value="MPK">MPK (Matakuliah Pengembangan
                                                        Kepribadian)</option>
                                                    <option value="MKK">MKK (Matakuliah Keilmuan dan
                                                        Keterampilan)</option>
                                                    <option value="MKB">MKB (Matakuliah Keahlian
                                                        Berkarya)</option>
                                                    <option value="MPB">MPB (Matakuliah Perilaku
                                                        Berkarya)</option>
                                                    <option value="MBB">MBB (Matakuliah Berkehidupan
                                                        Bermasyarakat)</option>
                                                    <option value="MKDK">MKDK (Matakuliah Dasar Keahlian
                                                        )</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sks" class="form-label">SKS Mata
                                                    Kuliah</label>
                                                <input type="number" class="form-control sks-total" id="sks"
                                                    name="sks" placeholder="0" readonly>
                                                <small class="text-muted">(SKS Tatap Muka + SKS Praktikum +
                                                    SKS Praktik Lapangan +
                                                    SKS Simulasi)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sks_tatap_muka" class="form-label">Bobot SKS
                                                    Tatap Muka</label>
                                                <input type="number" class="form-control sks-input" id="sks_tatap_muka"
                                                    name="sks_tatap_muka" placeholder="0" min="0" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sks_praktikum" class="form-label">Bobot SKS
                                                    Praktikum</label>
                                                <input type="number" class="form-control sks-input" id="sks_praktikum"
                                                    name="sks_praktikum" placeholder="0" min="0" value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sks_praktek_lapangan" class="form-label">Bobot
                                                    SKS Praktik
                                                    Lapangan</label>
                                                <input type="number" class="form-control sks-input"
                                                    id="sks_praktek_lapangan" name="sks_praktek_lapangan" placeholder="0"
                                                    min="0" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sks_simulasi" class="form-label">Bobot SKS
                                                    Simulasi</label>
                                                <input type="number" class="form-control sks-input" id="sks_simulasi"
                                                    name="sks_simulasi" placeholder="0" min="0" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fas fa-save me-1"></i>Simpan</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Import Tab -->
                            <div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
                                <!-- Alert Info -->
                                <div class="alert alert-info" role="alert">
                                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Panduan
                                        Import</h5>
                                    <p class="mb-2">Sebelum melakukan import, pastikan file Excel Anda sudah
                                        sesuai
                                        dengan
                                        format yang ditentukan:</p>
                                    <ul class="mb-0">
                                        <li>Download template terlebih dahulu untuk melihat format yang benar</li>
                                        <li>File yang diterima: .xlsx, .xls, .csv (maksimal 10MB)</li>
                                        <li>Pastikan kode_prodi atau nama_prodi sudah ada di database</li>
                                        <li>Kode Mata Kuliah harus unik per program studi</li>
                                    </ul>
                                </div>

                                <!-- Download Template Section -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <h5 class="card-title text-primary mb-3">
                                                    <i class="fas fa-download me-2"></i>Download Template
                                                </h5>
                                                <p class="card-text">Download template Excel untuk format import
                                                    data mata
                                                    kuliah</p>
                                                <button type="button" class="btn btn-primary" id="downloadTemplate">
                                                    <i class=" fas fa-file-excel me-2"></i>Download Template Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Import Form Section -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <h5 class="card-title text-success mb-3">
                                                    <i class="fas fa-upload me-2"></i>Upload File Excel
                                                </h5>

                                                <form id="formImportExcel" enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Import File Excel</label>

                                                        <!-- Upload Area -->
                                                        <div id="uploadArea"
                                                            class="upload-area text-center p-5 rounded-4 border border-dashed position-relative">

                                                            <input type="file" id="file_excel" name="file"
                                                                accept=".xlsx,.xls,.csv" required class="file-input">

                                                            <div id="uploadContent">
                                                                <i
                                                                    class="fas fa-cloud-upload-alt fa-3x mb-3 text-success"></i>
                                                                <h5 class="mb-2">Drag & Drop file di sini</h5>
                                                                <p class="text-muted mb-1">atau klik untuk memilih file</p>
                                                                <small class="text-muted">Format: .xlsx, .xls, .csv (Maks:
                                                                    10MB)</small>
                                                            </div>

                                                            <div id="fileName"
                                                                class="mt-3 fw-semibold text-success d-none"></div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <button type="submit" class="btn btn-success px-4"
                                                            id="btnImport">
                                                            <i class="fas fa-file-import me-2"></i>Import Data
                                                        </button>

                                                        <button type="button" class="btn btn-secondary px-4"
                                                            id="btnResetForm">
                                                            <i class="fas fa-redo me-2"></i>Reset
                                                        </button>
                                                    </div>
                                                </form>

                                                <!-- Progress Bar (Hidden by default) -->
                                                <div id="importProgress" class="mt-3" style="display: none;">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            role="progressbar" style="width: 100%">
                                                            Sedang mengimport...
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
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk menghitung total SKS
            function calculateTotalSKS() {
                const tatapMuka = parseInt($('#sks_tatap_muka').val()) || 0;
                const praktikum = parseInt($('#sks_praktikum').val()) || 0;
                const praktikLapangan = parseInt($('#sks_praktek_lapangan').val()) || 0;
                const simulasi = parseInt($('#sks_simulasi').val()) || 0;

                const total = tatapMuka + praktikum + praktikLapangan + simulasi;

                $('#sks').val(total);
            }

            // Event listener untuk setiap input SKS detail
            $('#sks_tatap_muka, #sks_praktikum, #sks_praktek_lapangan, #sks_simulasi').on('input', function() {
                calculateTotalSKS();
            });

            // Validasi tambahan: pastikan SKS tidak negatif
            $('input[id^="sks_"]').on('blur', function() {
                if ($(this).val() < 0) {
                    $(this).val(0);
                    calculateTotalSKS();
                }
            });

            // Download Template
            $('#downloadTemplate').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();

                btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Downloading...');

                // Use Laravel route for download
                window.location.href =
                    "{{ route('mata-kuliah.export.template', ['id_prodi' => $id_prodi]) }}";

                setTimeout(function() {
                    btn.prop('disabled', false).html(originalText);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Template berhasil diunduh',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 1000);
            });

            // Import Excel
            $('#formImportExcel').on('submit', function(e) {
                e.preventDefault();

                const fileInput = $('#file_excel');
                const file = fileInput[0].files[0];

                if (!file) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Pilih file Excel terlebih dahulu!'
                    });
                    return;
                }

                // Validasi file size (10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ukuran file terlalu besar! Maksimal 10MB'
                    });
                    return;
                }

                // Validasi file type
                const allowedTypes = ['application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'
                ];
                if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Format file tidak didukung! Gunakan .xlsx, .xls, atau .csv'
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('file', file);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Show progress
                $('#importProgress').show();
                $('#btnImport').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Importing...');

                $.ajax({
                    url: "{{ route('mata-kuliah.import', ['id_prodi' => $id_prodi]) }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#importProgress').hide();
                        $('#btnImport').prop('disabled', false).html(
                            '<i class="fas fa-file-import me-2"></i>Import Data');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message ||
                                'Data mata kuliah berhasil diimport!',
                            timer: 3000,
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal
                                .DismissReason.timer) {
                                // Reset form and switch to manual tab
                                $('#formImportExcel')[0].reset();
                                $('#manual-tab').tab('show');
                                // Reload page to show new data
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        $('#importProgress').hide();
                        $('#btnImport').prop('disabled', false).html(
                            '<i class="fas fa-file-import me-2"></i>Import Data');

                        let errorMessage = 'Terjadi kesalahan saat import data';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                // Handle validation errors
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        } else if (xhr.responseText) {
                            errorMessage = 'Server error: ' + xhr.status;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Import Gagal',
                            html: errorMessage,
                            width: '600px'
                        });
                    }
                });
            });

            // Reset Form
            $('#btnResetForm').on('click', function() {
                $('#formImportExcel')[0].reset();
                $('#importProgress').hide();
            });

            const fileInput = document.getElementById('file_excel');
            const fileNameText = document.getElementById('fileName');
            const uploadArea = document.getElementById('uploadArea');
            const resetBtn = document.getElementById('btnResetForm');

            // File change event
            $('#file_excel').on('change', function() {
                const file = this.files[0];

                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    const fileName = file.name;

                    const fileInfo = `<small class="text-info">File: ${fileName} (${fileSize} MB)</small>`;

                    if ($('#fileInfo').length === 0) {
                        $('#file_excel').after('<div id="fileInfo"></div>');
                    }

                    $('#fileInfo').html(fileInfo);

                    // Update custom text
                    fileNameText.textContent = "File dipilih: " + fileName;
                    fileNameText.classList.remove('d-none');
                }
            });

            // Reset button
            resetBtn.addEventListener('click', function() {
                fileInput.value = "";
                fileNameText.classList.add('d-none');
                fileNameText.textContent = "";
                $('#fileInfo').html('');
            });

            // Drag effect
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-success');
            });

            uploadArea.addEventListener('dragleave', function() {
                uploadArea.classList.remove('border-success');
            });
        });
    </script>
@endpush
