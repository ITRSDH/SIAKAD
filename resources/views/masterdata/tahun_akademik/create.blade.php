@extends('layouts.index')

@section('title', 'Tambah Tahun Akademik & Semester')

@section('content')
    <div class="page-inner">

        {{-- HEADER --}}
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-1">Tambah Tahun Akademik & Semester</h3>
            <p class="text-muted mb-0">
                Penetapan Tahun Akademik dan periode semester resmi
            </p>
        </div>

        {{-- INFO --}}
        <div class="alert alert-info shadow-sm">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-info-circle me-1"></i> Ketentuan Sistem
            </h6>
            <ul class="mb-0 small">
                <li>✔ Setiap Tahun Akademik memiliki <b>2 Semester</b></li>
                <li>✔ Semester <b>Ganjil & Genap dibuat otomatis</b></li>
                <li>✔ Admin hanya menentukan <b>tanggal semester</b></li>
                <li>⚠️ Data akan digunakan oleh seluruh modul akademik</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('tahun-akademik.store') }}">
            @csrf

            {{-- TAHUN AKADEMIK --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Informasi Tahun Akademik
                    </h5>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" class="form-control" placeholder="Contoh: 2025/2026"
                            required>
                    </div>
                </div>
            </div>

            {{-- SEMESTER GANJIL --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Semester Ganjil
                    </h5>

                    <input type="hidden" name="semester[0][nama_semester]" value="Ganjil">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="semester[0][tanggal_mulai]" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="semester[0][tanggal_selesai]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEMESTER GENAP --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Semester Genap
                    </h5>

                    <input type="hidden" name="semester[1][nama_semester]" value="Genap">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="semester[1][tanggal_mulai]" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="semester[1][tanggal_selesai]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light">
                    Kembali
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Tahun Akademik & Semester
                </button>
            </div>

        </form>
    </div>
@endsection
