@extends('layouts.index')
@section('title', 'KHS')

@push('styles-custom')
    <style>
        .khs-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 26%),
                linear-gradient(135deg, #1d4ed8 0%, #0f766e 52%, #0f172a 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-hero-copy {
            color: rgba(255, 255, 255, 0.82);
            max-width: 58ch;
        }

        .khs-panel {
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .khs-panel-header {
            padding: 1.35rem 1.5rem 0;
        }

        .khs-panel-body {
            padding: 1.5rem;
        }

        .khs-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .khs-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .khs-stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
            height: 100%;
        }

        .khs-stat-card .label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.45rem;
        }

        .khs-stat-card .value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .khs-semester-list {
            display: grid;
            gap: 0.85rem;
        }

        .khs-semester-item {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .khs-semester-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.06);
        }

        .khs-semester-item.active {
            border-color: #1d4ed8;
            background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
            box-shadow: 0 18px 30px rgba(29, 78, 216, 0.12);
        }

        .khs-empty-state {
            border: 1px dashed #cbd5e1;
            border-radius: 20px;
            padding: 2rem 1.25rem;
            text-align: center;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .khs-soft-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
            padding: 1rem;
            height: 100%;
        }

        .khs-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
        }

        .khs-table-wrap table {
            margin-bottom: 0;
        }

        .khs-status-pill {
            font-size: 0.8rem;
        }

        @media (max-width: 991.98px) {
            .khs-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .khs-stat-grid {
                grid-template-columns: 1fr;
            }
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

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-4">
                    <div>
                        <span class="khs-chip">
                            <i class="fas fa-file-signature"></i>
                            Ringkasan hasil studi per semester
                        </span>
                        <h2 class="fw-bold mt-3 mb-2">Lihat KHS dengan lebih jelas dan mudah dipahami.</h2>
                        <p class="khs-hero-copy mb-0">
                            Pilih semester di panel kiri untuk melihat ringkasan hasil studi, detail mata kuliah,
                            serta opsi cetak atau unduh KHS.
                        </p>
                    </div>
                    <button class="btn btn-light" id="refreshBtn">
                        <i class="fas fa-rotate-right me-1"></i> Muat Ulang
                    </button>
                </div>
            </div>
        </div>

        <div class="khs-stat-grid mb-4">
            <div class="khs-stat-card">
                <div class="label">Total KHS</div>
                <div class="value" id="totalKhsLabel">0</div>
            </div>
            <div class="khs-stat-card">
                <div class="label">Status Terpilih</div>
                <div class="value" id="statusLabel">-</div>
            </div>
            <div class="khs-stat-card">
                <div class="label">IPS</div>
                <div class="value" id="ipsLabel">0.00</div>
            </div>
            <div class="khs-stat-card">
                <div class="label">IPK</div>
                <div class="value" id="ipkLabel">0.00</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card khs-panel">
                    <div class="khs-panel-header">
                        <h4 class="fw-bold mb-1">Semester yang tersedia</h4>
                        <p class="text-muted mb-0">Pilih salah satu semester untuk membuka isi KHS.</p>
                    </div>
                    <div class="khs-panel-body">
                        <div id="khsEmptyState" class="khs-empty-state d-none">
                            <h5 class="mb-2">Belum ada data KHS</h5>
                            <p class="text-muted mb-0">Daftar semester akan muncul di sini setelah data KHS tersedia.</p>
                        </div>
                        <div class="khs-semester-list" id="khsList">
                            <div class="text-muted">Memuat daftar KHS...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card khs-panel mb-4">
                    <div class="khs-panel-header">
                        <h4 class="fw-bold mb-1">Ringkasan KHS terpilih</h4>
                        <p class="text-muted mb-0">Panel ini menampilkan informasi utama dari semester yang Anda pilih.</p>
                    </div>
                    <div class="khs-panel-body" id="khsSummaryBox">
                        <div class="text-muted">Pilih data KHS untuk melihat ringkasan.</div>
                    </div>
                </div>

                <div class="card khs-panel">
                    <div class="khs-panel-header">
                        <h4 class="fw-bold mb-1">Detail mata kuliah</h4>
                        <p class="text-muted mb-0">Daftar ini menampilkan nilai, status, mutu, dan bobot untuk setiap mata kuliah.</p>
                    </div>
                    <div class="khs-panel-body">
                        <div class="khs-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="6%">#</th>
                                            <th>Kode MK</th>
                                            <th>Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Nilai Angka</th>
                                            <th>Nilai Huruf</th>
                                            <th>Mutu</th>
                                            <th>Bobot</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailTableBody">
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Pilih data KHS terlebih dahulu.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const currentMahasiswaName = @json(session('profile.nama_mahasiswa') ?: 'Mahasiswa ini');
        const routes = {
            data: "{{ route('student.khs.data') }}",
            detailTemplate: "{{ route('student.khs.show', ['khsId' => '__KHS__']) }}",
            printTemplate: "{{ route('student.khs.print', ['khsId' => '__KHS__']) }}",
            downloadTemplate: "{{ route('student.khs.download', ['khsId' => '__KHS__']) }}",
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
                previewed: 'bg-info text-dark',
                final: 'bg-success',
                published: 'bg-success',
                processed: 'bg-success',
                failed: 'bg-danger',
                rolled_back: 'bg-dark'
            };

            return `<span class="badge ${map[status] || 'bg-secondary'} khs-status-pill">${escapeHtml(status || '-')}</span>`;
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
            const semester = item?.semester || {};
            const tahunAkademik = semester?.tahun_akademik || semester?.tahunAkademik || {};
            const tahunLabel = tahunAkademik?.tahun_akademik || '';
            return `${semester?.nama_semester || item?.nama_semester || item?.semester || '-'}`.trim() + (tahunLabel ? ` ${tahunLabel}` : '');
        }

        function renderKhsList(data) {
            khsCollection = normalizeCollection(data);
            $('#totalKhsLabel').text(khsCollection.length);

            if (!khsCollection.length) {
                $('#khsList').html('');
                $('#khsEmptyState h5').text(`Belum ada data KHS untuk ${currentMahasiswaName}`);
                $('#khsEmptyState p').text('Nilai hasil studi belum digenerate. Setelah data tersedia, daftar semester akan tampil di sini.');
                $('#khsEmptyState').removeClass('d-none');
                return;
            }

            $('#khsEmptyState').addClass('d-none');

            let html = '';
            khsCollection.forEach((item, index) => {
                const active = String(selectedKhsId) === String(item.id) ? 'active' : '';
                html += `
                    <button type="button" class="khs-semester-item ${active}" onclick="selectKhsByIndex(${index})">
                        <div class="fw-bold">${escapeHtml(formatSemester(item))}</div>
                        <div class="small text-muted mt-2">IPS ${escapeHtml(item.ips ?? '0.00')} • IPK ${escapeHtml(item.ipk ?? '0.00')}</div>
                        <div class="small text-muted">SKS Lulus ${escapeHtml(item.total_sks_lulus ?? 0)}</div>
                        <div class="mt-2">${statusBadge(item.is_final ? 'final' : 'draft')}</div>
                    </button>
                `;
            });

            $('#khsList').html(html);
        }

        function renderKhsDetail(data) {
            const summary = data?.summary || data || {};
            const details = Array.isArray(data?.details) ? data.details :
                (Array.isArray(data?.khs_detail) ? data.khs_detail : []);

            $('#statusLabel').text(summary.is_final ? 'Final' : 'Draft');
            $('#ipsLabel').text(summary.ips ?? '0.00');
            $('#ipkLabel').text(summary.ipk ?? '0.00');

            $('#khsSummaryBox').html(`
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="khs-soft-card">
                            <div class="small text-muted text-uppercase mb-2">Informasi Umum</div>
                            <div><strong>Semester:</strong> ${escapeHtml(formatSemester(summary))}</div>
                            <div><strong>Status:</strong> ${summary.is_final ? 'Final' : 'Draft'}</div>
                            <div><strong>Keterangan:</strong> ${escapeHtml(summary.keterangan || '-')}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="khs-soft-card">
                            <div class="small text-muted text-uppercase mb-2">Ringkasan Nilai</div>
                            <div><strong>Total SKS Diambil:</strong> ${escapeHtml(summary.total_sks ?? summary.total_sks_diambil ?? 0)}</div>
                            <div><strong>Total SKS Lulus:</strong> ${escapeHtml(summary.total_sks_lulus ?? 0)}</div>
                            <div><strong>Dibuat:</strong> ${escapeHtml(summary.generated_at || summary.updated_at || summary.created_at || '-')}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="${routes.printTemplate.replace('__KHS__', summary.id || data.id)}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-print me-1"></i> Cetak KHS
                            </a>
                            <a href="${routes.downloadTemplate.replace('__KHS__', summary.id || data.id)}" class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-file-arrow-down me-1"></i> Unduh KHS
                            </a>
                        </div>
                    </div>
                </div>
            `);

            if (!details.length) {
                $('#detailTableBody').html('<tr><td colspan="9" class="text-center text-muted">Belum ada detail mata kuliah pada KHS ini.</td></tr>');
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
                        <td>${escapeHtml(item.mutu ?? '-')}</td>
                        <td>${escapeHtml(item.bobot_nilai ?? '-')}</td>
                        <td>${statusBadge(item.status || (summary.is_final ? 'final' : 'draft'))}</td>
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
            $('#detailTableBody').html('<tr><td colspan="9" class="text-center text-muted">Memuat detail KHS...</td></tr>');

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
                        $('#statusLabel').text('-');
                        $('#ipsLabel').text('0.00');
                        $('#ipkLabel').text('0.00');
                        $('#khsSummaryBox').html(`<div class="text-muted">${escapeHtml(currentMahasiswaName)} belum memiliki data KHS.</div>`);
                        $('#detailTableBody').html('<tr><td colspan="9" class="text-center text-muted">Belum ada detail KHS karena data KHS mahasiswa masih kosong.</td></tr>');
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
