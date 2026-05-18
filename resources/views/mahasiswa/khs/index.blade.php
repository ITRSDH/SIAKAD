@extends('layouts.index')
@section('title', 'KHS')

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
            font-size: 1.15rem;
            font-weight: 600;
        }

        .empty-state {
            border: 1px dashed #ced4da;
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
        }

        .status-pill {
            font-size: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">KHS</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('student.khs.index') }}">KHS</a></li>
            </ul>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total KHS</div>
                    <div class="summary-value" id="totalKhsLabel">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Semester</div>
                    <div class="summary-value" id="semesterLabel">-</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">IPS</div>
                    <div class="summary-value" id="ipsLabel">0.00</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">IPK</div>
                    <div class="summary-value" id="ipkLabel">0.00</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Daftar KHS</h4>
                            <small class="text-muted">Data diambil dari endpoint `/khs`.</small>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                            <i class="fas fa-rotate-right me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="khsEmptyState" class="empty-state d-none">
                            <h5 class="mb-2">Belum ada data KHS</h5>
                            <p class="text-muted mb-0">KHS akan tampil di sini setelah backend mengembalikan data.</p>
                        </div>
                        <div class="list-group" id="khsList">
                            <div class="text-muted">Memuat daftar KHS...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Ringkasan KHS</h4>
                    </div>
                    <div class="card-body" id="khsSummaryBox">
                        <div class="text-muted">Pilih data KHS untuk melihat ringkasan.</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Detail Mata Kuliah</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Nilai Angka</th>
                                        <th>Nilai Huruf</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Pilih data KHS terlebih dahulu.</td>
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
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script>
        const currentMahasiswaName = @json(session('profile.nama_mahasiswa') ?: 'Mahasiswa ini');
        const routes = {
            data: "{{ route('student.khs.data') }}",
            detailTemplate: "{{ route('student.khs.show', ['khsId' => '__KHS__']) }}",
            printTemplate: "{{ route('student.khs.print', ['khsId' => '__KHS__']) }}",
        };

        let khsCollection = [];
        let selectedKhsId = null;

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function notify(message, type = 'info') {
            const alertClass = {
                success: 'alert-success',
                danger: 'alert-danger',
                warning: 'alert-warning',
                info: 'alert-info'
            }[type] || 'alert-info';

            const html = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            $('.page-inner').prepend(html);

            setTimeout(() => $('.page-inner .alert').first().alert('close'), 4000);
        }

        function statusBadge(status) {
            const map = {
                draft: 'bg-secondary',
                preview: 'bg-info text-dark',
                final: 'bg-success',
                published: 'bg-success'
            };

            return `<span class="badge ${map[status] || 'bg-secondary'} status-pill">${escapeHtml(status || '-')}</span>`;
        }

        function normalizeCollection(payload) {
            if (Array.isArray(payload)) {
                return payload;
            }

            if (Array.isArray(payload?.data)) {
                return payload.data;
            }

            return [];
        }

        function formatSemester(item) {
            return item?.semester?.nama_semester || item?.nama_semester || item?.semester || '-';
        }

        function renderKhsList(data) {
            khsCollection = normalizeCollection(data);
            $('#totalKhsLabel').text(khsCollection.length);

            if (!khsCollection.length) {
                $('#khsList').html('');
                $('#khsEmptyState h5').text(`Belum ada data KHS untuk ${currentMahasiswaName}`);
                $('#khsEmptyState p').text('Nilai hasil studi belum digenerate. Setelah data KHS tersedia, daftar semester akan tampil di sini.');
                $('#khsEmptyState').removeClass('d-none');
                return;
            }

            $('#khsEmptyState').addClass('d-none');

            let html = '';
            khsCollection.forEach((item, index) => {
                const active = String(selectedKhsId) === String(item.id) ? 'active' : '';
                html += `
                    <button type="button" class="list-group-item list-group-item-action ${active}" onclick="selectKhsByIndex(${index})">
                        <div class="fw-semibold">${escapeHtml(formatSemester(item))}</div>
                        <small class="text-muted d-block">IPS: ${escapeHtml(item.ips ?? '0.00')} | IPK: ${escapeHtml(item.ipk ?? '0.00')}</small>
                        <small>${statusBadge(item.status || item.status_snapshot || '-')}</small>
                    </button>
                `;
            });

            $('#khsList').html(html);
        }

        function renderKhsDetail(data) {
            const summary = data?.summary || data || {};
            const details = Array.isArray(data?.details) ? data.details :
                (Array.isArray(data?.khs_detail) ? data.khs_detail : []);

            $('#semesterLabel').text(formatSemester(summary));
            $('#ipsLabel').text(summary.ips ?? '0.00');
            $('#ipkLabel').text(summary.ipk ?? '0.00');

            $('#khsSummaryBox').html(`
                <div class="row g-3">
                    <div class="col-md-6">
                        <div><strong>Semester:</strong> ${escapeHtml(formatSemester(summary))}</div>
                        <div><strong>Total SKS:</strong> ${escapeHtml(summary.total_sks ?? summary.total_sks_diambil ?? 0)}</div>
                        <div><strong>IPS:</strong> ${escapeHtml(summary.ips ?? '0.00')}</div>
                    </div>
                    <div class="col-md-6">
                        <div><strong>IPK:</strong> ${escapeHtml(summary.ipk ?? '0.00')}</div>
                        <div><strong>Status:</strong> ${escapeHtml(summary.status || summary.status_snapshot || '-')}</div>
                        <div><strong>Diperbarui:</strong> ${escapeHtml(summary.updated_at || summary.created_at || '-')}</div>
                    </div>
                    <div class="col-12">
                        <a href="${routes.printTemplate.replace('__KHS__', summary.id || data.id)}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i> Cetak KHS
                        </a>
                    </div>
                </div>
            `);

            if (!details.length) {
                $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Belum ada detail mata kuliah pada KHS ini.</td></tr>');
                return;
            }

            let rows = '';
            details.forEach((item, index) => {
                const mk = item.mata_kuliah || item.mk || {};
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(mk.kode_mk || item.kode_mk || '-')}</td>
                        <td>${escapeHtml(mk.nama_mk || item.nama_mk || item.nama_mata_kuliah || '-')}</td>
                        <td>${escapeHtml(mk.sks || item.sks || 0)}</td>
                        <td>${escapeHtml(item.nilai_akhir ?? item.nilai_angka ?? '-')}</td>
                        <td>${escapeHtml(item.nilai_huruf ?? '-')}</td>
                        <td>${statusBadge(item.status || summary.status || '-')}</td>
                    </tr>
                `;
            });

            $('#detailTableBody').html(rows);
        }

        function selectKhsByIndex(index) {
            const item = khsCollection[index];
            if (!item?.id) {
                notify('Data KHS tidak ditemukan.', 'warning');
                return;
            }

            selectedKhsId = item.id;
            renderKhsList(khsCollection);
            loadKhsDetail(item.id);
        }

        function loadKhsDetail(khsId) {
            $('#khsSummaryBox').html('<div class="text-muted">Memuat ringkasan KHS...</div>');
            $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Memuat detail KHS...</td></tr>');

            $.get(routes.detailTemplate.replace('__KHS__', khsId))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat detail KHS.', 'danger');
                        return;
                    }

                    renderKhsDetail(response.data || {});
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat detail KHS.', 'danger');
                });
        }

        function loadKhs() {
            $.get(routes.data)
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat daftar KHS.', 'danger');
                        renderKhsList([]);
                        return;
                    }

                    renderKhsList(response.data || []);

                    if (khsCollection.length) {
                        const targetId = selectedKhsId || khsCollection[0].id;
                        selectedKhsId = targetId;
                        renderKhsList(khsCollection);
                        loadKhsDetail(targetId);
                    } else {
                        $('#semesterLabel').text('-');
                        $('#ipsLabel').text('0.00');
                        $('#ipkLabel').text('0.00');
                        $('#khsSummaryBox').html(`<div class="text-muted">${escapeHtml(currentMahasiswaName)} belum memiliki data KHS.</div>`);
                        $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Belum ada detail KHS karena data KHS mahasiswa masih kosong.</td></tr>');
                        $('#semesterLabel').text('-');
                        $('#ipsLabel').text('0.00');
                        $('#ipkLabel').text('0.00');
                    }
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat daftar KHS.', 'danger');
                    renderKhsList([]);
                });
        }

        $('#refreshBtn').on('click', loadKhs);

        $(document).ready(function() {
            loadKhs();
        });
    </script>
@endpush
