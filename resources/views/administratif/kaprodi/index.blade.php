@extends('layouts.index')
@section('title', 'Workspace Kaprodi')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Workspace Kaprodi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.kaprodi') }}">Workspace Kaprodi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.kaprodi') }}">Mutu Akademik Prodi</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="alert alert-info">
            Halaman ini menjadi pusat kerja `Kaprodi` untuk mengawal mutu prodi, operasional akademik, dan kemajuan akhir studi mahasiswa.
        </div>

        <div class="row">
            @foreach ($kpis as $item)
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
                                        <h4 class="card-title">{{ $item['value'] }}</h4>
                                        <p class="card-text text-muted small mb-0">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Alur Kerja Kaprodi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($workflowGroups as $group)
                                <div class="col-md-6 mb-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="fw-semibold mb-2">{{ $group['title'] }}</div>
                                        <div class="text-muted small mb-3">{{ $group['description'] }}</div>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($group['links'] as $link)
                                                <a href="{{ $link['route'] }}" class="btn btn-sm btn-light text-start">
                                                    {{ $link['label'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Prodi Kelolaan</h4>
                    </div>
                    <div class="card-body">
                        @if (count($managedProdi) > 0)
                            <div class="list-group">
                                @foreach ($managedProdi as $prodi)
                                    <div class="list-group-item">
                                        <div class="fw-semibold">{{ $prodi['nama_prodi'] ?? '-' }}</div>
                                        <div class="text-muted small">
                                            {{ $prodi['kode_prodi'] ?? '-' }} · {{ $prodi['jenjang_pendidikan'] ?? '-' }}
                                        </div>
                                        <div class="text-muted small">
                                            Akreditasi: {{ $prodi['akreditasi'] ?? '-' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Belum ada prodi yang terpetakan langsung ke akun kaprodi ini.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Snapshot Prodi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Area</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kurikulum</td>
                                        <td class="text-center">{{ number_format(count($filteredKurikulum)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas Kuliah</td>
                                        <td class="text-center">{{ number_format(count($filteredKelas)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tugas Akhir</td>
                                        <td class="text-center">{{ number_format(count($filteredTugasAkhir)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Yudisium</td>
                                        <td class="text-center">{{ number_format(count($filteredYudisium)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelulusan</td>
                                        <td class="text-center">{{ number_format(count($filteredKelulusan)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
