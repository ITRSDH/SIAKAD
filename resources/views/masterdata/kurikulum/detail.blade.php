@extends('layouts.index')
@section('title', 'Detail Kurikulum')

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
            <h3 class="fw-bold mb-3">Kurikulum Kuliah</h3>
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
                    <a href="#">Kurikulum</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Kurikulum</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Mengatur Kurikulum per Program Studi</h4>

                            <div class="d-flex gap-2">
                                <a href="{{ route('kurikulum.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </a>

                                <button type="submit" form="formubahkurikulum" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i> Ubah
                                </button>

                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                    data-id="{{ $kurikulum['id'] }}" data-nama="{{ $kurikulum['nama_kurikulum'] }}">
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

                        <form action="{{ route('kurikulum.update', $kurikulum['id'] ?? '') }}" method="POST"
                            id="formubahkurikulum">
                            @csrf
                            @if (isset($kurikulum))
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                {{-- Nama Kurikulum --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Kurikulum</label>
                                    <input type="text" name="nama_kurikulum" class="form-control"
                                        value="{{ old('nama_kurikulum', $kurikulum['nama_kurikulum'] ?? '') }}" required>
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
                                    <label class="form-label fw-semibold">Program Studi</label>
                                    <select name="id_prodi" class="form-control select2" required>
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
                                Salin data Matakuliah Kurikulum dari :
                            </span>

                            <form action="{{ route('kurikulum.clone-mata-kuliah', ['id_tujuan' => $kurikulum['id']]) }}"
                                method="POST" class="d-flex align-items-center gap-2">
                                @csrf

                                <!-- Dropdown -->
                                <div style="width:220px;">
                                    <select class="form-select select2" id="id_kurikulum_clone" name="id_kurikulum_asal"
                                        required>
                                        <option value="" selected disabled>– Pilih Kurikulum –</option>
                                        @foreach ($kurikulum_lain as $k)
                                            <option value="{{ $k['id'] }}">{{ $k['nama_kurikulum'] }}</option>
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
                                Matakuliah untuk Kurikulum
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
                                        Simpan Matakuliah Kurikulum
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
                                        <th style="width:10%">Mata Kuliah</th>
                                        <th style="width:10%">Tatap Muka</th>
                                        <th style="width:10%">Praktikum</th>
                                        <th style="width:10%">Prakt Lapangan</th>
                                        <th style="width:10%">Simulasi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalSks = 0;
                                        $totalTatapMuka = 0;
                                        $totalPraktikum = 0;
                                        $totalPraktekLapangan = 0;
                                        $totalSimulasi = 0;
                                    @endphp

                                    @forelse ($mataKuliahDiKurikulum as $mk)
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
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center fw-semibold">{{ $mk['kode_mk'] }}</td>
                                            <td class="text-center">{{ $mk['nama_mk'] }}</td>
                                            <td class="text-center">{{ $sks }}</td>
                                            <td class="text-center">{{ $tatapMuka }}</td>
                                            <td class="text-center">{{ $praktikum }}</td>
                                            <td class="text-center">{{ $praktekLapangan }}</td>
                                            <td class="text-center">{{ $simulasi }}</td>
                                            <td class="text-center">{{ $mk['pivot']['semester_ke'] ?? '-' }}</td>
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
                                                    <button type="submit" class="btn btn-danger ms-2">
                                                        <i class="fas fa-times me-1"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted py-4">
                                                Data mata kuliah belum tersedia
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- Footer Tabel untuk Total --}}

                                <tfoot class="table-light fw-bold">
                                    <tr class="text-center align-middle">
                                        <td colspan="3" class="text-end pe-3">JUMLAH SKS</td>
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
@endsection

@push('scripts-custom')
    <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script>
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
            const switchElement = document.getElementById('is_wajib_switch');
            if (switchElement) {
                switchElement.addEventListener('change', handleSwitchChange);

                // Panggil fungsi sekali untuk inisialisasi awal
                handleSwitchChange();
            }

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
            $(document).off('click', '.delete-btn').on('click', '.delete-btn', function() {
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
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ url('kurikulum') }}/" + id,
                            type: "DELETE",
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message ||
                                        'Data berhasil dihapus',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('kurikulum.index') }}";
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghapus.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.status === 404) {
                                    errorMessage = 'Data tidak ditemukan.';
                                } else if (xhr.status === 500) {
                                    errorMessage = 'Kesalahan server internal.';
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
