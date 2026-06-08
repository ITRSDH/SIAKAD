@extends('layouts.index')
@section('title', 'Jenis Kurikulum')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Jenis Kurikulum</h3>
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
                    <a href="{{ route('jenis-kurikulum.index') }}">Jenis Kurikulum</a>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Tambah Jenis Kurikulum</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jenis-kurikulum.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="kode_jenis" class="form-label">Kode Jenis</label>
                                <input type="text" class="form-control" id="kode_jenis" name="kode_jenis"
                                    value="{{ old('kode_jenis') }}" placeholder="Contoh: MBKM" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_jenis_kurikulum" class="form-label">Nama Jenis Kurikulum</label>
                                <input type="text" class="form-control" id="nama_jenis_kurikulum" name="nama_jenis_kurikulum"
                                    value="{{ old('nama_jenis_kurikulum') }}" placeholder="Contoh: Kurikulum Merdeka Belajar Kampus Merdeka" required>
                            </div>
                            <input type="hidden" name="is_aktif" value="0">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_aktif">Aktif</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('kurikulum-induk.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Daftar Jenis Kurikulum</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark opacity-75">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="18%">Kode</th>
                                        <th>Nama Jenis Kurikulum</th>
                                        <th width="12%">Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jenisKurikulum as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-primary">{{ $item['kode_jenis'] }}</span></td>
                                            <td>{{ $item['nama_jenis_kurikulum'] }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ !empty($item['is_aktif']) ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ !empty($item['is_aktif']) ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-warning edit-jenis-btn"
                                                        data-id="{{ $item['id'] }}"
                                                        data-kode="{{ $item['kode_jenis'] }}"
                                                        data-nama="{{ $item['nama_jenis_kurikulum'] }}"
                                                        data-aktif="{{ !empty($item['is_aktif']) ? 1 : 0 }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-danger delete-jenis-btn"
                                                        data-id="{{ $item['id'] }}"
                                                        data-nama="{{ $item['nama_jenis_kurikulum'] }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada jenis kurikulum.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editJenisKurikulumModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editJenisKurikulumForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Jenis Kurikulum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kode_jenis" class="form-label">Kode Jenis</label>
                            <input type="text" class="form-control" id="edit_kode_jenis" name="kode_jenis" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_jenis_kurikulum" class="form-label">Nama Jenis Kurikulum</label>
                            <input type="text" class="form-control" id="edit_nama_jenis_kurikulum" name="nama_jenis_kurikulum" required>
                        </div>
                        <input type="hidden" name="is_aktif" value="0">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="edit_is_aktif" name="is_aktif" value="1">
                            <label class="form-check-label" for="edit_is_aktif">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModalElement = document.getElementById('editJenisKurikulumModal');
            const editModal = editModalElement ? new bootstrap.Modal(editModalElement) : null;

            $(document).on('click', '.edit-jenis-btn', function() {
                const id = $(this).data('id');
                $('#editJenisKurikulumForm').attr('action', "{{ route('jenis-kurikulum.update', ':id') }}".replace(':id', id));
                $('#edit_kode_jenis').val($(this).data('kode'));
                $('#edit_nama_jenis_kurikulum').val($(this).data('nama'));
                $('#edit_is_aktif').prop('checked', Number($(this).data('aktif')) === 1);
                editModal.show();
            });

            $(document).on('click', '.delete-jenis-btn', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus jenis kurikulum?',
                    text: `Data "${nama}" akan dihapus.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('jenis-kurikulum.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.message || 'Jenis kurikulum berhasil dihapus.', 'success')
                                .then(() => window.location.reload());
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'Gagal menghapus jenis kurikulum.';
                            Swal.fire('Gagal', message, 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
