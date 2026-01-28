@extends('layouts.index')
@section('title', 'Mahasiswa Management')

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
            <h3 class="fw-bold mb-3">Mahasiswa Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('mahasiswa.baru.index') }}">Mahasiswa Baru</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Data Mahasiswa</h3>
                        <button id="syncMahasiswaBtn" class="btn btn-primary btn-sm"><i class="fas fa-sync"></i> Sync
                            Mahasiswa</button>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="table-responsive">
                            <table id="mahasiswa-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Prodi</th>
                                        <th>Aksi</th>
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

    {{-- Modal Sync --}}
    <div class="modal fade" id="syncModal" tabindex="-1" aria-labelledby="syncModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="syncForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="syncModalLabel">Sync Mahasiswa Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_periode_pendaftaran" class="form-label">Pilih Periode Pendaftaran</label>
                            <select id="id_periode_pendaftaran" name="id_periode_pendaftaran" class="form-control" required>
                                <option value="">-- Memuat --</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync"></i> Sync
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">
                <form id="mahasiswaForm">
                    @csrf
                    <input type="hidden" id="mahasiswaId">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Edit Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nama Mahasiswa</label>
                                <input type="text" id="nama_mahasiswa" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>NIM</label>
                                <input type="text" id="nim" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Program Studi</label>
                                <select id="id_prodi" class="form-control" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Jenis Kelamin</label>
                                <select id="jenis_kelamin" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Alamat</label>
                                <textarea id="alamat" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>No HP</label>
                                <input type="text" id="no_hp" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Asal Sekolah</label>
                                <input type="text" id="asal_sekolah" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Nama Orang Tua</label>
                                <input type="text" id="nama_orang_tua" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>No HP Orang Tua</label>
                                <input type="text" id="no_hp_orang_tua" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select id="status" class="form-control" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="DO">Drop Out</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="PMB">PMB</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Angkatan</label>
                                <input type="number" id="angkatan" class="form-control" min="1990"
                                    max="{{ date('Y') + 10 }}" required>
                            </div>

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
@endsection

@push('scripts-custom')
    <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            const modal = new bootstrap.Modal('#mahasiswaModal');
            const syncModal = new bootstrap.Modal('#syncModal');
            const mahasiswa = @json($mahasiswa ?? []);

            const table = $('#mahasiswa-table').DataTable({
                data: mahasiswa,
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'nama_mahasiswa'
                    },
                    {
                        data: 'nim'
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
                </div>`
                    }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                },
                drawCallback: () => $('#tableLoader').addClass('hidden')
            });

            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);

            // Sync
            $('#syncMahasiswaBtn').click(() => {
                $('#syncForm')[0].reset();
                $('#id_periode_pendaftaran').html('<option value="">-- Memuat --</option>');
                syncModal.show();

                $.get(`{{ config('api.pmb_url') }}/periode-pendaftaran`)
                    .done(res => {
                        if (res.success && res.data) {
                            let options = '<option value="">-- Pilih Periode --</option>';
                            res.data.forEach(item => {
                                options += `<option value="${item.id}">${item.nama_periode}</option>`;
                            });
                            $('#id_periode_pendaftaran').html(options);
                        } else {
                            $('#id_periode_pendaftaran').html('<option value="">-- Tidak ada data --</option>');
                        }
                    })
                    .fail(() => {
                        $('#id_periode_pendaftaran').html('<option value="">-- Gagal memuat --</option>');
                    });
            });

            $('#syncForm').on('submit', function(e) {
                e.preventDefault();
                const idPeriode = $('#id_periode_pendaftaran').val();

                if (!idPeriode) {
                    Swal.fire('Peringatan', 'Pilih periode pendaftaran terlebih dahulu', 'warning');
                    return;
                }

                Swal.showLoading();

                $.ajax({
                    url: "{{ route('mahasiswa.baru.sync') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_periode_pendaftaran: idPeriode
                    },
                    success: res => {
                        Swal.close();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message ?? 'Sync berhasil',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            syncModal.hide();
                            location.reload();
                        } else {
                            Swal.fire('Gagal', res.message ?? 'Sync gagal', 'error');
                        }
                    },
                    error: err => {
                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan saat sync', 'error');
                    }
                });
            });

            // Simpan / Update (hanya untuk edit, karena create diganti sync)
            $('#mahasiswaForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#mahasiswaId').val();
                if (!id) {
                    Swal.fire('Info', 'Gunakan tombol Sync untuk menambah mahasiswa baru', 'info');
                    return;
                }

                const url = "{{ route('mahasiswa.baru.update', ':id') }}".replace(':id', id);
                const method = 'PUT';

                $.ajax({
                    url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama_mahasiswa: $('#nama_mahasiswa').val(),
                        nim: $('#nim').val(),
                        id_prodi: $('#id_prodi').val(),
                        jenis_kelamin: $('#jenis_kelamin').val(),
                        tanggal_lahir: $('#tanggal_lahir').val(),
                        alamat: $('#alamat').val(),
                        no_hp: $('#no_hp').val(),
                        asal_sekolah: $('#asal_sekolah').val(),
                        nama_orang_tua: $('#nama_orang_tua').val(),
                        no_hp_orang_tua: $('#no_hp_orang_tua').val(),
                        status: $('#status').val(),
                        angkatan: $('#angkatan').val()
                    },
                    beforeSend: () => Swal.showLoading(),
                    success: res => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message ?? 'Data disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        modal.hide();
                        location.reload();
                    },
                    error: err => {
                        console.log(err);

                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.baru.show', ':id') }}".replace(':id', id);

                $.get(url, res => {
                    const m = res.data ?? res;
                    $('#mahasiswaId').val(m.id);
                    $('#nama_mahasiswa').val(m.nama_mahasiswa);
                    $('#nim').val(m.nim);
                    $('#id_prodi').val(m.id_prodi);
                    $('#jenis_kelamin').val(m.jenis_kelamin);
                    $('#tanggal_lahir').val(m.tanggal_lahir?.split('T')[0] ?? '');
                    $('#alamat').val(m.alamat);
                    $('#no_hp').val(m.no_hp);
                    $('#asal_sekolah').val(m.asal_sekolah);
                    $('#nama_orang_tua').val(m.nama_orang_tua);
                    $('#no_hp_orang_tua').val(m.no_hp_orang_tua);
                    $('#status').val(m.status);
                    $('#angkatan').val(m.angkatan);

                    $('#modalTitle').text('Edit Mahasiswa');
                    modal.show();

                }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data', 'error'));
            });

            // Hapus
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.baru.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Hapus mahasiswa ini?',
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
