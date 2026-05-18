@extends('layouts.index')
@section('title', 'Approval KRS Dosen PA')

@push('styles-custom')
    <style>
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
            font-size: 1.2rem;
            font-weight: 600;
        }

        .detail-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            background: #fff;
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

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total Mahasiswa Wali</div>
                    <div class="summary-value" id="totalMahasiswaWali">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Pending Approval</div>
                    <div class="summary-value" id="pendingApproval">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Disetujui Semester Ini</div>
                    <div class="summary-value" id="approvedThisSemester">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Revisi Semester Ini</div>
                    <div class="summary-value" id="revisedThisSemester">0</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Daftar KRS Pending</h4>
                            <small class="text-muted">KRS mahasiswa yang sudah diajukan dan menunggu pemeriksaan.</small>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" id="refreshPendingBtn">
                            <i class="fas fa-rotate-right me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Mahasiswa</th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                        <th>Status</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Detail KRS Mahasiswa</h4>
                    </div>
                    <div class="card-body" id="detailContainer">
                        <div class="text-muted">Pilih salah satu KRS pending untuk melihat detail.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
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

        let selectedKrsId = null;
        let latestStatistics = {};

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function notify(message, type = 'info') {
            const alertClass = {
                success: 'alert-success',
                danger: 'alert-danger',
                warning: 'alert-warning',
                info: 'alert-info'
            } [type] || 'alert-info';

            const html = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            $('.page-inner').prepend(html);

            setTimeout(() => $('.page-inner .alert').first().alert('close'), 4000);
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

        function loadPendingList() {
            $('#pendingTableBody').html('<tr><td colspan="6" class="text-center text-muted">Memuat data...</td></tr>');

            $.get(routes.pending)
                .done(function(response) {
                    const data = response.data || [];

                    if (!data.length) {
                        const totalMahasiswaWali = latestStatistics.total_mahasiswa_wali ?? 0;

                        if (totalMahasiswaWali <= 0) {
                            $('#pendingTableBody').html(`<tr><td colspan="6" class="text-center text-muted">${escapeHtml(currentDosenName)} belum memiliki mahasiswa bimbingan, sehingga belum ada KRS yang perlu diperiksa.</td></tr>`);
                            $('#detailContainer').html('<div class="text-muted">Detail KRS akan muncul setelah Anda memiliki mahasiswa bimbingan yang mengajukan KRS.</div>');
                            notify(`${currentDosenName} belum memiliki mahasiswa bimbingan.`, 'info');
                        } else {
                            $('#pendingTableBody').html('<tr><td colspan="6" class="text-center text-muted">Belum ada KRS pending dari mahasiswa bimbingan Anda.</td></tr>');
                            $('#detailContainer').html('<div class="text-muted">Saat ini belum ada KRS pending untuk diperiksa. Detail akan muncul jika ada pengajuan baru.</div>');
                        }
                        return;
                    }

                    let rows = '';
                    data.forEach((item, index) => {
                        const mahasiswa = item.mahasiswa || {};
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                    <div class="fw-semibold">${escapeHtml(mahasiswa.nama_mahasiswa)}</div>
                                    <small class="text-muted">${escapeHtml(mahasiswa.nim || '-')}</small>
                                </td>
                                <td>${escapeHtml(formatSemester(item.semester))}</td>
                                <td>${escapeHtml(item.total_sks ?? 0)}</td>
                                <td><span class="badge bg-info text-dark">Pending</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" onclick="loadDetail('${item.id}')">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#pendingTableBody').html(rows);
                })
                .fail(function(xhr) {
                    $('#pendingTableBody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    notify(xhr.responseJSON?.message || 'Gagal memuat daftar KRS pending.', 'danger');
                });
        }

        function renderValidationSummary(summary) {
            if (!summary) {
                return '<div class="text-muted">Validasi belum tersedia.</div>';
            }

            return `
                <div class="detail-card mb-3">
                    <div class="fw-semibold mb-2">Ringkasan Validasi</div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Mata Kuliah</span>
                        <span>${escapeHtml(summary.total_matkul ?? 0)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total SKS</span>
                        <span>${escapeHtml(summary.total_sks ?? 0)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Maksimal SKS</span>
                        <span class="${summary.max_sks_ok ? 'text-success' : 'text-danger'}">${escapeHtml(summary.max_sks_allowed ?? 24)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sisa SKS</span>
                        <span>${escapeHtml(summary.remaining_sks ?? 0)}</span>
                    </div>
                    <div class="mt-2">
                        <small class="${summary.is_valid ? 'text-success' : 'text-danger'}">
                            ${summary.is_valid ? 'KRS valid untuk diproses.' : 'KRS belum valid untuk disetujui.'}
                        </small>
                    </div>
                    ${summary.is_sks_override ? `<div class="mt-2"><small class="text-info">Override SKS aktif: ${escapeHtml(summary.sks_override_reason || 'Override administratif')}</small></div>` : ''}
                </div>
            `;
        }

        function renderDetailsTable(details) {
            if (!Array.isArray(details) || !details.length) {
                return '<div class="text-muted">Belum ada detail mata kuliah.</div>';
            }

            let rows = '';
            details.forEach((detail, index) => {
                const kelas = detail.kelas_kuliah || detail.kelasKuliah;
                const kmk = kelas?.kurikulum_mata_kuliah || kelas?.kurikulumMataKuliah;
                const mk = kmk?.mata_kuliah || kmk?.mataKuliah;
                const jadwal = Array.isArray(kelas?.jadwal) && kelas.jadwal.length ?
                    kelas.jadwal.map(j => `${j.hari}, ${j.jam_mulai} - ${j.jam_selesai}`).join('<br>') :
                    '-';

                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(mk?.kode_mk)}</td>
                        <td>${escapeHtml(mk?.nama_mk)}</td>
                        <td>${escapeHtml(kelas?.nama_kelas)}</td>
                        <td>${escapeHtml(mk?.sks ?? 0)}</td>
                        <td>${jadwal}</td>
                    </tr>
                `;
            });

            return `
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th>Kelas</th>
                                <th>SKS</th>
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
            $('#detailContainer').html('<div class="text-muted">Memuat detail KRS...</div>');

            $.get(routes.showTemplate.replace('__ID__', id))
                .done(function(response) {
                    const data = response.data || {};
                    const mahasiswa = data.mahasiswa || {};
                    const semester = data.semester || {};

                    let html = `
                        <div class="detail-card mb-3">
                            <div class="fw-semibold mb-2">Informasi Mahasiswa</div>
                            <div class="mb-1"><strong>Nama:</strong> ${escapeHtml(mahasiswa.nama_mahasiswa)}</div>
                            <div class="mb-1"><strong>NIM:</strong> ${escapeHtml(mahasiswa.nim || '-')}</div>
                            <div class="mb-1"><strong>Semester:</strong> ${escapeHtml(formatSemester(semester))}</div>
                            <div class="mb-1"><strong>Total SKS:</strong> ${escapeHtml(data.total_sks ?? 0)}</div>
                            <div class="mb-1"><strong>Status:</strong> ${escapeHtml(data.status_approval || '-')}</div>
                        </div>
                    `;

                    html += renderValidationSummary(data.validation_summary);

                    if (data.catatan) {
                        html += `<div class="alert alert-info"><strong>Catatan saat ini:</strong> ${escapeHtml(data.catatan)}</div>`;
                    }

                    if (data.sks_override?.is_active) {
                        const overrideBy = data.sks_override?.by?.name ? ` oleh ${escapeHtml(data.sks_override.by.name)}` : '';
                        html += `<div class="alert alert-warning"><strong>Override SKS aktif${overrideBy}.</strong><br>${escapeHtml(data.sks_override?.reason || 'Override administratif')}</div>`;
                    }

                    html += renderDetailsTable(data.details || []);

                    html += `
                        <div class="mt-3">
                            <label for="catatanDosen" class="form-label">Catatan Dosen</label>
                            <textarea class="form-control" id="catatanDosen" rows="3" placeholder="Tulis catatan untuk mahasiswa..."></textarea>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-success" ${data.can_approve ? '' : 'disabled'} onclick="submitDecision('approve')">
                                <i class="fas fa-check me-1"></i> Setujui
                            </button>
                            <button class="btn btn-warning text-dark" ${data.can_revision ? '' : 'disabled'} onclick="submitDecision('revision')">
                                <i class="fas fa-rotate-left me-1"></i> Revisi
                            </button>
                            <button class="btn btn-danger" ${data.can_revision ? '' : 'disabled'} onclick="submitDecision('reject')">
                                <i class="fas fa-xmark me-1"></i> Tolak
                            </button>
                        </div>
                    `;

                    $('#detailContainer').html(html);
                })
                .fail(function(xhr) {
                    $('#detailContainer').html('<div class="text-danger">Gagal memuat detail KRS.</div>');
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
                notify('Catatan wajib diisi untuk revisi atau reject.', 'warning');
                return;
            }

            if (!confirm(`Apakah Anda yakin ingin ${labels[action]} KRS ini?`)) {
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
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memproses KRS.', 'danger');
                        return;
                    }

                    notify(response.message || 'KRS berhasil diproses.', 'success');
                    selectedKrsId = null;
                    $('#detailContainer').html('<div class="text-muted">Pilih salah satu KRS pending untuk melihat detail.</div>');
                    loadStatistics();
                    loadPendingList();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memproses KRS.', 'danger');
                }
            });
        }

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
