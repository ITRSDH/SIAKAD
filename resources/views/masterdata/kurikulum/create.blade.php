@extends('layouts.index')
@section('title', 'Tambah Struktur Kurikulum')

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
            <h3 class="fw-bold mb-3">Tambah Struktur Kurikulum</h3>
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
                    <a href="#">Tambah Struktur Kurikulum</a>
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
                                    <i class="fas fa-sitemap me-1"></i> Kelola Tahun Kurikulum
                                </a>
                                <a href="{{ route('jenis-kurikulum.index') }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-layer-group me-1"></i> Jenis Kurikulum
                                </a>

                                <a href="{{ route('kurikulum.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>

                                <button type="submit" form="form-kurikulum" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="form-kurikulum" action="{{ route('kurikulum.store') }}" method="POST">
                            @csrf
                            <!-- Form fields will be added here -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_struktur_mk" class="form-label">Nama Struktur Kurikulum</label>
                                        <input type="text" class="form-control" id="nama_struktur_mk" name="nama_struktur_mk"
                                            value="{{ old('nama_struktur_mk') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_sks_pilihan" class="form-label">Jumlah Bobot Mata Kuliah
                                            Pilihan</label>
                                        <input type="text" class="form-control" id="jumlah_sks_pilihan"
                                            name="jumlah_sks_pilihan" placeholder="0" min="0" value="{{ old('jumlah_sks_pilihan', 0) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value="" disabled selected>Pilih Program Studi</option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}" {{ old('id_prodi') == $p['id'] ? 'selected' : '' }}>
                                                    {{ $p['prodi'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_induk" class="form-label">Tahun Kurikulum</label>
                                        <select class="form-select select2" id="id_kurikulum_induk" name="id_kurikulum_induk" required>
                                            <option value="" disabled selected>Pilih Tahun Kurikulum</option>
                                            @foreach ($kurikulumInduk as $item)
                                                <option value="{{ $item['id'] }}"
                                                    data-prodi="{{ $item['id_prodi'] }}"
                                                    data-kode="{{ $item['kode_kurikulum'] ?? '' }}"
                                                    data-tahun="{{ $item['tahun_kurikulum'] ?? '' }}"
                                                    data-keterangan="{{ $item['keterangan'] ?? $item['nama_kurikulum'] ?? '' }}"
                                                    data-mulai-berlaku="{{ $item['mulai_berlaku'] ?? '' }}"
                                                    data-jenis="{{ $item['jenis_kurikulum']['kode_jenis'] ?? '' }}"
                                                    {{ old('id_kurikulum_induk') == $item['id'] ? 'selected' : '' }}>
                                                    {{ $item['kurikulum_induk'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih master tahun kurikulum terlebih dulu. Nama struktur operasional di bawah tetap boleh dibedakan per semester implementasi.</small>
                                        <div id="kurikulumIndukSummary" class="small text-muted mt-2">Pilih tahun kurikulum untuk melihat ringkasan jenis, tahun, dan mulai berlakunya.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Mulai Berlaku</label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value="" disabled selected>Pilih Mulai Berlaku</option>
                                            @foreach ($semester as $s)
                                                <option value="{{ $s['id'] }}" {{ old('id_semester') == $s['id'] ? 'selected' : '' }}>
                                                    {{ $s['semester'] }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_sks_lulus" class="form-label">Jumlah SKS</label>
                                        <input type="number" class="form-control" id="jumlah_sks_lulus"
                                            name="jumlah_sks_lulus" placeholder="0" value="{{ old('jumlah_sks_lulus') }}" readonly>
                                        <small class="text-muted">(Jumlah SKS Pilihan + Jumlah SKS Wajib)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_sks_wajib" class="form-label">Jumlah Bobot Mata Kuliah
                                            Wajib</label>
                                        <input type="number" class="form-control" id="jumlah_sks_wajib"
                                            name="jumlah_sks_wajib" placeholder="0" min="0" value="{{ old('jumlah_sks_wajib', 0) }}" required>
                                    </div>
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
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
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

            // Fungsi untuk menghitung total SKS
            function calculateTotalSKSLulus() {
                const pilihan = parseInt($('#jumlah_sks_pilihan').val()) || 0;
                const wajib = parseInt($('#jumlah_sks_wajib').val()) || 0;

                const total = pilihan + wajib;

                $('#jumlah_sks_lulus').val(total);
            }

            // Event listener untuk setiap input SKS detail
            $('#jumlah_sks_pilihan, #jumlah_sks_wajib').on('input', function () {
                calculateTotalSKSLulus();
            });

            $('#id_prodi').on('change', function () {
                filterKurikulumIndukByProdi();
            });

            $('#id_kurikulum_induk').on('change', function () {
                renderKurikulumIndukSummary();
            });

            // Validasi tambahan: pastikan SKS tidak negatif
            $('input[id^="jumlah_sks_"]').on('blur', function () {
                if ($(this).val() < 0) {
                    $(this).val(0);
                    calculateTotalSKSLulus();
                }
            });

            filterKurikulumIndukByProdi();
            renderKurikulumIndukSummary();
        });
    </script>
@endpush
