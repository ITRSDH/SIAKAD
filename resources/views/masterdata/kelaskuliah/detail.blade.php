@extends('layouts.index')
@section('title', 'Detail Kelas Kuliah')

@push('styles-custom')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .modal-xxl {
            max-width: 95% !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Kelas Kuliah</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Kelas Kuliah</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Detail Kelas Kuliah</a></li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Kelas Kuliah</h4>

                            <div class="d-flex gap-2">
                                <a href="{{ route('kelas-kuliah.create') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>

                                <button type="submit" form="form-kelas-kuliah" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-pen me-1"></i> Ubah
                                </button>

                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $kelaskuliah['id'] }}" data-nama="{{ $kelaskuliah['nama_kelas'] }}">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>

                                <a href="{{ route('kelas-kuliah.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-bars me-1"></i> Daftar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="form-kelas-kuliah" action="{{ route('kelas-kuliah.update', $kelaskuliah['id']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- PRODI -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value=""></option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}"
                                                    {{ old('id_prodi', $kelaskuliah['id_prodi'] ?? '') == $p['id'] ? 'selected' : '' }}>
                                                    {{ $p['prodi'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- SEMESTER -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Semester</label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value=""></option>
                                            @foreach ($semester as $s)
                                                <option value="{{ $s['id'] }}"
                                                    {{ old('id_semester', $kelaskuliah['id_semester'] ?? '') == $s['id'] ? 'selected' : '' }}>
                                                    {{ $s['semester'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- MATA KULIAH -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_mata_kuliah" class="form-label">Mata Kuliah</label>
                                        <select class="form-select select2" id="id_kurikulum_mata_kuliah"
                                            name="id_kurikulum_mata_kuliah" required>
                                            <option value=""></option>
                                            @foreach ($kurikulum_matakuliah as $mk)
                                                <option value="{{ $mk['id'] }}"
                                                    {{ old('id_kurikulum_mata_kuliah', $kelaskuliah['id_kurikulum_mata_kuliah'] ?? '') == $mk['id'] ? 'selected' : '' }}>
                                                    {{ $mk['matakuliah'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- NAMA KELAS -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                            value="{{ old('nama_kelas', $kelaskuliah['nama_kelas'] ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks" class="form-label">SKS Mata Kuliah</label>
                                        <input type="number" class="form-control" id="sks" name="sks"
                                            placeholder="0" readonly value="{{ $kelaskuliah['mata_kuliah']['sks'] }}">
                                        <small class="text-muted">(SKS Tatap Muka + SKS Praktikum + SKS Praktik Lapangan +
                                            SKS Simulasi)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_tatap_muka" class="form-label">Bobot SKS Tatap Muka</label>
                                        <input type="number" class="form-control" id="sks_tatap_muka" name="sks_tatap_muka"
                                            placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_tatap_muka'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktikum" class="form-label">Bobot SKS Praktikum</label>
                                        <input type="number" class="form-control" id="sks_praktikum"
                                            name="sks_praktikum" placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_praktikum'] ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktik_lapangan" class="form-label">Bobot SKS Praktik
                                            Lapangan</label>
                                        <input type="number" class="form-control" id="sks_praktik_lapangan"
                                            name="sks_praktik_lapangan" placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_praktik_lapangan'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bahasan" class="form-label">Bahasan</label>
                                        <input type="text" class="form-control" id="bahasan" name="bahasan"
                                            value="{{ old('bahasan', $kelaskuliah['bahasan'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_simulasi" class="form-label">Bobot SKS Simulasi</label>
                                        <input type="number" class="form-control" id="sks_simulasi" name="sks_simulasi"
                                            placeholder="0" min="0" readonly
                                            value="{{ $kelaskuliah['mata_kuliah']['sks_simulasi'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- LINGKUP -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lingkup" class="form-label">Lingkup</label>
                                        <select class="form-select select2" id="lingkup" name="lingkup">
                                            <option value=""></option>
                                            <option value="internal"
                                                {{ $kelaskuliah['lingkup'] == 'internal' ? 'selected' : '' }}>Internal
                                            </option>
                                            <option value="eksternal"
                                                {{ $kelaskuliah['lingkup'] == 'eksternal' ? 'selected' : '' }}>Eksternal
                                            </option>
                                            <option value="campuran"
                                                {{ $kelaskuliah['lingkup'] == 'campuran' ? 'selected' : '' }}>Campuran
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas_peserta" class="form-label">Kapasitas Peserta</label>
                                        <input type="number" class="form-control" id="kapasitas_peserta"
                                            name="kapasitas_peserta" min="1"
                                            value="{{ old('kapasitas_peserta', $kelaskuliah['kapasitas_peserta'] ?? '') }}">
                                        <small class="text-muted">Digunakan sebagai kuota kelas dan pembanding kapasitas ruang.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mode_kuliah" class="form-label">Mode Kuliah</label>
                                        <select class="form-select select2" id="mode_kuliah" name="mode_kuliah">
                                            <option value=""></option>
                                            <option value="online"
                                                {{ $kelaskuliah['mode_kuliah'] == 'online' ? 'selected' : '' }}>Online
                                            </option>
                                            <option value="offline"
                                                {{ $kelaskuliah['mode_kuliah'] == 'offline' ? 'selected' : '' }}>Offline
                                            </option>
                                            <option value="campuran"
                                                {{ $kelaskuliah['mode_kuliah'] == 'campuran' ? 'selected' : '' }}>Campuran
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>

                            <div class="row">
                                <!-- TANGGAL MULAI -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai_efektif" class="form-label">Tanggal Mulai
                                            Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_mulai_efektif"
                                            name="tanggal_mulai_efektif"
                                            value="{{ old('tanggal_mulai_efektif', $kelaskuliah['tanggal_mulai_efektif']) }}">
                                    </div>
                                </div>

                                <!-- TANGGAL AKHIR -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_akhir_efektif" class="form-label">Tanggal Akhir
                                            Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_akhir_efektif"
                                            name="tanggal_akhir_efektif"
                                            value="{{ old('tanggal_akhir_efektif', $kelaskuliah['tanggal_akhir_efektif']) }}">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Kapasitas Peserta</div>
                                <div class="fs-4 fw-semibold">{{ $kelaskuliah['kapasitas_peserta'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Mode Kuliah</div>
                                <div class="fs-4 fw-semibold text-capitalize">{{ $kelaskuliah['mode_kuliah'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="text-muted small">Lingkup</div>
                                <div class="fs-4 fw-semibold text-capitalize">{{ $kelaskuliah['lingkup'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="dosen-pengajar-tab" data-bs-toggle="pill"
                                    href="#dosen-pengajar" role="tab" aria-controls="dosen-pengajar"
                                    aria-selected="true">Dosen Pengajar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mahasiswa-tab" data-bs-toggle="pill" href="#mahasiswa"
                                    role="tab" aria-controls="mahasiswa" aria-selected="false">Perserta
                                    KRS/Mahasiswa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="jadwal-tab" data-bs-toggle="pill" href="#jadwal" role="tab"
                                    aria-controls="jadwal" aria-selected="false">Jadwal</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3 mb-3" id="line-tabContent">
                            <div class="tab-pane fade show active" id="dosen-pengajar">
                                <div id="content-dosen">
                                    <div class="text-center p-3">Loading...</div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mahasiswa">
                                <div id="content-mahasiswa">
                                    <div class="text-center p-3">Loading...</div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="jadwal">
                                <div id="content-jadwal">
                                    <div class="text-center p-3">Loading...</div>
                                </div>
                            </div>
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

    <script>
        $(document).ready(function() {
            $('.select2').trigger('change');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus Kelas Kuliah "${nama}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('kelas-kuliah.destroy', '__ID__') }}`.replace(
                            '__ID__', id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('kelas-kuliah.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
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
    </script>

    @include('masterdata.kelaskuliah.scripts.dosen-pengajar-script')
    @include('masterdata.kelaskuliah.scripts.mahasiswa-script')
    @include('masterdata.kelaskuliah.scripts.jadwal-script')
@endpush
