<style>
    .table-loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .ik-row td:first-child::before {
        content: '↳ ';
        color: #6c757d;
        font-weight: bold;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-edit {
        background-color: #ffc107;
        color: #000;
    }

    .btn-add-ik {
        background-color: #28a745;
        color: #fff;
    }

    .btn-del {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-kategori {
        font-size: 12px;
        padding: 4px 8px;
    }
</style>

<div class="row">
    <!-- Tabel Data -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Capaian Pembelajaran Lulusan</h4>

                    <div class="d-flex gap-2">
                        <a href="javascript:void(0)" id="btn-tambah-cpl" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <div id="cpl-table-loader" class="table-loader d-none">
                    <div class="spinner-border text-primary"></div>
                </div>
                <div class="table-responsive">
                    <table id="cpl-table" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th style="width: 15%">Kode</th>
                                <th style="width: 50%">Deskripsi</th>
                                <th style="width: 15%">Kategori</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tableCPL
    var currentData = [];

    /* ================= UTIL ================= */

    var el = s => $(s);

    var toggleLoader = s => el('#cpl-table-loader').toggleClass('d-none', !s);

    var required = (v, m) => v ? true : (Swal.fire('Error', m, 'error'), false);

    var confirmDelete = cb =>
        Swal.fire({
            title: 'Hapus data ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        })
        .then(r => r.isConfirmed && cb());

    var handleAjax = ({
        url,
        type = 'POST',
        data
    }, msg, cb = null) => {
        toggleLoader(true);
        $.ajax({
            url,
            type,
            data,
            success: r => {
                toggleLoader(false);
                tableCPL.ajax.reload(null, false);
                cb && cb(r);
                Swal.fire('Berhasil', msg, 'success');
            },
            error: x => {
                toggleLoader(false);
                let m = x.responseJSON?.message || 'Terjadi kesalahan';
                if (x.responseJSON?.errors)
                    m = Object.values(x.responseJSON.errors).flat().join('<br>');
                Swal.fire('Error', m, 'error');
            }
        });
    };

    /* ================= FORM GENERATOR ================= */

    var formSelect = val => `
<select class="form-control form-kategori">
    <option value="">Pilih</option>
    ${['KK','KU','P','S'].map(v=>`<option ${val===v?'selected':''}>${v}</option>`).join('')}
</select>`;

    var formCPL = (d = {}) => `
<tr class="row-form" data-type="cpl" data-id="${d.id||''}">
<td><input class="form-control f-kode" value="${d.kode||''}" placeholder="Kode CPL"></td>
<td>
<textarea class="form-control mb-1 f-indo" style="height: 80px" placeholder="Deskripsi Indonesia (Wajib)">${d.deskripsi_cpl_indonesia||''}</textarea>
<textarea class="form-control f-eng" style="height: 80px" placeholder="Deskripsi Inggris (Opsional)">${d.deskripsi_cpl_english||''}</textarea>
</td>
<td>${formSelect(d.kategori)}</td>
<td class="text-center">
<button class="btn btn-success btn-save">✔</button>
<button class="btn btn-secondary btn-cancel">✖</button>
</td>
</tr>`;

    var formIK = (cplId, d = {}) => `
<tr class="row-form ik-row" data-type="ik" data-parent="${cplId}" data-id="${d.id||''}">
<td><input class="form-control f-kode" value="${d.kode||''}" placeholder="Kode IK"></td>
<td>
<textarea class="form-control mb-1 f-indo" style="height: 80px" placeholder="Deskripsi Indonesia (Wajib)">${d.deskripsi_cpl_indonesia||''}</textarea>
<textarea class="form-control f-eng" style="height: 80px" placeholder="Deskripsi Inggris (Opsional)">${d.deskripsi_cpl_english||''}</textarea>
</td>
<td>${formSelect(d.kategori)}</td>
<td class="text-center">
<button class="btn btn-success btn-save">✔</button>
<button class="btn btn-secondary btn-cancel">✖</button>
</td>
</tr>`;

    /* ================= DATATABLE ================= */

    var flatten = data => data.flatMap(c => [{
            ...mapCPL(c)
        },
        ...(c.indikator_kinerja || []).map(i => ({
            ...mapIK(i, c.id)
        }))
    ]);

    var mapCPL = c => ({
        id: c.id,
        kode: c.kode_cpl,
        deskripsi_cpl_indonesia: c.deskripsi_cpl_indonesia,
        deskripsi_cpl_english: c.deskripsi_cpl_english,
        kategori: c.kategori_cpl,
        type: 'cpl'
    });

    var mapIK = (i, p) => ({
        id: i.id,
        kode: i.kode_ik_cpl,
        deskripsi_cpl_indonesia: i.deskripsi_ik_cpl_indonesia,
        deskripsi_cpl_english: i.deskripsi_ik_cpl_english,
        kategori: i.kategori_ik_cpl,
        type: 'ik',
        parentId: p
    });

    var render = {
        kode: (d, t, r) => r.type === 'cpl' ? `<b>${d}</b>` : d,
        des: (d, t, r) =>
            `
        <div class="text-muted small">${r.deskripsi_cpl_indonesia||''}</div>
        ${r.deskripsi_cpl_english?`<div class="fst-italic text-muted" style="font-size:11px">${r.deskripsi_cpl_english}</div>`:''}`,
        kat: (d, t, r) => d ? `<span class="badge ${r.type==='cpl'?'bg-primary':'bg-secondary'}">${d}</span>` : '',
        act: (d, t, r) => r.type === 'cpl' ?
            `<button class="btn-action btn-edit" data-type="cpl" data-id="${r.id}"><i class="fas fa-edit"></i></button>
           <button class="btn-action btn-add-ik" data-id="${r.id}"><i class="fas fa-plus"></i></button>
           <button class="btn-action btn-del" data-type="cpl" data-id="${r.id}"><i class="fas fa-trash"></i></button>` : `<button class="btn-action btn-edit" data-type="ik" data-id="${r.id}"><i class="fas fa-edit"></i></button>
           <button class="btn-action btn-del" data-type="ik" data-id="${r.id}"><i class="fas fa-trash"></i></button>`
    };

    function initCPLTable() {
        if ($.fn.dataTable.isDataTable('#cpl-table'))
            el('#cpl-table').DataTable().destroy();

        var init = () => tableCPL = el('#cpl-table').DataTable({
            ordering: false,
            ajax: {
                url: "{{ route('capaian.cpl.data', $id_prodi) }}",
                dataSrc: r => (currentData = r.data || [], flatten(currentData)),
                beforeSend: () => toggleLoader(true),
                complete: () => toggleLoader(false)
            },
            columns: [{
                    data: 'kode',
                    render: render.kode,
                    className: 'text-center'
                },
                {
                    data: null,
                    render: render.des
                },
                {
                    data: 'kategori',
                    render: render.kat,
                    className: 'text-center'
                },
                {
                    data: null,
                    render: render.act,
                    className: 'text-center'
                }
            ],
            createdRow: (r, d) => d.type === 'ik' && $(r).addClass('ik-row'),
            language: {
                url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
            }
        });

        window.ensureDataTables ? ensureDataTables(init) : init();
    }

    /* ================= HELPER ================= */

    var getVal = row => ({
        kode: row.find('.f-kode').val(),
        indo: row.find('.f-indo').val(),
        eng: row.find('.f-eng').val(),
        kat: row.find('.form-kategori').val()
    });

    /* ================= ACTION ================= */

    // tambah CPL
    $(document).on('click', '#btn-tambah-cpl', () => {
        if (el('.row-form').length) return;
        el('#cpl-table tbody').prepend(formCPL());
    });

    // tambah IK
    $(document).on('click', '.btn-add-ik', function() {
        if (el('.row-form').length) return;
        $(this).closest('tr').after(formIK($(this).data('id')));
    });

    // cancel
    $(document).on('click', '.btn-cancel', () => tableCPL.ajax.reload(null, false));

    // edit
    $(document).on('click', '.btn-edit', function() {
        if (el('.row-form').length) return;

        let tr = $(this).closest('tr'),
            data = tableCPL.row(tr).data();

        tr.replaceWith(
            data.type === 'cpl' ?
            formCPL(data) :
            formIK(data.parentId, data)
        );
    });

    // save (CPL & IK)
    $(document).on('click', '.btn-save', function() {
        let row = $(this).closest('tr'),
            type = row.data('type'),
            id = row.data('id'),
            parent = row.data('parent'),
            v = getVal(row);

        if (!required(v.kode, 'Kode wajib')) return;

        let isEdit = !!id;

        let url = type === 'cpl' ?
            isEdit ?
            "{{ route('capaian.cpl.update', ['id' => ':id', 'id_prodi' => $id_prodi]) }}"
            .replace(':id', id) :
            "{{ route('capaian.cpl.store', $id_prodi) }}" :
            isEdit ?
            "{{ route('capaian.indikator-kinerja.update', ':id') }}"
            .replace(':id', id) :
            "{{ route('capaian.indikator-kinerja.store', ':id') }}"
            .replace(':id', parent);

        let data = {
            _token: "{{ csrf_token() }}",
            ...(type === 'cpl' ? {
                kode_cpl: v.kode,
                deskripsi_cpl_indonesia: v.indo,
                deskripsi_cpl_english: v.eng,
                kategori_cpl: v.kat
            } : {
                kode_ik_cpl: v.kode,
                deskripsi_ik_cpl_indonesia: v.indo,
                deskripsi_ik_cpl_english: v.eng,
                kategori_ik_cpl: v.kat
            })
        };

        handleAjax({
                url,
                type: isEdit ? 'PUT' : 'POST',
                data
            },
            isEdit ? 'Diupdate' : 'Disimpan');
    });

    // delete
    $(document).on('click', '.btn-del', function() {
        let id = $(this).data('id'),
            type = $(this).data('type');

        let url = type === 'cpl' ?
            "{{ route('capaian.cpl.destroy', ':id') }}" :
            "{{ route('capaian.indikator-kinerja.destroy', ':id') }}";

        confirmDelete(() => handleAjax({
            url: url.replace(':id', id),
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            }
        }, 'Dihapus'));
    });

    /* ================= INIT ================= */

    $(document).ready(initCPLTable);
</script>
