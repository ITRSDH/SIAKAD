@extends('layouts.index')
@section('title', 'Pertemuan & Presensi')

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
            <h3 class="fw-bold mb-3">Pertemuan & Presensi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('dosen.pertemuan-presensi.index') }}">Pertemuan & Presensi</a></li>
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
                    <div class="summary-label">Pertemuan</div>
                    <div class="summary-value" id="pertemuanCount">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Pertemuan Selesai</div>
                    <div class="summary-value" id="pertemuanSelesaiCount">0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Peserta Rekap</div>
                    <div class="summary-value" id="rekapPesertaCount">0</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Daftar Kelas Kuliah</h4>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" id="refreshKelasBtn">
                            <i class="fas fa-rotate-right me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="kelasEmptyState" class="empty-state d-none">
                            <h5 class="mb-2">Belum ada kelas kuliah</h5>
                            <p class="text-muted mb-0">Pilih kelas dari data backend yang tersedia untuk membuka pertemuan
                                dan presensi.</p>
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
                            <h4 class="card-title mb-0">Daftar Pertemuan</h4>
                            <small class="text-muted" id="selectedClassMeta">Pilih kelas kuliah terlebih dahulu.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" id="refreshPertemuanBtn" disabled>
                                <i class="fas fa-rotate-right me-1"></i> Refresh
                            </button>
                            <button class="btn btn-sm btn-primary" id="addPertemuanBtn" disabled>
                                <i class="fas fa-plus me-1"></i> Tambah Pertemuan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th>Pertemuan</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Materi</th>
                                        <th width="16%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="pertemuanTableBody">
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
                    <div class="card-header">
                        <h4 class="card-title mb-0">Rekap Presensi Kelas</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th>Mahasiswa</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpa</th>
                                        <th>Persentase</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Pilih kelas kuliah untuk melihat
                                            rekap presensi.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pertemuanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pertemuanModalTitle">Tambah Pertemuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="pertemuanForm">
                        <input type="hidden" id="pertemuanId">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Pertemuan Ke</label>
                                <input type="number" class="form-control" id="pertemuanKe" min="1" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Judul Pertemuan</label>
                                <input type="text" class="form-control" id="judulPertemuan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pertemuan</label>
                                <input type="date" class="form-control" id="tanggalPertemuan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusPertemuan" required>
                                    <option value="draft">Draft</option>
                                    <option value="terjadwal">Terjadwal</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Materi</label>
                                <textarea class="form-control" id="materiPertemuan" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatanPertemuan" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="savePertemuanBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="presensiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Presensi Pertemuan</h5>
                        <small class="text-muted" id="presensiModalMeta">Memuat data presensi...</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th width="6%">#</th>
                                    <th>Mahasiswa</th>
                                    <th width="18%">Status Kehadiran</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="presensiTableBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Memuat data presensi...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-info" id="generatePesertaBtn" type="button">Generate Peserta</button>
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-success" id="savePresensiBtn">Simpan Presensi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <script>
        const currentDosenName = @json(session('profile.nama_dosen') ?: 'Dosen ini');
        const routes = {
            kelasKuliah: "{{ route('dosen.pertemuan-presensi.kelas.index') }}",
            pertemuanByKelasTemplate: "{{ route('dosen.pertemuan-presensi.pertemuan.index', ['kelasKuliahId' => '__KELAS__']) }}",
            storePertemuanTemplate: "{{ route('dosen.pertemuan-presensi.pertemuan.store', ['kelasKuliahId' => '__KELAS__']) }}",
            updatePertemuanTemplate: "{{ route('dosen.pertemuan-presensi.pertemuan.update', ['id' => '__ID__']) }}",
            presensiTemplate: "{{ route('dosen.pertemuan-presensi.presensi.show', ['pertemuanId' => '__ID__']) }}",
            generatePesertaTemplate: "{{ route('dosen.pertemuan-presensi.presensi.generate', ['pertemuanId' => '__ID__']) }}",
            updatePresensiTemplate: "{{ route('dosen.pertemuan-presensi.presensi.update', ['pertemuanId' => '__ID__']) }}",
            rekapTemplate: "{{ route('dosen.pertemuan-presensi.rekap', ['kelasKuliahId' => '__KELAS__']) }}",
        };

        let kelasCollection = [];
        let pertemuanCollection = [];
        let presensiCollection = [];
        let selectedClass = null;
        let activePertemuanId = null;
        let pertemuanModalInstance = null;
        let presensiModalInstance = null;

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

        function ensurePertemuanModal() {
            if (!pertemuanModalInstance && window.bootstrap?.Modal) {
                pertemuanModalInstance = new window.bootstrap.Modal(document.getElementById('pertemuanModal'));
            }

            return pertemuanModalInstance;
        }

        function ensurePresensiModal() {
            if (!presensiModalInstance && window.bootstrap?.Modal) {
                presensiModalInstance = new window.bootstrap.Modal(document.getElementById('presensiModal'));
            }

            return presensiModalInstance;
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

        function statusBadge(status) {
            const map = {
                draft: 'bg-secondary',
                terjadwal: 'bg-info text-dark',
                selesai: 'bg-success',
                dibatalkan: 'bg-danger',
                hadir: 'bg-success',
                izin: 'bg-warning text-dark',
                sakit: 'bg-info text-dark',
                alpa: 'bg-danger',
                layak_penilaian: 'bg-success',
                tidak_layak_penilaian: 'bg-danger'
            };

            return `<span class="badge ${map[status] || 'bg-secondary'} status-pill">${escapeHtml(status || '-')}</span>`;
        }

        function classLabel(item) {
            const namaKelas = item?.nama_kelas || item?.kelas || '-';
            const mataKuliah = item?.mata_kuliah?.nama_mk || item?.nama_mata_kuliah || item?.mata_kuliah || '-';
            return {
                title: namaKelas,
                subtitle: mataKuliah,
            };
        }

        function renderKelasList(data) {
            kelasCollection = normalizeCollection(data);
            $('#kelasCount').text(kelasCollection.length);

            if (!kelasCollection.length) {
                $('#kelasList').html('');
                $('#kelasEmptyState h5').text(`Belum ada kelas ajar untuk ${currentDosenName}`);
                $('#kelasEmptyState p').text(
                    'Dosen ini belum terdaftar sebagai dosen pengajar pada kelas mana pun, sehingga pertemuan dan presensi belum bisa dikelola.'
                );
                $('#kelasEmptyState').removeClass('d-none');
                $('#refreshPertemuanBtn, #addPertemuanBtn').prop('disabled', true);
                $('#selectedClassMeta').text('Belum ada kelas ajar yang bisa dipilih.');
                $('#pertemuanCount').text('0');
                $('#pertemuanSelesaiCount').text('0');
                $('#rekapPesertaCount').text('0');
                $('#pertemuanTableBody').html(
                    `<tr><td colspan="6" class="text-center text-muted">${escapeHtml(currentDosenName)} belum memiliki kelas ajar untuk dikelola.</td></tr>`
                );
                $('#rekapTableBody').html(
                    '<tr><td colspan="8" class="text-center text-muted">Rekap presensi akan tersedia setelah dosen terdaftar pada kelas ajar.</td></tr>'
                );
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

        function selectKelasByIndex(index) {
            const item = kelasCollection[index];
            if (!item?.id) {
                notify('Data kelas kuliah tidak ditemukan.', 'warning');
                return;
            }

            selectedClass = item;
            renderKelasList(kelasCollection);
            $('#refreshPertemuanBtn, #addPertemuanBtn').prop('disabled', false);

            const label = classLabel(item);
            $('#selectedClassMeta').text(`${label.title} - ${label.subtitle}`);

            loadPertemuan();
            loadRekap();
        }

        function renderPertemuanTable(data) {
            pertemuanCollection = normalizeCollection(data);
            $('#pertemuanCount').text(pertemuanCollection.length);
            $('#pertemuanSelesaiCount').text(pertemuanCollection.filter(item => item.status === 'selesai').length);

            if (!pertemuanCollection.length) {
                $('#pertemuanTableBody').html(
                    '<tr><td colspan="6" class="text-center text-muted">Belum ada pertemuan pada kelas ini.</td></tr>');
                return;
            }

            let rows = '';
            pertemuanCollection.forEach((item, index) => {
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(item.judul_pertemuan || `Pertemuan ${item.pertemuan_ke || index + 1}`)}</div>
                            <small class="text-muted">Pertemuan ke-${escapeHtml(item.pertemuan_ke || '-')}</small>
                        </td>
                        <td>${escapeHtml(item.tanggal_pertemuan || '-')}</td>
                        <td>${statusBadge(item.status)}</td>
                        <td>${escapeHtml(item.materi || '-')}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="openEditPertemuan('${item.id}')">Edit</button>
                                <button class="btn btn-outline-success" onclick="openPresensi('${item.id}')">Presensi</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#pertemuanTableBody').html(rows);
        }

        function loadPertemuan() {
            if (!selectedClass?.id) {
                return;
            }

            $('#pertemuanTableBody').html(
                '<tr><td colspan="6" class="text-center text-muted">Memuat data pertemuan...</td></tr>');

            $.get(routes.pertemuanByKelasTemplate.replace('__KELAS__', selectedClass.id))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat pertemuan.', 'danger');
                        return;
                    }

                    renderPertemuanTable(response.data?.pertemuan || []);
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat pertemuan.', 'danger');
                    $('#pertemuanTableBody').html(
                        '<tr><td colspan="6" class="text-center text-danger">Gagal memuat pertemuan.</td></tr>');
                });
        }

        function renderRekapTable(data) {
            const rows = normalizeCollection(data);
            $('#rekapPesertaCount').text(rows.length);

            if (!rows.length) {
                $('#rekapTableBody').html(
                    '<tr><td colspan="8" class="text-center text-muted">Belum ada rekap presensi untuk kelas ini.</td></tr>'
                );
                return;
            }

            let html = '';
            rows.forEach((item, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(item.nama_mahasiswa || item.mahasiswa?.nama_mahasiswa || '-')}</div>
                            <small class="text-muted">${escapeHtml(item.nim || item.mahasiswa?.nim || '-')}</small>
                        </td>
                        <td>${escapeHtml(item.hadir ?? 0)}</td>
                        <td>${escapeHtml(item.izin ?? 0)}</td>
                        <td>${escapeHtml(item.sakit ?? 0)}</td>
                        <td>${escapeHtml(item.alpa ?? 0)}</td>
                        <td>${escapeHtml(item.persentase_presensi ?? item.persentase ?? 0)}%</td>
                        <td>${statusBadge(item.status_kelayakan || item.status || '-')}</td>
                    </tr>
                `;
            });

            $('#rekapTableBody').html(html);
        }

        function loadRekap() {
            if (!selectedClass?.id) {
                return;
            }

            $('#rekapTableBody').html(
                '<tr><td colspan="8" class="text-center text-muted">Memuat rekap presensi...</td></tr>');

            $.get(routes.rekapTemplate.replace('__KELAS__', selectedClass.id))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat rekap presensi.', 'danger');
                        return;
                    }

                    renderRekapTable(response.data || []);
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat rekap presensi.', 'danger');
                    $('#rekapTableBody').html(
                        '<tr><td colspan="8" class="text-center text-danger">Gagal memuat rekap presensi.</td></tr>'
                    );
                });
        }

        function resetPertemuanForm() {
            $('#pertemuanId').val('');
            $('#pertemuanKe').val('');
            $('#judulPertemuan').val('');
            $('#tanggalPertemuan').val('').prop('required', false);
            $('#statusPertemuan').val('draft').trigger('change');
            $('#materiPertemuan').val('');
            $('#catatanPertemuan').val('');
            $('#pertemuanModalTitle').text('Tambah Pertemuan');
        }

        function openAddPertemuan() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            resetPertemuanForm();
            ensurePertemuanModal()?.show();
        }

        function openEditPertemuan(id) {
            const item = pertemuanCollection.find(row => String(row.id) === String(id));
            if (!item) {
                notify('Data pertemuan tidak ditemukan.', 'warning');
                return;
            }

            $('#pertemuanId').val(item.id);
            $('#pertemuanKe').val(item.pertemuan_ke || '');
            $('#judulPertemuan').val(item.judul_pertemuan || '');
            $('#tanggalPertemuan').val(item.tanggal_pertemuan || '');
            $('#statusPertemuan').val(item.status || 'draft');
            $('#materiPertemuan').val(item.materi || '');
            $('#catatanPertemuan').val(item.catatan || '');
            $('#pertemuanModalTitle').text('Edit Pertemuan');
            ensurePertemuanModal()?.show();
        }

        function savePertemuan() {
            if (!selectedClass?.id) {
                notify('Pilih kelas kuliah terlebih dahulu.', 'warning');
                return;
            }

            const payload = {
                pertemuan_ke: $('#pertemuanKe').val(),
                judul_pertemuan: $('#judulPertemuan').val(),
                tanggal_pertemuan: $('#tanggalPertemuan').val(),
                status: $('#statusPertemuan').val(),
                materi: $('#materiPertemuan').val(),
                catatan: $('#catatanPertemuan').val()
            };

            const pertemuanId = $('#pertemuanId').val();
            const isEdit = !!pertemuanId;
            const url = isEdit ?
                routes.updatePertemuanTemplate.replace('__ID__', pertemuanId) :
                routes.storePertemuanTemplate.replace('__KELAS__', selectedClass.id);

            $.ajax({
                url: url,
                method: isEdit ? 'PUT' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: payload,
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menyimpan pertemuan.', 'danger');
                        return;
                    }

                    notify(response.message || 'Pertemuan berhasil disimpan.', 'success');
                    pertemuanModalInstance?.hide();
                    loadPertemuan();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menyimpan pertemuan.', 'danger');
                }
            });
        }

        function renderPresensiTable(data) {
            presensiCollection = normalizeCollection(data);
            $('#presensiModalMeta').text(`Jumlah peserta: ${presensiCollection.length}`);

            if (!presensiCollection.length) {
                $('#presensiTableBody').html(
                    '<tr><td colspan="4" class="text-center text-muted">Belum ada peserta presensi. Gunakan tombol generate peserta.</td></tr>'
                );
                return;
            }

            let html = '';
            presensiCollection.forEach((item, index) => {
                const mahasiswa = item.mahasiswa || item;
                const rowId = item.id_krs_detail || '';
                const status = item.status_kehadiran || item.status || 'hadir';

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="fw-semibold">${escapeHtml(mahasiswa.nama_mahasiswa || '-')}</div>
                            <small class="text-muted">${escapeHtml(mahasiswa.nim || '-')}</small>
                        </td>
                        <td>
                            <select class="form-select form-select-sm presensi-status" data-id="${escapeHtml(rowId)}">
                                <option value="hadir" ${status === 'hadir' ? 'selected' : ''}>Hadir</option>
                                <option value="izin" ${status === 'izin' ? 'selected' : ''}>Izin</option>
                                <option value="sakit" ${status === 'sakit' ? 'selected' : ''}>Sakit</option>
                                <option value="alpa" ${status === 'alpa' ? 'selected' : ''}>Alpa</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm presensi-catatan" data-id="${escapeHtml(rowId)}" value="${escapeHtml(item.catatan || '')}">
                        </td>
                    </tr>
                `;
            });

            $('#presensiTableBody').html(html);
        }

        function openPresensi(pertemuanId) {
            activePertemuanId = pertemuanId;
            $('#presensiModalMeta').text('Memuat data presensi...');
            $('#presensiTableBody').html(
                '<tr><td colspan="4" class="text-center text-muted">Memuat data presensi...</td></tr>');

            ensurePresensiModal()?.show();

            $.get(routes.presensiTemplate.replace('__ID__', pertemuanId))
                .done(function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal memuat presensi.', 'danger');
                        return;
                    }

                    renderPresensiTable(response.data?.presensi || []);
                })
                .fail(function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal memuat presensi.', 'danger');
                    $('#presensiTableBody').html(
                        '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data presensi.</td></tr>');
                });
        }

        function generatePeserta() {
            if (!activePertemuanId) {
                notify('Pilih pertemuan terlebih dahulu.', 'warning');
                return;
            }

            $.ajax({
                url: routes.generatePesertaTemplate.replace('__ID__', activePertemuanId),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal generate peserta presensi.', 'danger');
                        return;
                    }

                    notify(response.message || 'Peserta presensi berhasil digenerate.', 'success');
                    openPresensi(activePertemuanId);
                    loadRekap();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal generate peserta presensi.', 'danger');
                }
            });
        }

        function savePresensi() {
            if (!activePertemuanId) {
                notify('Pilih pertemuan terlebih dahulu.', 'warning');
                return;
            }

            const payload = [];
            $('.presensi-status').each(function() {
                const id = $(this).data('id');
                payload.push({
                    id_krs_detail: id, // ✅ Match API validation
                    status_kehadiran: $(this).val(),
                    catatan: $(`.presensi-catatan[data-id="${id}"]`).val()
                });
            });

            $.ajax({
                url: routes.updatePresensiTemplate.replace('__ID__', activePertemuanId),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    presensi: payload
                }),
                success: function(response) {
                    if (!response.success) {
                        notify(response.message || 'Gagal menyimpan presensi.', 'danger');
                        return;
                    }

                    notify(response.message || 'Presensi berhasil disimpan.', 'success');
                    presensiModalInstance?.hide();
                    loadRekap();
                },
                error: function(xhr) {
                    notify(xhr.responseJSON?.message || 'Gagal menyimpan presensi.', 'danger');
                }
            });
        }

        $('#refreshKelasBtn').on('click', loadKelasKuliah);
        $('#refreshPertemuanBtn').on('click', function() {
            loadPertemuan();
            loadRekap();
        });
        $('#addPertemuanBtn').on('click', openAddPertemuan);
        $('#savePertemuanBtn').on('click', savePertemuan);
        $('#generatePesertaBtn').on('click', generatePeserta);
        $('#savePresensiBtn').on('click', savePresensi);

        // Handle status change to show/hide date requirement
        $('#statusPertemuan').on('change', function() {
            const status = $(this).val();
            const tanggalField = $('#tanggalPertemuan');

            if (status === 'terjadwal' || status === 'selesai' || status === 'dibatalkan') {
                tanggalField.prop('required', true).prop('disabled', false);
            } else {
                tanggalField.prop('required', false).prop('disabled', false);
            }
        });

        $(document).ready(function() {
            loadKelasKuliah();
        });
    </script>
@endpush
