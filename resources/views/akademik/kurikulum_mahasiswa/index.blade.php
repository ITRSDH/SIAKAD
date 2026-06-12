@extends('layouts.index')

@section('title', 'Kurikulum Mahasiswa')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Kurikulum Mahasiswa</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Akademik</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Kurikulum Mahasiswa</a></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-1">Administrasi Kurikulum Mahasiswa</h4>
                        <p class="text-muted mb-0">Halaman ini khusus untuk melihat riwayat dan memproses migrasi kurikulum mahasiswa.</p>
                    </div>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Data Mahasiswa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Filter Program Studi</label>
                        <select id="filterProdi" class="form-select">
                            <option value="">Semua Program Studi</option>
                            @foreach (($prodi ?? []) as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Angkatan</label>
                        <input type="text" id="filterAngkatan" class="form-control" placeholder="Contoh: 2024">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pencarian Cepat</label>
                        <input type="text" id="searchMahasiswa" class="form-control" placeholder="Cari nama atau NIM">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="kurikulumMahasiswaTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Program Studi</th>
                                <th>Angkatan</th>
                                <th>Tahun Kurikulum Aktif</th>
                                <th>Struktur Operasional Aktif</th>
                                <th width="14%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="riwayatKurikulumModal" tabindex="-1" aria-labelledby="riwayatKurikulumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="riwayatKurikulumModalLabel">
                        <i class="fas fa-history me-2"></i>Riwayat Struktur Kurikulum Mahasiswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="riwayatKurikulumContent">
                        <p class="text-muted text-center mb-0">Memuat data riwayat kurikulum...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="migrasiKurikulumModal" tabindex="-1" aria-labelledby="migrasiKurikulumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="migrasiKurikulumForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="migrasiKurikulumModalLabel">
                            <i class="fas fa-exchange-alt me-2"></i>Migrasi Struktur Kurikulum Mahasiswa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="migrasiMahasiswaId">
                        <div class="alert alert-warning" id="migrasiKurikulumAlertBox"></div>
                        <div class="mb-3">
                            <label class="form-label">Mahasiswa</label>
                            <input type="text" id="migrasiMahasiswaLabel" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Kurikulum Saat Ini</label>
                            <input type="text" id="migrasiCurrentKurikulumIndukLabel" class="form-control mb-2" disabled>
                            <label class="form-label">Struktur Operasional Saat Ini</label>
                            <input type="text" id="migrasiCurrentKurikulumLabel" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Kurikulum Tujuan</label>
                            <select id="migrasi_id_kurikulum_induk_tujuan" class="form-control" required>
                                <option value="">-- Pilih Tahun Kurikulum --</option>
                            </select>
                            <small class="text-muted">Pilih tahun kurikulum induk terlebih dahulu.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Struktur Operasional Tujuan</label>
                            <select id="migrasi_id_kurikulum_tujuan" class="form-control" required disabled>
                                <option value="">-- Pilih Struktur Operasional Tujuan --</option>
                            </select>
                            <small class="text-muted">Daftar struktur operasional akan muncul sesuai tahun kurikulum yang dipilih.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai Berlaku</label>
                            <input type="date" id="migrasi_tanggal_mulai" class="form-control">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Catatan Migrasi</label>
                            <textarea id="migrasi_catatan" class="form-control" rows="3" placeholder="Contoh: Migrasi kurikulum karena pembaruan struktur angkatan 2023"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitMigrasiBtn">
                            <i class="fas fa-save me-1"></i>Proses Migrasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            let mahasiswa = @json($mahasiswa ?? []);
            const kurikulumList = @json($kurikulum ?? []);
            const riwayatModal = new bootstrap.Modal('#riwayatKurikulumModal');
            const migrasiModal = new bootstrap.Modal('#migrasiKurikulumModal');

            function formatKurikulumLabel(item) {
                const mulaiBerlaku = item?.mulai_berlaku ?? [
                    item?.semester_mulai?.tahun_akademik?.tahun_akademik,
                    item?.semester_mulai?.nama_semester
                ].filter(Boolean).join(' ');
                const kode = item?.kode_kurikulum ?? '';
                const nama = item?.nama_struktur_mk ?? item?.nama_kurikulum ?? 'Struktur Operasional';
                return [kode, nama, mulaiBerlaku ? `Mulai ${mulaiBerlaku}` : null].filter(Boolean).join(' | ');
            }

            function formatKurikulumIndukLabel(row = {}) {
                const context = row?.kurikulum_context || {};
                const historyItems = row?.riwayat_kurikulum || row?.riwayatKurikulum || [];
                const activeHistory = historyItems.find(item => item?.is_active) || {};
                const induk = context?.kurikulum_induk || activeHistory?.kurikulum_induk || row?.kurikulumInduk || {};

                return [
                    induk?.kode_kurikulum,
                    induk?.nama_kurikulum,
                    induk?.jenis_kurikulum?.kode_jenis
                ].filter(Boolean).join(' | ') || 'Belum ditentukan';
            }

            function getCurrentOperationalKurikulumId(row = {}) {
                const context = row?.kurikulum_context || {};
                const historyItems = row?.riwayat_kurikulum || row?.riwayatKurikulum || [];
                const activeHistory = historyItems.find(item => item?.is_active);
                return context?.id_kurikulum_operasional || context?.id_struktur_operasional || activeHistory?.id_kurikulum || '';
            }

            function getCurrentKurikulumIndukId(row = {}) {
                const context = row?.kurikulum_context || {};
                const historyItems = row?.riwayat_kurikulum || row?.riwayatKurikulum || [];
                const activeHistory = historyItems.find(item => item?.is_active);

                return context?.id_kurikulum_induk || activeHistory?.id_kurikulum_induk || '';
            }

            function hasActiveKurikulumHistory(row = {}) {
                const historyItems = row?.riwayat_kurikulum || row?.riwayatKurikulum || [];
                return historyItems.some(item => item?.is_active) || !!getCurrentOperationalKurikulumId(row);
            }

            function getCurrentOperationalKurikulumLabel(row = {}) {
                const context = row?.kurikulum_context || {};
                const historyItems = row?.riwayat_kurikulum || row?.riwayatKurikulum || [];
                const activeHistory = historyItems.find(item => item?.is_active) || {};
                const operasional = context?.struktur_operasional || activeHistory?.kurikulum_operasional || {};
                const contextLabel = formatKurikulumLabel(operasional);

                if (contextLabel) {
                    return contextLabel;
                }

                return formatKurikulumLabel(kurikulumList.find(item => item.id === getCurrentOperationalKurikulumId(row)) || {}) || 'Belum ditentukan';
            }

            function renderRiwayatKurikulum(items = []) {
                if (!items.length) {
                    return '<div class="text-center text-muted py-4">Belum ada riwayat kurikulum untuk mahasiswa ini.</div>';
                }

                return `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Struktur Kurikulum</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${items.map(item => {
                                    const operasional = item.kurikulum_operasional || item.kurikulum || {};
                                    const induk = item.kurikulum_induk || {};
                                    const periode = [item.tanggal_mulai || '-', item.tanggal_selesai || 'sekarang'].join(' s.d. ');
                                    const badge = item.is_active ? 'success' : 'secondary';
                                    const tahun = operasional.semester_mulai?.tahun_akademik?.tahun_akademik || '';
                                    const semester = operasional.semester_mulai?.nama_semester || '';
                                    const operasionalLabel = [
                                        operasional.kode_kurikulum,
                                        operasional.nama_struktur_mk || operasional.nama_kurikulum,
                                        tahun,
                                        semester
                                    ].filter(Boolean).join(' | ');
                                    const indukLabel = [
                                        induk.kode_kurikulum,
                                        induk.nama_kurikulum,
                                        induk.jenis_kurikulum?.kode_jenis
                                    ].filter(Boolean).join(' | ') || '-';

                                    return `
                                        <tr>
                                            <td>
                                                <div><strong>Induk:</strong> ${indukLabel}</div>
                                                <div><strong>Operasional:</strong> ${operasionalLabel || '-'}</div>
                                            </td>
                                            <td>${periode}</td>
                                            <td><span class="badge bg-${badge}">${item.is_active ? 'Aktif' : 'Riwayat'}</span></td>
                                            <td>${item.catatan || '-'}</td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            function getKurikulumIndukInfo(item = {}) {
                return item?.kurikulum_induk || item?.kurikulumInduk || {};
            }

            function formatKurikulumIndukOptionLabel(induk = {}) {
                return [
                    induk?.tahun_kurikulum,
                    induk?.kode_kurikulum,
                    induk?.nama_kurikulum
                ].filter(Boolean).join(' | ') || 'Tahun Kurikulum';
            }

            function populateKurikulumIndukOptions(prodiId, selectedIndukId = '') {
                const select = $('#migrasi_id_kurikulum_induk_tujuan');
                const grouped = new Map();

                kurikulumList
                    .filter(item => item.id_prodi === prodiId)
                    .forEach(item => {
                        const induk = getKurikulumIndukInfo(item);
                        const indukId = item?.id_kurikulum_induk || induk?.id;

                        if (!indukId || grouped.has(indukId)) {
                            return;
                        }

                        grouped.set(indukId, {
                            id: indukId,
                            label: formatKurikulumIndukOptionLabel(induk),
                        });
                    });

                const options = Array.from(grouped.values())
                    .sort((a, b) => String(a.label).localeCompare(String(b.label), 'id'))
                    .map(item => `<option value="${item.id}" ${item.id === selectedIndukId ? 'selected' : ''}>${item.label}</option>`)
                    .join('');

                select.html('<option value="">-- Pilih Tahun Kurikulum --</option>' + options);
            }

            function populateOperationalKurikulumOptions(prodiId, indukId = '', selectedOperationalId = '') {
                const select = $('#migrasi_id_kurikulum_tujuan');

                if (!indukId) {
                    select.prop('disabled', true).html('<option value="">-- Pilih Struktur Operasional Tujuan --</option>');
                    return;
                }

                const options = kurikulumList
                    .filter(item => item.id_prodi === prodiId)
                    .filter(item => (item?.id_kurikulum_induk || getKurikulumIndukInfo(item)?.id || '') === indukId)
                    .map(item => `<option value="${item.id}" ${item.id === selectedOperationalId ? 'selected' : ''}>${formatKurikulumLabel(item)}</option>`)
                    .join('');

                select.prop('disabled', false).html('<option value="">-- Pilih Struktur Operasional Tujuan --</option>' + options);
            }

            function filteredMahasiswa() {
                const prodiId = $('#filterProdi').val();
                const angkatan = ($('#filterAngkatan').val() || '').trim().toLowerCase();
                const keyword = ($('#searchMahasiswa').val() || '').trim().toLowerCase();

                return mahasiswa.filter(row => {
                    const matchProdi = !prodiId || row.id_prodi === prodiId;
                    const matchAngkatan = !angkatan || String(row.angkatan || '').toLowerCase().includes(angkatan);
                    const text = `${row.nama_mahasiswa || ''} ${row.nim || ''}`.toLowerCase();
                    const matchKeyword = !keyword || text.includes(keyword);
                    return matchProdi && matchAngkatan && matchKeyword;
                });
            }

            const table = $('#kurikulumMahasiswaTable').DataTable({
                data: filteredMahasiswa(),
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    { data: 'nama_mahasiswa' },
                    { data: 'nim' },
                    {
                        data: null,
                        render: row => row.prodi?.nama_prodi || '-'
                    },
                    { data: 'angkatan' },
                    {
                        data: null,
                        render: row => formatKurikulumIndukLabel(row)
                    },
                    {
                        data: null,
                        render: row => getCurrentOperationalKurikulumLabel(row)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: row => {
                            const hasRiwayatAktif = hasActiveKurikulumHistory(row);
                            const actionTitle = hasRiwayatAktif ? 'Migrasi Kurikulum' : 'Set Kurikulum Awal';
                            const actionIcon = hasRiwayatAktif ? 'fa-exchange-alt' : 'fa-diagram-project';
                            const actionClass = hasRiwayatAktif ? 'btn-secondary' : 'btn-primary';

                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-info btn-sm history-btn" data-id="${row.id}" title="Riwayat Kurikulum">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <button class="btn ${actionClass} btn-sm migrate-btn" data-id="${row.id}" title="${actionTitle}">
                                        <i class="fas ${actionIcon}"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                }
            });

            function refreshTable() {
                table.clear().rows.add(filteredMahasiswa()).draw();
            }

            $('#filterProdi, #filterAngkatan, #searchMahasiswa').on('change keyup', refreshTable);

            $(document).on('click', '.history-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('akademik.kurikulum-mahasiswa.riwayat', ':id') }}".replace(':id', id);

                $('#riwayatKurikulumContent').html('<p class="text-muted text-center mb-0">Memuat data riwayat kurikulum...</p>');
                riwayatModal.show();

                $.get(url, res => {
                    const payload = res.data || {};
                    const mahasiswaData = payload.mahasiswa || {};
                    $('#riwayatKurikulumModalLabel').html(`<i class="fas fa-history me-2"></i>Riwayat Struktur Kurikulum - ${mahasiswaData.nama_mahasiswa || 'Mahasiswa'}`);
                    $('#riwayatKurikulumContent').html(renderRiwayatKurikulum(payload.riwayat_kurikulum || []));
                }).fail(xhr => {
                    $('#riwayatKurikulumContent').html(`<div class="alert alert-danger mb-0">${xhr.responseJSON?.message || 'Gagal memuat riwayat kurikulum.'}</div>`);
                });
            });

            $(document).on('click', '.migrate-btn', function() {
                const id = $(this).data('id');
                const row = mahasiswa.find(item => item.id === id);
                const prodiId = row?.id_prodi || '';
                const currentKurikulumId = getCurrentOperationalKurikulumId(row);
                const currentKurikulumIndukId = getCurrentKurikulumIndukId(row);
                const hasRiwayatAktif = hasActiveKurikulumHistory(row);

                $('#migrasiKurikulumForm')[0].reset();
                $('#migrasiMahasiswaId').val(id);
                $('#migrasiKurikulumForm').data('prodi-id', prodiId);
                $('#migrasiMahasiswaLabel').val(`${row?.nama_mahasiswa || '-'} (${row?.nim || '-'})`);
                $('#migrasiKurikulumModalLabel').html(
                    hasRiwayatAktif
                        ? '<i class="fas fa-exchange-alt me-2"></i>Migrasi Struktur Kurikulum Mahasiswa'
                        : '<i class="fas fa-diagram-project me-2"></i>Set Struktur Kurikulum Awal Mahasiswa'
                );
                $('#migrasiKurikulumAlertBox')
                    .toggleClass('alert-warning', hasRiwayatAktif)
                    .toggleClass('alert-info', !hasRiwayatAktif)
                    .text(
                        hasRiwayatAktif
                            ? 'Migrasi kurikulum akan menutup riwayat sebelumnya dan membuka riwayat baru.'
                            : 'Mahasiswa ini belum memiliki riwayat kurikulum aktif. Pilih struktur operasional awalnya.'
                    );
                $('#migrasiCurrentKurikulumIndukLabel').val(hasRiwayatAktif ? formatKurikulumIndukLabel(row) : 'Belum ditentukan');
                $('#migrasiCurrentKurikulumLabel').val(hasRiwayatAktif ? getCurrentOperationalKurikulumLabel(row) : 'Belum ditentukan');
                $('#submitMigrasiBtn').html(
                    hasRiwayatAktif
                        ? '<i class="fas fa-save me-1"></i>Proses Migrasi'
                        : '<i class="fas fa-save me-1"></i>Simpan Kurikulum Awal'
                );
                $('#migrasi_tanggal_mulai').val(new Date().toISOString().split('T')[0]);
                populateKurikulumIndukOptions(prodiId, currentKurikulumIndukId);
                populateOperationalKurikulumOptions(prodiId, currentKurikulumIndukId, currentKurikulumId);
                migrasiModal.show();
            });

            $('#migrasi_id_kurikulum_induk_tujuan').on('change', function() {
                const prodiId = $('#migrasiKurikulumForm').data('prodi-id') || '';
                populateOperationalKurikulumOptions(prodiId, $(this).val(), '');
            });

            $('#migrasiKurikulumForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#migrasiMahasiswaId').val();
                const url = "{{ route('akademik.kurikulum-mahasiswa.migrasi', ':id') }}".replace(':id', id);
                const button = $('#submitMigrasiBtn');
                const originalHtml = button.html();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_kurikulum_tujuan: $('#migrasi_id_kurikulum_tujuan').val(),
                        tanggal_mulai: $('#migrasi_tanggal_mulai').val(),
                        catatan: $('#migrasi_catatan').val()
                    },
                    beforeSend: function() {
                        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Memproses...');
                    },
                    success: function(res) {
                        migrasiModal.hide();
                        Swal.fire('Berhasil', res.message || 'Migrasi kurikulum berhasil diproses.', 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal memproses migrasi kurikulum.', 'error');
                    },
                    complete: function() {
                        button.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>
@endpush
