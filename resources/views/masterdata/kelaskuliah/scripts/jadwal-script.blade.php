<script>
    $(document).ready(function() {
        let kelasId = "{{ $kelaskuliah['id'] }}";
        let jadwalLoaded = false;

        function resetJadwalValidation() {
            $('#jadwalValidationAlert').addClass('d-none').html('');
            $('#formJadwal .is-invalid').removeClass('is-invalid');
            $('#formJadwal [data-error-for]').html('');
        }

        function setFieldError(fieldName, message) {
            const normalizedFieldName = fieldName.replace(/\.\d+\./g, '.').replace(/\[\d+\]/g, '');
            const field = $(`#formJadwal [name="${fieldName}"], #formJadwal [name="${normalizedFieldName}"]`);
            const errorBox = $(`#formJadwal [data-error-for="${fieldName}"], #formJadwal [data-error-for="${normalizedFieldName}"]`).first();

            if (field.length) {
                field.addClass('is-invalid');
            }

            if (errorBox.length) {
                errorBox.html(message);
            }
        }

        function collectConflictItems(res) {
            if (Array.isArray(res?.data)) {
                return res.data;
            }

            if (Array.isArray(res?.meta?.conflicts)) {
                return res.meta.conflicts;
            }

            if (Array.isArray(res?.conflicts)) {
                return res.conflicts;
            }

            if (Array.isArray(res?.error_data?.conflicts)) {
                return res.error_data.conflicts;
            }

            return [];
        }

        function formatConflictItem(item) {
            const hari = item.hari || item.jadwal?.hari || '-';
            const jamMulai = item.jam_mulai || item.jadwal?.jam_mulai || '-';
            const jamSelesai = item.jam_selesai || item.jadwal?.jam_selesai || '-';
            const ruang = item.ruang_kuliah?.nama_ruang || item.nama_ruang || item.ruang || '-';
            const dosen = item.dosen?.nama_dosen || item.nama_dosen || null;

            let text = `${hari} ${jamMulai}-${jamSelesai}`;
            if (ruang && ruang !== '-') {
                text += ` | Ruang: ${ruang}`;
            }
            if (dosen) {
                text += ` | Dosen: ${dosen}`;
            }

            return text;
        }

        function initSelect2Jadwal() {
            let el = $('#modalJadwal .select2');

            el.each(function() {
                let e = $(this);

                if (e.data('select2')) {
                    e.select2('destroy');
                }

                e.select2({
                    dropdownParent: $('#modalJadwal'),
                    width: '100%',
                    placeholder: '-- Pilih Hari --'
                });
            });
        }

        $(document).on('shown.bs.modal', '#modalJadwal', function() {
            initSelect2Jadwal();
        });

        function loadJadwal() {
            if (jadwalLoaded) return;

            $("#content-jadwal").html(`
            <div class="text-center p-3">
                <div class="spinner-border"></div>
                <p>Loading...</p>
            </div>
        `);

            $.get(`/jadwal-kelas/kelas/${kelasId}`, function(res) {
                $("#content-jadwal").html(res);
                initSelect2Jadwal();
                jadwalLoaded = true;
            }).fail(function() {
                $("#content-jadwal").html(`
                <div class="alert alert-danger">
                    Gagal load data jadwal
                </div>
            `);
            });
        }

        window.reloadJadwal = function() {
            jadwalLoaded = false;
            loadJadwal();
        }

        // Load data saat tab aktif
        $('a[href="#jadwal"]').on('shown.bs.tab', function(e) {
            loadJadwal();
        });

        // =========================
        // TAMBAH JADWAL
        // =========================
        $(document).on('click', '.btn-add-jadwal', function() {
            $('#formJadwal')[0].reset();
            $('#id').val('');
            $('select[name=id_ruang]').val('').trigger('change');
            resetJadwalValidation();
            $('#modalJadwal').modal('show');
        });

        // =========================
        // EDIT JADWAL
        // =========================
        $(document).on('click', '.btn-edit-jadwal', function() {
            let id = $(this).data('id');

            $.get(`/jadwal-kelas/${id}`, function(res) {
                let d = res.data;

                resetJadwalValidation();
                $('#id').val(d.id);
                $('select[name=hari]').val(d.hari).trigger('change');
                $('select[name=id_ruang]').val(d.id_ruang || d.id_ruang_kuliah || '').trigger('change');
                $('input[name=jam_mulai]').val(d.jam_mulai);
                $('input[name=jam_selesai]').val(d.jam_selesai);

                $('#modalJadwal').modal('show');
            });
        });

        // =========================
        // SUBMIT JADWAL (CREATE + UPDATE)
        // =========================
        $(document).on('submit', '#formJadwal', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button[type=submit]');
            let id = $('#id').val();

            let url = `/jadwal-kelas/kelas/${kelasId}`;
            let method = "POST";

            if (id) {
                url = `/jadwal-kelas/${id}`;
                method = "PUT";
            }

            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),

                beforeSend: function() {
                    resetJadwalValidation();
                    btn.prop('disabled', true).text('Menyimpan...');
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Sedang menyimpan data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },

                success: function(res) {
                    Swal.close();
                    $('#modalJadwal').modal('hide');
                    form[0].reset();
                    $('#id').val('');
                    reloadJadwal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },

                error: function(xhr) {
                    Swal.close();
                    let res = xhr.responseJSON;

                    if (xhr.status === 422) {
                        let messageParts = [];

                        if (res?.message) {
                            messageParts.push(res.message);
                        }

                        if (res?.errors && typeof res.errors === 'object') {
                            Object.entries(res.errors).forEach(function([field, messages]) {
                                const text = Array.isArray(messages) ? messages.join('<br>') : messages;
                                setFieldError(field, text);
                            });
                            messageParts.push(Object.values(res.errors).flat().join('<br>'));
                        }

                        const conflictSource = collectConflictItems(res);
                        if (conflictSource.length) {
                            const conflictItems = conflictSource.map(formatConflictItem);
                            if (conflictItems.length) {
                                messageParts.push('<strong>Jadwal yang bentrok:</strong><br>' + conflictItems.join('<br>'));
                            }
                        }

                        let msg = messageParts.filter(Boolean).join('<br><br>');
                        if (!msg) {
                            msg = 'Validasi backend menolak data jadwal ini.';
                        }

                        $('#jadwalValidationAlert').removeClass('d-none').html(msg);

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Error',
                            html: msg
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server'
                        });
                    }
                },

                complete: function() {
                    btn.prop('disabled', false).text('Simpan');
                }
            });
        });

        // =========================
        // DELETE JADWAL
        // =========================
        $(document).on('click', '.btn-delete-jadwal', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/jadwal-kelas/${id}`,
                        type: "DELETE",

                        beforeSend: function() {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Menghapus data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },

                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            reloadJadwal();
                        },

                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal hapus data'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
