@extends('layouts.index')
@section('title', 'Role Management')

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

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="false"] .collapse-icon {
            transform: rotate(180deg);
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Role Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('roles.index') }}">Role Management</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-shield me-2"></i>Data Role
                        </h3>
                        <button id="addRoleBtn" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Role
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="role-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Role</th>
                                        {{-- <th>Permissions</th> --}}
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Diisi dari JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="roleForm">
                        @csrf
                        <input type="hidden" id="roleId">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Tambah Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Role</label>
                                <input type="text" id="name" class="form-control" required>
                            </div>

                            @php
                                /* ==========================================================
                                    1. Helper: Buat label dari permission
                                    ========================================================== */
                                function permLabel($perm)
                                {
                                    $parts = explode('.', $perm);
                                    return ucfirst(str_replace('-', ' ', end($parts)));
                                }

                                /* ==========================================================
                                    2. Konversi struktur dari $menu['menus'] menjadi struktur UI
                                    ========================================================== */
                                function convertToUICode($menus)
                                {
                                    $result = [];

                                    foreach ($menus as $menu) {
                                        $entry = [
                                            'title' => $menu['title'],
                                            'permissions' => [],
                                            'sub' => [],
                                        ];

                                        // Permissions langsung
                                        foreach ($menu['permissions'] as $perm) {
                                            $entry['permissions'][] = [
                                                'label' => permLabel($perm),
                                                'permission' => $perm,
                                            ];
                                        }

                                        // Recursive children
                                        foreach ($menu['children'] as $child) {
                                            $entry['sub'][] = convertToUICode([$child])[0];
                                        }

                                        $result[] = $entry;
                                    }

                                    return $result;
                                }

                                /* ==========================================================
                                    3. Konversi root menu
                                    ========================================================== */
                                $sections = [
                                    [
                                        'section' => 'Permissions',
                                        'menus' => convertToUICode($menu['menus']),
                                    ],
                                ];

                                /* ==========================================================
                                    4. FUNGSI RECURSIVE UNTUK TAMPILAN (tanpa include)
                                    ========================================================== */
                                function renderSubmenus($subs, $parentKey)
                                {
                                    $html = '';

                                    foreach ($subs as $sub) {
                                        $subKey = Str::slug($parentKey . '-' . $sub['title']);

                                        $isLeaf = empty($sub['sub']); // children paling akhir

                                        /* =====================================================
                                            1. NODE BUKAN LEAF → tetap pakai collapse + header
                                        ====================================================== */
                                        if (!$isLeaf) {
                                            $html .=
                                                '
                                                <div class="card mb-2">
                                                    <div class="card-header bg-secondary text-white"
                                                        role="button" data-bs-toggle="collapse"
                                                        href="#collapse-' .
                                                $subKey .
                                                '">
                                                        <strong>' .
                                                $sub['title'] .
                                                '</strong>
                                                    </div>

                                                    <div id="collapse-' .
                                                $subKey .
                                                '" class="collapse">
                                                        <div class="card-body p-2">
                                            ';

                                            // TABEL LEVEL INI
                                            if (!empty($sub['permissions'])) {
                                                $html .= '
                                                        <div class="table-responsive">
                                                        <table class="table table-sm table-bordered align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>Label</th>
                                                                    <th>Permission</th>
                                                                    <th width="10%">Check</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                    ';

                                                foreach ($sub['permissions'] as $perm) {
                                                    $html .=
                                                        '
                                                            <tr>
                                                                <td>' .
                                                        $perm['label'] .
                                                        '</td>
                                                                <td>' .
                                                        $perm['permission'] .
                                                        '</td>
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                                                        value="' .
                                                        $perm['permission'] .
                                                        '">
                                                                </td>
                                                            </tr>
                                                        ';
                                                }

                                                $html .= '</tbody></table></div>';
                                            }

                                            // RECURSIVE CHILDREN
                                            $html .=
                                                '<div class="ps-4">' . renderSubmenus($sub['sub'], $subKey) . '</div>';

                                            $html .= '</div></div></div>';
                                        } /* =====================================================
                                                2. LEAF → hanya tampilkan tabel, TANPA HEADER
                                            ====================================================== */ else {
                                            // Jika leaf punya permission
                                            if (!empty($sub['permissions'])) {
                                                $html .= '
                                                <div class="table-responsive">
                                                        <table class="table table-sm table-bordered align-middle mt-2">
                                                            <thead>
                                                                <tr>
                                                                    <th>Label</th>
                                                                    <th>Permission</th>
                                                                    <th width="10%">Check</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                    ';

                                                foreach ($sub['permissions'] as $perm) {
                                                    $html .=
                                                        '
                                                            <tr>
                                                                <td>' .
                                                        $perm['label'] .
                                                        '</td>
                                                                <td>' .
                                                        $perm['permission'] .
                                                        '</td>
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                                                        value="' .
                                                        $perm['permission'] .
                                                        '">
                                                                </td>
                                                            </tr>
                                                        ';
                                                }

                                                $html .= '</tbody></table></div>';
                                            }
                                        }
                                    }

                                    return $html;
                                }

                            @endphp


                            {{-- ==========================================================
                                5. TAMPILAN UI (Satu file, tanpa include)
                            ========================================================== --}}
                            <div class="mb-3">
                                <label>Permission</label>

                                <div class="mb-3 d-flex align-items-center justify-content-end">
                                    <input type="checkbox" id="selectAllGlobal" class="form-check-input me-2">
                                    <label for="selectAllGlobal" class="fw-bold">Pilih Semua Permission</label>
                                </div>

                                <div id="permissionList">

                                    @foreach ($sections as $section)
                                        <div class="card border-primary mb-3 shadow-sm">

                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">{{ $section['section'] }}</h5>
                                            </div>

                                            <div class="card-body p-2">

                                                @foreach ($section['menus'] as $menuItem)
                                                    @php $menuKey = Str::slug($menuItem['title']); @endphp

                                                    <div class="card mb-2 shadow-sm">

                                                        <div class="card-header bg-light d-flex justify-content-between align-items-center"
                                                            role="button" data-bs-toggle="collapse"
                                                            href="#collapse-{{ $menuKey }}">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-folder-open me-2 text-primary"></i>
                                                                {{ $menuItem['title'] }}
                                                            </h6>
                                                        </div>

                                                        <div id="collapse-{{ $menuKey }}" class="collapse">
                                                            <div class="card-body p-2">

                                                                {{-- Permissions langsung --}}
                                                                @foreach ($menuItem['permissions'] as $perm)
                                                                    <div class="form-check mb-1">
                                                                        <input type="checkbox"
                                                                            class="form-check-input permission-checkbox"
                                                                            value="{{ $perm['permission'] }}">

                                                                        <label class="form-check-label">
                                                                            {{ $perm['label'] }}
                                                                            <small
                                                                                class="text-muted">({{ $perm['permission'] }})</small>
                                                                        </label>
                                                                    </div>
                                                                @endforeach

                                                                {{-- CHILDREN (recursive) --}}
                                                                {!! renderSubmenus($menuItem['sub'], $menuKey) !!}

                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach

                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            </div>



                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
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
        $(document).ready(function() {
            // === Fitur Select All Global ===
            $('#selectAllGlobal').on('change', function() {
                const checked = $(this).is(':checked');
                $('.permission-checkbox').prop('checked', checked);
            });

            // === Jika ada 1 checkbox diubah, update status Select All Global ===
            $(document).on('change', '.permission-checkbox', function() {
                const total = $('.permission-checkbox').length;
                const checked = $('.permission-checkbox:checked').length;
                $('#selectAllGlobal').prop('checked', total === checked);
            });
        });
    </script>
    <script>
        $(function() {
            const modal = new bootstrap.Modal('#roleModal');
            const roles = @json($roles ?? []);
            const table = $('#role-table').DataTable({
                data: roles,
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
                    //{
                    // data: 'permissions',
                    //render: p => p.map(x =>
                    //`<span class="badge bg-info text-dark">${x.name}</span>`).join(' ')
                    //},
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

            // === Tambah Role ===
            $('#addRoleBtn').click(() => {
                $('#roleForm')[0].reset();
                $('#roleId').val('');
                $('#modalTitle').text('Tambah Role');
                $('.permission-checkbox').prop('checked', false);
                modal.show();
            });

            // === Simpan / Update ===
            $('#roleForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#roleId').val();
                const url = id ?
                    "{{ route('roles.update', ':id') }}".replace(':id', id) :
                    "{{ route('roles.store') }}";
                const method = id ? 'PUT' : 'POST';
                const permissions = $('.permission-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                $.ajax({
                    url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $('#name').val(),
                        permissions: permissions
                    },
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
                        Swal.close();
                        let msg = 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) {
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
                const url = "{{ route('roles.show', ':id') }}".replace(':id', id);
                $.get(url, res => {
                    const r = res.data ?? res;
                    $('#roleId').val(r.id);
                    $('#name').val(r.name);
                    $('.permission-checkbox').prop('checked', false);
                    r.permissions.forEach(p => {
                        $(`.permission-checkbox[value="${p.name}"]`).prop('checked', true);
                    });
                    $('#modalTitle').text('Edit Role');
                    modal.show();
                }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data role', 'error'));
            });

            // === Hapus ===
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('roles.destroy', ':id') }}".replace(':id', id);
                Swal.fire({
                    title: 'Hapus role ini?',
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
