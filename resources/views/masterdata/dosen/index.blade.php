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
                            <i class="fas fa-list me-2 text-primary"></i>Daftar Dosen
                        </h3>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Dosen
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
