@extends('layouts.index')
@section('title', 'Detail Dosen Wali')

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

        /* Modal Custom Styles */
        .modal-header.bg-primary {
            border-bottom: none;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .modal-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog.modal-xl {
            max-width: 95%;
        }

        .modal-footer.bg-light {
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 0.375rem 0.375rem;
        }

        /* Search Form Styles */
        .search-icon,
        .results-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
        }

        .results-icon {
            background: rgba(25, 135, 84, 0.1);
        }

        /* Table Styles */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .table-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
        }

        /* Button Styles */
        .btn-quick-select {
            transition: all 0.2s ease;
        }

        .btn-quick-select:hover {
            transform: translateY(-1px);
        }

        /* Form Floating Styles */
        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label,
        .form-floating>.form-select~label {
            color: #0d6efd;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Selected Count Badge */
        .badge.bg-primary.rounded-pill {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog.modal-xl {
                max-width: 100%;
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .avatar-sm {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Dosen Wali</h3>
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
                    <a href="{{ route('dosen-wali.index') }}">Dosen Wali</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Dosen Wali Info -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Informasi Dosen Wali</h4>
                            <form action="POST">
                                @csrf

                            </form>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-danger" id="btnRemoveAll">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                                </button>
                                <a href="{{ route('dosen-wali.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="dosenLoader" class="loader-overlay hidden">
                            <div class="loader-spinner"></div>
                        </div>
                        <form id="form-update-dosen" action="#" method="POST">
                            @csrf
                            <input type="hidden" id="dosenWaliId" name="id_dosen" value="{{ $dosenWali['id'] ?? '' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_dosen" class="form-label">Nama Dosen</label>
                                        <input type="text" class="form-control" id="nama_dosen" readonly
                                            value="{{ $dosenWali['nama_dosen'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="nidn" class="form-label">NIDN</label>
                                        <input type="text" class="form-control" id="nidn" readonly
                                            value="{{ $dosenWali['nidn'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="total_bimbingan" class="form-label">Jumlah Mahasiswa Bimbingan</label>
                                        <input type="text" class="form-control" id="total_bimbingan" readonly
                                            value="{{ $dosenWali['total_bimbingan'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="id_dosen" class="form-label">Ubah Dosen Wali</label>
                                        <select class="form-select select2" id="id_dosen" name="id_dosen">
                                            <option value="" disabled selected>Pilih Dosen Wali</option>
                                            @if (count($dosen_wali) > 0)
                                                @foreach ($dosen_wali as $d)
                                                    <option value="{{ $d['id'] }}">
                                                        {{ $d['dosen_wali'] }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Tidak ada dosen wali yang tersedia</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Dosen Wali
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Mahasiswa Bimbingan -->
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Mahasiswa Bimbingan</h4>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addMahasiswaModal">
                                <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="mahasiswaLoader" class="loader-overlay hidden">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="20%">Nama Mahasiswa</th>
                                        <th width="15%">NIM</th>
                                        <th width="20%">Program Studi</th>
                                        <th width="15%">Angkatan</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="mahasiswa_tbody">
                                    @if (isset($dosenWali['mahasiswa']) && count($dosenWali['mahasiswa']) > 0)
                                        @foreach ($dosenWali['mahasiswa'] as $index => $mahasiswa)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $mahasiswa['nama_mahasiswa'] ?? '' }}</td>
                                                <td>{{ $mahasiswa['nim'] ?? '' }}</td>
                                                <td>{{ $mahasiswa['prodi'] ?? '' }}</td>
                                                <td>{{ $mahasiswa['angkatan'] ?? '' }}</td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm btn-remove-mahasiswa"
                                                        data-id="{{ $mahasiswa['id'] }}"
                                                        data-nama="{{ $mahasiswa['nama_mahasiswa'] ?? '' }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Belum ada mahasiswa bimbingan</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Mahasiswa -->
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-labelledby="addMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon me-3">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="addMahasiswaModalLabel">Tambah Mahasiswa Bimbingan</h5>
                            <small class="opacity-75">Pilih mahasiswa yang akan ditambahkan ke daftar bimbingan</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Search Form -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="search-icon me-2">
                                    <i class="fas fa-search text-primary"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">Kriteria Pencarian</h6>
                            </div>
                            <form id="form-search-mahasiswa">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="search_nama" name="nama"
                                                placeholder="Ketik nama atau NIM">
                                            <label for="search_nama">Nama/NIM Mahasiswa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="search_angkatan"
                                                name="angkatan" placeholder="Contoh: 2023">
                                            <label for="search_angkatan">Angkatan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="search_prodi" name="id_prodi">
                                                <option value="">Semua Prodi</option>
                                                @if (isset($prodi) && count($prodi) > 0)
                                                    @foreach ($prodi as $p)
                                                        <option value="{{ $p['id'] }}">{{ $p['prodi'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <label for="search_prodi">Program Studi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-floating">
                                            <button type="submit" class="btn btn-primary h-100 w-100">
                                                <i class="fas fa-search"></i>
                                                <span class="d-none d-md-inline ms-1">Cari</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="results-icon me-2">
                                        <i class="fas fa-list text-success"></i>
                                    </div>
                                    <h6 class="mb-0 fw-semibold">Hasil Pencarian</h6>
                                </div>
                                <div id="selectedCount" class="badge bg-primary rounded-pill d-none">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="countText">0</span> mahasiswa dipilih
                                </div>
                            </div>

                            <div id="searchResults" class="min-vh-50">
                                <div class="text-center text-muted py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-search fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="fw-semibold">Belum Ada Pencarian</h6>
                                    <p class="mb-0">Masukkan kriteria pencarian untuk menemukan mahasiswa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary px-4" id="btnAssignSelected" disabled>
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="btnText">Tambah Mahasiswa Terpilih</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2({
                placeholder: 'Pilih Dosen Wali',
                allowClear: true,
                width: '100%'
            });

            // Set current dosen wali as selected if exists
            const currentDosenId = $('#dosenWaliId').val();
            if (currentDosenId) {
                $('#id_dosen').val(currentDosenId).trigger('change');
            }

            // Function to refresh dosen wali info
            function refreshDosenWaliInfo() {
                const dosenWaliId = $('#dosenWaliId').val();
                if (!dosenWaliId) return;

                $('#dosenLoader').removeClass('hidden');

                $.ajax({
                    url: `{{ route('dosen-wali.detail', ':id') }}`.replace(':id', dosenWaliId),
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Check if response has data property
                        if (!response || !response.data) {
                            console.error('Invalid response structure:', response);
                            $('#dosenLoader').addClass('hidden');
                            Swal.fire({
                                title: 'Error!',
                                text: 'Data dosen wali tidak ditemukan',
                                icon: 'error'
                            });
                            return;
                        }

                        // Update dosen info fields with safe property access
                        const data = response.data;
                        $('#nama_dosen').val(data.nama_dosen || '');
                        $('#nidn').val(data.nidn || '');
                        $('#total_bimbingan').val(data.total_bimbingan || 0);

                        // Update mahasiswa table
                        updateMahasiswaTable(data.mahasiswa || []);

                        $('#dosenLoader').addClass('hidden');
                    },
                    error: function() {
                        $('#dosenLoader').addClass('hidden');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memperbarui data dosen wali',
                            icon: 'error'
                        });
                    }
                });
            }

            // Function to update mahasiswa table
            function updateMahasiswaTable(mahasiswaList) {
                const tbody = $('#mahasiswa_tbody');
                tbody.empty();

                if (mahasiswaList.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>Belum ada mahasiswa bimbingan</p>
                            </td>
                        </tr>
                    `);
                } else {
                    mahasiswaList.forEach(function(mahasiswa, index) {
                        tbody.append(`
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td>${mahasiswa.nama_mahasiswa || ''}</td>
                                <td>${mahasiswa.nim || ''}</td>
                                <td>${mahasiswa.prodi || ''}</td>
                                <td>${mahasiswa.angkatan || ''}</td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-danger btn-sm btn-remove-mahasiswa"
                                        data-id="${mahasiswa.id}"
                                        data-nama="${mahasiswa.nama_mahasiswa || ''}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }
            }

            // Handle update dosen wali
            $('#form-update-dosen').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const currentDosenId = $('#dosenWaliId').val();
                const newDosenId = $('#id_dosen').val();

                // Check if dosen is selected
                if (!newDosenId) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih dosen wali terlebih dahulu.',
                        icon: 'warning'
                    });
                    return;
                }

                // Check if dosen is the same
                if (currentDosenId === newDosenId) {
                    Swal.fire({
                        title: 'Informasi!',
                        text: 'Dosen wali yang dipilih sama dengan yang sekarang.',
                        icon: 'info'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    html: 'Apakah Anda yakin ingin mengubah dosen wali?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        $('#dosenLoader').removeClass('hidden');
                        const submitBtn = form.find('button[type="submit"]');
                        submitBtn.prop('disabled', true);

                        // Get selected dosen name for display
                        const selectedOption = $('#id_dosen option:selected');
                        const dosenName = selectedOption.text();

                        const requestData = {
                            _token: '{{ csrf_token() }}',
                            id_dosen_lama: currentDosenId,
                            id_dosen_baru: newDosenId
                        };

                        $.ajax({
                            url: "{{ route('dosen-wali.unassign') }}",
                            method: 'POST',
                            data: requestData,
                            success: function(response) {
                                $('#dosenLoader').addClass('hidden');
                                submitBtn.prop('disabled', false);

                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Dosen wali berhasil diperbarui.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Redirect to new dosen wali detail page
                                        if (response.redirect_url) {
                                            window.location.href = response
                                                .redirect_url;
                                        } else {
                                            // Fallback: reload current page
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    let errorMessage = response.message ||
                                        'Terjadi kesalahan saat memperbarui dosen wali.';

                                    // Show detailed error if available
                                    if (response.errors && typeof response.errors ===
                                        'object') {
                                        errorMessage += '\n\nDetail error:\n' + JSON
                                            .stringify(response.errors, null, 2);
                                    }

                                    Swal.fire({
                                        title: 'Gagal!',
                                        html: `<pre>${errorMessage}</pre>`,
                                        icon: 'error',
                                        width: 600
                                    });
                                }
                            },
                            error: function(xhr) {
                                $('#dosenLoader').addClass('hidden');
                                submitBtn.prop('disabled', false);

                                let errorMessage =
                                    'Terjadi kesalahan saat memperbarui dosen wali.';
                                let errorDetails = '';

                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    if (xhr.responseJSON.errors) {
                                        errorDetails = '\n\nDetail error:\n' + JSON
                                            .stringify(xhr.responseJSON.errors, null,
                                                2);
                                    }
                                } else if (xhr.responseText) {
                                    try {
                                        const errorData = JSON.parse(xhr.responseText);
                                        if (errorData.message) {
                                            errorMessage = errorData.message;
                                        }
                                    } catch (e) {
                                        errorDetails = '\n\nResponse: ' + xhr
                                            .responseText;
                                    }
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    html: `<pre>${errorMessage}${errorDetails}</pre>`,
                                    icon: 'error',
                                    width: 600
                                });
                            }
                        });
                    }
                });
            });

            // Handle remove mahasiswa
            $(document).on('click', '.btn-remove-mahasiswa', function(e) {
                e.preventDefault();

                const btn = $(this);
                const mahasiswaId = btn.data('id');
                const mahasiswaNama = btn.data('nama');
                const dosenWaliId = $('#dosenWaliId').val();

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus mahasiswa <strong>${mahasiswaNama}</strong> dari daftar bimbingan?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        $('#mahasiswaLoader').removeClass('hidden');
                        btn.prop('disabled', true);

                        $.ajax({
                            url: "{{ route('dosen-wali.remove') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id_dosen: dosenWaliId,
                                id_mahasiswa: mahasiswaId
                            },
                            success: function(response) {
                                $('#mahasiswaLoader').addClass('hidden');
                                btn.prop('disabled', false);

                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Mahasiswa berhasil dihapus dari daftar bimbingan.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Refresh data without full reload
                                        refreshDosenWaliInfo();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menghapus data.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $('#mahasiswaLoader').addClass('hidden');
                                btn.prop('disabled', false);

                                let errorMessage =
                                    'Terjadi kesalahan saat menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Handle remove all mahasiswa
            $('#btnRemoveAll').on('click', function(e) {
                e.preventDefault();

                const dosenWaliId = $('#dosenWaliId').val();
                const mahasiswaRows = $('#mahasiswa_tbody tr');
                const mahasiswaCount = mahasiswaRows.length;

                // Check if there are any mahasiswa to remove
                if (mahasiswaCount === 0 || mahasiswaRows.find('td[colspan]').length > 0) {
                    Swal.fire({
                        title: 'Informasi!',
                        text: 'Tidak ada mahasiswa bimbingan untuk dihapus.',
                        icon: 'info'
                    });
                    return;
                }

                // Get all mahasiswa IDs
                const mahasiswaIds = [];
                mahasiswaRows.each(function() {
                    const removeBtn = $(this).find('.btn-remove-mahasiswa');
                    if (removeBtn.length > 0) {
                        mahasiswaIds.push(removeBtn.data('id'));
                    }
                });

                Swal.fire({
                    title: 'Konfirmasi Hapus Semua',
                    html: `Apakah Anda yakin ingin menghapus <strong>semua ${mahasiswaIds.length} mahasiswa</strong> dari daftar bimbingan?<br><br><span class="text-danger">Tindakan ini tidak dapat dibatalkan!</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        $('#mahasiswaLoader').removeClass('hidden');
                        const btn = $(this);
                        btn.prop('disabled', true);

                        $.ajax({
                            url: "{{ route('dosen-wali.remove') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id_dosen: dosenWaliId,
                                id_mahasiswa: mahasiswaIds,
                                remove_all: true
                            },
                            success: function(response) {
                                $('#mahasiswaLoader').addClass('hidden');
                                btn.prop('disabled', false);

                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Semua mahasiswa (${response.removed_count || mahasiswaIds.length}) berhasil dihapus dari daftar bimbingan.`,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Refresh data without full reload
                                        // refreshDosenWaliInfo();
                                        window.location.href =
                                            '{{ route('dosen-wali.index') }}';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menghapus semua data.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $('#mahasiswaLoader').addClass('hidden');
                                btn.prop('disabled', false);

                                let errorMessage =
                                    'Terjadi kesalahan saat menghapus semua data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Handle search mahasiswa form
            $('#form-search-mahasiswa').on('submit', function(e) {
                e.preventDefault();
                searchMahasiswa();
            });

            // Function to search mahasiswa
            function searchMahasiswa(page = 1) {
                const searchParams = {
                    nama: $('#search_nama').val(),
                    angkatan: $('#search_angkatan').val(),
                    id_prodi: $('#search_prodi').val(),
                    page: page,
                    per_page: 15
                };

                // Show loading in search results
                $('#searchResults').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="text-muted">Sedang Mencari Mahasiswa...</h6>
                        <p class="text-muted small mb-0">Mohon tunggu sebentar</p>
                    </div>
                `);

                $.ajax({
                    url: "{{ route('dosen-wali.search-mahasiswa') }}",
                    method: 'GET',
                    data: searchParams,
                    success: function(response) {
                        if (response.success && response.data) {
                            displaySearchResults(response.data, response.meta);
                        } else {
                            $('#searchResults').html(`
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-search fa-3x text-warning opacity-50"></i>
                                    </div>
                                    <h6 class="text-warning fw-semibold">Tidak Ada Hasil</h6>
                                    <p class="text-muted mb-0">${response.message || 'Tidak ada mahasiswa yang ditemukan dengan kriteria tersebut'}</p>
                                </div>
                            `);
                            $('#btnAssignSelected').prop('disabled', true);
                            $('#selectedCount').addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        $('#searchResults').html(`
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger opacity-50"></i>
                                </div>
                                <h6 class="text-danger fw-semibold">Terjadi Kesalahan</h6>
                                <p class="text-muted mb-0">Gagal melakukan pencarian mahasiswa</p>
                            </div>
                        `);
                        $('#btnAssignSelected').prop('disabled', true);
                        $('#selectedCount').addClass('d-none');
                    }
                });
            }

            // Function to display search results
            function displaySearchResults(mahasiswaList, meta) {
                let html = '';

                if (mahasiswaList.length === 0) {
                    html = `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-info opacity-50"></i>
                            </div>
                            <h6 class="text-info fw-semibold">Tidak Ada Data</h6>
                            <p class="text-muted mb-0">Tidak ada mahasiswa yang ditemukan dengan kriteria tersebut</p>
                        </div>
                    `;
                } else {
                    html = `
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="3%" class="text-center">
                                            <div class="form-check">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </div>
                                        </th>
                                        <th width="25%">Nama Mahasiswa</th>
                                        <th width="15%">NIM</th>
                                        <th width="25%">Program Studi</th>
                                <th width="10%">Angkatan</th>
                                        <th width="9%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    mahasiswaList.forEach(function(mahasiswa, index) {
                        // Split nama field to extract NIM and name
                        const namaParts = (mahasiswa.nama || '').split(' - ');
                        const nim = namaParts[0] || '';
                        const namaMahasiswa = namaParts[1] || mahasiswa.nama || '';

                        html += `
                            <tr class="mahasiswa-row" data-id="${mahasiswa.id}">
                                <td class="text-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input mahasiswa-checkbox"
                                               value="${mahasiswa.id}"
                                               data-nama="${namaMahasiswa}"
                                               data-nim="${nim}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${namaMahasiswa}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark fw-semibold">${nim}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                                        ${mahasiswa.prodi || '-'}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">${mahasiswa.angkatan || '-'}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-quick-select"
                                            data-id="${mahasiswa.id}"
                                            data-nama="${namaMahasiswa}"
                                            data-nim="${nim}"
                                            title="Pilih mahasiswa ini">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    // Add pagination if available
                    if (meta && meta.last_page > 1) {
                        html += `
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Menampilkan ${meta.from || 0} - ${meta.to || 0} dari ${meta.total || 0} data
                                </div>
                                <nav>
                                    <ul class="pagination mb-0">
                        `;

                        // Previous button
                        if (meta.current_page > 1) {
                            html += `
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${meta.current_page - 1}">Previous</a>
                                </li>
                            `;
                        } else {
                            html += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                        }

                        // Page numbers
                        const startPage = Math.max(1, meta.current_page - 2);
                        const endPage = Math.min(meta.last_page, meta.current_page + 2);

                        if (startPage > 1) {
                            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                            if (startPage > 2) {
                                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                            }
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            const activeClass = i === meta.current_page ? 'active' : '';
                            html += `
                                <li class="page-item ${activeClass}">
                                    ${i === meta.current_page ? `<span class="page-link">${i}</span>` : `<a class="page-link" href="#" data-page="${i}">${i}</a>`}
                                </li>
                            `;
                        }

                        if (endPage < meta.last_page) {
                            if (endPage < meta.last_page - 1) {
                                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                            }
                            html +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${meta.last_page}">${meta.last_page}</a></li>`;
                        }

                        // Next button
                        if (meta.current_page < meta.last_page) {
                            html += `
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${meta.current_page + 1}">Next</a>
                                </li>
                            `;
                        } else {
                            html += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                        }

                        html += `
                                    </ul>
                                </nav>
                            </div>
                        `;
                    }
                }

                $('#searchResults').html(html);
                updateAssignButton();
            }

            // Handle select all checkbox
            $(document).on('change', '#selectAll', function() {
                const isChecked = $(this).is(':checked');
                $('.mahasiswa-checkbox').prop('checked', isChecked);
                updateAssignButton();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.mahasiswa-checkbox', function() {
                updateAssignButton();
                updateSelectAllCheckbox();
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    searchMahasiswa(page);
                }
            });

            // Function to update assign button state
            function updateAssignButton() {
                const checkedCount = $('.mahasiswa-checkbox:checked').length;
                $('#btnAssignSelected').prop('disabled', checkedCount === 0);

                if (checkedCount > 0) {
                    $('#btnText').text(`Tambah ${checkedCount} Mahasiswa`);
                    $('#selectedCount').removeClass('d-none');
                    $('#countText').text(checkedCount);
                } else {
                    $('#btnText').text('Tambah Mahasiswa Terpilih');
                    $('#selectedCount').addClass('d-none');
                }
            }

            // Function to update select all checkbox state
            function updateSelectAllCheckbox() {
                const totalCheckboxes = $('.mahasiswa-checkbox').length;
                const checkedCheckboxes = $('.mahasiswa-checkbox:checked').length;
                $('#selectAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
            }

            // Handle assign selected mahasiswa
            $('#btnAssignSelected').on('click', function() {
                const selectedMahasiswa = [];
                $('.mahasiswa-checkbox:checked').each(function() {
                    selectedMahasiswa.push($(this).val());
                });

                if (selectedMahasiswa.length === 0) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Pilih minimal satu mahasiswa untuk ditambahkan.',
                        icon: 'warning'
                    });
                    return;
                }

                const dosenWaliId = $('#dosenWaliId').val();
                const btn = $(this);

                Swal.fire({
                    title: 'Konfirmasi Penambahan',
                    html: `Apakah Anda yakin ingin menambahkan <strong>${selectedMahasiswa.length}</strong> mahasiswa ke daftar bimbingan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tambahkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true);

                        $.ajax({
                            url: "{{ route('dosen-wali.assign') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id_dosen: dosenWaliId,
                                mahasiswa_ids: selectedMahasiswa
                            },
                            success: function(response) {
                                btn.prop('disabled', false);

                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Mahasiswa berhasil ditambahkan ke daftar bimbingan.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Close modal and refresh data
                                        $('#addMahasiswaModal').modal('hide');
                                        refreshDosenWaliInfo();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menambahkan mahasiswa.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr) {
                                btn.prop('disabled', false);

                                let errorMessage =
                                    'Terjadi kesalahan saat menambahkan mahasiswa.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Handle quick select button
            $(document).on('click', '.btn-quick-select', function(e) {
                e.preventDefault();
                const btn = $(this);
                const mahasiswaId = btn.data('id');
                const checkbox = $(`.mahasiswa-checkbox[value="${mahasiswaId}"]`);

                // Toggle checkbox
                checkbox.prop('checked', !checkbox.prop('checked'));
                updateAssignButton();
                updateSelectAllCheckbox();

                // Visual feedback
                const row = btn.closest('tr');
                if (checkbox.prop('checked')) {
                    row.addClass('table-success');
                    btn.removeClass('btn-outline-primary').addClass('btn-success').html(
                        '<i class="fas fa-check"></i>');
                } else {
                    row.removeClass('table-success');
                    btn.removeClass('btn-success').addClass('btn-outline-primary').html(
                        '<i class="fas fa-plus"></i>');
                }
            });

            // Handle checkbox changes to update quick select button
            $(document).on('change', '.mahasiswa-checkbox', function() {
                const checkbox = $(this);
                const row = checkbox.closest('tr');
                const quickBtn = row.find('.btn-quick-select');

                if (checkbox.prop('checked')) {
                    row.addClass('table-success');
                    quickBtn.removeClass('btn-outline-primary').addClass('btn-success').html(
                        '<i class="fas fa-check"></i>');
                } else {
                    row.removeClass('table-success');
                    quickBtn.removeClass('btn-success').addClass('btn-outline-primary').html(
                        '<i class="fas fa-plus"></i>');
                }
            });

            // Reset modal when hidden
            $('#addMahasiswaModal').on('hidden.bs.modal', function() {
                $('#form-search-mahasiswa')[0].reset();
                $('#search_prodi').val('').trigger('change');
                $('#searchResults').html(`
                    <div class="text-center text-muted py-5">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x opacity-50"></i>
                        </div>
                        <h6 class="fw-semibold">Belum Ada Pencarian</h6>
                        <p class="mb-0">Masukkan kriteria pencarian untuk menemukan mahasiswa</p>
                    </div>
                `);
                $('#btnAssignSelected').prop('disabled', true);
                $('#selectedCount').addClass('d-none');
            });
        });
    </script>
@endpush
