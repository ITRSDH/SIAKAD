@extends('layouts.index')
@section('title', 'Beban Ajar & Jadwal Dosen')
@push('styles-custom')
    <style>
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Beban Ajar & Jadwal Dosen</h3>
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
                    <a href="{{ route('dosen-mk.index') }}">Dosen Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Beban Ajar & Jadwal</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-tie me-2 text-primary"></i>
                            Informasi Dosen & Mata Kuliah
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nama Dosen:</strong>
                                    {{ $dosen_mk['dosen']['nama_dosen'] ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>NUP:</strong> {{ $dosen_mk['dosen']['nup'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Mata Kuliah:</strong>
                                    {{ $dosen_mk['kelas_mk']['mata_kuliah']['nama_mk'] ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Kelas:</strong> {{ $dosen_mk['kelas_mk']['jenis_kelas']['nama_kelas'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Program Studi:</strong>
                                    {{ $dosen_mk['kelas_mk']['kelas_pararel']['prodi']['nama_prodi'] ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Semester:</strong>
                                    {{ $dosen_mk['kelas_mk']['semester']['nama_semester'] ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Form Jadwal -->
                        <div class="form-section">
                            <h5><i class="fas fa-calendar-check me-2 text-success"></i>Buat/Edit Jadwal</h5>
                            <form id="jadwalForm" action="{{ route('jadwal.beban-ajar-dosen.store') }}" method="POST">
                                @csrf
                                @method('POST')

                                <input type="hidden" name="id_kelas_mk" value="{{ $dosen_mk['id_kelas_mk'] }}">
                                <input type="hidden" name="id_dosen" value="{{ $dosen_mk['id_dosen'] }}">
                                <input type="hidden" name="id_semester"
                                    value="{{ $dosen_mk['kelas_mk']['id_semester'] }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_ruang">Ruang *</label>
                                            <select name="id_ruang" id="id_ruang" class="form-control" required>
                                                <option value="">Pilih Ruang</option>
                                                @foreach ($ruangs as $ruang)
                                                    <option value="{{ $ruang['id'] }}"
                                                        {{ isset($existing_jadwal) && $existing_jadwal && $existing_jadwal['id_ruang'] == $ruang['id'] ? 'selected' : '' }}>
                                                        {{ $ruang['nama_ruang'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hari">Hari *</label>
                                            <select name="hari" id="hari" class="form-control" required>
                                                <option value="">Pilih Hari</option>
                                                @foreach ($hari_options as $hari)
                                                    <option value="{{ $hari }}"
                                                        {{ isset($existing_jadwal) && $existing_jadwal && $existing_jadwal['hari'] == $hari ? 'selected' : '' }}>
                                                        {{ $hari }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_mulai">Jam Mulai *</label>
                                            <select name="jam_mulai" id="jam_mulai" class="form-control" required>
                                                <option value="">Pilih Jam Mulai</option>
                                                @foreach ($jam_options as $jam)
                                                    <option value="{{ $jam }}"
                                                        {{ isset($existing_jadwal) && $existing_jadwal && $existing_jadwal['jam_mulai'] == $jam ? 'selected' : '' }}>
                                                        {{ $jam }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_selesai">Jam Selesai *</label>
                                            <select name="jam_selesai" id="jam_selesai" class="form-control" required>
                                                <option value="">Pilih Jam Selesai</option>
                                                @foreach ($jam_options as $jam)
                                                    <option value="{{ $jam }}"
                                                        {{ isset($existing_jadwal) && $existing_jadwal && $existing_jadwal['jam_selesai'] == $jam ? 'selected' : '' }}>
                                                        {{ $jam }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        {{ isset($existing_jadwal) && $existing_jadwal ? 'Update Jadwal' : 'Simpan Jadwal' }}
                                    </button>
                                    <a href="{{ route('dosen-mk.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Kembali
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Info Jadwal Saat Ini -->
                        @if (isset($existing_jadwal) && $existing_jadwal)
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Jadwal Saat Ini:</h6>
                                <p>
                                    <strong>Hari:</strong> {{ $existing_jadwal['hari'] }} |
                                    <strong>Jam:</strong> {{ $existing_jadwal['jam_mulai'] }} -
                                    {{ $existing_jadwal['jam_selesai'] }} |
                                    <strong>Ruang:</strong> {{ $existing_jadwal['ruang']['nama_ruang'] ?? 'N/A' }} -
                                    {{ $existing_jadwal['ruang']['jenis_ruang'] ?? 'N/A' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(document).ready(function() {
            // Validasi jam selesai harus lebih besar dari jam mulai
            $('#jam_mulai, #jam_selesai').change(function() {
                var jamMulai = $('#jam_mulai').val();
                var jamSelesai = $('#jam_selesai').val();

                if (jamMulai && jamSelesai) {
                    if (jamSelesai <= jamMulai) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Jam selesai harus lebih besar dari jam mulai!',
                            confirmButtonText: 'OK'
                        });
                        $('#jam_selesai').val('');
                    }
                }
            });

            // Handle form submission
            $('#jadwalForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location
                                    .reload(); // Refresh halaman untuk menampilkan data terbaru
                            });
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
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
