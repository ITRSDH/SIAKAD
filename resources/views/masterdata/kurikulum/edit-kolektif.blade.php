@extends('layouts.index')
@section('title', 'Edit Kolektif Struktur Kurikulum')

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
            <h3 class="fw-bold mb-3">Struktur Kurikulum</h3>
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
                    <a href="#">Struktur Kurikulum</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Struktur Kurikulum</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Matakuliah Untuk Struktur MK {{ $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] }}</h4>

                            <div class="d-flex gap-2">
                                <button type="submit" form="form-tambah-mk-kolektif" class="btn btn-primary">
                                    <i class="fas fa-check me-1"></i> Simpan
                                </button>

                                <a href="{{ route('kurikulum.detail', $kurikulum['id']) }}" class="btn btn-success">
                                    <i class="fas fa-sync me-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Nama Struktur MK
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah Bobot Mata Kuliah Pilihan
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_pilihan'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Program Studi
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['prodi'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Mulai Berlaku
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['semester_mulai'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah SKS
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_lulus'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah Bobot Mata Kuliah Wajib
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_wajib'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <form id="form-tambah-mk-kolektif"
                                action="{{ route('kurikulum.tambah-mata-kuliah-checkbox', $kurikulum['id']) }}"
                                method="POST">
                                @csrf

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-primary">
                                            <tr class="text-center align-middle">
                                                <th rowspan="2" style="">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="check-all"
                                                            style="transform: scale(2.0); cursor:pointer;">
                                                    </div>
                                                </th>
                                                <th rowspan="2" style="width:5%">No</th>
                                                <th rowspan="2" style="width:5%">Kode MK</th>
                                                <th rowspan="2" style="width:20%">Nama Mata Kuliah</th>
                                                <th rowspan="2" style="width:20%">Program Studi</th>
                                                <th colspan="5">Bobot Mata Kuliah (SKS)</th>
                                                <th rowspan="2" style="width:2%">Semester</th>
                                                <th rowspan="2" style="width:2%">Wajib?</th>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <th style="width:10%">Mata Kuliah</th>
                                                <th style="width:10%">Tatap Muka</th>
                                                <th style="width:10%">Praktikum</th>
                                                <th style="width:10%">Prakt Lapangan</th>
                                                <th style="width:10%">Simulasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($matakuliah as $index => $mk)
                                                @php
                                                    // Cek apakah matakuliah sudah ada di kurikulum
                                                    $mkSudahAda = false;
                                                    $dataMk = null;

                                                    if (
                                                        isset($mataKuliahDiKurikulum) &&
                                                        is_array($mataKuliahDiKurikulum)
                                                    ) {
                                                        foreach ($mataKuliahDiKurikulum as $mk_kurikulum) {
                                                            if ($mk_kurikulum['id'] == $mk['id']) {
                                                                $mkSudahAda = true;
                                                                $dataMk = $mk_kurikulum;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    {{-- ✅ Checkbox --}}
                                                    <td class="text-center">
                                                        <input type="checkbox" class="check-mk" name="selected_mk[]"
                                                            value="{{ $mk['id'] }}"
                                                            style="transform: scale(2.0); cursor:pointer;"
                                                            {{ $mkSudahAda ? 'checked disabled' : '' }}>
                                                    </td>

                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="text-center fw-semibold">{{ $mk['kode_mk'] }}</td>
                                                    <td class="text-center">{{ $mk['nama_mk'] }}</td>
                                                    <td class="text-center">{{ $kurikulum['prodi'] ?? '-' }}</td>

                                                    <td class="text-center">{{ $mk['sks'] ?? 0 }}</td>
                                                    <td class="text-center">{{ $mk['sks_tatap_muka'] ?? 0 }}</td>
                                                    <td class="text-center">{{ $mk['sks_praktikum'] ?? 0 }}</td>
                                                    <td class="text-center">{{ $mk['sks_praktek_lapangan'] ?? 0 }}</td>
                                                    <td class="text-center">{{ $mk['sks_simulasi'] ?? 0 }}</td>

                                                    {{-- ✅ Semester (pakai ID sebagai key) --}}
                                                    <td class="text-center">
                                                        <select name="semester_ke[{{ $mk['id'] }}]"
                                                            class="form-select">
                                                            @for ($s = 1; $s <= 8; $s++)
                                                                <option value="{{ $s }}"
                                                                    {{ $mkSudahAda && ($dataMk['pivot']['semester_ke'] ?? null) == $s ? 'selected' : '' }}>
                                                                    {{ $s }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </td>

                                                    {{-- ✅ Wajib --}}
                                                    <td class="text-center">
                                                        <input type="checkbox" name="is_wajib[{{ $mk['id'] }}]"
                                                            value="1" style="transform: scale(1.5); cursor:pointer;"
                                                            {{ $mkSudahAda && ($dataMk['pivot']['is_wajib'] ?? null) == 1 ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center">Tidak ada mata kuliah tersedia.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> SIMPAN
                                    </button>
                                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> BATAL
                                    </button>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        // ✅ CHECK ALL
        document.getElementById('check-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.check-mk');

            checkboxes.forEach(cb => {
                if (!cb.disabled) {
                    cb.checked = this.checked;
                }
            });
        });

        // ✅ Highlight MK yang sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                const checkbox = row.querySelector('.check-mk');

                if (checkbox && checkbox.checked && checkbox.disabled) {
                    row.classList.add('table-info');
                }
            });
        });

        // ✅ VALIDASI SUBMIT
        document.getElementById('form-tambah-mk-kolektif')
            .addEventListener('submit', function(e) {

                const checked = document.querySelectorAll('.check-mk:checked:not(:disabled)');

                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal 1 mata kuliah!');
                }
            });
    </script>
@endpush
