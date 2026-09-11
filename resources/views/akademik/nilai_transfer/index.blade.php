@extends('layouts.index')
@section('title', 'Konversi Nilai & RPL')

@push('styles-custom')
    <style>
        /* Gaya untuk loader persis seperti kelas kuliah */
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: inherit;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .loader-overlay.hidden {
            display: none;
        }

        .select2-container {
            width: 100% !important;
        }
        .select2-container--open,
        .select2-dropdown {
            z-index: 99999 !important;
        }
        .card-profile {
            border-left: 4px solid #1572E8;
        }
        .stat-box {
            padding: 1rem;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid #ebedf2;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1572E8;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #8d9498;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Konversi Nilai & RPL</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Akademik</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Konversi Nilai</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Pencarian Mahasiswa</h4>
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-light border">
                                <i class="fas fa-users me-1"></i> Data Mahasiswa
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0 fw-bold">Cari Mahasiswa</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="checkIncludeReguler">
                                        <label class="form-check-label text-muted small" for="checkIncludeReguler">
                                            Tampilkan Mahasiswa Reguler
                                        </label>
                                    </div>
                                </div>
                                <select class="form-select select2" id="mahasiswaSelect">
                                    <option value="">-- Pilih Mahasiswa --</option>
                                </select>
                                <small class="text-muted mt-2 d-block" id="filterStatusHint">
                                    Menampilkan mahasiswa jalur RPL dan Pindahan.
                                </small>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" id="btnLoadMahasiswa">
                                    <i class="fas fa-search me-1"></i> Tampilkan Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Workspace Konversi --}}
        <div id="transferWorkspace" class="d-none">
            {{-- Profil Mahasiswa --}}
            <div class="card card-profile mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg">
                                <span class="avatar-title rounded-circle border border-white bg-primary">
                                    <i class="fas fa-user-graduate"></i>
                                </span>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1" id="bannerNamaMahasiswa">-</h4>
                                <div class="d-flex flex-wrap gap-3 text-muted">
                                    <span><i class="fas fa-id-badge me-1"></i> <span id="bannerNim">-</span></span>
                                    <span><i class="fas fa-graduation-cap me-1"></i> <span id="bannerProdi">-</span></span>
                                    <span><i class="fas fa-university me-1"></i> <span id="bannerKampusAsal">-</span></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark fw-bold" id="bannerJenisPendaftaran">RPL</span>
                            <span class="badge bg-light text-dark border ms-1" id="bannerAngkatanPill">Angkatan <span id="bannerAngkatan">-</span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="row mb-4">
                <div class="col-sm-6 col-md-3">
                    <div class="stat-box">
                        <div class="stat-label">Total SKS Diakui</div>
                        <div class="stat-value" id="summaryTotalSks">0</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="stat-box">
                        <div class="stat-label">Mata Kuliah Diakui</div>
                        <div class="stat-value text-success" id="summaryTotalMk">0</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="stat-box">
                        <div class="stat-label">Rata-rata Nilai</div>
                        <div class="stat-value text-info" id="summaryRataIndeks">0.00</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="stat-box">
                        <div class="stat-label">Ekuivalen Semester</div>
                        <div class="stat-value text-warning" id="summaryEkuivalenSemester">-</div>
                    </div>
                </div>
            </div>

            {{-- Tabel Konversi (Selaras dengan format masterdata/kelaskuliah) --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Daftar Mata Kuliah Konversi</h4>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-primary" id="btnAddTransfer">
                                        <i class="fas fa-plus me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="tableLoader" class="loader-overlay hidden">
                                <div class="loader-spinner"></div>
                            </div>
                            <div class="table-responsive">
                                <table id="transferTable"
                                    class="table table-bordered table-striped table-hover text-center" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="32%" class="text-center">Mata Kuliah Asal</th>
                                            <th width="33%" class="text-center">Penyetaraan (Kurikulum STIKES)</th>
                                            <th width="10%" class="text-center">SKS Diakui</th>
                                            <th width="10%" class="text-center">Nilai Diakui</th>
                                            <th width="10%" class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- DataTables isi disini -->
                                    </tbody>
                                    <tfoot id="transferTableFoot" class="table-light d-none">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total Rekapitulasi:</td>
                                            <td class="text-center fw-bold text-primary" id="footTotalSks">0 SKS</td>
                                            <td class="text-center fw-bold" id="footRataNilai">-</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Empty State --}}
        <div id="transferEmptyPrompt" class="card my-4">
            <div class="card-body text-center py-5">
                <div class="display-4 text-muted mb-3"><i class="fas fa-file-invoice"></i></div>
                <h4 class="fw-bold">Belum Ada Mahasiswa Dipilih</h4>
                <p class="text-muted">Silakan cari dan pilih mahasiswa pada form di atas untuk mengelola data konversi nilai.</p>
            </div>
        </div>
    </div>

    {{-- Modal Tambah / Edit Konversi --}}
    <div class="modal fade" id="modalTransfer" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTransferTitle">Tambah Konversi Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTransfer">
                    <input type="hidden" id="editTransferId" name="id">
                    <input type="hidden" id="inputMahasiswaId" name="id_mahasiswa">
                    
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Pastikan data mata kuliah asal dan penyetaraannya sudah sesuai dengan transkrip resmi mahasiswa.
                        </div>
                        <div class="row g-3">
                            {{-- Seksi Asal --}}
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-2 border-bottom pb-2">Mata Kuliah Asal (Kampus Asal)</h6>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_mata_kuliah_asal" name="nama_mata_kuliah_asal" placeholder="Misal: Anatomi Dasar" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kode MK</label>
                                <input type="text" class="form-control" id="kode_mata_kuliah_asal" name="kode_mata_kuliah_asal" placeholder="Opsional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKS Asal</label>
                                <input type="number" step="0.5" class="form-control" id="sks_asal" name="sks_asal" placeholder="Misal: 3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nilai Asal</label>
                                <input type="text" class="form-control text-uppercase" id="nilai_huruf_asal" name="nilai_huruf_asal" placeholder="A/B+">
                            </div>

                            {{-- Seksi STIKES --}}
                            <div class="col-md-12 mt-4">
                                <h6 class="fw-bold mb-2 border-bottom pb-2">Penyetaraan (Kurikulum STIKES)</h6>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mata Kuliah Tujuan <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_mata_kuliah" name="id_mata_kuliah" required>
                                    <option value="">-- Pilih Mata Kuliah --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKS Diakui <span class="text-danger">*</span></label>
                                <input type="number" step="0.5" class="form-control" id="sks_diakui" name="sks_diakui" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nilai Diakui <span class="text-danger">*</span></label>
                                <select class="form-select" id="nilai_huruf_diakui" name="nilai_huruf_diakui" required>
                                    <option value="A">A (4.00)</option>
                                    <option value="AB">AB / B+ (3.50)</option>
                                    <option value="B">B (3.00)</option>
                                    <option value="BC">BC / C+ (2.50)</option>
                                    <option value="C">C (2.00)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Indeks Mutu</label>
                                <input type="text" class="form-control bg-light" id="nilai_indeks_diakui" name="nilai_indeks_diakui" value="4.00" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Keterangan / Nomor SK</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Opsional">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSaveTransfer">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const initialSelectedMahasiswaId = @json($selectedMahasiswaId);
        const routes = {
            data: "{{ route('akademik.nilai-transfer.data') }}",
            store: "{{ route('akademik.nilai-transfer.store') }}",
            summaryTemplate: "{{ route('akademik.nilai-transfer.summary', ['mahasiswaId' => '__ID__']) }}",
            updateTemplate: "{{ route('akademik.nilai-transfer.update', ['id' => '__ID__']) }}",
            destroyTemplate: "{{ route('akademik.nilai-transfer.destroy', ['id' => '__ID__']) }}",
            mataKuliahOptions: "{{ route('akademik.nilai-transfer.mata-kuliah-options') }}",
            mahasiswaOptions: "{{ route('akademik.nilai-transfer.mahasiswa-options') }}",
        };

        let activeMahasiswaId = initialSelectedMahasiswaId || null;
        let activeMahasiswaData = null;
        let mahasiswaMap = {};
        let cachedMataKuliahList = [];
        let modalTransferInstance = null;
        let transferDataTable = null;

        function extractArray(source) {
            if (!source) return [];
            if (Array.isArray(source)) return source;
            if (Array.isArray(source.data)) return source.data;
            if (source.data && Array.isArray(source.data.data)) return source.data.data;
            if (source.data && Array.isArray(source.data.mahasiswa)) return source.data.mahasiswa;
            if (source.data && Array.isArray(source.data.mata_kuliah)) return source.data.mata_kuliah;
            return [];
        }

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function notify(message, type = 'info') {
            const swalType = {
                success: 'success',
                danger: 'error',
                warning: 'warning',
                info: 'info'
            }[type] || 'info';

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: swalType,
                title: message,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }

        function initMataKuliahSelect2() {
            const $mkSelect = $('#id_mata_kuliah');
            if ($mkSelect.hasClass('select2-hidden-accessible')) {
                $mkSelect.select2('destroy');
            }
            $mkSelect.siblings('.select2-container').remove();
            $mkSelect.select2({
                dropdownParent: $('#modalTransfer'),
                width: '100%',
                placeholder: '-- Pilih Mata Kuliah --',
                allowClear: true
            });
        }

        $('#nilai_huruf_diakui').on('change', function() {
            const grade = $(this).val();
            const map = {
                'A': 4.00, 'AB': 3.50, 'B': 3.00, 'BC': 2.50, 'C': 2.00, 'D': 1.00, 'E': 0.00
            };
            if (map[grade] !== undefined) {
                $('#nilai_indeks_diakui').val(map[grade].toFixed(2));
            }
        });

        function initMahasiswaSelect(includeReguler = false) {
            const currentVal = $('#mahasiswaSelect').val() || activeMahasiswaId;
            
            $.get(routes.mahasiswaOptions, { include_reguler: includeReguler ? 1 : 0 })
                .done(function(res) {
                    const items = extractArray(res);
                    mahasiswaMap = {};
                    const placeholderText = '-- Pilih Mahasiswa --';

                    let options = `<option value="">${placeholderText}</option>`;
                    items.forEach(m => {
                        mahasiswaMap[m.id] = m;
                        const isRpl = ['RPL', 'Pindahan'].includes(m.jenis_pendaftaran)
                            || (m.nim && m.nim.toUpperCase().endsWith('B'))
                            || (m.jalur_masuk && m.jalur_masuk.toUpperCase() === 'RPL');
                        const rplBadge = isRpl ? ` [RPL]` : ' [Reguler]';
                        const prodiName = m.prodi?.nama_prodi ? ` - ${m.prodi.nama_prodi}` : '';
                        const isSelected = (currentVal && currentVal === m.id) ? 'selected' : '';
                        options += `<option value="${m.id}" ${isSelected}>${escapeHtml(m.nim)} - ${escapeHtml(m.nama_mahasiswa)}${rplBadge}${escapeHtml(prodiName)}</option>`;
                    });
                    $('#mahasiswaSelect').html(options);

                    if ($.fn.select2) {
                        $('#mahasiswaSelect').select2({
                            placeholder: placeholderText,
                            allowClear: true,
                            width: '100%'
                        });
                    }

                    if (currentVal && mahasiswaMap[currentVal]) {
                        $('#mahasiswaSelect').val(currentVal).trigger('change.select2');
                        if (!activeMahasiswaData) {
                            loadMahasiswaWorkspace(currentVal);
                        }
                    }
                })
                .fail(function(xhr) {
                    notify('Gagal memuat daftar mahasiswa', 'danger');
                });
        }

        $('#checkIncludeReguler').on('change', function() {
            const isChecked = $(this).is(':checked');
            if (isChecked) {
                $('#filterStatusHint').text('Menampilkan seluruh mahasiswa (RPL, Pindahan, dan Reguler).');
            } else {
                $('#filterStatusHint').text('Menampilkan mahasiswa jalur RPL dan Pindahan.');
            }
            initMahasiswaSelect(isChecked);
        });

        function populateMataKuliahDropdown(prodiId, selectedId = null) {
            const $mkSelect = $('#id_mata_kuliah');
            $mkSelect.html('<option value="">Memuat...</option>').prop('disabled', true);

            $.get(routes.mataKuliahOptions, { id_prodi: prodiId })
                .done(function(res) {
                    cachedMataKuliahList = extractArray(res);
                    let options = '<option value="">-- Pilih Mata Kuliah --</option>';
                    cachedMataKuliahList.forEach(mk => {
                        const kodeMk = mk.kode_mata_kuliah || mk.kode_mk || '-';
                        const namaMk = mk.nama_mata_kuliah || mk.nama_mk || '-';
                        const sks = mk.sks ?? 0;
                        const smt = mk.semester_paket ? `Smt ${mk.semester_paket}` : (mk.semester ? `Smt ${mk.semester}` : '');
                        const smtLabel = smt ? ` - ${smt}` : '';
                        const label = `[${kodeMk}] ${namaMk} (${sks} SKS)${smtLabel}`;
                        options += `<option value="${mk.id}" data-sks="${sks}">${escapeHtml(label)}</option>`;
                    });
                    $mkSelect.html(options).prop('disabled', false);

                    if (selectedId) {
                        $mkSelect.val(selectedId);
                    }
                    initMataKuliahSelect2();
                })
                .fail(function(xhr) {
                    $mkSelect.html('<option value="">Gagal memuat mata kuliah</option>');
                    notify('Gagal memuat mata kuliah kurikulum', 'danger');
                });
        }

        $('#id_mata_kuliah').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.length && selectedOption.val()) {
                const defaultSks = selectedOption.data('sks');
                if (defaultSks && !$('#sks_diakui').val()) {
                    $('#sks_diakui').val(defaultSks);
                }
            }
        });

        function loadMahasiswaWorkspace(mahasiswaId) {
            if (!mahasiswaId) return;
            
            const student = mahasiswaMap[mahasiswaId];
            if (!student) {
                notify('Data mahasiswa tidak ditemukan di lokal, memuat ulang...', 'warning');
                return;
            }

            activeMahasiswaId = mahasiswaId;
            activeMahasiswaData = student;

            $('#bannerNamaMahasiswa').text(student.nama_mahasiswa || '-');
            $('#bannerNim').text(student.nim || '-');
            $('#bannerProdi').text(student.prodi?.nama_prodi || '-');
            $('#bannerAngkatan').text(student.angkatan || '-');
            $('#bannerKampusAsal').text(student.asal_kampus || student.sekolah_asal || 'Tidak ada data kampus asal');
            
            let badgeText = 'REGULER';
            let badgeClass = 'bg-secondary';
            if (student.jenis_pendaftaran === 'RPL') {
                badgeText = 'RPL';
                badgeClass = 'bg-warning';
            } else if (student.jenis_pendaftaran === 'Pindahan') {
                badgeText = 'PINDAHAN';
                badgeClass = 'bg-info';
            }
            $('#bannerJenisPendaftaran').text(badgeText).removeClass('bg-warning bg-info bg-secondary').addClass(badgeClass);

            $('#transferEmptyPrompt').addClass('d-none');
            $('#transferWorkspace').removeClass('d-none');

            populateMataKuliahDropdown(student.id_prodi);
            refreshTransferData();
        }

        $('#btnLoadMahasiswa').on('click', function() {
            const selectedId = $('#mahasiswaSelect').val();
            if (!selectedId) {
                notify('Pilih mahasiswa terlebih dahulu', 'warning');
                return;
            }
            loadMahasiswaWorkspace(selectedId);
        });

        function refreshTransferData() {
            if (!activeMahasiswaId) return;

            $('#tableLoader').removeClass('hidden');

            $.get(routes.summaryTemplate.replace('__ID__', activeMahasiswaId))
                .done(function(res) {
                    const data = res.data || {};
                    
                    $('#summaryTotalSks').text(data.total_sks_diakui || '0');
                    $('#summaryTotalMk').text(data.total_mata_kuliah || '0');
                    $('#summaryRataIndeks').text(data.rata_rata_indeks || '0.00');
                    $('#summaryEkuivalenSemester').text(data.ekuivalen_semester_text || 'Semester 1');

                    $('#footTotalSks').text((data.total_sks_diakui || '0') + ' SKS');
                    $('#footRataNilai').text(data.rata_rata_indeks || '0.00');
                    $('#transferTableFoot').removeClass('d-none');
                });

            $.get(routes.data, { id_mahasiswa: activeMahasiswaId, per_page: 500 })
                .done(function(res) {
                    const list = extractArray(res);
                    renderDataTable(list);
                })
                .fail(function(xhr) {
                    $('#tableLoader').addClass('hidden');
                    let message = 'Data konversi nilai belum tersedia.';
                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'info',
                        title: 'Informasi',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                });
        }

        function renderDataTable(list) {
            window.currentTransferData = list;

            if ($.fn.DataTable.isDataTable('#transferTable')) {
                transferDataTable.clear().rows.add(list).draw();
                return;
            }

            transferDataTable = $('#transferTable').DataTable({
                data: list,
                columns: [
                    {
                        data: null,
                        className: 'text-center align-middle',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mata_kuliah_asal',
                        className: 'text-start align-middle',
                        render: function(data, type, row) {
                            const nama = escapeHtml(row.nama_mata_kuliah_asal || '-');
                            const kode = row.kode_mata_kuliah_asal ? ` <small class="text-muted">(${escapeHtml(row.kode_mata_kuliah_asal)})</small>` : '';
                            const sksNilaiAsal = (row.sks_asal || row.nilai_huruf_asal) 
                                ? `<div class="small text-muted mt-1">Asal: ${row.sks_asal ? row.sks_asal + ' SKS' : ''} ${row.nilai_huruf_asal ? '• Nilai: ' + row.nilai_huruf_asal : ''}</div>` 
                                : '';
                            const ket = row.keterangan ? `<div class="small text-muted"><i class="fas fa-info-circle me-1"></i>${escapeHtml(row.keterangan)}</div>` : '';
                            return `<div class="fw-semibold text-dark">${nama}${kode}</div>${sksNilaiAsal}${ket}`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-start align-middle',
                        render: function(data, type, row) {
                            const mkObj = row.mata_kuliah || row.mataKuliah;
                            if (!mkObj) {
                                return '<span class="text-danger small">Mata kuliah tidak ditemukan / terhapus</span>';
                            }
                            const kode = mkObj.kode_mata_kuliah || mkObj.kode_mk || '-';
                            const nama = mkObj.nama_mata_kuliah || mkObj.nama_mk || '-';
                            return `<span class="badge bg-light text-dark border mb-1">${escapeHtml(kode)}</span><div class="fw-semibold text-dark">${escapeHtml(nama)}</div>`;
                        }
                    },
                    {
                        data: 'sks_diakui',
                        className: 'text-center align-middle fw-bold',
                        render: function(data) {
                            return (data !== null && data !== undefined) ? data : 0;
                        }
                    },
                    {
                        data: 'nilai_huruf_diakui',
                        className: 'text-center align-middle',
                        render: function(data, type, row) {
                            const indeks = (row.nilai_indeks_diakui !== null && row.nilai_indeks_diakui !== undefined) 
                                ? ` <small class="text-muted">(${parseFloat(row.nilai_indeks_diakui).toFixed(2)})</small>` 
                                : '';
                            return `<span class="badge bg-success">${escapeHtml(data || '-')}</span>${indeks}`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center align-middle',
                        render: function(data, type, row) {
                            const nama = escapeHtml(row.nama_mata_kuliah_asal || '');
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-warning btn-sm text-white edit-btn" data-id="${row.id}" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" data-nama="${nama}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json',
                    emptyTable: 'Belum ada data konversi nilai.',
                    zeroRecords: 'Data konversi nilai tidak ditemukan.'
                },
                drawCallback: function(settings) {
                    $('#tableLoader').addClass('hidden');
                }
            });
        }

        $('#btnAddTransfer').on('click', function() {
            if (!activeMahasiswaId) return;
            
            $('#formTransfer')[0].reset();
            $('#editTransferId').val('');
            $('#inputMahasiswaId').val(activeMahasiswaId);
            $('#modalTransferTitle').text('Tambah Konversi Mata Kuliah');
            
            $('#id_mata_kuliah').val('').trigger('change.select2');
            $('#nilai_huruf_diakui').val('A').trigger('change');
            
            if (!modalTransferInstance) {
                modalTransferInstance = new bootstrap.Modal(document.getElementById('modalTransfer'));
            }
            modalTransferInstance.show();
            setTimeout(initMataKuliahSelect2, 200);
        });

        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const list = Array.isArray(window.currentTransferData) ? window.currentTransferData : [];
            const item = list.find(x => x.id === id);
            
            if (!item) return;

            $('#formTransfer')[0].reset();
            $('#editTransferId').val(item.id);
            $('#inputMahasiswaId').val(item.id_mahasiswa);
            $('#modalTransferTitle').text('Edit Konversi Mata Kuliah');
            
            $('#nama_mata_kuliah_asal').val(item.nama_mata_kuliah_asal);
            $('#kode_mata_kuliah_asal').val(item.kode_mata_kuliah_asal);
            $('#sks_asal').val(item.sks_asal);
            $('#nilai_huruf_asal').val(item.nilai_huruf_asal);
            
            $('#sks_diakui').val(item.sks_diakui);
            $('#nilai_huruf_diakui').val(item.nilai_huruf_diakui).trigger('change');
            $('#keterangan').val(item.keterangan);

            const mkObj = item.mata_kuliah || item.mataKuliah;
            if ($('#id_mata_kuliah option[value="'+item.id_mata_kuliah+'"]').length === 0 && mkObj) {
                const kodeMk = mkObj.kode_mata_kuliah || mkObj.kode_mk || '';
                const namaMk = mkObj.nama_mata_kuliah || mkObj.nama_mk || '';
                const opt = new Option(`[${kodeMk}] ${namaMk}`, item.id_mata_kuliah, true, true);
                $('#id_mata_kuliah').append(opt).trigger('change');
            } else {
                $('#id_mata_kuliah').val(item.id_mata_kuliah).trigger('change.select2');
            }

            if (!modalTransferInstance) {
                modalTransferInstance = new bootstrap.Modal(document.getElementById('modalTransfer'));
            }
            modalTransferInstance.show();
            setTimeout(initMataKuliahSelect2, 200);
        });

        // Event handler untuk tombol delete persis seperti masterdata/kelaskuliah
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama') || 'data ini';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus Konversi Nilai "${nama}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routes.destroyTemplate.replace('__ID__', id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message || 'Data konversi nilai berhasil dihapus.',
                                    confirmButtonText: 'OK'
                                });
                                refreshTransferData();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        $('#formTransfer').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#btnSaveTransfer');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
            
            const formData = $(this).serialize() + '&_token={{ csrf_token() }}';
            const editId = $('#editTransferId').val();
            
            const url = editId ? routes.updateTemplate.replace('__ID__', editId) : routes.store;
            const method = editId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(res) {
                    modalTransferInstance.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message || 'Data konversi berhasil disimpan!',
                        confirmButtonText: 'OK'
                    });
                    refreshTransferData();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: msg,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).html('Simpan Data');
                }
            });
        });

        $(document).ready(function() {
            initMahasiswaSelect();
        });
    </script>
@endpush
