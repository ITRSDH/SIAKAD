@extends('admin.layouts.index')
@section('title', 'Tambah Mata Kuliah')

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
                    <a href="{{ route('matakuliah.create') }}">Tambah Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Mata Kuliah Baru
                        </h3>
                        <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="mataKuliahForm" method="POST" action="{{ route('matakuliah.store') }}">
                            @csrf
                            <div class="row">
                                <!-- Kolom Kiri - Informasi Utama -->
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="id_prodi" class="col-form-label">
                                            <i class="fas fa-graduation-cap me-1 text-info"></i>Program Studi <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="id_prodi" id="id_prodi"
                                            class="form-control form-select @error('id_prodi') is-invalid @enderror"
                                            required>
                                            <option value="">Pilih Program Studi...</option>
                                            @foreach ($prodi as $item)
                                                <option value="{{ $item['id'] }}"
                                                    {{ old('id_prodi') == $item['id'] ? 'selected' : '' }}>
                                                    {{ $item['nama_prodi'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_prodi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted mt-1">Pilih program studi terlebih dahulu untuk
                                            melihat kurikulum yang tersedia.</small>
                                    </div>

                                    <!-- Bagian Kurikulum -->
                                    <div class="form-group mb-4" id="kurikulumSection" style="display: none;">
                                        <label for="id_kurikulum" class="col-form-label">
                                            <i class="fas fa-book me-1 text-info"></i>Kurikulum <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="id_kurikulum" id="id_kurikulum"
                                            class="form-control form-select @error('id_kurikulum') is-invalid @enderror"
                                            required>
                                            <option value="">Memuat kurikulum...</option>
                                            <!-- Opsi akan diisi oleh JS -->
                                        </select>
                                        @error('id_kurikulum')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small id="kurikulumHelp" class="form-text text-muted mt-1">Pilih kurikulum yang
                                            sesuai.</small>
                                        <div id="kurikulumAlert" class="alert alert-info mt-2" style="display: none;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <span id="alertMessage">Kurikulum akan dipilih otomatis berdasarkan prodi
                                                aktif.</span>
                                            <a href="#" id="showAllKurikulumLink" class="ms-2">Lihat Semua
                                                Kurikulum?</a>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="kode_mk" class="col-form-label">
                                            <i class="fas fa-barcode me-1 text-info"></i>Kode Mata Kuliah <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('kode_mk') is-invalid @enderror"
                                            id="kode_mk" name="kode_mk" placeholder="Contoh: IF101"
                                            value="{{ old('kode_mk') }}" required>
                                        @error('kode_mk')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted mt-1">Gunakan kode unik, tanpa spasi.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="nama_mk" class="col-form-label">
                                            <i class="fas fa-font me-1 text-info"></i>Nama Mata Kuliah <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('nama_mk') is-invalid @enderror"
                                            id="nama_mk" name="nama_mk" placeholder="Contoh: Algoritma dan Pemrograman"
                                            value="{{ old('nama_mk') }}" required>
                                        @error('nama_mk')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="jenis" class="col-form-label">
                                            <i class="fas fa-tags me-1 text-info"></i>Jenis <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="form-control form-select @error('jenis') is-invalid @enderror"
                                            id="jenis" name="jenis" required>
                                            <option value="">Pilih Jenis...</option>
                                            <option value="Wajib" {{ old('jenis') == 'Wajib' ? 'selected' : '' }}>Wajib
                                            </option>
                                            <option value="Pilihan" {{ old('jenis') == 'Pilihan' ? 'selected' : '' }}>
                                                Pilihan</option>
                                        </select>
                                        @error('jenis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan - Detail Jam & Deskripsi -->
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="sks" class="col-form-label">
                                            <i class="fas fa-weight-hanging me-1 text-info"></i>SKS <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('sks') is-invalid @enderror"
                                            id="sks" name="sks" placeholder="Jumlah SKS"
                                            value="{{ old('sks') }}" required min="0" max="10">
                                        @error('sks')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted mt-1">Jumlah Satuan Kredit Semester
                                            (0-10).</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="semester_rekomendasi" class="col-form-label">
                                            <i class="fas fa-calendar-alt me-1 text-info"></i>Semester Rekomendasi <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control @error('semester_rekomendasi') is-invalid @enderror"
                                            id="semester_rekomendasi" name="semester_rekomendasi" placeholder="Contoh: 1"
                                            value="{{ old('semester_rekomendasi') }}" required min="1"
                                            max="14">
                                        @error('semester_rekomendasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted mt-1">Semester ideal untuk mengambil MK
                                            (1-14).</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 pe-md-2 mb-3 mb-md-0">
                                            <label for="teori" class="col-form-label">
                                                <i class="fas fa-chalkboard-teacher me-1 text-info"></i>Teori (Jam) <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('teori') is-invalid @enderror" id="teori"
                                                name="teori" placeholder="0" value="{{ old('teori') }}" required
                                                min="0">
                                            @error('teori')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 ps-md-2">
                                            <label for="praktikum" class="col-form-label">
                                                <i class="fas fa-flask me-1 text-info"></i>Praktikum (Jam) <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('praktikum') is-invalid @enderror"
                                                id="praktikum" name="praktikum" placeholder="0"
                                                value="{{ old('praktikum') }}" required min="0">
                                            @error('praktikum')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 pe-md-2 mb-3 mb-md-0">
                                            <label for="seminar" class="col-form-label">
                                                <i class="fas fa-users me-1 text-info"></i>Seminar (Jam) <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('seminar') is-invalid @enderror" id="seminar"
                                                name="seminar" placeholder="0" value="{{ old('seminar') }}" required
                                                min="0">
                                            @error('seminar')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 ps-md-2">
                                            <label for="praktek_klinik" class="col-form-label">
                                                <i class="fas fa-stethoscope me-1 text-info"></i>Praktek Klinik (Jam) <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('praktek_klinik') is-invalid @enderror"
                                                id="praktek_klinik" name="praktek_klinik" placeholder="0"
                                                value="{{ old('praktek_klinik') }}" required min="0">
                                            @error('praktek_klinik')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <label for="deskripsi" class="col-form-label">
                                            <i class="fas fa-align-left me-1 text-info"></i>Deskripsi
                                        </label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" style="height:100px" id="deskripsi"
                                            name="deskripsi" placeholder="Deskripsi singkat mata kuliah...">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4 d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> Simpan
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
    <style>
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .col-form-label i {
            color: #6c757d;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Jarak antar form group */
        .form-group {
            margin-bottom: 1.5rem;
            /* Atur sesuai kebutuhan */
        }

        /* Gaya untuk alert kurikulum */
        #kurikulumAlert {
            font-size: 0.875em;
            /* Ukuran teks kecil */
        }

        /* Link di dalam alert */
        #showAllKurikulumLink {
            text-decoration: underline;
        }
    </style>
@endpush

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const kurikulumData = @json($kurikulum);
            const oldProdiId = @json(old('id_prodi'));
            const oldKurikulumId = @json(old('id_kurikulum'));

            // Fungsi untuk mengisi dropdown kurikulum berdasarkan ID Prodi
            function fillKurikulumOptions(prodiId) {
                const kurikulumSelect = $('#id_kurikulum');
                const kurikulumSection = $('#kurikulumSection');
                const helpText = $('#kurikulumHelp');
                const alertDiv = $('#kurikulumAlert');
                const alertMessage = $('#alertMessage');

                kurikulumSelect.empty();

                if (prodiId) {
                    // Filter kurikulum berdasarkan id_prodi
                    const filteredKurikulum = kurikulumData.filter(kur => kur.id_prodi == prodiId);

                    if (filteredKurikulum.length > 0) {
                        // Misalnya, kita pilih kurikulum dengan id_terbesar (mungkin yang terbaru/aktif) sebagai default
                        // Atau cari kurikulum yang memiliki status 'aktif' jika ada
                        // Untuk sementara, ambil yang pertama sebagai default
                        const defaultKurikulum = filteredKurikulum[0]; // Ubah logika ini sesuai kebutuhan API

                        // Tampilkan section kurikulum
                        kurikulumSection.show();

                        // Isi dropdown dengan semua kurikulum dari prodi
                        filteredKurikulum.forEach(kur => {
                            const isSelected = (oldKurikulumId && kur.id == oldKurikulumId) ? 'selected' :
                                '';
                            kurikulumSelect.append(
                                `<option value="${kur.id}" ${isSelected}>${kur.nama_kurikulum}</option>`
                            );
                        });

                        // Jika tidak ada oldKurikulumId, pilih defaultKurikulum
                        if (!oldKurikulumId) {
                            kurikulumSelect.val(defaultKurikulum.id);
                            alertMessage.text(`Kurikulum default: ${defaultKurikulum.nama_kurikulum}`);
                        } else {
                            alertMessage.text('Kurikulum dipilih dari data sebelumnya.');
                        }

                        helpText.text('Pilih kurikulum yang sesuai.');
                        alertDiv.show(); // Tampilkan alert info

                    } else {
                        // Tidak ada kurikulum untuk prodi ini
                        kurikulumSection.show();
                        kurikulumSelect.append(
                            '<option value="" disabled>Tidak ada kurikulum untuk prodi ini</option>');
                        kurikulumSelect.val(''); // Kosongkan pilihan
                        helpText.text('Prodi ini tidak memiliki kurikulum yang tersedia.');
                        alertDiv.hide(); // Sembunyikan alert info
                    }
                } else {
                    // Prodi belum dipilih
                    kurikulumSection.hide(); // Sembunyikan section kurikulum
                    kurikulumSelect.val(''); // Kosongkan pilihan
                    alertDiv.hide(); // Sembunyikan alert info
                }
            }

            // Event listener untuk perubahan pada dropdown prodi
            $('#id_prodi').on('change', function() {
                const selectedProdiId = $(this).val();
                fillKurikulumOptions(selectedProdiId);
            });

            // Inisialisasi jika old input tersedia
            if (oldProdiId) {
                // Tampilkan section kurikulum jika prodi lama dipilih
                $('#kurikulumSection').show();
                fillKurikulumOptions(oldProdiId);
                // Jika oldKurikulumId juga ada, alert akan menyesuaikan
            }

            // Handler untuk link "Lihat Semua Kurikulum"
            $('#showAllKurikulumLink').on('click', function(e) {
                e.preventDefault();
                // Jika link diklik, fokus ke dropdown kurikulum
                $('#id_kurikulum').focus();
                // Anda bisa menambahkan logika lain di sini jika perlu
            });


            // Validasi sederhana sebelum submit AJAX
            $('#mataKuliahForm').on('submit', function(e) {
                e.preventDefault();

                // Ambil nilai-nilai input
                const prodiId = $('#id_prodi').val();
                const kurikulumId = $('#id_kurikulum').val();
                const kode = $('#kode_mk').val().trim();
                const nama = $('#nama_mk').val().trim();
                const sks = parseInt($('#sks').val());
                const semester = parseInt($('#semester_rekomendasi').val());

                // Reset error feedback
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let isValid = true;
                let errorMessage = '';

                // Validasi sisi klien
                if (!prodiId) {
                    $('#id_prodi').addClass('is-invalid');
                    isValid = false;
                    errorMessage = 'Silakan pilih Program Studi.';
                }
                if (!kurikulumId) {
                    $('#id_kurikulum').addClass('is-invalid');
                    isValid = false;
                    errorMessage = errorMessage ? errorMessage + ' Kurikulum harus dipilih.' :
                        'Silakan pilih Kurikulum.';
                }
                if (!kode) {
                    $('#kode_mk').addClass('is-invalid');
                    isValid = false;
                    errorMessage = errorMessage ? errorMessage + ' Kode MK wajib diisi.' :
                        'Kode MK wajib diisi.';
                }
                if (!nama) {
                    $('#nama_mk').addClass('is-invalid');
                    isValid = false;
                    errorMessage = errorMessage ? errorMessage + ' Nama MK wajib diisi.' :
                        'Nama MK wajib diisi.';
                }
                if (isNaN(sks) || sks < 0 || sks > 10) {
                    $('#sks').addClass('is-invalid');
                    isValid = false;
                    errorMessage = errorMessage ? errorMessage + ' SKS harus angka antara 0-10.' :
                        'SKS harus angka antara 0-10.';
                }
                if (isNaN(semester) || semester < 1 || semester > 14) {
                    $('#semester_rekomendasi').addClass('is-invalid');
                    isValid = false;
                    errorMessage = errorMessage ? errorMessage + ' Semester harus angka antara 1-14.' :
                        'Semester harus angka antara 1-14.';
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    return; // Batalkan submit
                }

                // Jika semua validasi lolos, kirim data
                const form = this;
                const formData = new FormData(form);

                const originalBtnText = $('#saveBtn').html();
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                $.ajax({
                    url: "{{ route('matakuliah.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil disimpan.',
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
                                    'Terjadi kesalahan saat menyimpan data.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
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
                        $('#saveBtn').prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
@endpush
