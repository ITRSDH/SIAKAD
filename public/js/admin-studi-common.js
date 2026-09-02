/* ===================================================================== */
/* Admin Studi Mahasiswa — Common helpers + ringkasan + auto-load filter */
/* Dipakai oleh halaman: krs, nilai, khs, riwayat.                       */
/* Dependensi Blade dibaca dari window.studyConfig.                      */
/* ===================================================================== */
(function () {
    'use strict';

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function showStudyAlert(message, type = 'info') {
        const iconMap = {
            info: 'info',
            success: 'success',
            warning: 'warning',
            danger: 'error',
            error: 'error',
        };

        return Swal.fire({
            icon: iconMap[type] || 'info',
            text: message || 'Terjadi kesalahan.',
            confirmButtonText: 'OK',
        });
    }

    function showStudyLoading(title = 'Memproses...', text = 'Mohon tunggu sebentar.') {
        Swal.fire({
            title,
            text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });
    }

    function formatGpaValue(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        const number = Number(value);
        return Number.isFinite(number) ? number.toFixed(2) : escapeHtml(value);
    }

    function getStudyContext() {
        return {
            id_semester: $('#studySemesterId').val(),
            id_prodi: $('#studyProdiId').val(),
            angkatan: $('#studyAngkatan').val(),
            semester_ke: $('#studySemesterKe').val()
        };
    }

    function renderSummaryCards(cards) {
        if (!$('#studySummaryCards').length) {
            return;
        }

        const html = (cards || []).map((card) => `
                <div class="col-md-6 col-xl-3">
                    <div class="study-stat">
                        <div class="small text-muted text-uppercase mb-2">${escapeHtml(card.label ?? '-')}</div>
                        <div class="study-stat-value text-${card.tone ?? 'primary'}">${escapeHtml(card.value ?? 0)}</div>
                        <div class="small text-muted mt-2">${escapeHtml(card.description ?? '')}</div>
                    </div>
                </div>
            `).join('');

        $('#studySummaryCards').html(html ||
            '<div class="col-12"><div class="text-muted">Tidak ada ringkasan yang dapat ditampilkan.</div></div>');
    }

    function refreshSummary() {
        if (!$('#studySummaryCards').length || !window.studyConfig?.summaryRoute) {
            return;
        }

        $.get(window.studyConfig.summaryRoute, getStudyContext())
            .done(function (response) {
                renderSummaryCards(response.data?.summary_cards ?? []);
            })
            .fail(function () {
                // Abaikan: ringkasan tidak kritis; halaman utama tetap berfungsi.
            });
    }

    // Auto-load: setiap perubahan filter me-refresh ringkasan (debounce).
    $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', debounce(refreshSummary, 300));

    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Tersedia untuk halaman yang memanggil window.x (di-expose minimal).
    window.studyCommon = {
        escapeHtml,
        showStudyAlert,
        showStudyLoading,
        formatGpaValue,
        getStudyContext,
        renderSummaryCards,
        refreshSummary,
        debounce,
    };
})();
