@extends('layouts.index')
@section('title', 'Edit Kelas Mata Kuliah')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Kelas Mata Kuliah</h3>
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
                    <a href="{{ route('kelas-mk.index') }}">Kelas Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit Kelas Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit me-2 text-warning"></i>Edit Kelas Mata Kuliah
                        </h3>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('kelas-mk.update', $id) }}" method="POST" id="kelasMkForm">
                            @csrf
                            @method('PUT')

                            {{-- Kode Kelas --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Kode Kelas MK *</label>
                                <div class="col-sm-10">
                                    <input type="text" name="kode_kelas_mk" class="form-control"
                                        value="{{ old('kode_kelas_mk', $kelasMk['kode'] ?? '') }}">
                                </div>
                            </div>

                            {{-- Kuota --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Kuota *</label>
                                <div class="col-sm-10">
                                    <input type="number" name="kuota" class="form-control"
                                        value="{{ old('kuota', $kelasMk['kuota'] ?? '') }}">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Program Studi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $filters['nama_prodi'] ?? '-' }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- Tahun & Semester --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">
                                    Tahun & Semester <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $tahunSemester['label'] ?? 'Tidak ditemukan' }}" readonly>

                                    <input type="hidden" name="id_semester"
                                        value="{{ old('id_semester', $tahunSemester['id_semester'] ?? '') }}">

                                    <small class="form-text text-muted">
                                        Tahun akademik dan semester aktif yang digunakan untuk kelas ini
                                    </small>
                                </div>
                            </div>

                            {{-- Kurikulum --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Kurikulum</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $dropdownData['kurikulum'][0]['nama_kurikulum'] ?? '-' }}" readonly>
                                    <input type="hidden" name="id_kurikulum"
                                        value="{{ $dropdownData['kurikulum'][0]['id'] ?? '' }}">
                                </div>
                            </div>

                            {{-- Filter Semester --}}
                            {{-- Filter Semester (Read Only) --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Semester</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="filter_semester"
                                        value="Semester {{ $selectedSemester }}" readonly
                                        data-semester="{{ $selectedSemester }}">
                                </div>
                            </div>


                            {{-- Mata Kuliah --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Mata Kuliah *</label>
                                <div class="col-sm-10">
                                    <select name="id_mk" id="mata-kuliah-select" class="form-select"></select>
                                </div>
                            </div>

                            {{-- Kelas Pararel --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Kelas Pararel *</label>
                                <div class="col-sm-10">
                                    <select name="id_kelas_pararel" class="form-select">
                                        @foreach ($dropdownData['kelas_pararel'] as $kp)
                                            <option value="{{ $kp['id'] }}"
                                                {{ $kelasMk['id_kelas_pararel'] == $kp['id'] ? 'selected' : '' }}>
                                                {{ $kp['nama_kelas'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Jenis Kelas --}}
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Jenis Kelas *</label>
                                <div class="col-sm-10">
                                    <select name="id_jenis_kelas" class="form-select">
                                        @foreach ($dropdownData['jenis_kelas'] as $jk)
                                            <option value="{{ $jk['id'] }}"
                                                {{ $kelasMk['id_jenis_kelas'] == $jk['id'] ? 'selected' : '' }}>
                                                {{ $jk['nama_kelas'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Update
                                </button>
                                <a href="{{ route('kelas-mk.index') }}" class="btn btn-secondary">Kembali</a>
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
        document.addEventListener('DOMContentLoaded', () => {
            const dropdown = @json($dropdownData);
            const kelasMk = @json($kelasMk);

            const mkSelect = document.getElementById('mata-kuliah-select');
            const semesterInput = document.getElementById('filter_semester');

            const semester = semesterInput.dataset.semester;
            const mkBySemester = dropdown.kurikulum[0].mata_kuliah_by_semester;

            function renderMK(semester) {
                mkSelect.innerHTML = '';
                const data = mkBySemester.find(s => s.semester == semester);

                if (!data) {
                    mkSelect.innerHTML = '<option value="">Tidak ada MK</option>';
                    return;
                }

                data.mata_kuliah.forEach(mk => {
                    const opt = document.createElement('option');
                    opt.value = mk.id;
                    opt.textContent = `[${mk.kode_mk}] ${mk.nama_mk} (${mk.sks} SKS)`;
                    if (kelasMk.id_mk === mk.id) opt.selected = true;
                    mkSelect.appendChild(opt);
                });
            }

            // Init (sekali saja)
            renderMK(semester);
        });
    </script>
@endpush
