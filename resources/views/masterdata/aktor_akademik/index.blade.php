@extends('layouts.index')
@section('title', 'Aktor Akademik')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Aktor Akademik</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('aktor-akademik.index') }}">Aktor Akademik</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('aktor-akademik.index') }}">Ringkasan Aktor</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="row">
            @foreach ($summary as $item)
                <div class="col-md-6 col-xl-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-{{ $item['theme'] }} bubble-shadow-small">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">{{ $item['title'] }}</p>
                                        <h4 class="card-title">{{ number_format($item['count']) }}</h4>
                                        <p class="card-text text-muted small mb-3">{{ $item['description'] }}</p>
                                        <a href="{{ $item['action_url'] }}" class="btn btn-sm btn-outline-{{ $item['theme'] }}">
                                            {{ $item['action_label'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Peta Fungsi Aktor Akademik</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Aktor</th>
                                        <th>Fungsi Utama</th>
                                        <th>Workspace</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Dosen</td>
                                        <td>Data induk dosen dan identitas pengajaran.</td>
                                        <td><a href="{{ route('dosen.index') }}">Kelola Dosen</a></td>
                                    </tr>
                                    <tr>
                                        <td>Ketua Program Studi</td>
                                        <td>Penetapan kaprodi pada masing-masing program studi.</td>
                                        <td><a href="{{ route('aktor-akademik.kaprodi') }}">Kelola Kaprodi</a></td>
                                    </tr>
                                    <tr>
                                        <td>Pembimbing Akademik</td>
                                        <td>Penugasan dosen pembimbing akademik dan distribusi mahasiswa bimbingan.</td>
                                        <td><a href="{{ route('aktor-akademik.pembimbing-akademik') }}">Kelola Pembimbing</a></td>
                                    </tr>
                                    <tr>
                                        <td>BAAK</td>
                                        <td>Pengelolaan user operasional akademik berbasis role BAAK.</td>
                                        <td><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Snapshot Cepat</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="fw-semibold">Program Studi dengan Kaprodi</div>
                            <div class="text-muted small">{{ count($kaprodiAssigned) }} dari {{ count($prodi) }} program studi sudah memiliki kaprodi.</div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-semibold">Sebaran Pembimbing Akademik</div>
                            <div class="text-muted small">{{ count($pembimbingAkademik) }} dosen pembimbing akademik menangani {{ number_format($totalMahasiswaBimbingan) }} mahasiswa.</div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-semibold">User BAAK</div>
                            <div class="text-muted small">{{ count($baakUsers) }} akun BAAK siap digunakan untuk operasional administrasi akademik.</div>
                        </div>
                        <div class="alert alert-info mb-0">
                            Halaman ini menjadi pintu kerja `Aktor Akademik`, jadi admin bisa masuk ke aktor yang tepat tanpa harus menebak modul sumbernya.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
