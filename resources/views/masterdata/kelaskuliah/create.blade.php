@extends('layouts.index')
@section('title', 'Tambah Kelas Kuliah')

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
            <h3 class="fw-bold mb-3">Tambah Kelas Kuliah</h3>
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
                    <a href="#">Kelas Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Kelas Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Kelas Kuliah</h4>

                            <div class="d-flex gap-2">

                                <a href="{{ route('kelas-kuliah.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>

                                <button type="submit" form="form-kelas-kuliah" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="form-kelas-kuliah" action="{{ route('kelas-kuliah.store') }}" method="POST">
                            @csrf
                            <!-- Form fields will be added here -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value="" disabled {{ old('id_prodi') ? '' : 'selected' }}>-- Pilih Program Studi --</option>
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
                                        <label for="id_semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value="" disabled {{ old('id_semester') ? '' : 'selected' }}>-- Pilih Semester --</option>
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
                                        <label for="id_kurikulum" class="form-label">
                                            Struktur Kurikulum <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select select2" id="id_kurikulum" required>
                                            <option value="" disabled selected>-- Pilih Program Studi Terlebih Dahulu --</option>
                                        </select>
                                        <small class="text-muted">Pilih kurikulum untuk menyaring mata kuliah yang berlaku.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                            placeholder="Mis. 01, A, Reguler, Pagi" value="{{ old('nama_kelas') }}" required>
                                        <small class="text-muted">Nama kelas dapat dibuat sama untuk paralel/sesi yang berbeda.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="id_kurikulum_mata_kuliah" class="form-label">
                                            Mata Kuliah <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select select2" id="id_kurikulum_mata_kuliah"
                                            name="id_kurikulum_mata_kuliah" required>
                                            <option value="" disabled selected>-- Pilih Struktur Kurikulum Terlebih Dahulu --</option>
                                        </select>
                                        <small class="text-muted">Mata kuliah otomatis dikelompokkan berdasarkan semester paket kurikulum.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Card Detail Mata Kuliah -->
                            <div class="row" id="mk-preview-container">
                                <div class="col-md-12">
                                    <div id="mk-preview-card" class="card border border-primary border-opacity-25 bg-light mb-3 d-none shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                    <span class="badge bg-primary fs-6 px-2 py-1" id="preview-kode-mk">-</span>
                                                    <span class="fw-bold fs-6 text-dark" id="preview-nama-mk">-</span>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap gap-1">
                                                    <span class="badge bg-secondary" id="preview-kurikulum-badge">-</span>
                                                    <span class="badge" id="preview-sifat-badge">-</span>
                                                    <span class="badge bg-info text-dark" id="preview-semester-badge">-</span>
                                                    <span class="badge" id="preview-kesesuaian-badge"></span>
                                                </div>
                                            </div>
                                            <div class="row g-2 text-center text-md-start pt-2 border-top">
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Total Bobot SKS</div>
                                                    <div class="fw-bold text-primary fs-5" id="preview-sks-total">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Tatap Muka</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-tm">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Praktikum / Lapangan</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-prak">0 SKS</div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-muted small">Simulasi</div>
                                                    <div class="fw-semibold text-secondary" id="preview-sks-sim">0 SKS</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lingkup" class="form-label">Lingkup</label>
                                        <select class="form-select select2" id="lingkup" name="lingkup">
                                            <option value="" disabled {{ old('lingkup') ? '' : 'selected' }}>-- Pilih Lingkup --</option>
                                            <option value="internal" {{ old('lingkup') == 'internal' ? 'selected' : '' }}>Internal</option>
                                            <option value="eksternal" {{ old('lingkup') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                            <option value="campuran" {{ old('lingkup') == 'campuran' ? 'selected' : '' }}>Campuran</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas_peserta" class="form-label">Kapasitas Peserta</label>
                                        <input type="number" class="form-control" id="kapasitas_peserta"
                                            name="kapasitas_peserta" min="1" placeholder="Mis. 40"
                                            value="{{ old('kapasitas_peserta') }}">
                                        <small class="text-muted">Kuota kelas ini akan dipakai untuk validasi kapasitas dan KRS.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mode_kuliah" class="form-label">Mode Kuliah</label>
                                        <select class="form-select select2" id="mode_kuliah" name="mode_kuliah">
                                            <option value="" disabled {{ old('mode_kuliah') ? '' : 'selected' }}>-- Pilih Mode --</option>
                                            <option value="online" {{ old('mode_kuliah') == 'online' ? 'selected' : '' }}>Online</option>
                                            <option value="offline" {{ old('mode_kuliah') == 'offline' ? 'selected' : '' }}>Offline</option>
                                            <option value="campuran" {{ old('mode_kuliah') == 'campuran' ? 'selected' : '' }}>Campuran</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bahasan" class="form-label">Bahasan / Topik Khusus</label>
                                        <input type="text" class="form-control" id="bahasan" name="bahasan"
                                            placeholder="Opsional" value="{{ old('bahasan') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai_efektif" class="form-label">Tanggal Mulai Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_mulai_efektif"
                                            name="tanggal_mulai_efektif" value="{{ old('tanggal_mulai_efektif') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_akhir_efektif" class="form-label">Tanggal Akhir Efektif</label>
                                        <input type="date" class="form-control" id="tanggal_akhir_efektif"
                                            name="tanggal_akhir_efektif" value="{{ old('tanggal_akhir_efektif') }}">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const rawKurikulum = @json($kurikulum);
            const rawKurikulumMk = @json($kurikulum_matakuliah);

            const $prodi = $('#id_prodi');
            const $semester = $('#id_semester');
            const $kurikulum = $('#id_kurikulum');
            const $mataKuliah = $('#id_kurikulum_mata_kuliah');

            function populateKurikulum(prodiId, selectedKurikulumId = null) {
                $kurikulum.empty();

                if (!prodiId) {
                    $kurikulum.append('<option value="" disabled selected>-- Pilih Program Studi Terlebih Dahulu --</option>');
                    $kurikulum.trigger('change.select2');
                    populateMataKuliah(null);
                    return;
                }

                const filtered = rawKurikulum.filter(item => String(item.id_prodi) === String(prodiId));

                if (filtered.length === 0) {
                    $kurikulum.append('<option value="" disabled selected>-- Tidak ada Kurikulum untuk Prodi ini --</option>');
                } else {
                    $kurikulum.append('<option value="" disabled selected>-- Pilih Struktur Kurikulum --</option>');
                    filtered.forEach(k => {
                        const label = k.kurikulum || (k.kode_kurikulum + ' - ' + (k.nama_kurikulum || k.nama_struktur_mk));
                        const isSelected = selectedKurikulumId && String(k.id) === String(selectedKurikulumId);
                        $kurikulum.append(new Option(label, k.id, false, isSelected));
                    });
                }

                if (filtered.length === 1 && !selectedKurikulumId) {
                    $kurikulum.val(filtered[0].id);
                }

                $kurikulum.trigger('change.select2');

                const activeKurId = $kurikulum.val();
                populateMataKuliah(activeKurId, null);
            }

            function populateMataKuliah(kurikulumId, selectedMkId = null) {
                $mataKuliah.empty();

                if (!kurikulumId) {
                    $mataKuliah.append('<option value="" disabled selected>-- Pilih Struktur Kurikulum Terlebih Dahulu --</option>');
                    $mataKuliah.trigger('change.select2');
                    updateMkPreview(null);
                    return;
                }

                const filtered = rawKurikulumMk.filter(item => String(item.id_kurikulum) === String(kurikulumId));

                if (filtered.length === 0) {
                    $mataKuliah.append('<option value="" disabled selected>-- Tidak ada Mata Kuliah pada Kurikulum ini --</option>');
                } else {
                    $mataKuliah.append('<option value="" disabled selected>-- Pilih Mata Kuliah --</option>');

                    // Kelompokkan per semester_ke
                    const groups = {};
                    filtered.forEach(mk => {
                        const sem = mk.semester_ke || 'Lainnya';
                        if (!groups[sem]) groups[sem] = [];
                        groups[sem].push(mk);
                    });

                    const sortedSemesters = Object.keys(groups).sort((a, b) => {
                        const numA = parseInt(a), numB = parseInt(b);
                        if (isNaN(numA) && isNaN(numB)) return a.localeCompare(b);
                        if (isNaN(numA)) return 1;
                        if (isNaN(numB)) return -1;
                        return numA - numB;
                    });

                    sortedSemesters.forEach(sem => {
                        const groupLabel = isNaN(parseInt(sem)) ? sem : `Semester ${sem}`;
                        const $optgroup = $(`<optgroup label="${groupLabel}"></optgroup>`);

                        groups[sem].sort((a, b) => (a.kode_mk || '').localeCompare(b.kode_mk || ''));

                        groups[sem].forEach(mk => {
                            const sifat = mk.status_mk ? mk.status_mk.toUpperCase() : (mk.is_wajib ? 'WAJIB' : 'PILIHAN');
                            const label = `[${mk.kode_mk || '-'}] ${mk.nama_mk || '-'} (${mk.sks || 0} SKS) - ${sifat}`;
                            const isSelected = selectedMkId && String(mk.id) === String(selectedMkId);
                            $optgroup.append(new Option(label, mk.id, false, isSelected));
                        });

                        $mataKuliah.append($optgroup);
                    });
                }

                if (selectedMkId) {
                    $mataKuliah.val(selectedMkId);
                }

                $mataKuliah.trigger('change.select2');
                updateMkPreview($mataKuliah.val());
            }

            function updateMkPreview(mkId) {
                if (!mkId) {
                    $('#mk-preview-card').addClass('d-none');
                    return;
                }

                const mk = rawKurikulumMk.find(item => String(item.id) === String(mkId));
                if (!mk) {
                    $('#mk-preview-card').addClass('d-none');
                    return;
                }

                $('#preview-kode-mk').text(mk.kode_mk || '-');
                $('#preview-nama-mk').text(mk.nama_mk || '-');
                $('#preview-kurikulum-badge').text(mk.nama_kurikulum || 'Kurikulum');

                const isWajib = mk.is_wajib || (mk.status_mk && String(mk.status_mk).toLowerCase() === 'wajib');
                $('#preview-sifat-badge')
                    .removeClass('bg-danger bg-info text-dark')
                    .addClass(isWajib ? 'bg-danger' : 'bg-info text-dark')
                    .text(isWajib ? 'Wajib' : 'Pilihan');

                $('#preview-semester-badge').text('Semester Paket ' + (mk.semester_ke || '-'));

                $('#preview-sks-total').text((mk.sks || 0) + ' SKS');
                $('#preview-sks-tm').text((mk.sks_tatap_muka || 0) + ' SKS');
                const prakSks = Number(mk.sks_praktikum || 0) + Number(mk.sks_praktek_lapangan || 0);
                $('#preview-sks-prak').text(prakSks + ' SKS');
                $('#preview-sks-sim').text((mk.sks_simulasi || 0) + ' SKS');

                // Validasi kesesuaian periode semester (Ganjil / Genap)
                const semText = $('#id_semester option:selected').text().toLowerCase();
                const mkSem = parseInt(mk.semester_ke);

                if (semText && !isNaN(mkSem)) {
                    const isGanjilSem = semText.includes('ganjil') || semText.includes('gasal');
                    const isGenapSem = semText.includes('genap');
                    const isMkGanjil = (mkSem % 2 !== 0);

                    if ((isGanjilSem && isMkGanjil) || (isGenapSem && !isMkGanjil)) {
                        $('#preview-kesesuaian-badge')
                            .removeClass('d-none bg-warning text-dark')
                            .addClass('badge bg-success')
                            .html('<i class="fas fa-check-circle me-1"></i> Sesuai Periode (' + (isMkGanjil ? 'Ganjil' : 'Genap') + ')');
                    } else if (isGanjilSem || isGenapSem) {
                        $('#preview-kesesuaian-badge')
                            .removeClass('d-none bg-success')
                            .addClass('badge bg-warning text-dark')
                            .html('<i class="fas fa-exclamation-triangle me-1"></i> Beda Periode (MK Semester ' + mkSem + ' di Semester ' + (isGanjilSem ? 'Ganjil' : 'Genap') + ')');
                    } else {
                        $('#preview-kesesuaian-badge').addClass('d-none');
                    }
                } else {
                    $('#preview-kesesuaian-badge').addClass('d-none');
                }

                $('#mk-preview-card').removeClass('d-none');
            }

            // Event Handlers
            $prodi.on('change', function() {
                populateKurikulum($(this).val(), null);
            });

            $kurikulum.on('change', function() {
                populateMataKuliah($(this).val(), null);
            });

            $mataKuliah.on('change', function() {
                updateMkPreview($(this).val());
            });

            $semester.on('change', function() {
                if ($mataKuliah.val()) {
                    updateMkPreview($mataKuliah.val());
                }
            });

            // Initial restore jika ada old input (validation redirect)
            const initialProdi = $prodi.val();
            if (initialProdi) {
                populateKurikulum(initialProdi, null);
            }
        });
    </script>
@endpush
