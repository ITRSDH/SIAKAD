/* ===================================================================== */
/* Admin Studi Mahasiswa — Halaman: Generate KHS (khs.blade.php)         */
/* Alur BATCH satu-tombol: muat siap → pilih banyak → IPK manual →      */
/* generate+finalize per mahasiswa berurutan → hasil + tautan Lihat KHS. */
/* ===================================================================== */
(function () {
    'use strict';

    const common = window.studyCommon;
    const escapeHtml = common.escapeHtml;
    const showStudyAlert = common.showStudyAlert;
    const showStudyLoading = common.showStudyLoading;
    const formatGpaValue = common.formatGpaValue;

    let readyKhsRows = [];
    let selectedKhsStudentIds = [];
    let khsBatchPage = 1;
    const khsBatchPageSize = 10;
    let khsSearchTerm = '';

    function getFilteredReadyRows(rows) {
        const q = khsSearchTerm.trim().toLowerCase();
        if (!q) {
            return rows;
        }
        return rows.filter((row) => {
            const name = (row.nama_mahasiswa || '').toLowerCase();
            const nim = (row.nim || '').toLowerCase();
            return name.includes(q) || nim.includes(q);
        });
    }

    function renderReadyKhsRows(rows) {
        readyKhsRows = rows || [];
        const filteredRows = getFilteredReadyRows(readyKhsRows);
        const hasSearch = khsSearchTerm.trim() !== '';

        if (!rows || !rows.length || !filteredRows.length) {
            $('#readyKhsTableBody').html(
                '<tr><td colspan="7" class="text-center text-muted py-4">' +
                (hasSearch
                    ? 'Tidak ada mahasiswa yang cocok dengan pencarian.'
                    : 'Tidak ada mahasiswa yang bisa ditampilkan untuk konteks ini.') +
                '</td></tr>'
            );
            $('#khsBatchGenerateBtn').prop('disabled', true).text('Generate KHS (0)');
            $('#khsBatchSelectAll').prop('checked', false);
            $('#khsBatchResult').html('');
            renderKhsBatchPager(0);
            return;
        }

        // Saring ID yang dipilih yang masih ada di daftar terbaru.
        const availableIds = readyKhsRows.map((row) => row.id_mahasiswa);
        selectedKhsStudentIds = selectedKhsStudentIds.filter((id) => availableIds.includes(id));

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / khsBatchPageSize));
        khsBatchPage = Math.min(Math.max(1, khsBatchPage), totalPages);
        const startIndex = (khsBatchPage - 1) * khsBatchPageSize;
        const pageRows = filteredRows.slice(startIndex, startIndex + khsBatchPageSize);

        const html = pageRows.map((row) => {
            const isReady = row.status === 'ready';
            const selected = selectedKhsStudentIds.includes(row.id_mahasiswa);
            const checkDisabled = isReady ? '' : 'disabled';
            const statusBadge = isReady ?
                '<span class="badge bg-success">Siap</span>' :
                '<span class="badge bg-secondary">Belum Siap</span>';
            const reason = !isReady && row.message ?
                `<div class="small text-danger">${escapeHtml(row.message)}</div>` : '';
            const finalDetail = isReady ?
                `<span class="badge bg-success-subtle text-success">${row.final_detail ?? 0}/${row.total_detail ?? 0}</span>` :
                `<span class="badge bg-warning-subtle text-warning">${row.final_detail ?? 0}/${row.total_detail ?? 0}</span>`;
            const existingKhsBadge = row.existing_khs ?
                `<span class="badge ${row.existing_khs_is_final ? 'bg-primary' : 'bg-warning text-dark'}">${row.existing_khs_is_final ? 'KHS Final' : 'KHS Draft'}</span>` :
                '<span class="text-muted">Belum ada</span>';
            const ipkInput = isReady ?
                `<input type="number" min="0" max="4" step="0.01"
                    class="form-control form-control-sm ready-khs-manual-ipk"
                    data-id-mahasiswa="${escapeHtml(row.id_mahasiswa)}"
                    placeholder="Isi IPK jika semester > 1">
                 <div class="form-text">Semester 1 memakai IPS; IPK manual wajib untuk semester > 1.</div>` :
                '<span class="text-muted">—</span>';
            const actionCell = row.existing_khs ?
                `<a href="${window.studyConfig.khsUrl}/${escapeHtml(row.existing_khs)}" target="_blank"
                    class="btn btn-outline-primary btn-sm w-100" title="Lihat detail KHS mahasiswa ini">
                    <i class="fas fa-eye me-1"></i> Detail
                </a>` :
                `<button type="button" class="btn btn-outline-secondary btn-sm w-100" disabled
                    title="KHS belum tersedia. Generate terlebih dahulu untuk membuka detail.">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>`;

            return `
                    <tr>
                        <td><input type="checkbox" class="khs-batch-row-checkbox" value="${escapeHtml(row.id_mahasiswa)}" ${selected ? 'checked' : ''} ${checkDisabled}></td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(row.nama_mahasiswa)}</div>
                            <div class="small text-muted">${escapeHtml(row.nim)}${row.prodi ? ' • ' + escapeHtml(row.prodi) : ''}</div>
                            ${reason}
                        </td>
                        <td>${statusBadge}</td>
                        <td>${finalDetail}</td>
                        <td>${existingKhsBadge}</td>
                        <td>${ipkInput}</td>
                        <td class="text-center">${actionCell}</td>
                    </tr>
                `;
        }).join('');

        $('#readyKhsTableBody').html(html);
        renderKhsBatchPager(filteredRows.length);
        syncKhsBatchGenerateState();
        $('#khsBatchSelectAll').prop('checked', false);
    }

    function renderKhsBatchPager(totalRows) {
        const totalPages = Math.max(1, Math.ceil(totalRows / khsBatchPageSize));

        if (!readyKhsRows.length || totalRows === 0) {
            $('#khsBatchPagerInfo').text('Belum ada halaman untuk ditampilkan.');
            $('#khsBatchPrevPage, #khsBatchNextPage').prop('disabled', true);
            return;
        }

        $('#khsBatchPagerInfo').text(
            `Halaman ${khsBatchPage} dari ${totalPages} • Menampilkan mahasiswa ${Math.min(totalRows, ((khsBatchPage - 1) * khsBatchPageSize) + 1)}–${Math.min(khsBatchPage * khsBatchPageSize, totalRows)} dari ${totalRows}`
        );
        $('#khsBatchPrevPage').prop('disabled', khsBatchPage <= 1);
        $('#khsBatchNextPage').prop('disabled', khsBatchPage >= totalPages);
    }

    function getSelectedReadyRows() {
        return readyKhsRows.filter((row) => selectedKhsStudentIds.includes(row.id_mahasiswa));
    }

    function syncKhsBatchGenerateState() {
        const count = selectedKhsStudentIds.length;
        $('#khsBatchGenerateBtn').prop('disabled', count === 0).text(`Generate KHS (${count})`);
    }

    function getReadyKhsManualIpk(mahasiswaId) {
        const inputValue = $(`.ready-khs-manual-ipk[data-id-mahasiswa="${mahasiswaId}"]`).val();
        return inputValue === '' ? null : inputValue;
    }

    // Satu tombol: generate + finalize per mahasiswa berurutan (tanpa preview wajib).
    function runKhsBatchGenerate() {
        const selectedRows = getSelectedReadyRows();

        if (!selectedRows.length) {
            showStudyAlert('Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
            return;
        }

        const semesterId = $('#studySemesterId').val();
        if (!semesterId) {
            showStudyAlert('Pilih semester pada filter terlebih dahulu.', 'warning');
            return;
        }

        showStudyLoading('Membuat KHS...', `Memproses ${selectedRows.length} mahasiswa berurutan. Jangan tutup halaman ini.`);

        const results = [];
        let index = 0;

        function next() {
            if (index >= selectedRows.length) {
                finishBatch(results);
                return;
            }

            const row = selectedRows[index];
            const studentKey = row.id_mahasiswa;

            $.post(window.studyConfig.generateKhsExecuteRoute, {
                _token: window.studyConfig.csrfToken,
                id_mahasiswa: row.id_mahasiswa,
                id_semester: semesterId,
                is_final: '1',
                ipk: getReadyKhsManualIpk(studentKey)
            }).done(function (response) {
                results.push({
                    id_mahasiswa: row.id_mahasiswa,
                    nama_mahasiswa: row.nama_mahasiswa,
                    nim: row.nim,
                    status: 'success',
                    khsId: response.data?.id ?? null,
                    message: response.message ?? 'KHS berhasil dibuat.'
                });
            }).fail(function (xhr) {
                results.push({
                    id_mahasiswa: row.id_mahasiswa,
                    nama_mahasiswa: row.nama_mahasiswa,
                    nim: row.nim,
                    status: 'failed',
                    khsId: null,
                    message: xhr.responseJSON?.message ?? 'Gagal membuat KHS.'
                });
            }).always(function () {
                index += 1;
                next();
            });
        }

        next();
    }

    function finishBatch(results) {
        Swal.close();

        const successCount = results.filter((r) => r.status === 'success').length;
        const failedCount = results.filter((r) => r.status === 'failed').length;

        const items = results.map((r) => `
                <div class="study-preview-item ${r.status === 'success' ? 'executed' : 'failed'}">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(r.nama_mahasiswa)}</div>
                            <div class="small text-muted">${escapeHtml(r.nim)}</div>
                            <div class="small mt-1">${escapeHtml(r.message)}</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge ${r.status === 'success' ? 'bg-success' : 'bg-danger'}">${r.status === 'success' ? 'Berhasil' : 'Gagal'}</span>
                            ${r.khsId ? `<a href="${window.studyConfig.khsUrl}/${r.khsId}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat KHS</a>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');

        $('#khsBatchResult').html(`
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="badge bg-success">${successCount} berhasil</span>
                    ${failedCount ? `<span class="badge bg-danger">${failedCount} gagal</span>` : ''}
                </div>
                <div class="d-grid gap-2">${items}</div>
            `);

        if (successCount && !failedCount) {
            showStudyAlert('KHS selesai dibuat dan difinalisasi.', 'success');
        } else if (successCount && failedCount) {
            showStudyAlert(`Selesai: ${successCount} berhasil, ${failedCount} gagal. Detail di daftar hasil.`, 'warning');
        } else {
            showStudyAlert('Tidak ada KHS yang berhasil dibuat. Cek alasan pada daftar hasil.', 'danger');
        }

        // Bersihkan selection & refresh list.
        selectedKhsStudentIds = [];
        khsBatchPage = 1;
        $('#khsBatchSelectAll').prop('checked', false);
        $('#khsBatchGenerateBtn').prop('disabled', true).text('Generate KHS (0)');
        const semesterId = $('#studySemesterId').val();
        if (semesterId) {
            loadReadyKhsData(semesterId);
        }
    }

    function loadReadyKhsData(semesterId) {
        const $button = $('#loadReadyKhsBtn');
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...');

        $.get(window.studyConfig.readyKhsRoute, {
            id_semester: semesterId,
            id_prodi: $('#studyProdiId').val(),
            angkatan: $('#studyAngkatan').val(),
            q: khsSearchTerm
        }).done(function (response) {
            renderReadyKhsRows(response.data ?? []);
        }).fail(function (xhr) {
            showStudyAlert(xhr.responseJSON?.message ?? 'Data mahasiswa yang siap dibuatkan KHS gagal dimuat.', 'danger');
        }).always(function () {
            $button.prop('disabled', false).html('<i class="fas fa-list-check me-1"></i> Muat Mahasiswa Siap');
        });
    }

    // ---- Event bindings ----
    $('#loadReadyKhsBtn').on('click', function () {
        const semesterId = $('#studySemesterId').val();
        if (!semesterId) {
            showStudyAlert('Pilih semester pada filter terlebih dahulu.', 'warning');
            return;
        }
        loadReadyKhsData(semesterId);
    });

    $('#khsBatchSelectAll').on('change', function () {
        const rows = getFilteredReadyRows(readyKhsRows).filter((row) => row.status === 'ready');
        const readyIds = rows.map((row) => row.id_mahasiswa);
        const shouldCheckAll = $(this).is(':checked');

        if (shouldCheckAll) {
            selectedKhsStudentIds = [...new Set([...selectedKhsStudentIds, ...readyIds])];
        } else {
            selectedKhsStudentIds = selectedKhsStudentIds.filter((id) => !readyIds.includes(id));
        }

        readyKhsRows.forEach((row) => {
            if (row.status === 'ready' && readyIds.includes(row.id_mahasiswa)) {
                $(`.khs-batch-row-checkbox[value="${row.id_mahasiswa}"]`).prop('checked', shouldCheckAll);
            }
        });
        syncKhsBatchGenerateState();
    });

    $(document).on('change', '.khs-batch-row-checkbox', function () {
        const studentId = $(this).val();

        if ($(this).is(':checked')) {
            if (!selectedKhsStudentIds.includes(studentId)) {
                selectedKhsStudentIds.push(studentId);
            }
        } else {
            selectedKhsStudentIds = selectedKhsStudentIds.filter((id) => id !== studentId);
        }

        syncKhsBatchGenerateState();
    });

    $('#khsBatchGenerateBtn').on('click', runKhsBatchGenerate);

    // Pencarian nama / NIM mahasiswa (server-side, debounce 300ms).
    let khsSearchTimer = null;
    $('#khsBatchSearch').on('input', function () {
        khsSearchTerm = $(this).val();
        clearTimeout(khsSearchTimer);
        khsSearchTimer = setTimeout(function () {
            khsBatchPage = 1;
            const semesterId = $('#studySemesterId').val();
            if (semesterId) {
                loadReadyKhsData(semesterId);
            }
        }, 300);
    });

    $('#khsBatchPrevPage').on('click', function () {
        if (khsBatchPage > 1) {
            khsBatchPage -= 1;
            renderReadyKhsRows(readyKhsRows);
        }
    });

    $('#khsBatchNextPage').on('click', function () {
        const totalPages = Math.max(1, Math.ceil(getFilteredReadyRows(readyKhsRows).length / khsBatchPageSize));
        if (khsBatchPage < totalPages) {
            khsBatchPage += 1;
            renderReadyKhsRows(readyKhsRows);
        }
    });

    // Auto-load saat semester dipilih (ringan).
    $('#studySemesterId').on('change', function () {
        const semesterId = $(this).val();
        if (!semesterId) {
            selectedKhsStudentIds = [];
            khsBatchPage = 1;
            readyKhsRows = [];
            $('#khsBatchGenerateBtn').prop('disabled', true).text('Generate KHS (0)');
            $('#readyKhsTableBody').html(
                '<tr><td colspan="7" class="text-center text-muted py-4">Pilih semester lalu muat daftar.</td></tr>'
            );
            $('#khsBatchPagerInfo').text('Belum ada halaman untuk ditampilkan.');
            $('#khsBatchPrevPage, #khsBatchNextPage').prop('disabled', true);
            return;
        }
        khsBatchPage = 1;
        loadReadyKhsData(semesterId);
    });
})();
