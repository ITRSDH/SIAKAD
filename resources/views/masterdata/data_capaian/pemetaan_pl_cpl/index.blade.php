<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Pemetaan PL → CPL</h4>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-sm btn-primary" id="simpanBtn">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="batalBtn">
                    <i class="fas fa-times"></i> Batalkan
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">

                    <!-- Keterangan -->
                    <div class="col-md-8">
                        <label class="fw-bold mb-2 d-block">Pilih Metode Pembobotan</label>
                        <ul class="mb-0 ps-3 text-muted small">
                            <li><strong>Manual:</strong> Tentukan bobot secara mandiri pada kolom yang tersedia.</li>
                            <li><strong>Otomatis (Rata):</strong> Cukup centang, bobot akan dibagi rata otomatis ke
                                setiap CPL.</li>
                        </ul>
                    </div>

                    <!-- Dropdown -->
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <select class="form-select form-select-sm w-auto d-inline-block" id="metodePembobotan">
                            <option value="manual">Isi Manual</option>
                            <option value="otomatis">Otomatis (Rata)</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="pemetaanTable">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center align-middle">Kode PL</th>
                        <th rowspan="2" class="text-center align-middle">Profil Lulusan</th>
                        <th colspan="{{ $cplCount ?? 5 }}" class="text-center">Pemetaan PL → CPL</th>
                        <th rowspan="2" class="text-center align-middle">Total</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {

        const prodiId = "{{ $id_prodi }}";

        init();

        function init() {
            bindStaticEvents();
            loadData();
        }

        // ==============================
        // LOAD DATA
        // ==============================
        function loadData() {
            $.get(`{{ route('capaian.pl-cpl.data', $id_prodi) }}`)
                .done(res => renderTable(res.data))
                .fail(() => Swal.fire('Error', 'Gagal memuat data', 'error'));
        }

        // ==============================
        // RENDER TABLE
        // ==============================
        function renderTable(data) {

            if (!data || !data.pl || !data.cpl ||
                !Array.isArray(data.pl) || !Array.isArray(data.cpl) ||
                data.pl.length === 0 || data.cpl.length === 0) {
                $('#tableBody').html(
                    `<tr><td colspan="100%" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Data tidak tersedia
                    </td></tr>`);
                return;
            }

            const metode = $('#metodePembobotan').val();
            const {
                pl,
                cpl,
                mapping
            } = data;

            renderHeader(cpl, metode);

            let html = '';

            pl.forEach(plItem => {

                html += `<tr>
                <td class="text-center">${plItem.kode_pl ?? ''}</td>
                <td>${plItem.profile_lulusan ?? ''}</td>`;

                cpl.forEach(cplItem => {

                    const value = mapping?.[plItem.id]?.[cplItem.id] ?? 0;

                    if (metode === 'otomatis') {
                        html += `
                        <td class="text-center">
                            <input type="checkbox" class="mapping-checkbox"
                                data-pl="${plItem.id}" data-cpl="${cplItem.id}"
                                ${value > 0 ? 'checked' : ''}>
                        </td>`;
                    } else {
                        html += `
                        <td>
                            <input type="number" class="form-control text-center mapping-input"
                                data-pl="${plItem.id}" data-cpl="${cplItem.id}"
                                value="${value}" step="0.01" min="0" max="1">
                        </td>`;
                    }
                });

                if (metode === 'manual') {
                    const total = Object.values(mapping?.[plItem.id] ?? {})
                        .reduce((sum, v) => sum + parseFloat(v || 0), 0);

                    html += `<td class="text-center total-cell fw-bold">${total.toFixed(2)}</td>`;
                }

                html += `</tr>`;
            });

            $('#tableBody').html(html);
        }

        // ==============================
        // HEADER
        // ==============================
        function renderHeader(cpl, metode) {

            let html = `
        <tr>
            <th rowspan="2" class="text-center" style="width: 5%;">Kode PL</th>
            <th rowspan="2" class="text-center" style="width: 20%;">Profil Lulusan</th>
            <th colspan="${cpl.length}" class="text-center" style="width: 30%;">Pemetaan PL → CPL</th>
            ${metode === 'manual' ? '<th rowspan="2" class="text-center" style="width: 5%;">Total</th>' : ''}
        </tr>
        <tr>`;

            cpl.forEach(c => {
                html += `<th class="text-center">${c.kode_cpl.toUpperCase()}</th>`;
            });

            html += '</tr>';

            $('#pemetaanTable thead').html(html);
        }

        // ==============================
        // EVENT STATIC (ONCE)
        // ==============================
        function bindStaticEvents() {

            $('#metodePembobotan').on('change', loadData);

            $('#simpanBtn').on('click', saveData);

            $('#batalBtn').on('click', loadData);

            // delegation (dynamic elements)
            $('#pemetaanTable').on('input', '.mapping-input', updateTotal);
            $('#pemetaanTable').on('change', '.mapping-checkbox', updateTotal);
        }

        // ==============================
        // UPDATE TOTAL
        // ==============================
        function updateTotal() {
            let row = $(this).closest('tr');

            let total = 0;

            row.find('.mapping-input').each(function() {
                total += parseFloat($(this).val() || 0);
            });

            row.find('.total-cell').text(total.toFixed(2));
        }

        // ==============================
        // SAVE DATA
        // ==============================
        function saveData() {

            const metode = $('#metodePembobotan').val();
            let mapping = {};
            let isValid = true;
            let errorMsg = '';

            $('#pemetaanTable tbody tr').each(function() {

                let row = $(this);
                let plId = row.find('[data-pl]').first().data('pl');

                if (!plId) return;

                mapping[plId] = {};

                if (metode === 'manual') {

                    let total = 0;

                    row.find('.mapping-input').each(function() {
                        let cplId = $(this).data('cpl');
                        let val = parseFloat($(this).val() || 0);

                        mapping[plId][cplId] = val;
                        total += val;
                    });

                    let totalRounded = Math.round(total * 100) / 100;

                    if (totalRounded !== 0 && totalRounded !== 100) {
                        isValid = false;
                        errorMsg = `Total PL harus 100% atau 0% (PL ID: ${plId})`;
                        return false;
                    }

                } else {

                    row.find('.mapping-checkbox').each(function() {
                        let cplId = $(this).data('cpl');
                        mapping[plId][cplId] = $(this).is(':checked') ? 1 : 0;
                    });
                }
            });

            if (!isValid) {
                Swal.fire('Validasi Gagal', errorMsg, 'warning');
                return;
            }

            $('#simpanBtn').prop('disabled', true);

            $.ajax({
                url: `{{ route('capaian.pl-cpl.store') }}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    mapping,
                    mode: metode
                },

                success: res => {
                    Swal.fire('Berhasil', res.message, 'success');
                    loadData();
                },

                error: xhr => {
                    if (xhr.status === 422) return; // ❌ no spam

                    Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menyimpan', 'error');
                },

                complete: () => {
                    $('#simpanBtn').prop('disabled', false);
                }
            });
        }

    });
</script>
