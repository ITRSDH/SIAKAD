@extends('layouts.index')
@section('title', 'Approval KRS Dosen PA')

@push('styles-custom')
    <style>
        :root {
            --pa-primary: #1572e8;
            --pa-primary-rgb: 21, 114, 232;
            --pa-primary-soft: #edf5ff;
            --pa-info: #0284c7;
            --pa-info-rgb: 2, 132, 199;
            --pa-info-soft: #f0f9ff;
            --pa-border: #e2e8f0;
            --pa-card-shadow: 0 4px 16px rgba(21, 114, 232, 0.05);
        }

        /* Modern PA Card */
        .pa-card {
            border: 1px solid var(--pa-border);
            border-radius: 1rem;
            box-shadow: var(--pa-card-shadow);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .pa-card-header {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-bottom: 1px solid var(--pa-border);
            padding: 1.15rem 1.4rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        /* Stat Cards */
        .summary-stat-card {
            background: #ffffff;
            border: 1px solid var(--pa-border);
            border-radius: 0.85rem;
            padding: 1.1rem 1.25rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(21, 114, 232, 0.08);
            border-color: rgba(var(--pa-primary-rgb), 0.35);
        }

        .summary-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--pa-primary);
        }

        .summary-stat-card.stat-info::before {
            background: var(--pa-info);
        }

        .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.3rem;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-icon-wrapper {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-left: 0.75rem;
        }

        .stat-icon-primary {
            background: var(--pa-primary-soft);
            color: var(--pa-primary);
        }

        .stat-icon-info {
            background: var(--pa-info-soft);
            color: var(--pa-info);
        }

        /* Pill / Soft Badges */
        .badge-soft-primary {
            background-color: #e0e7ff !important;
            color: #1d4ed8 !important;
            border: 1px solid #c7d2fe !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.38em 0.85em;
        }

        .badge-soft-info {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            border: 1px solid #bae6fd !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.38em 0.85em;
        }

        .badge-soft-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.38em 0.85em;
        }

        /* Modern Table */
        .table-pa {
            margin-bottom: 0;
        }

        .table-pa thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 2px solid var(--pa-border);
            padding: 0.95rem 0.85rem;
            white-space: nowrap;
        }

        .table-pa tbody td {
            padding: 0.95rem 0.85rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .table-pa tbody tr:hover {
            background-color: rgba(var(--pa-primary-rgb), 0.025);
        }

        /* Detail Boxes inside Modal */
        .detail-box-modern {
            border: 1px solid var(--pa-border);
            border-radius: 0.85rem;
            background: #ffffff;
            padding: 1.25rem;
        }

        /* Empty State */
        .pa-empty-state {
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 4rem 1.5rem;
            text-align: center;
            background: #f8fafc;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Approval KRS Dosen PA</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('dosenpa.krs.index') }}">Approval KRS</a></li>
            </ul>
        </div>

        <!-- Top Summary Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Total Mahasiswa Wali</div>
                        <div class="stat-value text-primary" id="totalMahasiswaWali">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-primary d-none d-sm-flex">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card stat-info">
                    <div class="stat-content">
                        <div class="stat-label">Pending Approval</div>
                        <div class="stat-value text-info" id="pendingApproval">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-info d-none d-sm-flex">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Disetujui Semester Ini</div>
                        <div class="stat-value text-primary" id="approvedThisSemester">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-primary d-none d-sm-flex">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card stat-info">
                    <div class="stat-content">
                        <div class="stat-label">Revisi Semester Ini</div>
                        <div class="stat-value text-info" id="revisedThisSemester">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-info d-none d-sm-flex">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full-Width Approval Workspace -->
        <div class="row">
            <div class="col-12">
                <div class="pa-card">
                    <div class="pa-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-primary" style="width: 38px; height: 38px; border-radius: 8px; font-size: 1rem; margin-left: 0;">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Antrean Persetujuan KRS Mahasiswa</h5>
                                <small class="text-muted">Daftar pengajuan KRS yang menunggu pemeriksaan dan persetujuan Anda</small>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="input-group input-group-sm" style="max-width: 280px;">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 rounded-end-pill" id="searchPendingInput" placeholder="Cari nama mahasiswa / NIM...">
                            </div>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="refreshPendingBtn">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-pa align-middle">
                                <thead>
                                    <tr>
                                        <th width="4%" class="text-center">No</th>
                                        <th>Mahasiswa</th>
                                        <th>Semester</th>
                                        <th class="text-center">Jalur Masuk</th>
                                        <th class="text-center">Beban SKS</th>
                                        <th class="text-center">Status</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">Memuat data pengajuan KRS...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tinjau & Validasi KRS (Focused Review Dialog) -->
        <div class="modal fade" id="modalDetailKrs" tabindex="-1" aria-labelledby="modalDetailKrsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-primary" style="width: 36px; height: 36px; border-radius: 8px; font-size: 1rem; margin-left: 0;">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark" id="modalDetailKrsLabel">Tinjau & Validasi KRS</h5>
                                <small class="text-muted">Pemeriksaan rencana studi & keputusan Dosen PA</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4" id="detailContainer">
                        <div class="text-center py-5">
                            <div class="stat-icon-wrapper stat-icon-primary mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <div class="text-muted small">Memuat detail pengajuan KRS...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const currentDosenName = @json(session('profile.nama_dosen') ?: 'Dosen ini');
        const routes = {
            statistics: "{{ route('dosenpa.krs.statistics') }}",
            pending: "{{ route('dosenpa.krs.pending') }}",
            showTemplate: "{{ route('dosenpa.krs.show', ['id' => '__ID__']) }}",
            approve: "{{ route('dosenpa.krs.approve') }}",
            revision: "{{ route('dosenpa.krs.revision') }}",
            reject: "{{ route('dosenpa.krs.reject') }}",
        };

        let rawPendingList = [];
        let selectedKrsId = null;
        let latestStatistics = {};
        let detailModalInstance = null;

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function getDetailModal() {
            if (!detailModalInstance) {
                const modalEl = document.getElementById('modalDetailKrs');
                if (modalEl && window.bootstrap) {
                    detailModalInstance = new bootstrap.Modal(modalEl);
                }
            }
            return detailModalInstance;
        }

        // Modern Toast Notification Instance
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function notify(message, type = 'info') {
            const iconMap = {
                success: 'success',
                danger: 'error',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };

            Toast.fire({
                icon: iconMap[type] || 'info',
                title: message
            });
        }

        function formatSemester(semester) {
            if (!semester) return '-';
            const tahun = semester.tahun_akademik?.nama_tahun_akademik || '';
            return tahun ? `${semester.nama_semester} - ${tahun}` : (semester.nama_semester || '-');
        }

        function loadStatistics() {
            $.get(routes.statistics)
                .done(function(response) {
                    const data = response.data || {};
                    latestStatistics = data;
                    $('#totalMahasiswaWali').text(data.total_mahasiswa_wali ?? 0);
                    $('#pendingApproval').text(data.pending_approval ?? 0);
                    $('#approvedThisSemester').text(data.approved_this_semester ?? 0);
                    $('#revisedThisSemester').text(data.revised_this_semester ?? 0);
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat statistik dosen PA.', 'danger');
                });
        }

        function renderPendingRows(data) {
            if (!data.length) {
                const totalMahasiswaWali = latestStatistics.total_mahasiswa_wali ?? 0;

                if (totalMahasiswaWali <= 0) {
                    $('#pendingTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="pa-empty-state border-0 py-2">
                                    <div class="stat-icon-wrapper stat-icon-info mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.35rem; margin-left: auto;">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Mahasiswa Bimbingan</h6>
                                    <p class="text-muted small mb-0">${escapeHtml(currentDosenName)} belum memiliki mahasiswa bimbingan, sehingga belum ada KRS yang perlu diperiksa.</p>
                                </div>
                            </td>
                        </tr>
                    `);
                    notify(`${currentDosenName} belum memiliki mahasiswa bimbingan.`, 'info');
                } else {
                    $('#pendingTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="pa-empty-state border-0 py-2">
                                    <div class="stat-icon-wrapper stat-icon-primary mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.35rem; margin-left: auto;">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Antrean KRS Pending</h6>
                                    <p class="text-muted small mb-0">Semua KRS mahasiswa bimbingan Anda telah selesai diperiksa dan disetujui.</p>
                                </div>
                            </td>
                        </tr>
                    `);
                }
                return;
            }

            let rows = '';
            data.forEach((item, index) => {
                const mahasiswa = item.mahasiswa || {};
                const isRpl = ['RPL', 'Pindahan'].includes(mahasiswa.jenis_pendaftaran);
                const jalurBadge = isRpl
                    ? `<span class="badge badge-soft-info">${escapeHtml(mahasiswa.jenis_pendaftaran)}</span>`
                    : `<span class="badge badge-soft-secondary">Reguler</span>`;

                rows += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td>
                            <div class="fw-bold text-dark">${escapeHtml(mahasiswa.nama_mahasiswa || '-')}</div>
                            <div class="small text-muted font-monospace">${escapeHtml(mahasiswa.nim || '-')}</div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">${escapeHtml(formatSemester(item.semester))}</div>
                        </td>
                        <td class="text-center">
                            ${jalurBadge}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-primary border fw-bold px-3 py-1">${escapeHtml(item.total_sks ?? 0)} SKS</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-info">Menunggu Approval</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" onclick="loadDetail('${item.id}')">
                                <i class="fas fa-search me-1"></i> Tinjau KRS
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#pendingTableBody').html(rows);
        }

        function loadPendingList() {
            $('#pendingTableBody').html('<tr><td colspan="7" class="text-center text-muted py-5">Memuat data pengajuan KRS...</td></tr>');

            $.get(routes.pending)
                .done(function(response) {
                    rawPendingList = response.data || [];
                    applyPendingSearchFilter();
                })
                .fail(function(xhr) {
                    $('#pendingTableBody').html('<tr><td colspan="7" class="text-center text-danger py-5">Gagal memuat daftar KRS pending.</td></tr>');
                    notify(xhr.responseJSON?.message || 'Gagal memuat daftar KRS pending.', 'danger');
                });
        }

        function applyPendingSearchFilter() {
            const query = ($('#searchPendingInput').val() || '').trim().toLowerCase();

            if (!query) {
                renderPendingRows(rawPendingList);
                return;
            }

            const filtered = rawPendingList.filter(item => {
                const mahasiswa = item.mahasiswa || {};
                const matchNama = (mahasiswa.nama_mahasiswa || '').toLowerCase().includes(query);
                const matchNim = (mahasiswa.nim || '').toLowerCase().includes(query);
                return matchNama || matchNim;
            });

            if (!filtered.length) {
                $('#pendingTableBody').html('<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada mahasiswa yang cocok dengan pencarian.</td></tr>');
                return;
            }

            renderPendingRows(filtered);
        }

        function renderValidationSummary(summary) {
            if (!summary) {
                return '<div class="text-muted text-center py-3">Validasi belum tersedia.</div>';
            }

            const currentSks = Number(summary.total_sks ?? 0);
            const maxSks = Number(summary.max_sks_allowed ?? 24);
            const percentage = Math.min(100, Math.round((currentSks / (maxSks || 1)) * 100));

            return `
                <div class="detail-box-modern mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark"><i class="fas fa-shield-alt text-primary me-1"></i> Ringkasan Validasi KRS</div>
                        <span class="badge ${summary.is_valid ? 'badge-soft-primary' : 'badge-soft-secondary'}">
                            <i class="fas ${summary.is_valid ? 'fa-check-circle' : 'fa-info-circle'} me-1"></i>
                            ${summary.is_valid ? 'Valid untuk Diproses' : 'Perlu Diperiksa'}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small fw-bold text-secondary mb-1">
                            <span>Beban Pengambilan SKS</span>
                            <span class="text-primary">${currentSks} / ${maxSks} SKS (${percentage}%)</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 20px; background-color: #f1f5f9;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${percentage}%; border-radius: 20px;" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="p-2 border rounded-3 bg-light-soft">
                                <div class="small text-muted">Jumlah MK</div>
                                <div class="fw-bold text-dark">${escapeHtml(summary.total_matkul ?? 0)} MK</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded-3 bg-light-soft">
                                <div class="small text-muted">Batas Maks. SKS</div>
                                <div class="fw-bold ${summary.max_sks_ok ? 'text-primary' : 'text-danger'}">${escapeHtml(maxSks)} SKS</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded-3 bg-light-soft">
                                <div class="small text-muted">Sisa Kuota SKS</div>
                                <div class="fw-bold text-info">${escapeHtml(summary.remaining_sks ?? 0)} SKS</div>
                            </div>
                        </div>
                    </div>

                    ${summary.is_sks_override ? `
                        <div class="alert alert-info border-0 shadow-sm mt-3 mb-0 p-2 small rounded-3 d-flex align-items-center">
                            <i class="fas fa-shield-alt text-primary fa-lg me-2"></i>
                            <div><strong>Batas SKS dioverride:</strong> ${escapeHtml(summary.sks_override_reason || 'Override administratif')}</div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        function renderDetailsTable(details) {
            if (!Array.isArray(details) || !details.length) {
                return '<div class="text-muted text-center py-3">Belum ada rincian mata kuliah pada KRS ini.</div>';
            }

            let rows = '';
            details.forEach((detail, index) => {
                const kelas = detail.kelas_kuliah || detail.kelasKuliah;
                const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
                const mk = kmk?.mata_kuliah || kmk?.mataKuliah;
                const jadwal = Array.isArray(kelas?.jadwal) && kelas.jadwal.length ?
                    kelas.jadwal.map(j => `<span class="badge bg-light text-dark border me-1"><i class="far fa-clock text-info me-1"></i>${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}</span>`).join(' ') :
                    '<span class="text-muted">-</span>';

                rows += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="fw-bold font-monospace text-primary">${escapeHtml(mk?.kode_mk)}</td>
                        <td class="fw-semibold text-dark">${escapeHtml(mk?.nama_mk)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHtml(kelas?.nama_kelas)}</span></td>
                        <td class="text-center fw-bold text-dark">${escapeHtml(mk?.sks ?? 0)}</td>
                        <td>${jadwal}</td>
                    </tr>
                `;
            });

            return `
                <div class="table-responsive rounded-3 border mb-3">
                    <table class="table table-pa align-middle">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Kode MK</th>
                                <th>Mata Kuliah</th>
                                <th>Kelas</th>
                                <th class="text-center">SKS</th>
                                <th>Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            `;
        }

        function loadDetail(id) {
            selectedKrsId = id;

            $('#detailContainer').html(`
                <div class="text-center py-5">
                    <div class="stat-icon-wrapper stat-icon-primary mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.25rem;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="text-muted small">Memuat detail pengajuan KRS...</div>
                </div>
            `);

            getDetailModal()?.show();

            $.get(routes.showTemplate.replace('__ID__', id))
                .done(function(response) {
                    const data = response.data || {};
                    const mahasiswa = data.mahasiswa || {};
                    const semester = data.semester || {};

                    const isRpl = ['RPL', 'Pindahan'].includes(mahasiswa.jenis_pendaftaran);
                    const jalurBadge = isRpl
                        ? `<span class="badge badge-soft-info">${escapeHtml(mahasiswa.jenis_pendaftaran)}</span> <small class="text-muted">(${escapeHtml(mahasiswa.sks_diakui || 0)} SKS Konversi)</small>`
                        : `<span class="badge badge-soft-secondary">${escapeHtml(mahasiswa.jenis_pendaftaran || 'Reguler')}</span>`;

                    let html = `
                        <!-- Student Profile Banner -->
                        <div class="detail-box-modern mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-icon-wrapper stat-icon-primary" style="width: 42px; height: 42px; border-radius: 10px; font-size: 1.15rem; margin-left: 0;">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">${escapeHtml(mahasiswa.nama_mahasiswa || '-')}</h5>
                                        <small class="text-muted font-monospace">${escapeHtml(mahasiswa.nim || '-')}</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-soft-primary px-3 py-2 fw-bold" style="font-size: 0.9rem;">
                                        <i class="fas fa-layer-group me-1"></i> Total ${escapeHtml(data.total_sks ?? 0)} SKS
                                    </span>
                                </div>
                            </div>
                            <div class="row g-3 small">
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Jalur Masuk:</span>
                                    <div class="mt-1">${jalurBadge}</div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Semester Aktif:</span>
                                    <strong class="text-dark">${escapeHtml(formatSemester(semester))}</strong>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Status Approval:</span>
                                    <span class="badge badge-soft-info mt-1">${escapeHtml(data.status_approval || 'Pending')}</span>
                                </div>
                            </div>
                        </div>
                    `;

                    html += renderValidationSummary(data.validation_summary);

                    if (data.catatan) {
                        html += `
                            <div class="alert alert-info border-0 shadow-sm mb-3 p-3 rounded-3 d-flex align-items-center">
                                <i class="fas fa-comment-alt text-info fa-lg me-2"></i>
                                <div>
                                    <strong>Catatan Pengajuan:</strong>
                                    <div class="text-secondary small mt-1">${escapeHtml(data.catatan)}</div>
                                </div>
                            </div>
                        `;
                    }

                    if (data.sks_override?.is_active) {
                        const overrideBy = data.sks_override?.by?.name ? ` oleh ${escapeHtml(data.sks_override.by.name)}` : '';
                        html += `
                            <div class="alert alert-info border-0 shadow-sm mb-3 p-3 rounded-3 d-flex align-items-center">
                                <i class="fas fa-shield-alt text-primary fa-lg me-2"></i>
                                <div>
                                    <strong>Override SKS Aktif${overrideBy}</strong>
                                    <div class="text-secondary small">${escapeHtml(data.sks_override?.reason || 'Override administratif')}</div>
                                </div>
                            </div>
                        `;
                    }

                    html += renderDetailsTable(data.details || []);

                    html += `
                        <!-- Decision & Feedback Form -->
                        <div class="detail-box-modern">
                            <div class="fw-bold text-dark mb-2">
                                <i class="fas fa-pen-alt text-primary me-1"></i> Catatan Dosen PA
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control rounded-3" id="catatanDosen" rows="3" placeholder="Tuliskan catatan, saran, atau arahan revisi untuk mahasiswa..."></textarea>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Catatan wajib diisi jika Anda mengembalikan untuk revisi atau menolak pengajuan.</small>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center pt-2 border-top">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </button>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" ${data.can_revision ? '' : 'disabled'} onclick="submitDecision('reject')">
                                        <i class="fas fa-times me-1"></i> Tolak
                                    </button>
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3" ${data.can_revision ? '' : 'disabled'} onclick="submitDecision('revision')">
                                        <i class="fas fa-edit me-1"></i> Revisi
                                    </button>
                                    <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm" ${data.can_approve ? '' : 'disabled'} onclick="submitDecision('approve')">
                                        <i class="fas fa-check-circle me-1"></i> Setujui KRS
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#detailContainer').html(html);
                })
                .fail(function(xhr) {
                    $('#detailContainer').html('<div class="alert alert-danger mb-0">Gagal memuat rincian KRS mahasiswa. Silakan tutup dan coba kembali.</div>');
                    notify(xhr.responseJSON?.message || 'Gagal memuat detail KRS.', 'danger');
                });
        }

        function submitDecision(action) {
            if (!selectedKrsId) {
                notify('Pilih detail KRS terlebih dahulu.', 'warning');
                return;
            }

            const catatan = $('#catatanDosen').val();
            const routeMap = {
                approve: routes.approve,
                revision: routes.revision,
                reject: routes.reject,
            };

            const labels = {
                approve: 'menyetujui',
                revision: 'mengembalikan untuk revisi',
                reject: 'menolak',
            };

            if (action !== 'approve' && !catatan.trim()) {
                notify('Catatan wajib diisi untuk revisi atau penolakan.', 'warning');
                return;
            }

            const actionConfig = {
                approve: {
                    title: 'Setujui KRS ini?',
                    text: 'KRS mahasiswa akan disetujui dan status mahasiswa siap mengikuti perkuliahan.',
                    icon: 'question',
                    confirmButtonText: 'Ya, Setujui'
                },
                revision: {
                    title: 'Kembalikan untuk Revisi?',
                    text: 'Mahasiswa akan diminta memperbaiki KRS sesuai catatan yang Anda berikan.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Kembalikan'
                },
                reject: {
                    title: 'Tolak Pengajuan KRS?',
                    text: 'Pengajuan KRS mahasiswa akan ditolak.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Tolak'
                }
            };

            Swal.fire({
                title: actionConfig[action].title,
                text: actionConfig[action].text,
                icon: actionConfig[action].icon,
                showCancelButton: true,
                confirmButtonText: actionConfig[action].confirmButtonText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: routeMap[action],
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        id_krs: selectedKrsId,
                        catatan: catatan
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: `Sedang ${labels[action]} KRS mahasiswa.`,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (!response.success) {
                            notify(response.message || 'Gagal memproses KRS.', 'danger');
                            return;
                        }

                        notify(response.message || 'KRS berhasil diproses.', 'success');
                        selectedKrsId = null;

                        // Hide modal
                        getDetailModal()?.hide();

                        loadStatistics();
                        loadPendingList();
                    },
                    error: function(xhr) {
                        Swal.close();
                        notify(xhr.responseJSON?.message || 'Gagal memproses KRS.', 'danger');
                    }
                });
            });
        }

        $('#searchPendingInput').on('keyup', function() {
            applyPendingSearchFilter();
        });

        $('#refreshPendingBtn').on('click', function() {
            loadStatistics();
            loadPendingList();
        });

        $(document).ready(function() {
            loadStatistics();
            loadPendingList();
        });
    </script>
@endpush
