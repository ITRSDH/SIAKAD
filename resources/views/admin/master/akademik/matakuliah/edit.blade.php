@extends('admin.layouts.index')
@section('title', 'Edit Mata Kuliah')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Mata Kuliah</h3>
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
                    <a href="{{ route('matakuliah.index') }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('matakuliah.index') }}">List Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <!-- Gunakan $matakuliah['id'] disini -->
                    <a href="{{ route('matakuliah.edit', $matakuliah['id']) }}">Edit Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12"> <!-- Lebih lebar untuk formulir -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit me-2 text-primary"></i>Edit Mata Kuliah
                        </h3>
                        <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Gunakan $matakuliah['id'] disini -->
                        <form id="mataKuliahForm" method="POST"
                            action="{{ route('matakuliah.update', $matakuliah['id']) }}">
                            @csrf
                            @method('PUT')

                            <!-- Input hidden untuk id_kurikulum yang akan dikirim ke API -->
                            <input type="hidden" name="id_kurikulum" value="{{ $matakuliah['id_kurikulum'] }}">

                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <!-- Input Prodi (disabled) -->
                                    <div class="form-group mb-3">
                                        <label for="id_prodi_display" class="col-form-label">
                                            <i class="fas fa-graduation-cap me-1 text-info"></i>Program Studi
                                        </label>
                                        <select name="id_prodi_display" id="id_prodi_display"
                                            class="form-control form-select" disabled required>
                                            <option value="{{ $matakuliah['kurikulum']['prodi']['id'] ?? '' }}" selected>
                                                {{ $matakuliah['kurikulum']['prodi']['nama_prodi'] ?? 'Data Tidak Ditemukan' }}
                                            </option>
                                        </select>
                                        <small class="form-text text-muted">Nilai tidak dapat diubah.</small>
                                    </div>

                                    <!-- Input Kurikulum (disabled) -->
                                    <div class="form-group mb-3">
                                        <label for="id_kurikulum_display" class="col-form-label">
                                            <i class="fas fa-book me-1 text-info"></i>Kurikulum
                                        </label>
                                        <select name="id_kurikulum_display" id="id_kurikulum_display"
                                            class="form-control form-select" disabled required>
                                            <option value="{{ $matakuliah['id_kurikulum'] }}" selected>
                                                {{ $matakuliah['kurikulum']['nama_kurikulum'] ?? 'Data Tidak Ditemukan' }}
                                            </option>
                                        </select>
                                        <small class="form-text text-muted">Nilai tidak dapat diubah.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="kode_mk" class="col-form-label">
                                            <i class="fas fa-barcode me-1 text-info"></i>Kode Mata Kuliah <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('kode_mk') is-invalid @enderror"
                                            id="kode_mk" name="kode_mk" placeholder="Contoh: IF101"
                                            value="{{ old('kode_mk', $matakuliah['kode_mk']) }}" required>
                                        @error('kode_mk')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Gunakan kode unik untuk mata kuliah ini.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="nama_mk" class="col-form-label">
                                            <i class="fas fa-font me-1 text-info"></i>Nama Mata Kuliah <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('nama_mk') is-invalid @enderror"
                                            id="nama_mk" name="nama_mk" placeholder="Contoh: Algoritma dan Pemrograman"
                                            value="{{ old('nama_mk', $matakuliah['nama_mk']) }}" required>
                                        @error('nama_mk')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="jenis" class="col-form-label">
                                            <i class="fas fa-tags me-1 text-info"></i>Jenis <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="form-control form-select @error('jenis') is-invalid @enderror"
                                            id="jenis" name="jenis" required>
                                            <option value="">Pilih Jenis...</option>
                                            <option value="Wajib"
                                                {{ old('jenis', $matakuliah['jenis']) == 'Wajib' ? 'selected' : '' }}>Wajib
                                            </option>
                                            <option value="Pilihan"
                                                {{ old('jenis', $matakuliah['jenis']) == 'Pilihan' ? 'selected' : '' }}>
                                                Pilihan
                                            </option>
                                        </select>
                                        @error('jenis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sks" class="col-form-label">
                                            <i class="fas fa-weight-hanging me-1 text-info"></i>SKS <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('sks') is-invalid @enderror"
                                            id="sks" name="sks" placeholder="Jumlah SKS"
                                            value="{{ old('sks', $matakuliah['sks']) }}" required min="0"
                                            max="10">
                                        @error('sks')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Jumlah Satuan Kredit Semester (0-10).</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="semester_rekomendasi" class="col-form-label">
                                            <i class="fas fa-calendar-alt me-1 text-info"></i>Semester Rekomendasi <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control @error('semester_rekomendasi') is-invalid @enderror"
                                            id="semester_rekomendasi" name="semester_rekomendasi" placeholder="Contoh: 1"
                                            value="{{ old('semester_rekomendasi', $matakuliah['semester_rekomendasi']) }}"
                                            required min="1" max="14">
                                        @error('semester_rekomendasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Semester ideal untuk mengambil MK
                                            (1-14).</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="teori" class="col-form-label">
                                            <i class="fas fa-chalkboard-teacher me-1 text-info"></i>Teori (Jam) <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('teori') is-invalid @enderror"
                                            id="teori" name="teori" placeholder="Jam Teori"
                                            value="{{ old('teori', $matakuliah['teori']) }}" required min="0">
                                        @error('teori')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Durasi jam teori per minggu.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="praktikum" class="col-form-label">
                                            <i class="fas fa-flask me-1 text-info"></i>Praktikum (Jam) <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control @error('praktikum') is-invalid @enderror" id="praktikum"
                                            name="praktikum" placeholder="Jam Praktikum"
                                            value="{{ old('praktikum', $matakuliah['praktikum']) }}" required
                                            min="0">
                                        @error('praktikum')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Durasi jam praktikum per minggu.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="seminar" class="col-form-label">
                                            <i class="fas fa-users me-1 text-info"></i>Seminar (Jam) <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('seminar') is-invalid @enderror"
                                            id="seminar" name="seminar" placeholder="Jam Seminar"
                                            value="{{ old('seminar', $matakuliah['seminar']) }}" required min="0">
                                        @error('seminar')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Durasi jam seminar per minggu.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="praktek_klinik" class="col-form-label">
                                            <i class="fas fa-stethoscope me-1 text-info"></i>Praktek Klinik (Jam) <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control @error('praktek_klinik') is-invalid @enderror"
                                            id="praktek_klinik" name="praktek_klinik" placeholder="Jam Praktek Klinik"
                                            value="{{ old('praktek_klinik', $matakuliah['praktek_klinik']) }}" required
                                            min="0">
                                        @error('praktek_klinik')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Durasi jam praktek klinik per minggu.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0 mt-3">
                                <label for="deskripsi" class="col-form-label">
                                    <i class="fas fa-align-left me-1 text-info"></i>Deskripsi
                                </label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" style="height:100px" id="deskripsi"
                                    name="deskripsi" placeholder="Masukkan deskripsi singkat mata kuliah...">{{ old('deskripsi', $matakuliah['deskripsi']) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mt-4 d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> Perbarui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles-custom')
    <!-- Tambahkan style khusus jika perlu -->
    <style>
        /* Contoh: Menyesuaikan warna border input saat focus */
        .form-control:focus {
            border-color: #0d6efd;
            /* Bootstrap primary color */
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Contoh: Menyesuaikan warna ikon label */
        .col-form-label i {
            color: #6c757d;
            /* Secondary text color */
        }

        /* Contoh: Menyesuaikan warna tombol saat hover */
        .btn:hover {
            opacity: 0.9;
        }

        /* Style untuk input disabled */
        select[disabled] {
            background-color: #e9ecef;
            /* Warna latar belakang disabled */
            opacity: 0.7;
            /* Opacity untuk menunjukkan disabled */
        }
    </style>
@endpush

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Tidak perlu logika JavaScript untuk mengisi dropdown karena disabled
            // Tapi kita tetap validasi submit di sisi client jika perlu

            $('#mataKuliahForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                // Tidak perlu validasi prodi/kurikulum karena dropdown disabled dan hidden input ada

                // Tambahkan indikator loading ke tombol
                const originalBtnText = $('#saveBtn').html();
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Memperbarui...');

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href =
                                    "{{ route('matakuliah.index') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat memperbarui data.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            // Tampilkan pesan error pertama
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        // Kembalikan teks tombol setelah selesai
                        $('#saveBtn').prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
@endpush
