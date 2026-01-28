@extends('layouts.index')
@section('title', 'Dosen Mata Kuliah')
@push('styles-custom')
    <style>
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .loader-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .action-buttons .btn {
            margin-bottom: 5px;
        }

        @media (min-width: 768px) {
            .action-buttons .btn {
                margin-bottom: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dosen Mata Kuliah</h3>
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
                    <a href="#">Dosen Mata Kuliah</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>Daftar Dosen Mata Kuliah
                        </h3>
                        <a href="{{ route('dosen-mk.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Buat Dosen Mata Kuliah Baru
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="dosenmk-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;" class="text-center">Aksi</th>
                                        <th class="text-center">Nama Dosen</th>
                                        <th class="text-center">NUP</th>
                                        <th class="text-center">Mata Kuliah</th>
                                        <th class="text-center">Kelas</th>
                                        <th class="text-center">Status</th>
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
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var dosenData =
            @json($dosen); // Pastikan nama variabel sesuai dengan yang dikirim dari controller

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#dosenmk-table').DataTable({
                data: dosenData,
                columns: [{
                        data: null, // Tidak ada data spesifik dari API untuk kolom ini
                        render: function(data, type, row) {
                            // Generate tombol aksi berdasarkan ID dari data API
                            var editUrl = "{{ route('dosen-mk.edit', ':id') }}".replace(':id', row
                                .id);

                            // URL untuk halaman beban ajar dan jadwal
                            var bebanAjarUrl = "{{ route('jadwal.beban-ajar-dosen.index', ':id') }}"
                                .replace(':id', row.id);

                            return `
                            <div class="d-flex justify-content-center gap-2 flex-wrap action-buttons">
                                <a href="${bebanAjarUrl}"
                                   class="btn btn-info btn-sm"
                                   title="Beban Ajar & Jadwal">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                                <a href="${editUrl}"
                                   class="btn btn-warning btn-sm edit-btn"
                                   title="Edit Dosen Mata Kuliah">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn"
                                        data-id="${row.id}"
                                        data-nama="${row.dosen.nama_dosen}"
                                        title="Hapus Dosen Mata Kuliah">
                                    <i class="fas fa-trash"></i>
                                </button>
                           </div>
                        `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'dosen.nama_dosen',
                        render: function(data) {
                            return data || 'N/A';
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'dosen.nup',
                        render: function(data) {
                            return data || 'N/A';
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'kelas_mk.mata_kuliah.nama_mk',
                        render: function(data) {
                            return data || 'N/A';
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'kelas_mk.kode_kelas_mk',
                        render: function(data) {
                            return data || 'N/A';
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            // Cek apakah sudah ada jadwal
                            var hasSchedule = row.jadwal_kuliah && Object.keys(row.jadwal_kuliah)
                                .length > 0;
                            var statusClass = hasSchedule ? 'badge-success' : 'badge-warning';
                            var statusText = hasSchedule ? 'Sudah Ada Jadwal' : 'Belum Ada Jadwal';

                            return `<span class="badge ${statusClass}">${statusText}</span>`;
                        },
                        className: 'text-center'
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

            // Event handler untuk tombol delete
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus Dosen Mata Kuliah "${nama}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('dosen-mk.destroy', '__ID__') }}`.replace(
                                '__ID__', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success || response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message || response
                                            .success,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        table.row($('button[data-id="' + id +
                                                '"]').parents('tr')).remove()
                                            .draw();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan.',
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
