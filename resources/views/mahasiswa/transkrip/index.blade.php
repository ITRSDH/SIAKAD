@extends('layouts.index')
@section('title', 'Transkrip')

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
            <h3 class="fw-bold mb-3">Transkrip</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('student.transkrip.index') }}">Transkrip</a></li>
            </ul>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total Transkrip</div>
                    <div class="summary-value" id="totalTranskripLabel">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total SKS Lulus</div>
                    <div class="summary-value" id="sksLabel">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">IPK</div>
                    <div class="summary-value" id="ipkLabel">0.00</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Status</div>
                    <div class="summary-value" id="statusLabel">-</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Daftar Transkrip</h4>
                            <small class="text-muted">Data diambil dari endpoint `/transkrip`.</small>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                            <i class="fas fa-rotate-right me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="transkripEmptyState" class="empty-state d-none">
                            <h5 class="mb-2">Belum ada data transkrip</h5>
                            <p class="text-muted mb-0">Transkrip akan tampil di sini setelah backend mengembalikan data.</p>
                        </div>
                        <div class="list-group" id="transkripList">
                            <div class="text-muted">Memuat daftar transkrip...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Ringkasan Transkrip</h4>
                    </div>
                    <div class="card-body" id="summaryBox">
                        <div class="text-muted">Pilih data transkrip untuk melihat ringkasan.</div>
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
                                        <th>Bobot</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Pilih data transkrip terlebih dahulu.</td>
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
            data: "{{ route('student.transkrip.data') }}",
            detailTemplate: "{{ route('student.transkrip.show', ['transkripId' => '__TRANSKRIP__']) }}",
        };

        let transkripCollection = [];
        let selectedTranskripId = null;

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

        function renderList(data) {
            transkripCollection = normalizeCollection(data);
            $('#totalTranskripLabel').text(transkripCollection.length);

            if (!transkripCollection.length) {
                $('#transkripList').html('');
                $('#transkripEmptyState h5').text(`Belum ada data transkrip untuk ${currentMahasiswaName}`);
                $('#transkripEmptyState p').text('Transkrip belum digenerate. Setelah data tersedia, ringkasan akademik mahasiswa akan tampil di sini.');
                $('#transkripEmptyState').removeClass('d-none');
                return;
            }

            $('#transkripEmptyState').addClass('d-none');

            let html = '';
            transkripCollection.forEach((item, index) => {
                const active = String(selectedTranskripId) === String(item.id) ? 'active' : '';
                html += `
                    <button type="button" class="list-group-item list-group-item-action ${active}" onclick="selectTranskripByIndex(${index})">
                        <div class="fw-semibold">${escapeHtml(item.nama_mahasiswa || item.mahasiswa?.nama_mahasiswa || 'Transkrip')}</div>
                        <small class="text-muted d-block">IPK: ${escapeHtml(item.ipk ?? '0.00')} | SKS: ${escapeHtml(item.total_sks_lulus ?? item.total_sks ?? 0)}</small>
                        <small>${statusBadge(item.status || item.status_snapshot || '-')}</small>
                    </button>
                `;
            });

            $('#transkripList').html(html);
        }

        function renderDetail(data) {
            const summary = data?.summary || data || {};
            const details = Array.isArray(data?.details) ? data.details :
                (Array.isArray(data?.transkrip_detail) ? data.transkrip_detail : []);

            $('#sksLabel').text(summary.total_sks_lulus ?? summary.total_sks ?? 0);
            $('#ipkLabel').text(summary.ipk ?? '0.00');
            $('#statusLabel').html(statusBadge(summary.status || summary.status_snapshot || '-'));

            $('#summaryBox').html(`
                <div class="row g-3">
                    <div class="col-md-6">
                        <div><strong>Mahasiswa:</strong> ${escapeHtml(summary.nama_mahasiswa || summary.mahasiswa?.nama_mahasiswa || '-')}</div>
                        <div><strong>Total SKS Lulus:</strong> ${escapeHtml(summary.total_sks_lulus ?? summary.total_sks ?? 0)}</div>
                        <div><strong>IPK:</strong> ${escapeHtml(summary.ipk ?? '0.00')}</div>
                    </div>
                    <div class="col-md-6">
                        <div><strong>Status:</strong> ${escapeHtml(summary.status || summary.status_snapshot || '-')}</div>
                        <div><strong>Diperbarui:</strong> ${escapeHtml(summary.updated_at || summary.created_at || '-')}</div>
                        <div><strong>Jumlah Detail:</strong> ${escapeHtml(details.length)}</div>
                    </div>
                </div>
            `);

            if (!details.length) {
                $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Belum ada detail mata kuliah pada transkrip ini.</td></tr>');
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
                        <td>${escapeHtml(item.bobot_nilai ?? item.bobot ?? '-')}</td>
                    </tr>
                `;
            });

            $('#detailTableBody').html(rows);
        }

        function selectTranskripByIndex(index) {
            const item = transkripCollection[index];
            if (!item?.id) {
                notify('Data transkrip tidak ditemukan.', 'warning');
                return;
            }

            selectedTranskripId = item.id;
            renderList(transkripCollection);
            loadTranskripDetail(item.id);
        }

        function loadTranskripDetail(transkripId) {
            $('#summaryBox').html('<div class="text-muted">Memuat ringkasan transkrip...</div>');
            $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Memuat detail transkrip...</td></tr>');

            $.get(routes.detailTemplate.replace('__TRANSKRIP__', transkripId))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat detail transkrip.', 'danger');
                        return;
                    }

                    renderDetail(response.data || {});
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat detail transkrip.', 'danger');
                });
        }

        function loadTranskrip() {
            $.get(routes.data)
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat daftar transkrip.', 'danger');
                        renderList([]);
                        return;
                    }

                    renderList(response.data || []);

                    if (transkripCollection.length) {
                        const targetId = selectedTranskripId || transkripCollection[0].id;
                        selectedTranskripId = targetId;
                        renderList(transkripCollection);
                        loadTranskripDetail(targetId);
                    } else {
                        $('#sksLabel').text('0');
                        $('#ipkLabel').text('0.00');
                        $('#statusLabel').text('-');
                        $('#summaryBox').html(`<div class="text-muted">${escapeHtml(currentMahasiswaName)} belum memiliki data transkrip.</div>`);
                        $('#detailTableBody').html('<tr><td colspan="7" class="text-center text-muted">Belum ada detail transkrip karena data transkrip mahasiswa masih kosong.</td></tr>');
                    }
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat daftar transkrip.', 'danger');
                    renderList([]);
                });
        }

        $('#refreshBtn').on('click', loadTranskrip);

        $(document).ready(function() {
            loadTranskrip();
        });
    </script>
@endpush
