@extends('layouts.index')
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
                    <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('mata-kuliah.edit', $semester) }}?id_kurikulum={{ request()->query('id_kurikulum') }}">Edit
                        Mata Kuliah Semester {{ $semester }}</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit me-2 text-primary"></i>Edit Mata Kuliah - Semester {{ $semester }}
                        </h3>
                        <a href="{{ route('mata-kuliah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="mataKuliahUpdateForm">
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
                                                    {{ old('id_prodi', $selectedProdi) == $item['id'] ? 'selected' : '' }}>
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
                                            value="{{ old('semester_rekomendasi', $semester) }}" readonly>
                                        @error('semester_rekomendasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Tambah Baris -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-success" id="addRowBtn">
                                    <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
                                </button>
                            </div>

                            <!-- Tabel Repeater -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%" rowspan="2" class="align-middle text-center">NO
                                            </th>
                                            <th width="15%" rowspan="2" class="align-middle text-center">KODE
                                            </th>
                                            <th width="35%" rowspan="2" class="align-middle text-center">MATA
                                                KULIAH</th>
                                            <th colspan="4" class="text-center">SKS</th>
                                            </th>
                                            <th width="5%" rowspan="2" class="align-middle text-center">AKSI
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
                                        @php $index = 0; @endphp
                                        @foreach ($mataKuliah as $mk)
                                            <tr class="repeater-item" data-index="{{ $index }}"
                                                data-id="{{ $mk['id'] }}">
                                                <td>
                                                    <input type="text" class="form-control no-input"
                                                        value="{{ $index + 1 }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="mata_kuliah[{{ $index }}][id]"
                                                        value="{{ $mk['id'] }}">
                                                    <input type="text"
                                                        name="mata_kuliah[{{ $index }}][kode_mk]"
                                                        class="form-control" value="{{ $mk['kode_mk'] }}" required>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="mata_kuliah[{{ $index }}][nama_mk]"
                                                        class="form-control" value="{{ $mk['nama_mk'] }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="mata_kuliah[{{ $index }}][teori]"
                                                        class="form-control teori-input" min="0"
                                                        value="{{ $mk['teori'] }}">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="mata_kuliah[{{ $index }}][praktikum]"
                                                        class="form-control praktikum-input" min="0"
                                                        value="{{ $mk['praktikum'] }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="mata_kuliah[{{ $index }}][klinik]"
                                                        class="form-control klinik-input" min="0"
                                                        value="{{ $mk['klinik'] }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control sks-output"
                                                        value="{{ $mk['sks'] }}" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
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

                            <div class="form-group mt-4 d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
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
            let nextIndex = @json(count($mataKuliah));

            function fillKurikulumOptions(prodiId) {
                const kurikulumSelect = $('#id_kurikulum');
                kurikulumSelect.empty().append('<option value="">Pilih Kurikulum...</option>');

                if (prodiId) {
                    const filtered = kurikulumData.filter(k => k.id_prodi == prodiId);
                    filtered.forEach(k => {
                        kurikulumSelect.append(
                            `<option value="${k.id}" ${k.id == @json($selectedKurikulum) ? 'selected' : ''}>${k.nama_kurikulum}</option>`
                        );
                    });
                }
            }

            $('#id_prodi').on('change', function() {
                fillKurikulumOptions($(this).val());
            });

            fillKurikulumOptions($('#id_prodi').val());

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

            function recalculateNo() {
                $('.repeater-item').each(function(index) {
                    $(this).find('.no-input').val(index + 1);
                    const rowInputs = $(this).find('input');
                    rowInputs.each(function() {
                        const name = $(this).attr('name');
                        if (name && name.startsWith('mata_kuliah[')) {
                            const newName = name.replace(/\[([0-9]+)\]/, '[' + index + ']');
                            $(this).attr('name', newName);
                        }
                    });
                    $(this).attr('data-index', index);
                });
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

            $(document).on('input', '.teori-input, .praktikum-input, .klinik-input', function() {
                const row = $(this).closest('.repeater-item');
                hitungSKS(row);
                hitungTotalSemester();
            });

            // Fungsi untuk menghapus baris dari DOM
            function removeRowFromDOM(row) {
                row.remove();
                recalculateNo();
                hitungTotalSemester();
            }

            // Event delegation untuk tombol hapus
            $(document).on('click', '.remove-row-btn', async function() {
                const row = $(this).closest('.repeater-item');
                const id = row.data('id'); // Ambil ID dari data-id

                // Jika tidak ada ID, hanya hapus dari DOM
                if (!id) {
                    removeRowFromDOM(row);
                    return;
                }

                // Jika ada ID, konfirmasi dan hapus dari DB
                const result = await Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data mata kuliah ini juga akan dihapus dari database.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    // Panggil API untuk menghapus dari DB
                    $.ajax({
                        url: `{{ route('mata-kuliah.destroy-single', '__ID__') }}`.replace(
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
                                });
                                // Hapus baris dari DOM setelah sukses menghapus dari DB
                                removeRowFromDOM(row);
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
                            let errorMessage = 'Gagal menghapus data dari database.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                            // Jangan hapus baris jika gagal menghapus dari DB
                        }
                    });
                }
            });

            $('#addRowBtn').on('click', function() {
                const newRow = `
                    <tr class="repeater-item" data-index="${nextIndex}">
                        <td>
                            <input type="text" class="form-control no-input" value="${$('.repeater-item').length + 1}" readonly>
                        </td>
                        <td>
                            <input type="hidden" name="mata_kuliah[${nextIndex}][id]" value="">
                            <input type="text" name="mata_kuliah[${nextIndex}][kode_mk]" class="form-control" required>
                        </td>
                        <td>
                            <input type="text" name="mata_kuliah[${nextIndex}][nama_mk]" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="mata_kuliah[${nextIndex}][teori]" class="form-control teori-input" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" name="mata_kuliah[${nextIndex}][praktikum]" class="form-control praktikum-input" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" name="mata_kuliah[${nextIndex}][klinik]" class="form-control klinik-input" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" class="form-control sks-output" value="0" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#mataKuliahContainer').append(newRow);
                nextIndex++;
            });

            $('#mataKuliahUpdateForm').on('submit', function(e) {
                e.preventDefault();

                const originalBtnText = $('#saveBtn').html();
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                const idProdi = $('#id_prodi').val();
                const idKurikulum = $('#id_kurikulum').val();
                const semesterRekomendasi = $('#semester_rekomendasi').val();

                let mataKuliah = [];
                let hasError = false;

                $('.repeater-item').each(function(index) {
                    const row = $(this);
                    const mkId = row.find('input[name*="[id]"]').val();
                    const kodeMk = row.find('input[name*="[kode_mk]"]').val();
                    const namaMk = row.find('input[name*="[nama_mk]"]').val();
                    const teori = parseInt(row.find('input[name*="[teori]"]').val()) || 0;
                    const praktikum = parseInt(row.find('input[name*="[praktikum]"]').val()) || 0;
                    const klinik = parseInt(row.find('input[name*="[klinik]"]').val()) || 0;

                    if (!kodeMk || !namaMk || teori === '' || praktikum === '' || klinik === '') {
                        hasError = true;
                        return false;
                    }

                    const mkObject = {
                        kode_mk: kodeMk,
                        nama_mk: namaMk,
                        teori: teori,
                        praktikum: praktikum,
                        klinik: klinik
                    };

                    if (mkId) {
                        mkObject.id = mkId;
                    }

                    mataKuliah.push(mkObject);
                });

                if (hasError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Pastikan semua data mata kuliah lengkap (Kode, Nama, T, P, K).',
                        confirmButtonText: 'OK'
                    });
                    $('#saveBtn').prop('disabled', false).html(originalBtnText);
                    return;
                }

                const payload = {
                    id_prodi: idProdi,
                    id_kurikulum: idKurikulum,
                    semester_rekomendasi: semesterRekomendasi,
                    mata_kuliah: mataKuliah
                };

                $.ajax({
                    url: "{{ route('mata-kuliah.update', $semester) }}",
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Data berhasil diperbarui/ditambahkan.',
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
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors[0] || errorMessage;
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
