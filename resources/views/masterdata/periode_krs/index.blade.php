@extends('layouts.index')
@section('title', 'Data Periode KRS')

@push('styles-custom')
    <style>
        .action-buttons {
            display: flex;
            gap: 0.25rem;
        }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Master Data</h3>
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
                    <a href="{{ route('periode-krs.index') }}">Periode KRS</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Periode KRS</h4>
                            <a href="{{ route('periode-krs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Periode
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="periode-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi via JavaScript --}}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var periodeData = @json($periodeKRS);

            function formatTanggalIndonesia(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return dateStr;
                return d.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }

            function truncateText(text, maxLen) {
                if (!text) return '-';
                if (text.length <= maxLen) return text;
                return text.substring(0, maxLen) + '...';
            }

            // Inisialisasi DataTables client-side dari data PHP
            const table = $('#periode-table').DataTable({
                data: periodeData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'semester',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `${data.kode_semester} - ${data.nama_semester}`;
                        }
                    },
                    {
                        data: 'tanggal_mulai',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return formatTanggalIndonesia(data);
                        }
                    },
                    {
                        data: 'tanggal_selesai',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return formatTanggalIndonesia(data);
                        }
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            if (data === 'aktif') {
                                return '<span class="badge bg-success">Aktif</span>';
                            } else if (data === 'draft') {
                                return '<span class="badge bg-secondary">Draft</span>';
                            } else if (data === 'ditutup') {
                                return '<span class="badge bg-danger">Ditutup</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'catatan',
                        render: function(data) {
                            return truncateText(data, 50);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            const editUrl = "{{ route('periode-krs.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="${editUrl}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon delete-btn" data-id="${row.id}" data-nama="${row.semester?.tahun_ajaran || 'Data'}" title="Hapus">
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

            // Delete Function - Use event delegation for dynamic content
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data periode "${nama}" akan dihapus permanently!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/periode-krs/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    table.row($('button[data-id="' + id + '"]').closest('tr')).remove().draw();
                                    
                                    Swal.fire('Berhasil!', 'Data periode KRS berhasil dihapus', 'success');
                                } else {
                                    Swal.fire('Error', 'Gagal menghapus data periode KRS', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush