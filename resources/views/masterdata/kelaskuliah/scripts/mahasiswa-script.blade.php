<script>
$(document).ready(function() {
    let kelasId = "{{ $kelaskuliah['id'] }}";
    let mahasiswaLoaded = false;

    function loadMahasiswa() {
        if (mahasiswaLoaded) return;

        $("#content-mahasiswa").html(`
            <div class="text-center p-3">
                <div class="spinner-border"></div>
                <p>Loading...</p>
            </div>
        `);

        $.get(`/mahasiswa-kelas/kelas/${kelasId}`, function(res) {
            $("#content-mahasiswa").html(res);
            mahasiswaLoaded = true;
        }).fail(function() {
            $("#content-mahasiswa").html(`
                <div class="alert alert-danger">
                    Gagal load data mahasiswa
                </div>
            `);
        });
    }

    window.reloadMahasiswa = function() {
        mahasiswaLoaded = false;
        loadMahasiswa();
    }

    // Load data saat tab aktif
    $('a[href="#mahasiswa"]').on('shown.bs.tab', function (e) {
        loadMahasiswa();
    });

    // =========================
    // TAMBAH MAHASISWA
    // =========================
    $(document).on('click', '.btn-add-mahasiswa', function() {
        $('#formMahasiswa')[0].reset();
        $('#id_mahasiswa').val('');
        $('#modalMahasiswa').modal('show');
    });

    // =========================
    // EDIT MAHASISWA
    // =========================
    $(document).on('click', '.btn-edit-mahasiswa', function() {
        let id = $(this).data('id');

        $.get(`/mahasiswa-kelas/${id}`, function(res) {
            let d = res.data;

            $('#id_mahasiswa').val(d.id);
            $('select[name=id_registrasi_mahasiswa]').val(d.id_registrasi_mahasiswa).trigger('change');
            $('input[name=angkatan]').val(d.angkatan);

            $('#modalMahasiswa').modal('show');
        });
    });

    // =========================
    // SUBMIT MAHASISWA (CREATE + UPDATE)
    // =========================
    $(document).on('submit', '#formMahasiswa', function(e) {
        e.preventDefault();

        let form = $(this);
        let btn = form.find('button[type=submit]');
        let id = $('#id_mahasiswa').val();

        let url = `/mahasiswa-kelas/kelas/${kelasId}`;
        let method = "POST";

        if (id) {
            url = `/mahasiswa-kelas/${id}`;
            method = "PUT";
        }

        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),

            beforeSend: function() {
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
                $('#modalMahasiswa').modal('hide');
                form[0].reset();
                $('#id_mahasiswa').val('');
                reloadMahasiswa();

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
                    let msg = Object.values(res.errors).join('<br>');
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
    // DELETE MAHASISWA
    // =========================
    $(document).on('click', '.btn-delete-mahasiswa', function() {
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
                    url: `/mahasiswa-kelas/${id}`,
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
                        reloadMahasiswa();
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
