@extends('layouts.index')
@section('title', 'Tambah Dosen Wali')

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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Dosen Wali</h3>
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
                    <a href="#">Dosen Wali</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Dosen Wali</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Dosen Wali</h4>

                            <div class="d-flex gap-2">

                                <a href="{{ route('dosen-wali.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>

                                <button type="submit" form="form-dosen-wali" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="form-dosen-wali" action="{{ route('dosen-wali.assign') }}" method="POST">
                            @csrf
                            <!-- Form fields will be added here -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="id_dosen" class="form-label">Dosen Wali</label>
                                        <select class="form-select select2" id="id_dosen" name="id_dosen" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($dosen_wali as $d)
                                                <option value="{{ $d['id'] }}">
                                                    {{ $d['dosen_wali'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mahasiswa</label>

                                        <!-- Search Form -->
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="nama" class="form-label">Cari Mahasiswa (Nama/NIM)</label>
                                                <input type="text" class="form-control" id="nama"
                                                    placeholder="Ketik nama mahasiswa atau NIM...">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="angkatan" class="form-label">Angkatan</label>
                                                <input type="text" class="form-control" id="angkatan"
                                                    placeholder="Contoh: 2023">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="id_prodi" class="form-label">Program Studi</label>
                                                <select class="form-select select2" id="id_prodi">
                                                    <option value="">Semua Prodi</option>
                                                    @foreach ($prodi as $p)
                                                        <option value="{{ $p['id'] }}">{{ $p['prodi'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="per_page" class="form-label">Per Halaman</label>
                                                <select class="form-select" id="per_page">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary" id="btn_search_mahasiswa">
                                                    <i class="fas fa-search me-1"></i> Cari
                                                </button>
                                                <button type="button" class="btn btn-secondary ms-2" id="btn_reset_filter">
                                                    <i class="fas fa-redo me-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50px" class="text-center">
                                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                                        <div id="selectAllStatus" class="text-muted"
                                                            style="font-size: 10px; margin-top: 2px; display: none;">
                                                            <span id="selectAllCount">0</span> dipilih
                                                        </div>
                                                    </th>
                                                    <th>Nama Mahasiswa / NIM</th>
                                                    <th>Prodi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="mahasiswa_tbody">
                                                <tr id="empty_state">
                                                    <td colspan="4" class="text-center text-muted">
                                                        <i class="fas fa-search fa-2x mb-2"></i>
                                                        <p>Masukkan kata kunci pencarian untuk menampilkan data
                                                            mahasiswa</p>
                                                    </td>
                                                </tr>
                                                <tr id="loading_state" style="display: none;">
                                                    <td colspan="4" class="text-center">
                                                        <div class="spinner-border spinner-border-sm me-2" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        Sedang mencari data...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-between align-items-center mt-3"
                                        id="pagination_container" style="display: none;">
                                        <div class="text-muted">
                                            Menampilkan <span id="showing_from">0</span> - <span id="showing_to">0</span>
                                            dari <span id="total_records">0</span> data
                                        </div>
                                        <nav>
                                            <ul class="pagination mb-0" id="pagination_links">
                                                <!-- Pagination links will be inserted here -->
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
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
        $(document).ready(function () {
            var currentPage = 1;
            var currentSearch = '';
            var isSelectAllActive = false;
            var allSelectedIds = new Set();

            // Update selectAll status indicator
            function updateSelectAllStatus() {
                if (isSelectAllActive && allSelectedIds.size > 0) {
                    $('#selectAllStatus').show();
                    $('#selectAllCount').text(allSelectedIds.size);
                } else {
                    $('#selectAllStatus').hide();
                }
            }

            // Search mahasiswa function
            function searchMahasiswa(page = 1) {
                var nama = $('#nama').val().trim();
                var angkatan = $('#angkatan').val().trim();
                var prodiId = $('#id_prodi').val();
                var perPage = $('#per_page').val();

                // Check if any filter is applied
                if (!nama && !angkatan && !prodiId) {
                    $('#mahasiswa_tbody').html(`
                            <tr id="empty_state">
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-filter fa-2x mb-2"></i>
                                    <p>Isi minimal satu filter untuk menampilkan data mahasiswa</p>
                                </td>
                            </tr>
                        `);
                    $('#pagination_container').hide();
                    return;
                }

                currentPage = page;
                currentSearch = {
                    nama: nama,
                    angkatan: angkatan,
                    id_prodi: prodiId
                };

                // Show loading state
                $('#mahasiswa_tbody').html(`
                        <tr id="loading_state">
                            <td colspan="4" class="text-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Sedang mencari data...
                            </td>
                        </tr>
                    `);

                // AJAX request
                $.ajax({
                    url: '{{ route('dosen-wali.search-mahasiswa') }}',
                    method: 'GET',
                    data: {
                        nama: nama,
                        angkatan: angkatan,
                        id_prodi: prodiId,
                        page: page,
                        per_page: perPage
                    },
                    success: function (response) {
                        if (response.success && response.data.length > 0) {
                            var html = '';
                            response.data.forEach(function (m) {
                                // assign all IDs from current page
                                allSelectedIds.add(m.id);

                                html += `
                                        <tr>
                                            <td class="text-center">
                                                <input class="form-check-input mahasiswa-checkbox"
                                                    type="checkbox" name="mahasiswa_ids[]"
                                                    value="${m.id}" id="mahasiswa_${m.id}">
                                            </td>
                                            <td>
                                                <div>${m.nama_mahasiswa || m.nama || ''}</div>
                                                <small class="text-muted">${m.nim || ''}</small>
                                            </td>
                                            <td>${m.prodi?.nama_prodi || m.prodi || ''}</td>
                                        </tr>
                                    `;
                            });
                            $('#mahasiswa_tbody').html(html);

                            // Update pagination info
                            updatePagination(response.meta);

                            // Re-attach checkbox event handlers
                            attachCheckboxHandlers();

                            // If selectAll is active, check all checkboxes on current page
                            if (isSelectAllActive) {
                                $('.mahasiswa-checkbox').prop('checked', true);
                            }

                            // Update selectAll status indicator
                            updateSelectAllStatus();
                        } else {
                            $('#mahasiswa_tbody').html(`
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>Tidak ada data mahasiswa yang ditemukan</p>
                                        </td>
                                    </tr>
                                `);
                            $('#pagination_container').hide();
                        }
                    },
                    error: function (xhr) {
                        $('#mahasiswa_tbody').html(`
                                <tr>
                                    <td colspan="4" class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <p>Terjadi kesalahan saat mengambil data</p>
                                    </td>
                                </tr>
                            `);
                        $('#pagination_container').hide();
                    }
                });
            }

            // Update pagination controls
            function updatePagination(meta) {
                if (!meta) {
                    $('#pagination_container').hide();
                    return;
                }

                var currentPage = meta.current_page;
                var lastPage = meta.last_page;
                var perPage = meta.per_page;
                var total = meta.total;
                var from = (currentPage - 1) * perPage + 1;
                var to = Math.min(currentPage * perPage, total);

                $('#showing_from').text(from);
                $('#showing_to').text(to);
                $('#total_records').text(total);
                $('#pagination_container').show();

                var paginationHtml = '';

                // Previous button
                if (total === 0 || currentPage <= 1 || lastPage <= 1) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                } else {
                    paginationHtml +=
                        `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
                }

                // Page numbers
                if (total === 0) {
                    // No data - no page numbers
                } else if (lastPage === 1) {
                    paginationHtml += `<li class="page-item active"><span class="page-link">1</span></li>`;
                } else {
                    var startPage = Math.max(1, currentPage - 2);
                    var endPage = Math.min(lastPage, currentPage + 2);

                    if (startPage > 1) {
                        paginationHtml +=
                            `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                        if (startPage > 2) {
                            paginationHtml +=
                                `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                        }
                    }

                    for (var i = startPage; i <= endPage; i++) {
                        paginationHtml += i === currentPage ?
                            `<li class="page-item active"><span class="page-link">${i}</span></li>` :
                            `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }

                    if (endPage < lastPage) {
                        if (endPage < lastPage - 1) {
                            paginationHtml +=
                                `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                        }
                        paginationHtml +=
                            `<li class="page-item"><a class="page-link" href="#" data-page="${lastPage}">${lastPage}</a></li>`;
                    }
                }

                // Next button
                if (total === 0 || currentPage >= lastPage) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                } else {
                    paginationHtml +=
                        `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
                }

                $('#pagination_links').html(paginationHtml);
            }

            // Attach checkbox event handlers
            function attachCheckboxHandlers() {
                $('.mahasiswa-checkbox').off('change').on('change', function () {
                    var checkbox = $(this);
                    var id = checkbox.val();

                    if (checkbox.prop('checked')) {
                        allSelectedIds.add(id);
                    } else {
                        allSelectedIds.delete(id);
                        // If any checkbox is unchecked, uncheck selectAll
                        isSelectAllActive = false;
                        $('#selectAll').prop('checked', false);
                    }

                    // Update selectAll state based on current page checkboxes
                    var totalCheckboxes = $('.mahasiswa-checkbox').length;
                    var checkedCheckboxes = $('.mahasiswa-checkbox:checked').length;

                    if (!isSelectAllActive) {
                        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                    }

                    // Update selectAll status indicator
                    updateSelectAllStatus();
                });
            }

            // Search button click
            $('#btn_search_mahasiswa').on('click', function () {
                searchMahasiswa(1);
            });

            // Reset filter button
            $('#btn_reset_filter').on('click', function () {
                $('#nama').val('');
                $('#angkatan').val('');
                $('#id_prodi').val('');
                currentSearch = null;
                isSelectAllActive = false;
                allSelectedIds.clear();
                $('#selectAll').prop('checked', false);
                updateSelectAllStatus();
                $('#mahasiswa_tbody').html(`
                        <tr id="empty_state">
                            <td colspan="4" class="text-center text-muted">
                                <i class="fas fa-filter fa-2x mb-2"></i>
                                <p>Isi minimal satu filter untuk menampilkan data mahasiswa</p>
                            </td>
                        </tr>
                    `);
                $('#pagination_container').hide();
            });

            // Enter key on filter inputs
            $('#nama, #angkatan').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    searchMahasiswa(1);
                }
            });

            // Prodi change
            $('#id_prodi').on('change', function () {
                searchMahasiswa(1);
            });

            // Per page change
            $('#per_page').on('change', function () {
                if (currentSearch && (currentSearch.nama || currentSearch.angkatan || currentSearch
                    .id_prodi)) {
                    searchMahasiswa(1);
                }
            });

            // Pagination click handler
            $(document).on('click', '.pagination .page-link', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page && currentSearch && (currentSearch.nama || currentSearch.angkatan || currentSearch
                    .id_prodi)) {
                    searchMahasiswa(page);
                }
            });

            // Select all functionality
            $('#selectAll').on('change', function () {
                var isChecked = $(this).prop('checked');

                if (isChecked) {
                    // Activate selectAll mode
                    isSelectAllActive = true;

                    // Check all checkboxes on current page
                    $('.mahasiswa-checkbox').prop('checked', true);

                    // Add all current page IDs to allSelectedIds
                    $('.mahasiswa-checkbox').each(function () {
                        allSelectedIds.add($(this).val());
                    });

                    // Update status indicator
                    updateSelectAllStatus();

                    // Show confirmation message
                    Swal.fire({
                        icon: 'info',
                        title: 'Semua Data Dipilih',
                        html: `Semua mahasiswa di semua halaman akan dipilih.<br><small>Total: ${allSelectedIds.size} mahasiswa</small><br>Klik lagi untuk batal pilih semua.`,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    // Deactivate selectAll mode
                    isSelectAllActive = false;

                    // Uncheck all checkboxes on current page
                    $('.mahasiswa-checkbox').prop('checked', false);

                    // Clear all selected IDs
                    allSelectedIds.clear();

                    // Update status indicator
                    updateSelectAllStatus();
                }
            });

            // Form submission with AJAX
            $('#form-dosen-wali').on('submit', function (e) {
                e.preventDefault();

                // If selectAll is active, add all selected IDs to hidden inputs
                if (isSelectAllActive && allSelectedIds.size > 0) {
                    // Remove existing mahasiswa_ids inputs
                    $('input[name="mahasiswa_ids[]"]').remove();

                    // Add hidden inputs for all selected IDs
                    var form = $(this);
                    allSelectedIds.forEach(function (id) {
                        form.append('<input type="hidden" name="mahasiswa_ids[]" value="' + id +
                            '">');
                    });
                }

                // Check if at least one mahasiswa is selected
                var mahasiswaChecked = isSelectAllActive ? allSelectedIds.size : $(
                    'input[name="mahasiswa_ids[]"]:checked').length;

                if (mahasiswaChecked === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih minimal satu mahasiswa!',
                        confirmButtonColor: '#3085d6'
                    });
                    return false;
                }

                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data dosen wali',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message ||
                                'Data dosen wali berhasil disimpan',
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(function () {
                            // Redirect to index page
                            window.location.href = '{{ route('dosen-wali.index') }}';
                        });
                    },
                    error: function (xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        var responseErrors = null;

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON.errors) {
                                responseErrors = xhr.responseJSON.errors;
                            }
                        }

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonColor: '#d33',
                            footer: responseErrors ? '<pre class="text-left">' + JSON
                                .stringify(responseErrors, null, 2) + '</pre>' : ''
                        });
                    }
                });
            });
        });
    </script>
@endpush
