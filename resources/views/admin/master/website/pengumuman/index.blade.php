@extends('layouts.index')
@section('title', 'Pengumuman')
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

        .content-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pengumuman</h3>
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
                    <a href="{{ route('pengumuman.index') }}">Website</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengumuman.index') }}">Pengumuman</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Form Create -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" href="#collapsePengumumanForm" aria-expanded="true"
                        aria-controls="collapsePengumumanForm">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Pengumuman
                        </h3>
                        <div class="card-tools">
                            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
                        </div>
                    </div>
                    <!-- Card Body dengan kelas collapse dan show untuk tampil awal -->
                    <div class="collapse show" id="collapsePengumumanForm">
                        <div class="card-body">
                            <form id="pengumumanForm" name="pengumumanForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="pengumuman_id">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="judul" class="form-label">Judul Pengumuman <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="judul" name="judul"
                                                placeholder="Masukkan judul pengumuman">
                                            <div class="text-danger error-text" id="judul_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="form-label">Kategori <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="kategori" name="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <option value="akademik">Akademik</option>
                                                <option value="administrasi">Administrasi</option>
                                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                                <option value="umum">Umum</option>
                                            </select>
                                            <div class="text-danger error-text" id="kategori_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="tanggal" class="form-label">Tanggal <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                            <div class="text-danger error-text" id="tanggal_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="isi" class="form-label">Isi Pengumuman <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="isi" name="isi" rows="6" placeholder="Masukkan isi pengumuman"></textarea>
                                            <div class="text-danger error-text" id="isi_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" id="saveBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary ms-2">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Data Pengumuman</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="pengumumanTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Isi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengumuman as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['judul'] }}</td>
                                            <td>
                                                @if($item['kategori'])
                                                    <span class="badge bg-info">{{ ucfirst($item['kategori']) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item['tanggal'])
                                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="{{ $item['isi'] }}">
                                                    {{ Str::limit(strip_tags($item['isi']), 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-warning btn-icon edit-btn" 
                                                            data-id="{{ $item['id'] }}" 
                                                            title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger btn-icon delete-btn" 
                                                            data-id="{{ $item['id'] }}" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-inbox"></i> Tidak ada data pengumuman
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalPengumuman" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pengumumanFormModal" name="pengumumanFormModal" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="pengumuman_id_modal">

                        <div class="form-group mb-3">
                            <label for="judul_modal" class="form-label">Judul Pengumuman <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_modal" name="judul"
                                placeholder="Masukkan judul pengumuman">
                            <div class="text-danger error-text" id="judul_modal_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori_modal" class="form-label">Kategori <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="kategori_modal" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="akademik">Akademik</option>
                                <option value="administrasi">Administrasi</option>
                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                <option value="umum">Umum</option>
                            </select>
                            <div class="text-danger error-text" id="kategori_modal_error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="tanggal_modal" class="form-label">Tanggal <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_modal" name="tanggal">
                                    <div class="text-danger error-text" id="tanggal_modal_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="isi_modal" class="form-label">Isi Pengumuman <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_modal" name="isi" rows="6" placeholder="Masukkan isi pengumuman"></textarea>
                            <div class="text-danger error-text" id="isi_modal_error"></div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" id="saveBtnModal" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
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
    <!-- SweetAlert2 CDN untuk production -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#pengumumanTable').DataTable();

            // Edit Function - Use event delegation for dynamic content
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                $.get("/pengumuman/" + id)
                    .done(function(data) {
                        if (data && data.data) {
                            $('#modelHeading').text('Edit Pengumuman');
                            $('#pengumuman_id_modal').val(data.data.id);
                            $('#judul_modal').val(data.data.judul);
                            $('#kategori_modal').val(data.data.kategori);
                            $('#isi_modal').val(data.data.isi);
                            
                            // Format tanggal untuk input date (YYYY-MM-DD)
                            if (data.data.tanggal) {
                                var tanggalDate = new Date(data.data.tanggal);
                                var formattedDate = tanggalDate.toISOString().split('T')[0];
                                $('#tanggal_modal').val(formattedDate);
                            }

                            $('#modalPengumuman').modal('show');
                        } else {
                            Swal.fire('Error', 'Gagal mengambil data pengumuman', 'error');
                        }
                    })
                    .fail(function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                    });
            });

            // Delete Function - Use event delegation for dynamic content
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data pengumuman akan dihapus permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/pengumuman/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    table.row($('button[data-id="' + id + '"]').closest('tr')).remove().draw();
                                    
                                    Swal.fire('Berhasil!', 'Data pengumuman berhasil dihapus', 'success');
                                } else {
                                    Swal.fire('Error', 'Gagal menghapus data pengumuman', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                            }
                        });
                    }
                });
            });

            // Store/Update Form Submit
            $('#pengumumanForm').on('submit', function(e) {
                e.preventDefault();
                
                var url = "{{ route('pengumuman.store') }}";
                var data = {
                    'judul': $('#judul').val(),
                    'kategori': $('#kategori').val(),
                    'tanggal': $('#tanggal').val(),
                    'isi': $('#isi').val(),
                    '_token': '{{ csrf_token() }}'
                };
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Tambah data ke tabel
                            table.row.add(response.data).draw();
                            
                            // Reset form
                            $('#pengumumanForm')[0].reset();
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data pengumuman berhasil ditambahkan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            // Handle validation errors
                            if (response.errors) {
                                // Clear previous errors
                                $('.error-text').text('');
                                
                                // Show validation errors
                                $.each(response.errors, function(key, value) {
                                    var fieldId = key + '_error';
                                    $('#' + fieldId).text(value[0]);
                                });
                            } else {
                                Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
                            }
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.errors) {
                            // Clear previous errors
                            $('.error-text').text('');
                            
                            // Show validation errors
                            $.each(response.errors, function(key, value) {
                                var fieldId = key + '_error';
                                $('#' + fieldId).text(value[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    }
                });
            });

            // Update Form Submit (Modal)
            $('#pengumumanFormModal').on('submit', function(e) {
                e.preventDefault();
                
                var id = $('#pengumuman_id_modal').val();
                var url = '/pengumuman/' + id;
                var data = {
                    'judul': $('#judul_modal').val(),
                    'kategori': $('#kategori_modal').val(),
                    'tanggal': $('#tanggal_modal').val(),
                    'isi': $('#isi_modal').val(),
                    '_token': '{{ csrf_token() }}',
                    '_method': 'PUT'
                };

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        handleUpdateResponse(response);
                    },
                    error: function(xhr) {
                        handleUpdateError(xhr);
                    }
                });
                
                function handleUpdateResponse(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data pengumuman berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Update baris di tabel
                        const row = table.row($('button[data-id="' + id + '"]').closest('tr'));
                        const updatedData = [
                            response.data.judul,
                            response.data.kategori,
                            response.data.tanggal,
                            `<div class="d-flex gap-2">
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${response.data.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${response.data.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`
                        ];
                        row.data(updatedData).draw();
                        
                        $('#modalPengumuman').modal('hide');
                    } else {
                        if (response.errors) {
                            $('.error-text').text('');
                            $.each(response.errors, function(key, value) {
                                var fieldId = key + '_modal_error';
                                $('#' + fieldId).text(value[0]);
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                }
                
                function handleUpdateError(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        $('.error-text').text('');
                        $.each(response.errors, function(key, value) {
                            var fieldId = key + '_modal_error';
                            $('#' + fieldId).text(value[0]);
                        });
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan saat memperbarui data', 'error');
                    }
                }
            });

            // Reset Form
            $('#resetBtn').click(function() {
                $('#pengumumanForm')[0].reset();
                $('.error-text').text('');
            });
        });
    </script>
@endpush
