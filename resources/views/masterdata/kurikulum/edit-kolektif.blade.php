@extends('layouts.index')
@section('title', 'Edit Kolektif Struktur Kurikulum')

@push('styles-custom')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        /* Optimalisasi agar tidak ada scroll horizontal */
        #table-edit-kolektif {
            width: 100% !important;
        }

        #table-edit-kolektif th,
        #table-edit-kolektif td {
            padding: 6px 7px !important;
            vertical-align: middle !important;
            font-size: 0.85rem;
        }

        #table-edit-kolektif thead th {
            font-size: 0.8rem;
            white-space: normal;
        }

        .table-no-scroll {
            overflow-x: hidden !important;
        }

        @media (max-width: 991px) {
            .table-no-scroll {
                overflow-x: auto !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Struktur Kurikulum</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Struktur Kurikulum</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Struktur Kurikulum</a>
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Matakuliah Untuk Struktur MK {{ $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] }}</h4>

                            <div class="d-flex gap-2">
                                <button type="submit" form="form-tambah-mk-kolektif" class="btn btn-primary">
                                    <i class="fas fa-check me-1"></i> Simpan
                                </button>

                                <a href="{{ route('kurikulum.detail', $kurikulum['id']) }}" class="btn btn-success">
                                    <i class="fas fa-sync me-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Nama Struktur MK
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['nama_struktur_mk'] ?? $kurikulum['nama_kurikulum'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah Bobot Mata Kuliah Pilihan
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_pilihan'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Program Studi
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['prodi'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Mulai Berlaku
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['semester_mulai'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah SKS
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_lulus'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="row align-items-center">
                                            <div class="col-6 fw-semibold fs-5">
                                                Jumlah Bobot Mata Kuliah Wajib
                                            </div>
                                            <div class="col-6 fs-5 fw-semibold">
                                                : {{ $kurikulum['jumlah_sks_wajib'] ?? '-' }} SKS
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Toolbar Filter Kode MK & Nama MK -->
                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-md-3">
                                <label for="filterKodeMk" class="form-label fw-semibold">
                                    <i class="fas fa-barcode me-1 text-primary"></i>Filter Kode MK
                                </label>
                                <input type="text" id="filterKodeMk" class="form-control" placeholder="Cari Kode MK...">
                            </div>
                            <div class="col-md-4">
                                <label for="filterNamaMk" class="form-label fw-semibold">
                                    <i class="fas fa-book me-1 text-primary"></i>Filter Nama Mata Kuliah
                                </label>
                                <input type="text" id="filterNamaMk" class="form-control" placeholder="Cari Nama Mata Kuliah...">
                            </div>
                            <div class="col-md-5">
                                <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-primary px-3 py-2 fs-6" id="counterSelectedMk">
                                        0 MK Dipilih
                                    </span>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnPilihSemuaVisible">
                                        <i class="fas fa-check-square me-1"></i> Pilih yang Tampil
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnKosongkanPilihan">
                                        <i class="fas fa-square me-1"></i> Kosongkan
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnResetFilter">
                                        <i class="fas fa-undo me-1"></i> Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-no-scroll">
                            <form id="form-tambah-mk-kolektif"
                                action="{{ route('kurikulum.tambah-mata-kuliah-checkbox', $kurikulum['id']) }}"
                                method="POST">
                                @csrf

                                <table class="table table-bordered table-striped table-hover align-middle" id="table-edit-kolektif" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr class="text-center align-middle">
                                            <th rowspan="2" style="width: 42px;">
                                                <div class="form-check p-0 m-0 text-center">
                                                    <input type="checkbox" id="check-all"
                                                        style="transform: scale(1.6); cursor:pointer;">
                                                </div>
                                            </th>
                                            <th rowspan="2" style="width: 45px;">No</th>
                                            <th rowspan="2" style="width: 105px;">Kode MK</th>
                                            <th rowspan="2">Nama Mata Kuliah</th>
                                            <th colspan="5">Bobot Mata Kuliah (SKS)</th>
                                            <th rowspan="2" style="width: 85px;">Semester</th>
                                            <th rowspan="2" style="width: 60px;">Wajib?</th>
                                        </tr>
                                        <tr class="text-center align-middle">
                                            <th style="width: 75px;">Mata Kuliah</th>
                                            <th style="width: 75px;">Tatap Muka</th>
                                            <th style="width: 75px;">Praktikum</th>
                                            <th style="width: 85px;">Praktek Lapangan</th>
                                            <th style="width: 65px;">Simulasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($matakuliah as $index => $mk)
                                            @php
                                                // Cek apakah matakuliah sudah ada di kurikulum
                                                $mkSudahAda = false;
                                                $dataMk = null;

                                                if (
                                                    isset($mataKuliahDiKurikulum) &&
                                                    is_array($mataKuliahDiKurikulum)
                                                ) {
                                                    foreach ($mataKuliahDiKurikulum as $mk_kurikulum) {
                                                        if ($mk_kurikulum['id'] == $mk['id']) {
                                                            $mkSudahAda = true;
                                                            $dataMk = $mk_kurikulum;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <tr class="{{ $mkSudahAda ? 'table-info row-mk-existing' : '' }}" id="row-mk-{{ $mk['id'] }}">
                                                {{-- Checkbox --}}
                                                <td class="text-center">
                                                    <input type="checkbox"
                                                        class="check-mk {{ $mkSudahAda ? 'check-mk-existing' : '' }}"
                                                        name="selected_mk[]"
                                                        value="{{ $mk['id'] }}"
                                                        data-id-mk="{{ $mk['id'] }}"
                                                        data-nama-mk="{{ $mk['nama_mk'] }}"
                                                        style="transform: scale(1.6); cursor:pointer;"
                                                        {{ $mkSudahAda ? 'checked' : '' }}>
                                                </td>

                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center fw-semibold text-nowrap"><code>{{ $mk['kode_mk'] }}</code></td>
                                                <td>
                                                    <div class="fw-semibold">{{ $mk['nama_mk'] }}</div>
                                                    @if($mkSudahAda)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1 badge-existing-status">
                                                            <i class="fas fa-check me-1"></i>Sudah di Struktur
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center fw-semibold">{{ $mk['sks'] ?? 0 }}</td>
                                                <td class="text-center text-muted">{{ $mk['sks_tatap_muka'] ?? 0 }}</td>
                                                <td class="text-center text-muted">{{ $mk['sks_praktikum'] ?? 0 }}</td>
                                                <td class="text-center text-muted">{{ $mk['sks_praktek_lapangan'] ?? 0 }}</td>
                                                <td class="text-center text-muted">{{ $mk['sks_simulasi'] ?? 0 }}</td>

                                                {{-- Semester (pakai ID sebagai key) --}}
                                                <td class="text-center">
                                                    <select name="semester_ke[{{ $mk['id'] }}]"
                                                        class="form-select form-select-sm">
                                                        @for ($s = 1; $s <= 8; $s++)
                                                            <option value="{{ $s }}"
                                                                {{ $mkSudahAda && ($dataMk['pivot']['semester_ke'] ?? null) == $s ? 'selected' : '' }}>
                                                                Sem. {{ $s }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </td>

                                                {{-- Wajib --}}
                                                <td class="text-center">
                                                    <input type="checkbox" name="is_wajib[{{ $mk['id'] }}]"
                                                        value="1" style="transform: scale(1.4); cursor:pointer;"
                                                        {{ $mkSudahAda && ($dataMk['pivot']['is_wajib'] ?? null) == 1 ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(document).ready(function() {
            const kurikulumId = "{{ $kurikulum['id'] }}";

            // Inisialisasi DataTable (11 kolom: 0..10)
            const table = $('#table-edit-kolektif').DataTable({
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'Semua']
                ],
                order: [[2, 'asc']], // default urutkan berdasarkan Kode MK
                columnDefs: [
                    { orderable: false, targets: [0, 9, 10] }, // Checkbox, Semester, Wajib
                    { searchable: false, targets: [0, 1, 4, 5, 6, 7, 8, 9, 10] }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json') }}'
                },
                drawCallback: function() {
                    updateCheckAllState();
                    updateSelectedCounter();
                }
            });

            // Filter Kode MK
            $('#filterKodeMk').on('keyup change', function() {
                table.column(2).search(this.value.trim()).draw();
            });

            // Filter Nama Mata Kuliah
            $('#filterNamaMk').on('keyup change', function() {
                table.column(3).search(this.value.trim()).draw();
            });

            // Reset Filter
            $('#btnResetFilter').on('click', function() {
                $('#filterKodeMk').val('');
                $('#filterNamaMk').val('');
                table.column(2).search('').column(3).search('').draw();
            });

            // Update counter jumlah MK yang dipilih
            function updateSelectedCounter() {
                const totalSelected = table.$('.check-mk:checked').length;
                const newSelected = table.$('.check-mk:checked:not(.check-mk-existing)').length;
                $('#counterSelectedMk').text(`${totalSelected} MK Terpilih (${newSelected} Baru)`);
            }

            // Update status master checkbox #check-all
            function updateCheckAllState() {
                const $visibleCheckboxes = table.$('.check-mk', { filter: 'applied' });
                if ($visibleCheckboxes.length === 0) {
                    $('#check-all').prop({ checked: false, indeterminate: false });
                    return;
                }
                const checkedCount = $visibleCheckboxes.filter(':checked').length;
                $('#check-all').prop('checked', checkedCount === $visibleCheckboxes.length);
                $('#check-all').prop('indeterminate', checkedCount > 0 && checkedCount < $visibleCheckboxes.length);
            }

            // Uncheck pada MK yang sudah tersimpan di kurikulum (dengan konfirmasi SweetAlert)
            $('#table-edit-kolektif').on('click', '.check-mk-existing', function(e) {
                const $checkbox = $(this);
                const isNowChecked = $checkbox.is(':checked');

                // Jika sedang di-uncheck
                if (!isNowChecked) {
                    e.preventDefault(); // Batalkan uncheck sampai admin konfirmasi

                    const mkId = $checkbox.data('id-mk');
                    const mkNama = $checkbox.data('nama-mk');
                    const deleteUrl = "{{ route('kurikulum.hapus-mata-kuliah', ['id' => $kurikulum['id'], 'id_mk' => ':id_mk']) }}".replace(':id_mk', mkId);

                    Swal.fire({
                        title: 'Hapus dari Struktur Kurikulum?',
                        html: `
                            <div class="text-start">
                                <p class="mb-2">Anda akan menghapus mata kuliah <strong>"${mkNama}"</strong> dari kurikulum ini.</p>
                                <div class="alert alert-danger small mb-2 text-dark">
                                    <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                    <strong>Peringatan Risiko:</strong><br>
                                    Jika mata kuliah ini sudah dibuatkan kelas perkuliahan atau sudah ada mahasiswa yang mengambil KRS, menghapusnya dapat menyebabkan kelas kuliah dan data KRS mahasiswa ikut terhapus!
                                </div>
                                <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus mata kuliah ini dari struktur kurikulum?</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return $.ajax({
                                url: deleteUrl,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Accept': 'application/json'
                                }
                            }).then(response => {
                                return response;
                            }).catch(error => {
                                const msg = error.responseJSON?.message || 'Gagal menghapus mata kuliah dari kurikulum.';
                                Swal.showValidationMessage(msg);
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Dihapus',
                                text: result.value.message || 'Mata kuliah berhasil dihapus dari kurikulum.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Sukses: ubah status baris menjadi bukan existing dan uncheck
                            $checkbox.prop('checked', false);
                            $checkbox.removeClass('check-mk-existing');
                            const $row = $checkbox.closest('tr');
                            $row.removeClass('table-info row-mk-existing');
                            $row.find('.badge-existing-status').remove();

                            updateSelectedCounter();
                            updateCheckAllState();
                        } else {
                            // Batal / gagal: kembalikan checkbox ke checked
                            $checkbox.prop('checked', true);
                        }
                    });
                }
            });

            // Checkbox per baris reguler diubah
            $('#table-edit-kolektif').on('change', '.check-mk:not(.check-mk-existing)', function() {
                updateSelectedCounter();
                updateCheckAllState();
            });

            // Check all checkbox diubah (hanya centang yang belum existing agar tidak memicu hapus massal tidak sengaja)
            $('#check-all').on('change', function() {
                const isChecked = this.checked;
                if (isChecked) {
                    table.$('.check-mk:not(.check-mk-existing)', { filter: 'applied' }).prop('checked', true);
                } else {
                    table.$('.check-mk:not(.check-mk-existing)', { filter: 'applied' }).prop('checked', false);
                }
                updateSelectedCounter();
            });

            // Pilih yang tampil di halaman aktif (hanya MK baru)
            $('#btnPilihSemuaVisible').on('click', function() {
                table.$('.check-mk:not(.check-mk-existing)', { page: 'current' }).prop('checked', true);
                updateSelectedCounter();
                updateCheckAllState();
            });

            // Kosongkan semua pilihan yang belum tersimpan
            $('#btnKosongkanPilihan').on('click', function() {
                table.$('.check-mk:not(.check-mk-existing)').prop('checked', false);
                $('#check-all').prop({ checked: false, indeterminate: false });
                updateSelectedCounter();
            });

            // Form submit validation & DataTables hidden fields injection
            $('#form-tambah-mk-kolektif').on('submit', function(e) {
                // Ambil MK baru yang dicentang
                const $checkedNew = table.$('.check-mk:checked:not(.check-mk-existing)');

                if ($checkedNew.length === 0) {
                    e.preventDefault();
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Pilih minimal 1 mata kuliah baru untuk ditambahkan ke kurikulum!'
                        });
                    } else {
                        alert('Pilih minimal 1 mata kuliah baru untuk ditambahkan ke kurikulum!');
                    }
                    return false;
                }

                // Hapus payload tersembunyi yang dibuat sebelumnya (jika ada)
                $(this).find('.dt-hidden-payload').remove();

                // Pastikan input dari baris baru yang berada di halaman pagination lain ikut terkirim
                $checkedNew.each(function() {
                    const mkId = $(this).val();

                    // Jika elemen tidak berada di dalam dokumen aktif (karena berada di halaman pagination lain)
                    if (!$.contains(document, this)) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_mk[]',
                            value: mkId,
                            class: 'dt-hidden-payload'
                        }).appendTo('#form-tambah-mk-kolektif');

                        const $sem = table.$(`select[name="semester_ke[${mkId}]"]`);
                        if ($sem.length) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: `semester_ke[${mkId}]`,
                                value: $sem.val(),
                                class: 'dt-hidden-payload'
                            }).appendTo('#form-tambah-mk-kolektif');
                        }

                        const $wajib = table.$(`input[name="is_wajib[${mkId}]"]:checked`);
                        if ($wajib.length) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: `is_wajib[${mkId}]`,
                                value: '1',
                                class: 'dt-hidden-payload'
                            }).appendTo('#form-tambah-mk-kolektif');
                        }
                    }
                });

                return true;
            });

            updateSelectedCounter();
        });
    </script>
@endpush
