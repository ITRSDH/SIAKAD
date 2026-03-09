@extends('layouts.index')
@section('title', 'Detail Capaian')
@push('styles-custom')
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Capaian</h3>
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
                    <a href="{{ route('prodi.index') }}">Detail Capaian</a>
                </li>
            </ul>
        </div>

        {{-- INFO NOTE --}}
        <div class="card shadow-sm border">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title"> Informasi Program Studi</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mata-kuliah.indexProdi') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Kode Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['kode_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['nama_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Akreditasi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['akreditasi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Jenjang Pendidikan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['jenjang_pendidikan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Tahun Berdiri
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['tahun_berdiri'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Gelar Lulusan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['gelar_lulusan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Capaian</h4>
                    </div>

                    <div class="card-body">

                        <!-- NAV TAB -->
                        <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="tab-pl" data-bs-toggle="pill" href="#content-pl"
                                    role="tab" aria-controls="content-pl" aria-selected="true">
                                    Profile Lulusan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="tab-cpl" data-bs-toggle="pill" href="#content-cpl" role="tab"
                                    aria-controls="content-cpl" aria-selected="false">
                                    Capaian Pembelajaran Lulusan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="tab-pl-cpl" data-bs-toggle="pill" href="#content-pl-cpl"
                                    role="tab" aria-controls="content-pl-cpl" aria-selected="false">
                                    Pemetaan PL <i class="fas fa-arrow-right fs-7"></i> CPL
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="tab-cpl-mk" data-bs-toggle="pill" href="#content-cpl-mk"
                                    role="tab" aria-controls="content-cpl-mk" aria-selected="false">
                                    Pemetaan CPL <i class="fas fa-arrow-right fs-7"></i> MK
                                </a>
                            </li>

                        </ul>

                        <!-- TAB CONTENT -->
                        <div class="tab-content mt-3 mb-3" id="line-tabContent">

                            <!-- PROFILE LULUSAN -->
                            <div class="tab-pane fade show active" id="content-pl" role="tabpanel" aria-labelledby="tab-pl">

                                <p>Konten Profile Lulusan</p>

                                @include('masterdata.data_capaian.pl.index')

                            </div>

                            <!-- CPL -->
                            <div class="tab-pane fade" id="content-cpl" role="tabpanel" aria-labelledby="tab-cpl">

                                <p>Konten Capaian Pembelajaran Lulusan</p>

                            </div>

                            <!-- PL -> CPL -->
                            <div class="tab-pane fade" id="content-pl-cpl" role="tabpanel" aria-labelledby="tab-pl-cpl">

                                <p>Konten Pemetaan PL ke CPL</p>

                            </div>

                            <!-- CPL -> MK -->
                            <div class="tab-pane fade" id="content-cpl-mk" role="tabpanel" aria-labelledby="tab-cpl-mk">

                                <p>Konten Pemetaan CPL ke Mata Kuliah</p>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts-custom')
@endpush
