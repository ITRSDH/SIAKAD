<script>
    $(document).ready(function() {
        let kelasId = "{{ $kelaskuliah['id'] }}";
        let dosenLoaded = false;

        function resetDosenValidation() {
            $('#dosenValidationAlert').addClass('d-none').html('');
            $('#formDosen .is-invalid').removeClass('is-invalid');
            $('#formDosen [data-error-for]').html('');
        }

        function setDosenFieldError(fieldName, message) {
            const normalizedFieldName = fieldName.replace(/\.\d+\./g, '.').replace(/\[\d+\]/g, '');
            const field = $(`#formDosen [name="${fieldName}"], #formDosen [name="${normalizedFieldName}"]`);
            const errorBox = $(`#formDosen [data-error-for="${fieldName}"], #formDosen [data-error-for="${normalizedFieldName}"]`).first();

            if (field.length) {
                field.addClass('is-invalid');
            }

            if (errorBox.length) {
                errorBox.html(message);
            }
        }

        function collectDosenConflictItems(res) {
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

        function formatDosenConflictItem(item) {
            const dosen = item.dosen?.nama_dosen || item.nama_dosen || item.dosen_pengajar?.nama_dosen || '-';
            const kelas = item.nama_kelas || item.kelas_kuliah?.nama_kelas || '-';
            const hari = item.hari || item.jadwal?.hari || '-';
            const jamMulai = item.jam_mulai || item.jadwal?.jam_mulai || '-';
            const jamSelesai = item.jam_selesai || item.jadwal?.jam_selesai || '-';

            let text = kelas;
            if (hari !== '-' || jamMulai !== '-' || jamSelesai !== '-') {
                text += ` | ${hari} ${jamMulai}-${jamSelesai}`;
            }
            if (dosen && dosen !== '-') {
                text += ` | Dosen: ${dosen}`;
            }

            return text;
        }

        function initSelect2() {
            let el = $('#modalDosen .select2');

            el.each(function() {
                let e = $(this);

                if (e.data('select2')) {
                    e.select2('destroy');
                }

                e.select2({
                    dropdownParent: $('#modalDosen'),
                    width: '100%',
                    placeholder: '-- Pilih Dosen --'
                });
            });
        }

        $(document).on('shown.bs.modal', '#modalDosen', function() {
            initSelect2();
        });

        function loadDosen() {
            if (dosenLoaded) return;

            $("#content-dosen").html(`
            <div class="text-center p-3">
                <div class="spinner-border"></div>
                <p>Loading...</p>
            </div>
        `);

            $.get(`/dosen-pengajar-kelas/kelas/${kelasId}`, function(res) {
                $("#content-dosen").html(res);
                initSelect2();
                dosenLoaded = true;
            }).fail(function() {
                $("#content-dosen").html(`
                <div class="alert alert-danger">
                    Gagal load data
                </div>
            `);
            });
        }

        window.reloadDosen = function() {
            dosenLoaded = false;
            loadDosen();
        }

        // Load data saat tab aktif
        $('a[href="#dosen-pengajar"]').on('shown.bs.tab', function(e) {
            loadDosen();
        });

        // Load data otomatis saat halaman dimuat (karena ini tab default)
        loadDosen();

        // =========================
        // TAMBAH
        // =========================
        $(document).on('click', '.btn-add', function() {
            $('#formDosen')[0].reset();
            $('#id').val('');
            resetDosenValidation();
            $('#modalTitle').text('TAMBAH AKTIVITAS DOSEN MENGAJAR');
            $('#modalDosen').modal('show');
        });

        // =========================
        // EDIT
        // =========================
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.get(`/dosen-pengajar-kelas/${id}`, function(res) {
                let d = res.data;

                resetDosenValidation();
                $('#id').val(d.id);
                $('select[name=id_registrasi_dosen]').val(d.id_registrasi_dosen).trigger(
                    'change');
                $('input[name=sks_substansi_total]').val(d.sks_substansi_total);
                $('input[name=rencana_tatap_muka]').val(d.rencana_tatap_muka);
                $('input[name=realisasi_tatap_muka]').val(d.realisasi_tatap_muka);
                $('input[name=urutan]').val(d.urutan);

                $('#modalTitle').text('UBAH AKTIVITAS DOSEN MENGAJAR');
                $('#modalDosen').modal('show');
            });
        });

        // =========================
        // SUBMIT (CREATE + UPDATE)
        // =========================
        $(document).on('submit', '#formDosen', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button[type=submit]');
            let id = $('#id').val();

            let url = `/dosen-pengajar-kelas/kelas/${kelasId}`;
            let method = "POST";

            if (id) {
                url = `/dosen-pengajar-kelas/${id}`;
                method = "PUT";
            }

            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),

                beforeSend: function() {
                    resetDosenValidation();
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
                    $('#modalDosen').modal('hide');
                    form[0].reset();
                    $('#id').val('');
                    reloadDosen();

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
                                setDosenFieldError(field, text);
                            });
                            messageParts.push(Object.values(res.errors).flat().join('<br>'));
                        }

                        const conflictSource = collectDosenConflictItems(res);
                        if (conflictSource.length) {
                            const conflictItems = conflictSource.map(formatDosenConflictItem);
                            if (conflictItems.length) {
                                messageParts.push('<strong>Konflik pengajaran dosen:</strong><br>' + conflictItems.join('<br>'));
                            }
                        }

                        let msg = messageParts.filter(Boolean).join('<br><br>');
                        if (!msg) {
                            msg = 'Validasi backend menolak data dosen pengajar ini.';
                        }

                        $('#dosenValidationAlert').removeClass('d-none').html(msg);
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
        // DELETE (PAKAI KONFIRMASI)
        // =========================
        $(document).on('click', '.btn-delete', function() {
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
                        url: `/dosen-pengajar-kelas/${id}`,
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
                            reloadDosen();
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
