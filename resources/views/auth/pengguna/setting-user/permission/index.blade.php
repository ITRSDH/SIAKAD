@extends('layouts.index')
@section('title', 'Permission Management')

@push('styles-custom')
    <style>
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
            z-index: 5;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #007bff;
            border-top: 4px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Permission Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('permissions.index') }}">Permission Management</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-lock me-2"></i>Data Permission
                        </h3>
                        <button id="syncPermissionBtn" class="btn btn-success btn-sm">
                            <i class="fas fa-sync-alt"></i> Sinkronisasi Permission
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="permission-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Permission</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data dari JS -->
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
    <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            const permissionData = @json($permissions ?? []);
            const table = $('#permission-table').DataTable({
                data: permissionData,
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
                        data: 'name',
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        render: row => `
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `
                    }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json') }}'
                },
                responsive: true,
                autoWidth: false,
                drawCallback: () => $('#tableLoader').addClass('hidden')
            });
            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);

            // === Sinkronisasi Route ===
            $('#syncPermissionBtn').click(() => {
                Swal.fire({
                    title: 'Sinkronisasi Permission?',
                    text: 'Permission akan disesuaikan dengan daftar route yang ada.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, sinkronkan!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        // Jalankan sinkronisasi
                        $.ajax({
                            url: "{{ route('permissions.sync') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: () => {
                                // === Menampilkan SweetAlert timer ===
                                let timerInterval;
                                Swal.fire({
                                    title: "Sedang Sinkronisasi...",
                                    html: "Menutup otomatis dalam <b></b> milidetik.",
                                    timer: 3000,
                                    timerProgressBar: true,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup()
                                            .querySelector("b");
                                        timerInterval = setInterval(() => {
                                            timer.textContent =
                                                `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                    }
                                });
                            },
                            success: res => {
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil!",
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(() => location.reload(), 1800);
                                }, 500);
                            },
                            error: () => {
                                Swal.fire('Gagal', 'Sinkronisasi gagal dilakukan.',
                                    'error');
                            }
                        });
                    }
                });
            });

            // === Hapus Permission ===
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('permissions.destroy', ':id') }}".replace(':id', id);
                Swal.fire({
                    title: 'Hapus permission ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: res => {
                                Swal.fire('Berhasil', res.message ?? 'Data dihapus',
                                    'success');
                                table.rows((i, d) => d.id === id).remove().draw();
                            },
                            error: () => Swal.fire('Gagal', 'Tidak dapat menghapus data.',
                                'error')
                        });
                    }
                });
            });
        });
    </script>
@endpush
