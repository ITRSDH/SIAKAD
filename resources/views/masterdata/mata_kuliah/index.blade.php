@extends('layouts.index')
@section('title', 'Data Mata Kuliah')
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
        }

        .dropdown-menu-lg {
            min-width: 250px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu-lg .dropdown-item {
            padding: 12px 18px;
            font-size: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data Mata Kuliah</h3>
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
                    <a href="{{ route('mata-kuliah.indexProdi') }}">Program Studi</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <span>Mata Kuliah</span>
                </li>
            </ul>
        </div>

        {{-- INFO NOTE --}}
        <div class="card shadow-sm border">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title"> Informasi Program Studi</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mata-kuliah.indexProdi') }}" class="btn btn-secondary">
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Daftar Mata Kuliah</h4>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success" id="exportData">
                                    <i class="fas fa-file-excel me-1"></i> Export
                                </button>
                                <a href="{{ route('mata-kuliah.create', $id_prodi) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="matakuliah-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="5%" class="text-center">KODE MK</th>
                                        <th width="40%" class="text-center">MATA KULIAH</th>
                                        <th width="10%" class="text-center">SKS</th>
                                        <th width="10%" class="text-center">JENIS MK</th>
                                        <th width="10%" class="text-center">PRASYARAT</th>
                                        <th width="10%" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi ini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- @stack('scripts') --}}
    <script>
        $(document).ready(function() {
            var tabel = $('#matakuliah-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('mata-kuliah.data', $id_prodi) }}",
                    type: 'GET',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            // Kolom No (indeks baris + 1)
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_mk',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mk',
                        className: 'text-center',
                        render: function(data, type, row) {

                            let url = "{{ route('mata-kuliah.detail', ':id') }}"
                                .replace(':id', row.id);

                            return `<a href="${url}" class="fw-bold text-primary">${data}</a>`;
                        }
                    },
                    {
                        data: 'sks',
                        className: 'text-center'
                    },
                    {
                        data: 'jenis_mk',
                        className: 'text-center'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            const count = row.prasyarat_count ?? row.total_prasyarat ?? (Array.isArray(row.prasyarat) ? row.prasyarat.length : 0);

                            if (count > 0) {
                                return `<span class="badge bg-info text-dark">${count} Prasyarat</span>`;
                            }

                            return '<span class="badge bg-secondary">Tanpa Prasyarat</span>';
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {

                            var baseUrl = "{{ route('mata-kuliah.detail', ':id') }}".replace(':id',
                                row.id);

                            return `
        <div class="d-flex justify-content-center gap-2">

<div class="dropstart">
    <button class="btn btn-warning btn-sm text-white"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">
       <i class="fas fa-cog"></i>
    </button>

                <ul class="dropdown-menu dropdown-menu-lg">
                    <li>
                        <a class="dropdown-item" href="${baseUrl}#cpl-cpmk">
                            CPL & CPMK
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="${baseUrl}#prasyarat">
                            Prasyarat Mata Kuliah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="${baseUrl}#rps">
                            Detail RPS
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="${baseUrl}#rencana-pembelajaran">
                            Rencana Pembelajaran
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="${baseUrl}#rencana-evaluasi">
                            Rencana Evaluasi
                        </a>
                    </li>
                </ul>
            </div>

            <button class="btn btn-danger btn-sm delete-btn"
                    data-id="${row.id}"
                    data-nama="${row.nama_mk}">
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
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                },
                drawCallback: function(settings) {
                    $('#tableLoader').addClass('hidden');
                }
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus kurikulum "${nama}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('mata-kuliah.destroy', '__ID__') }}`.replace(
                                '__ID__', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghapus.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Export Data
            $('#exportData').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Exporting...');

                // Use Laravel route for export
                const exportUrl = "{{ route('mata-kuliah.export.data') }}?id_prodi={{ $id_prodi }}";
                window.location.href = exportUrl;

                setTimeout(function() {
                    btn.prop('disabled', false).html(originalText);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data mata kuliah berhasil diexport!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 1000);
            });
        });
    </script>
@endpush
