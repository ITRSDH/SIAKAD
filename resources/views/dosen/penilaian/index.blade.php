@extends('layouts.index')
@section('title', 'Penilaian')

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

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Penilaian</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('dosen.penilaian.index') }}">Penilaian</a></li>
            </ul>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Kelas Kuliah</div>
                    <div class="summary-value" id="kelasCount">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Komponen Aktif</div>
                    <div class="summary-value" id="komponenCount">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total Bobot</div>
                    <div class="summary-value" id="bobotTotalLabel">0%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Peserta Nilai</div>
                    <div class="summary-value" id="pesertaCount">0</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Daftar Kelas Kuliah</h4>
                            {{-- <small class="text-muted">Sumber data kelas mengikuti endpoint `/kelas-kuliah`.</small> --}}
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" id="refreshKelasBtn">
                            <i class="fas fa-rotate-right me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="kelasEmptyState" class="empty-state d-none">
                            <h5 class="mb-2">Belum ada kelas kuliah</h5>
                            <p class="text-muted mb-0">Pilih kelas untuk membuka komponen dan grid nilai.</p>
                        </div>
                        <div class="list-group" id="kelasList">
                            <div class="text-muted">Memuat kelas kuliah...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Komponen Penilaian</h4>
                            <small class="text-muted" id="selectedClassMeta">Pilih kelas kuliah terlebih dahulu.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" id="refreshKomponenBtn" disabled>
                                <i class="fas fa-rotate-right me-1"></i> Refresh
                            </button>
                            <button class="btn btn-sm btn-primary" id="addKomponenBtn" disabled>
                                <i class="fas fa-plus me-1"></i> Tambah Komponen
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="border rounded-3 p-3 mb-3 bg-light-subtle" id="workflowPanel">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="text-muted small mb-1">Workflow Penilaian</div>
                                    <div class="mb-2" id="workflowStatusBadge">{!! '<span class="badge bg-secondary status-pill">belum dipilih</span>' !!}</div>
                                    <div class="small text-muted" id="workflowStatusNote">Pilih kelas kuliah untuk melihat status penilaian.</div>
                                    <div class="small text-muted mt-1" id="workflowAuditInfo"></div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-warning" id="reopenPenilaianBtn" disabled>
                                        <i class="fas fa-unlock me-1"></i> Reopen
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th>Komponen</th>
                                        <th>Bobot</th>
                                        <th>Urutan</th>
                                        <th>Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="komponenTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Pilih kelas kuliah terlebih
                                            dahulu.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Grid Nilai</h4>
                            <small class="text-muted">Frontend akan menandai konteks presensi dan status kelayakan jika
                                backend mengirimkan data tersebut.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" id="refreshNilaiBtn" disabled>
                                <i class="fas fa-rotate-right me-1"></i> Refresh
                            </button>
                            <button class="btn btn-sm btn-success" id="publishFinalBtn" disabled>
                                <i class="fas fa-paper-plane me-1"></i> Publish Final
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead id="nilaiTableHead">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Presensi</th>
                                        <th>Status</th>
                                        <th>Nilai Final</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="nilaiTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Pilih kelas kuliah terlebih
                                            dahulu.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3" id="nilaiSaveActions"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="komponenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="komponenModalTitle">Tambah Komponen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="komponenForm">
                        <input type="hidden" id="komponenId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Komponen</label>
                                <input type="text" class="form-control" id="namaKomponen" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bobot</label>
                                <input type="number" class="form-control" id="bobotKomponen" min="0"
                                    max="100" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="urutanKomponen" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusKomponen">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="saveKomponenBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manualFinalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nilai Final Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="manualKrsDetailId">
                    <div class="mb-3">
                        <label class="form-label">Nilai Angka</label>
                        <input type="number" class="form-control" id="manualNilaiAkhir" min="0" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Huruf</label>
                        <input type="text" class="form-control" id="manualNilaiHuruf" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="manualCatatan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="saveManualFinalBtn">Simpan</button>
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
            kelasKuliah: "{{ route('dosen.penilaian.kelas.index') }}",
            komponenByKelasTemplate: "{{ route('dosen.penilaian.komponen.index', ['kelasKuliahId' => '__KELAS__']) }}",
            komponenStoreTemplate: "{{ route('dosen.penilaian.komponen.store', ['kelasKuliahId' => '__KELAS__']) }}",
            komponenUpdateTemplate: "{{ route('dosen.penilaian.komponen.update', ['id' => '__ID__']) }}",
            komponenDeleteTemplate: "{{ route('dosen.penilaian.komponen.destroy', ['id' => '__ID__']) }}",
            nilaiByKelasTemplate: "{{ route('dosen.penilaian.nilai.index', ['kelasKuliahId' => '__KELAS__']) }}",
            nilaiUpdateTemplate: "{{ route('dosen.penilaian.nilai.update', ['komponenId' => '__ID__']) }}",
            publishFinalTemplate: "{{ route('dosen.penilaian.publish-final', ['kelasKuliahId' => '__KELAS__']) }}",
            reopenTemplate: "{{ route('dosen.penilaian.reopen', ['kelasKuliahId' => '__KELAS__']) }}",
            manualFinalTemplate: "{{ route('dosen.penilaian.manual-final', ['krsDetailId' => '__ID__']) }}",
        };

        let kelasCollection = [];
        let komponenCollection = [];
        let nilaiCollection = [];
        let selectedClass = null;
        let currentWorkflow = null;
        let komponenModalInstance = null;
        let manualFinalModalInstance = null;

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

            const html =
                `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            $('.page-inner').prepend(html);
            setTimeout(() => $('.page-inner .alert').first().alert('close'), 4000);
        }

        function ensureKomponenModal() {
            if (!komponenModalInstance && window.bootstrap?.Modal) {
                komponenModalInstance = new window.bootstrap.Modal(document.getElementById('komponenModal'));
            }

            return komponenModalInstance;
        }

        function ensureManualFinalModal() {
            if (!manualFinalModalInstance && window.bootstrap?.Modal) {
                manualFinalModalInstance = new window.bootstrap.Modal(document.getElementById('manualFinalModal'));
            }

            return manualFinalModalInstance;
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

        function classLabel(item) {
            return {
                title: item?.nama_kelas || item?.kelas || '-',
                subtitle: item?.mata_kuliah?.nama_mk || item?.nama_mata_kuliah || item?.mata_kuliah || '-',
            };
        }

        function statusBadge(status) {
            const map = {
                aktif: 'bg-success',
                nonaktif: 'bg-secondary',
                layak_penilaian: 'bg-success',
                tidak_layak_penilaian: 'bg-danger',
                published: 'bg-success',
                draft: 'bg-secondary',
                reopened: 'bg-warning text-dark'
            };

            return `<span class="badge ${map[status] || 'bg-info text-dark'} status-pill">${escapeHtml(status || '-')}</span>`;
        }

        function workflowAllowsEdit() {
            return currentWorkflow?.can_manage_draft_data === true;
        }

        function workflowCanReopen() {
            return currentWorkflow?.can_reopen === true;
        }

        function formatDateTime(value) {
            if (!value) {
                return '-';
            }

            const date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return value;
            }

            return new Intl.DateTimeFormat('id-ID', {
                dateStyle: 'medium',
                timeStyle: 'short'
            }).format(date);
        }

        function renderWorkflowPanel(workflow = null) {
            currentWorkflow = workflow;

            if (!workflow) {
                $('#workflowStatusBadge').html('<span class="badge bg-secondary status-pill">belum dipilih</span>');
                $('#workflowStatusNote').text('Pilih kelas kuliah untuk melihat status penilaian.');
                $('#workflowAuditInfo').text('');
                $('#reopenPenilaianBtn').prop('disabled', true);
                return;
            }

            const notes = {
                draft: 'Komponen dan nilai masih bisa diubah. Publish final akan mengunci penilaian kelas.',
                published: 'Penilaian kelas sudah dipublikasikan. Ubah data harus melalui proses reopen.',
                reopened: 'Penilaian kelas sudah dibuka kembali. Komponen dan nilai dapat diperbarui lagi sebelum publish ulang.',
            };

            const auditParts = [];
            if (workflow.published_at) {
                auditParts.push(`Dipublish: ${formatDateTime(workflow.published_at)}`);
            }
            if (workflow.reopened_at) {
                auditParts.push(`Dibuka lagi: ${formatDateTime(workflow.reopened_at)}`);
            }
            if (workflow.reopen_reason) {
                auditParts.push(`Alasan: ${workflow.reopen_reason}`);
            }

            $('#workflowStatusBadge').html(statusBadge(workflow.status));
            $('#workflowStatusNote').text(notes[workflow.status] || 'Status workflow penilaian aktif pada kelas ini.');
            $('#workflowAuditInfo').text(auditParts.join(' | '));
            $('#reopenPenilaianBtn').prop('disabled', !workflowCanReopen());
        }

        function syncActionButtons() {
            const hasClass = !!selectedClass?.id;
            const canEdit = hasClass && workflowAllowsEdit();
            const canReopen = hasClass && workflowCanReopen();

            $('#refreshKomponenBtn, #refreshNilaiBtn').prop('disabled', !hasClass);
            $('#addKomponenBtn').prop('disabled', !canEdit);
            $('#publishFinalBtn').prop('disabled', !canEdit);
            $('#reopenPenilaianBtn').prop('disabled', !canReopen);
        }

        function loadKelasKuliah() {
            $.get(routes.kelasKuliah)
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat kelas kuliah.', 'danger');
                        renderKelasList([]);
                        return;
                    }

                    renderKelasList(response.data || []);

                    if (!normalizeCollection(response.data || []).length) {
                        notify(response.message || `${currentDosenName} belum memiliki kelas ajar.`, 'info');
                    }
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat kelas kuliah.', 'danger');
                    renderKelasList([]);
                });
        }

        function renderKelasList(data) {
            kelasCollection = normalizeCollection(data);
            $('#kelasCount').text(kelasCollection.length);

            if (!kelasCollection.length) {
                $('#kelasList').html('');
                $('#kelasEmptyState h5').text(`Belum ada kelas ajar untuk ${currentDosenName}`);
                $('#kelasEmptyState p').text(
                    'Dosen ini belum terdaftar sebagai dosen pengajar pada kelas mana pun, sehingga komponen penilaian dan input nilai belum bisa dibuka.'
                );
                $('#kelasEmptyState').removeClass('d-none');
                $('#refreshKomponenBtn, #addKomponenBtn, #refreshNilaiBtn, #publishFinalBtn').prop('disabled', true);
                $('#selectedClassMeta').text('Belum ada kelas ajar yang bisa dipilih.');
                $('#komponenCount').text('0');
                $('#bobotTotalLabel').text('0%');
                $('#pesertaCount').text('0');
                selectedClass = null;
                renderWorkflowPanel(null);
                $('#komponenTableBody').html(
                    `<tr><td colspan="6" class="text-center text-muted">${escapeHtml(currentDosenName)} belum memiliki kelas ajar untuk dinilai.</td></tr>`
                );
                $('#nilaiTableHead').html(
                    '<tr><th>Mahasiswa</th><th>Presensi</th><th>Status</th><th>Nilai Final</th><th width="12%">Aksi</th></tr>'
                );
                $('#nilaiTableBody').html(
                    '<tr><td colspan="5" class="text-center text-muted">Grid nilai akan tersedia setelah dosen terdaftar pada kelas ajar.</td></tr>'
                );
                $('#nilaiSaveActions').html('');
                return;
            }

            $('#kelasEmptyState').addClass('d-none');

            let html = '';
            kelasCollection.forEach((item, index) => {
                const label = classLabel(item);
                const active = String(selectedClass?.id) === String(item.id) ? 'active' : '';
                html += `
                    <button type="button" class="list-group-item list-group-item-action ${active}" onclick="selectKelasByIndex(${index})">
                        <div class="fw-semibold p-2">${escapeHtml(label.title)} - ${escapeHtml(label.subtitle)}</div>
                    </button>
                `;
            });

            $('#kelasList').html(html);
        }

        function selectKelasByIndex(index) {
            const item = kelasCollection[index];
            if (!item?.id) {
                notify('Data kelas kuliah tidak ditemukan.', 'warning');
                return;
            }

            selectedClass = item;
            renderKelasList(kelasCollection);
            renderWorkflowPanel(null);
            syncActionButtons();

            const label = classLabel(item);
            $('#selectedClassMeta').text(`${label.title} - ${label.subtitle}`);

            loadKomponen();
            loadNilai();
        }

        function loadKomponen() {
            if (!selectedClass?.id) {
                return;
            }

            $('#komponenTableBody').html('<tr><td colspan="6" class="text-center text-muted">Memuat komponen...</td></tr>');

            $.get(routes.komponenByKelasTemplate.replace('__KELAS__', selectedClass.id))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat komponen penilaian.', 'danger');
                        return;
                    }

                    renderKomponenTable(response.data || {});
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat komponen penilaian.', 'danger');
                    $('#komponenTableBody').html(
                        '<tr><td colspan="6" class="text-center text-danger">Gagal memuat komponen.</td></tr>');
                });
        }

        function renderKomponenTable(data) {
            currentWorkflow = data?.workflow_penilaian || currentWorkflow;
            renderWorkflowPanel(currentWorkflow);
            komponenCollection = Array.isArray(data?.komponen) ? data.komponen : normalizeCollection(data);
            const activeComponents = komponenCollection.filter(item => {
                return item.is_active === true || item.is_active === 1 || item.is_active === '1' || item.status ===
                    'aktif';
            });

            $('#komponenCount').text(activeComponents.length);
            $('#bobotTotalLabel').text(`${activeComponents.reduce((sum, item) => sum + Number(item.bobot || 0), 0)}%`);
            syncActionButtons();

            if (!komponenCollection.length) {
                $('#komponenTableBody').html(
                    '<tr><td colspan="6" class="text-center text-muted">Belum ada komponen pada kelas ini.</td></tr>');
                return;
            }

            let rows = '';
            komponenCollection.forEach((item, index) => {
                const active = item.is_active === true || item.is_active === 1 || item.is_active === '1' || item
                    .status === 'aktif';
                const editDisabled = workflowAllowsEdit() ? '' : 'disabled';
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(item.nama || item.nama_komponen || '-')}</td>
                        <td>${escapeHtml(item.bobot || 0)}%</td>
                        <td>${escapeHtml(item.urutan || '-')}</td>
                        <td>${statusBadge(active ? 'aktif' : 'nonaktif')}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="openEditKomponen('${item.id}')" ${editDisabled}>Edit</button>
                                <button class="btn btn-outline-danger" onclick="deleteKomponen('${item.id}')" ${editDisabled}>Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#komponenTableBody').html(rows);
        }

        function resetKomponenForm() {
            $('#komponenId').val('');
            $('#namaKomponen').val('');
            $('#bobotKomponen').val('');
            $('#urutanKomponen').val('');
            $('#statusKomponen').val('1');
            $('#komponenModalTitle').text('Tambah Komponen');
        }

        function openAddKomponen() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Komponen tidak dapat ditambah karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            resetKomponenForm();
            ensureKomponenModal()?.show();
        }

        function openEditKomponen(id) {
            const item = komponenCollection.find(row => String(row.id) === String(id));
            if (!item) {
                notify('Data komponen tidak ditemukan.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Komponen tidak dapat diubah karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            $('#komponenId').val(item.id);
            $('#namaKomponen').val(item.nama || item.nama_komponen || '');
            $('#bobotKomponen').val(item.bobot || '');
            $('#urutanKomponen').val(item.urutan || '');
            $('#statusKomponen').val(item.is_active === false || item.is_active === 0 || item.is_active === '0' ? '0' :
                '1');
            $('#komponenModalTitle').text('Edit Komponen');
            ensureKomponenModal()?.show();
        }

        function saveKomponen() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Komponen tidak dapat disimpan karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            const payload = {
                nama: $('#namaKomponen').val(),
                bobot: $('#bobotKomponen').val(),
                urutan: $('#urutanKomponen').val(),
                is_active: $('#statusKomponen').val()
            };

            const komponenId = $('#komponenId').val();
            const isEdit = !!komponenId;
            const url = isEdit ?
                routes.komponenUpdateTemplate.replace('__ID__', komponenId) :
                routes.komponenStoreTemplate.replace('__KELAS__', selectedClass.id);

            $.ajax({
                url: url,
                method: isEdit ? 'PUT' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: payload,
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menyimpan komponen.', 'danger');
                        return;
                    }

                    notify(response.message || 'Komponen berhasil disimpan.', 'success');
                    komponenModalInstance?.hide();
                    loadKomponen();
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menyimpan komponen.', 'danger');
                }
            });
        }

        function deleteKomponen(id) {
            if (!workflowAllowsEdit()) {
                notify('Komponen tidak dapat dihapus karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            if (!confirm('Hapus komponen ini?')) {
                return;
            }

            $.ajax({
                url: routes.komponenDeleteTemplate.replace('__ID__', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menghapus komponen.', 'danger');
                        return;
                    }

                    notify(response.message || 'Komponen berhasil dihapus.', 'success');
                    loadKomponen();
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menghapus komponen.', 'danger');
                }
            });
        }

        function loadNilai() {
            if (!selectedClass?.id) {
                return;
            }

            $('#nilaiTableBody').html('<tr><td colspan="5" class="text-center text-muted">Memuat grid nilai...</td></tr>');
            $('#nilaiSaveActions').html('');

            $.get(routes.nilaiByKelasTemplate.replace('__KELAS__', selectedClass.id))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat grid nilai.', 'danger');
                        return;
                    }

                    renderNilaiGrid(response.data || {});
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat grid nilai.', 'danger');
                    $('#nilaiTableBody').html(
                        '<tr><td colspan="5" class="text-center text-danger">Gagal memuat grid nilai.</td></tr>');
                });
        }

        function renderNilaiGrid(data) {
            currentWorkflow = data?.workflow_penilaian || currentWorkflow;
            renderWorkflowPanel(currentWorkflow);
            const komponen = Array.isArray(data?.komponen) ? data.komponen : komponenCollection;
            nilaiCollection = Array.isArray(data?.mahasiswa) ? data.mahasiswa : normalizeCollection(data);
            const canEdit = workflowAllowsEdit();

            $('#pesertaCount').text(nilaiCollection.length);
            syncActionButtons();

            let headHtml = '<tr><th>Mahasiswa</th><th>Presensi</th><th>Status</th>';
            komponen.forEach(item => {
                headHtml +=
                    `<th>${escapeHtml(item.nama || item.nama_komponen || 'Komponen')}<br><small>${escapeHtml(item.bobot || 0)}%</small></th>`;
            });
            headHtml += '<th>Nilai Final</th><th width="12%">Aksi</th></tr>';
            $('#nilaiTableHead').html(headHtml);

            if (!nilaiCollection.length) {
                $('#nilaiTableBody').html(
                    `<tr><td colspan="${komponen.length + 5}" class="text-center text-muted">Belum ada peserta atau data nilai pada kelas ini.</td></tr>`
                );
                return;
            }

            let rows = '';
            nilaiCollection.forEach((item, index) => {
                const mahasiswa = item.mahasiswa || item;
                const presensi = item.presensi_summary || {};
                const finalScore = item.nilai_akhir_existing || item;
                rows += `
                    <tr>
                        <td>
                            <div class="fw-semibold">${escapeHtml(mahasiswa.nama_mahasiswa || '-')}</div>
                            <small class="text-muted">${escapeHtml(mahasiswa.nim || '-')}</small>
                        </td>
                        <td>${escapeHtml(presensi.persentase_presensi ?? item.persentase_presensi ?? item.presensi_persen ?? '-')} ${(presensi.persentase_presensi != null || item.persentase_presensi != null || item.presensi_persen != null) ? '%' : ''}</td>
                        <td>${statusBadge(presensi.is_layak_penilaian === false ? 'tidak_layak_penilaian' : (presensi.is_layak_penilaian === true ? 'layak_penilaian' : (item.status_kelayakan || item.status_penilaian || '-')))}</td>
                `;

                komponen.forEach(component => {
                    const score = findNilaiForKomponen(item, component.id);
                    rows += `
                        <td>
                            <input type="number" class="form-control form-control-sm nilai-input"
                                data-komponen-id="${escapeHtml(component.id)}"
                                data-krs-detail-id="${escapeHtml(item.id_krs_detail || item.krs_detail_id || item.id || '')}"
                                value="${escapeHtml(score ?? '')}"
                                min="0" max="100" ${canEdit ? '' : 'disabled'}>
                        </td>
                    `;
                });

                rows += `
                        <td>
                            <div>${escapeHtml(finalScore.nilai_akhir ?? item.nilai_akhir ?? '-')}</div>
                            <small class="text-muted">${escapeHtml(finalScore.nilai_huruf ?? item.nilai_huruf ?? '-')}</small>
                        </td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" onclick="openManualFinal('${item.id_krs_detail || item.krs_detail_id || item.id || ''}', '${escapeHtml(finalScore.nilai_akhir ?? item.nilai_akhir ?? '')}', '${escapeHtml(finalScore.nilai_huruf ?? item.nilai_huruf ?? '')}')" ${canEdit ? '' : 'disabled'}>Manual</button>
                        </td>
                    </tr>
                `;
            });

            $('#nilaiTableBody').html(rows);
            renderNilaiSaveActions(komponen);
        }

        function findNilaiForKomponen(item, komponenId) {
            const nilaiItems = Array.isArray(item.nilai_komponen) ? item.nilai_komponen : (Array.isArray(item.komponen) ?
                item.komponen : []);
            const found = nilaiItems.find(entry => String(entry.id_komponen_penilaian || entry.komponen_id || entry
                .id_komponen || entry.id) === String(komponenId));
            return found ? (found.nilai ?? found.nilai_angka ?? '') : '';
        }

        function renderNilaiSaveActions(komponen) {
            if (!komponen.length || !workflowAllowsEdit()) {
                $('#nilaiSaveActions').html('');
                return;
            }

            let html = '<div class="d-flex flex-wrap gap-2">';
            komponen.forEach(item => {
                html +=
                    `<button type="button" class="btn btn-outline-success btn-sm" onclick="saveNilaiKomponen('${item.id}', '${escapeHtml(item.nama || item.nama_komponen || item.kode_komponen || 'Komponen')}')">Simpan ${escapeHtml(item.nama || item.nama_komponen || item.kode_komponen || 'Komponen')}</button>`;
            });
            html += '</div>';
            $('#nilaiSaveActions').html(html);
        }

        function saveNilaiKomponen(komponenId, label) {
            if (!workflowAllowsEdit()) {
                notify('Nilai komponen tidak dapat disimpan karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            const payload = [];
            $(`.nilai-input[data-komponen-id="${komponenId}"]`).each(function() {
                payload.push({
                    id_krs_detail: $(this).data('krs-detail-id'),
                    nilai: $(this).val()
                });
            });

            $.ajax({
                url: routes.nilaiUpdateTemplate.replace('__ID__', komponenId),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    nilai: payload
                }),
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || `Gagal menyimpan nilai ${label}.`, 'danger');
                        return;
                    }

                    notify(response.message || `Nilai ${label} berhasil disimpan.`, 'success');
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || `Gagal menyimpan nilai ${label}.`, 'danger');
                }
            });
        }

        function publishFinal() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Publish final hanya dapat dilakukan saat penilaian kelas masih dapat diedit.', 'warning');
                return;
            }

            if (!confirm('Publish nilai final untuk kelas ini? Pastikan komponen dan nilai sudah benar.')) {
                return;
            }

            $.ajax({
                url: routes.publishFinalTemplate.replace('__KELAS__', selectedClass.id),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal publish nilai final.', 'danger');
                        return;
                    }

                    notify(response.message || 'Nilai final berhasil dipublish.', 'success');
                    loadKomponen();
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal publish nilai final.', 'danger');
                }
            });
        }

        function openManualFinal(krsDetailId, nilaiAkhir, nilaiHuruf) {
            if (!krsDetailId) {
                notify('ID KRS detail tidak tersedia pada baris ini.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Nilai final manual tidak dapat diubah karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            $('#manualKrsDetailId').val(krsDetailId);
            $('#manualNilaiAkhir').val(nilaiAkhir);
            $('#manualNilaiHuruf').val(nilaiHuruf);
            $('#manualCatatan').val('');
            ensureManualFinalModal()?.show();
        }

        function saveManualFinal() {
            const krsDetailId = $('#manualKrsDetailId').val();
            if (!krsDetailId) {
                notify('ID KRS detail tidak tersedia.', 'warning');
                return;
            }

            if (!workflowAllowsEdit()) {
                notify('Nilai final manual tidak dapat disimpan karena penilaian kelas sedang terkunci.', 'warning');
                return;
            }

            $.ajax({
                url: routes.manualFinalTemplate.replace('__ID__', krsDetailId),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    nilai_akhir: $('#manualNilaiAkhir').val(),
                    catatan: $('#manualCatatan').val()
                },
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menyimpan nilai final manual.', 'danger');
                        return;
                    }

                    notify(response.message || 'Nilai final manual berhasil disimpan.', 'success');
                    manualFinalModalInstance?.hide();
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menyimpan nilai final manual.', 'danger');
                }
            });
        }

        function reopenPenilaian() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            if (!workflowCanReopen()) {
                notify('Penilaian kelas ini belum bisa dibuka kembali.', 'warning');
                return;
            }

            const reopenReason = prompt('Masukkan alasan reopen penilaian kelas ini:');
            if (reopenReason === null) {
                return;
            }

            if (!reopenReason.trim()) {
                notify('Alasan reopen wajib diisi.', 'warning');
                return;
            }

            $.ajax({
                url: routes.reopenTemplate.replace('__KELAS__', selectedClass.id),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    reopen_reason: reopenReason.trim()
                },
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal membuka kembali penilaian kelas.', 'danger');
                        return;
                    }

                    notify(response.message || 'Penilaian kelas berhasil dibuka kembali.', 'success');
                    loadKomponen();
                    loadNilai();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal membuka kembali penilaian kelas.', 'danger');
                }
            });
        }

        $('#refreshKelasBtn').on('click', loadKelasKuliah);
        $('#refreshKomponenBtn').on('click', loadKomponen);
        $('#refreshNilaiBtn').on('click', loadNilai);
        $('#addKomponenBtn').on('click', openAddKomponen);
        $('#reopenPenilaianBtn').on('click', reopenPenilaian);
        $('#saveKomponenBtn').on('click', saveKomponen);
        $('#publishFinalBtn').on('click', publishFinal);
        $('#saveManualFinalBtn').on('click', saveManualFinal);

        $(document).ready(function() {
            loadKelasKuliah();
        });
    </script>
@endpush
