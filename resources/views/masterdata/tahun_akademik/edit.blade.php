@extends('layouts.index')

@section('title', 'Edit Tahun Akademik & Semester')

@section('content')
    <div class="page-inner">

        {{-- HEADER --}}
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-1 m-2">Edit Tahun Akademik & Semester</h3>
            <p class="text-muted mb-0 m-2">
                Perbarui periode semester untuk tahun akademik yang dipilih
            </p>
        </div>

        {{-- INFO NOTE --}}
        <div class="alert alert-warning shadow-sm">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i> Perhatian
            </h6>
            <ul class="mb-0 small">
                <li>✔ Nama semester <b>tidak dapat diubah</b>.</li>
                <li>✔ Perubahan tanggal semester akan mempengaruhi jadwal akademik.</li>
                <li>⚠️ Pastikan tidak mengganggu proses perkuliahan yang sedang berjalan.</li>
            </ul>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('tahun-akademik.update', $data['id']) }}">
            @csrf
            @method('PUT')

            {{-- INFO TAHUN --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <span class="badge bg-primary me-2">1</span>
                        Informasi Tahun Akademik
                    </h5>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control"
                                value="{{ $data['tahun_akademik'] }}" required>
                            <small class="text-muted">
                                Identitas periode akademik
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEMESTER --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <span class="badge bg-primary me-2">2</span>
                        Pengaturan Semester
                    </h5>

                    <div class="row">
                        @foreach ($data['semester'] as $i => $smt)
                            <div class="col-md-6 mb-3">
                                <div
                                    class="border rounded p-3 h-100
                            {{ $smt['status'] === 'Aktif' ? 'border-primary bg-light' : '' }}">

                                    <input type="hidden" name="semester[{{ $i }}][id]"
                                        value="{{ $smt['id'] }}">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-semibold mb-0">
                                            {{ $smt['nama_semester'] }}
                                        </h6>

                                        @if ($smt['status'] === 'Aktif')
                                            <span class="badge bg-primary">Sedang Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="semester[{{ $i }}][tanggal_mulai]"
                                            class="form-control" value="{{ $smt['tanggal_mulai'] }}" required>
                                    </div>

                                    <div>
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="semester[{{ $i }}][tanggal_selesai]"
                                            class="form-control" value="{{ $smt['tanggal_selesai'] }}" required>
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        Perubahan tanggal akan diterapkan langsung
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light">
                    Batal
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
@endsection
