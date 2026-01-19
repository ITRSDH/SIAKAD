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
                    <a href="{{ route('kelas-mk.index') }}">List Kelas Mata Kuliah</a>
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

                        <!-- Informasi Tahun Akademik dan Semester Aktif -->
                        <div class="alert alert-info mb-4">
                            <h5 class="mb-2"><i class="fas fa-calendar-alt me-2"></i>Tahun Akademik dan Semester Aktif
                            </h5>
                            <p class="mb-1">
                                <strong>Tahun Akademik:</strong>
                                {{ $tahun_akademik ? $tahun_akademik['tahun_akademik'] : 'Tidak ditemukan' }}
                            </p>
                            <p class="mb-0">
                                <strong>Semester:</strong>
                                {{ $semester ? $semester['nama_semester'] : 'Tidak ditemukan' }}
                            </p>
                        </div>

                        <!-- Panduan Pengisian Form -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="text-muted mb-2"><i class="fas fa-info-circle me-2"></i>Panduan Pengisian Form</h6>
                            <ol class="mb-0">
                                <li>Pastikan <strong>Tahun Akademik dan Semester</strong> sesuai dengan periode aktif</li>
                                <li>Pilih <strong>Program Studi</strong> terlebih dahulu</li>
                                <li>Isi <strong>Kuota Kelas</strong> sesuai kapasitas maksimal</li>
                                <li><strong>Kode Kelas MK</strong> akan digunakan sebagai identitas unik</li>
                                <li>Pilih <strong>Jenis Kelas</strong> (Reguler, Karyawan, dll)</li>
                            </ol>
                        </div>

                        <form action="{{ route('kelas-mk.store') }}" method="POST">
                            @csrf

                            <div class="form-group row mb-3">
                                <label for="kode_kelas_mk" class="col-sm-2 col-form-label">
                                    Kode Kelas Mata Kuliah <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('kode_kelas_mk') is-invalid @enderror"
                                        id="kode_kelas_mk" name="kode_kelas_mk" value="{{ old('kode_kelas_mk') }}"
                                        placeholder="Contoh: IF101A-Ganjil2025/2026">
                                    <small class="form-text text-muted">
                                        Gunakan format: [Kode Prodi][Kode MK][Kelas] (Misal: IF101A)
                                    </small>
                                    <span id="kode_kelas_mk_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
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

                            <div class="form-group row mb-3">
                                <label for="semester_nama" class="col-sm-2 col-form-label">
                                    Semester <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <!-- Input yang terlihat user: nama semester -->
                                    <input type="text" class="form-control bg-light" id="semester_nama"
                                        name="semester_nama"
                                        value="{{ $semester ? $semester['nama_semester'] : 'Tidak ditemukan' }}"
                                        placeholder="Semester Aktif" readonly disabled>

                                    <!-- Hidden input untuk mengirim ID semester ke server -->
                                    <input type="hidden" name="id_semester" value="{{ $semester ? $semester['id'] : '' }}">

                                    <small class="form-text text-muted">
                                        Semester ini sedang aktif dan digunakan untuk kelas ini
                                    </small>
                                </div>
                            </div>

                            <!-- Prodi Dropdown -->
                            <div class="form-group row mb-3">
                                <label for="id_prodi" class="col-sm-2 col-form-label">
                                    Program Studi <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('id_prodi') is-invalid @enderror" id="id_prodi"
                                        name="id_prodi">
                                        <option value="" disabled selected>Pilih Program Studi</option>
                                        @foreach ($dropdownData['prodi'] as $prodi)
                                            <option value="{{ $prodi['id'] }}"
                                                {{ old('id_prodi') == $prodi['id'] ? 'selected' : '' }}>
                                                {{ $prodi['nama_prodi'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Pilih program studi tempat kelas mata kuliah ini diselenggarakan
                                    </small>
                                    <span id="id_prodi_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <!-- Kurikulum Dropdown -->
                            <div class="form-group row mb-3">
                                <label for="id_kurikulum" class="col-sm-2 col-form-label">
                                    Kurikulum <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('id_kurikulum') is-invalid @enderror"
                                        id="id_kurikulum" name="id_kurikulum" disabled>
                                        <option value="" disabled selected>Pilih Kurikulum</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Kurikulum otomatis muncul setelah memilih program studi
                                    </small>
                                    <span id="id_kurikulum_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <!-- Mata Kuliah Dropdown -->
                            <div class="form-group row mb-3">
                                <label for="id_mk" class="col-sm-2 col-form-label">
                                    Mata Kuliah <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('id_mk') is-invalid @enderror" id="id_mk"
                                        name="id_mk" disabled>
                                        <option value="" disabled selected>Pilih Mata Kuliah</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Pilih mata kuliah berdasarkan kurikulum yang dipilih
                                    </small>
                                    <span id="id_mk_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <!-- Kelas Pararel Dropdown -->
                            <div class="form-group row mb-3">
                                <label for="id_kelas_pararel" class="col-sm-2 col-form-label">
                                    Kelas Pararel <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('id_kelas_pararel') is-invalid @enderror"
                                        id="id_kelas_pararel" name="id_kelas_pararel" disabled>
                                        <option value="" disabled selected>Pilih Kelas Pararel</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Kelas pararel digunakan untuk mengelompokkan kelas-kelas sejenis
                                    </small>
                                    <span id="id_kelas_pararel_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="id_jenis_kelas" class="col-sm-2 col-form-label">
                                    Jenis Kelas <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('id_jenis_kelas') is-invalid @enderror"
                                        id="id_jenis_kelas" name="id_jenis_kelas">
                                        <option value="" disabled selected>Pilih Jenis Kelas</option>
                                        @foreach ($dropdownData['jenis_kelas'] as $jk)
                                            <option value="{{ $jk['id'] }}"
                                                {{ old('id_jenis_kelas') == $jk['id'] ? 'selected' : '' }}>
                                                {{ $jk['nama_kelas'] }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Jenis kelas seperti Reguler, Karyawan, atau Khusus
                                    </small>
                                    <span id="id_jenis_kelas_error" class="text-danger error-text"></span>
                                </div>
                            </div>

                            <hr class="mt-4 mb-3">

                            <div class="form-group row mb-0">
                                <div class="col-sm-12 text-end">
                                    <button type="submit" class="btn btn-primary me-2" id="saveBtn">
                                        <i class="fas fa-save me-1"></i>Simpan Kelas
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetBtn">
                                        <i class="fas fa-redo me-1"></i>Reset Form
                                    </button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prodiSelect = document.getElementById('id_prodi');
            const kurikulumSelect = document.getElementById('id_kurikulum');
            const mataKuliahSelect = document.getElementById('id_mk');
            const kelasPararelSelect = document.getElementById('id_kelas_pararel');
            const resetBtn = document.getElementById('resetBtn');

            // Ambil data dari blade template
            const dropdownData = @json($dropdownData);

            // Event listener untuk prodi
            prodiSelect.addEventListener('change', function() {
                const selectedProdiId = this.value;

                // Reset dan disable dropdown berikutnya
                resetDropdown(kurikulumSelect, 'Pilih Kurikulum');
                resetDropdown(mataKuliahSelect, 'Pilih Mata Kuliah');

                // Untuk kelas pararel, kita isi saat prodi dipilih
                if (selectedProdiId) {
                    // Cari prodi yang dipilih
                    const selectedProdi = dropdownData.prodi.find(p => p.id == selectedProdiId);

                    // Reset dropdown kelas pararel
                    resetDropdown(kelasPararelSelect, 'Pilih Kelas Pararel');

                    if (selectedProdi && selectedProdi.kelas_pararel.length > 0) {
                        // Aktifkan dropdown kelas pararel
                        kelasPararelSelect.disabled = false;

                        // Isi dropdown kelas pararel
                        selectedProdi.kelas_pararel.forEach(kp => {
                            const option = document.createElement('option');
                            option.value = kp.id;
                            option.textContent = kp.nama_kelas;
                            kelasPararelSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada kelas pararel';
                        kelasPararelSelect.appendChild(option);
                        kelasPararelSelect.disabled = true;
                    }
                } else {
                    // Jika prodi tidak dipilih, disable kelas pararel
                    resetDropdown(kelasPararelSelect, 'Pilih Kelas Pararel');
                }

                if (selectedProdiId) {
                    // Aktifkan dropdown kurikulum
                    kurikulumSelect.disabled = false;

                    // Cari prodi yang dipilih
                    const selectedProdi = dropdownData.prodi.find(p => p.id == selectedProdiId);

                    if (selectedProdi && selectedProdi.kurikulum.length > 0) {
                        // Isi dropdown kurikulum
                        selectedProdi.kurikulum.forEach(kurikulum => {
                            const option = document.createElement('option');
                            option.value = kurikulum.id;
                            option.textContent = kurikulum.nama_kurikulum;
                            kurikulumSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada kurikulum';
                        kurikulumSelect.appendChild(option);
                        kurikulumSelect.disabled = true;
                    }
                }
            });

            // Event listener untuk kurikulum
            kurikulumSelect.addEventListener('change', function() {
                const selectedProdiId = prodiSelect.value;
                const selectedKurikulumId = this.value;

                // Reset dan disable dropdown mata kuliah
                resetDropdown(mataKuliahSelect, 'Pilih Mata Kuliah');

                if (selectedKurikulumId) {
                    // Aktifkan dropdown mata kuliah
                    mataKuliahSelect.disabled = false;

                    // Cari prodi dan kurikulum yang dipilih
                    const selectedProdi = dropdownData.prodi.find(p => p.id == selectedProdiId);
                    if (selectedProdi) {
                        const selectedKurikulum = selectedProdi.kurikulum.find(k => k.id ==
                            selectedKurikulumId);

                        if (selectedKurikulum && selectedKurikulum.mata_kuliah.length > 0) {
                            // Isi dropdown mata kuliah
                            selectedKurikulum.mata_kuliah.forEach(mk => {
                                const option = document.createElement('option');
                                option.value = mk.id;
                                option.textContent = `${mk.kode_mk} - ${mk.nama_mk}`;
                                mataKuliahSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Tidak ada mata kuliah';
                            mataKuliahSelect.appendChild(option);
                            mataKuliahSelect.disabled = true;
                        }
                    }
                }
            });

            // Fungsi untuk mereset dropdown
            function resetDropdown(dropdown, placeholderText) {
                dropdown.innerHTML = `<option value="" disabled selected>${placeholderText}</option>`;
                dropdown.disabled = true;
            }

            // Reset button functionality
            resetBtn.addEventListener('click', function() {
                // Reset semua dropdown
                resetDropdown(kurikulumSelect, 'Pilih Kurikulum');
                resetDropdown(mataKuliahSelect, 'Pilih Mata Kuliah');
                resetDropdown(kelasPararelSelect, 'Pilih Kelas Pararel');

                // Reset prodi selection
                prodiSelect.selectedIndex = 0;

                // Reset form
                document.querySelector('form').reset();

                // Reset error messages
                document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            });
        });
    </script>
@endpush
