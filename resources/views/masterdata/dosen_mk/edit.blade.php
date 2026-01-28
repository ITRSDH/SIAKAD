@extends('layouts.index')
@section('title', 'Edit Dosen Mata Kuliah')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dosen Mata Kuliah</h3>
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
                    <a href="#">Edit Data</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Edit Dosen Mata Kuliah</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dosen-mk.update', $dosen_mk['id']) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Dropdown Dosen -->
                            <div class="form-group row">
                                <label for="id_dosen" class="col-sm-2 col-form-label">Nama Dosen *</label>
                                <div class="col-sm-10">
                                    <select name="id_dosen" id="id_dosen" class="form-control select2" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach ($dosen as $item)
                                            <option value="{{ $item['id'] }}" data-nama-dosen="{{ $item['nama_dosen'] }}"
                                                {{ $dosen_mk['id_dosen'] == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['nama_dosen'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_dosen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nama Dosen Display -->
                            <div class="form-group row">
                                <label for="nama_dosen_display" class="col-sm-2 col-form-label">Nama Dosen</label>
                                <div class="col-sm-10">
                                    <input type="text" id="nama_dosen_display" name="nama_dosen_display" readonly
                                        class="form-control" value="{{ $dosen_mk['dosen']['nama_dosen'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Dropdown Kelas MK -->
                            <div class="form-group row">
                                <label for="id_kelas_mk" class="col-sm-2 col-form-label">Kelas Mata Kuliah *</label>
                                <div class="col-sm-10">
                                    <select name="id_kelas_mk" id="id_kelas_mk" class="form-control select2" required>
                                        <option value="">-- Pilih Kelas Mata Kuliah --</option>
                                        @foreach ($kelasmk as $item)
                                            <option value="{{ $item['id'] }}"
                                                data-kode-kelas="{{ $item['kode_kelas_mk'] }}"
                                                data-nama-mk="{{ $item['mata_kuliah']['nama_mk'] ?? 'N/A' }}"
                                                data-kuota="{{ $item['kuota'] ?? 'N/A' }}"
                                                data-nama-kelas="{{ $item['kelas_pararel']['nama_kelas'] ?? 'N/A' }}"
                                                data-nama-prodi="{{ $item['kelas_pararel']['prodi']['nama_prodi'] ?? 'N/A' }}"
                                                data-semester="{{ $item['semester']['nama_semester'] ?? 'N/A' }}"
                                                data-jenis-kelas="{{ $item['jenis_kelas']['nama_kelas'] ?? 'N/A' }}"
                                                data-angkatan="{{ $item['kelas_pararel']['angkatan'] ?? 'N/A' }}"
                                                {{ $dosen_mk['id_kelas_mk'] == $item['id'] ? 'selected' : '' }}>
                                                [{{ $item['kode_kelas_mk'] }}]
                                                {{ $item['mata_kuliah']['nama_mk'] ?? 'N/A' }}
                                                (Program Studi {{ $item['kelas_pararel']['prodi']['nama_prodi'] ?? 'N/A' }}
                                                -
                                                Kelas {{ $item['kelas_pararel']['nama_kelas'] ?? 'N/A' }} - Angkatan
                                                {{ $item['kelas_pararel']['angkatan'] ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas_mk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Info Otomatis -->
                            <div class="form-group row">
                                <label for="kode_kelas_display" class="col-sm-2 col-form-label">Kode Kelas</label>
                                <div class="col-sm-10">
                                    <input type="text" id="kode_kelas_display" name="kode_kelas_display" readonly
                                        class="form-control" value="{{ $dosen_mk['kelas_mk']['kode_kelas_mk'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nama_mk_display" class="col-sm-2 col-form-label">Mata Kuliah</label>
                                <div class="col-sm-10">
                                    <input type="text" id="nama_mk_display" name="nama_mk_display" readonly
                                        class="form-control"
                                        value="{{ $dosen_mk['kelas_mk']['mata_kuliah']['nama_mk'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="kelas_pararel_display" class="col-sm-2 col-form-label">Kelas Pararel</label>
                                <div class="col-sm-10">
                                    <input type="text" id="kelas_pararel_display" name="kelas_pararel_display" readonly
                                        class="form-control"
                                        value="Program Studi {{ $dosen_mk['kelas_mk']['kelas_pararel']['prodi']['nama_prodi'] ?? 'N/A' }} - Kelas {{ $dosen_mk['kelas_mk']['kelas_pararel']['nama_kelas'] ?? 'N/A' }} (Angkatan {{ $dosen_mk['kelas_mk']['kelas_pararel']['angkatan'] ?? 'N/A' }})">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="semester_display" class="col-sm-2 col-form-label">Semester</label>
                                <div class="col-sm-10">
                                    <input type="text" id="semester_display" name="semester_display" readonly
                                        class="form-control"
                                        value="{{ $dosen_mk['kelas_mk']['semester']['nama_semester'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="jenis_kelas_display" class="col-sm-2 col-form-label">Jenis Kelas</label>
                                <div class="col-sm-10">
                                    <input type="text" id="jenis_kelas_display" name="jenis_kelas_display" readonly
                                        class="form-control"
                                        value="{{ $dosen_mk['kelas_mk']['jenis_kelas']['nama_kelas'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="kuota_display" class="col-sm-2 col-form-label">Kuota</label>
                                <div class="col-sm-10">
                                    <input type="number" id="kuota_display" name="kuota_display" readonly
                                        class="form-control" value="{{ $dosen_mk['kelas_mk']['kuota'] ?? '' }}">
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Perbarui</button>
                                    <a href="{{ route('dosen-mk.index') }}" class="btn btn-secondary">Kembali</a>
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
    <script>
        function initializeAutoFill() {
            if (typeof $ !== 'undefined') {
                $(document).ready(function() {
                    // Auto fill nama dosen
                    $('#id_dosen').on('select2:select', function(e) {
                        const namaDosen = $(this).find(':selected').data('nama-dosen');
                        $('#nama_dosen_display').val(namaDosen || '');
                    });

                    // Auto fill info kelas MK
                    $('#id_kelas_mk').on('select2:select', function(e) {
                        const $selected = $(this).find(':selected');
                        $('#kode_kelas_display').val($selected.data('kode-kelas') || '');
                        $('#nama_mk_display').val($selected.data('nama-mk') || '');
                        $('#kelas_pararel_display').val(
                            'Program Studi ' + $selected.data('nama-prodi') +
                            '-Kelas ' + $selected.data('nama-kelas') +
                            ' (Angkatan ' + $selected.data('angkatan') + ')'
                        );
                        $('#semester_display').val($selected.data('semester') || '');
                        $('#jenis_kelas_display').val($selected.data('jenis-kelas') || '');
                        $('#kuota_display').val($selected.data('kuota') || '');
                    });
                });
            } else {
                setTimeout(initializeAutoFill, 100); // Ulangi jika jQuery belum siap
            }
        }

        initializeAutoFill();
    </script>
@endpush
