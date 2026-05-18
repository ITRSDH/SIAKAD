@extends('layouts.index')
@section('title', 'Detail Mata Kuliah')
@push('styles-custom')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 38px !important;
            padding: 2px 6px;
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
                    <a href="{{ route('mata-kuliah.index', $matakuliah['id_prodi']) }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mata-kuliah.create', $matakuliah['id_prodi']) }}">Tambah Mata Kuliah</a>
                </li>
            </ul>
        </div>

        {{-- INFO NOTE --}}
        <div class="card shadow-sm border">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title"> Informasi Program Studi</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mata-kuliah.index', $matakuliah['id_prodi']) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Kode Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $matakuliah['prodi']['kode_prodi'] ?? '-' }}
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
                                    : {{ $matakuliah['prodi']['nama_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Akreditasi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $matakuliah['prodi']['akreditasi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Jenjang Pendidikan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $matakuliah['prodi']['jenjang_pendidikan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Tahun Berdiri
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $matakuliah['prodi']['tahun_berdiri'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Gelar Lulusan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $matakuliah['prodi']['gelar_lulusan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- DETAIL INFORMASI MATAKULIAH --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title"> Informasi Matakuliah</h4>
                            <div class="d-flex gap-2">
                                <button type="submit" form="formEditMK" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
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
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <form id="formEditMK"
                            action="{{ route('mata-kuliah.update', [$matakuliah['id'], $matakuliah['id_prodi']]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Form fields will be added here -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kode_mk" class="form-label">Kode Mata
                                            Kuliah</label>
                                        <input type="text" class="form-control" id="kode_mk" name="kode_mk"
                                            value="{{ $matakuliah['kode_mk'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_mk" class="form-label">Nama Mata
                                            Kuliah</label>
                                        <input type="text" class="form-control" id="nama_mk" name="nama_mk"
                                            value="{{ $matakuliah['nama_mk'] ?? '' }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jenis_mk" class="form-label">Jenis Mata Kuliah</label>
                                        <select class="form-select select2" id="jenis_mk" name="jenis_mk" required>
                                            <option value="" disabled selected>Pilih Jenis Mata Kuliah</option>
                                            <option value="wajib_prodi" {{ $matakuliah['jenis_mk'] == 'wajib_prodi' ? 'selected' : '' }}>Wajib
                                                Program Studi</option>
                                            <option value="wajib_nasional" {{ $matakuliah['jenis_mk'] == 'wajib_nasional' ? 'selected' : '' }}>Wajib
                                                Nasional</option>
                                            <option value="pilihan" {{ $matakuliah['jenis_mk'] == 'pilihan' ? 'selected' : '' }}>Pilihan
                                            </option>
                                            <option value="peminatan" {{ $matakuliah['jenis_mk'] == 'peminatan' ? 'selected' : '' }}>Peminatan
                                            </option>
                                            <option value="tugas_akhir/skripsi/tesis/disertasi" {{ $matakuliah['jenis_mk'] == 'tugas_akhir/skripsi/tesis/disertasi' ? 'selected' : '' }}>
                                                Tugas Akhir/Skripsi/Tesis/Disertasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kelompok_mk" class="form-label">Kelompok Mata Kuliah</label>
                                        <select class="form-select select2" id="kelompok_mk" name="kelompok_mk" required>
                                            <option value="" disabled selected>Pilih Kelompok Mata Kuliah</option>
                                            <option value="MPK" {{ $matakuliah['kelompok_mk'] == 'MPK' ? 'selected' : '' }}>
                                                MPK (Matakuliah
                                                Pengembangan Kepribadian)</option>
                                            <option value="MKK" {{ $matakuliah['kelompok_mk'] == 'MKK' ? 'selected' : '' }}>
                                                MKK (Matakuliah
                                                Keilmuan dan Keterampilan)</option>
                                            <option value="MKB" {{ $matakuliah['kelompok_mk'] == 'MKB' ? 'selected' : '' }}>
                                                MKB (Matakuliah
                                                Keahlian Berkarya)</option>
                                            <option value="MPB" {{ $matakuliah['kelompok_mk'] == 'MPB' ? 'selected' : '' }}>
                                                MPB (Matakuliah
                                                Perilaku Berkarya)</option>
                                            <option value="MBB" {{ $matakuliah['kelompok_mk'] == 'MBB' ? 'selected' : '' }}>
                                                MBB (Matakuliah
                                                Berkehidupan Bermasyarakat)</option>
                                            <option value="MKDK" {{ $matakuliah['kelompok_mk'] == 'MKDK' ? 'selected' : '' }}>
                                                MKDK
                                                (Matakuliah Dasar Keahlian )</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks" class="form-label">SKS Mata Kuliah</label>
                                        <input type="number" class="form-control" id="sks" name="sks" placeholder="0"
                                            readonly value="{{ $matakuliah['sks'] }}">
                                        <small class="text-muted">(SKS Tatap Muka + SKS Praktikum + SKS Praktik Lapangan +
                                            SKS Simulasi)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_tatap_muka" class="form-label">Bobot SKS Tatap Muka</label>
                                        <input type="number" class="form-control" id="sks_tatap_muka" name="sks_tatap_muka"
                                            placeholder="0" min="0" value="{{ $matakuliah['sks_tatap_muka'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktikum" class="form-label">Bobot SKS Praktikum</label>
                                        <input type="number" class="form-control" id="sks_praktikum" name="sks_praktikum"
                                            placeholder="0" min="0" value="{{ $matakuliah['sks_praktikum'] ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_praktik_lapangan" class="form-label">Bobot SKS Praktik
                                            Lapangan</label>
                                        <input type="number" class="form-control" id="sks_praktek_lapangan"
                                            name="sks_praktek_lapangan" placeholder="0" min="0"
                                            value="{{ $matakuliah['sks_praktek_lapangan'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sks_simulasi" class="form-label">Bobot SKS Simulasi</label>
                                        <input type="number" class="form-control" id="sks_simulasi" name="sks_simulasi"
                                            placeholder="0" min="0" value="{{ $matakuliah['sks_simulasi'] ?? 0 }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- RENCANA PEMBELAJARAN DAN EVALUASI --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rencana Pembelajaran dan Evaluasi</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="cpl-cpmk-tab" data-bs-toggle="pill" href="#cpl-cpmk"
                                    role="tab" aria-controls="cpl-cpmk" aria-selected="true">CPL & CPMK</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="prasyarat-tab" data-bs-toggle="pill" href="#prasyarat" role="tab"
                                    aria-controls="prasyarat" aria-selected="false">Prasyarat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rps-tab" data-bs-toggle="pill" href="#rps" role="tab"
                                    aria-controls="rps" aria-selected="false">Detail RPS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rencana-pembelajaran-tab" data-bs-toggle="pill"
                                    href="#rencana-pembelajaran" role="tab" aria-controls="rencana-pembelajaran"
                                    aria-selected="false">RENCANA PEMBELAJARAN</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rencana-evaluasi-tab" data-bs-toggle="pill" href="#rencana-evaluasi"
                                    role="tab" aria-controls="rencana-evaluasi" aria-selected="false">RENCANA EVALUASI</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3 mb-3" id="line-tabContent">
                            <div class="tab-pane fade show active" id="cpl-cpmk" role="tabpanel"
                                aria-labelledby="cpl-cpmk-tab">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts. Separated they live in Bookmarksgrove
                                    right at the coast of the Semantics, a large language ocean.</p>

                                <p>A small river named Duden flows by their place and supplies it with the necessary
                                    regelialia. It is a paradisematic country, in which roasted parts of sentences
                                    fly into your mouth.</p>
                            </div>
                            <div class="tab-pane fade" id="prasyarat" role="tabpanel" aria-labelledby="prasyarat-tab">
                                @php
                                    $selectedPrasyarat = collect($prasyarat ?? [])
                                        ->map(function ($item) {
                                            return [
                                                'id' => $item['id'] ?? $item['id_mata_kuliah_prasyarat'] ?? $item['mata_kuliah_prasyarat']['id'] ?? '',
                                                'min_bobot_nilai' => $item['min_bobot_nilai'] ?? 2.00,
                                                'kode_mk' => $item['kode_mk'] ?? $item['mata_kuliah_prasyarat']['kode_mk'] ?? '-',
                                                'nama_mk' => $item['nama_mk'] ?? $item['mata_kuliah_prasyarat']['nama_mk'] ?? '-'
                                            ];
                                        })
                                        ->filter(fn($item) => !empty($item['id']))
                                        ->keyBy('id')
                                        ->all();
                                @endphp

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 h-100 bg-light">
                                            <div class="fw-semibold mb-2">Status Prasyarat</div>
                                            <div id="prasyaratStatusLabel" class="fs-5 fw-semibold">
                                                {{ count($selectedPrasyarat) > 0 ? 'Memiliki Prasyarat' : 'Tanpa Prasyarat' }}
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                Prasyarat akan dipakai backend saat memvalidasi KRS mahasiswa.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <div class="fw-semibold">Sinkronisasi Prasyarat</div>
                                                    <small class="text-muted">
                                                        Pilih mata kuliah yang wajib sudah lulus sebelum mata kuliah ini
                                                        diambil.
                                                    </small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary" id="savePrasyaratBtn">
                                                    <i class="fas fa-save me-1"></i> Simpan Prasyarat
                                                </button>
                                            </div>

                                            <div class="mb-3">
                                                <label for="prasyaratSelect" class="form-label">Daftar Mata Kuliah
                                                    Prasyarat</label>
                                                <select class="form-select select2" id="prasyaratSelect" multiple>
                                                    @foreach ($referensiPrasyarat ?? [] as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            data-kode="{{ $item['kode_mk'] ?? '-' }}"
                                                            data-nama="{{ $item['nama_mk'] ?? '-' }}" {{ isset($selectedPrasyarat[$item['id']]) ? 'selected' : '' }}>
                                                            {{ $item['kode_mk'] ?? '-' }} - {{ $item['nama_mk'] ?? '-' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="alert alert-info mb-0">
                                                Jika dibiarkan kosong, mata kuliah ini dianggap tidak memiliki prasyarat.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3 border">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Detail Prasyarat & Nilai Minimum</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="prasyaratDetailBox">
                                            @if (count($selectedPrasyarat))
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode MK</th>
                                                                <th>Nama Mata Kuliah</th>
                                                                <th style="width: 150px;">Nilai Minimum</th>
                                                                <th style="width: 80px;">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="prasyaratTableBody">
                                                            @foreach ($selectedPrasyarat as $id => $item)
                                                                <tr data-id="{{ $id }}">
                                                                    <td>{{ $item['kode_mk'] }}</td>
                                                                    <td>{{ $item['nama_mk'] }}</td>
                                                                    <td>
                                                                        <input type="number"
                                                                            class="form-control form-control-sm min-bobot-input"
                                                                            value="{{ number_format($item['min_bobot_nilai'], 2) }}"
                                                                            min="0" max="4" step="0.01" placeholder="2.00">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger remove-prasyarat-btn">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-muted">Belum ada prasyarat yang tersimpan.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="rps" role="tabpanel" aria-labelledby="rps-tab">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts. Separated they live in Bookmarksgrove
                                    right at the coast of the Semantics, a large language ocean.</p>

                                <p>A small river named Duden flows by their place and supplies it with the necessary
                                    regelialia. It is a paradisematic country, in which roasted parts of sentences
                                    fly into your mouth.</p>
                            </div>
                            <div class="tab-pane fade" id="rencana-pembelajaran" role="tabpanel"
                                aria-labelledby="rencana-pembelajaran-tab">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts. Separated they live in Bookmarksgrove
                                    right at the coast of the Semantics, a large language ocean.</p>

                                <p>A small river named Duden flows by their place and supplies it with the necessary
                                    regelialia. It is a paradisematic country, in which roasted parts of sentences
                                    fly into your mouth.</p>
                            </div>
                            <div class="tab-pane fade" id="rencana-evaluasi" role="tabpanel"
                                aria-labelledby="rencana-evaluasi-tab">
                                <p>Even the all-powerful Pointing has no control about the blind texts it is an
                                    almost unorthographic life One day however a small line of blind text by the
                                    name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
                                <p>The Big Oxmox advised her not to do so, because there were thousands of bad
                                    Commas, wild Question Marks and devious Semikoli, but the Little Blind Text
                                    didnâ€™t listen. She packed her seven versalia, put her initial into the belt
                                    and made herself on the way.
                                </p>
                            </div>
                        </div>
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
        $(document).ready(function () {
            $('#prasyaratSelect').select2({
                width: '100%',
                placeholder: 'Pilih mata kuliah prasyarat'
            });

            // Load data prasyarat yang benar saat halaman dibuka
            setTimeout(function () {
                refreshPrasyaratFromServer();
            }, 1000);

            // Fungsi untuk menghitung total SKS
            function calculateTotalSKS() {
                const tatapMuka = parseInt($('#sks_tatap_muka').val()) || 0;
                const praktikum = parseInt($('#sks_praktikum').val()) || 0;
                const praktikLapangan = parseInt($('#sks_praktek_lapangan').val()) || 0;
                const simulasi = parseInt($('#sks_simulasi').val()) || 0;

                const total = tatapMuka + praktikum + praktikLapangan + simulasi;

                $('#sks').val(total);
            }

            // Event listener untuk setiap input SKS detail
            $('#sks_tatap_muka, #sks_praktikum, #sks_praktek_lapangan, #sks_simulasi').on('input', function () {
                calculateTotalSKS();
            });

            // Validasi tambahan: pastikan SKS tidak negatif
            $('input[id^="sks_"]').on('blur', function () {
                if ($(this).val() < 0) {
                    $(this).val(0);
                    calculateTotalSKS();
                }
            });

            function refreshPrasyaratTable() {
                const selectedOptions = $('#prasyaratSelect option:selected');
                const total = selectedOptions.length;

                $('#prasyaratStatusLabel').text(total ? 'Memiliki Prasyarat' : 'Tanpa Prasyarat');

                if (!total) {
                    $('#prasyaratTableBody').empty();
                    $('#prasyaratDetailBox').html('<div class="text-muted">Belum ada prasyarat yang tersimpan.</div>');
                    return;
                }

                let html = '';
                selectedOptions.each(function () {
                    const id = $(this).val();
                    const kode = $(this).data('kode');
                    const nama = $(this).data('nama');

                    html += `
                                                                            <tr data-id="${id}">
                                                                                <td>${kode}</td>
                                                                                <td>${nama}</td>
                                                                                <td>
                                                                                    <input type="number" 
                                                                                        class="form-control form-control-sm min-bobot-input" 
                                                                                        value="2.00" 
                                                                                        min="0" 
                                                                                        max="4" 
                                                                                        step="0.01"
                                                                                        placeholder="2.00">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-sm btn-danger remove-prasyarat-btn">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        `;
                });

                $('#prasyaratTableBody').html(html);

                // Show table if hidden
                if ($('#prasyaratDetailBox .text-muted').length > 0) {
                    $('#prasyaratDetailBox').html(`
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Kode MK</th>
                                                                                            <th>Nama Mata Kuliah</th>
                                                                                            <th style="width: 150px;">Nilai Minimum</th>
                                                                                            <th style="width: 80px;">Aksi</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="prasyaratTableBody">
                                                                                        ${html}
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        `);
                }
            }

            function removePrasyarat(id) {
                $('#prasyaratSelect option[value="' + id + '"]').prop('selected', false);
                $('#prasyaratSelect').trigger('change');
            }

            function getPrasyaratData() {
                const data = [];
                $('#prasyaratTableBody tr').each(function () {
                    const id = $(this).data('id');
                    let minBobot = $(this).find('.min-bobot-input').val() || 2.00;

                    // Ensure minBobot is a number
                    minBobot = parseFloat(minBobot);
                    if (isNaN(minBobot)) {
                        minBobot = 2.00;
                    }

                    data.push({
                        id: id,
                        min_bobot_nilai: minBobot
                    });
                });
                return data;
            }

            function refreshPrasyaratFromServer() {
                $.ajax({
                    url: "{{ route('mata-kuliah.prasyarat', $matakuliah['id']) }}",
                    method: 'GET',
                    success: function (response) {
                        if (response.success && response.data) {
                            // Update select2 options
                            updateSelectOptions(response.data);

                            // Update table with current values
                            updateTableWithData(response.data);
                        }
                    }
                });
            }

            function updateSelectOptions(prasyaratData) {
                // Clear current selection
                $('#prasyaratSelect option').prop('selected', false);

                // Select options based on server data
                prasyaratData.forEach(function (item) {
                    const id = item.id_mata_kuliah_prasyarat || item.id;
                    $('#prasyaratSelect option[value="' + id + '"]').prop('selected', true);
                });

                // Trigger change to update table
                $('#prasyaratSelect').trigger('change');
            }

            function updateTableWithData(prasyaratData) {
                // Update min_bobot_nilai values in table
                prasyaratData.forEach(function (item) {
                    const id = item.id_mata_kuliah_prasyarat || item.id;
                    let minBobot = item.min_bobot_nilai || 2.00;

                    // Ensure minBobot is a number
                    minBobot = parseFloat(minBobot);
                    if (isNaN(minBobot)) {
                        minBobot = 2.00;
                    }

                    $('#prasyaratTableBody tr[data-id="' + id + '"] .min-bobot-input').val(minBobot.toFixed(2));
                });
            }

            $('#prasyaratSelect').on('change', refreshPrasyaratTable);

            // Event delegation for dynamically added elements
            $(document).on('click', '.remove-prasyarat-btn', function () {
                const id = $(this).closest('tr').data('id');
                removePrasyarat(id);
            });

            // Validate min_bobot_nilai input
            $(document).on('input', '.min-bobot-input', function () {
                let value = parseFloat($(this).val());

                if (isNaN(value) || value < 0) {
                    $(this).val(0);
                } else if (value > 4) {
                    $(this).val(4);
                } else {
                    $(this).val(value.toFixed(2));
                }
            });

            $('#savePrasyaratBtn').on('click', function () {
                const button = $(this);
                const prasyaratData = getPrasyaratData();

                $.ajax({
                    url: "{{ route('mata-kuliah.prasyarat.update', $matakuliah['id']) }}",
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        prasyarat: prasyaratData
                    }),
                    beforeSend: function () {
                        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan');
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: response.success === false ? 'warning' : 'success',
                            title: response.success === false ? 'Perlu Perhatian' : 'Berhasil',
                            text: response.message || 'Prasyarat berhasil disimpan',
                            timer: 2200,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Gagal menyimpan prasyarat'
                        });
                    },
                    complete: function () {
                        button.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Prasyarat');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var hash = window.location.hash;
            if (hash) {
                var triggerEl = document.querySelector('a[href="' + hash + '"]');
                if (triggerEl) {
                    var tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }
        });
    </script>
@endpush