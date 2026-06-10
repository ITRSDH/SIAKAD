@extends('layouts.index')
@section('title', 'KRS Mahasiswa')

@push('styles-custom')
    <style>
        .modal-xxl {
            max-width: 95% !important;
        }

        .summary-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            background: #fff;
            height: 100%;
        }

        .summary-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .summary-value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .badge-status {
            font-size: 0.85rem;
        }

        .empty-state {
            border: 1px dashed #ced4da;
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
        }

        .package-box {
            border: 1px solid #e8edf3;
            border-radius: 0.75rem;
            background: #fafcff;
            padding: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">KRS Mahasiswa</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="">Mahasiswa</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="">KRS</a></li>
            </ul>
        </div>

        <div class="row g-3 mb-3" id="summarySection">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Semester Aktif</div>
                    <div class="summary-value" id="semesterAktifLabel">-</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Status KRS</div>
                    <div class="summary-value"><span id="statusBadge" class="badge bg-secondary badge-status">-</span></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total SKS</div>
                    <div class="summary-value" id="totalSksLabel">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Batas Maksimal SKS</div>
                    <div class="summary-value" id="maxSksLabel">0</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Nama</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nama_mahasiswa" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">NIM</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="nim" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Program Studi</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="prodi" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Tahun Akademik</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="tahun_akademik" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Angkatan</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="angkatan" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Semester</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="semester" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">KRS Semester Aktif</h4>
                            <small class="text-muted" id="krsMetaInfo">Memuat data KRS...</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                                <i class="fas fa-sync me-1"></i> Refresh
                            </button>
                            <button class="btn btn-sm btn-outline-primary" id="historyBtn">
                                <i class="fas fa-clock-rotate-left me-1"></i> Riwayat KRS
                            </button>
                            <button class="btn btn-sm btn-outline-success d-none" id="printBtn">
                                <i class="fas fa-print me-1"></i> Cetak KRS
                            </button>
                            <button class="btn btn-sm btn-primary d-none" id="createDraftBtn">
                                <i class="fas fa-file-circle-plus me-1"></i> Buat Draft KRS
                            </button>
                            <button class="btn btn-sm btn-primary d-none" id="openModalBtn">
                                <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah Manual
                            </button>
                            <button class="btn btn-sm btn-success d-none" id="submitBtn">
                                <i class="fas fa-paper-plane me-1"></i> Ajukan KRS
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="emptyState" class="empty-state d-none">
                            <h5 class="mb-2" id="emptyStateTitle">Menyiapkan KRS semester aktif</h5>
                            <p class="text-muted mb-0" id="emptyStateDescription">Sistem sedang memeriksa penawaran mata kuliah untuk semester aktif.</p>
                        </div>

                        <div id="krsContent" class="d-none">
                            <div class="alert alert-info d-none" id="catatanBox"></div>
                            <div class="row g-3 mb-3">
                                <div class="col-lg-6">
                                    <div class="package-box" id="packageSummaryBox">
                                        <div class="text-muted">Ringkasan paket semester belum tersedia.</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="package-box" id="packageIssueBox">
                                        <div class="text-muted">Kendala generate paket akan muncul di sini bila ada.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kode MK</th>
                                            <th>Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th>SKS</th>
                                            <th>Kategori</th>
                                            <th>Jadwal</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedCoursesBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Memuat data...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Ringkasan Validasi</h4>
                    </div>
                    <div class="card-body" id="validationBox">
                        <div class="text-muted">Memuat validasi...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPenawaran" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xxl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Penawaran Mata Kuliah Manual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th>Kelas</th>
                                        <th>Kategori</th>
                                        <th>Semester Paket</th>
                                        <th>SKS</th>
                                        <th>Jadwal</th>
                                        <th>Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="availableCoursesBody">
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalRiwayatKrs" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat KRS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                        <th>Total SKS</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="historyCoursesBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Memuat riwayat KRS...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDetailKrs" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail KRS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <div class="summary-card">
                                    <div class="summary-label">Semester</div>
                                    <div class="summary-value" id="detailSemesterLabel">-</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="summary-card">
                                    <div class="summary-label">Status</div>
                                    <div class="summary-value"><span id="detailStatusBadge"
                                            class="badge bg-secondary badge-status">-</span></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="summary-card">
                                    <div class="summary-label">Total SKS</div>
                                    <div class="summary-value" id="detailTotalSksLabel">0</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="summary-card">
                                    <div class="summary-label">Mata Kuliah</div>
                                    <div class="summary-value" id="detailTotalMkLabel">0</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info d-none" id="detailCatatanBox"></div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th>Kelas</th>
                                        <th>SKS</th>
                                        <th>Jadwal</th>
                                    </tr>
                                </thead>
                                <tbody id="detailCoursesBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Pilih riwayat KRS untuk melihat
                                            detail.</td>
                                    </tr>
                                </tbody>
                            </table>
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
        // ================================
        // GLOBAL VARIABLES & CONFIGURATION
        // ================================

        // Route definitions for API endpoints
        const routes = {
            current: "{{ route('krs.current') }}",
            initCurrent: "{{ route('krs.current.init') }}",
            available: "{{ route('krs.penawaran') }}",
            repeatCandidates: "{{ route('krs.repeat-candidates') }}",
            history: "{{ route('krs.data') }}",
            statistics: "{{ route('krs.statistics') }}",
            showTemplate: "{{ route('krs.show', ['id' => '__KRS__']) }}",
            printTemplate: "{{ route('krs.print', ['id' => '__KRS__']) }}",
            validation: "{{ route('krs.validation-summary') }}",
            add: "{{ route('krs.add-mata-kuliah') }}",
            submit: "{{ route('krs.submit') }}",
            removeTemplate: "{{ route('krs.remove-mata-kuliah', ['krsId' => '__KRS__', 'kelasKuliahId' => '__KELAS__']) }}",
        };

        // State management variables
        let currentKrs = null;
        let currentSemesterNumber = null;
        let packageSummary = null;
        let unresolvedPackageItems = [];
        let penawaranModalInstance = null;
        let historyModalInstance = null;
        let detailModalInstance = null;
        let autoDraftAttempted = false;

        // ================================
        // HELPER FUNCTIONS
        // ================================

        /**
         * Escape HTML to prevent XSS attacks
         * @param {string} value
         * @returns {string}
         */
        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        /**
         * Get modal instances for Bootstrap modals
         * @param {string} elementId
         * @param {string} modalType
         * @returns {object|null}
         */
        function getPenawaranModal() {
            return getModalInstance('modalPenawaran', 'penawaran');
        }

        function getHistoryModal() {
            return getModalInstance('modalRiwayatKrs', 'history');
        }

        function getDetailModal() {
            return getModalInstance('modalDetailKrs', 'detail');
        }

        /**
         * Generic modal instance handler
         * @param {string} elementId
         * @param {string} modalType
         * @returns {object|null}
         */
        function getModalInstance(elementId, modalType) {
            const modalElement = document.getElementById(elementId);
            if (!modalElement) {
                return null;
            }

            if (window.bootstrap && window.bootstrap.Modal) {
                if (modalType === 'penawaran' && !penawaranModalInstance) {
                    penawaranModalInstance = new window.bootstrap.Modal(modalElement);
                }
                if (modalType === 'history' && !historyModalInstance) {
                    historyModalInstance = new window.bootstrap.Modal(modalElement);
                }
                if (modalType === 'detail' && !detailModalInstance) {
                    detailModalInstance = new window.bootstrap.Modal(modalElement);
                }

                return {
                    penawaran: penawaranModalInstance,
                    history: historyModalInstance,
                    detail: detailModalInstance,
                } [modalType];
            }

            return {
                show() {
                    if (window.jQuery) {
                        window.jQuery(modalElement).modal('show');
                    }
                },
                hide() {
                    if (window.jQuery) {
                        window.jQuery(modalElement).modal('hide');
                    }
                }
            };
        }

        /**
         * Get status configuration for KRS approval
         * @param {string} status
         * @returns {object}
         */
        function statusConfig(status) {
            const config = {
                revised: {
                    text: 'Draft / Revisi',
                    className: 'bg-warning text-dark'
                },
                pending: {
                    text: 'Menunggu Persetujuan',
                    className: 'bg-info text-dark'
                },
                approved: {
                    text: 'Disetujui',
                    className: 'bg-success'
                },
                rejected: {
                    text: 'Ditolak',
                    className: 'bg-danger'
                }
            };

            return config[status] || {
                text: status || '-',
                className: 'bg-secondary'
            };
        }

        /**
         * Format semester display text
         * @param {object} semesterAktif
         * @returns {string}
         */
        function formatSemester(semesterAktif) {
            if (!semesterAktif) return '-';

            const namaSemester = semesterAktif.nama_semester || '-';
            const tahunAkademik = semesterAktif.tahun_akademik?.tahun_akademik || '';

            return tahunAkademik ? `${tahunAkademik} ${namaSemester}` : namaSemester;
        }

        function formatAcademicYear(semesterAktif) {
            return semesterAktif?.tahun_akademik?.tahun_akademik || '-';
        }

        function formatSemesterStudyLabel(semesterAktif, semesterTempuh) {
            const namaSemester = semesterAktif?.nama_semester || '-';

            if (!semesterTempuh || Number(semesterTempuh) < 1) {
                return `${namaSemester} | Belum masuk semester studi aktif`;
            }

            return `${namaSemester} | Semester Tempuh ${semesterTempuh}`;
        }

        /**
         * Format program study display text
         * @param {object} prodi
         * @returns {string}
         */
        function formatProdi(prodi) {
            if (!prodi) return '-';

            const namaProdi = prodi.nama_prodi || '-';
            const jenjang = prodi.jenjang_pendidikan || '-';

            return namaProdi ? `(${jenjang}) ${namaProdi}` : jenjang;
        }

        function getCourseCategory(detail, semesterNumber) {
            if (detail?.kategori_pengambilan) {
                const map = {
                    paket: {
                        text: 'Paket',
                        className: 'bg-primary'
                    },
                    ulang: {
                        text: 'Ulang',
                        className: 'bg-warning text-dark'
                    },
                    tambahan: {
                        text: 'Tambahan',
                        className: 'bg-secondary'
                    }
                };

                return map[detail.kategori_pengambilan] || map.tambahan;
            }

            const kelas = detail?.kelas_kuliah || detail?.kelasKuliah;
            const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
            const semesterKe = Number(kmk?.semester_ke || 0);

            if (semesterNumber && semesterKe === Number(semesterNumber)) {
                return {
                    text: 'Paket',
                    className: 'bg-primary'
                };
            }

            if (semesterNumber && semesterKe > 0 && semesterKe < Number(semesterNumber)) {
                return {
                    text: 'Ulang',
                    className: 'bg-warning text-dark'
                };
            }

            return {
                text: 'Tambahan',
                className: 'bg-secondary'
            };
        }

        function getOfferedCourseCategory(item, semesterNumber) {
            const semesterKe = Number(item?.semester_ke || 0);
            const currentSemester = Number(semesterNumber || 0);

            if (currentSemester > 0 && semesterKe === currentSemester) {
                return {
                    text: 'Paket',
                    className: 'bg-primary'
                };
            }

            if (currentSemester > 0 && semesterKe > 0 && semesterKe < currentSemester) {
                return {
                    text: 'Ulang',
                    className: 'bg-warning text-dark'
                };
            }

            return {
                text: 'Tambahan',
                className: 'bg-secondary'
            };
        }

        function buildPackageSummaryFromKrs(krs, semesterNumber) {
            const details = krs?.details || [];
            let packageCount = 0;
            let packageSks = 0;
            let repeatCount = 0;
            let repeatSks = 0;

            details.forEach(detail => {
                const kelas = detail?.kelas_kuliah || detail?.kelasKuliah;
                const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
                const mk = kmk?.mata_kuliah || kmk?.mataKuliah;
                const semesterKe = Number(kmk?.semester_ke || 0);
                const sks = Number(mk?.sks || 0);

                if (semesterNumber && semesterKe === Number(semesterNumber)) {
                    packageCount++;
                    packageSks += sks;
                    return;
                }

                if (semesterNumber && semesterKe > 0 && semesterKe < Number(semesterNumber)) {
                    repeatCount++;
                    repeatSks += sks;
                }
            });

            return {
                semester_ke: semesterNumber,
                generated_count: packageCount,
                generated_sks: packageSks,
                repeat_count: repeatCount,
                repeat_sks: repeatSks,
                unresolved_count: unresolvedPackageItems.length,
            };
        }

        function renderPackageInsights(krs, semesterNumber) {
            const summary = packageSummary || buildPackageSummaryFromKrs(krs, semesterNumber);

            $('#packageSummaryBox').html(`
                <div class="fw-semibold mb-2">Ringkasan Paket Semester</div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Semester tempuh</span>
                    <span>${escapeHtml(summary?.semester_ke ?? semesterNumber ?? '-')}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Mata kuliah paket</span>
                    <span>${escapeHtml(summary?.generated_count ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>SKS paket</span>
                    <span>${escapeHtml(summary?.generated_sks ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Mata kuliah ulang di KRS</span>
                    <span>${escapeHtml(summary?.repeat_count ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>SKS ulang di KRS</span>
                    <span>${escapeHtml(summary?.repeat_sks ?? 0)}</span>
                </div>
            `);

            if (!Array.isArray(unresolvedPackageItems) || !unresolvedPackageItems.length) {
                $('#packageIssueBox').html(`
                    <div class="fw-semibold mb-2">Kendala Paket</div>
                    <div class="text-success">Semua paket yang bisa digenerate sudah berhasil dimasukkan ke draft KRS.</div>
                `);
                return;
            }

            const items = unresolvedPackageItems.map(item => {
                const title = item?.kode_mk ? `${item.kode_mk} - ${item.nama_mk}` : 'Item paket';
                return `<li><strong>${escapeHtml(title)}</strong>: ${escapeHtml(item?.reason || 'Belum dapat digenerate')}</li>`;
            }).join('');

            $('#packageIssueBox').html(`
                <div class="fw-semibold mb-2">Kendala Paket</div>
                <ul class="mb-0 ps-3">${items}</ul>
            `);
        }

        function flattenRepeatCandidates(items) {
            const rows = [];

            (items || []).forEach(item => {
                const history = item?.riwayat_terakhir || {};
                const historySemester = formatSemester(history?.semester);
                const historyLabel = `${historySemester} | ${history?.nilai_huruf || '-'} (${history?.bobot_nilai ?? '-'})`;

                const kelasTersedia = Array.isArray(item?.kelas_tersedia) ? item.kelas_tersedia : [];
                if (!kelasTersedia.length) {
                    rows.push({
                        id: null,
                        kode_mk: item.kode_mk,
                        mata_kuliah: item.nama_mk,
                        nama_kelas: '-',
                        sks: item.sks,
                        riwayat: historyLabel,
                        jadwal: [],
                        is_available: false,
                        availability_reason: item.availability_reason || 'Belum ada kelas aktif untuk mata kuliah ini pada semester berjalan.',
                    });
                    return;
                }

                kelasTersedia.forEach(kelas => {
                    rows.push({
                        id: kelas.id_kelas_kuliah,
                        kode_mk: item.kode_mk,
                        mata_kuliah: item.nama_mk,
                        nama_kelas: kelas.nama_kelas,
                        sks: kelas.sks,
                        riwayat: historyLabel,
                        jadwal: kelas.jadwal,
                        is_available: kelas.is_available,
                        availability_reason: kelas.availability_reason,
                    });
                });
            });

            return rows;
        }

        // ================================
        // RENDER FUNCTIONS
        // ================================

        /**
         * Render validation summary display
         * @param {object} summary
         */
        function renderValidationSummary(summary) {
            if (!summary) {
                $('#validationBox').html('<div class="text-muted">Validasi belum tersedia.</div>');
                return;
            }

            const checks = [{
                    label: 'Jumlah mata kuliah dipilih',
                    value: summary.total_matkul ?? 0,
                    ok: summary.has_items
                },
                {
                    label: 'Maksimal SKS',
                    value: `${summary.total_sks ?? 0} / ${summary.max_sks_allowed ?? 0}`,
                    ok: summary.max_sks_ok
                },
                {
                    label: 'Bentrok jadwal',
                    value: summary.schedule_conflict ? 'Ada bentrok' : 'Aman',
                    ok: !summary.schedule_conflict
                },
                {
                    label: 'Sisa SKS',
                    value: summary.remaining_sks ?? 0,
                    ok: true
                }
            ];

            let html = '<ul class="list-group list-group-flush">';
            checks.forEach(item => {
                html += `
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>${escapeHtml(item.label)}</div>
                                            <div class="text-end">
                                                <div>${escapeHtml(item.value)}</div>
                                                <small class="${item.ok ? 'text-success' : 'text-danger'}">${item.ok ? 'OK' : 'Belum valid'}</small>
                                            </div>
                                        </li>
                                    `;
            });
            html += '</ul>';

            if (summary.can_submit) {
                html += '<div class="alert alert-success mt-3 mb-0">KRS sudah valid dan siap diajukan.</div>';
            } else {
                html +=
                    '<div class="alert alert-warning mt-3 mb-0">KRS belum dapat diajukan. Lengkapi validasinya terlebih dahulu.</div>';
            }

            if (summary.is_sks_override) {
                html += `<div class="alert alert-info mt-3 mb-0">Batas maksimal SKS telah dioverride. Alasan: ${escapeHtml(summary.sks_override_reason || 'Override administratif')}</div>`;
            }

            $('#validationBox').html(html);
        }

        /**
         * Render selected courses table
         * @param {object} krs
         */
        function renderSelectedCourses(krs) {
            const details = krs?.details || [];

            if (!details.length) {
                $('#selectedCoursesBody').html(`
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Belum ada mata kuliah yang dipilih.</td>
                                        </tr>
                                    `);
                return;
            }

            let rows = '';
            details.forEach((detail, index) => {
                const kelas = detail.kelas_kuliah || detail.kelasKuliah;
                const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
                const mk = kmk?.mata_kuliah || kmk?.mataKuliah;
                const jadwalList = kelas?.jadwal || [];
                const category = getCourseCategory(detail, currentSemesterNumber);
                const jadwalText = jadwalList.length ?
                    jadwalList.map(j => `${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}`).join('<br>') :
                    '-';

                const removeButton = krs.can_edit ?
                    `<button class="btn btn-sm btn-outline-danger" onclick="removeCourse('${krs.id}', '${kelas?.id}')"><i class="fas fa-trash"></i></button>` :
                    '<span class="text-muted">-</span>';

                rows += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${escapeHtml(mk?.kode_mk)}</td>
                                            <td>${escapeHtml(mk?.nama_mk)}</td>
                                            <td>${escapeHtml(kelas?.nama_kelas)}</td>
                                            <td>${escapeHtml(mk?.sks ?? 0)}</td>
                                            <td><span class="badge ${category.className}">${escapeHtml(category.text)}</span></td>
                                            <td>${jadwalText}</td>
                                            <td class="text-center">${removeButton}</td>
                                        </tr>
                                    `;
            });

            $('#selectedCoursesBody').html(rows);
        }

        /**
         * Extract history items from response payload
         * @param {any} payload
         * @returns {array}
         */
        function extractHistoryItems(payload) {
            if (Array.isArray(payload)) {
                return payload;
            }

            if (Array.isArray(payload?.data)) {
                return payload.data;
            }

            if (Array.isArray(payload?.items)) {
                return payload.items;
            }

            return [];
        }

        /**
         * Render history rows table
         * @param {array} items
         */
        function renderHistoryRows(items) {
            if (!items.length) {
                $('#historyCoursesBody').html(
                    '<tr><td colspan="6" class="text-center text-muted">Belum ada riwayat KRS.</td></tr>');
                return;
            }

            let rows = '';
            items.forEach((item, index) => {
                const semesterLabel = item.semester_aktif || '-';

                const status = statusConfig(item.status_approval);
                const printButton = item.status_approval === 'approved' ?
                    `<button class="btn btn-sm btn-outline-success ms-1" onclick="printKrs('${item.id}')">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>` :
                    '';

                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(formatSemester(semesterLabel))}</td>
                        <td><span class="badge ${status.className} badge-status">${escapeHtml(status.text)}</span></td>
                        <td>${escapeHtml(item.total_sks ?? 0)}</td>
                        <td>${escapeHtml(item.catatan || '-')}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary" onclick="showHistoryDetail('${item.id}')">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </button>
                                ${printButton}
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#historyCoursesBody').html(rows);
        }

        /**
         * Render KRS detail modal content
         * @param {object} krs
         */
        function renderKrsDetailModal(krs) {
            const details = krs?.details || [];
            const status = statusConfig(krs?.status_approval);
            const semesterLabel = krs?.semester_aktif || '-';

            $('#detailSemesterLabel').text(formatSemester(semesterLabel));
            $('#detailStatusBadge').attr('class', `badge badge-status ${status.className}`).text(status.text);
            $('#detailTotalSksLabel').text(krs?.total_sks ?? 0);
            $('#detailTotalMkLabel').text(details.length);

            if (krs?.catatan) {
                $('#detailCatatanBox').removeClass('d-none').html(`<strong>Catatan:</strong> ${escapeHtml(krs.catatan)}`);
            } else {
                $('#detailCatatanBox').addClass('d-none').empty();
            }

            if (!details.length) {
                $('#detailCoursesBody').html(
                    '<tr><td colspan="6" class="text-center text-muted">Belum ada detail mata kuliah.</td></tr>');
                return;
            }

            let rows = '';
            details.forEach((detail, index) => {
                const kelas = detail.kelas_kuliah || detail.kelasKuliah;
                const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
                const mk = kmk?.mata_kuliah || kmk?.mataKuliah;
                const jadwalList = kelas?.jadwal || [];
                const jadwalText = jadwalList.length ?
                    jadwalList.map(j => `${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}`).join('<br>') :
                    '-';

                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(mk?.kode_mk)}</td>
                        <td>${escapeHtml(mk?.nama_mk)}</td>
                        <td>${escapeHtml(kelas?.nama_kelas)}</td>
                        <td>${escapeHtml(mk?.sks ?? 0)}</td>
                        <td>${jadwalText}</td>
                    </tr>
                `;
            });

            $('#detailCoursesBody').html(rows);
        }

        /**
         * Render main KRS state and UI
         * @param {object} payload
         */
        function renderKrsState(payload) {
            const semesterAktif = payload?.semester_aktif || null;
            const prodi = payload?.mahasiswa?.prodi || null;
            currentKrs = payload?.krs || null;
            mahasiswa = payload?.mahasiswa || null;
            currentSemesterNumber = payload?.semester_saat_ini || currentKrs?.semester_ke || null;
            packageSummary = currentKrs?.package_summary || null;
            unresolvedPackageItems = currentKrs?.unresolved_package_items || [];

            $('#semesterAktifLabel').text(formatSemester(semesterAktif));

            $('#nama_mahasiswa').val(mahasiswa?.nama_mahasiswa || '');
            $('#nim').val(mahasiswa?.nim || '');
            $('#prodi').val(formatProdi(prodi) || '');
            $('#angkatan').val(mahasiswa?.angkatan || '');
            $('#tahun_akademik').val(formatAcademicYear(semesterAktif));
            $('#semester').val(formatSemesterStudyLabel(semesterAktif, payload?.semester_saat_ini));

            if (!currentKrs) {
                $('#statusBadge').attr('class', 'badge bg-secondary badge-status').text('Belum Ada KRS');
                $('#totalSksLabel').text('0');
                $('#maxSksLabel').text('0');
                $('#krsMetaInfo').removeClass('d-none');
                $('#krsMetaInfo').text(payload?.eligibility_message || 'KRS semester aktif belum tersedia.');
                $('#emptyState').removeClass('d-none');
                $('#krsContent').addClass('d-none');
                $('#createDraftBtn, #createDraftBtnEmpty, #openModalBtn, #submitBtn, #printBtn').addClass('d-none');
                packageSummary = null;
                unresolvedPackageItems = [];

                if (payload?.can_auto_init && !autoDraftAttempted) {
                    $('#emptyStateTitle').text('Menyiapkan KRS semester aktif');
                    $('#emptyStateDescription').text('Sistem sedang membuat draft KRS dan menyiapkan mata kuliah paket otomatis.');
                } else if (payload?.is_krs_eligible === false) {
                    $('#emptyStateTitle').text('KRS belum tersedia untuk angkatan ini');
                    $('#emptyStateDescription').text(payload?.eligibility_message || 'Semester aktif saat ini belum sesuai dengan angkatan mahasiswa.');
                    $('#validationBox').html(`
                        <div class="alert alert-warning mb-0">
                            <div class="fw-semibold mb-1">KRS belum dapat diproses</div>
                            <div>${escapeHtml(payload?.eligibility_message || 'Periode akademik aktif belum berlaku untuk mahasiswa ini.')}</div>
                            <hr>
                            <div class="small text-muted">
                                Contoh: jika periode aktif masih 2025/2026 Ganjil, maka mahasiswa angkatan 2026 belum masuk semester 1 dan penawaran mata kuliah tidak akan ditampilkan.
                            </div>
                        </div>
                    `);
                } else {
                    $('#emptyStateTitle').text('KRS semester aktif belum tersedia');
                    $('#emptyStateDescription').text(payload?.eligibility_message || 'Draft KRS belum berhasil disiapkan untuk semester aktif.');
                }

                if (payload?.is_krs_eligible !== false) {
                    renderValidationSummary(null);
                }
                return;
            }

            const status = statusConfig(currentKrs.status_approval);
            const summary = currentKrs.validation_summary || {};

            $('#statusBadge')
                .attr('class', `badge badge-status ${status.className}`)
                .text(status.text);
            $('#totalSksLabel').text(currentKrs.total_sks ?? 0);
            $('#maxSksLabel').text(summary.max_sks_allowed ?? 0);
            // $('#krsMetaInfo').text(`ID KRS: ${currentKrs.id}`);
            $('#krsMetaInfo').addClass('d-none');

            $('#emptyState').addClass('d-none');
            $('#krsContent').removeClass('d-none');
            $('#createDraftBtn').addClass('d-none');
            $('#openModalBtn').toggleClass('d-none', !currentKrs.can_edit);
            $('#submitBtn').toggleClass('d-none', !currentKrs.can_submit);
            $('#printBtn').toggleClass('d-none', currentKrs.status_approval !== 'approved');

            if (currentKrs.catatan) {
                $('#catatanBox').removeClass('d-none').html(`<strong>Catatan:</strong> ${escapeHtml(currentKrs.catatan)}`);
            } else {
                $('#catatanBox').addClass('d-none').empty();
            }

            renderSelectedCourses(currentKrs);
            renderValidationSummary(summary);
            renderPackageInsights(currentKrs, currentSemesterNumber);
        }

        // ================================
        // NOTIFICATION FUNCTIONS
        // ================================

        /**
         * Show notification alert
         * @param {string} message
         * @param {string} type
         */
        function notify(message, type = 'info') {
            const alertClass = {
                success: 'alert-success',
                danger: 'alert-danger',
                warning: 'alert-warning',
                info: 'alert-info'
            } [type] || 'alert-info';

            const html =
                `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            $('.page-inner').prepend(html);

            setTimeout(() => {
                $('.page-inner .alert').first().alert('close');
            }, 4000);
        }

        // ================================
        // API CALL FUNCTIONS
        // ================================

        /**
         * Load current KRS data
         */
        function loadCurrentKrs() {
            $.ajax({
                url: routes.current,
                method: 'GET',
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat KRS.', 'danger');
                        return;
                    }

                    renderKrsState(response.data);

                    if (!response.data?.krs && response.data?.can_auto_init && !autoDraftAttempted) {
                        autoDraftAttempted = true;
                        createDraft({
                            silent: true,
                            auto: true
                        });
                    }
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat KRS semester aktif.', 'danger');
                }
            });
        }

        /**
         * Create new KRS draft
         */
        function createDraft(options = {}) {
            $.ajax({
                url: routes.initCurrent,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        if (!options.silent) {
                            notify(response.message || 'Gagal membuat draft KRS.', 'danger');
                        }
                        return;
                    }

                    packageSummary = response.data?.package_summary || null;
                    unresolvedPackageItems = response.data?.unresolved_package_items || [];

                    if (!options.silent && packageSummary) {
                        const generated = packageSummary.generated_count ?? 0;
                        const unresolved = packageSummary.unresolved_count ?? 0;
                        notify(`Draft KRS berhasil dibuat. Paket tergenerate: ${generated} mata kuliah. Kendala paket: ${unresolved}.`, unresolved > 0 ? 'warning' : 'success');
                    } else if (!options.silent) {
                        notify(response.message || 'Draft KRS berhasil dibuat.', 'success');
                    }

                    loadCurrentKrs();
                },
                error: function(xhr) {
                    if (!options.silent || options.auto) {
                        notify(xhr.responseJSON?.message || 'Gagal membuat draft KRS.', 'danger');
                    }
                }
            });
        }

        /**
         * Load available courses for selection
         */
        function loadAvailableCourses() {
            if (!currentKrs?.id) {
                notify('Draft KRS belum tersedia.', 'warning');
                return;
            }

            $('#availableCoursesBody').html('<tr><td colspan="10" class="text-center text-muted">Memuat data...</td></tr>');
            getPenawaranModal()?.show();

            $.ajax({
                url: routes.available,
                method: 'GET',
                data: {
                    id_krs: currentKrs.id,
                    id_semester: currentKrs.id_semester || currentKrs?.semester?.id
                },
                success: function(response) {
                    const rowsData = Array.isArray(response.data) ? response.data : [];
                    const metaMessage = response.meta?.message;

                    if (!response.success || !rowsData.length) {
                        $('#availableCoursesBody').html(
                            `<tr><td colspan="10" class="text-center text-muted">${escapeHtml(metaMessage || 'Tidak ada penawaran mata kuliah manual yang tersedia pada semester ini.')}</td></tr>`
                        );
                        return;
                    }

                    let rows = '';
                    rowsData.forEach((item, index) => {
                        const jadwalText = Array.isArray(item.jadwal) && item.jadwal.length ?
                            item.jadwal.map(j => `${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}`).join(
                                '<br>') :
                            '-';
                        const category = getOfferedCourseCategory(item, currentSemesterNumber);

                        const statusHtml = item.is_available ?
                            '<span class="badge bg-success">Tersedia</span>' :
                            `<span class="badge bg-secondary">${escapeHtml(item.availability_reason || 'Tidak tersedia')}</span>`;

                        const addButton = item.is_available ?
                            `<button class="btn btn-sm btn-primary" onclick="addCourse('${item.id}')"><i class="fas fa-plus me-1"></i>Tambah</button>` :
                            '<button class="btn btn-sm btn-outline-secondary" disabled>Tidak Bisa</button>';

                        rows += `
                                                <tr>
                                                    <td>${index + 1}</td>
                                                    <td>${escapeHtml(item.kode_mk)}</td>
                                                    <td>${escapeHtml(item.mata_kuliah)}</td>
                                                    <td>${escapeHtml(item.nama_kelas)}</td>
                                                    <td><span class="badge ${category.className}">${escapeHtml(category.text)}</span></td>
                                                    <td>${escapeHtml(item.semester_ke ?? '-')}</td>
                                                    <td>${escapeHtml(item.sks)}</td>
                                                    <td>${jadwalText}</td>
                                                    <td>${statusHtml}</td>
                                                    <td class="text-center">${addButton}</td>
                                                </tr>
                                            `;
                    });

                    $('#availableCoursesBody').html(rows);
                },
                error: function(xhr) {
                    $('#availableCoursesBody').html(
                        '<tr><td colspan="10" class="text-center text-danger">Gagal memuat penawaran mata kuliah manual.</td></tr>'
                    );
                    notify(xhr.responseJSON?.message || 'Gagal memuat penawaran mata kuliah manual.', 'danger');
                }
            });
        }

        /**
         * Load KRS history
         */
        function loadHistoryKrs() {
            $('#historyCoursesBody').html(
                '<tr><td colspan="6" class="text-center text-muted">Memuat riwayat KRS...</td></tr>');
            getHistoryModal()?.show();

            $.ajax({
                url: routes.history,
                method: 'GET',
                success: function(response) {
                    if (!response.success) {
                        $('#historyCoursesBody').html(
                            '<tr><td colspan="6" class="text-center text-danger">Gagal memuat riwayat KRS.</td></tr>'
                        );
                        notify(response.message || 'Gagal memuat riwayat KRS.', 'danger');
                        return;
                    }

                    renderHistoryRows(extractHistoryItems(response.data));
                },
                error: function(xhr) {
                    $('#historyCoursesBody').html(
                        '<tr><td colspan="6" class="text-center text-danger">Gagal memuat riwayat KRS.</td></tr>'
                    );
                    notify(xhr.responseJSON?.message || 'Gagal memuat riwayat KRS.', 'danger');
                }
            });
        }

        /**
         * Show KRS detail in modal
         * @param {string} krsId
         */
        function showHistoryDetail(krsId) {
            const url = routes.showTemplate.replace('__KRS__', krsId);
            $('#detailCoursesBody').html(
                '<tr><td colspan="6" class="text-center text-muted">Memuat detail KRS...</td></tr>');
            getDetailModal()?.show();

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat detail KRS.', 'danger');
                        $('#detailCoursesBody').html(
                            '<tr><td colspan="6" class="text-center text-danger">Gagal memuat detail KRS.</td></tr>'
                        );
                        return;
                    }

                    renderKrsDetailModal(response.data || {});
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat detail KRS.', 'danger');
                    $('#detailCoursesBody').html(
                        '<tr><td colspan="6" class="text-center text-danger">Gagal memuat detail KRS.</td></tr>'
                    );
                }
            });
        }

        /**
         * Print KRS
         * @param {string} krsId
         */
        function printKrs(krsId) {
            const url = routes.printTemplate.replace('__KRS__', krsId);
            window.open(url, '_blank', 'noopener');
        }

        /**
         * Add course to KRS
         * @param {string} kelasId
         */
        function addCourse(kelasId) {
            if (!currentKrs?.id) {
                notify('Draft KRS belum tersedia.', 'warning');
                return;
            }

            $.ajax({
                url: routes.add,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    id_krs: currentKrs.id,
                    id_kelas_kuliah: kelasId
                }),
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menambahkan mata kuliah.', 'danger');
                        return;
                    }

                    notify(response.message || 'Mata kuliah berhasil ditambahkan.', 'success');
                    currentKrs = response.data?.krs || currentKrs;
                    renderKrsState({
                        semester_aktif: currentKrs.semester,
                        krs: currentKrs,
                    });
                    loadCurrentKrs();
                    loadAvailableCourses();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menambahkan mata kuliah.', 'danger');
                }
            });
        }

        /**
         * Remove course from KRS
         * @param {string} krsId
         * @param {string} kelasId
         */
        function removeCourse(krsId, kelasId) {
            const url = routes.removeTemplate
                .replace('__KRS__', krsId)
                .replace('__KELAS__', kelasId);

            Swal.fire({
                title: 'Hapus mata kuliah ini?',
                text: 'Mata kuliah akan dikeluarkan dari draft KRS aktif.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menghapus mata kuliah dari KRS.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (!response.success) {
                            notify(response.message || 'Gagal menghapus mata kuliah.', 'danger');
                            return;
                        }

                        notify(response.message || 'Mata kuliah berhasil dihapus.', 'success');
                        currentKrs = response.data || currentKrs;
                        renderKrsState({
                            semester_aktif: currentKrs.semester,
                            krs: currentKrs,
                        });
                        loadCurrentKrs();

                        if ($('#modalPenawaran').hasClass('show')) {
                            loadAvailableCourses();
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        notify(xhr.responseJSON?.message || 'Gagal menghapus mata kuliah.', 'danger');
                    }
                });
            });
        }

        /**
         * Submit KRS for approval
         */
        function submitKrs() {
            if (!currentKrs?.id) {
                notify('Draft KRS belum tersedia.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Ajukan KRS sekarang?',
                text: 'KRS akan dikirim ke dosen wali untuk proses persetujuan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ajukan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: routes.submit,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        id_krs: currentKrs.id
                    }),
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang mengajukan KRS ke dosen wali.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (!response.success) {
                            notify(response.message || 'Gagal mengajukan KRS.', 'danger');
                            return;
                        }

                        notify(response.message || 'KRS berhasil diajukan.', 'success');
                        loadCurrentKrs();
                    },
                    error: function(xhr) {
                        Swal.close();
                        const message = xhr.responseJSON?.message || 'Gagal mengajukan KRS.';
                        notify(message, 'danger');
                    }
                });
            });
        }

        // ================================
        // EVENT HANDLERS & INITIALIZATION
        // ================================

        // Button event handlers
        $('#refreshBtn').on('click', loadCurrentKrs);
        $('#historyBtn').on('click', loadHistoryKrs);
        $('#printBtn').on('click', function() {
            if (currentKrs?.id && currentKrs?.status_approval === 'approved') {
                printKrs(currentKrs.id);
                return;
            }

            notify('KRS hanya dapat dicetak setelah disetujui dosen wali.', 'warning');
        });
        $('#createDraftBtn, #createDraftBtnEmpty').on('click', createDraft);
        $('#openModalBtn').on('click', loadAvailableCourses);
        $('#submitBtn').on('click', submitKrs);

        // Global function assignments
        window.showHistoryDetail = showHistoryDetail;
        window.printKrs = printKrs;

        // Document ready initialization
        $(document).ready(function() {
            loadCurrentKrs();
        });
    </script>
@endpush
