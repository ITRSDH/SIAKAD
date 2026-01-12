@extends('layouts.index')
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
                    <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mata-kuliah.create') }}">Tambah Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Mata Kuliah
                        </h3>
                        <a href="{{ route('mata-kuliah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="mataKuliahForm">
                            @csrf

                            <!-- Prodi, Kurikulum, Semester -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
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
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_kurikulum" class="col-form-label">
                                            <i class="fas fa-book me-1 text-info"></i>Kurikulum <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="id_kurikulum" id="id_kurikulum"
                                            class="form-control form-select @error('id_kurikulum') is-invalid @enderror"
                                            required>
                                            <option value="">Pilih Kurikulum...</option>
                                            <!-- Diisi oleh JS -->
                                        </select>
                                        @error('id_kurikulum')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="semester_rekomendasi" class="col-form-label">
                                            <i class="fas fa-calendar-alt me-1 text-info"></i>Semester <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" id="semester_rekomendasi" name="semester_rekomendasi"
                                            class="form-control @error('semester_rekomendasi') is-invalid @enderror"
                                            min="1" max="14" required
                                            value="{{ old('semester_rekomendasi', 1) }}">
                                        @error('semester_rekomendasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Repeater -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="7%" rowspan="2" class="align-middle text-center">NO</th>
                                            <th width="10%" rowspan="2" class="align-middle text-center">KODE</th>
                                            <th width="40%" rowspan="2" class="align-middle text-center">MATA
                                                KULIAH</th>
                                            <th colspan="4" class="text-center">SKS</th>
                                            <th width="5%" rowspan="2" class="text-center">AKSI</th>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">T</th>
                                            <th class="text-center">P</th>
                                            <th class="text-center">K</th>
                                            <th class="text-center">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="mataKuliahContainer">
                                        <!-- Default Row -->
                                        <tr class="repeater-item">
                                            <td>
                                                <input type="text" class="form-control" value="1" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="mata_kuliah[0][kode_mk]" class="form-control"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="text" name="mata_kuliah[0][nama_mk]" class="form-control"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="mata_kuliah[0][teori]"
                                                    class="form-control teori-input" min="0" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="mata_kuliah[0][praktikum]"
                                                    class="form-control praktikum-input" min="0" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="mata_kuliah[0][klinik]"
                                                    class="form-control klinik-input" min="0" value="0">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control sks-output" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-row-btn"
                                                    style="display:none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="3" class="text-center">JUMLAH</td>
                                            <td><input type="number" id="totalTeori" class="form-control" readonly></td>
                                            <td><input type="number" id="totalPraktikum" class="form-control" readonly>
                                            </td>
                                            <td><input type="number" id="totalKlinik" class="form-control" readonly>
                                            </td>
                                            <td><input type="number" id="totalSKS" class="form-control" readonly></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                                    <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
                                </button>
                            </div>

                            <div class="form-group mt-4 d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> Simpan Semua
                                </button>
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
    <script>
        $(document).ready(function() {
            const kurikulumData = @json($kurikulum);

            function fillKurikulumOptions(prodiId) {
                const kurikulumSelect = $('#id_kurikulum');
                kurikulumSelect.empty().append('<option value="">Pilih Kurikulum...</option>');

                if (prodiId) {
                    const filtered = kurikulumData.filter(k => k.id_prodi == prodiId);
                    filtered.forEach(k => {
                        kurikulumSelect.append(`<option value="${k.id}">${k.nama_kurikulum}</option>`);
                    });
                }
            }

            $('#id_prodi').on('change', function() {
                fillKurikulumOptions($(this).val());
            });

            // Repeater Logic
            let rowCounter = 1;

            function hitungSKS(row) {
                const teori = parseInt(row.find('.teori-input').val()) || 0;
                const praktikum = parseInt(row.find('.praktikum-input').val()) || 0;
                const klinik = parseInt(row.find('.klinik-input').val()) || 0;
                const total = teori + praktikum + klinik;
                row.find('.sks-output').val(total);
                return {
                    teori,
                    praktikum,
                    klinik,
                    total
                };
            }

            function hitungTotalSemester() {
                let totalT = 0,
                    totalP = 0,
                    totalK = 0,
                    totalSKS = 0;

                $('.repeater-item').each(function() {
                    const data = hitungSKS($(this));
                    totalT += data.teori;
                    totalP += data.praktikum;
                    totalK += data.klinik;
                    totalSKS += data.total;
                });

                $('#totalTeori').val(totalT);
                $('#totalPraktikum').val(totalP);
                $('#totalKlinik').val(totalK);
                $('#totalSKS').val(totalSKS);
            }

            $('#addRowBtn').on('click', function() {
                const newRow = `
                <tr class="repeater-item">
                    <td>
                        <input type="text" class="form-control" value="${rowCounter + 1}" readonly>
                    </td>
                    <td>
                        <input type="text" name="mata_kuliah[${rowCounter}][kode_mk]" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="mata_kuliah[${rowCounter}][nama_mk]" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" name="mata_kuliah[${rowCounter}][teori]" class="form-control teori-input" min="0" value="0">
                    </td>
                    <td>
                        <input type="number" name="mata_kuliah[${rowCounter}][praktikum]" class="form-control praktikum-input" min="0" value="0">
                    </td>
                    <td>
                        <input type="number" name="mata_kuliah[${rowCounter}][klinik]" class="form-control klinik-input" min="0" value="0">
                    </td>
                    <td>
                        <input type="number" class="form-control sks-output" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                const newRowElement = $(newRow);
                hitungSKS(newRowElement);
                $('#mataKuliahContainer').append(newRowElement);
                rowCounter++;
                hitungTotalSemester();
            });

            $(document).on('input', '.teori-input, .praktikum-input, .klinik-input', function() {
                const row = $(this).closest('.repeater-item');
                hitungSKS(row);
                hitungTotalSemester();
            });

            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('.repeater-item').remove();
                $('.repeater-item').each((index, el) => {
                    $(el).find('input[type="text"]').eq(0).val(index + 1);
                });
                hitungTotalSemester();
            });

            $('#mataKuliahForm').on('submit', function(e) {
                e.preventDefault();

                const originalBtnText = $('#saveBtn').html();
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                // Buat FormData dan tambahkan data manual
                const formData = new FormData();
                formData.append('id_prodi', $('#id_prodi').val());
                formData.append('id_kurikulum', $('#id_kurikulum').val());
                formData.append('semester_rekomendasi', $('#semester_rekomendasi').val());

                // Ambil data mata kuliah
                $('.repeater-item').each(function(index) {
                    const row = $(this);
                    formData.append(`mata_kuliah[${index}][kode_mk]`, row.find(
                        'input[name*="[kode_mk]"]').val());
                    formData.append(`mata_kuliah[${index}][nama_mk]`, row.find(
                        'input[name*="[nama_mk]"]').val());
                    formData.append(`mata_kuliah[${index}][teori]`, row.find(
                        'input[name*="[teori]"]').val());
                    formData.append(`mata_kuliah[${index}][praktikum]`, row.find(
                        'input[name*="[praktikum]"]').val());
                    formData.append(`mata_kuliah[${index}][klinik]`, row.find(
                        'input[name*="[klinik]"]').val());
                });

                // Kirim data ke endpoint API
                $.ajax({
                    url: "{{ route('mata-kuliah.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil disimpan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href =
                                    "{{ route('mata-kuliah.index') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan.',
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
                            const errors = Object.values(xhr.responseJSON.errors);
                            errorMessage = Array.isArray(errors[0]) ? errors[0][0] :
                                errorMessage;
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

            hitungTotalSemester();
        });
    </script>
@endpush
