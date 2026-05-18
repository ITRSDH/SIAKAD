@extends('layouts.index')
@section('title', 'Edit Periode KRS')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Master Data</h3>
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
                    <a href="{{ route('periode-krs.index') }}">Periode KRS</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('periode-krs.edit', $periodeKRS['id']) }}">Edit Periode KRS</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Edit Periode KRS</h4>
                            <a href="{{ route('periode-krs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="formEdit" method="POST" action="{{ route('periode-krs.update', $periodeKRS['id']) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="id_semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select" id="id_semester" name="id_semester" required>
                                            <option value="">Pilih Semester</option>
                                            @if(isset($semester) && count($semester) > 0)
                                                @foreach($semester as $item)
                                                    <option value="{{ $item['id'] }}" {{ ($periodeKRS['id_semester'] ?? '') == $item['id'] ? 'selected' : '' }}>
                                                        {{ $item['kode_semester'] }} - {{ $item['nama_semester'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('id_semester')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ \Carbon\Carbon::parse($periodeKRS['tanggal_mulai'] ?? null)->format('Y-m-d') }}" required>
                                        @error('tanggal_mulai')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ \Carbon\Carbon::parse($periodeKRS['tanggal_selesai'] ?? null)->format('Y-m-d') }}" required>
                                        @error('tanggal_selesai')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="draft" {{ ($periodeKRS['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="aktif" {{ ($periodeKRS['status'] ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="ditutup" {{ ($periodeKRS['status'] ?? '') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Opsional: Masukkan catatan tambahan">{{ $periodeKRS['catatan'] ?? '' }}</textarea>
                                        @error('catatan')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('periode-krs.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(document).ready(function() {
            // Auto-hide error messages setelah 5 detik
            function autoHideErrors() {
                setTimeout(function() {
                    $('.alert-danger').fadeOut('slow');
                }, 5000);
            }
            
            // Tampilkan error ke UI dengan format yang lebih baik
            function showErrorToUser(message) {
                // Cek apakah ada error container
                let errorContainer = $('#error-container');
                if (errorContainer.length === 0) {
                    // Buat error container jika belum ada
                    errorContainer = $('<div id="error-container" class="mb-3"></div>');
                    $('.card-body').prepend(errorContainer);
                }
                
                // Tampilkan error dengan format yang bagus
                errorContainer.html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                
                // Auto hide setelah 5 detik
                autoHideErrors();
            }
            
            // Date validation
            $('#tanggal_mulai, #tanggal_selesai').on('change', function() {
                const tanggalMulai = new Date($('#tanggal_mulai').val());
                const tanggalSelesai = new Date($('#tanggal_selesai').val());
                
                if (tanggalMulai && tanggalSelesai && tanggalMulai > tanggalSelesai) {
                    $('#tanggal_selesai').val('{{ $periodeKRS['tanggal_selesai'] ?? '' }}');
                    showErrorToUser('Tanggal selesai harus lebih besar atau sama dengan tanggal mulai');
                }
            });

            // Set minimum date untuk tanggal mulai (hari ini)
            const today = new Date().toISOString().split('T')[0];
            $('#tanggal_mulai').attr('min', today);
            $('#tanggal_selesai').attr('min', today);
            
            // Auto-hide existing errors
            autoHideErrors();
        });
    </script>
@endpush