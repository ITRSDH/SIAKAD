@extends('layouts.index')
@section('title', 'Input Nilai Mahasiswa')

@push('styles-custom')
    <style>
        /* Gaya Umum */
        body {
            background-color: #f8fafc;
        }

        .nilai-input {
            width: 90px;
            transition: all 0.3s ease;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .nilai-input:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 76, 86, 0.25);
            border-color: #004c56;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background: linear-gradient(135deg, #004c56 0%, #007a8c 100%);
            color: white;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            padding: 1rem 1.25rem;
        }

        .card-header h4 {
            color: white;
            margin-bottom: 0.25rem;
        }

        .card-header small {
            color: rgba(255, 255, 255, 0.85);
        }

        .progress-indicator {
            display: none;
            margin-left: 10px;
        }

        .table-container {
            max-height: 60vh;
            overflow-y: auto;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        /* Styling Tabel */
        .table {
            margin-bottom: 0;
            background-color: white;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: #e9ecef;
            position: sticky;
            top: 0;
            z-index: 10;
            border-top: none;
            border-bottom-width: 2px;
            font-weight: 600;
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: #f9fafb;
        }

        .table tbody tr:nth-of-type(odd):hover {
            background-color: #edf2f7;
        }

        .error-field {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .highlight-row {
            background-color: #fff3cd !important;
        }

        /* Tombol Aksi */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-reset {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-reset:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        /* Badge Info */
        .info-badge {
            font-size: 0.75rem;
            padding: 0.25em 0.5em;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }

        .total-students {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }

        /* Alert Petunjuk */
        .guide-alert {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 0 0.375rem 0.375rem 0;
        }

        .no-students-placeholder {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .no-students-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Input Nilai Mahasiswa</h3>
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
                    <a href="#">Akademik</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Input Nilai</a>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (empty($kelasList))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada kelas yang tersedia untuk input nilai saat ini.
            </div>
        @else
            <div class="alert guide-alert mb-4">
                <i class="fas fa-lightbulb me-2 text-primary"></i>
                <strong>Petunjuk:</strong> Masukkan nilai antara <strong>0 - 100</strong> untuk mahasiswa yang bersangkutan.
                Gunakan tombol <strong>Reset</strong> untuk membersihkan semua input nilai dalam satu kelas.
            </div>

            @foreach ($kelasList as $item)
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-1">
                                    {{ $item['kelas_mk']['kode_mk'] ?? '-' }} -
                                    {{ $item['kelas_mk']['nama_mk'] ?? '-' }}
                                </h4>
                                <small class="opacity-75">
                                    {{ $item['kelas_mk']['kelas'] ?? '-' }} •
                                    {{ $item['kelas_mk']['sks'] ?? '0' }} SKS
                                </small>
                            </div>
                            <div class="badge bg-light text-dark border">
                                <i class="fas fa-users me-1"></i>
                                {{ count($item['mahasiswa']) }} Mahasiswa
                            </div>
                        </div>
                    </div>

                    <form class="form-nilai">
                        @csrf
                        <input type="hidden" name="id_kelas_mk" value="{{ $item['kelas_mk']['id'] }}">

                        <div class="card-body p-0">
                            <div class="table-responsive table-container">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">NIM</th>
                                            <th>Nama</th>
                                            <th style="width: 20%;">Program Studi</th>
                                            <th class="text-center" style="width: 15%;">Nilai (0-100)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($item['mahasiswa'] as $index => $mhs)
                                            <tr>
                                                <td class="fw-medium">{{ $loop->iteration }}</td>
                                                <td class="font-monospace fw-medium">{{ $mhs['nim'] }}</td>
                                                <td>{{ $mhs['nama_mahasiswa'] }}</td>
                                                <td>{{ $mhs['prodi']['nama_prodi'] ?? '-' }}</td>
                                                <td class="text-center">
                                                    <input type="number" name="nilai[{{ $index }}][nilai_angka]"
                                                        class="form-control form-control-sm nilai-input text-center"
                                                        placeholder="-" min="0" max="100" step="0.1">
                                                    <input type="hidden" name="nilai[{{ $index }}][id_mahasiswa]"
                                                        value="{{ $mhs['id'] }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="no-students-placeholder">
                                                    <i class="fas fa-user-slash text-muted"></i>
                                                    <h5 class="text-muted">Tidak Ada Mahasiswa Terdaftar</h5>
                                                    <p class="mb-0 text-muted">Kelas ini belum memiliki mahasiswa terdaftar.
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-top">
                            <div class="action-buttons">
                                <button type="button" class="btn btn-outline-secondary btn-reset"
                                    data-form-id="{{ $item['kelas_mk']['id'] }}">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Nilai
                                    <span class="progress-indicator">
                                        <i class="fas fa-spinner fa-spin ms-1"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {

            // Reset form nilai
            $('.btn-reset').click(function() {
                const formId = $(this).data('form-id');
                const form = $(`.form-nilai input[name="id_kelas_mk"][value="${formId}"]`).closest('form');

                form.find('input[name*="[nilai_angka]"]').val('');
                form.find('.error-field').removeClass('error-field');
                form.find('tr').removeClass('highlight-row');

                Swal.fire({
                    icon: 'info',
                    title: 'Input Nilai Direset',
                    text: 'Semua nilai dalam kelas ini telah dikosongkan.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });

            // Validasi dan submit form
            $('.form-nilai').submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let btn = form.find('button[type="submit"]');
                let spinner = form.find('.progress-indicator');

                form.find('.error-field').removeClass('error-field');
                form.find('tr').removeClass('highlight-row');

                let formData = new FormData();
                formData.append('id_kelas_mk', form.find('input[name="id_kelas_mk"]').val());

                let valid = false;
                let errorRows = [];
                let invalidInputs = [];

                form.find('input[name*="[nilai_angka]"]').each(function(index) {
                    let nilai = parseFloat($(this).val());
                    let idMhs = form.find(`input[name="nilai[${index}][id_mahasiswa]"]`).val();

                    if ($(this).val() !== '') {
                        if (isNaN(nilai) || nilai < 0 || nilai > 100) {
                            $(this).addClass('error-field');
                            $(this).closest('tr').addClass('highlight-row');
                            errorRows.push(form.find(`tr`).eq(index).find('td:first').text());
                            invalidInputs.push(this);
                        } else {
                            formData.append(`nilai[${index}][id_mahasiswa]`, idMhs);
                            formData.append(`nilai[${index}][nilai_angka]`, nilai.toFixed(1));
                            valid = true;
                        }
                    }
                });

                if (errorRows.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nilai Tidak Valid',
                        html: `Nilai tidak valid ditemukan pada mahasiswa berikut:<br><strong>${errorRows.slice(0, 5).join(', ')}</strong>${errorRows.length > 5 ? ` dan ${errorRows.length - 5} lainnya...` : ''}<br><br>Silakan periksa kembali inputan Anda.`,
                        confirmButtonText: 'Perbaiki'
                    });
                    if (invalidInputs.length > 0) {
                        $(invalidInputs[0]).focus().select();
                    }
                    return;
                }

                if (!valid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Ada Data Disimpan',
                        text: 'Silakan masukkan setidaknya satu nilai mahasiswa.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Konfirmasi sebelum submit
                Swal.fire({
                    title: 'Konfirmasi Penyimpanan',
                    text: `Anda akan menyimpan ${form.find('input[name*="[nilai_angka]"]:not(:empty)').length} nilai. Apakah Anda yakin ingin melanjutkan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true);
                        spinner.show();

                        $.ajax({
                            url: "{{ route('dosenmk.store-nilai') }}",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message || 'Nilai berhasil disimpan.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    form.find('input[name*="[nilai_angka]"]').val(
                                        '').removeClass('error-field');
                                });
                            },
                            error(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menyimpan',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan saat menyimpan nilai.',
                                    confirmButtonText: 'Coba Lagi'
                                });
                            },
                            complete() {
                                btn.prop('disabled', false);
                                spinner.hide();
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
