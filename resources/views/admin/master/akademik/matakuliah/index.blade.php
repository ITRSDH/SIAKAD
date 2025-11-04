@extends('admin.layouts.index')
@section('title', 'Daftar Mata Kuliah')
@push('styles-custom')
    <style>
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: inherit;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .card-body {
            position: relative;
        }

        .loader-overlay.hidden {
            display: none;
        }

        /* Gaya untuk header semester */
        .semester-header {
            background-color: #f8f9fa;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        /* Gaya untuk baris total */
        .total-row {
            background-color: #e9ecef;
            /* Warna latar belakang untuk baris total */
            font-weight: bold;
        }

        /* Gaya untuk pesan default */
        .default-message {
            text-align: center;
            padding: 40px 0;
            font-size: 1.1em;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Mata Kuliah</h3>
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
                    <a href="{{ route('matakuliah.index') }}">Mata Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('matakuliah.index') }}">List Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Mata Kuliah
                        </h3>
                        <a href="{{ route('matakuliah.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Mata Kuliah
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="prodiFilter" class="form-label">Filter Berdasarkan Prodi:</label>
                                <select id="prodiFilter" class="form-control">
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kurikulumFilter" class="form-label">Filter Berdasarkan Kurikulum:</label>
                                <select id="kurikulumFilter" class="form-control" disabled>
                                    <option value="">Pilih Kurikulum (Pilih Prodi Dahulu)</option>
                                </select>
                            </div>
                        </div>

                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="table-responsive">
                            <div id="grouped-table-container">
                                <!-- Tampilkan pesan default saat halaman dimuat -->
                                <div class="default-message">
                                    Silakan pilih Program Studi terlebih dahulu.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil data dari variabel PHP yang dilewatkan ke view
            var mkData = @json($mk);
            var kurikulumData = @json($kurikulum);

            // Fungsi untuk mengisi dropdown kurikulum berdasarkan prodi_id
            function populateKurikulumFilter(prodiId) {
                const kurikulumFilter = $('#kurikulumFilter');
                kurikulumFilter.empty().append('<option value="">Pilih Kurikulum...</option>');

                if (prodiId) {
                    // Asumsikan kurikulum memiliki field 'id_prodi' sebagai foreign key ke prodi
                    const filteredKurikulum = kurikulumData.filter(k => k.id_prodi == prodiId);
                    filteredKurikulum.forEach(k => {
                        kurikulumFilter.append(
                            `<option value="${k.id}">${k.nama_kurikulum}</option>`);
                    });
                    kurikulumFilter.prop('disabled', false);
                } else {
                    kurikulumFilter.append('<option value="">Pilih Kurikulum (Pilih Prodi Dahulu)</option>');
                    kurikulumFilter.prop('disabled', true);
                }
            }

            // Fungsi untuk menampilkan pesan default
            function showDefaultMessage() {
                $('#grouped-table-container').html(`
                    <div class="default-message">
                        Silakan pilih Program Studi terlebih dahulu.
                    </div>
                `);
                $('#tableLoader').addClass('hidden');
            }

            // Fungsi untuk mengelompokkan data dan menampilkan tabel
            function renderTable(data) {
                // Jika data kosong setelah filter, tampilkan pesan berbeda
                if (data.length === 0) {
                    $('#grouped-table-container').html(`
                        <div class="default-message">
                            Tidak ada data mata kuliah untuk filter yang dipilih.
                        </div>
                    `);
                    $('#tableLoader').addClass('hidden');
                    return;
                }

                // Kelompokkan data berdasarkan semester_rekomendasi
                const groupedBySemester = data.reduce((acc, item) => {
                    const semester = item.semester_rekomendasi;
                    if (!acc[semester]) {
                        acc[semester] = [];
                    }
                    acc[semester].push(item);
                    return acc;
                }, {});

                // Urutkan semester
                const sortedSemesters = Object.keys(groupedBySemester).sort((a, b) => parseInt(a) - parseInt(b));

                let tableHtml = '';
                sortedSemesters.forEach(semester => {
                    const mksInSemester = groupedBySemester[semester];

                    // Hitung total untuk semester ini
                    const totals = mksInSemester.reduce((acc, item) => {
                        acc.sks += parseInt(item.sks) || 0;
                        acc.teori += parseInt(item.teori) || 0;
                        acc.seminar += parseInt(item.seminar) || 0;
                        acc.praktikum += parseInt(item.praktikum) || 0;
                        acc.praktek_klinik += parseInt(item.praktek_klinik) || 0;
                        return acc;
                    }, {
                        sks: 0,
                        teori: 0,
                        seminar: 0,
                        praktikum: 0,
                        praktek_klinik: 0
                    });

                    tableHtml += `<h5 class="semester-header">Semester ${semester}</h5>`;
                    tableHtml += `
                        <table class="table table-bordered table-striped table-grouped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No.Kode</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th>SKS</th>
                                    <th>T</th>
                                    <th>S</th>
                                    <th>P</th>
                                    <th>PK</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    mksInSemester.forEach((row, index) => {
                        // Cari nama kurikulum
                        const kur = kurikulumData.find(k => k.id === row.id_kurikulum);
                        const namaKurikulum = kur ? kur.nama_kurikulum : 'N/A';
                        tableHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${row.kode_mk}</td>
                                <td>${row.nama_mk}</td>
                                <td>${row.jenis}</td>
                                <td>${row.deskripsi || '-'}</td>
                                <td>${row.sks}</td>
                                <td>${row.teori}</td>
                                <td>${row.seminar}</td>
                                <td>${row.praktikum}</td>
                                <td>${row.praktek_klinik}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="/matakuliah/${row.id}/edit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                   </div>
                                </td>
                            </tr>
                        `;
                    });
                    // Tambahkan baris total
                    tableHtml += `
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="5" class="text-center">Total Semester ${semester}</td>
                                    <td>${totals.sks}</td>
                                    <td>${totals.teori}</td>
                                    <td>${totals.seminar}</td>
                                    <td>${totals.praktikum}</td>
                                    <td>${totals.praktek_klinik}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    `;
                });

                $('#grouped-table-container').html(tableHtml);
                // Sembunyikan loader setelah selesai render
                $('#tableLoader').addClass('hidden');
            }

            // Tidak render awal dengan semua data, hanya tampilkan pesan default
            showDefaultMessage();

            // Event listener untuk filter prodi
            $('#prodiFilter').on('change', function() {
                const selectedProdiId = $(this).val();
                // Reset dan isi ulang filter kurikulum
                populateKurikulumFilter(selectedProdiId);

                // Jika prodi dipilih, filter data berdasarkan prodi
                let filteredData = mkData;
                if (selectedProdiId) {
                    // Asumsikan bahwa data mata kuliah memiliki informasi kurikulum,
                    // dan kurikulum memiliki id_prodi. Kita perlu menghubungkan data ini.
                    filteredData = mkData.filter(item => {
                        const kur = kurikulumData.find(k => k.id === item.id_kurikulum);
                        return kur && kur.id_prodi == selectedProdiId;
                    });
                    // Tampilkan loader saat filter
                    $('#tableLoader').removeClass('hidden');
                    // Render ulang tabel dengan data yang telah difilter
                    renderTable(filteredData);
                } else {
                    // Jika prodi direset, kembalikan ke pesan default
                    showDefaultMessage();
                    // Juga reset filter kurikulum
                    $('#kurikulumFilter').val('').prop('disabled', true);
                }
            });

            // Event listener untuk filter kurikulum
            $('#kurikulumFilter').on('change', function() {
                const selectedKurikulumId = $(this).val();
                let filteredData = mkData;

                // Jika kurikulum dipilih, filter data berdasarkan kurikulum
                if (selectedKurikulumId) {
                    filteredData = mkData.filter(item => item.id_kurikulum == selectedKurikulumId);
                    // Tampilkan loader saat filter
                    $('#tableLoader').removeClass('hidden');
                    // Render ulang tabel dengan data yang telah difilter
                    renderTable(filteredData);
                } else {
                    // Jika kurikulum direset, kembali ke filter berdasarkan prodi saja
                    const selectedProdiId = $('#prodiFilter').val();
                    if (selectedProdiId) {
                        filteredData = mkData.filter(item => {
                            const kur = kurikulumData.find(k => k.id === item.id_kurikulum);
                            return kur && kur.id_prodi == selectedProdiId;
                        });
                        // Tampilkan loader saat filter
                        $('#tableLoader').removeClass('hidden');
                        // Render ulang tabel dengan data yang telah difilter
                        renderTable(filteredData);
                    } else {
                        // Jika tidak ada prodi dipilih, kembali ke pesan default
                        showDefaultMessage();
                    }
                }
            });

            // Delete
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#tableLoader').removeClass('hidden');
                        $.ajax({
                            url: "{{ route('matakuliah.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success')
                                        .then(() => {
                                            // Temukan dan hapus item dari data JS
                                            mkData = mkData.filter(item => item
                                                .id != id);
                                            // Render ulang tabel dengan data yang sudah diperbarui (jika prodi masih dipilih)
                                            const selectedProdiId = $(
                                                '#prodiFilter').val();
                                            if (selectedProdiId) {
                                                // Filter ulang data MK setelah penghapusan
                                                let updatedFilteredData = mkData
                                                    .filter(item => {
                                                        const kur =
                                                            kurikulumData.find(
                                                                k => k.id ===
                                                                item
                                                                .id_kurikulum);
                                                        return kur && kur
                                                            .id_prodi ==
                                                            selectedProdiId;
                                                    });
                                                // Cek filter kurikulum juga
                                                const selectedKurikulumId = $(
                                                    '#kurikulumFilter').val();
                                                if (selectedKurikulumId) {
                                                    updatedFilteredData =
                                                        updatedFilteredData.filter(
                                                            item => item
                                                            .id_kurikulum ==
                                                            selectedKurikulumId);
                                                }
                                                renderTable(updatedFilteredData);
                                            } else {
                                                // Jika tidak ada prodi dipilih, kembali ke pesan default
                                                showDefaultMessage();
                                            }
                                        });
                                } else {
                                    Swal.fire('Gagal!', response.message ||
                                        'Gagal menghapus data.', 'error');
                                }
                            },
                            error: function(xhr) {
                                console.error('AJAX Error:', xhr);
                                let errorMessage = 'Gagal menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire('Gagal!', errorMessage, 'error');
                            },
                            complete: function() {
                                // Loader disembunyikan di renderTable atau showDefaultMessage
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
