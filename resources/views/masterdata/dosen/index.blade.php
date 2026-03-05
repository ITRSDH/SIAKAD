@extends('layouts.index')
@section('title', 'Dosen Management')

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

        .modal-xxl {
            max-width: 95% !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dosen Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('dosen.index') }}">Dosen</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i>Data Dosen</h3>
                        <div class="d-flex gap-2"> <!-- Container untuk tombol-tombol -->
                            {{-- <a class="btn btn-success btn-sm" href="{{ route('dosen-mk.index') }}">
                                <i class="fas fa-calendar-plus me-1"></i>Lihat Dosen Mata Kuliah
                            </a> --}}
                            <button id="addDosenBtn" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Tambah Dosen
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="dosen-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>NIDN</th>
                                        <th>Prodi</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    <!-- Modal Tambah Dosen -->
    <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-labelledby="modalTambahDosenLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDosenLabel">Tambah Data Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="dosenForm" action="{{ route('dosen.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="dosenId">
                    <div class="modal-body">

                        <div class="row">

                            <!-- Prodi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <select name="id_prodi" id="id_prodi"
                                    class="form-select @error('id_prodi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['kode_prodi'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('id_prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIDN -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIDN</label>
                                <input type="text" name="nidn"
                                    class="form-control @error('nidn') is-invalid @enderror" placeholder="Opsional">
                                @error('nidn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NUP -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NUP</label>
                                <input type="text" name="nup" class="form-control @error('nup') is-invalid @enderror"
                                    placeholder="Opsional">
                                @error('nup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Dosen -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dosen"
                                    class="form-control @error('nama_dosen') is-invalid @enderror" required>
                                @error('nama_dosen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2"></textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp"
                                    class="form-control @error('no_hp') is-invalid @enderror" maxlength="20">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div> <!-- end row -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>

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

            const modal = new bootstrap.Modal('#modalTambahDosen');
            const dosen = @json($dosen ?? []); // pastikan controller kirim variabel $dosen

            // === DATATABLE ===
            const table = $('#dosen-table').DataTable({
                data: dosen,
                columns: [{
                        data: null,
                        render: (data, type, row, meta) =>
                            meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'nama_dosen'
                    },
                    {
                        data: 'nidn'
                    },
                    {
                        data: null,
                        render: row => row.prodi?.nama_prodi ?? '-'
                    },
                    {
                        data: null,
                        render: row => `
                    <div class="d-flex justify-content-center gap-2">
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
                drawCallback: () => $('#tableLoader').addClass('hidden')
            });

            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);


            // === TAMBAH DOSEN ===
            $('#addDosenBtn').click(() => {
                $('#dosenForm')[0].reset();
                $('#dosenId').val('');
                $('#modalTambahDosenLabel').text('Tambah Data Dosen');
                modal.show();
            });


            // === SIMPAN / UPDATE DOSEN ===
            $('#dosenForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#dosenId').val();
                const url = id ?
                    "{{ route('dosen.update', ':id') }}".replace(':id', id) :
                    "{{ route('dosen.store') }}";

                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url,
                    type: method,
                    data: $(this).serialize(),
                    beforeSend: () => Swal.showLoading(),
                    success: res => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message ?? 'Data berhasil disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        modal.hide();
                        location.reload();
                    },
                    error: err => {
                        Swal.close();
                        Swal.fire('Gagal', err.responseJSON?.message ?? 'Terjadi kesalahan.',
                            'error');
                    }
                });
            });


            // === EDIT DOSEN ===
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('dosen.show', ':id') }}".replace(':id', id);

                $.get(url, res => {
                        const d = res.data ?? res;
                        $('#dosenId').val(d.id);
                        $('#id_prodi').val(d.id_prodi);
                        $('input[name="nidn"]').val(d.nidn);
                        $('input[name="nup"]').val(d.nup);
                        $('input[name="nama_dosen"]').val(d.nama_dosen);
                        $('select[name="jenis_kelamin"]').val(d.jenis_kelamin);
                        $('input[name="tanggal_lahir"]').val(d.tanggal_lahir?.split('T')[0] ?? '');
                        $('textarea[name="alamat"]').val(d.alamat);
                        $('input[name="no_hp"]').val(d.no_hp);

                        $('#modalTambahDosenLabel').text('Edit Data Dosen');
                        modal.show();
                    })
                    .fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data', 'error'));
            });


            // === HAPUS DOSEN ===
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('dosen.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Hapus dosen ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
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
