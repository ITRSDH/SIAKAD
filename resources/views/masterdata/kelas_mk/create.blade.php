@extends('layouts.index')
@section('title', 'Tambah Kelas Mata Kuliah')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Kelas Mata Kuliah</h3>
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
                    <a href="{{ route('kelas-mk.index') }}">Kelas Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Kelas Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus me-2 text-primary"></i>Tambah Kelas Mata Kuliah
                        </h3>
                    </div>
                    <div class="card-body">

                        <!-- Filter Prodi -->
                        @if (!request()->has('nama_prodi'))
                            <div class="alert alert-info mb-4">
                                <h5 class="mb-2"><i class="fas fa-filter me-2"></i>Filter Program Studi</h5>
                                <form method="GET" action="{{ route('kelas-mk.create') }}" id="filterForm">
                                    <div class="row mb-3">
                                        <label for="prodi_filter" class="col-sm-2 col-form-label">
                                            Program Studi <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="prodi_filter" name="nama_prodi" required>
                                                <option value="">-- Pilih Program Studi --</option>
                                                @if (isset($prodiList) && !empty($prodiList))
                                                    @foreach ($prodiList as $prodi)
                                                        <option value="{{ $prodi['nama_prodi'] }}"
                                                            {{ request('nama_prodi') == $prodi['nama_prodi'] ? 'selected' : '' }}>
                                                            {{ $prodi['nama_prodi'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small class="form-text text-muted">
                                                Pilih program studi untuk menampilkan data yang relevan
                                            </small>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search me-1"></i>Terapkan Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Form Utama -->
                        @if (request()->has('nama_prodi') && isset($dropdownData))
                            <!-- Informasi Tahun Akademik dan Semester -->
                            @if (isset($tahun_akademik) && $tahun_akademik)
                                <div class="alert alert-info mb-4">
                                    <h5 class="mb-2"><i class="fas fa-calendar-alt me-2"></i>Tahun Akademik dan Semester
                                        Aktif</h5>
                                    <p class="mb-1">
                                        <strong>Tahun Akademik:</strong>
                                        {{ $tahun_akademik['tahun_akademik'] ?? 'Tidak ditemukan' }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Semester:</strong>
                                        {{ $semester['nama_semester'] ?? 'Tidak ditemukan' }}
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-warning mb-4">
                                    <h5 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Peringatan</h5>
                                    <p class="mb-0">Tidak ada tahun akademik atau semester aktif.</p>
                                </div>
                            @endif

                            <!-- Panduan -->
                            <div class="alert alert-light border mb-4">
                                <h6 class="text-muted mb-2"><i class="fas fa-info-circle me-2"></i>Panduan Pengisian Form
                                </h6>
                                <ol class="mb-0">
                                    <li>Pastikan <strong>Tahun Akademik dan Semester</strong> sesuai dengan periode aktif
                                    </li>
                                    <li>Data yang ditampilkan sudah difilter berdasarkan <strong>Program Studi</strong> yang
                                        dipilih</li>
                                    <li>Isi <strong>Kuota Kelas</strong> sesuai kapasitas maksimal</li>
                                    <li><strong>Kode Kelas MK</strong> akan digunakan sebagai identitas unik</li>
                                    <li>Pilih <strong>Jenis Kelas</strong> (Reguler, Karyawan, dll)</li>
                                </ol>
                            </div>

                            <!-- Ganti Prodi -->
                            <div class="alert alert-warning mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Program Studi Saat Ini:</strong>
                                        {{ $dropdownData['prodi'][0]['nama_prodi'] ?? 'Tidak diketahui' }}
                                    </span>
                                    <a href="{{ route('kelas-mk.create') }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-sync-alt me-1"></i>Ganti Prodi
                                    </a>
                                </div>
                            </div>

                            <form action="{{ route('kelas-mk.store') }}" method="POST" id="kelasMkForm">
                                @csrf

                                <div class="row mb-3">
                                    <label for="kode_kelas_mk" class="col-sm-2 col-form-label">
                                        Kode Kelas MK <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('kode_kelas_mk') is-invalid @enderror"
                                            id="kode_kelas_mk" name="kode_kelas_mk" value="{{ old('kode_kelas_mk') }}"
                                            placeholder="Contoh: IF101A-Ganjil2025/2026">
                                        <small class="form-text text-muted">
                                            Gunakan format: [Kode Prodi][Kode MK][Kelas] (Misal: IF101A)
                                        </small>
                                        <span id="kode_kelas_mk_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="kuota" class="col-sm-2 col-form-label">
                                        Kuota <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control @error('kuota') is-invalid @enderror"
                                            id="kuota" name="kuota" value="{{ old('kuota') }}" min="1"
                                            max="100" placeholder="Masukkan jumlah kuota mahasiswa">
                                        <small class="form-text text-muted">
                                            Jumlah maksimum mahasiswa yang dapat mendaftar di kelas ini
                                        </small>
                                        <span id="kuota_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">
                                        Semester <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $semester['nama_semester'] ?? 'Tidak ditemukan' }}" readonly>
                                        <input type="hidden" name="id_semester" value="{{ $semester['id'] ?? '' }}">
                                        <small class="form-text text-muted">
                                            Semester ini sedang aktif dan digunakan untuk kelas ini
                                        </small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">
                                        Program Studi <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $dropdownData['prodi'][0]['nama_prodi'] ?? 'Tidak ditemukan' }}"
                                            readonly>
                                        <input type="hidden" name="id_prodi"
                                            value="{{ $dropdownData['prodi'][0]['id'] ?? '' }}">
                                        <small class="form-text text-muted">
                                            Program studi sudah difilter dan tidak dapat diubah pada form ini
                                        </small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">
                                        Kurikulum <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $dropdownData['prodi'][0]['kurikulum'][0]['nama_kurikulum'] ?? 'Tidak ditemukan' }}"
                                            readonly>
                                        <input type="hidden" name="id_kurikulum"
                                            value="{{ $dropdownData['prodi'][0]['kurikulum'][0]['id'] ?? '' }}">
                                        <small class="form-text text-muted">
                                            Kurikulum otomatis diambil dari data terbaru
                                        </small>
                                    </div>
                                </div>

                                <!-- Filter Semester untuk MK -->
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <label class="col-form-label pt-0">Filter Semester</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="filter_semester" name="semester_filter">
                                            <option value="" disabled selected>-- Pilih Semester --</option>
                                            @php
                                                $allSemesters = collect();
                                                foreach ($dropdownData['prodi'][0]['kurikulum'] ?? [] as $kurikulum) {
                                                    if (isset($kurikulum['mata_kuliah_by_semester'])) {
                                                        foreach ($kurikulum['mata_kuliah_by_semester'] as $mks) {
                                                            $allSemesters->push($mks['semester']);
                                                        }
                                                    }
                                                }
                                                $uniqueSemesters = $allSemesters->unique()->sort();
                                            @endphp
                                            @foreach ($uniqueSemesters as $sem)
                                                <option value="{{ $sem }}">Semester {{ $sem }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted mt-1">
                                            Pilih semester untuk menampilkan mata kuliah tertentu
                                        </small>
                                    </div>
                                </div>

                                <!-- Mata Kuliah Select (Tunggal) -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">
                                        Mata Kuliah <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="id_mk" id="mata-kuliah-select" class="form-select" disabled>
                                            <option value="">Pilih semester terlebih dahulu</option>
                                        </select>

                                        <small class="form-text text-muted">
                                            Pilih satu mata kuliah berdasarkan semester
                                        </small>
                                        <span id="id_mk_error" class="text-danger error-text"></span>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="id_kelas_pararel" class="col-sm-2 col-form-label">
                                        Kelas Pararel <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-select @error('id_kelas_pararel') is-invalid @enderror"
                                            id="id_kelas_pararel" name="id_kelas_pararel" required>
                                            <option value="" disabled selected>Pilih Kelas Pararel</option>
                                            @if (isset($dropdownData['prodi'][0]['kelas_pararel']) && !empty($dropdownData['prodi'][0]['kelas_pararel']))
                                                @foreach ($dropdownData['prodi'][0]['kelas_pararel'] as $kelasPararel)
                                                    <option value="{{ $kelasPararel['id'] }}"
                                                        {{ old('id_kelas_pararel') == $kelasPararel['id'] ? 'selected' : '' }}>
                                                        {{ $kelasPararel['nama_kelas'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="form-text text-muted">
                                            Kelas pararel digunakan untuk mengelompokkan kelas-kelas sejenis
                                        </small>
                                        <span id="id_kelas_pararel_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="id_jenis_kelas" class="col-sm-2 col-form-label">
                                        Jenis Kelas <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-select @error('id_jenis_kelas') is-invalid @enderror"
                                            id="id_jenis_kelas" name="id_jenis_kelas" required>
                                            <option value="" disabled selected>Pilih Jenis Kelas</option>
                                            @if (isset($dropdownData['jenis_kelas']) && !empty($dropdownData['jenis_kelas']))
                                                @foreach ($dropdownData['jenis_kelas'] as $jk)
                                                    <option value="{{ $jk['id'] }}"
                                                        {{ old('id_jenis_kelas') == $jk['id'] ? 'selected' : '' }}>
                                                        {{ $jk['nama_kelas'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="form-text text-muted">
                                            Jenis kelas seperti Reguler, Karyawan, atau Khusus
                                        </small>
                                        <span id="id_jenis_kelas_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <hr class="mt-4 mb-3">

                                <div class="row mb-0">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary me-2" id="saveBtn">
                                            <i class="fas fa-save me-1"></i>Simpan Kelas
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetBtn">
                                            <i class="fas fa-redo me-1"></i>Reset Form
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (request()->has('nama_prodi') && isset($dropdownData))

                const dropdownData = @json($dropdownData);

                if (!dropdownData.prodi?.length) return;

                const prodi = dropdownData.prodi[0];
                if (!prodi.kurikulum?.length) return;

                const kurikulum = prodi.kurikulum[0];
                const semesterFilter = document.getElementById('filter_semester');
                const mkSelect = document.getElementById('mata-kuliah-select');
                const form = document.getElementById('kelasMkForm');
                const resetBtn = document.getElementById('resetBtn');

                const mataKuliahBySemester = kurikulum.mata_kuliah_by_semester || [];

                // Filter semester
                semesterFilter.addEventListener('change', function() {
                    const selectedSemester = parseInt(this.value);

                    mkSelect.innerHTML = '';
                    mkSelect.disabled = true;

                    if (isNaN(selectedSemester)) {
                        mkSelect.innerHTML = '<option value="">Pilih semester terlebih dahulu</option>';
                        return;
                    }

                    const semesterData = mataKuliahBySemester.find(
                        sem => sem.semester === selectedSemester
                    );

                    if (semesterData?.mata_kuliah?.length) {
                        displayMataKuliah(semesterData.mata_kuliah);
                    } else {
                        mkSelect.innerHTML = '<option value="">Tidak ada mata kuliah</option>';
                    }
                });

                // Render option
                function displayMataKuliah(mataKuliahList) {
                    mkSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
                    mkSelect.disabled = false;

                    mataKuliahList.forEach(mk => {
                        const option = document.createElement('option');
                        option.value = mk.id;
                        option.textContent = `[${mk.kode_mk}] ${mk.nama_mk} (SKS: ${mk.sks})`;
                        mkSelect.appendChild(option);
                    });
                }

                // Validasi submit
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!mkSelect.value) {
                        document.getElementById('id_mk_error').textContent =
                            'Harap pilih satu mata kuliah.';
                        mkSelect.classList.add('is-invalid');
                        return;
                    }

                    this.submit();
                });

                // Reset
                resetBtn.addEventListener('click', function() {
                    form.reset();
                    mkSelect.innerHTML = '<option value="">Pilih semester terlebih dahulu</option>';
                    mkSelect.disabled = true;
                    mkSelect.classList.remove('is-invalid');

                    document.querySelectorAll('.error-text')
                        .forEach(el => el.textContent = '');
                });
            @endif
        });
    </script>
@endpush
