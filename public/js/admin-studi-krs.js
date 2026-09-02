/* ===================================================================== */
/* Admin Studi Mahasiswa — Halaman: Daftarkan KRS (krs.blade.php)        */
/* Alur: muat data → pilih mahasiswa → siapkan form → build → jalankan.  */
/* ===================================================================== */
(function () {
    'use strict';

    const common = window.studyCommon;
    const escapeHtml = common.escapeHtml;
    const showStudyAlert = common.showStudyAlert;
    const showStudyLoading = common.showStudyLoading;
    const getStudyContext = common.getStudyContext;

    let historicalEligibleStudents = [];
    let historicalPackageClasses = [];
    let historicalRepeatCandidatesByStudent = {};
    let historicalBuilderActiveStudentId = null;
    let historicalEligiblePage = 1;
    let historicalBuilderPage = 1;
    const historicalPageSize = 10;
    let selectedHistoricalStudentIds = [];

    function getHistoricalBuildMode() {
        return $('#studyHistoricalBuildMode').val() || 'krs_only';
    }

    function getSelectedHistoricalStudentIds() {
        return [...selectedHistoricalStudentIds];
    }

    function getSelectedHistoricalStudents() {
        const selectedIds = getSelectedHistoricalStudentIds();
        return historicalEligibleStudents.filter((row) => selectedIds.includes(row.id));
    }

    function getFilteredHistoricalEligibleStudents(rows = historicalEligibleStudents) {
        const keyword = ($('#studyHistoricalEligibleSearch').val() || '').toLowerCase().trim();

        return (rows || []).filter((row) => {
            if (!keyword) {
                return true;
            }

            return `${row.nama_mahasiswa ?? ''} ${row.nim ?? ''}`.toLowerCase().includes(keyword);
        });
    }

    function syncHistoricalSelectAllState(filteredRows = null) {
        const currentFilteredRows = filteredRows ?? getFilteredHistoricalEligibleStudents();
        const filteredIds = currentFilteredRows.map((row) => row.id);
        const checkedCount = filteredIds.filter((id) => selectedHistoricalStudentIds.includes(id)).length;
        const totalCount = filteredIds.length;

        $('#studyHistoricalSelectAll')
            .prop('checked', totalCount > 0 && checkedCount === totalCount)
            .prop('indeterminate', checkedCount > 0 && checkedCount < totalCount);
    }

    function getHistoricalStudentProgress(row) {
        if (row.existing_historical_krs?.is_locked) {
            return { text: 'Riwayat Final', className: 'bg-success' };
        }

        if (row.existing_historical_krs) {
            return { text: 'KRS Historis Siap', className: 'bg-primary' };
        }

        if (row.default_action === 'ready') {
            return { text: 'Siap Migrasi', className: 'bg-info text-dark' };
        }

        if (row.default_action === 'skipped') {
            return { text: 'Perlu Review', className: 'bg-warning text-dark' };
        }

        return { text: 'Belum Siap', className: 'bg-danger' };
    }

    function renderHistoricalSelectionSummary() {
        const selectedStudents = getSelectedHistoricalStudents();

        if (!selectedStudents.length) {
            $('#studyHistoricalSelectionSummary').text('Belum ada mahasiswa yang dipilih.');
            return;
        }

        const names = selectedStudents.slice(0, 3).map((row) => row.nama_mahasiswa).join(', ');
        const extra = selectedStudents.length > 3 ? ` +${selectedStudents.length - 3} mahasiswa` : '';
        const filteredRows = getFilteredHistoricalEligibleStudents();
        const filteredIds = filteredRows.map((row) => row.id);
        const selectedFilteredCount = filteredIds.filter((id) => selectedHistoricalStudentIds.includes(id)).length;
        const summary = `${selectedStudents.length} mahasiswa dipilih (${selectedFilteredCount} pada hasil filter saat ini): ${names}${extra}`;

        $('#studyHistoricalSelectionSummary').text(summary);
    }

    function renderHistoricalPackageClasses(rows) {
        if (!rows || !rows.length) {
            $('#studyHistoricalPackageClasses').html(
                '<div class="text-muted">Daftar kelas belum tersedia untuk pilihan filter ini.</div>');
            return;
        }

        const totalSks = rows.reduce((sum, row) => sum + (row.mata_kuliah?.sks ?? 0), 0);

        const body = rows.map((row, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(row.mata_kuliah?.kode_mk ?? '-')}</td>
                    <td>${escapeHtml(row.mata_kuliah?.nama_mk ?? 'Mata kuliah')}</td>
                    <td>${escapeHtml(row.nama_kelas ?? 'Kelas')}</td>
                    <td>${row.mata_kuliah?.sks ?? 0}</td>
                    <td>Semester ${row.semester_ke ?? '-'}</td>
                </tr>
            `).join('');

        $('#studyHistoricalPackageClasses').html(`
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0 study-collective-table">
                    <thead>
                        <tr>
                            <th style="width: 48px;">No</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>Kelas</th>
                            <th style="width: 80px;">SKS</th>
                            <th style="width: 120px;">Semester</th>
                        </tr>
                    </thead>
                    <tbody>${body}</tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Total SKS Paket</th>
                            <th>${totalSks}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `);
    }

    function renderHistoricalEligibleStudents(rows) {
        if (!rows || !rows.length) {
            $('#studyHistoricalEligibleSummary').text('Tidak ada mahasiswa yang ditemukan untuk konteks ini.');
            $('#studyHistoricalEligibleTableBody').html(
                '<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada mahasiswa yang ditemukan untuk konteks ini.</td></tr>'
            );
            $('#studyHistoricalEligiblePagerInfo').text('Belum ada halaman untuk ditampilkan.');
            $('#studyHistoricalEligiblePrevPage, #studyHistoricalEligibleNextPage').prop('disabled', true);
            renderHistoricalSelectionSummary();
            return;
        }

        const keyword = ($('#studyHistoricalEligibleSearch').val() || '').toLowerCase().trim();
        const filteredRows = getFilteredHistoricalEligibleStudents(rows);
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / historicalPageSize));
        historicalEligiblePage = Math.min(Math.max(1, historicalEligiblePage), totalPages);
        const startIndex = (historicalEligiblePage - 1) * historicalPageSize;
        const currentRows = filteredRows.slice(startIndex, startIndex + historicalPageSize);

        $('#studyHistoricalEligibleSummary').text(
            keyword ?
            `Menampilkan ${filteredRows.length} dari ${rows.length} mahasiswa yang cocok dengan pencarian.` :
            `Menampilkan ${rows.length} mahasiswa. Gunakan pencarian jika daftarnya panjang.`
        );
        $('#studyHistoricalEligiblePagerInfo').text(
            `Halaman ${historicalEligiblePage} dari ${totalPages} • Menampilkan ${currentRows.length} mahasiswa`
        );
        $('#studyHistoricalEligiblePrevPage').prop('disabled', historicalEligiblePage <= 1);
        $('#studyHistoricalEligibleNextPage').prop('disabled', historicalEligiblePage >= totalPages);

        const html = currentRows.map((row) => {
            const existing = row.existing_historical_krs ?
                `<span class="badge ${row.existing_historical_krs.is_locked ? 'bg-primary' : 'bg-warning text-dark'}">${escapeHtml(row.existing_historical_krs.status_approval ?? 'Draft')}</span>` :
                '<span class="text-muted">Belum ada</span>';
            const statusBadge = row.default_action === 'ready' ?
                '<span class="badge bg-success">Siap</span>' :
                row.default_action === 'skipped' ?
                '<span class="badge bg-warning text-dark">Lewati</span>' :
                '<span class="badge bg-danger">Perlu Cek</span>';

            return `
                    <tr data-search="${escapeHtml(`${row.nama_mahasiswa ?? ''} ${row.nim ?? ''}`.toLowerCase())}">
                        <td><input type="checkbox" class="study-historical-student-checkbox" value="${escapeHtml(row.id)}" ${selectedHistoricalStudentIds.includes(row.id) ? 'checked' : ''}></td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa)}</div>
                            <div class="small text-muted mt-1">
                                <div><strong>Struktur:</strong> ${escapeHtml(row.kurikulum_context?.struktur_operasional?.nama_struktur_mk ?? row.nama_struktur_operasional ?? row.nama_kurikulum ?? '-')}</div>
                            </div>
                            <div class="small text-muted">${escapeHtml(row.nim)}${row.prodi?.nama_prodi ? ' • ' + escapeHtml(row.prodi.nama_prodi) : ''}</div>
                            <div class="small text-muted mt-1">${escapeHtml(row.message ?? '')}</div>
                            <div class="mt-2"><span class="badge ${getHistoricalStudentProgress(row).className}">${escapeHtml(getHistoricalStudentProgress(row).text)}</span></div>
                        </td>
                        <td>${row.semester_target ?? '-'}</td>
                        <td>${existing}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
        }).join('');

        $('#studyHistoricalEligibleTableBody').html(html);
        syncHistoricalSelectAllState(filteredRows);
        renderHistoricalSelectionSummary();
    }

    function filterHistoricalEligibleStudents() {
        historicalEligiblePage = 1;
        renderHistoricalEligibleStudents(historicalEligibleStudents);
    }

    function renderHistoricalBuilderCards() {
        const selectedStudents = getSelectedHistoricalStudents();
        const buildMode = getHistoricalBuildMode();

        if (!selectedStudents.length) {
            $('#studyHistoricalBuilderCards').html(
                '<div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form KRS</strong>.</div>'
            );
            return;
        }

        if (!historicalPackageClasses.length) {
            $('#studyHistoricalBuilderCards').html(
                '<div class="text-muted">Daftar kelas belum ditampilkan. Lengkapi program studi dan semester ke, lalu muat ulang data historis.</div>'
            );
            return;
        }

        const selectedIds = selectedStudents.map((student) => student.id);
        if (!historicalBuilderActiveStudentId || !selectedIds.includes(historicalBuilderActiveStudentId)) {
            historicalBuilderActiveStudentId = selectedIds[0];
        }
        const totalBuilderPages = Math.max(1, Math.ceil(selectedStudents.length / historicalPageSize));
        const activeStudentIndex = selectedIds.indexOf(historicalBuilderActiveStudentId);
        if (activeStudentIndex >= 0) {
            historicalBuilderPage = Math.floor(activeStudentIndex / historicalPageSize) + 1;
        }
        historicalBuilderPage = Math.min(Math.max(1, historicalBuilderPage), totalBuilderPages);
        const navStart = (historicalBuilderPage - 1) * historicalPageSize;
        const navStudents = selectedStudents.slice(navStart, navStart + historicalPageSize);

        const navigationItems = navStudents.map((student, navIndex) => {
            const isActive = student.id === historicalBuilderActiveStudentId;
            const repeatCount = (historicalRepeatCandidatesByStudent[student.id] ?? []).length;
            const actualIndex = navStart + navIndex;

            return `
                    <button type="button"
                        class="study-builder-nav-item ${isActive ? 'active' : ''}"
                        data-id-mahasiswa="${escapeHtml(student.id)}">
                        <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                        <div class="meta">${escapeHtml(student.nim)}${student.prodi?.nama_prodi ? ' • ' + escapeHtml(student.prodi.nama_prodi) : ''}</div>
                        <div class="meta mt-1">Urutan ${actualIndex + 1} dari ${selectedStudents.length} • Semester ${student.semester_target ?? '-'}</div>
                        ${repeatCount ? `<div class="meta mt-1 text-warning">Ulang gagal tersedia: ${repeatCount}</div>` : ''}
                    </button>
                `;
        }).join('');

        const cards = selectedStudents.map((student) => {
            const repeatCandidates = historicalRepeatCandidatesByStudent[student.id] ?? [];
            const isActive = student.id === historicalBuilderActiveStudentId;
            const activeIndex = selectedIds.indexOf(student.id);
            const prevDisabled = activeIndex <= 0 ? 'disabled' : '';
            const nextDisabled = activeIndex >= selectedIds.length - 1 ? 'disabled' : '';

            const packageRows = historicalPackageClasses.map((course) => `
                    <div class="study-course-row">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-5">
                                <div class="form-check">
                                    <input class="form-check-input study-course-include" type="checkbox" checked>
                                    <label class="form-check-label small fw-semibold">
                                        ${escapeHtml(course.mata_kuliah?.kode_mk ?? '-')} - ${escapeHtml(course.mata_kuliah?.nama_mk ?? 'Mata kuliah')}
                                    </label>
                                </div>
                                <div class="small text-muted mt-1">${escapeHtml(course.nama_kelas ?? 'Kelas')} • ${course.mata_kuliah?.sks ?? 0} SKS • Paket semester</div>
                                <input type="hidden" class="study-course-class-id" value="${escapeHtml(course.id)}">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label small">Nilai Akhir</label>
                                <input type="number" class="form-control form-control-sm study-course-score" min="0" max="100" step="0.01" placeholder="${buildMode === 'krs_with_scores' ? '0 - 100' : 'Opsional'}" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label small">Catatan</label>
                                <input type="text" class="form-control form-control-sm study-course-note" placeholder="Opsional" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                            </div>
                        </div>
                    </div>
                `).join('');

            const repeatRows = repeatCandidates.map((course) => {
                const options = (course.available_classes ?? []).map((item) =>
                    `<option value="${escapeHtml(item.id)}">${escapeHtml(item.nama_kelas ?? 'Kelas')} • ${item.mata_kuliah?.sks ?? 0} SKS</option>`
                ).join('');
                const failedGrade = course.riwayat_terakhir?.nilai_huruf ? `Nilai ${escapeHtml(course.riwayat_terakhir.nilai_huruf)}` : 'Belum lulus';
                const failedSemester = course.riwayat_terakhir?.semester_label ? ` • ${escapeHtml(course.riwayat_terakhir.semester_label)}` : '';

                return `
                        <div class="study-course-row border-warning-subtle bg-warning-subtle">
                            <div class="row g-2 align-items-end">
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input study-course-include" type="checkbox">
                                        <label class="form-check-label small fw-semibold">
                                            ${escapeHtml(course.kode_mk ?? '-')} - ${escapeHtml(course.nama_mk ?? 'Mata kuliah ulang')}
                                        </label>
                                    </div>
                                    <div class="small text-muted mt-1">Ulang matkul gagal • ${course.sks ?? 0} SKS</div>
                                    <div class="small text-muted">${failedGrade}${failedSemester}</div>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small">Kelas Ulang</label>
                                    <select class="form-select form-select-sm study-course-class-id">
                                        ${options}
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Nilai Akhir</label>
                                    <input type="number" class="form-control form-control-sm study-course-score" min="0" max="100" step="0.01" placeholder="${buildMode === 'krs_with_scores' ? '0 - 100' : 'Opsional'}" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small">Catatan</label>
                                    <input type="text" class="form-control form-control-sm study-course-note" placeholder="Opsional" ${buildMode === 'krs_only' ? 'disabled' : ''}>
                                </div>
                            </div>
                        </div>
                    `;
            }).join('');

            const repeatSection = repeatRows ? `
                    <div class="study-soft-box mt-3">
                        <div class="fw-semibold mb-2">Pilihan Ulang Matkul Gagal</div>
                        <div class="small text-muted mb-3">Bagian ini menampilkan mata kuliah yang sebelumnya tidak lulus dan tersedia lagi di semester target.</div>
                        <div class="d-grid gap-2">${repeatRows}</div>
                    </div>
                ` : '';

            return `
                    <div class="study-builder-card study-student-builder-card ${isActive ? '' : 'd-none'}" data-id-mahasiswa="${escapeHtml(student.id)}">
                        <div class="study-builder-toolbar">
                            <div class="summary">Form mahasiswa ${activeIndex + 1} dari ${selectedStudents.length}</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm study-builder-prev" data-id-mahasiswa="${escapeHtml(student.id)}" ${prevDisabled}>
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm study-builder-next" data-id-mahasiswa="${escapeHtml(student.id)}" ${nextDisabled}>
                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa)}</div>
                                <div class="small text-muted">${escapeHtml(student.nim)}${student.prodi?.nama_prodi ? ' • ' + escapeHtml(student.prodi.nama_prodi) : ''}</div>
                            </div>
                            <span class="badge bg-light text-dark">Semester Target ${student.semester_target ?? '-'}</span>
                        </div>
                        <div class="d-grid gap-2">${packageRows}</div>
                        ${repeatSection}
                    </div>
                `;
        }).join('');

        $('#studyHistoricalBuilderCards').html(`
                <div class="study-builder-shell">
                    <div class="study-builder-nav">
                        <div class="fw-semibold mb-2">Daftar Mahasiswa Dipilih</div>
                        <div class="small text-muted mb-3">Klik nama mahasiswa untuk berpindah form tanpa scroll panjang.</div>
                        <div class="study-builder-nav-list">${navigationItems}</div>
                        <div class="study-pager mt-3">
                            <div class="info">Halaman ${historicalBuilderPage} dari ${totalBuilderPages}</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalBuilderPrevPage" ${historicalBuilderPage <= 1 ? 'disabled' : ''}>
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="studyHistoricalBuilderNextPage" ${historicalBuilderPage >= totalBuilderPages ? 'disabled' : ''}>
                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="study-builder-stage">${cards}</div>
                </div>
            `);
    }

    function loadHistoricalRepeatCandidates(selectedStudentIds = []) {
        historicalRepeatCandidatesByStudent = {};

        if (!selectedStudentIds.length) {
            return $.Deferred().resolve().promise();
        }

        const context = getStudyContext();
        if (!context.id_semester || !context.semester_ke) {
            return $.Deferred().resolve().promise();
        }

        return $.get(window.studyConfig.historicalRepeatCandidatesRoute, {
            id_semester: context.id_semester,
            semester_ke: context.semester_ke,
            id_mahasiswa: selectedStudentIds
        }).done(function (response) {
            (response.data ?? []).forEach(function (row) {
                historicalRepeatCandidatesByStudent[row.id_mahasiswa] = row.courses ?? [];
            });
        }).fail(function (xhr) {
            historicalRepeatCandidatesByStudent = {};
            showStudyAlert(xhr.responseJSON?.message ?? 'Gagal memuat pilihan ulang mata kuliah.', 'danger');
        });
    }

    function buildHistoricalStudentsPayload() {
        const buildMode = getHistoricalBuildMode();
        const payload = [];

        $('.study-student-builder-card').each(function () {
            const $card = $(this);
            const studentId = $card.data('id-mahasiswa');
            const courses = [];
            let hasInvalidScore = false;

            $card.find('.study-course-row').each(function () {
                const $row = $(this);
                const isIncluded = $row.find('.study-course-include').is(':checked');
                const score = $row.find('.study-course-score').val();

                if (!isIncluded) {
                    return;
                }

                if (buildMode === 'krs_with_scores' && (score === '' || Number(score) < 0 || Number(
                        score) > 100)) {
                    hasInvalidScore = true;
                    return false;
                }

                const coursePayload = {
                    id_kelas_kuliah: $row.find('.study-course-class-id').val(),
                    catatan: $row.find('.study-course-note').val()
                };

                if (buildMode === 'krs_with_scores') {
                    coursePayload.nilai_akhir = score;
                }

                courses.push(coursePayload);
            });

            if (hasInvalidScore) {
                throw new Error(
                    'Pastikan setiap mata kuliah yang dicentang memiliki nilai akhir antara 0 sampai 100.');
            }

            if (courses.length) {
                payload.push({
                    id_mahasiswa: studentId,
                    build_mode: buildMode,
                    courses: courses
                });
            }
        });

        return payload;
    }

    function renderHistoricalPreviewResults(targetSelector, rows, emptyMessage) {
        if (!rows || !rows.length) {
            $(targetSelector).html(`<div class="text-muted">${escapeHtml(emptyMessage)}</div>`);
            return;
        }

        const html = rows.map((row) => `
                <div class="study-preview-item ${escapeHtml(row.status ?? '')}">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa ?? '-')}</div>
                            <div class="small text-muted">${escapeHtml(row.nim ?? '-')}</div>
                        </div>
                        <span class="badge ${row.status === 'ready' || row.status === 'executed' ? 'bg-success' : row.status === 'skipped' ? 'bg-warning text-dark' : 'bg-danger'}">${escapeHtml(row.status ?? '-')}</span>
                    </div>
                    <div class="small mt-2">${escapeHtml(row.message ?? '-')}</div>
                    ${row.meta?.total_courses ? `<div class="small text-muted mt-2">Mata kuliah: ${row.meta.total_courses} • Total SKS: ${row.meta.total_sks ?? 0}</div>` : ''}
                </div>
            `).join('');

        $(targetSelector).html(html);
    }

    function resetHistoricalExecuteButtons() {
        $('#studyHistoricalExecuteBuildBtn').prop('disabled', true);
    }

    function loadHistoricalWorkspaceData() {
        const context = getStudyContext();

        if (!context.id_semester) {
            showStudyAlert('Pilih semester pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        const $button = $('#studyHistoricalLoadBtn');
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');
        resetHistoricalExecuteButtons();
        $('#studyHistoricalEligibleSearch').prop('disabled', false);

        $.get(window.studyConfig.historicalEligibleRoute, {
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan
        }).done(function (response) {
            historicalEligibleStudents = response.data ?? [];
            selectedHistoricalStudentIds = [];
            renderHistoricalEligibleStudents(historicalEligibleStudents);
            $('#studyHistoricalBuilderCards').html(
                '<div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form KRS</strong>.</div>'
            );
            renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', [],
                'Belum ada hasil pratinjau yang ditampilkan.');
        }).fail(function (xhr) {
            historicalEligibleStudents = [];
            selectedHistoricalStudentIds = [];
            renderHistoricalEligibleStudents([]);
            showStudyAlert(xhr.responseJSON?.message ?? 'Data mahasiswa semester lampau gagal dimuat.', 'danger');
        }).always(function () {
            $button.prop('disabled', false).html('<i class="fas fa-book-open me-1"></i> Muat Data');
        });

        if (context.id_prodi && context.semester_ke) {
            $.get(window.studyConfig.historicalPackageClassesRoute, {
                id_semester: context.id_semester,
                id_prodi: context.id_prodi,
                semester_ke: context.semester_ke
            }).done(function (response) {
                historicalPackageClasses = response.data ?? [];
                renderHistoricalPackageClasses(historicalPackageClasses);
            }).fail(function (xhr) {
                historicalPackageClasses = [];
                renderHistoricalPackageClasses([]);
                showStudyAlert(xhr.responseJSON?.message ?? 'Daftar kelas semester lampau gagal dimuat.', 'danger');
            });
        } else {
            historicalPackageClasses = [];
            renderHistoricalPackageClasses([]);
        }
    }

    function submitHistoricalPreview() {
        const context = getStudyContext();

        if (!context.id_prodi) {
            showStudyAlert('Pilih program studi pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        if (!context.semester_ke) {
            showStudyAlert('Pilih semester ke pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        const selectedIds = getSelectedHistoricalStudentIds();
        if (!selectedIds.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const payload = {
            _token: window.studyConfig.csrfToken,
            action_type: 'build_historical_krs',
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan,
            semester_ke: context.semester_ke,
            build_mode: getHistoricalBuildMode(),
            selected_mahasiswa_ids: selectedIds,
            notes: $('#studyHistoricalBuildNotes').val()
        };

        try {
            payload.students_payload = buildHistoricalStudentsPayload();
        } catch (error) {
            showStudyAlert(error.message, 'warning');
            return;
        }

        if (!payload.students_payload.length) {
            showStudyAlert('Siapkan minimal satu form mata kuliah mahasiswa sebelum melihat pratinjau.', 'warning');
            return;
        }

        $.post(window.studyConfig.historicalPreviewRoute, payload)
            .done(function (response) {
                const rows = response.data ?? [];
                renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', rows,
                    'Pratinjau tidak menghasilkan data.');
                $('#studyHistoricalExecuteBuildBtn').prop('disabled', !rows.length);
            })
            .fail(function (xhr) {
                $('#studyHistoricalExecuteBuildBtn').prop('disabled', true);
                showStudyAlert(xhr.responseJSON?.message ?? 'Pratinjau riwayat studi gagal dijalankan.', 'danger');
            });
    }

    function submitHistoricalExecute() {
        const context = getStudyContext();

        if (!context.id_prodi) {
            showStudyAlert('Pilih program studi pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        if (!context.semester_ke) {
            showStudyAlert('Pilih semester ke pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        const selectedIds = getSelectedHistoricalStudentIds();
        if (!selectedIds.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const payload = {
            _token: window.studyConfig.csrfToken,
            action_type: 'build_historical_krs',
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan,
            semester_ke: context.semester_ke,
            build_mode: getHistoricalBuildMode(),
            selected_mahasiswa_ids: selectedIds,
            notes: $('#studyHistoricalBuildNotes').val()
        };

        try {
            payload.students_payload = buildHistoricalStudentsPayload();
        } catch (error) {
            showStudyAlert(error.message, 'warning');
            return;
        }

        const $button = $('#studyHistoricalExecuteBuildBtn');
        const originalHtml = '<i class="fas fa-play me-1"></i> Jalankan Proses';

        Swal.fire({
            title: 'Jalankan proses sekarang?',
            text: 'Pastikan data pratinjau sudah sesuai sebelum proses riwayat studi dijalankan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, jalankan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');
            showStudyLoading('Memproses riwayat studi...', 'Sistem sedang menjalankan proses yang dipilih.');

            $.post(window.studyConfig.historicalExecuteRoute, payload)
                .done(function (response) {
                    Swal.close();
                    const rows = response.data?.results ?? [];
                    renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', rows,
                        'Proses selesai tanpa detail hasil.');
                    showStudyAlert(response.message ?? 'Proses riwayat studi selesai dijalankan.', 'success');

                    if (response.data?.redirect_url) {
                        window.open(response.data.redirect_url, '_blank');
                    }
                })
                .fail(function (xhr) {
                    Swal.close();
                    showStudyAlert(xhr.responseJSON?.message ?? 'Proses riwayat studi gagal dijalankan.', 'danger');
                })
                .always(function () {
                    $button.prop('disabled', false).html(originalHtml);
                });
        });
    }

    // ---- Event bindings ----
    $('#studyHistoricalLoadBtn').on('click', loadHistoricalWorkspaceData);

    $('#studyHistoricalSelectAll').on('change', function () {
        const filteredRows = getFilteredHistoricalEligibleStudents();
        const filteredIds = filteredRows.map((row) => row.id);
        const shouldCheckAll = $(this).is(':checked');

        if (shouldCheckAll) {
            selectedHistoricalStudentIds = [...new Set([...selectedHistoricalStudentIds, ...filteredIds])];
        } else {
            selectedHistoricalStudentIds = selectedHistoricalStudentIds.filter((id) => !filteredIds.includes(id));
        }

        $('.study-historical-student-checkbox').prop('checked', shouldCheckAll);
        syncHistoricalSelectAllState(filteredRows);
        renderHistoricalSelectionSummary();
        resetHistoricalExecuteButtons();
    });

    $(document).on('change', '.study-historical-student-checkbox', function () {
        const studentId = $(this).val();

        if ($(this).is(':checked')) {
            if (!selectedHistoricalStudentIds.includes(studentId)) {
                selectedHistoricalStudentIds.push(studentId);
            }
        } else {
            selectedHistoricalStudentIds = selectedHistoricalStudentIds.filter((id) => id !== studentId);
        }

        syncHistoricalSelectAllState();
        renderHistoricalSelectionSummary();
        resetHistoricalExecuteButtons();
    });

    $('#studyHistoricalEligibleSearch').on('input', function () {
        filterHistoricalEligibleStudents();
    });

    $('#studyHistoricalEligiblePrevPage').on('click', function () {
        if (historicalEligiblePage > 1) {
            historicalEligiblePage -= 1;
            renderHistoricalEligibleStudents(historicalEligibleStudents);
        }
    });

    $('#studyHistoricalEligibleNextPage').on('click', function () {
        historicalEligiblePage += 1;
        renderHistoricalEligibleStudents(historicalEligibleStudents);
    });

    $(document).on('click', '.study-builder-nav-item', function () {
        historicalBuilderActiveStudentId = $(this).data('id-mahasiswa');
        renderHistoricalBuilderCards();
    });

    $(document).on('click', '.study-builder-prev', function () {
        const selectedIds = getSelectedHistoricalStudentIds();
        const currentId = $(this).data('id-mahasiswa');
        const currentIndex = selectedIds.indexOf(currentId);
        if (currentIndex > 0) {
            historicalBuilderActiveStudentId = selectedIds[currentIndex - 1];
            renderHistoricalBuilderCards();
        }
    });

    $(document).on('click', '.study-builder-next', function () {
        const selectedIds = getSelectedHistoricalStudentIds();
        const currentId = $(this).data('id-mahasiswa');
        const currentIndex = selectedIds.indexOf(currentId);
        if (currentIndex >= 0 && currentIndex < selectedIds.length - 1) {
            historicalBuilderActiveStudentId = selectedIds[currentIndex + 1];
            renderHistoricalBuilderCards();
        }
    });

    $(document).on('click', '#studyHistoricalBuilderPrevPage', function () {
        if (historicalBuilderPage > 1) {
            historicalBuilderPage -= 1;
            const selectedStudents = getSelectedHistoricalStudents();
            const startIndex = (historicalBuilderPage - 1) * historicalPageSize;
            historicalBuilderActiveStudentId = selectedStudents[startIndex]?.id ?? historicalBuilderActiveStudentId;
            renderHistoricalBuilderCards();
        }
    });

    $(document).on('click', '#studyHistoricalBuilderNextPage', function () {
        const selectedStudents = getSelectedHistoricalStudents();
        const totalPages = Math.max(1, Math.ceil(selectedStudents.length / historicalPageSize));
        if (historicalBuilderPage < totalPages) {
            historicalBuilderPage += 1;
            const startIndex = (historicalBuilderPage - 1) * historicalPageSize;
            historicalBuilderActiveStudentId = selectedStudents[startIndex]?.id ?? historicalBuilderActiveStudentId;
            renderHistoricalBuilderCards();
        }
    });

    $('#studyHistoricalPrepareBuilderBtn').on('click', function () {
        const selectedIds = getSelectedHistoricalStudentIds();

        if (!selectedIds.length) {
            renderHistoricalBuilderCards();
            return;
        }

        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyiapkan...');

        loadHistoricalRepeatCandidates(selectedIds).always(function () {
            renderHistoricalBuilderCards();
            $button.prop('disabled', false).html('<i class="fas fa-pen-ruler me-1"></i> Siapkan Form KRS');
        });
    });

    $('#studyHistoricalBuildMode').on('change', function () {
        const buildMode = getHistoricalBuildMode();
        $('#studyHistoricalBuildModeHelper').text(
            buildMode === 'krs_only' ?
            'Mode ini akan membuat KRS dan detail KRS tanpa harus mengisi nilai akhir atau catatan setiap mata kuliah.' :
            'Mode ini akan membuat KRS sekaligus menyimpan nilai akhir semester lampau pada mata kuliah yang dipilih.'
        );
        if ($('.study-student-builder-card').length) {
            renderHistoricalBuilderCards();
        }
        resetHistoricalExecuteButtons();
    });

    $('#studyHistoricalPreviewBuildBtn').on('click', submitHistoricalPreview);
    $('#studyHistoricalExecuteBuildBtn').on('click', submitHistoricalExecute);

    // Sinkronkan helper awal.
    $('#studyHistoricalBuildModeHelper').text(
        'Mode ini akan membuat KRS dan detail KRS tanpa harus mengisi nilai akhir atau catatan setiap mata kuliah.');

    // Auto-invalidate selection saat filter berubah.
    $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', function () {
        historicalEligibleStudents = [];
        historicalPackageClasses = [];
        historicalRepeatCandidatesByStudent = {};
        selectedHistoricalStudentIds = [];
        historicalBuilderActiveStudentId = null;
        historicalEligiblePage = 1;
        historicalBuilderPage = 1;
        $('#studyHistoricalEligibleSummary').text('Belum ada mahasiswa yang dimuat.');
        $('#studyHistoricalEligiblePagerInfo').text('Belum ada halaman untuk ditampilkan.');
        $('#studyHistoricalEligibleSearch').val('');
        $('#studyHistoricalEligibleSearch').prop('disabled', false);
        $('#studyHistoricalEligibleTableBody').html(
            '<tr><td colspan="5" class="text-center text-muted py-4">Filter berubah. Muat ulang data historis.</td></tr>'
        );
        $('#studyHistoricalPackageClasses').html(
            '<div class="text-muted">Filter berubah. Muat ulang data historis untuk menampilkan daftar kelas.</div>'
        );
        $('#studyHistoricalBuilderCards').html(
            '<div class="text-muted">Filter berubah. Siapkan kembali form nilai setelah data historis dimuat ulang.</div>'
        );
        renderHistoricalPreviewResults('#studyHistoricalBuildPreviewResults', [],
            'Filter berubah. Jalankan pratinjau lagi untuk melihat hasil sesuai filter terbaru.');
        renderHistoricalSelectionSummary();
        resetHistoricalExecuteButtons();
    });
})();
