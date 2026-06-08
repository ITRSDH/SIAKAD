@extends('layouts.index')
@section('title', $pageTitle ?? 'Dosen Wali Management')
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
            <h3 class="fw-bold mb-3">{{ $pageHeading ?? ($pageTitle ?? 'Dosen Wali Management') }}</h3>
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
                    <a href="{{ $pageRoute ?? route('dosen-wali.index') }}">{{ $pageTitle ?? 'Dosen Wali' }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ $pageRoute ?? route('dosen-wali.index') }}">{{ $pageListLabel ?? 'List Dosen Wali' }}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title"> Data {{ $pageTitle ?? 'Dosen Wali' }}</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ $createRoute ?? route('dosen-wali.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($pageDescription))
                            <div class="alert alert-info">{{ $pageDescription }}</div>
                        @endif

                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="dosen-wali-table" class="table table-bordered table-striped table-hover text-center"
                                style="width:100%">

                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="10%" class="text-center">Dosen</th>
                                        <th width="10%" class="text-center">NIDN</th>
                                        <th width="10%" class="text-center">Jumlah Mahasiswa Bimbingan</th>
                                        <th width="10%" class="text-center">AKSI</th>
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
            var table = $('#dosen-wali-table').DataTable({
                ajax: {
                    url: "{{ route('dosen-wali.getDataDosenWali') }}"
                },
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
                        data: 'nama_dosen',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'nidn',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'total_bimbingan',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var detailUrl = "{{ route('dosen-wali.detail', ':id') }}".replace(
                                ':id',
                                row
                                .id);
                            return `
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="${detailUrl}" class="btn btn-warning btn-sm text-white">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-all"
                                                    data-id="${row.id}" data-nama="${row.nama_dosen}"
                                                    data-total="${row.total_bimbingan || 0}">
                                                <i class="fas fa-trash-alt"></i>
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

            // Handle remove all mahasiswa
            $(document).on('click', '.btn-remove-all', function(e) {
                e.preventDefault();

                const btn = $(this);
                const dosenWaliId = btn.data('id');
                const dosenNama = btn.data('nama');
                const totalMahasiswa = parseInt(btn.data('total'));

                // Check if there are any mahasiswa to remove
                if (totalMahasiswa === 0) {
                    Swal.fire({
                        title: 'Informasi!',
                        text: 'Tidak ada mahasiswa bimbingan untuk dihapus.',
                        icon: 'info'
                    });
                    return;
                }

                // First, get all mahasiswa IDs for this dosen wali
                $.ajax({
                    url: `{{ route('dosen-wali.detail', ':id') }}`.replace(':id', dosenWaliId),
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (!response || !response.data || !response.data.mahasiswa) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal mengambil data mahasiswa.',
                                icon: 'error'
                            });
                            return;
                        }

                        const mahasiswaList = response.data.mahasiswa;
                        const mahasiswaIds = mahasiswaList.map(m => m.id);

                        if (mahasiswaIds.length === 0) {
                            Swal.fire({
                                title: 'Informasi!',
                                text: 'Tidak ada mahasiswa bimbingan untuk dihapus.',
                                icon: 'info'
                            });
                            return;
                        }

                        // Show confirmation dialog
                        Swal.fire({
                            title: 'Konfirmasi Hapus Semua',
                            html: `Apakah Anda yakin ingin menghapus <strong>semua ${mahasiswaIds.length} mahasiswa</strong> dari daftar bimbingan <strong>${dosenNama}</strong>?<br><br><span class="text-danger">Tindakan ini tidak dapat dibatalkan!</span>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Hapus Semua!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading
                                $('#tableLoader').removeClass('hidden');
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
                                        $('#tableLoader').addClass(
                                            'hidden');
                                        btn.prop('disabled', false);

                                        if (response.success) {
                                            Swal.fire({
                                                title: 'Berhasil!',
                                                text: `Semua mahasiswa (${response.removed_count || mahasiswaIds.length}) berhasil dihapus dari daftar bimbingan.`,
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false
                                            }).then(() => {
                                                // Refresh DataTable
                                                table.ajax.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Gagal!',
                                                text: response
                                                    .message ||
                                                    'Terjadi kesalahan saat menghapus semua data.',
                                                icon: 'error'
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#tableLoader').addClass(
                                            'hidden');
                                        btn.prop('disabled', false);

                                        let errorMessage =
                                            'Terjadi kesalahan saat menghapus semua data.';
                                        if (xhr.responseJSON && xhr
                                            .responseJSON.message) {
                                            errorMessage = xhr.responseJSON
                                                .message;
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
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil data mahasiswa.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endpush
