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
                                        <label for="default_nama_kelas" class="form-label">Nama Kelas (Default) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="default_nama_kelas"
                                            name="default_nama_kelas" placeholder="Mis. 1A, A, Reguler, 01" value="{{ old('default_nama_kelas') }}">
                                        <small class="text-muted">Diisi otomatis ke seluruh mata kuliah yang siap dibuat.</small>
                                    </div>
                                </div>
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
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="card-title mb-0">Daftar Mata Kuliah Calon Kelas</h4>
                                <span id="selectedCountBadge" class="badge bg-primary px-3 py-2 d-none">0 mata kuliah dipilih</span>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <div class="input-group input-group-sm" style="max-width: 280px;">
                                    <input type="text" class="form-control" id="bulkNamaKelasInput" placeholder="Nama kelas massal...">
                                    <button class="btn btn-outline-secondary" type="button" id="applyBulkNamaKelasBtn">Terapkan</button>
                                </div>
                                <button type="button" id="generateCreateBtn" class="btn btn-sm btn-success" disabled>
                                    <i class="fas fa-cogs me-1"></i> Generate Kelas
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="candidateTable" class="table table-bordered table-striped text-center align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="4%"><input type="checkbox" id="checkAll" title="Pilih semua yang tersedia"></th>
                                        <th width="10%">Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th width="6%">SKS</th>
                                        <th width="8%">Semester Ke</th>
                                        <th width="14%">Nama Kelas</th>
                                        <th width="10%">Kapasitas</th>
                                        <th width="18%">Status</th>
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
                $('#id_kurikulum').val('').trigger('change');
            });

            // Update placeholder nama kelas default sesuai semester_ke jika diketik
            $('#semester_ke').on('input change', function () {
                const sem = $(this).val()?.trim();
                if (sem && !$('#default_nama_kelas').val()?.trim()) {
                    $('#default_nama_kelas').attr('placeholder', 'Mis. ' + sem + 'A, ' + sem + 'B');
                }
            });

            $('#lihatMkBtn').on('click', function () {
                loadCandidates();
            });

            // Toggle checklist semua baris yang tersedia
            $('#checkAll').on('change', function () {
                const checked = $(this).is(':checked');
                const defaultNama = $('#default_nama_kelas').val()?.trim() || $('#bulkNamaKelasInput').val()?.trim() || '';

                $('#candidateTable tbody tr').each(function () {
                    const cb = $(this).find('.candidate-checkbox');
                    if (cb.length && !cb.prop('disabled')) {
                        cb.prop('checked', checked);
                        const $namaInput = $(this).find('.nama-kelas-input');
                        if (checked && !$namaInput.val().trim() && defaultNama) {
                            $namaInput.val(defaultNama);
                        }
                    }
                });

                updateGenerateButtonState();
            });

            // Terapkan nama kelas massal ke semua/tercentang
            $('#applyBulkNamaKelasBtn').on('click', function () {
                const bulkVal = $('#bulkNamaKelasInput').val().trim();
                if (!bulkVal) {
                    Swal.fire('Perhatian', 'Ketik nama kelas yang ingin diterapkan terlebih dahulu.', 'info');
                    return;
                }

                let updated = 0;
                const checkedCount = $('#candidateTable tbody .candidate-checkbox:checked').length;

                $('#candidateTable tbody tr').each(function () {
                    const cb = $(this).find('.candidate-checkbox');
                    if (cb.length && !cb.prop('disabled')) {
                        // Jika ada yang dicentang, terapkan ke yang dicentang. Jika tidak ada, terapkan ke semua yang aktif
                        if (checkedCount === 0 || cb.is(':checked')) {
                            $(this).find('.nama-kelas-input').val(bulkVal);
                            if (!cb.is(':checked')) {
                                cb.prop('checked', true);
                            }
                            updated++;
                        }
                    }
                });

                // Sinkronkan juga ke input default nama kelas di atas
                $('#default_nama_kelas').val(bulkVal);

                const totalEnabled = $('#candidateTable tbody .candidate-checkbox:not(:disabled)').length;
                const totalChecked = $('#candidateTable tbody .candidate-checkbox:checked').length;
                $('#checkAll').prop('checked', totalEnabled > 0 && totalChecked === totalEnabled);

                updateGenerateButtonState();

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nama kelas "' + bulkVal + '" diterapkan ke ' + updated + ' baris',
                    showConfirmButton: false,
                    timer: 1500
                });
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
            const defaultNamaKelas = $('#default_nama_kelas').val()?.trim() || '';
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
                default_nama_kelas: defaultNamaKelas,
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
                    '<tr><td colspan="8" class="text-muted py-4">Tidak ada mata kuliah untuk filter ini.</td></tr>'
                );
                $('#generateCreateBtn').prop('disabled', true);
                $('#selectedCountBadge').addClass('d-none');
                $('#checkAll').prop('checked', false);
                return;
            }

            const defaultNama = $('#default_nama_kelas').val()?.trim() || '';
            const defaultKapasitas = $('#default_kapasitas').val()?.trim() || '';

            rows.forEach(function (row) {
                const canEdit = row.status !== 'duplicate';
                let statusLabel = '<span class="badge bg-success">Siap dibuat</span>';
                if (row.status === 'duplicate') {
                    statusLabel = '<span class="badge bg-warning text-dark">Sudah ada (' + (row.existing_kelas?.join(', ') || 'Kelas') + ')</span>';
                } else if (row.existing_kelas && row.existing_kelas.length > 0) {
                    statusLabel += '<br><small class="text-muted">Ada kelas: ' + row.existing_kelas.join(', ') + '</small>';
                }

                const tr = $('<tr>');
                if (!canEdit) {
                    tr.addClass('row-duplicate');
                }

                const namaVal = row.nama_kelas || defaultNama;
                const kapasitasVal = row.default_kapasitas || defaultKapasitas;

                tr.append(
                    $('<td>').append(canEdit
                        ? '<input type="checkbox" class="candidate-checkbox" data-kmk="' + row.id_kurikulum_mata_kuliah + '">'
                        : ''),
                    $('<td>').text(row.kode_mk ?? '-'),
                    $('<td>').addClass('text-start').text(row.nama_mk ?? '-'),
                    $('<td>').text(row.sks ?? 0),
                    $('<td>').text(row.semester_ke ?? '-'),
                    $('<td>').append(canEdit
                        ? '<input type="text" class="form-control form-control-sm nama-kelas-input text-center" data-kmk="' + row.id_kurikulum_mata_kuliah + '" placeholder="Mis. 1A" value="' + (namaVal || '') + '">'
                        : '<span class="text-muted">' + (row.existing_kelas?.join(', ') || '-') + '</span>'),
                    $('<td>').append(canEdit
                        ? '<input type="number" class="form-control form-control-sm kapasitas-input text-center" data-kmk="' + row.id_kurikulum_mata_kuliah + '" min="1" placeholder="Kuota" value="' + (kapasitasVal || '') + '">'
                        : '<span class="text-muted">-</span>'),
                    $('<td>').html(statusLabel)
                );

                tbody.append(tr);
            });

            bindCandidateInputEvents();

            // Otomatis centang semua baris yang siap dibuat agar memudahkan user
            const $editableCheckboxes = $('#candidateTable tbody .candidate-checkbox:not(:disabled)');
            if ($editableCheckboxes.length > 0) {
                $editableCheckboxes.prop('checked', true);
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }

            updateGenerateButtonState();
        }

        function bindCandidateInputEvents() {
            $('.nama-kelas-input').off('input').on('input', function () {
                $(this).removeClass('is-invalid');
                updateGenerateButtonState();
            });

            $('.candidate-checkbox').off('change').on('change', function () {
                const defaultNama = $('#default_nama_kelas').val()?.trim() || $('#bulkNamaKelasInput').val()?.trim() || '';
                if ($(this).is(':checked')) {
                    const $namaInput = $(this).closest('tr').find('.nama-kelas-input');
                    if (!$namaInput.val().trim() && defaultNama) {
                        $namaInput.val(defaultNama);
                    }
                }

                const totalEnabled = $('#candidateTable tbody .candidate-checkbox:not(:disabled)').length;
                const totalChecked = $('#candidateTable tbody .candidate-checkbox:checked').length;
                $('#checkAll').prop('checked', totalEnabled > 0 && totalChecked === totalEnabled);

                updateGenerateButtonState();
            });
        }

        function updateGenerateButtonState() {
            const checkedCount = $('#candidateTable tbody .candidate-checkbox:checked').length;

            $('#generateCreateBtn').prop('disabled', checkedCount === 0);

            if (checkedCount > 0) {
                $('#selectedCountBadge').text(checkedCount + ' mata kuliah dipilih').removeClass('d-none');
            } else {
                $('#selectedCountBadge').addClass('d-none');
            }
        }

        function executeGenerate() {
            const idProdi = $('#id_prodi').val();
            const idKurikulum = $('#id_kurikulum').val();
            const idSemester = $('#id_semester').val();
            const semesterKe = $('#semester_ke').val();
            const defaultNama = $('#default_nama_kelas').val()?.trim() || '';
            const defaultKapasitas = $('#default_kapasitas').val()?.trim() || null;

            const rows = [];
            let missingNamaCount = 0;

            $('#candidateTable tbody tr').each(function () {
                const cb = $(this).find('.candidate-checkbox');
                if (cb.length === 0 || cb.prop('disabled') || !cb.is(':checked')) {
                    return;
                }

                const $namaInput = $(this).find('.nama-kelas-input');
                let namaKelas = $namaInput.val()?.trim() || '';
                if (!namaKelas && defaultNama) {
                    namaKelas = defaultNama;
                    $namaInput.val(namaKelas);
                }

                if (!namaKelas) {
                    missingNamaCount++;
                    $namaInput.addClass('is-invalid');
                    return;
                } else {
                    $namaInput.removeClass('is-invalid');
                }

                let kapasitas = $(this).find('.kapasitas-input').val()?.trim();
                if (!kapasitas && defaultKapasitas) {
                    kapasitas = defaultKapasitas;
                }

                rows.push({
                    id_kurikulum_mata_kuliah: cb.data('kmk'),
                    nama_kelas: namaKelas,
                    kapasitas_peserta: kapasitas ? Number(kapasitas) : null,
                });
            });

            if (rows.length === 0) {
                if (missingNamaCount > 0) {
                    Swal.fire('Perhatian', 'Nama kelas belum diisi untuk mata kuliah yang dicentang. Silakan isi nama kelas.', 'warning');
                } else {
                    Swal.fire('Perhatian', 'Centang minimal satu mata kuliah untuk dibuat kelasnya.', 'warning');
                }
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
