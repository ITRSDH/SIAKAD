@extends('layouts.index')
@section('title', 'KRS Mahasiswa')

@push('styles-custom')
    <style>
        :root {
            --krs-primary: #1572e8;
            --krs-primary-rgb: 21, 114, 232;
            --krs-primary-soft: #edf5ff;
            --krs-info: #0284c7;
            --krs-info-rgb: 2, 132, 199;
            --krs-info-soft: #f0f9ff;
            --krs-border: #e2e8f0;
            --krs-card-shadow: 0 4px 16px rgba(21, 114, 232, 0.05);
        }

        .modal-xxl {
            max-width: 96% !important;
        }

        /* Modern Dashboard Card */
        .krs-card {
            border: 1px solid var(--krs-border);
            border-radius: 1rem;
            box-shadow: var(--krs-card-shadow);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .krs-card-header {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-bottom: 1px solid var(--krs-border);
            padding: 1.1rem 1.35rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        /* Summary Stat Cards */
        .summary-stat-card {
            background: #ffffff;
            border: 1px solid var(--krs-border);
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
            border-color: rgba(var(--krs-primary-rgb), 0.35);
        }

        .summary-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--krs-primary);
        }

        .summary-stat-card.stat-info::before {
            background: var(--krs-info);
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
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
            word-break: break-word;
        }

        .stat-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-left: 0.75rem;
        }

        .stat-icon-primary {
            background: var(--krs-primary-soft);
            color: var(--krs-primary);
        }

        .stat-icon-info {
            background: var(--krs-info-soft);
            color: var(--krs-info);
        }

        /* Pill / Soft Badges */
        .badge-soft-primary {
            background-color: #e0e7ff !important;
            color: #1d4ed8 !important;
            border: 1px solid #c7d2fe !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.4em 0.85em;
        }

        .badge-soft-info {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            border: 1px solid #bae6fd !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.4em 0.85em;
        }

        .badge-soft-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.4em 0.85em;
        }

        .badge-status {
            font-size: 0.82rem;
        }

        /* Modern Tables */
        .table-krs {
            margin-bottom: 0;
        }

        .table-krs thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 2px solid var(--krs-border);
            padding: 0.85rem 0.75rem;
            white-space: nowrap;
        }

        .table-krs tbody td {
            padding: 0.85rem 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .table-krs tbody tr:hover {
            background-color: rgba(var(--krs-primary-rgb), 0.025);
        }

        /* Package Insights Box */
        .package-box-modern {
            border: 1px solid var(--krs-border);
            border-radius: 0.85rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
            padding: 1.15rem;
            font-size: 0.88rem;
            height: 100%;
        }

        /* Empty State */
        .empty-state {
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 3rem 1.5rem;
            text-align: center;
            background: #f8fafc;
        }

        /* Responsive Sidebar */
        @media (min-width: 992px) {
            .sticky-sidebar {
                position: sticky;
                top: 20px;
                z-index: 10;
            }
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

        <!-- Summary Stat Cards -->
        <div class="row g-3 mb-4" id="summarySection">
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Semester Aktif</div>
                        <div class="stat-value" id="semesterAktifLabel">-</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-primary d-none d-sm-flex">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card stat-info">
                    <div class="stat-content">
                        <div class="stat-label">Status KRS</div>
                        <div class="stat-value">
                            <span id="statusBadge" class="badge badge-soft-info badge-status">-</span>
                        </div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-info d-none d-sm-flex">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Total SKS Diambil</div>
                        <div class="stat-value text-primary" id="totalSksLabel">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-primary d-none d-sm-flex">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="summary-stat-card stat-info">
                    <div class="stat-content">
                        <div class="stat-label">Batas Maks. SKS</div>
                        <div class="stat-value text-info" id="maxSksLabel">0</div>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-info d-none d-sm-flex">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Profile Card (Original Structure) -->
        <div class="row mb-4">
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
                            <div id="rplInfoAlert" class="alert alert-info d-none mt-2 mb-0 py-2 d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-lg me-3 text-info"></i>
                                <div>
                                    <div class="fw-bold">Mahasiswa Jalur RPL / Alih Jenjang</div>
                                    <small id="rplInfoText" class="text-muted"></small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main KRS Content & Validation Summary Row -->
        <div class="row g-3 mb-4">
            <!-- KRS Semester Aktif (Table) Column -->
            <div class="col-lg-8">
                <div class="krs-card mb-3">
                    <div class="krs-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">KRS Semester Aktif</h5>
                            <small class="text-muted" id="krsMetaInfo">Memuat data KRS...</small>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="refreshBtn">
                                <i class="fas fa-sync me-1"></i> Refresh
                            </button>
                            <button class="btn btn-sm btn-outline-info rounded-pill px-3" id="historyBtn">
                                <i class="fas fa-history me-1"></i> Riwayat KRS
                            </button>
                            <button class="btn btn-sm btn-info text-white rounded-pill px-3 d-none" id="printBtn">
                                <i class="fas fa-print me-1"></i> Cetak KRS
                            </button>
                            <button class="btn btn-sm btn-primary rounded-pill px-3 d-none" id="createDraftBtn">
                                <i class="fas fa-plus-circle me-1"></i> Buat Draft KRS
                            </button>
                            <button class="btn btn-sm btn-outline-info rounded-pill px-3 d-none" id="regenerateBtn" title="Muat ulang paket mata kuliah sesuai kurikulum aktif">
                                <i class="fas fa-sync-alt me-1"></i> Muat Ulang Paket
                            </button>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 d-none" id="openModalBtn">
                                <i class="fas fa-plus me-1"></i> Tambah MK Manual
                            </button>
                            <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm d-none" id="submitBtn">
                                <i class="fas fa-paper-plane me-1"></i> Ajukan KRS
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div id="emptyState" class="empty-state d-none">
                            <div class="mb-3">
                                <div class="stat-icon-wrapper stat-icon-primary mx-auto" style="width: 54px; height: 54px; font-size: 1.5rem; margin-left: auto;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark" id="emptyStateTitle">Menyiapkan KRS semester aktif</h5>
                            <p class="text-muted mb-0 mx-auto" style="max-width: 500px;" id="emptyStateDescription">Sistem sedang memeriksa penawaran mata kuliah untuk semester aktif.</p>
                        </div>

                        <div id="krsContent" class="d-none">
                            <div class="alert alert-info border-0 shadow-sm d-none mb-3 p-3 rounded-3" id="catatanBox"></div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="package-box-modern" id="packageSummaryBox">
                                        <div class="text-muted">Ringkasan paket semester belum tersedia.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="package-box-modern" id="packageIssueBox">
                                        <div class="text-muted">Kendala generate paket akan muncul di sini bila ada.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive rounded-3 border">
                                <table class="table table-krs align-middle">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th>Kode MK</th>
                                            <th>Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th class="text-center">SKS</th>
                                            <th class="text-center">Kategori</th>
                                            <th>Jadwal</th>
                                            <th width="8%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedCoursesBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Memuat data...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Summary Column -->
            <div class="col-lg-4">
                <div class="krs-card sticky-sidebar">
                    <div class="krs-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-info" style="width: 32px; height: 32px; border-radius: 8px; font-size: 0.9rem; margin-left: 0;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Ringkasan Validasi</h5>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4" id="validationBox">
                        <div class="text-muted text-center py-3">Memuat validasi...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Penawaran Mata Kuliah Manual -->
        <div class="modal fade" id="modalPenawaran" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xxl">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-primary" style="width: 36px; height: 36px; border-radius: 8px; font-size: 1rem; margin-left: 0;">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark">Penawaran Mata Kuliah Manual</h5>
                                <small class="text-muted">Pilih mata kuliah yang ingin ditambahkan ke KRS Anda</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <div class="alert alert-info border-0 shadow-sm mb-3 rounded-3 d-none" id="penawaranInfoBox"></div>

                        <!-- Modern Filter Bar -->
                        <div class="card border mb-3 rounded-3 shadow-sm" style="background: #f8fafc; border-color: #e2e8f0;">
                            <div class="card-body py-3 px-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label small fw-bold mb-1 text-secondary" for="filterPenawaranKategori">
                                            <i class="fas fa-filter text-primary me-1"></i> Kategori:
                                        </label>
                                        <select class="form-select form-select-sm rounded-pill" id="filterPenawaranKategori">
                                            <option value="all">Semua Kategori</option>
                                            <option value="Paket" selected>Paket (Default)</option>
                                            <option value="Ulang">Ulang</option>
                                            <option value="Tambahan">Tambahan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label small fw-bold mb-1 text-secondary" for="filterPenawaranSemester">
                                            <i class="fas fa-layer-group text-info me-1"></i> Semester Target:
                                        </label>
                                        <select class="form-select form-select-sm rounded-pill" id="filterPenawaranSemester">
                                            <option value="all">Semua Semester</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold mb-1 text-secondary" for="filterPenawaranSearch">
                                            <i class="fas fa-search text-primary me-1"></i> Cari Mata Kuliah / Kelas:
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control border-start-0 rounded-end-pill" id="filterPenawaranSearch" placeholder="Ketik kode / nama MK / kelas...">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill w-100 mt-md-4" id="resetPenawaranFilterBtn">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive rounded-3 border">
                            <table class="table table-krs align-middle">
                                <thead>
                                    <tr>
                                        <th width="4%" class="text-center">No</th>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th>Kelas</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Sem.</th>
                                        <th class="text-center">SKS</th>
                                        <th>Jadwal</th>
                                        <th class="text-center">Status</th>
                                        <th width="8%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="availableCoursesBody">
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Riwayat KRS -->
        <div class="modal fade" id="modalRiwayatKrs" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-info" style="width: 36px; height: 36px; border-radius: 8px; font-size: 1rem; margin-left: 0;">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark">Riwayat KRS Mahasiswa</h5>
                                <small class="text-muted">Daftar KRS yang pernah diambil pada semester sebelumnya</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-krs align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Semester</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Total SKS</th>
                                        <th>Catatan</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="historyCoursesBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Memuat riwayat KRS...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail KRS -->
        <div class="modal fade" id="modalDetailKrs" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon-wrapper stat-icon-primary" style="width: 36px; height: 36px; border-radius: 8px; font-size: 1rem; margin-left: 0;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark">Detail KRS</h5>
                                <small class="text-muted">Rincian mata kuliah pada KRS terpilih</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-card">
                                    <div class="stat-content">
                                        <div class="stat-label">Semester</div>
                                        <div class="stat-value" id="detailSemesterLabel">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-card stat-info">
                                    <div class="stat-content">
                                        <div class="stat-label">Status</div>
                                        <div class="stat-value">
                                            <span id="detailStatusBadge" class="badge badge-soft-info badge-status">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-card">
                                    <div class="stat-content">
                                        <div class="stat-label">Total SKS</div>
                                        <div class="stat-value text-primary" id="detailTotalSksLabel">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-card stat-info">
                                    <div class="stat-content">
                                        <div class="stat-label">Jumlah MK</div>
                                        <div class="stat-value text-info" id="detailTotalMkLabel">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm d-none mb-3 rounded-3" id="detailCatatanBox"></div>

                        <div class="table-responsive rounded-3 border">
                            <table class="table table-krs align-middle">
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
                                <tbody id="detailCoursesBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Pilih riwayat KRS untuk melihat detail.</td>
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
            regenerate: "{{ route('krs.current.regenerate') }}",
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
        let targetKurikulumSemester = null;
        let packageSummary = null;
        let unresolvedPackageItems = [];
        let rawOfferedCourses = [];
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
                    className: 'badge-soft-info'
                },
                pending: {
                    text: 'Menunggu Persetujuan',
                    className: 'badge-soft-primary'
                },
                approved: {
                    text: 'Disetujui',
                    className: 'badge-soft-primary'
                },
                rejected: {
                    text: 'Ditolak',
                    className: 'badge-soft-secondary'
                }
            };

            return config[status] || {
                text: status || '-',
                className: 'badge-soft-secondary'
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

        function formatOperationalCurriculumLabel(context) {
            const operational = context?.struktur_operasional || null;
            const induk = context?.kurikulum_induk || null;

            if (operational?.nama_struktur_mk) {
                const indukSuffix = induk?.nama_kurikulum ? ` | Induk: ${induk.nama_kurikulum}` : '';
                return `${operational.nama_struktur_mk}${indukSuffix}`;
            }

            if (induk?.nama_kurikulum) {
                return `${induk.nama_kurikulum} (induk)`;
            }

            return 'Belum terpetakan';
        }

        function renderPenawaranInfo(meta = {}) {
            const context = currentKrs?.kurikulum_context || {};
            const semesterTempuh = meta?.semester_tempuh ?? currentSemesterNumber ?? '-';
            const maxSks = meta?.max_sks_allowed ?? currentKrs?.validation_summary?.max_sks_allowed ?? 0;
            const currentSks = meta?.current_sks ?? currentKrs?.total_sks ?? 0;
            const infoHtml = `
                <div class="fw-bold text-dark mb-2"><i class="fas fa-info-circle text-primary me-1"></i> Konteks Penawaran Manual</div>
                <div class="small mb-1 text-secondary"><strong>Semester tempuh:</strong> ${escapeHtml(semesterTempuh)}</div>
                <div class="small mb-1 text-secondary"><strong>Struktur kurikulum aktif:</strong> ${escapeHtml(formatOperationalCurriculumLabel(context))}</div>
                <div class="small mb-1 text-secondary"><strong>SKS saat ini:</strong> <span class="text-primary fw-bold">${escapeHtml(currentSks)}</span> / <span class="text-info fw-bold">${escapeHtml(maxSks)}</span></div>
                <div class="small text-muted mt-2 pt-2 border-top">Jika paket otomatis belum lengkap, mata kuliah tetap bisa ditambahkan manual dari penawaran kelas yang sesuai semester tempuh.</div>
            `;

            $('#penawaranInfoBox').removeClass('d-none').html(infoHtml);
        }

        function getCourseCategory(detail, semesterNumber) {
            if (detail?.kategori_pengambilan) {
                const map = {
                    paket: {
                        text: 'Paket',
                        className: 'badge-soft-primary'
                    },
                    ulang: {
                        text: 'Ulang',
                        className: 'badge-soft-info'
                    },
                    tambahan: {
                        text: 'Tambahan',
                        className: 'badge-soft-secondary'
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
                    className: 'badge-soft-primary'
                };
            }

            if (semesterNumber && semesterKe > 0 && semesterKe < Number(semesterNumber)) {
                return {
                    text: 'Ulang',
                    className: 'badge-soft-info'
                };
            }

            return {
                text: 'Tambahan',
                className: 'badge-soft-secondary'
            };
        }

        function getOfferedCourseCategory(item, semesterNumber) {
            const semesterKe = Number(item?.semester_ke || 0);
            const currentSemester = Number(semesterNumber || 0);

            if (currentSemester > 0 && semesterKe === currentSemester) {
                return {
                    text: 'Paket',
                    className: 'badge-soft-primary'
                };
            }

            if (currentSemester > 0 && semesterKe > 0 && semesterKe < currentSemester) {
                return {
                    text: 'Ulang',
                    className: 'badge-soft-info'
                };
            }

            return {
                text: 'Tambahan',
                className: 'badge-soft-secondary'
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
            const unresolvedCount = Number(summary?.unresolved_count ?? unresolvedPackageItems.length ?? 0);
            const hasAutomaticIssues = unresolvedCount > 0;

            $('#packageSummaryBox').html(`
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-cubes text-primary"></i>
                    <span class="fw-bold text-dark">Ringkasan Paket Semester</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Semester tempuh</span>
                    <span class="fw-semibold text-dark">${escapeHtml(summary?.semester_ke ?? semesterNumber ?? '-')}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Mata kuliah paket</span>
                    <span class="fw-semibold text-primary">${escapeHtml(summary?.generated_count ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>SKS paket</span>
                    <span class="fw-semibold text-primary">${escapeHtml(summary?.generated_sks ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Mata kuliah ulang di KRS</span>
                    <span class="fw-semibold text-info">${escapeHtml(summary?.repeat_count ?? 0)}</span>
                </div>
                <div class="d-flex justify-content-between small text-secondary">
                    <span>SKS ulang di KRS</span>
                    <span class="fw-semibold text-info">${escapeHtml(summary?.repeat_sks ?? 0)}</span>
                </div>
                <hr class="my-2 border-light">
                <div class="small ${hasAutomaticIssues ? 'text-info fw-semibold' : 'text-primary fw-semibold'}">
                    <i class="fas ${hasAutomaticIssues ? 'fa-info-circle' : 'fa-check-circle'} me-1"></i>
                    ${hasAutomaticIssues
                        ? 'Sebagian paket belum tergenerate otomatis. Anda dapat menambahkan mata kuliah manual dari penawaran kelas.'
                        : 'Paket semester berhasil digenerate otomatis ke draft KRS.'}
                </div>
            `);

            if (!Array.isArray(unresolvedPackageItems) || !unresolvedPackageItems.length) {
                $('#packageIssueBox').html(`
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-check-double text-info"></i>
                        <span class="fw-bold text-dark">Status Paket</span>
                    </div>
                    <div class="text-info small"><i class="fas fa-check-circle me-1"></i>Semua paket kurikulum aktif yang tersedia sudah masuk ke draft KRS Anda.</div>
                `);
                return;
            }

            const items = unresolvedPackageItems.map(item => {
                const title = item?.kode_mk ? `${item.kode_mk} - ${item.nama_mk}` : 'Item paket';
                return `<li class="small mb-1"><strong>${escapeHtml(title)}</strong>: <span class="text-muted">${escapeHtml(item?.reason || 'Belum dapat digenerate')}</span></li>`;
            }).join('');

            $('#packageIssueBox').html(`
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-info"></i>
                    <span class="fw-bold text-dark">Catatan Paket</span>
                </div>
                <ul class="mb-0 ps-3 text-secondary">${items}</ul>
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
                $('#validationBox').html('<div class="text-muted text-center py-3">Validasi belum tersedia.</div>');
                return;
            }

            const currentSks = Number(summary.total_sks ?? 0);
            const maxSks = Number(summary.max_sks_allowed ?? 1);
            const percentage = Math.min(100, Math.round((currentSks / (maxSks || 1)) * 100));

            const checks = [{
                    label: 'Jumlah mata kuliah dipilih',
                    value: `${summary.total_matkul ?? 0} MK`,
                    ok: summary.has_items
                },
                {
                    label: 'Beban SKS saat ini',
                    value: `${currentSks} / ${maxSks} SKS`,
                    ok: summary.max_sks_ok
                },
                {
                    label: 'Pemeriksaan bentrok jadwal',
                    value: summary.schedule_conflict ? 'Ada bentrok' : 'Bebas bentrok',
                    ok: !summary.schedule_conflict
                },
                {
                    label: 'Sisa kuota SKS',
                    value: `${summary.remaining_sks ?? 0} SKS`,
                    ok: true
                }
            ];

            let html = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between small fw-bold text-secondary mb-1">
                        <span>Penggunaan SKS</span>
                        <span class="text-primary">${percentage}%</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 20px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: ${percentage}%; border-radius: 20px;" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <ul class="list-group list-group-flush border-top border-bottom my-3">
            `;

            checks.forEach(item => {
                const badgeClass = item.ok ? 'badge-soft-primary' : 'badge-soft-secondary';
                const icon = item.ok ? '<i class="fas fa-check me-1"></i>' : '<i class="fas fa-clock me-1"></i>';
                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-light">
                        <div class="small text-secondary">${escapeHtml(item.label)}</div>
                        <div class="text-end">
                            <div class="small fw-bold text-dark">${escapeHtml(item.value)}</div>
                            <span class="badge ${badgeClass} badge-status mt-1" style="font-size: 0.72rem; padding: 0.25em 0.65em;">${icon}${item.ok ? 'OK' : 'Periksa'}</span>
                        </div>
                    </li>
                `;
            });
            html += '</ul>';

            if (summary.can_submit) {
                html += `
                    <div class="alert alert-info border-0 shadow-sm mt-3 mb-0 p-3 rounded-3 d-flex align-items-center">
                        <i class="fas fa-check-circle text-primary fa-lg me-2"></i>
                        <div>
                            <div class="fw-bold text-primary">KRS Siap Diajukan</div>
                            <small class="text-secondary">Semua kriteria validasi telah terpenuhi.</small>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="alert alert-info border-0 shadow-sm mt-3 mb-0 p-3 rounded-3 d-flex align-items-center">
                        <i class="fas fa-info-circle text-info fa-lg me-2"></i>
                        <div>
                            <div class="fw-bold text-info">Belum Dapat Diajukan</div>
                            <small class="text-secondary">Pastikan mata kuliah dipilih dan tidak ada bentrok.</small>
                        </div>
                    </div>
                `;
            }

            if (summary.is_sks_override) {
                html += `
                    <div class="alert alert-info border-0 shadow-sm mt-2 mb-0 p-2 small rounded-3">
                        <i class="fas fa-shield-alt text-primary me-1"></i> <strong>Batas SKS dioverride:</strong> ${escapeHtml(summary.sks_override_reason || 'Override administratif')}
                    </div>
                `;
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
                        <td colspan="8" class="text-center text-muted py-4">Belum ada mata kuliah yang dipilih.</td>
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
                    jadwalList.map(j => `<span class="badge bg-light text-dark border me-1"><i class="far fa-clock text-info me-1"></i>${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}</span>`).join(' ') :
                    '<span class="text-muted">-</span>';

                const removeButton = krs.can_edit ?
                    `<button class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 28px; height: 28px; line-height: 1;" title="Hapus mata kuliah" onclick="removeCourse('${krs.id}', '${kelas?.id}')"><i class="fas fa-times fa-xs"></i></button>` :
                    '<span class="text-muted">-</span>';

                rows += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="fw-bold font-monospace text-primary">${escapeHtml(mk?.kode_mk)}</td>
                        <td class="fw-semibold text-dark">${escapeHtml(mk?.nama_mk)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHtml(kelas?.nama_kelas)}</span></td>
                        <td class="text-center fw-bold text-dark">${escapeHtml(mk?.sks ?? 0)}</td>
                        <td class="text-center"><span class="badge ${category.className} badge-status">${escapeHtml(category.text)}</span></td>
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

                const printButton = item.status_approval === 'approved' ?
                    `<button class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="printKrs('${item.id}')">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>` :
                    '';

                rows += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="fw-semibold text-dark">${escapeHtml(formatSemester(semesterLabel))}</td>
                        <td class="text-center"><span class="badge ${status.className} badge-status">${escapeHtml(status.text)}</span></td>
                        <td class="text-center fw-bold text-primary">${escapeHtml(item.total_sks ?? 0)}</td>
                        <td class="small text-secondary">${escapeHtml(item.catatan || '-')}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="showHistoryDetail('${item.id}')">
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
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="fw-bold font-monospace text-primary">${escapeHtml(mk?.kode_mk)}</td>
                        <td class="fw-semibold text-dark">${escapeHtml(mk?.nama_mk)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHtml(kelas?.nama_kelas)}</span></td>
                        <td class="text-center fw-bold text-dark">${escapeHtml(mk?.sks ?? 0)}</td>
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

            if (payload?.is_rpl) {
                $('#rplInfoAlert').removeClass('d-none');
                $('#rplInfoText').text(`Mahasiswa Jalur RPL diakui memiliki ${payload.sks_diakui || 0} SKS Konversi (${payload.nilai_transfer_count || 0} mata kuliah terkonversi). Terdaftar pada Semester ${payload.semester_saat_ini || 1} dengan paket perkuliahan Semester ${payload.paket_semester || payload.semester_saat_ini || 1}.`);
            } else {
                $('#rplInfoAlert').addClass('d-none');
            }

            if (!currentKrs) {
                $('#statusBadge').attr('class', 'badge badge-soft-secondary badge-status').text('Belum Ada KRS');
                $('#totalSksLabel').text('0');
                $('#maxSksLabel').text('0');
                $('#krsMetaInfo').removeClass('d-none');
                $('#krsMetaInfo').text(payload?.eligibility_message || 'KRS semester aktif belum tersedia.');
                $('#emptyState').removeClass('d-none');
                $('#krsContent').addClass('d-none');
                $('#createDraftBtn, #createDraftBtnEmpty, #openModalBtn, #regenerateBtn, #submitBtn, #printBtn').addClass('d-none');
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
                    $('#emptyStateDescription').text(payload?.eligibility_message || 'Draft KRS belum tersedia untuk semester aktif. Jika semester sudah sesuai, sistem akan mencoba menyiapkannya otomatis.');
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
            $('#regenerateBtn').toggleClass('d-none', !currentKrs.can_edit);
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
                        notify(
                            unresolved > 0
                                ? `Draft KRS berhasil dibuat. Paket otomatis masuk ${generated} mata kuliah, dan ${unresolved} item belum tergenerate. Anda masih bisa menambahkan mata kuliah manual.`
                                : `Draft KRS berhasil dibuat. Paket otomatis masuk ${generated} mata kuliah.`,
                            unresolved > 0 ? 'warning' : 'success'
                        );
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

            renderPenawaranInfo();
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
                    rawOfferedCourses = Array.isArray(response.data) ? response.data : [];
                    const metaMessage = response.meta?.message;
                    if (response.meta?.paket_semester) {
                        targetKurikulumSemester = response.meta.paket_semester;
                    }
                    renderPenawaranInfo(response.meta || {});

                    // Populate semester target filter dropdown
                    populatePenawaranSemesterOptions(rawOfferedCourses);

                    // Reset filter ke default: Kategori "Paket"
                    $('#filterPenawaranKategori').val('Paket');
                    $('#filterPenawaranSearch').val('');

                    if (!response.success || !rawOfferedCourses.length) {
                        $('#availableCoursesBody').html(
                            `<tr><td colspan="10" class="text-center text-muted">${escapeHtml(metaMessage || 'Tidak ada penawaran mata kuliah manual yang tersedia pada semester ini.')}</td></tr>`
                        );
                        return;
                    }

                    renderFilteredAvailableCourses();
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
         * Populate semester options in filter dropdown based on available courses
         */
        function populatePenawaranSemesterOptions(courses) {
            const semesters = [...new Set(courses.map(c => Number(c.semester_ke)).filter(s => s > 0))].sort((a, b) => a - b);
            let options = '<option value="all">Semua Semester</option>';
            semesters.forEach(s => {
                const isCurrent = (targetKurikulumSemester && s === Number(targetKurikulumSemester)) || (currentSemesterNumber && s === Number(currentSemesterNumber));
                options += `<option value="${s}">Semester ${s}${isCurrent ? ' (Target Mahasiswa)' : ''}</option>`;
            });
            $('#filterPenawaranSemester').html(options);

            // Default: jika ada target semester, pilih target semester atau default "all"
            $('#filterPenawaranSemester').val('all');
        }

        /**
         * Render filtered available courses based on current filter values
         */
        function renderFilteredAvailableCourses() {
            if (!rawOfferedCourses.length) {
                $('#availableCoursesBody').html(
                    '<tr><td colspan="10" class="text-center text-muted">Tidak ada data penawaran kelas.</td></tr>'
                );
                return;
            }

            const targetSemesterNum = targetKurikulumSemester || currentSemesterNumber;
            const selectedCategory = $('#filterPenawaranKategori').val() || 'all';
            const selectedSemester = $('#filterPenawaranSemester').val() || 'all';
            const searchQuery = ($('#filterPenawaranSearch').val() || '').trim().toLowerCase();

            const filtered = rawOfferedCourses.filter(item => {
                const category = getOfferedCourseCategory(item, targetSemesterNum);

                // Filter Kategori
                if (selectedCategory !== 'all' && category.text !== selectedCategory) {
                    return false;
                }

                // Filter Semester
                if (selectedSemester !== 'all' && Number(item.semester_ke) !== Number(selectedSemester)) {
                    return false;
                }

                // Filter Pencarian Teks (Kode MK, Nama MK, Nama Kelas)
                if (searchQuery) {
                    const matchKode = (item.kode_mk || '').toLowerCase().includes(searchQuery);
                    const matchNama = (item.mata_kuliah || '').toLowerCase().includes(searchQuery);
                    const matchKelas = (item.nama_kelas || '').toLowerCase().includes(searchQuery);
                    if (!matchKode && !matchNama && !matchKelas) {
                        return false;
                    }
                }

                return true;
            });

            if (!filtered.length) {
                let emptyMsg = 'Tidak ada mata kuliah yang cocok dengan filter yang dipilih.';
                if (selectedCategory === 'Paket') {
                    emptyMsg = 'Tidak ada mata kuliah kategori <strong>Paket</strong> yang tersedia (atau sudah masuk semua ke KRS). Anda dapat mengubah filter kategori ke <em>"Semua Kategori"</em> untuk melihat mata kuliah lainnya.';
                }
                $('#availableCoursesBody').html(
                    `<tr><td colspan="10" class="text-center text-muted py-4">${emptyMsg}</td></tr>`
                );
                return;
            }

            let rows = '';
            filtered.forEach((item, index) => {
                const jadwalText = Array.isArray(item.jadwal) && item.jadwal.length ?
                    item.jadwal.map(j => `${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}`).join('<br>') :
                    '-';
                const category = getOfferedCourseCategory(item, targetSemesterNum);

                let statusHtml = '';
                if (item.is_transferred) {
                    statusHtml = `<span class="badge badge-soft-info badge-status"><i class="fas fa-check-circle me-1"></i>Konversi (${escapeHtml(item.nilai_transfer || 'A')})</span>`;
                } else if (item.is_available) {
                    statusHtml = '<span class="badge badge-soft-primary badge-status"><i class="fas fa-check me-1"></i>Tersedia</span>';
                } else {
                    statusHtml = `<span class="badge badge-soft-secondary badge-status">${escapeHtml(item.availability_reason || 'Tidak tersedia')}</span>`;
                }

                const addButton = item.is_available ?
                    `<button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addCourse('${item.id}')"><i class="fas fa-plus me-1"></i>Pilih</button>` :
                    '<button class="btn btn-sm btn-outline-secondary rounded-pill px-2" disabled>Penuh</button>';

                rows += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="fw-bold font-monospace text-primary">${escapeHtml(item.kode_mk)}</td>
                        <td class="fw-semibold text-dark">${escapeHtml(item.mata_kuliah)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHtml(item.nama_kelas)}</span></td>
                        <td class="text-center"><span class="badge ${category.className} badge-status">${escapeHtml(category.text)}</span></td>
                        <td class="text-center fw-semibold">${escapeHtml(item.semester_ke ?? '-')}</td>
                        <td class="text-center fw-bold text-dark">${escapeHtml(item.sks)}</td>
                        <td>${jadwalText}</td>
                        <td class="text-center">${statusHtml}</td>
                        <td class="text-center">${addButton}</td>
                    </tr>
                `;
            });

            $('#availableCoursesBody').html(rows);
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

        /**
         * Regenerate package courses for current active semester
         */
        function regeneratePackage() {
            if (!currentKrs?.id) {
                notify('Draft KRS belum tersedia.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Muat Ulang Paket Semester?',
                text: 'Mata kuliah draft KRS Anda akan disinkronkan kembali sesuai struktur kurikulum semester aktif. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, muat ulang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: routes.regenerate,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang memuat ulang paket semester sesuai kurikulum.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (!response.success) {
                            notify(response.message || 'Gagal memuat ulang paket KRS.', 'danger');
                            return;
                        }

                        notify(response.message || 'Paket KRS berhasil dimuat ulang.', 'success');
                        loadCurrentKrs();
                    },
                    error: function(xhr) {
                        Swal.close();
                        const message = xhr.responseJSON?.message || 'Gagal memuat ulang paket KRS.';
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
        $('#regenerateBtn').on('click', regeneratePackage);
        $('#submitBtn').on('click', submitKrs);

        // Filter event handlers for Modal Penawaran Mata Kuliah Manual
        $('#filterPenawaranKategori, #filterPenawaranSemester').on('change', renderFilteredAvailableCourses);
        $('#filterPenawaranSearch').on('input', renderFilteredAvailableCourses);
        $('#resetPenawaranFilterBtn').on('click', function() {
            $('#filterPenawaranKategori').val('Paket');
            $('#filterPenawaranSemester').val('all');
            $('#filterPenawaranSearch').val('');
            renderFilteredAvailableCourses();
        });

        // Global function assignments
        window.showHistoryDetail = showHistoryDetail;
        window.printKrs = printKrs;

        // Document ready initialization
        $(document).ready(function() {
            loadCurrentKrs();
        });
    </script>
@endpush
