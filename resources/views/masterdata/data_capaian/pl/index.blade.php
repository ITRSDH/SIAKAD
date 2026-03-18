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

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Profile Lulusan</h4>

                    <div class="d-flex gap-2">
                        <a href="javascript:void(0)" id="btn-tambah-pl" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body position-relative">

                <div id="pl-table-loader" class="table-loader d-none">
                    <div class="spinner-border text-primary"></div>
                </div>

                <div class="table-responsive">
                    <table id="pl-table" class="table table-bordered table-striped table-hover text-center w-100">
                        <thead>
                            <tr>
                                <th>Kode PL</th>
                                <th>Profile Lulusan</th>
                                <th>Deskripsi</th>
                                <th>Profesi Lulusan</th>
                                <th width="120">Aksi</th>
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
    var tablePL;

    function showLoader() {
        $('#pl-table-loader').removeClass('d-none');
    }

    function hideLoader() {
        $('#pl-table-loader').addClass('d-none');
    }

    function initPLTable() {

        if ($.fn.dataTable.isDataTable('#pl-table')) {
            $('#pl-table').DataTable().destroy();
        }

        window.ensureDataTables(function() {
            tablePL = $('#pl-table').DataTable({

                ajax: {
                    url: "{{ route('capaian.pl.data', $id_prodi) }}",
                    type: "GET",
                    cache: false,
                    dataSrc: 'data',
                    beforeSend: function() {
                        showLoader();
                    },
                    complete: function() {
                        hideLoader();
                    }
                },

                columns: [{
                        data: 'kode_pl',
                        defaultContent: ''
                    },
                    {
                        data: 'profile_lulusan',
                        defaultContent: ''
                    },

                    {
                        data: null,
                        defaultContent: '',
                        render: function(data, type, row) {

                            let indo = row.deskripsi_profile_lulusan_indonesia ?? '';
                            let eng = row.deskripsi_profile_lulusan_english ?? '';

                            return `
                            <div>
                                <div>${indo}</div>
                                <div style="font-style: italic; color:#6c757d;">${eng}</div>
                            </div>
                        `;
                        }
                    },

                    {
                        data: 'profesi_lulusan',
                        defaultContent: ''
                    },

                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {

                            return `
                        <div class="d-flex justify-content-center gap-2">

                            <button class="btn btn-warning btn-sm btn-edit-pl" data-id="${data}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm btn-delete-pl" data-id="${data}">
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>`;
                        }
                    }
                ],

                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                }

            });
        });

    }

    initPLTable();


    // ===============================
    // TAMBAH DATA
    // ===============================

    $(document).on('click', '#btn-tambah-pl', function() {

        if ($('#pl-form-row').length) return;

        let formRow = `

<tr id="pl-form-row">

<td>
<input type="text" class="form-control" placeholder="KODE PL" id="pl-kode_pl">
</td>

<td>
<textarea class="form-control" rows="4" placeholder="Profil Lulusan" id="pl-profil_lulusan"></textarea>
</td>

<td>

<textarea class="form-control mb-2"
rows="2"
placeholder="Bahasa Indonesia (Wajib)"
id="pl-deskripsi_id"></textarea>

<textarea class="form-control"
rows="2"
placeholder="Bahasa Inggris (Optional)"
id="pl-deskripsi_en"></textarea>

</td>

<td>
<textarea class="form-control"
rows="4"
placeholder="Profesi Lulusan"
id="pl-profesi"></textarea>
</td>

<td>

<div class="d-flex justify-content-center gap-2 flex-wrap">

<button class="btn btn-success btn-sm" id="pl-btn-simpan-pl">
<i class="fas fa-save"></i>
</button>

<button class="btn btn-warning btn-sm" id="pl-btn-batal-pl">
<i class="fas fa-times"></i>
</button>

</div>

</td>

</tr>
`;

        $('#pl-table tbody').prepend(formRow);

    });


    // batal tambah
    $(document).on('click', '#pl-btn-batal-pl', function() {
        $('#pl-form-row').remove();
    });


    // ===============================
    // SIMPAN
    // ===============================

    $(document).on('click', '#pl-btn-simpan-pl', function() {

        let kodePl = $('#pl-kode_pl').val().trim();

        // Check if Kode PL is empty
        if (!kodePl) {
            Swal.fire('Error', 'Kode PL wajib diisi', 'error');
            return;
        }

        // Check for duplicate Kode PL in existing data
        let existingData = tablePL.data().toArray();
        let duplicate = existingData.find(item => item.kode_pl === kodePl);

        if (duplicate) {
            Swal.fire('Error', 'Kode PL sudah digunakan', 'error');
            return;
        }

        showLoader();

        $.ajax({

            url: "{{ route('capaian.pl.store', $id_prodi) }}",
            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                kode_pl: $('#pl-kode_pl').val(),
                profile_lulusan: $('#pl-profil_lulusan').val(),
                deskripsi_profile_lulusan_indonesia: $('#pl-deskripsi_id').val(),
                deskripsi_profile_lulusan_english: $('#pl-deskripsi_en').val(),
                profesi_lulusan: $('#pl-profesi').val(),
                id_prodi: "{{ $id_prodi }}"
            },

            success: function(res) {

                $('#pl-form-row').remove();

                tablePL.ajax.reload(null, false);

                Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');

            },

            error: function(xhr) {
                hideLoader();

                let errorMessage = 'Gagal menyimpan data';
                let errors = xhr.responseJSON?.errors;

                if (errors) {
                    // Display specific validation errors
                    if (typeof errors === 'object') {
                        let errorMessages = Object.values(errors).flat();
                        errorMessage = errorMessages.join('<br>');
                    }
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire('Error', errorMessage, 'error');
            }

        });

    });


    // ===============================
    // EDIT
    // ===============================

    $(document).on('click', '.btn-edit-pl', function() {

        let tr = $(this).closest('tr');
        let data = tablePL.row(tr).data();

        let formEdit = `

<tr id="pl-edit-row">

<td>
<input type="text" class="form-control"
value="${data.kode_pl}"
id="pl-edit_kode_pl">
</td>

<td>
<textarea class="form-control"
rows="4"
id="pl-edit_profil">${data.profile_lulusan}</textarea>
</td>

<td>

<textarea class="form-control mb-2"
rows="2"
id="pl-edit_deskripsi_id">${data.deskripsi_profile_lulusan_indonesia}</textarea>

<textarea class="form-control"
rows="2"
id="pl-edit_deskripsi_en">${data.deskripsi_profile_lulusan_english ?? ''}</textarea>

</td>

<td>
<textarea class="form-control"
rows="4"
id="pl-edit_profesi">${data.profesi_lulusan}</textarea>
</td>

<td>

<div class="d-flex justify-content-center gap-2 flex-wrap">

<button class="btn btn-success btn-sm pl-btn-update-pl"
data-id="${data.id}">
<i class="fas fa-save"></i>
</button>

<button class="btn btn-secondary btn-sm pl-btn-cancel-pl">
<i class="fas fa-times"></i>
</button>

</div>

</td>

</tr>
`;

        tr.replaceWith(formEdit);

    });


    // batal edit
    $(document).on('click', '.pl-btn-cancel-pl', function() {
        tablePL.ajax.reload(null, false);
    });


    // ===============================
    // UPDATE
    // ===============================

    $(document).on('click', '.pl-btn-update-pl', function() {

        let id = $(this).data('id');
        let kodePl = $('#pl-edit_kode_pl').val().trim();

        // Check if Kode PL is empty
        if (!kodePl) {
            Swal.fire('Error', 'Kode PL wajib diisi', 'error');
            return;
        }

        // Check for duplicate Kode PL in existing data (excluding current record)
        let existingData = tablePL.data().toArray();
        var currentData = existingData.find(item => item.id == id);
        let duplicate = existingData.find(item => item.kode_pl === kodePl && item.id != id);

        if (duplicate) {
            Swal.fire('Error', 'Kode PL sudah digunakan', 'error');
            return;
        }

        showLoader();

        $.ajax({

            url: "{{ route('capaian.pl.update', ['id' => ':id', 'id_prodi' => $id_prodi]) }}".replace(
                ':id',
                id),

            type: "PUT",

            data: {
                _token: "{{ csrf_token() }}",
                kode_pl: $('#pl-edit_kode_pl').val(),
                profile_lulusan: $('#pl-edit_profil').val(),
                deskripsi_profile_lulusan_indonesia: $('#pl-edit_deskripsi_id').val(),
                deskripsi_profile_lulusan_english: $('#pl-edit_deskripsi_en').val(),
                profesi_lulusan: $('#pl-edit_profesi').val()
            },

            success: function() {

                hideLoader();

                Swal.fire('Berhasil', 'Data berhasil diupdate', 'success');

                tablePL.ajax.reload(null, false);

            },

            error: function(xhr) {

                hideLoader();

                let errorMessage = 'Gagal update data';
                let errors = xhr.responseJSON?.errors;

                if (errors) {
                    if (typeof errors === 'object') {
                        let errorMessages = Object.values(errors).flat();
                        errorMessage = errorMessages.join('<br>');
                    }
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire('Error', errorMessage, 'error');

            }

        });

    });


    // ===============================
    // DELETE
    // ===============================

    $(document).on('click', '.btn-delete-pl', function() {

        let id = $(this).data('id');
        let row = $(this).closest('tr');

        Swal.fire({

            title: 'Hapus data ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'

        }).then((result) => {

            if (result.isConfirmed) {

                showLoader();

                $.ajax({

                    url: "{{ route('capaian.pl.destroy', ':id') }}".replace(':id', id),

                    type: 'DELETE',

                    data: {
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        hideLoader();

                        tablePL.row(row).remove().draw(false);

                        Swal.fire('Berhasil', res.message ?? 'Data dihapus', 'success');

                    },

                    error: function() {

                        hideLoader();

                        Swal.fire('Gagal', 'Tidak dapat menghapus data', 'error');

                    }

                });

            }

        });

    });
</script>
