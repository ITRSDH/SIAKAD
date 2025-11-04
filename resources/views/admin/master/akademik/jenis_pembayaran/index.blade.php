@extends('admin.layouts.index')
@section('title', 'Jenis Pembayaran')
@push('styles-custom')
    <style>
        /* Gaya untuk loader */
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            /* Latar belakang transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            /* Pastikan loader muncul di atas konten */
            border-radius: inherit;
            /* Membuat sudut tetap jika card memiliki border-radius */
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            /* Warna spinner */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Pastikan card body memiliki posisi relatif agar loader muncul di dalamnya */
        .card-body {
            position: relative;
        }

        /* Sembunyikan loader secara default */
        .loader-overlay.hidden {
            display: none;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .card-header[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
            /* Berputar 180 derajat saat dibuka */
        }

        /* Gaya untuk format nominal */
        .nominal-display {
            font-family: monospace;
            text-align: right;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Jenis Pembayaran</h3>
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
                    <a href="{{ route('jenis-pembayaran.index') }}">Jenis Pembayaran</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jenis-pembayaran.index') }}">List Jenis Pembayaran</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapsePembayaranForm" aria-expanded="true"
                        aria-controls="collapsePembayaranForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Jenis Pembayaran
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapsePembayaranForm">
                        <div class="card-body">
                            <form id="pembayaranForm" name="pembayaranForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="pembayaran_id">

                                <div class="form-group row mb-3">
                                    <label for="nama_pembayaran" class="col-sm-3 col-form-label text-end">
                                        Nama Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nama_pembayaran"
                                            name="nama_pembayaran"
                                            placeholder="Contoh: SPP Bulanan, Uang Gedung, Uang Seragam" required>
                                        <small class="form-text text-muted">Nama dari jenis pembayaran.</small>
                                        <span id="nama_pembayaran_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <!-- Input Nominal dengan Format Rupiah -->
                                <div class="form-group row mb-3">
                                    <label for="nominal_display" class="col-sm-3 col-form-label text-end">
                                        Nominal <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <!-- Input yang ditampilkan ke pengguna -->
                                        <input type="text" class="form-control" id="nominal_display"
                                            name="nominal_display" placeholder="Contoh: Rp 5.000.000" required>
                                        <!-- Input tersembunyi untuk menyimpan nilai angka murni -->
                                        <input type="hidden" id="nominal" name="nominal" value="">
                                        <small class="form-text text-muted">Masukkan nominal pembayaran (akan otomatis
                                            diformat).</small>
                                        <span id="nominal_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="keterangan" class="col-sm-3 col-form-label text-end">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" style="height:100px" id="keterangan" name="keterangan"
                                            placeholder="Tambahkan keterangan opsional..."></textarea>
                                        <small class="form-text text-muted">Keterangan tambahan mengenai jenis pembayaran
                                            ini jika diperlukan.</small>
                                        <span id="keterangan_error" class="text-danger error-text"></span>
                                    </div>
                                </div>
                                <hr class="mt-0 mb-4">
                                <div class="form-group row mb-0">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary" id="saveBtn">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetBtn">
                                            <i class="fas fa-redo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tabel Data -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Jenis Pembayaran
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="pembayaran-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pembayaran</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi ini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalPembayaran" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pembayaranFormModal" name="pembayaranFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="pembayaran_id_modal">

                        <div class="form-group mb-3">
                            <label for="nama_pembayaran_modal" class="col-form-label">Nama Pembayaran</label>
                            <input type="text" class="form-control" id="nama_pembayaran_modal" name="nama_pembayaran"
                                required>
                            <span class="text-danger error-text nama_pembayaran_error"></span>
                        </div>
                        <!-- Input Nominal Modal dengan Format Rupiah -->
                        <div class="form-group mb-3">
                            <label for="nominal_display_modal" class="col-form-label">Nominal</label>
                            <!-- Input yang ditampilkan ke pengguna di modal -->
                            <input type="text" class="form-control" id="nominal_display_modal"
                                name="nominal_display_modal" required>
                            <!-- Input tersembunyi untuk menyimpan nilai angka murni di modal -->
                            <input type="hidden" id="nominal_modal" name="nominal" value="">
                            <span class="text-danger error-text nominal_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan_modal" class="col-form-label">Keterangan</label>
                            <textarea class="form-control" style="height:100px" id="keterangan_modal" name="keterangan"></textarea>
                            <span class="text-danger error-text keterangan_error"></span>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary" id="saveBtnModal">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk format angka ke Rupiah
        function formatRupiah(angka, prefix = 'Rp ') {
            if (isNaN(angka)) return prefix + '0';
            // Hapus semua karakter non-digit
            let number_string = angka.toString().replace(/[^,\d]/g, '');
            // Pisahkan koma jika ada (untuk desimal, meskipun untuk nominal biasanya integer)
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }

        // Fungsi untuk mengembalikan format Rupiah ke angka murni
        function unformatRupiah(formattedRupiah) {
            // Hapus prefix 'Rp ' dan semua karakter non-digit
            return formattedRupiah.replace(/[^,\d]/g, '');
        }

        // Fungsi untuk mengisi form input nominal (create)
        function fillNominalInputCreate(nominalValue) {
            $('#nominal_display').val(formatRupiah(nominalValue));
            $('#nominal').val(nominalValue);
        }

        // Fungsi untuk mengisi form input nominal (edit modal)
        function fillNominalInputEdit(nominalValue) {
            $('#nominal_display_modal').val(formatRupiah(nominalValue));
            $('#nominal_modal').val(nominalValue);
        }

        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var pembayaranData =
            @json($jenis_pembayaran); // Pastikan nama variabel sesuai dengan yang dikirim dari controller

            // Fungsi format nominal untuk tampilan tabel
            function formatRupiahTable(angka) {
                if (isNaN(angka)) return 'Rp 0';
                return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
            }

            // Inisialisasi DataTables dengan data dari PHP
            var table = $('#pembayaran-table').DataTable({
                data: pembayaranData,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            // Kolom No (indeks baris + 1)
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_pembayaran'
                    },
                    {
                        data: 'nominal',
                        render: function(data, type, row) {
                            // Format nominal ke Rupiah untuk tampilan tabel
                            return '<span class="nominal-display">' + formatRupiahTable(data) +
                                '</span>';
                        }
                    },
                    {
                        data: 'keterangan',
                        render: function(data, type, row) {
                            // Tampilkan '-' jika keterangan kosong
                            return data || '-';
                        }
                    },
                    {
                        data: null, // Tidak ada data spesifik dari API untuk kolom ini
                        render: function(data, type, row) {
                            // Generate tombol aksi berdasarkan ID dari data API
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
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
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json' // Bahasa Indonesia
                },
                drawCallback: function(settings) {
                    // Sembunyikan loader setelah tabel selesai digambar
                    $('#tableLoader').addClass('hidden');
                }
            });

            // Event listener untuk input tampilan (Create)
            $('#nominal_display').on('keyup', function(e) {
                // Ambil nilai dari input tampilan
                let input = $(this).val();
                // Hapus prefix 'Rp ' jika ada saat pengguna mengetik
                input = input.replace(/^Rp\s?/, '');
                // Hapus semua karakter non-digit (angka, titik, koma)
                let numericValue = input.replace(/[^\d,]/g, '');

                // Format kembali ke Rupiah dan tampilkan di input tampilan
                $(this).val(formatRupiah(numericValue));

                // Simpan nilai angka murni ke input tersembunyi
                $('#nominal').val(numericValue);
            });

            // Event listener untuk input tampilan (Edit Modal)
            $('#nominal_display_modal').on('keyup', function(e) {
                // Ambil nilai dari input tampilan modal
                let input = $(this).val();
                // Hapus prefix 'Rp ' jika ada saat pengguna mengetik
                input = input.replace(/^Rp\s?/, '');
                // Hapus semua karakter non-digit (angka, titik, koma)
                let numericValue = input.replace(/[^\d,]/g, '');

                // Format kembali ke Rupiah dan tampilkan di input tampilan modal
                $(this).val(formatRupiah(numericValue));

                // Simpan nilai angka murni ke input tersembunyi modal
                $('#nominal_modal').val(numericValue);
            });

            // Reset form (Create)
            $('#resetBtn').click(function() {
                $('#pembayaranForm')[0].reset();
                $('#pembayaran_id').val('');
                $('#nominal').val(''); // Reset input hidden juga
                $('#nominal_display').val(''); // Reset input display
                $('.error-text').text(''); // Hapus pesan error
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#pembayaranForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Gunakan serialize() - input hidden 'nominal' akan terbawa
                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('jenis-pembayaran.store') }}", // Pastikan route sesuai
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location
                                    .reload(); // Reload halaman setelah SweetAlert ditutup
                            });
                        } else {
                            // Tangani error dari server (jika ada pesan kesalahan spesifik)
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                // Tampilkan pesan error umum jika tidak ada error spesifik
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message ||
                                        'Terjadi kesalahan saat menyimpan data.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Tampilkan error pertama dari server
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        // Tampilkan error spesifik jika ada
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    },
                    complete: function() { // Aktifkan kembali tombol setelah permintaan selesai
                        $('#saveBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
                        );
                    }
                });
            });

            // Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                // Ambil data pembayaran spesifik dari API
                $.get("{{ route('jenis-pembayaran.show', '') }}/" + id) // Pastikan route sesuai
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Jenis Pembayaran');
                            $('#pembayaran_id_modal').val(data.data.id);
                            $('#nama_pembayaran_modal').val(data.data.nama_pembayaran);
                            // Gunakan fungsi untuk mengisi nominal dengan format
                            fillNominalInputEdit(data.data.nominal);
                            $('#keterangan_modal').val(data.data.keterangan);
                            // Hapus error sebelumnya
                            $('.error-text').text('');
                            $('#modalPembayaran').modal('show');
                        } else {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data yang Anda cari tidak ditemukan.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .fail(function(xhr) {
                        console.error('Error fetching data for edit:', xhr);
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengambil data untuk diedit.',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Submit edit via modal
            $('#pembayaranFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#pembayaran_id_modal').val();
                // Gunakan serialize() - input hidden 'nominal' dari modal akan terbawa
                const formData = $(this).serialize();

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('jenis-pembayaran.update', '') }}/" +
                    id, // Pastikan route sesuai
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Ganti alert dengan SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#modalPembayaran').modal('hide');
                                location
                                    .reload(); // Reload halaman setelah SweetAlert ditutup
                            });
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            } else {
                                // Tampilkan pesan error umum jika tidak ada error spesifik
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message ||
                                        'Terjadi kesalahan saat memperbarui data.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Gagal memperbarui data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Tampilkan error pertama dari server
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0] || errorMessage;
                        }
                        // Ganti alert dengan SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    },
                    complete: function() {
                        $('#saveBtnModal').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan'
                        );
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                // Ganti confirm dengan SweetAlert2
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data jenis pembayaran ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loader tabel saat menghapus
                        $('#tableLoader').removeClass('hidden');

                        $.ajax({
                            url: "{{ route('jenis-pembayaran.destroy', '') }}/" +
                                id, // Pastikan route sesuai
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location
                                            .reload(); // Reload halaman setelah SweetAlert ditutup
                                    });
                                } else {
                                    // Ganti alert dengan SweetAlert2
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Gagal menghapus data.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error('AJAX Error:', xhr);
                                let errorMessage = 'Gagal menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                // Ganti alert dengan SweetAlert2
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            },
                            complete: function() { // Pastikan loader disembunyikan setelah permintaan selesai
                                $('#tableLoader').addClass('hidden');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
