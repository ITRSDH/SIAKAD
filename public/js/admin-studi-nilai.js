/* ===================================================================== */
/* Admin Studi Mahasiswa — Halaman: Input Nilai (nilai.blade.php)        */
/* Jalur Import Excel + Jalur Manual (GRID MASAL).                       */
/* Grid: baris = mahasiswa (checkbox), kolom = MK paket, isi nilai       */
/* sekali, satu tombol Simpan → endpoint baru nilai-manual/save (backend */
/* menulis langsung ke krs_detail spt import, tanpa penilaian/komponen). */
/*                                                                       */
/* Per 2026-08-24 (TASK-SIK-005): hanya mahasiswa yang KRS-nya sudah     */
/* approved (sudah melakukan KRS) yang dikembalikan oleh endpoint        */
/* nilai-manual/context; mahasiswa dengan KHS final ditandai             */
/* has_final_khs → di-stabilo hijau dan checkbox-nya dikunci (tidak      */
/* bisa dipilih/diubah). Daftar di-paginasi klien (10 per halaman).      */
/* ===================================================================== */
(function () {
    'use strict';

    const common = window.studyCommon;
    const escapeHtml = common.escapeHtml;
    const showStudyAlert = common.showStudyAlert;
    const getStudyContext = common.getStudyContext;

    // ---------- Jalur Import (guard elemen) ----------
    function syncImportFormsFromWorkspaceContext() {
        if (!$('#studyImportTemplateForm').length) {
            return;
        }

        $('#studyImportTemplateAngkatan').val($('#studyAngkatan').val());
        $('#studyImportTemplateProdi').val($('#studyProdiId').val());
        $('#studyImportTemplateSemester').val($('#studySemesterId').val());
        $('#studyImportTemplateSemesterKe').val($('#studySemesterKe').val());
        $('#studyImportUploadSemester').val($('#studySemesterId').val());
    }

    $('#studyExportTemplateBtn').on('click', function (event) {
        syncImportFormsFromWorkspaceContext();

        if (!$('#studyImportTemplateAngkatan').val() || !$('#studyImportTemplateProdi').val() || !$(
                '#studyImportTemplateSemester').val() || !$('#studyImportTemplateSemesterKe').val()) {
            event.preventDefault();
            showStudyAlert(
                'Lengkapi angkatan, program studi, semester, dan semester ke pada filter di atas sebelum mengunduh template.',
                'warning'
            );
        }
    });

    $('#studyImportUploadForm').on('submit', function (event) {
        syncImportFormsFromWorkspaceContext();

        if (!$('#studyImportUploadSemester').val()) {
            event.preventDefault();
            showStudyAlert('Pilih semester pada filter di atas sebelum mengunggah file nilai.', 'warning');
        }
    });

    // ---------- Jalur Manual — GRID MASAL ----------
    const manualPageSize = 10;
    let manualPackageClasses = []; // kolom MK paket (dari package-classes)
    let manualClasses = [];        // kolom gabungan: paket + MK ulang tersendiri
    let manualStudents = [];       // baris mahasiswa (approved KRS, dari nilai-manual/context)
    let selectedManualStudentIds = [];
    let manualPage = 1;
    let manualSearchTerm = '';

    function loadManualClasses() {
        const context = getStudyContext();

        if (!context.id_semester || !context.id_prodi || !context.semester_ke) {
            showStudyAlert('Lengkapi semester, program studi, dan semester ke pada filter sebelum memuat kelas.', 'warning');
            return;
        }

        const $btn = $('#nilaiManualLoadClassesBtn');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

        $.get(window.studyConfig.historicalPackageClassesRoute, {
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            semester_ke: context.semester_ke
        }).done(function (response) {
            manualPackageClasses = response.data ?? [];
            manualClasses = buildManualColumns();
            loadManualStudents();
        }).fail(function (xhr) {
            manualPackageClasses = [];
            manualClasses = [];
            manualStudents = [];
            selectedManualStudentIds = [];
            manualPage = 1;
            renderManualGrid();
            showStudyAlert(xhr.responseJSON?.message ?? 'Daftar kelas gagal dimuat.', 'danger');
        }).always(function () {
            $btn.prop('disabled', false).html('<i class="fas fa-sync me-1"></i> Muat Data');
        });
    }

    // Baris mahasiswa + nilai existing + status KHS final sekaligus dari
    // endpoint khusus nilai-manual/context (admin-only).
    function loadManualStudents() {
        const context = getStudyContext();

        if (!context.id_semester) {
            showStudyAlert('Pilih semester pada filter terlebih dahulu.', 'warning');
            return;
        }

        $.get(window.studyConfig.manualNilaiContextRoute, {
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan,
            semester_ke: context.semester_ke || '',
            q: manualSearchTerm
        }).done(function (response) {
            manualStudents = (response.data ?? []).map((student) => ({
                ...student,
                id: student.id || student.id_mahasiswa,
            }));
            selectedManualStudentIds = [];
            manualPage = 1;
            renderManualGrid();
        }).fail(function (xhr) {
            manualStudents = [];
            selectedManualStudentIds = [];
            manualPage = 1;
            renderManualGrid();
            showStudyAlert(xhr.responseJSON?.message ?? 'Daftar mahasiswa gagal dimuat.', 'danger');
        });
    }

    // Kolom gabungan = kolom paket + kolom MK ulang (dari kategori 'ulang' tiap
    // mahasiswa) yang belum ada di paket. MK ulang tampil sebagai kolom tersendiri
    // meski kelasnya berbeda dari kolom paket (dicocokkan via id_mata_kuliah).
    function buildManualColumns() {
        const cols = manualPackageClasses.map((course) => ({
            ...course,
            columnType: 'paket',
            columnKey: 'c-' + course.id,
        }));

        const seenMkIds = new Set(
            cols.map((c) => c.mata_kuliah?.id).filter(Boolean)
        );

        manualStudents.forEach((student) => {
            (student.courses ?? []).forEach((course) => {
                if (
                    course.category === 'ulang' &&
                    course.id_mata_kuliah &&
                    !seenMkIds.has(course.id_mata_kuliah)
                ) {
                    seenMkIds.add(course.id_mata_kuliah);
                    cols.push({
                        id: null,
                        columnType: 'ulang',
                        columnKey: 'mk-' + course.id_mata_kuliah,
                        mata_kuliah: {
                            id: course.id_mata_kuliah,
                            kode_mk: course.kode_mk,
                            nama_mk: course.nama_mk,
                            sks: course.sks,
                        },
                    });
                }
            });
        });

        return cols;
    }

    // Ambil nilai existing mahasiswa pada kolom MK tertentu.
    function getCoursePrefill(student, column) {
        const course = getCourseEntry(student, column);
        return course?.nilai_akhir ?? '';
    }

    // Entri kursus mahasiswa untuk kolom MK tertentu.
    // - Kolom paket: cocokkan persis via id_kelas_kuliah.
    // - Kolom MK ulang: cocokkan via id_mata_kuliah (agar tetap tampil meski beda kelas).
    function getCourseEntry(student, column) {
        if (column.columnType === 'ulang') {
            return (student.courses ?? []).find(
                (item) => item.id_mata_kuliah === column.mata_kuliah?.id
            );
        }
        return (student.courses ?? []).find(
            (item) => item.id_kelas_kuliah === column.id
        );
    }

    // Dynamic column heading (MK) generation + baris paginasi.
    function getFilteredManualStudents() {
        const q = manualSearchTerm.trim().toLowerCase();
        if (!q) {
            return manualStudents;
        }
        return manualStudents.filter((s) => {
            const name = (s.nama_mahasiswa || '').toLowerCase();
            const nim = (s.nim || '').toLowerCase();
            return name.includes(q) || nim.includes(q);
        });
    }

    function renderManualGrid() {
        manualClasses = buildManualColumns();
        const filteredStudents = getFilteredManualStudents();
        const isEmpty = !manualClasses.length || !filteredStudents.length;

        if (isEmpty) {
            const hasSearch = manualSearchTerm.trim() !== '';
            $('#nilaiManualGridBody').html(
                '<tr><td colspan="3" class="text-center text-muted py-4">' +
                (hasSearch
                    ? 'Tidak ada mahasiswa yang cocok dengan pencarian.'
                    : 'Tidak ada data. Lengkapi filter (semester, prodi, semester ke) lalu klik <strong>Muat Data</strong>.') +
                '</td></tr>'
            );
            $('#nilaiManualGridHead').html('');
            renderManualPager(0);
            syncManualSelectionSummary();
            return;
        }

        // Rebuild <thead> dengan kolom MK paket + MK ulang (nama MK + kode + SKS).
        const mkCols = manualClasses.map((course, i) => {
            const repeatTag = course.columnType === 'ulang'
                ? '<div><span class="badge bg-warning-subtle text-warning mt-1">Ulang</span></div>'
                : '';
            return `
                <th class="text-center" title="${escapeHtml(course.mata_kuliah?.nama_mk ?? '')} (${escapeHtml(course.mata_kuliah?.kode_mk ?? '')})">
                    <div class="small fw-bold">${escapeHtml(course.mata_kuliah?.nama_mk ?? 'MK' + (i + 1))}</div>
                    <div class="small text-muted">${escapeHtml(course.mata_kuliah?.kode_mk ?? '')} • ${course.mata_kuliah?.sks ?? 0} SKS</div>
                    ${repeatTag}
                </th>
            `;
        }).join('');
        $('#nilaiManualGridHead').html(
            '<tr>' +
            '<th style="width: 42px;"><input type="checkbox" id="nilaiManualSelectAll"></th>' +
            '<th style="min-width: 220px;">Mahasiswa</th>' + mkCols +
            '</tr>'
        );

        const totalPages = Math.max(1, Math.ceil(filteredStudents.length / manualPageSize));
        manualPage = Math.min(Math.max(1, manualPage), totalPages);
        const startIndex = (manualPage - 1) * manualPageSize;
        const pageStudents = filteredStudents.slice(startIndex, startIndex + manualPageSize);

        const rowsHtml = pageStudents.map((student) => {
            const isFinalKhs = student.has_final_khs === true;
            const isChecked = !isFinalKhs && selectedManualStudentIds.includes(student.id);
            const courseCells = manualClasses.map((course) => {
                const prefill = getCoursePrefill(student, course);
                const courseEntry = getCourseEntry(student, course);
                const notTaken = !courseEntry;
                const isRepeat = courseEntry?.category === 'ulang';

                // Mahasiswa tidak mengambil MK ini (tidak ada krs_detail untuk MK tsb).
                if (notTaken) {
                    return `
                            <td class="text-center study-not-taken-cell" title="MK tidak diambil mahasiswa">
                                <span class="small text-muted">×</span>
                            </td>
                        `;
                }

                if (isFinalKhs) {
                    const repeatBadge = isRepeat ? '<span class="badge bg-soft-warning text-warning ms-1">Ulang</span>' : '';
                    return `<td class="text-center text-muted">${prefill !== '' ? escapeHtml(prefill) : '<span class="text-muted">—</span>'}${repeatBadge}</td>`;
                }

                const repeatBadge = isRepeat
                    ? '<div class="mt-1"><span class="badge bg-warning-subtle text-warning" title="Mahasiswa mengulang mata kuliah ini">Ulang</span></div>'
                    : '';

                return `
                        <td class="text-center ${isRepeat ? 'study-repeat-cell' : ''}">
                            <input type="number"
                                class="form-control form-control-sm text-center nilai-manual-sel"
                                data-mahasiswa="${escapeHtml(student.id)}"
                                data-kelas="${escapeHtml(course.columnKey)}"
                                min="0" max="100" step="0.01"
                                placeholder="—" style="min-width:70px;"
                                value="${escapeHtml(prefill)}">
                            ${repeatBadge}
                        </td>
                    `;
            }).join('');

            // Jumlah MK ulang mahasiswa (untuk info ringkas di kolom nama).
            const repeatCount = (student.courses ?? []).filter((c) => c.category === 'ulang').length;
            const repeatInfo = repeatCount
                ? `<div class="mt-1"><span class="badge bg-warning-subtle text-warning"><i class="fas fa-rotate-left me-1"></i>Mengulang ${repeatCount} MK</span></div>`
                : '';

            return `
                    <tr class="${isFinalKhs ? 'study-final-khs-row' : ''}">
                        <td class="text-center">
                            <input type="checkbox" class="nilai-manual-row-check" value="${escapeHtml(student.id)}" ${isChecked ? 'checked' : ''} ${isFinalKhs ? 'disabled' : ''}>
                        </td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                            <div class="small text-muted">${escapeHtml(student.nim ?? '-')}${student.prodi ? ' • ' + escapeHtml(student.prodi) : ''}</div>
                            ${repeatInfo}
                            ${isFinalKhs
                                ? '<div class="mt-1"><span class="badge bg-success">KHS Final</span></div>'
                                : (student.status_approval === 'approved'
                                    ? '<div class="mt-1"><span class="badge bg-primary-soft text-primary">KRS Approved</span></div>'
                                    : '')}
                        </td>
                        ${courseCells}
                    </tr>
                `;
        }).join('');

        $('#nilaiManualGridBody').html(rowsHtml);
        renderManualPager(filteredStudents.length);
        syncManualSelectionSummary();
    }

    function renderManualPager(totalRows) {
        const totalPages = Math.max(1, Math.ceil(totalRows / manualPageSize));

        if (!manualStudents.length || !manualClasses.length) {
            $('#nilaiManualPagerInfo').text('Belum ada halaman untuk ditampilkan.');
            $('#nilaiManualPrevPage, #nilaiManualNextPage').prop('disabled', true);
            return;
        }

        $('#nilaiManualPagerInfo').text(
            `Halaman ${manualPage} dari ${totalPages} • Menampilkan mahasiswa ${Math.min(manualStudents.length, ((manualPage - 1) * manualPageSize) + 1)}–${Math.min(manualPage * manualPageSize, manualStudents.length)} dari ${totalRows}`
        );
        $('#nilaiManualPrevPage').prop('disabled', manualPage <= 1);
        $('#nilaiManualNextPage').prop('disabled', manualPage >= totalPages);
    }

    function getSelectedRowsData() {
        return selectedManualStudentIds.map((id) => {
            const student = manualStudents.find((s) => s.id === id);
            const courses = [];

            manualClasses.forEach((course) => {
                // Hanya sertakan kolom yang benar-benar diambil mahasiswa (paket
                // maupun ulang), agar kelas yang dipakai adalah id_kelas_kuliah
                // asli dari detail KRS (mencegah error "kelas tidak terdaftar").
                const entry = getCourseEntry(student, course);
                if (!entry) {
                    return;
                }

                const $input = $(`.nilai-manual-sel[data-mahasiswa="${id}"][data-kelas="${course.columnKey}"]`);
                const raw = typeof $input.val() === 'string' ? $input.val() : '';

                courses.push({
                    id_kelas_kuliah: entry.id_kelas_kuliah,
                    nilai_akhir: raw === '' ? null : raw
                });
            });

            return {
                id_mahasiswa: id,
                courses: courses
            };
        });
    }

    function syncManualSelectionSummary() {
        const count = selectedManualStudentIds.length;
        $('#nilaiManualSelSummary').text(count ? `${count} mahasiswa dipilih` : 'Belum ada mahasiswa dipilih');
        $('#nilaiManualSaveBtn').prop('disabled', count === 0).text(`Simpan Nilai (${count})`);
    }

    function toggleRowChecked(studentId, checked) {
        if (checked) {
            if (!selectedManualStudentIds.includes(studentId)) {
                selectedManualStudentIds.push(studentId);
            }
        } else {
            selectedManualStudentIds = selectedManualStudentIds.filter((id) => id !== studentId);
        }
        syncManualSelectionSummary();
    }

    $('#nilaiManualLoadClassesBtn').on('click', loadManualClasses);

    $(document).on('change', '.nilai-manual-row-check', function () {
        toggleRowChecked($(this).val(), $(this).is(':checked'));
    });

    $(document).on('click', '#nilaiManualSelectAll', function () {
        const shouldCheckAll = $(this).is(':checked');
        const selectableIds = getFilteredManualStudents()
            .filter((s) => s.has_final_khs !== true)
            .map((s) => s.id);
        selectedManualStudentIds = shouldCheckAll ? [...new Set([...selectedManualStudentIds, ...selectableIds])] : selectedManualStudentIds.filter((id) => !selectableIds.includes(id));
        $('.nilai-manual-row-check').prop('checked', shouldCheckAll);
        syncManualSelectionSummary();
    });

    // Pencarian nama / NIM mahasiswa (server-side, debounce 300ms).
    let manualSearchTimer = null;
    $('#nilaiManualSearch').on('input', function () {
        manualSearchTerm = $(this).val();
        clearTimeout(manualSearchTimer);
        manualSearchTimer = setTimeout(function () {
            manualPage = 1;
            loadManualStudents();
        }, 300);
    });

    $('#nilaiManualPrevPage').on('click', function () {
        if (manualPage > 1) {
            manualPage -= 1;
            renderManualGrid();
        }
    });

    $('#nilaiManualNextPage').on('click', function () {
        const totalPages = Math.max(1, Math.ceil(manualStudents.length / manualPageSize));
        if (manualPage < totalPages) {
            manualPage += 1;
            renderManualGrid();
        }
    });

    $('#nilaiManualSaveBtn').on('click', function () {
        const context = getStudyContext();

        if (!context.id_semester || !context.id_prodi || !context.semester_ke) {
            showStudyAlert('Lengkapi semester, program studi, dan semester ke pada filter terlebih dahulu.', 'warning');
            return;
        }

        if (!selectedManualStudentIds.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const rows = getSelectedRowsData().filter((row) =>
            (row.courses || []).some((c) => c.nilai_akhir !== null && c.nilai_akhir !== '')
        );

        if (!rows.length) {
            showStudyAlert('Isi minimal satu nilai pada mahasiswa yang dipilih sebelum menyimpan.', 'warning');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.ajax({
            url: window.studyConfig.manualNilaiSaveRoute,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': window.studyConfig.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                id_semester: context.id_semester,
                id_prodi: context.id_prodi,
                angkatan: context.angkatan,
                semester_ke: context.semester_ke,
                rows: rows
            })
        }).done(function (response) {
            const results = response.data?.results ?? [];

            const successCount = results.filter((r) => r.status === 'success').length;
            const failedCount = results.filter((r) => r.status === 'failed').length;

            $('#nilaiManualGridBody').prepend(`
                    <tr class="${failedCount ? 'study-preview-item failed' : 'study-preview-item executed'}">
                        <td colspan="${2 + manualClasses.length}">
                            <span class="badge ${failedCount ? 'bg-warning text-dark' : 'bg-success'}">
                                ${successCount} berhasil${failedCount ? `, ${failedCount} gagal` : ''}
                            </span>
                            <div class="small text-muted mt-1">${escapeHtml(response.message ?? '')}</div>
                        </td>
                    </tr>
                `);

            showStudyAlert(response.message ?? 'Nilai berhasil disimpan.', failedCount ? 'warning' : 'success');
            // Muat ulang konteks agar nilai yang barusan tersimpan tampil di grid.
            loadManualStudents();
        }).fail(function (xhr) {
            showStudyAlert(xhr.responseJSON?.message ?? 'Gagal menyimpan nilai.', 'danger');
        }).always(function () {
            const count = selectedManualStudentIds.length;
            $btn.prop('disabled', count === 0).html(`<i class="fas fa-save me-1"></i> Simpan Nilai (${count})`);
        });
    });

    // Auto-reset grid saat filter berubah.
    $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', function () {
        syncImportFormsFromWorkspaceContext();
        manualPackageClasses = [];
        manualClasses = [];
        manualStudents = [];
        selectedManualStudentIds = [];
        manualPage = 1;
        manualSearchTerm = '';
        $('#nilaiManualSearch').val('');
        renderManualGrid();
    });
})();
