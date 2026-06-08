@extends('layouts.index')
@section('title', 'Tahun Kurikulum')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tahun Kurikulum</h3>
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
                    <a href="{{ route('kurikulum-induk.index') }}">Tahun Kurikulum</a>
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
                        <h4 class="card-title mb-0">Tambah Tahun Kurikulum</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kurikulum-induk.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="id_prodi" class="form-label">Program Studi</label>
                                <select class="form-select" id="id_prodi" name="id_prodi" required>
                                    <option value="" selected disabled>Pilih Program Studi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_jenis_kurikulum" class="form-label">Jenis Kurikulum</label>
                                <select class="form-select" id="id_jenis_kurikulum" name="id_jenis_kurikulum" required>
                                    <option value="" selected disabled>Pilih Jenis Kurikulum</option>
                                    @foreach ($jenisKurikulum as $item)
                                        <option value="{{ $item['id'] }}" {{ old('id_jenis_kurikulum') == $item['id'] ? 'selected' : '' }}>{{ $item['kode_jenis'] }} - {{ $item['nama_jenis_kurikulum'] }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <a href="{{ route('jenis-kurikulum.index') }}">Kelola jenis kurikulum</a> jika daftar belum sesuai.
                                </small>
                            </div>
                            <div class="mb-3">
                                <label for="tahun_kurikulum" class="form-label">Tahun Kurikulum</label>
                                <input type="text" class="form-control" id="tahun_kurikulum" name="tahun_kurikulum"
                                    value="{{ old('tahun_kurikulum') }}" placeholder="Contoh: 2024" maxlength="4" required>
                            </div>
                            <div class="mb-3">
                                <label for="kode_kurikulum" class="form-label">Kode Kurikulum</label>
                                <input type="text" class="form-control" id="kode_kurikulum" name="kode_kurikulum"
                                    value="{{ old('kode_kurikulum') }}" placeholder="Opsional, akan digenerate otomatis jika dikosongkan">
                            </div>
                            <input type="hidden" name="is_aktif" value="0">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_aktif">Jadikan aktif</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('kurikulum.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('jenis-kurikulum.index') }}" class="btn btn-info">
                                    <i class="fas fa-layer-group me-1"></i> Jenis Kurikulum
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Daftar Tahun Kurikulum</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark opacity-75">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="14%">Kode</th>
                                        <th width="10%">Tahun</th>
                                        <th width="14%">Mulai Berlaku</th>
                                        <th>Keterangan</th>
                                        <th width="18%">Jenis</th>
                                        <th>Program Studi</th>
                                        <th width="10%">Status</th>
                                        <th width="12%">Jumlah Struktur</th>
                                        <th width="24%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kurikulumInduk as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['kode_kurikulum'] ?? '-' }}</td>
                                            <td class="text-center">{{ $item['tahun_kurikulum'] ?? '-' }}</td>
                                            <td>{{ $item['mulai_berlaku'] ?? '-' }}</td>
                                            <td>{{ $item['keterangan'] ?? $item['nama_kurikulum'] }}</td>
                                            <td>{{ $item['jenis_kurikulum']['nama_jenis_kurikulum'] ?? '-' }}</td>
                                            <td>{{ $item['prodi'] ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ !empty($item['is_aktif']) ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ !empty($item['is_aktif']) ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $item['jumlah_struktur_operasional'] ?? 0 }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-warning edit-induk-btn"
                                                        data-id="{{ $item['id'] }}"
                                                        data-id_prodi="{{ $item['id_prodi'] }}"
                                                        data-id_jenis_kurikulum="{{ $item['jenis_kurikulum']['id'] ?? '' }}"
                                                        data-kode_kurikulum="{{ $item['kode_kurikulum'] ?? '' }}"
                                                        data-tahun_kurikulum="{{ $item['tahun_kurikulum'] ?? '' }}"
                                                        data-is_aktif="{{ !empty($item['is_aktif']) ? 1 : 0 }}"
                                                        data-nama="{{ $item['nama_kurikulum'] }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-danger delete-induk-btn"
                                                        data-id="{{ $item['id'] }}"
                                                        data-nama="{{ $item['nama_kurikulum'] }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">Belum ada data tahun kurikulum.</td>
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

    <div class="modal fade" id="editKurikulumIndukModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editKurikulumIndukForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Tahun Kurikulum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_id_prodi" class="form-label">Program Studi</label>
                            <select class="form-select" id="edit_id_prodi" name="id_prodi" required>
                                <option value="" selected disabled>Pilih Program Studi</option>
                                @foreach ($prodi as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['prodi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_jenis_kurikulum" class="form-label">Jenis Kurikulum</label>
                            <select class="form-select" id="edit_id_jenis_kurikulum" name="id_jenis_kurikulum" required>
                                <option value="" selected disabled>Pilih Jenis Kurikulum</option>
                                @foreach ($jenisKurikulum as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['kode_jenis'] }} - {{ $item['nama_jenis_kurikulum'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tahun_kurikulum" class="form-label">Tahun Kurikulum</label>
                            <input type="text" class="form-control" id="edit_tahun_kurikulum" name="tahun_kurikulum" maxlength="4" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kode_kurikulum" class="form-label">Kode Kurikulum</label>
                            <input type="text" class="form-control" id="edit_kode_kurikulum" name="kode_kurikulum">
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
            const editModalElement = document.getElementById('editKurikulumIndukModal');
            const editModal = editModalElement ? new bootstrap.Modal(editModalElement) : null;

            $(document).on('click', '.edit-induk-btn', function() {
                const id = $(this).data('id');
                const idProdi = $(this).data('id_prodi');
                const idJenisKurikulum = $(this).data('id_jenis_kurikulum');
                const kodeKurikulum = $(this).data('kode_kurikulum');
                const tahunKurikulum = $(this).data('tahun_kurikulum');
                const isAktif = Number($(this).data('is_aktif'));

                $('#editKurikulumIndukForm').attr('action', "{{ route('kurikulum-induk.update', ':id') }}".replace(':id', id));
                $('#edit_id_prodi').val(idProdi);
                $('#edit_id_jenis_kurikulum').val(idJenisKurikulum);
                $('#edit_tahun_kurikulum').val(tahunKurikulum);
                $('#edit_kode_kurikulum').val(kodeKurikulum);
                $('#edit_is_aktif').prop('checked', isAktif === 1);
                editModal.show();
            });

            $(document).on('click', '.delete-induk-btn', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus tahun kurikulum?',
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
                        url: "{{ route('kurikulum-induk.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.message || 'Tahun kurikulum berhasil dihapus.', 'success')
                                .then(() => window.location.reload());
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'Gagal menghapus tahun kurikulum.';
                            Swal.fire('Gagal', message, 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
