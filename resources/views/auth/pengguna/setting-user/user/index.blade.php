@extends('layouts.index')
@section('title', $pageTitle ?? 'User Management')

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
            <h3 class="fw-bold mb-3">{{ $pageHeading ?? ($pageTitle ?? 'User Management') }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ $pageRoute ?? route('users.index') }}">{{ $pageTitle ?? 'User Management' }}</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ $pageRoute ?? route('users.index') }}">{{ $pageListLabel ?? 'List User Management' }}</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Data {{ $pageTitle ?? 'User Management' }}
                        </h3>
                        <button id="addUserBtn" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pengguna
                        </button>
                    </div>
                    <div class="card-body">
                        @if (!empty($pageDescription))
                            <div class="alert alert-info">{{ $pageDescription }}</div>
                        @endif

                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="user-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data diisi dari JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="userId">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Tambah Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" id="name" class="form-control" placeholder="Masukkan Nama"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control" placeholder="Masukkan Email">
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" id="password" class="form-control" placeholder=" Masukkan Password">
                                <small class="text-muted" id="passwordHint">Kosongkan jika tidak ingin mengganti password.
                                </small>
                            </div>
                            <div class="mb-3">
                                <label>Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" class="form-control"
                                    placeholder="Masukkan Konfirmasi Password">
                                <small class="text-muted" id="passwordConfirmationHint">Kosongkan jika tidak ingin mengganti
                                    password.</small>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select id="status" class="form-select">
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak-aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Role</label>
                                <select id="roles" class="form-select">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles ?? [] as $r)
                                        <option value="{{ $r['name'] }}" {{ ($roleFilter ?? null) === $r['name'] ? 'selected' : '' }}>{{ $r['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
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
        $(function() {
            const modal = new bootstrap.Modal('#userModal');
            const userData = @json($users ?? []);
            const table = $('#user-table').DataTable({
                data: userData,
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
                        data: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'status',
                        render: data => data === 'aktif' ?
                            '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-secondary">Tidak Aktif</span>'
                    },
                    {
                        data: 'roles',
                        render: roles => roles?.length ? roles.map(r =>
                            `<span class="badge bg-info">${r.name}</span>`).join(' ') : '-'
                    },
                    {
                        data: null,
                        render: row => `
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>`
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                },
                responsive: true,
                autoWidth: false,
                drawCallback: () => $('#tableLoader').addClass('hidden')
            });

            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);

            // === Tambah User ===
            $('#addUserBtn').click(() => {
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#modalTitle').text('Tambah Pengguna');
                $('#roles').val(@json($roleFilter));
                $('#passwordHint').hide(); // Hide password hint for POST
                $('#passwordConfirmationHint').hide(); // Hide confirmation hint for POST
                modal.show();
            });

            // === Simpan / Update ===
            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#userId').val();
                const url = id ?
                    "{{ route('users.update', ':id') }}".replace(':id', id) :
                    "{{ route('users.store') }}";
                const method = id ? 'PUT' : 'POST';

                const payload = {
                    _token: "{{ csrf_token() }}",
                    name: $('#name').val(),
                    email: $('#email').val(),
                    status: $('#status').val(),
                    roles: $('#roles').val()
                };

                // Only include password if it's not empty
                const password = $('#password').val();
                const password_confirmation = $('#password_confirmation').val();
                if (password) {
                    payload.password = password;
                }
                if (password_confirmation) {
                    payload.password_confirmation = password_confirmation;
                }
                $.ajax({
                    url,
                    method,
                    data: payload,
                    beforeSend: () => Swal.showLoading(),
                    success: res => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message ?? 'Data disimpan',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        modal.hide();
                        location.reload();
                    },
                    error: err => {
                        console.log(err);

                        Swal.close();
                        let msg = 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors.errors) {
                            msg = Object.values(err.responseJSON.errors.errors).flat().join(
                                '<br>');
                        }
                        Swal.fire('Gagal', msg, 'error');
                    }
                });
            });

            // === Edit ===
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('users.show', ':id') }}".replace(':id', id);
                $.get(url, res => {
                    const u = res.data ?? res;
                    $('#userId').val(u.id);
                    $('#name').val(u.name);
                    $('#email').val(u.email);
                    $('#status').val(u.status === 'aktif' ? 'aktif' : 'tidak-aktif');
                    $('#roles').val(u.roles?.[0]?.name ?? @json($roleFilter));
                    $('#password').val('');
                    $('#passwordHint').show(); // Show password hint for PUT
                    $('#passwordConfirmationHint').show(); // Show confirmation hint for PUT
                    $('#modalTitle').text('Edit Pengguna');
                    modal.show();
                }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data pengguna', 'error'));
            });

            // === Hapus ===
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('users.destroy', ':id') }}".replace(':id', id);
                Swal.fire({
                    title: 'Hapus pengguna ini?',
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
