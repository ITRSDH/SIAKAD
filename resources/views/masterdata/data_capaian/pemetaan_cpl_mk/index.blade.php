<!-- =========================
STYLE
========================= -->
<style>
    .table-mapping td {
        vertical-align: middle;
    }

    .table-mapping tbody tr:hover {
        background-color: #f1f5ff;
    }

    .table-mapping input[type="checkbox"] {
        transform: scale(1.2);
    }
</style>

<!-- =========================
JUDUL
========================= -->
<h5 class="mb-3 text-primary">
    <b>Pemetaan CPL → MK</b>
</h5>

<!-- =========================
FILTER
========================= -->
<div class="card mb-3">
    <div class="card-body">
        <label><b>Pilih Level Pemetaan</b></label>
        <ul class="mb-2">
            <li>CPL: sampai level CPL saja</li>
            <li>IK: sampai level Indikator Kinerja</li>
        </ul>

        <select id="levelPemetaan" class="form-select w-auto">
            <option value="cpl">CPL</option>
            <option value="cpl_ik">IK</option>
        </select>
    </div>
</div>

<!-- =========================
TABLE
========================= -->
<div class="table-responsive">
    <table class="table table-bordered text-center table-mapping" id="tableMapping">
        <thead id="theadMapping"></thead>
        <tbody id="tbodyMapping"></tbody>
    </table>
</div>

<!-- =========================
SCRIPT
========================= -->
<script>
    var idProdi = "{{ $id_prodi }}";
    var currentData = {};

    $(document).ready(function() {
        loadData();

        $('#levelPemetaan').change(function() {
            loadData();
        });
    });

    function loadData() {
        let level = $('#levelPemetaan').val();

        $.get(`/capaian/cpl-mk/data/${idProdi}`, {
            level_pemetaan: level
        }, function(res) {
            currentData = res.data;
            renderTable();
        }).fail(function() {
            alert('Gagal mengambil data');
        });
    }

    function renderTable() {
        var {
            cpl = [],
                mata_kuliah = [],
                mapping = {},
                indikator_kinerja_mapping = {}
        } = currentData;

        var thead = '';
        var tbody = '';

        // =========================
        // HEADER
        // =========================

        if (currentData.level_pemetaan === 'cpl') {

            thead += `
            <tr>
                <th rowspan="2">Kode MK</th>
                <th rowspan="2">Mata Kuliah</th>
                <th colspan="${cpl.length}">Pemetaan CPL → MK</th>
            </tr>
            <tr>
                ${cpl.map(c => `<th>${c.kode_cpl}</th>`).join('')}
            </tr>`;

        } else {

            let totalKolom = cpl.reduce((sum, c) => {
                return sum + (c.indikator_kinerja?.length || 1);
            }, 0);

            // ROW 1
            thead += `
            <tr>
                <th rowspan="3">Kode MK</th>
                <th rowspan="3">Mata Kuliah</th>
                <th colspan="${totalKolom}">Pemetaan CPL → MK</th>
            </tr>`;

            // ROW 2 (CPL)
            thead += `<tr>`;
            cpl.forEach(c => {
                let jumlahIK = c.indikator_kinerja?.length || 0;

                if (jumlahIK > 0) {
                    // kalau ada IK → normal colspan
                    thead += `<th colspan="${jumlahIK}">${c.kode_cpl}</th>`;
                } else {
                    // kalau tidak ada IK → merge ke bawah
                    thead += `<th rowspan="2">${c.kode_cpl}</th>`;
                }
            });
            thead += `</tr>`;

            // ROW 3 (IK)
            thead += `<tr>`;
            cpl.forEach(c => {
                if (c.indikator_kinerja && c.indikator_kinerja.length > 0) {
                    c.indikator_kinerja.forEach(ik => {
                        thead += `<th>${ik.kode_ik_cpl}</th>`;
                    });
                }
                // ❌ HAPUS else (tidak perlu th kosong)
            });
            thead += `</tr>`;
        }

        $('#theadMapping').html(thead);

        // =========================
        // BODY
        // =========================

        mata_kuliah.forEach(mk => {

            tbody += `
            <tr>
                <td class="text-start">${mk.kode_mk}</td>
                <td class="text-start">${mk.nama_mk}</td>`;

            if (currentData.level_pemetaan === 'cpl') {

                cpl.forEach(c => {
                    let value = mapping?.[c.id]?.[mk.id] ?? 0;

                    tbody += `
                    <td>
                        <input type="checkbox"
                            ${value > 0 ? 'checked' : ''}>
                    </td>`;
                });

            } else {

                cpl.forEach(c => {

                    // ✅ ADA IK
                    if (c.indikator_kinerja && c.indikator_kinerja.length > 0) {

                        c.indikator_kinerja.forEach(ik => {
                            let value = indikator_kinerja_mapping?.[ik.id]?.[mk.id] ?? 0;

                            tbody += `
                            <td>
                                <input type="checkbox"
                                    ${value > 0 ? 'checked' : ''}>
                            </td>`;
                        });

                    } else {

                        // ✅ TIDAK ADA IK → pakai CPL
                        let value = mapping?.[c.id]?.[mk.id] ?? 0;

                        tbody += `
                        <td>
                            <input type="checkbox"
                                ${value > 0 ? 'checked' : ''}>
                        </td>`;
                    }
                });
            }

            tbody += `</tr>`;
        });

        $('#tbodyMapping').html(tbody);
    }
</script>
