@extends('layouts.index')
@section('title', 'Struktur Kurikulum')
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Struktur Kurikulum</h3>
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
                    <a href="{{ route('kurikulum.index') }}">Struktur Kurikulum</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kurikulum.index') }}">List Struktur Kurikulum</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Struktur Kurikulum</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kurikulum-induk.index') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-sitemap me-1"></i> Tahun Kurikulum
                                </a>
                                <a href="{{ route('kurikulum.create') }}" class="btn btn-sm btn-primary">
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
                            <table id="kurikulum-table" class="table table-bordered table-striped table-hover text-center"
                                style="width:100%">

                                <thead class="table-dark opacity-75">
                                    <tr>
                                        <th rowspan="2" width="5%">No</th>
                                        <th rowspan="2" width="12%">Kode Induk</th>
                                        <th rowspan="2" width="16%">Keterangan Tahun Kurikulum</th>
                                        <th rowspan="2" width="12%">Jenis</th>
                                        <th rowspan="2" width="18%">Nama Struktur Kurikulum</th>
                                        <th rowspan="2" width="14%">Program Studi</th>
                                        <th rowspan="2" width="12%">Mulai Berlaku</th>

                                        <th colspan="3" width="20%">Aturan Jumlah SKS</th>
                                        <th colspan="2" width="15%">Jumlah SKS Matakuliah</th>

                                        <th rowspan="2" width="10%">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>Lulus</th>
                                        <th>Wajib</th>
                                        <th>Pilihan</th>

                                        <th>Wajib</th>
                                        <th>Pilihan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- DataTables isi disini -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Perbaiki URL -->
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#kurikulum-table').DataTable({
                ajax: {
                    url: "{{ route('kurikulum.dataKurikulum') }}"
                },
                columnDefs: [{
                    orderable: false,
                    targets: [7, 8, 9, 10, 11, 12]
                }],
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kurikulum_induk',
                        className: 'text-center',
                        render: function(data) {
                            return data?.kode_kurikulum || '-';
                        }
                    },
                    {
                        data: 'keterangan_kurikulum_induk',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'kurikulum_induk',
                        className: 'text-center',
                        render: function(data) {
                            return data?.jenis_kurikulum?.kode_jenis || '-';
                        }
                    },
                    {
                        data: 'nama_struktur_mk',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var detailUrl = "{{ route('kurikulum.detail', ':id') }}".replace(':id',
                                row
                                .id);
                            return `
                                <a href="${detailUrl}"
                                class="fw-bold text-primary"
                                data-id="${row.id}">
                                    ${data}
                                </a>
                            `;
                        }
                    },
                    {
                        data: 'prodi',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'semester_mulai',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'jumlah_sks_lulus',
                        className: 'text-center',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: 'jumlah_sks_wajib',
                        className: 'text-center',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: 'jumlah_sks_pilihan',
                        className: 'text-center',
                        render: function(data) {
                            return data || 0;
                        }
                    },

                    {
                        data: 'jumlah_sks_wajib_mk',
                        className: 'text-center',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: 'jumlah_sks_pilihan_mk',
                        className: 'text-center',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var detailUrl = "{{ route('kurikulum.detail', ':id') }}".replace(':id',
                                row
                                .id);
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="${detailUrl}" class="btn btn-warning btn-sm text-white">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" data-nama="${row.nama_struktur_mk || row.nama_kurikulum}">
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

            $('#kurikulum-table').on('click', '.detail-btn', function() {
                let id = $(this).data('id');

                $('#detailId').val(id);
                $('#detailForm').submit();
            });

            // Event handler untuk tombol delete
            $(document).on('click', '.delete-btn', function(event) {
                event.preventDefault();
                event.stopPropagation();

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
                            url: `{{ route('kurikulum.destroy', '__ID__') }}`.replace(
                                '__ID__', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
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

        });
    </script>
@endpush
