/* ===================================================================== */
/* Admin Studi Mahasiswa — Halaman: Koreksi & Finalisasi (riwayat.blade) */
/* Alur: aksi (reopen/reset/refinalize/generate) → muat mahasiswa →      */
/* pilih → preview → jalankan.                                           */
/* ===================================================================== */
(function () {
    'use strict';

    const common = window.studyCommon;
    const escapeHtml = common.escapeHtml;
    const showStudyAlert = common.showStudyAlert;
    const showStudyLoading = common.showStudyLoading;
    const getStudyContext = common.getStudyContext;

    let historicalEligibleStudents = [];
    let selectedHistoricalStudentIds = [];

    function getSelectedHistoricalStudentIds() {
        return [...selectedHistoricalStudentIds];
    }

    function getSelectedHistoricalStudents() {
        const selectedIds = getSelectedHistoricalStudentIds();
        return historicalEligibleStudents.filter((row) => selectedIds.includes(row.id));
    }

    function getHistoricalMutationAction() {
        return $('#studyHistoricalMutationAction').val() || 'reopen_historical_krs';
    }

    function syncHistoricalMutationManualIpkPanel() {
        const selectedStudents = getSelectedHistoricalStudents();
        const shouldShow = getHistoricalMutationAction() === 'generate_khs';
        const $panel = $('#studyHistoricalMutationManualIpkPanel');
        const $list = $('#studyHistoricalMutationManualIpkList');
        const existingValues = {};

        $('.study-historical-manual-ipk').each(function () {
            existingValues[$(this).data('id-mahasiswa')] = $(this).val();
        });

        $panel.toggleClass('d-none', !shouldShow);

        if (!shouldShow) {
            return;
        }

        if (!selectedStudents.length) {
            $list.html('<div class="text-muted">Belum ada mahasiswa yang dipilih.</div>');
            return;
        }

        const html = selectedStudents.map((student) => `
                <div class="border rounded-3 p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(student.nama_mahasiswa ?? '-')}</div>
                            <div class="small text-muted">${escapeHtml(student.nim ?? '-')}</div>
                        </div>
                        <span class="badge bg-light text-dark">Semester Target ${escapeHtml(student.semester_target ?? '-')}</span>
                    </div>
                    <div class="mt-3">
                        <label class="form-label small">IPK Manual</label>
                        <input
                            type="number"
                            min="0"
                            max="4"
                            step="0.01"
                            class="form-control form-control-sm study-historical-manual-ipk"
                            data-id-mahasiswa="${escapeHtml(student.id)}"
                            value="${escapeHtml(existingValues[student.id] ?? '')}"
                            placeholder="Isi jika semester di atas 1">
                        <div class="form-text">Semester 1 akan tetap mengikuti IPS. Isi nilai ini untuk semester berikutnya.</div>
                    </div>
                </div>
            `).join('');

        $list.html(html);
    }

    function buildHistoricalGenerateKhsPayload() {
        return getSelectedHistoricalStudents().map((student) => {
            const inputValue = $(`.study-historical-manual-ipk[data-id-mahasiswa="${student.id}"]`).val();
            return {
                id_mahasiswa: student.id,
                ipk: inputValue === '' ? null : inputValue
            };
        });
    }

    function renderHistoricalSelectionSummary() {
        const selectedStudents = getSelectedHistoricalStudents();

        if (!selectedStudents.length) {
            $('#studyHistoricalMutationSelectionSummary').text(
                'Belum ada mahasiswa yang dipilih. Pilih mahasiswa di halaman Daftarkan KRS, atau muat ulang data dengan tombol di atas.'
            );
            syncHistoricalMutationManualIpkPanel();
            return;
        }

        const names = selectedStudents.slice(0, 3).map((row) => row.nama_mahasiswa).join(', ');
        const extra = selectedStudents.length > 3 ? ` +${selectedStudents.length - 3} mahasiswa` : '';
        const summary = `${selectedStudents.length} mahasiswa dipilih: ${names}${extra}`;

        $('#studyHistoricalMutationSelectionSummary').text(summary);
        syncHistoricalMutationManualIpkPanel();
    }

    function renderHistoricalEligibleCheckboxes(rows) {
        // Halaman riwayat: render checkbox mahasiswa yang bisa dipilih (dari filter).
        if (!$('#studyHistoricalMutationSelectionList').length) {
            // Tidak ada elemen daftar di view — hanya perbarui ringkasan.
            renderHistoricalSelectionSummary();
            return;
        }

        const $list = $('#studyHistoricalMutationSelectionList').empty();

        (rows || []).forEach((row) => {
            const isSelected = selectedHistoricalStudentIds.includes(row.id);
            const $label = $(`
                    <label class="study-riwayat-select-item form-check d-flex align-items-center gap-2 mb-1">
                        <input type="checkbox" class="form-check-input study-historical-student-checkbox"
                            value="${escapeHtml(row.id)}" ${isSelected ? 'checked' : ''}>
                        <span class="small">${escapeHtml(row.nama_mahasiswa)} (${escapeHtml(row.nim ?? '-')})</span>
                    </label>
                `);
            $list.append($label);
        });

        renderHistoricalSelectionSummary();
    }

    function loadHistoricalWorkspaceData() {
        const context = getStudyContext();

        if (!context.id_semester) {
            showStudyAlert('Pilih semester pada filter konteks terlebih dahulu.', 'warning');
            return;
        }

        const $button = $('#studyRiwayatLoadBtn');
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

        $.get(window.studyConfig.historicalEligibleRoute, {
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan
        }).done(function (response) {
            historicalEligibleStudents = response.data ?? [];
            selectedHistoricalStudentIds = [];
            renderHistoricalEligibleCheckboxes(historicalEligibleStudents);
            renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', [],
                'Belum ada hasil pratinjau tindakan yang ditampilkan.');
            $('#studyHistoricalExecuteMutationBtn').prop('disabled', true);
        }).fail(function (xhr) {
            historicalEligibleStudents = [];
            selectedHistoricalStudentIds = [];
            renderHistoricalEligibleCheckboxes([]);
            showStudyAlert(xhr.responseJSON?.message ?? 'Data mahasiswa semester lampau gagal dimuat.', 'danger');
        }).always(function () {
            $button.prop('disabled', false).html('<i class="fas fa-users me-1"></i> Tampilkan Mahasiswa');
        });
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
        $('#studyHistoricalExecuteMutationBtn').prop('disabled', true);
    }

    function submitMutationPreview() {
        const context = getStudyContext();
        const action = getHistoricalMutationAction();

        const selectedIds = getSelectedHistoricalStudentIds();
        if (!selectedIds.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const payload = {
            _token: window.studyConfig.csrfToken,
            action_type: action,
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan,
            semester_ke: context.semester_ke,
            build_mode: 'krs_only',
            selected_mahasiswa_ids: selectedIds,
            notes: $('#studyHistoricalMutationNotes').val()
        };

        if (action === 'generate_khs') {
            payload.students_payload = buildHistoricalGenerateKhsPayload();
        }

        $.post(window.studyConfig.historicalPreviewRoute, payload)
            .done(function (response) {
                const rows = response.data ?? [];
                renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', rows,
                    'Pratinjau tidak menghasilkan data.');
                $('#studyHistoricalExecuteMutationBtn').prop('disabled', !rows.length);
            })
            .fail(function (xhr) {
                $('#studyHistoricalExecuteMutationBtn').prop('disabled', true);
                showStudyAlert(xhr.responseJSON?.message ?? 'Pratinjau riwayat studi gagal dijalankan.', 'danger');
            });
    }

    function submitMutationExecute() {
        const context = getStudyContext();
        const action = getHistoricalMutationAction();

        const selectedIds = getSelectedHistoricalStudentIds();
        if (!selectedIds.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const payload = {
            _token: window.studyConfig.csrfToken,
            action_type: action,
            id_semester: context.id_semester,
            id_prodi: context.id_prodi,
            angkatan: context.angkatan,
            semester_ke: context.semester_ke,
            build_mode: 'krs_only',
            selected_mahasiswa_ids: selectedIds,
            notes: $('#studyHistoricalMutationNotes').val()
        };

        if (action === 'generate_khs') {
            payload.students_payload = buildHistoricalGenerateKhsPayload();
        }

        const $button = $('#studyHistoricalExecuteMutationBtn');
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
                    renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', rows,
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
    $('#studyRiwayatLoadBtn').on('click', loadHistoricalWorkspaceData);

    $('#studyHistoricalPreviewMutationBtn').on('click', submitMutationPreview);
    $('#studyHistoricalExecuteMutationBtn').on('click', submitMutationExecute);

    $('#studyHistoricalMutationAction').on('change', function () {
        syncHistoricalMutationManualIpkPanel();
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

        renderHistoricalSelectionSummary();
        resetHistoricalExecuteButtons();
    });

    // Auto-invalidate selection saat filter berubah.
    $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', function () {
        historicalEligibleStudents = [];
        selectedHistoricalStudentIds = [];
        renderHistoricalEligibleCheckboxes([]);
        renderHistoricalPreviewResults('#studyHistoricalMutationPreviewResults', [],
            'Filter berubah. Muat ulang mahasiswa untuk melihat hasil terbaru.');
        resetHistoricalExecuteButtons();
    });

    syncHistoricalMutationManualIpkPanel();
})();
