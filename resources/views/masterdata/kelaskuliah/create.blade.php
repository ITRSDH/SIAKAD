@extends('layouts.index')
@section('title', 'Tambah Kelas Kuliah')

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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Kelas Kuliah</h3>
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
                    <a href="#">Kelas Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Kelas Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Kelas Kuliah</h4>

                            <div class="d-flex gap-2">

                                <a href="{{ route('kelas-kuliah.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>

                                <button type="submit" form="form-kelas-kuliah" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="form-kelas-kuliah" action="{{ route('kelas-kuliah.store') }}" method="POST">
                            @csrf
                            <!-- Form fields will be added here -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}">
                                                    {{ $p['prodi'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Semester</label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($semester as $s)
                                                <option value="{{ $s['id'] }}">
                                                    {{ $s['semester'] }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_mata_kuliah" class="form-label">Mata Kuliah</label>
                                        <select class="form-select select2" id="id_kurikulum_mata_kuliah"
                                            name="id_kurikulum_mata_kuliah" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($kurikulum_matakuliah as $mk)
                                                <option value="{{ $mk['id'] }}">
                                                    {{ $mk['matakuliah'] }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lingkup" class="form-label">Lingkup</label>
                                        <select class="form-select select2" id="lingkup" name="lingkup">
                                            <option value="" disabled selected></option>
                                            <option value="internal">Internal</option>
                                            <option value="eksternal">Eksternal</option>
                                            <option value="campuran">Campuran</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas_peserta" class="form-label">Kapasitas Peserta</label>
                                        <input type="number" class="form-control" id="kapasitas_peserta"
                                            name="kapasitas_peserta" min="1" placeholder="Mis. 40">
                                        <small class="text-muted">Kuota kelas ini akan dipakai untuk validasi kapasitas dan KRS.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mode_kuliah" class="form-label">Mode Kuliah</label>
                                        <select class="form-select select2" id="mode_kuliah" name="mode_kuliah">
                                            <option value="" disabled selected></option>
                                            <option value="online">Online</option>
                                            <option value="offline">Offline</option>
                                            <option value="campuran">Campuran</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai_efektif" class="form-label">Tanggal Mulai Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_mulai_efektif"
                                            name="tanggal_mulai_efektif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_akhir_efektif" class="form-label">Tanggal Akhir
                                            Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_akhir_efektif"
                                            name="tanggal_akhir_efektif">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
