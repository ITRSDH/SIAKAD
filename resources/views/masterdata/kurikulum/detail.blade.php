@extends('layouts.index')
@section('title', 'Detail Struktur Kurikulum')

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

        .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@php
    $formatKurikulumIndukLabel = static function (?array $induk): string {
        if (empty($induk)) {
            return '-';
        }

        return collect([
            $induk['kode_kurikulum'] ?? null,
            $induk['nama_kurikulum'] ?? null,
            $induk['jenis_kurikulum']['kode_jenis'] ?? null,
        ])->filter()->implode(' | ') ?: '-';
    };

    $formatStrukturKurikulumLabel = static function (array $item) use ($formatKurikulumIndukLabel): string {
        return collect([
            $formatKurikulumIndukLabel($item['kurikulum_induk'] ?? null),
            $item['nama_struktur_mk'] ?? ($item['nama_kurikulum'] ?? null),
        ])->filter()->implode(' | ') ?: '-';
    };
@endphp

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
                            <h4 class="card-title mb-0">Mengatur Struktur Kurikulum per Program Studi</h4>

                            <div class="d-flex gap-2">
                                <a href="{{ route('kurikulum-induk.index') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-sitemap me-1"></i> Tahun Kurikulum
                                </a>
                                <a href="{{ route('jenis-kurikulum.index') }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-layer-group me-1"></i> Jenis Kurikulum
                                </a>
                                <a href="{{ route('kurikulum.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>

                                <button type="submit" form="formubahkurikulum" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i> Ubah
                                </button>

                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                    data-id="{{ $kurikulum['id'] }}" data-nama="{{ $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] }}">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>

                                <a href="{{ route('kurikulum.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
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

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('kurikulum.update', $kurikulum['id'] ?? '') }}" method="POST"
                            id="formubahkurikulum">
                            @csrf
                            @if (isset($kurikulum))
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                {{-- Nama Struktur MK --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Struktur Kurikulum</label>
                                    <input type="text" name="nama_struktur_mk" class="form-control"
                                        value="{{ old('nama_struktur_mk', $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] ?? '') }}" required>
                                </div>

                                {{-- SKS Pilihan --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah SKS Pilihan</label>
                                    <input type="number" name="jumlah_sks_pilihan" class="form-control"
                                        value="{{ old('jumlah_sks_pilihan', $kurikulum['jumlah_sks_pilihan'] ?? '') }}"
                                        required>
                                </div>

                                {{-- Program Studi --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tahun Kurikulum</label>
                                    <select name="id_kurikulum_induk" id="id_kurikulum_induk" class="form-control select2" required>
                                        @foreach ($kurikulumInduk as $item)
                                            <option value="{{ $item['id'] }}"
                                                data-prodi="{{ $item['id_prodi'] }}"
                                                data-kode="{{ $item['kode_kurikulum'] ?? '' }}"
                                                data-tahun="{{ $item['tahun_kurikulum'] ?? '' }}"
                                                data-keterangan="{{ $item['keterangan'] ?? $item['nama_kurikulum'] ?? '' }}"
                                                data-mulai-berlaku="{{ $item['mulai_berlaku'] ?? '' }}"
                                                data-jenis="{{ $item['jenis_kurikulum']['kode_jenis'] ?? '' }}"
                                                {{ old('id_kurikulum_induk', $kurikulum['id_kurikulum_induk'] ?? '') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['kurikulum_induk'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Struktur operasional ini harus terhubung ke satu master tahun kurikulum yang jelas.</small>
                                    <div id="kurikulumIndukSummary" class="small text-muted mt-2">
                                        @if (!empty($kurikulum['kurikulum_induk']))
                                            {{ $formatKurikulumIndukLabel($kurikulum['kurikulum_induk']) }}
                                        @else
                                            Pilih tahun kurikulum untuk melihat ringkasan jenis, tahun, dan mulai berlakunya.
                                        @endif
                                    </div>
                                </div>

                                {{-- Program Studi --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Program Studi</label>
                                    <select name="id_prodi" id="id_prodi" class="form-control select2" required>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p['id'] }}"
                                                {{ old('id_prodi', $kurikulum['id_prodi'] ?? '') == $p['id'] ? 'selected' : '' }}>
                                                {{ $p['prodi'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Semester Mulai --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mulai Berlaku</label>
                                    <select name="id_semester" id="id_semester" class="form-control select2" required>
                                        @foreach ($semester as $s)
                                            <option value="{{ $s['id'] }}"
                                                {{ old('id_semester', $kurikulum['id_semester'] ?? '') == $s['id'] ? 'selected' : '' }}>
                                                {{ $s['semester'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jumlah SKS Lulus --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah SKS Lulus</label>
                                    <input type="number" name="jumlah_sks_lulus" class="form-control"
                                        value="{{ old('jumlah_sks_lulus', $kurikulum['jumlah_sks_lulus'] ?? '') }}"
                                        readonly>
                                    <small class="text-muted">(Jumlah SKS Pilihan + Jumlah SKS Wajib)</small>
                                </div>

                                {{-- SKS Wajib --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah SKS Wajib</label>
                                    <input type="number" name="jumlah_sks_wajib" class="form-control"
                                        value="{{ old('jumlah_sks_wajib', $kurikulum['jumlah_sks_wajib'] ?? '') }}"
                                        required>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Judul -->
                            <span class="fw-semibold">
                                Salin data Matakuliah Struktur MK dari :
                            </span>

                            <form action="{{ route('kurikulum.clone-mata-kuliah', ['id_tujuan' => $kurikulum['id']]) }}"
                                method="POST" class="d-flex align-items-center gap-2">
                                @csrf

                                <!-- Dropdown -->
                                <div style="width:220px;">
                                    <select class="form-select select2" id="id_kurikulum_clone" name="id_kurikulum_asal"
                                        required>
                                        <option value="" selected disabled>– Pilih Struktur MK –</option>
                                        @foreach ($kurikulum_lain as $k)
                                            <option value="{{ $k['id'] }}">{{ $formatStrukturKurikulumLabel($k) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tombol Submit -->
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-copy me-1"></i>
                                    SALIN MATAKULIAH
                                </button>
                            </form>

                            <!-- Tombol Edit Kolektif -->
                            <a href="{{ route('kurikulum.edit-kolektif', ['id' => $kurikulum['id']]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                EDIT KOLEKTIF MATAKULIAH
                            </a>

                            <!-- Tombol Tambah -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#modalTambahMatkul">
                                <i class="fas fa-plus me-1"></i>
                                TAMBAH MATAKULIAH
                            </button>

                            <button type="button" class="btn btn-info" id="btnTambahKonversi">
                                <i class="fas fa-right-left me-1"></i>
                                ATUR KONVERSI MATAKULIAH
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Mata Kuliah -->
            <div class="modal fade" id="modalTambahMatkul" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 70%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Matakuliah untuk Struktur MK
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="form-tambah-mk-kurikulum"
                            action="{{ route('kurikulum.tambah-mata-kuliah', $kurikulum['id']) }}" method="POST">
                            @csrf

                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- Mata Kuliah -->
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-md-3 col-form-label">
                                            Mata Kuliah
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-select select2" id="id_mata_kuliah"
                                                name="mata_kuliah[0][id_mata_kuliah]" required>
                                                <option value="">– Pilih Mata Kuliah –</option>
                                                @foreach ($matakuliah as $mk)
                                                    <option value="{{ $mk['id'] }}">{{ $mk['kode_mk'] }} -
                                                        {{ $mk['nama_mk'] }} - {{ $mk['sks'] }} SKS
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Semester -->
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-md-3 col-form-label">
                                            Semester
                                        </label>
                                        <div class="col-md-9">
                                            <input type="number" name="mata_kuliah[0][semester_ke]" class="form-control"
                                                min="1" max="8">
                                        </div>
                                    </div>

                                    <!-- Hidden is_wajib (bukan status_mk) -->
                                    <input type="hidden" name="mata_kuliah[0][is_wajib]" id="is_wajib_hidden"
                                        value="0">
                                    <input type="hidden" name="mata_kuliah[0][status_mk]" id="status_mk_hidden"
                                        value="pilihan">

                                    <!-- Switch Wajib -->
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-md-3 col-form-label">
                                            Status
                                        </label>
                                        <div class="col-md-9">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_wajib_switch"
                                                    style="transform: scale(2.0); cursor:pointer;">
                                                <label class="form-check-label ps-2" for="is_wajib_switch">
                                                    Wajib
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                Jika tidak dicentang maka dianggap mata kuliah Pilihan
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i>
                                        Simpan Matakuliah Struktur MK
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ... bagian sebelumnya tidak berubah ... --}}

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Aturan Konversi Mata Kuliah ke Struktur MK Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0" id="konversiTable">
                                <thead>
                                    <tr>
                                        <th>Struktur MK Asal</th>
                                        <th>Mata Kuliah Asal</th>
                                        <th>Mata Kuliah Tujuan</th>
                                        <th>Status</th>
                                        <th>Min Bobot</th>
                                        <th>Catatan</th>
                                        <th style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="konversiTableBody">
                                    @forelse ($konversiMataKuliah as $rule)
                                        <tr data-rule='@json($rule)'>
                                            <td>{{ ($rule['kurikulum_asal']['kode_kurikulum'] ?? '') . ' | ' . ($rule['kurikulum_asal']['nama_struktur_mk'] ?? $rule['kurikulum_asal']['nama_kurikulum'] ?? '-') }}</td>
                                            <td>{{ ($rule['mata_kuliah_asal']['kode_mk'] ?? '') . ' - ' . ($rule['mata_kuliah_asal']['nama_mk'] ?? '-') }}</td>
                                            <td>{{ ($rule['mata_kuliah_tujuan']['kode_mk'] ?? '') . ' - ' . ($rule['mata_kuliah_tujuan']['nama_mk'] ?? '-') }}</td>
                                            <td><span class="badge bg-{{ ($rule['status_konversi'] ?? '') === 'diakui' ? 'success' : (($rule['status_konversi'] ?? '') === 'wajib_ulang' ? 'warning' : 'secondary') }}">{{ strtoupper($rule['status_konversi'] ?? '-') }}</span></td>
                                            <td>{{ $rule['min_bobot_nilai'] ?? '-' }}</td>
                                            <td>{{ $rule['catatan'] ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm edit-konversi-btn">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm delete-konversi-btn" data-id="{{ $rule['id'] }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada aturan konversi mata kuliah untuk struktur MK ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-primary">
                                    <tr class="text-center align-middle">
                                        <th rowspan="2" style="width:5%">No</th>
                                        <th rowspan="2" style="width:5%">Kode MK</th>
                                        <th rowspan="2" style="width:20%">Mata Kuliah</th>
                                        <th colspan="5">Bobot Mata Kuliah (SKS)</th>
                                        <th rowspan="2" style="width:2%">Semester</th>
                                        <th rowspan="2" style="width:2%">Wajib?</th>
                                        <th rowspan="2" style="width:5%"></th>
                                    </tr>
                                    <tr class="text-center align-middle">
                                        <th>Mata Kuliah</th>
                                        <th>Tatap Muka</th>
                                        <th>Praktikum</th>
                                        <th>Prakt Lapangan</th>
                                        <th>Simulasi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalSks = 0;
                                        $totalTatapMuka = 0;
                                        $totalPraktikum = 0;
                                        $totalPraktekLapangan = 0;
                                        $totalSimulasi = 0;

                                        // 🔥 GROUP BY SEMESTER
                                        $grouped = collect($mataKuliahDiKurikulum)
                                            ->groupBy(function ($item) {
                                                return $item['pivot']['semester_ke'] ?? 'Tanpa Semester';
                                            })
                                            ->sortKeys();
                                    @endphp

                                    @forelse ($grouped as $semester => $items)

                                        @php
                                            $no = 1; // ✅ RESET PER SEMESTER
                                        @endphp

                                        {{-- 🔵 HEADER SEMESTER --}}
                                        <tr class="table-secondary">
                                            <td colspan="11" class="fw-bold text-start">
                                                Semester {{ $semester }}
                                            </td>
                                        </tr>

                                        @foreach ($items as $mk)
                                            @php
                                                $sks = $mk['sks'] ?? 0;
                                                $tatapMuka = $mk['sks_tatap_muka'] ?? 0;
                                                $praktikum = $mk['sks_praktikum'] ?? 0;
                                                $praktekLapangan = $mk['sks_praktek_lapangan'] ?? 0;
                                                $simulasi = $mk['sks_simulasi'] ?? 0;

                                                $totalSks += $sks;
                                                $totalTatapMuka += $tatapMuka;
                                                $totalPraktikum += $praktikum;
                                                $totalPraktekLapangan += $praktekLapangan;
                                                $totalSimulasi += $simulasi;
                                            @endphp

                                            <tr>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td class="text-center fw-semibold">{{ $mk['kode_mk'] }}</td>
                                                <td>{{ $mk['nama_mk'] }}</td>
                                                <td class="text-center">{{ $sks }}</td>
                                                <td class="text-center">{{ $tatapMuka }}</td>
                                                <td class="text-center">{{ $praktikum }}</td>
                                                <td class="text-center">{{ $praktekLapangan }}</td>
                                                <td class="text-center">{{ $simulasi }}</td>
                                                <td class="text-center">{{ $semester }}</td>
                                                <td class="text-center">
                                                    <i class="fas {{ $mk['pivot']['is_wajib'] ? 'fa-check text-success' : 'fa-times text-danger' }}"
                                                        title="{{ $mk['pivot']['is_wajib'] ? 'Wajib' : 'Pilihan' }}">
                                                    </i>
                                                </td>
                                                <td class="text-center">
                                                    <form
                                                        action="{{ route('kurikulum.hapus-mata-kuliah', [$kurikulum['id'], $mk['id']]) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- 🔥 TOTAL PER SEMESTER --}}
                                        @php
                                            $totalSemester = collect($items)->sum('sks');
                                            $totalTatapMuka = collect($items)->sum('sks_tatap_muka');
                                            $totalPraktikum = collect($items)->sum('sks_praktikum');
                                            $totalPraktekLapangan = collect($items)->sum('sks_praktek_lapangan');
                                            $totalSimulasi = collect($items)->sum('sks_simulasi');
                                        @endphp
                                        <tr class="table-light fw-bold">
                                            <td colspan="3" class="text-end">Total Semester {{ $semester }}</td>
                                            <td class="text-center">{{ $totalSemester }}</td>
                                            <td class="text-center">{{ $totalTatapMuka }}</td>
                                            <td class="text-center">{{ $totalPraktikum }}</td>
                                            <td class="text-center">{{ $totalPraktekLapangan }}</td>
                                            <td class="text-center">{{ $totalSimulasi }}</td>
                                            <td colspan="7"></td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted py-4">
                                                Data mata kuliah belum tersedia
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- 🔥 TOTAL KESELURUHAN --}}
                                <tfoot class="table-primary fw-bold">
                                    <tr class="text-center align-middle">
                                        <td colspan="3" class="text-end">TOTAL SEMUA SKS</td>
                                        <td>{{ $totalSks }}</td>
                                        <td>{{ $totalTatapMuka }}</td>
                                        <td>{{ $totalPraktikum }}</td>
                                        <td>{{ $totalPraktekLapangan }}</td>
                                        <td>{{ $totalSimulasi }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ... bagian selanjutnya tidak berubah ... --}}
    </div>

    <div class="modal fade" id="konversiMatkulModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="konversiMatkulForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="konversiModalTitle">Tambah Aturan Konversi Struktur MK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="konversi_id">
                        <input type="hidden" id="id_kurikulum_tujuan" value="{{ $kurikulum['id'] }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Struktur MK Asal</label>
                                <select class="form-control" id="id_kurikulum_asal" required>
                                    <option value="">-- Pilih Struktur MK Asal --</option>
                                    @foreach ($kurikulum_lain as $k)
                                        <option value="{{ $k['id'] }}">{{ $formatStrukturKurikulumLabel($k) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mata Kuliah Asal</label>
                                <select class="form-control" id="id_mata_kuliah_asal" required>
                                    <option value="">-- Pilih Mata Kuliah Asal --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mata Kuliah Tujuan</label>
                                <select class="form-control" id="id_mata_kuliah_tujuan" required>
                                    <option value="">-- Pilih Mata Kuliah Tujuan --</option>
                                    @foreach ($mataKuliahDiKurikulum as $mk)
                                        <option value="{{ $mk['id'] }}">{{ ($mk['kode_mk'] ?? '') . ' - ' . ($mk['nama_mk'] ?? '-') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Konversi</label>
                                <select class="form-control" id="status_konversi" required>
                                    <option value="diakui">Diakui</option>
                                    <option value="wajib_ulang">Wajib Ulang</option>
                                    <option value="pilihan_bebas">Pilihan Bebas</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minimal Bobot Nilai</label>
                                <input type="number" class="form-control" id="min_bobot_nilai" min="0" max="4" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitKonversiBtn">
                            <i class="fas fa-save me-1"></i>Simpan Aturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <form id="deleteKurikulumDetailForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
    {{-- <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script> --}}
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk menangani toggle switch
        function handleSwitchChange() {
            const switchElement = document.getElementById('is_wajib_switch');
            if (!switchElement) return;

            const isWajibInput = document.getElementById('is_wajib_hidden');
            const statusMkInput = document.getElementById('status_mk_hidden');

            if (isWajibInput && statusMkInput) {
                if (switchElement.checked) {
                    isWajibInput.value = '1';
                    statusMkInput.value = 'wajib';
                } else {
                    isWajibInput.value = '0';
                    statusMkInput.value = 'pilihan';
                }
            }
        }

        // Event listener untuk switch
        document.addEventListener('DOMContentLoaded', function() {
            function filterKurikulumIndukByProdi() {
                const selectedProdi = $('#id_prodi').val();
                const indukSelect = $('#id_kurikulum_induk');
                const currentValue = indukSelect.val();

                indukSelect.find('option').each(function() {
                    const option = $(this);
                    const optionProdi = option.data('prodi');

                    if (!option.val()) {
                        option.prop('hidden', false);
                        return;
                    }

                    option.prop('hidden', selectedProdi && optionProdi !== selectedProdi);
                });

                if (currentValue && indukSelect.find(`option[value="${currentValue}"]`).prop('hidden')) {
                    indukSelect.val(null).trigger('change');
                }
            }

            function renderKurikulumIndukSummary() {
                const selectedOption = $('#id_kurikulum_induk option:selected');
                if (!selectedOption.length || !selectedOption.val()) {
                    $('#kurikulumIndukSummary').text('Pilih tahun kurikulum untuk melihat ringkasan jenis, tahun, dan mulai berlakunya.');
                    return;
                }

                const parts = [
                    selectedOption.data('kode'),
                    selectedOption.data('tahun') ? `Tahun ${selectedOption.data('tahun')}` : null,
                    selectedOption.data('jenis') ? `Jenis ${selectedOption.data('jenis')}` : null,
                    selectedOption.data('mulai-berlaku') ? `Mulai ${selectedOption.data('mulai-berlaku')}` : null,
                    selectedOption.data('keterangan') ? `Ket: ${selectedOption.data('keterangan')}` : null,
                ].filter(Boolean);

                $('#kurikulumIndukSummary').text(parts.join(' | ') || 'Ringkasan tahun kurikulum belum tersedia.');
            }

            const konversiModalElement = document.getElementById('konversiMatkulModal');
            const konversiModal = konversiModalElement ? new bootstrap.Modal(konversiModalElement) : null;
            const targetCourseOptions = @json($mataKuliahDiKurikulum ?? []);

            const switchElement = document.getElementById('is_wajib_switch');
            if (switchElement) {
                switchElement.addEventListener('change', handleSwitchChange);

                // Panggil fungsi sekali untuk inisialisasi awal
                handleSwitchChange();
            }

            $('#id_prodi').on('change', function() {
                filterKurikulumIndukByProdi();
            });

            $('#id_kurikulum_induk').on('change', function() {
                renderKurikulumIndukSummary();
            });

            filterKurikulumIndukByProdi();
            renderKurikulumIndukSummary();

            // Inisialisasi select2 untuk modal
            $('#modalTambahMatkul').on('shown.bs.modal', function() {
                $('.select2', this).each(function() {
                    $(this).select2({
                        width: '100%',
                        dropdownParent: $(this).closest('.modal')
                    });
                });
            });

            // Handler untuk tombol delete
            $(document).off('click', '.delete-btn').on('click', '.delete-btn', function(event) {
                event.preventDefault();
                event.stopPropagation();

                const id = $(this).data('id');
                const nama = $(this).data('nama');

                if (!id || !nama) {
                    console.error('Missing required data attributes');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus kurikulum "${nama}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#deleteKurikulumDetailForm');
                        form.attr('action', "{{ route('kurikulum.destroy', '__ID__') }}".replace('__ID__', id));
                        form.trigger('submit');
                    }
                });
            });

            function resetKonversiForm() {
                $('#konversiMatkulForm')[0].reset();
                $('#konversi_id').val('');
                $('#id_mata_kuliah_asal').html('<option value="">-- Pilih Mata Kuliah Asal --</option>');
                $('#konversiModalTitle').text('Tambah Aturan Konversi Struktur MK');
                clearFormErrors('#konversiMatkulForm');
            }

            function clearFormErrors(formSelector) {
                const form = $(formSelector);
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback.dynamic-error').remove();
            }

            function applyFormErrors(fieldMap, errors = {}) {
                Object.entries(errors).forEach(([field, messages]) => {
                    const selector = fieldMap[field];
                    if (!selector) {
                        return;
                    }

                    const input = $(selector);
                    if (!input.length) {
                        return;
                    }

                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback.dynamic-error').remove();
                    input.after(
                        `<div class="invalid-feedback dynamic-error">${Array.isArray(messages) ? messages[0] : messages}</div>`
                    );
                });
            }

            function loadMataKuliahAsal(kurikulumId, selectedId = '') {
                const select = $('#id_mata_kuliah_asal');
                select.html('<option value="">Memuat mata kuliah...</option>');

                $.get("{{ route('kurikulum.mata-kuliah-json', ':id') }}".replace(':id', kurikulumId), function(res) {
                    const items = res.data || [];
                    select.html('<option value="">-- Pilih Mata Kuliah Asal --</option>');
                    items.forEach(item => {
                        select.append(`<option value="${item.id}">${item.kode_mk || ''} - ${item.nama_mk || ''}</option>`);
                    });
                    if (selectedId) {
                        select.val(selectedId);
                    }
                }).fail(function() {
                    select.html('<option value="">Gagal memuat mata kuliah asal</option>');
                });
            }

            $('#btnTambahKonversi').on('click', function() {
                resetKonversiForm();
                konversiModal.show();
            });

            $('#id_kurikulum_asal').on('change', function() {
                const id = $(this).val();
                if (id) {
                    loadMataKuliahAsal(id);
                } else {
                    $('#id_mata_kuliah_asal').html('<option value="">-- Pilih Mata Kuliah Asal --</option>');
                }
            });

            $(document).on('click', '.edit-konversi-btn', function() {
                resetKonversiForm();
                const row = $(this).closest('tr');
                const rule = row.data('rule');
                if (!rule) {
                    return;
                }

                $('#konversi_id').val(rule.id);
                $('#id_kurikulum_asal').val(rule.id_kurikulum_asal);
                $('#id_mata_kuliah_tujuan').val(rule.id_mata_kuliah_tujuan);
                $('#status_konversi').val(rule.status_konversi);
                $('#min_bobot_nilai').val(rule.min_bobot_nilai || '');
                $('#catatan').val(rule.catatan || '');
                $('#konversiModalTitle').text('Ubah Aturan Konversi Struktur MK');
                loadMataKuliahAsal(rule.id_kurikulum_asal, rule.id_mata_kuliah_asal);
                konversiModal.show();
            });

            $('#konversiMatkulForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#konversi_id').val();
                const url = id
                    ? "{{ route('kurikulum.konversi-mata-kuliah.update', ':id') }}".replace(':id', id)
                    : "{{ route('kurikulum.konversi-mata-kuliah.store') }}";
                const method = 'POST';
                const submitBtn = $('#submitKonversiBtn');
                const originalHtml = submitBtn.html();
                clearFormErrors('#konversiMatkulForm');

                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');

                $.ajax({
                    url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        ...(id ? {
                            _method: 'PUT'
                        } : {}),
                        id_kurikulum_asal: $('#id_kurikulum_asal').val(),
                        id_kurikulum_tujuan: $('#id_kurikulum_tujuan').val(),
                        id_mata_kuliah_asal: $('#id_mata_kuliah_asal').val(),
                        id_mata_kuliah_tujuan: $('#id_mata_kuliah_tujuan').val(),
                        status_konversi: $('#status_konversi').val(),
                        min_bobot_nilai: $('#min_bobot_nilai').val(),
                        catatan: $('#catatan').val()
                    },
                    success: function(res) {
                        Swal.fire('Berhasil', res.message || 'Aturan konversi berhasil disimpan.', 'success')
                            .then(() => window.location.reload());
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON || {};
                        const backendErrors = response.errors?.errors || response.errors || {};
                        applyFormErrors({
                            id_kurikulum_asal: '#id_kurikulum_asal',
                            id_kurikulum_tujuan: '#id_kurikulum_tujuan',
                            id_mata_kuliah_asal: '#id_mata_kuliah_asal',
                            id_mata_kuliah_tujuan: '#id_mata_kuliah_tujuan',
                            status_konversi: '#status_konversi',
                            min_bobot_nilai: '#min_bobot_nilai',
                            catatan: '#catatan'
                        }, backendErrors);
                        const message = response.message || 'Gagal menyimpan aturan konversi mata kuliah.';
                        Swal.fire('Gagal', message, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            $(document).on('click', '.delete-konversi-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Hapus aturan konversi ini?',
                    text: 'Aturan yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('kurikulum.konversi-mata-kuliah.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            Swal.fire('Berhasil', res.message || 'Aturan konversi berhasil dihapus.', 'success')
                                .then(() => window.location.reload());
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'Gagal menghapus aturan konversi mata kuliah.';
                            Swal.fire('Gagal', message, 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
