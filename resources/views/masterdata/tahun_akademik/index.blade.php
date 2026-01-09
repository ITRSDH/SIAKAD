@extends('layouts.index')
@section('title', 'Tahun Akademik & Semester')

@section('content')
    <div class="page-inner">

        {{-- HEADER --}}
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-1">Tahun Akademik & Semester</h3>
            <p class="text-muted mb-0">
                Pengaturan tahun akademik dan semester aktif untuk seluruh proses akademik
            </p>
        </div>

        {{-- INFO NOTE --}}
        <div class="alert alert-info shadow-sm">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-info-circle me-1"></i> Informasi Penting
            </h6>
            <ul class="mb-0 small">
                <li>✔ Sistem hanya mengizinkan <b>1 Tahun Akademik Aktif</b></li>
                <li>✔ Semester otomatis mengikuti pola <b>Ganjil → Genap</b></li>
                <li>✔ Semester Aktif menentukan KRS, nilai, dan perkuliahan</li>
                <li>⚠️ Perubahan status berdampak langsung ke sistem akademik</li>
            </ul>
        </div>

        {{-- ACTION BAR --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">
                Total data: <b>{{ count($data) }}</b> tahun akademik
            </span>

            <a href="{{ route('tahun-akademik.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Tahun Akademik
            </a>
        </div>

        {{-- EMPTY STATE --}}
        @if (count($data) === 0)
            <div class="card border-0 shadow-sm text-center p-5">
                <h5 class="fw-bold mb-2">Belum Ada Tahun Akademik & Semester</h5>
                <p class="text-muted mb-3">
                    Silakan tambahkan tahun akademik untuk memulai pengaturan sistem
                </p>
                <a href="{{ route('tahun-akademik.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Tahun Akademik & Semester
                </a>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif



        {{-- LIST DATA --}}
        @foreach ($data as $item)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold mb-1">
                                Tahun Akademik {{ $item['tahun_akademik'] }}
                            </h5>
                            <small class="text-muted">
                                Dibuat pada {{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y') }}
                            </small>
                        </div>

                        {{-- STATUS TAHUN --}}
                        @if ($item['status_aktif'])
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <form method="POST" action="{{ route('tahun-akademik.tahun-aktif', $item['id']) }}"
                                class="form-activate-tahun">
                                @csrf @method('PUT')
                                <button type="button" class="btn btn-outline-success btn-sm btn-activate-tahun">
                                    Aktifkan Tahun Ini
                                </button>
                            </form>
                        @endif
                    </div>

                    <hr>

                    {{-- SEMESTER --}}
                    <h6 class="fw-semibold mb-2">Daftar Semester</h6>

                    <div class="row">
                        @foreach ($item['semester'] as $i => $smt)
                            <div class="col-md-6 mb-3">
                                <div
                                    class="border rounded p-3 h-100
                                {{ $smt['status'] === 'Aktif' ? 'border-primary bg-light' : '' }}">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="fw-bold">
                                                {{ $smt['nama_semester'] }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($smt['tanggal_mulai'])->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($smt['tanggal_selesai'])->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                            </small>

                                        </div>

                                        <div class="text-end">
                                            @if ($smt['status'] === 'Aktif')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-star me-1"></i>Semester Aktif
                                                </span>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('tahun-akademik.semester-aktif', $smt['id']) }}"
                                                    class="form-activate-semester">
                                                    @csrf @method('PUT')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary btn-activate-semester">
                                                        Jadikan Aktif
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tahun-akademik.edit', $item['id']) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>

                        <button class="btn btn-danger btn-sm btn-delete"
                            data-url="{{ route('tahun-akademik.destroy', $item['id']) }}">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </div>

                </div>
            </div>
        @endforeach

        {{-- FORM DELETE --}}
        <form id="delete-form" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>

    </div>
@endsection

@push('scripts-custom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // DELETE
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.dataset.url;

                Swal.fire({
                    title: 'Hapus Tahun Akademik & Semester?',
                    text: 'Seluruh semester di dalamnya akan ikut terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form')
                            .setAttribute('action', url);
                        document.getElementById('delete-form').submit();
                    }
                });
            });
        });

        // AKTIFKAN TAHUN
        document.querySelectorAll('.btn-activate-tahun').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Aktifkan Tahun Akademik?',
                    text: 'Tahun akademik lain akan otomatis dinonaktifkan.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Aktifkan',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });

        // AKTIFKAN SEMESTER
        document.querySelectorAll('.btn-activate-semester').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Aktifkan Semester?',
                    text: 'Semester aktif sebelumnya akan ditandai selesai.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Aktifkan',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endpush
