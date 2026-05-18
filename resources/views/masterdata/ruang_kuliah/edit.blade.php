@extends('layouts.index')
@section('title', 'Edit Ruang Kuliah')

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
                    <a href="{{ route('ruang-kuliah.index') }}">Ruang Kuliah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ruang-kuliah.edit', $ruangKuliah['id']) }}">Edit Ruang Kuliah</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Edit Ruang Kuliah</h4>
                            <a href="{{ route('ruang-kuliah.index') }}" class="btn btn-secondary">
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

                        <form id="formEdit" method="POST" action="{{ route('ruang-kuliah.update', $ruangKuliah['id']) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kode_ruang" class="form-label">Kode Ruang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_ruang" name="kode_ruang" value="{{ $ruangKuliah['kode_ruang'] ?? '' }}" required>
                                        @error('kode_ruang')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_ruang" class="form-label">Nama Ruang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_ruang" name="nama_ruang" value="{{ $ruangKuliah['nama_ruang'] ?? '' }}" required>
                                        @error('nama_ruang')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="{{ $ruangKuliah['kapasitas'] ?? '' }}" min="1" required>
                                        @error('kapasitas')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gedung" class="form-label">Gedung <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="gedung" name="gedung" value="{{ $ruangKuliah['gedung'] ?? '' }}" required>
                                        @error('gedung')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lantai" class="form-label">Lantai <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="lantai" name="lantai" value="{{ $ruangKuliah['lantai'] ?? '' }}" min="1" required>
                                        @error('lantai')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ruang-kuliah.index') }}" class="btn btn-secondary me-2">
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
            // Number input validation
            $('input[type="number"]').on('input', function() {
                if ($(this).val() < 1) {
                    $(this).val(1);
                }
            });
        });
    </script>
@endpush