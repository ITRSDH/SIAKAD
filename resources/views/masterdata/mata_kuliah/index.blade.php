@extends('layouts.index')
@section('title', 'Daftar Mata Kuliah')

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
                    <a href="#">Mata Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>Daftar Mata Kuliah
                        </h3>
                        <a href="{{ route('mata-kuliah.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelompok Berdasarkan Semester, Prodi, dan Kurikulum -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2 text-info"></i>Kelompok Mata Kuliah per Semester
                        </h3>
                    </div>
                    <div class="card-body">
                        @forelse($groupedMataKuliah as $group)
                            <div class="mb-4 border rounded p-3 bg-light">
                                <!-- Informasi Prodi dan Kurikulum -->
                                <div class="mb-2">
                                    <span class="badge bg-info">Prodi: {{ $group['prodi'] }}</span>
                                    <span class="badge bg-secondary">Kurikulum: {{ $group['kurikulum'] }}</span>
                                    <span class="badge bg-success">Semester: {{ $group['semester'] }}</span>
                                </div>

                                @php
                                    $firstMk = $group['mata_kuliah'][0] ?? null;
                                @endphp

                                @if ($firstMk)
                                    <h5 class="mb-3">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        {{ $group['prodi'] }} - {{ $group['kurikulum'] }} - Semester
                                        {{ $group['semester'] }}
                                        <div class="float-end">
                                            <a href="{{ route('mata-kuliah.edit', ['semester' => $group['semester']]) }}?id_kurikulum={{ $firstMk['id_kurikulum'] }}"
                                                class="btn btn-sm btn-warning me-1">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-semester-btn"
                                                data-semester="{{ $group['semester'] }}"
                                                data-kurikulum-id="{{ $firstMk['id_kurikulum'] }}"
                                                data-prodi="{{ $group['prodi'] }}"
                                                data-kurikulum="{{ $group['kurikulum'] }}">
                                                <i class="fas fa-trash me-1"></i> Hapus Semester
                                            </button>
                                        </div>
                                    </h5>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="5%" rowspan="2" class="align-middle text-center">NO
                                                    </th>
                                                    <th width="15%" rowspan="2" class="align-middle text-center">KODE
                                                    </th>
                                                    <th width="35%" rowspan="2" class="align-middle text-center">MATA
                                                        KULIAH</th>
                                                    <th colspan="4" class="text-center">SKS</th>
                                                    </th>
                                                    <th width="5%" rowspan="2" class="align-middle text-center">AKSI
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">T</th>
                                                    <th class="text-center">P</th>
                                                    <th class="text-center">K</th>
                                                    <th class="text-center">JUMLAH</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $no = 1;
                                                    $total_t = 0;
                                                    $total_p = 0;
                                                    $total_k = 0;
                                                    $total_jumlah = 0;
                                                @endphp

                                                @foreach ($group['mata_kuliah'] as $mk)
                                                    @php
                                                        $t = $mk['teori'] ?? 0;
                                                        $p = $mk['praktikum'] ?? 0;
                                                        $k = $mk['klinik'] ?? 0;
                                                        $jumlah = $mk['sks'] ?? $t + $p + $k;

                                                        $total_t += $t;
                                                        $total_p += $p;
                                                        $total_k += $k;
                                                        $total_jumlah += $jumlah;
                                                    @endphp

                                                    <tr>
                                                        <td class="text-center">{{ $no++ }}</td>
                                                        <td>{{ $mk['kode_mk'] }}</td>
                                                        <td>{{ $mk['nama_mk'] }}</td>
                                                        <td class="text-center">{{ $t }}</td>
                                                        <td class="text-center">{{ $p }}</td>
                                                        <td class="text-center">{{ $k }}</td>
                                                        <td class="text-center">{{ $jumlah }}</td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-single-btn"
                                                                data-id="{{ $mk['id'] }}"
                                                                data-nama="{{ $mk['nama_mk'] }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <!-- Baris Total -->
                                                <tr class="fw-bold bg-light">
                                                    <td colspan="3" class="text-center">JUMLAH</td>
                                                    <td class="text-center">{{ $total_t }}</td>
                                                    <td class="text-center">{{ $total_p }}</td>
                                                    <td class="text-center">{{ $total_k }}</td>
                                                    <td class="text-center">{{ $total_jumlah }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">Tidak ada mata kuliah dalam semester ini.</p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="lead">Tidak ada data mata kuliah.</p>
                                <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
                                </a>
                            </div>
                        @endforelse
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
            // Delete action untuk semester
            $('.delete-semester-btn').on('click', function() {
                const semester = $(this).data('semester');
                const idKurikulum = $(this).data('kurikulum-id');
                const prodi = $(this).data('prodi');
                const kurikulum = $(this).data('kurikulum');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Semua mata kuliah di semester ${semester} untuk Prodi: ${prodi} dan Kurikulum: ${kurikulum}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('mata-kuliah.destroy', ['semester' => '__SEMESTER__']) }}?id_kurikulum=${idKurikulum}`
                                .replace('__SEMESTER__', semester),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghapus.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Delete action untuk satu mata kuliah
            $('.delete-single-btn').on('click', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Mata kuliah: "${nama}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('mata-kuliah.destroy-single', '__ID__') }}`
                                .replace('__ID__', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghapus.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
