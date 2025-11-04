@extends('admin.layouts.index')
@section('title', 'Data Ruang')
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
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Ruang</h3>
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
                    <a href="{{ route('ruang.index') }}">Ruang</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ruang.index') }}">List Ruang Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapseRuangForm" aria-expanded="true"
                        aria-controls="collapseRuangForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Ruang Kuliah
                        </h3>
                        <div class="card-tools">
                            <!-- Ikon panah untuk indikasi collapse -->
                            <i class="fas fa-chevron-up collapse-icon"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapseRuangForm">
                        <div class="card-body">
                            <form id="ruangForm" name="ruangForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="id" id="ruang_id">

                                <div class="form-group row mb-3">
                                    <label for="nama_ruang" class="col-sm-3 col-form-label text-end">
                                        Nama Ruang <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nama_ruang" name="nama_ruang"
                                            placeholder="Contoh: A1.01, Lab Anatomi, Studio Fisioterapi" required>
                                        <small class="form-text text-muted">Nama ruang yang unik dan deskriptif.</small>
                                        <span id="nama_ruang_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="kapasitas" class="col-sm-3 col-form-label text-end">
                                        Kapasitas <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="kapasitas" name="kapasitas"
                                            placeholder="Contoh: 30, 40" required min="1">
                                        <small class="form-text text-muted">Jumlah maksimal peserta dalam ruang.</small>
                                        <span id="kapasitas_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="jenis_ruang" class="col-sm-3 col-form-label text-end">
                                        Jenis Ruang <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="jenis_ruang" name="jenis_ruang" required>
                                            <option value="">Pilih Jenis Ruang</option>
                                            <option value="Teori">Ruang Teori</option>
                                            <option value="Praktikum">Ruang Praktikum</option>
                                            <option value="Laboratorium">Laboratorium</option>
                                            <option value="Studio">Studio</option>
                                            <option value="Aula">Aula</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <small class="form-text text-muted">Kategori ruang berdasarkan fungsinya.</small>
                                        <span id="jenis_ruang_error" class="text-danger error-text"></span>
                                    </div>
                                </div>

                                <!-- Input untuk jenis ruang lainnya, disembunyikan secara default -->
                                <div class="form-group row mb-3 d-none" id="lainnyaContainer">
                                    <label for="jenis_ruang_lainnya" class="col-sm-3 col-form-label text-end">
                                        Jenis Ruang Spesifik <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="jenis_ruang_lainnya"
                                            name="jenis_ruang_lainnya"
                                            placeholder="Contoh: Laboratorium Anatomi, Studio Fisioterapi" disabled>
                                        <small class="form-text text-muted">Isi jenis ruang spesifik jika memilih 'Lainnya'
                                            di atas.</small>
                                        <span id="jenis_ruang_lainnya_error" class="text-danger error-text"></span>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Ruang
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="ruang-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        {{-- <th>No</th> --}}
                                        <th>Nama Ruang</th>
                                        <th>Kapasitas</th>
                                        <th>Jenis Ruang</th> <!-- Kolom ini sekarang akan menampilkan nilai akhir -->
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
    <div class="modal fade" id="modalRuang" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ruangFormModal" name="ruangFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="ruang_id_modal">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_ruang_modal" class="col-form-label">Nama Ruang</label>
                                    <input type="text" class="form-control" id="nama_ruang_modal" name="nama_ruang"
                                        required>
                                    <span class="text-danger error-text nama_ruang_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kapasitas_modal" class="col-form-label">Kapasitas</label>
                                    <input type="number" class="form-control" id="kapasitas_modal" name="kapasitas"
                                        required min="1">
                                    <span class="text-danger error-text kapasitas_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jenis_ruang_modal" class="col-form-label">Jenis Ruang</label>
                            <select class="form-control" id="jenis_ruang_modal" name="jenis_ruang" required>
                                <option value="">Pilih Jenis Ruang</option>
                                <option value="Teori">Ruang Teori</option>
                                <option value="Praktikum">Ruang Praktikum</option>
                                <option value="Laboratorium">Laboratorium</option>
                                <option value="Studio">Studio</option>
                                <option value="Aula">Aula</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <span class="text-danger error-text jenis_ruang_error"></span>
                        </div>

                        <div class="form-group mb-3 d-none" id="lainnyaContainerModal">
                            <label for="jenis_ruang_lainnya_modal" class="col-form-label">Jenis Ruang Spesifik</label>
                            <input type="text" class="form-control" id="jenis_ruang_lainnya_modal"
                                name="jenis_ruang_lainnya" placeholder="Contoh: Laboratorium Anatomi, Studio Fisioterapi"
                                disabled>
                            <span class="text-danger error-text jenis_ruang_lainnya_error"></span>
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
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var ruangData = @json($ruang);

            // Fungsi untuk menangani tampilan input 'Lainnya'
            function toggleLainnyaInput(selector, containerId) {
                const selectedValue = $(selector).val();
                const container = $(`#${containerId}`);
                const input = container.find('input');

                if (selectedValue === 'Lainnya') {
                    container.removeClass('d-none'); // ← Ini penting!
                    input.prop('disabled', false).prop('required', true);
                } else {
                    container.addClass('d-none'); // ← Ini penting!
                    input.prop('disabled', true).prop('required', false).val('');
                    container.find('.error-text').text('');
                }
            }

            // Event listener untuk dropdown utama (form create)
            $('#jenis_ruang').on('change', function() {
                toggleLainnyaInput(this, 'lainnyaContainer');
            });

            // Event listener untuk dropdown modal (form edit)
            $('#jenis_ruang_modal').on('change', function() {
                toggleLainnyaInput(this, 'lainnyaContainerModal');
            });

            // Inisialisasi DataTables dengan data dari PHP
            // Fungsi untuk menggabungkan baris berdasarkan jenis_ruang di kolom jenis_ruang
            function groupByJenisRuang(api) {
                const tbody = $('#ruang-table tbody');
                tbody.empty();

                const rowsToProcess = api.rows({
                    page: 'current'
                }).nodes();

                let currentGroup = '';
                let groupCount = 0;
                let startIndex = 0;

                // Array untuk menyimpan elemen baris yang telah dimodifikasi
                const rowsToAppend = [];

                // Proses setiap baris dalam halaman saat ini
                $(rowsToProcess).each(function(index) {
                    const rowData = api.row(this).data();

                    if (!rowData) {
                        console.warn("Data row undefined for element:", this);
                        // Tetap tambahkan baris asli jika datanya tidak valid
                        rowsToAppend.push(this);
                        return;
                    }

                    const jenisRuang = rowData.jenis_ruang;

                    if (jenisRuang !== currentGroup) {
                        // Jika grup berubah dan bukan grup pertama
                        if (currentGroup !== '') {
                            // Set rowspan pada baris pertama grup sebelumnya
                            if (startIndex < rowsToAppend.length) { // Pastikan index valid
                                const firstRowOfPreviousGroup = $(rowsToAppend[startIndex]);
                                firstRowOfPreviousGroup.find('td:eq(2)').attr('rowspan', groupCount)
                                    .addClass(
                                        'text-center align-middle'); // Kolom jenis_ruang adalah indeks 2
                            }
                        }
                        // Mulai grup baru
                        currentGroup = jenisRuang;
                        groupCount = 1;
                        startIndex = index; // Simpan index baris pertama grup saat ini

                        // Tambahkan baris ini ke daftar
                        rowsToAppend.push(this);
                    } else {
                        // Dalam grup yang sama, tambahkan baris tetapi sembunyikan sel jenis_ruang
                        groupCount++;
                        const $currentRow = $(this)
                            .clone(); // Clone agar aman jika perlu manipulasi lanjutan
                        $currentRow.find('td:eq(2)')
                            .remove(); // Hapus sel jenis_ruang dari baris berikutnya
                        rowsToAppend.push($currentRow.get(0)); // Tambahkan elemen DOM kloning
                    }
                });

                // Set rowspan untuk grup terakhir setelah loop selesai
                if (currentGroup !== '' && startIndex < rowsToAppend.length) {
                    const firstRowOfLastGroup = $(rowsToAppend[startIndex]);
                    firstRowOfLastGroup.find('td:eq(2)').attr('rowspan', groupCount).addClass(
                        'text-center align-middle');
                }

                // Tambahkan semua baris yang telah diproses ke tbody
                tbody.append(rowsToAppend);
            }


            var table = $('#ruang-table').DataTable({
                data: ruangData, // Pastikan 'ruangData' didefinisikan sebelum ini
                columns: [
                    // Kolom nomor urut dihapus
                    {
                        data: 'nama_ruang'
                    },
                    {
                        data: 'kapasitas',
                        render: function(data, type, row) {
                            return data !== null && data !== undefined ? data : '-';
                        }
                    },
                    {
                        data: 'jenis_ruang',
                        render: function(data, type, row) {
                            // Render untuk membuat isi sel, tetapi tampilan akan diatur oleh groupByJenisRuang
                            const predefinedOptions = ['Teori', 'Praktikum',
                                'Laboratorium', 'Studio', 'Aula', 'Lainnya'
                            ];
                            return predefinedOptions.includes(data) ? data : (data || '-');
                        }
                    },
                    {
                        data: null, // Kolom aksi (Edit/Hapus) sekarang berada di indeks 3
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json' // Bahasa Indonesia
                },
                order: [
                    [2, 'asc']
                ], // Urutkan berdasarkan kolom jenis_ruang (indeks 2)
                initComplete: function(settings, json) {
                    const api = new $.fn.dataTable.Api(settings);
                    groupByJenisRuang(api); // Panggil grouping setelah inisialisasi
                    $('#tableLoader').addClass('hidden');
                },
                drawCallback: function(settings) {
                    const api = new $.fn.dataTable.Api(settings);
                    // Panggil fungsi grouping setiap kali tabel digambar ulang
                    // Ini mencakup inisialisasi, pencarian, pengurutan, dan perubahan halaman
                    groupByJenisRuang(api);
                }
            });


            // Reset form
            $('#resetBtn').click(function() {
                $('#ruangForm')[0].reset();
                $('#ruang_id').val('');
                // Sembunyikan input lainnya setelah reset
                $('#lainnyaContainer').addClass('d-none');
                $('#jenis_ruang_lainnya').prop('disabled', true).prop('required', false).val('');
                $('.error-text').text(''); // Hapus pesan error
                $('#saveBtn').prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Simpan'
                );
            });

            // Submit form create
            $('#ruangForm').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                // Jika jenis_ruang adalah 'Lainnya', pastikan input jenis_ruang_lainnya tidak kosong
                const jenisRuang = $('#jenis_ruang').val();
                let formData = $(this).serialize();

                if (jenisRuang === 'Lainnya') {
                    const jenisRuangLainnya = $('#jenis_ruang_lainnya').val().trim();
                    if (!jenisRuangLainnya) {
                        $('#jenis_ruang_lainnya_error').text(
                            'Jenis ruang spesifik harus diisi jika memilih Lainnya.');
                        return; // Hentikan proses submit
                    }
                    // Tambahkan nilai jenis_ruang_lainnya ke formData
                    // Kita tetap kirim jenis_ruang sebagai 'Lainnya' dan tambahkan field lainnya
                    // Di backend, Anda bisa memutuskan untuk menyimpan atau menggabungkan nilainya
                    // Misalnya, simpan 'jenis_ruang' sebagai 'Lainnya' dan 'jenis_ruang_lainnya' sebagai detailnya
                    // Atau, simpan 'jenis_ruang' sebagai nilai dari 'jenis_ruang_lainnya' jika 'Lainnya' dipilih
                    // Contoh berikut mengikuti pendekatan kedua: mengganti nilai jika 'Lainnya'
                    formData = $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});
                    if (formData.jenis_ruang === 'Lainnya' && formData.jenis_ruang_lainnya) {
                        formData.jenis_ruang = formData.jenis_ruang_lainnya;
                        // Hapus field 'jenis_ruang_lainnya' jika tidak ingin dikirim ke backend
                        // delete formData.jenis_ruang_lainnya;
                    }
                    formData = $.param(formData); // Kembalikan ke string format
                } else {
                    formData = $(this).serialize(); // Gunakan serialize biasa jika bukan 'Lainnya'
                }

                // Nonaktifkan tombol dan tampilkan loader
                $('#saveBtn').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('ruang.store') }}",
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
                $.get("{{ route('ruang.show', '') }}/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Ruang Kuliah');
                            $('#ruang_id_modal').val(data.data.id);
                            $('#nama_ruang_modal').val(data.data.nama_ruang);
                            $('#kapasitas_modal').val(data.data.kapasitas);

                            const jenisRuangValue = data.data.jenis_ruang;
                            // Daftar opsi predefined yang ada di dropdown
                            const predefinedOptions = ['Teori', 'Praktikum', 'Laboratorium',
                                'Studio', 'Aula', 'Lainnya'
                            ];

                            if (predefinedOptions.includes(jenisRuangValue)) {
                                // Jika nilainya adalah predefined option, pilih dropdown sesuai nilai itu
                                $('#jenis_ruang_modal').val(jenisRuangValue).trigger(
                                    'change'); // Trigger change untuk logika tampilan input
                                // Kosongkan input lainnya, karena tidak digunakan
                                $('#jenis_ruang_lainnya_modal').val('');
                            } else {
                                // Jika nilainya BUKAN predefined option (artinya ini adalah nilai spesifik seperti 'Laboratorium Anatomi'),
                                // maka atur dropdown ke 'Lainnya' dan tampilkan input teksnya
                                $('#jenis_ruang_modal').val('Lainnya').trigger(
                                    'change'); // Trigger change untuk memicu logika tampilan input
                                // Isi input teks dengan nilai spesifik yang sebelumnya disimpan
                                $('#jenis_ruang_lainnya_modal').val(jenisRuangValue);
                            }

                            // Hapus pesan error sebelumnya
                            $('.error-text').text('');
                            $('#modalRuang').modal('show');
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
            $('#ruangFormModal').on('submit', function(e) {
                e.preventDefault();

                // Hapus pesan error sebelumnya
                $('.error-text').text('');

                const id = $('#ruang_id_modal').val();
                let formData = $(this).serialize();

                // Jika jenis_ruang adalah 'Lainnya', pastikan input jenis_ruang_lainnya tidak kosong
                const jenisRuang = $('#jenis_ruang_modal').val();
                if (jenisRuang === 'Lainnya') {
                    const jenisRuangLainnya = $('#jenis_ruang_lainnya_modal').val().trim();
                    if (!jenisRuangLainnya) {
                        $('#jenis_ruang_lainnya_error').text(
                            'Jenis ruang spesifik harus diisi jika memilih Lainnya.');
                        return; // Hentikan proses submit
                    }
                    // Sama seperti di form create, sesuaikan formData jika 'Lainnya' dipilih
                    formData = $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});
                    if (formData.jenis_ruang === 'Lainnya' && formData.jenis_ruang_lainnya) {
                        formData.jenis_ruang = formData.jenis_ruang_lainnya;
                        // Hapus field 'jenis_ruang_lainnya' jika tidak ingin dikirim ke backend
                        // delete formData.jenis_ruang_lainnya;
                    }
                    formData = $.param(formData); // Kembalikan ke string format
                } else {
                    formData = $(this).serialize(); // Gunakan serialize biasa jika bukan 'Lainnya'
                }

                // Nonaktifkan tombol dan tampilkan loader (opsional di modal)
                $('#saveBtnModal').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('ruang.update', '') }}/" + id,
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
                                $('#modalRuang').modal('hide');
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
                    text: "Data ruang ini akan dihapus secara permanen!",
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
                            url: "{{ route('ruang.destroy', '') }}/" + id,
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
