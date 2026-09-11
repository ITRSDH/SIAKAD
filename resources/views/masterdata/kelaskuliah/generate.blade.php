@extends('layouts.index')
@section('title', 'Generate Kelas Kuliah Massal')

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

        #candidateTable input.candidate-checkbox {
            cursor: pointer;
        }

        #candidateTable input.nama-kelas-input {
            min-width: 100px;
        }

        #candidateTable tr.row-duplicate {
            background-color: rgba(220, 53, 69, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Generate Kelas Kuliah Massal</h3>
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
                    <a href="{{ route('kelas-kuliah.index') }}">Kelas Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Generate Kelas</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Pilih Filter</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kelas-kuliah.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="button" id="lihatMkBtn" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search me-1"></i> Lihat MK
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="form-generate" onsubmit="return false;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select select2" id="id_prodi" name="id_prodi" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}">{{ $p['prodi'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="id_kurikulum" class="form-label">Kurikulum</label>
                                        <select class="form-select select2" id="id_kurikulum" name="id_kurikulum" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($kurikulum as $k)
                                                <option value="{{ $k['id'] }}"
                                                    data-prodi="{{ $k['id_prodi'] }}">
                                                    {{ $k['kurikulum'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Semester (Target)</label>
                                        <select class="form-select select2" id="id_semester" name="id_semester" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($semester as $s)
                                                <option value="{{ $s['id'] }}">{{ $s['semester'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="semester_ke" class="form-label">Semester Ke</label>
                                        <input type="number" class="form-control" id="semester_ke" name="semester_ke"
                                            min="1" max="14" placeholder="1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="default_kapasitas" class="form-label">Kapasitas Default (opsional)</label>
                                        <input type="number" class="form-control" id="default_kapasitas"
                                            name="default_kapasitas" min="1" placeholder="Mis. 40">
                                        <small class="text-muted">Diisi otomatis ke setiap kelas baru bila kosong per baris.</small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-none" id="candidateSection">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Daftar Mata Kuliah Calon Kelas</h4>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" id="generateCreateBtn" class="btn btn-sm btn-success" disabled>
                                    <i class="fas fa-cogs me-1"></i> Generate Kelas
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="candidateTable" class="table table-bordered table-striped text-center" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="4%"><input type="checkbox" id="checkAll" title="Pilih semua yang tersedia"></th>
                                        <th width="10%">Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th width="6%">SKS</th>
                                        <th width="8%">Semester Ke</th>
                                        <th width="12%">Nama Kelas</th>
                                        <th width="10%">Kapasitas</th>
                                        <th width="16%">Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        let candidateRows = [];

        $(document).ready(function () {
            // Filter dropdown kurikulum by prodi terpilih
            $('#id_prodi').on('change', function () {
                const prodiId = $(this).val();
                $('#id_kurikulum').find('option').each(function () {
                    const isSelected = $(this).data('prodi') === prodiId || !prodiId;
                    $(this).prop('disabled', !isSelected);
                });
                // Reset pilihan kurikulum bila prodi berubah
                $('#id_kurikulum').val('').trigger('change');
            });

            $('#lihatMkBtn').on('click', function () {
                loadCandidates();
            });

            $('#checkAll').on('change', function () {
                const checked = $(this).is(':checked');
                $('#candidateTable tbody tr').each(function () {
                    const cb = $(this).find('.candidate-checkbox');
                    const namaKelas = $(this).find('.nama-kelas-input').val().trim();
                    // Hanya toggle baris yang diizinkan (will_create) & berisi nama kelas
                    if (!cb.prop('disabled') && namaKelas) {
                        cb.prop('checked', checked);
                    }
                });
                updateGenerateButtonState();
            });

            $('#generateCreateBtn').on('click', function () {
                executeGenerate();
            });
        });

        function loadCandidates() {
            const idProdi = $('#id_prodi').val();
            const idKurikulum = $('#id_kurikulum').val();
            const idSemester = $('#id_semester').val();
            const semesterKe = $('#semester_ke').val();
            const defaultKapasitas = $('#default_kapasitas').val() || '';

            if (!idProdi || !idKurikulum || !idSemester || !semesterKe) {
                Swal.fire('Perhatian', 'Lengkapi filter (Prodi, Kurikulum, Semester, Semester Ke) terlebih dahulu.', 'warning');
                return;
            }

            const btn = $('#lihatMkBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

            $.get('{{ route('kelas-kuliah.generate.candidates') }}', {
                id_prodi: idProdi,
                id_kurikulum: idKurikulum,
                id_semester: idSemester,
                semester_ke: semesterKe,
                default_kapasitas: defaultKapasitas,
            })
                .done(function (response) {
                    candidateRows = response.data ?? [];
                    renderCandidates(candidateRows);
                    $('#candidateSection').removeClass('d-none');
                })
                .fail(function (xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Swal.fire('Validasi', Object.values(errors).flat().join('\n'), 'warning');
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message ?? 'Gagal memuat daftar MK.', 'error');
                    }
                })
                .always(function () {
                    btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i> Lihat MK');
                });
        }

        function renderCandidates(rows) {
            const tbody = $('#candidateTable tbody');
            tbody.empty();

            if (!Array.isArray(rows) || rows.length === 0) {
                tbody.append(
                    '<tr><td colspan="8" class="text-muted">Tidak ada mata kuliah untuk filter ini.</td></tr>'
                );
                $('#generateCreateBtn').prop('disabled', true);
                return;
            }

            rows.forEach(function (row) {
                const canEdit = row.status === 'will_create';
                const statusLabel = row.status === 'duplicate'
                    ? '<span class="badge bg-warning">Sudah ada</span>'
                    : '<span class="badge bg-success">Siap dibuat</span>';

                const tr = $('<tr>');
                if (!canEdit) {
                    tr.addClass('row-duplicate');
                }

                tr.append(
                    $('<td>').append(canEdit
                        ? '<input type="checkbox" class="candidate-checkbox" data-kmk="' + row.id_kurikulum_mata_kuliah + '">'
                        : ''),
                    $('<td>').text(row.kode_mk ?? '-'),
                    $('<td>').text(row.nama_mk ?? '-'),
                    $('<td>').text(row.sks ?? 0),
                    $('<td>').text(row.semester_ke ?? '-'),
                    $('<td>').append(canEdit
                        ? '<input type="text" class="form-control form-control-sm nama-kelas-input" data-kmk="' + row.id_kurikulum_mata_kuliah + '" placeholder="Mis. 1A">'
                        : '<span class="text-muted">-</span>'),
                    $('<td>').append(canEdit
                        ? '<input type="number" class="form-control form-control-sm kapasitas-input" data-kmk="' + row.id_kurikulum_mata_kuliah + '" min="1" placeholder="Otomatis/default">'
                        : '<span class="text-muted">-</span>'),
                    $('<td>').html(statusLabel)
                );

                tbody.append(tr);
            });

            // Isi kapasitas default bila diset
            const defaultKapasitas = $('#default_kapasitas').val();
            if (defaultKapasitas) {
                $('.kapasitas-input').val(defaultKapasitas);
            }

            bindCandidateInputEvents();
            updateGenerateButtonState();
        }

        function bindCandidateInputEvents() {
            $('.nama-kelas-input').on('input', function () {
                updateGenerateButtonState();
            });
            $('.candidate-checkbox').on('change', function () {
                updateGenerateButtonState();
            });
        }

        function updateGenerateButtonState() {
            const hasEditableNama = $('#candidateTable .nama-kelas-input').filter(function () {
                return $(this).val().trim() !== '';
            }).length > 0;

            $('#generateCreateBtn').prop('disabled', !hasEditableNama);
        }

        function executeGenerate() {
            const idProdi = $('#id_prodi').val();
            const idKurikulum = $('#id_kurikulum').val();
            const idSemester = $('#id_semester').val();
            const semesterKe = $('#semester_ke').val();

            const rows = [];
            $('#candidateTable tbody tr').each(function () {
                const cb = $(this).find('.candidate-checkbox');
                if (cb.length === 0 || cb.prop('disabled') || !cb.is(':checked')) {
                    return;
                }

                const namaKelas = $(this).find('.nama-kelas-input').val().trim();
                if (!namaKelas) {
                    return; // baris tercentang tanpa nama = dilewati
                }

                const kapasitas = $(this).find('.kapasitas-input').val();
                rows.push({
                    id_kurikulum_mata_kuliah: cb.data('kmk'),
                    nama_kelas: namaKelas,
                    kapasitas_peserta: kapasitas ? Number(kapasitas) : null,
                });
            });

            if (rows.length === 0) {
                Swal.fire('Perhatian', 'Centang minimal satu mata kuliah dan isi nama kelasnya.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Buat Kelas Massal?',
                text: 'Kelas akan dibuat untuk ' + rows.length + ' mata kuliah yang dipilih.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Generate',
                cancelButtonText: 'Batal',
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                const btn = $('#generateCreateBtn');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');

                $.post('{{ route('kelas-kuliah.generate.create') }}', {
                    _token: '{{ csrf_token() }}',
                    id_prodi: idProdi,
                    id_kurikulum: idKurikulum,
                    id_semester: idSemester,
                    semester_ke: semesterKe,
                    rows: rows,
                })
                    .done(function (response) {
                        const summary = response?.data?.summary ?? {};
                        const created = summary.created_count ?? 0;
                        const skipped = summary.skipped_count ?? 0;
                        const failed = summary.failed_count ?? 0;

                        let msg = 'Kelas berhasil dibuat: ' + created;
                        if (skipped > 0) msg += '\nDilewati (sudah ada): ' + skipped;
                        if (failed > 0) msg += '\nGagal: ' + failed;

                        Swal.fire({
                            title: 'Selesai',
                            text: msg,
                            icon: created > 0 && failed === 0 ? 'success' : 'info',
                            confirmButtonText: 'OK',
                        }).then(function () {
                            window.location.href = '{{ route('kelas-kuliah.index') }}';
                        });
                    })
                    .fail(function (xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            Swal.fire('Validasi', Object.values(errors).flat().join('\n'), 'warning');
                        } else {
                            Swal.fire('Error', xhr.responseJSON?.message ?? 'Gagal membuat kelas.', 'error');
                        }
                    })
                    .always(function () {
                        btn.prop('disabled', false).html('<i class="fas fa-cogs me-1"></i> Generate Kelas');
                    });
            });
        }
    </script>
@endpush
