@extends('layouts.index')
@section('title', 'Workspace Dosen Pengajar')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Workspace Dosen Pengajar</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.dosen-pengajar') }}">Workspace Dosen Pengajar</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.dosen-pengajar') }}">Kelas Ajar</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

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
                        <h4 class="card-title mb-0">Alur Kerja Dosen Pengajar</h4>
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
                        <h4 class="card-title mb-0">Snapshot Kelas Ajar</h4>
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
                                        <td>Kelas Ajar</td>
                                        <td class="text-center">{{ number_format($kelasCount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Peserta</td>
                                        <td class="text-center">{{ number_format($totalPeserta) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total SKS</td>
                                        <td class="text-center">{{ number_format($totalSks) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Kelas Ajar Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>Kelas</th>
                                <th>Semester</th>
                                <th class="text-center">Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentClasses as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $item['mata_kuliah']['nama_mk'] ?? ($item['mata_kuliah']['nama_mata_kuliah'] ?? ($item['nama_mata_kuliah'] ?? '-')) }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $item['mata_kuliah']['kode_mk'] ?? ($item['mata_kuliah']['kode_mata_kuliah'] ?? ($item['kode_mata_kuliah'] ?? '-')) }}
                                        </div>
                                    </td>
                                    <td>{{ $item['nama_kelas'] ?? ($item['kelas'] ?? '-') }}</td>
                                    <td>{{ is_array($item['semester'] ?? null) ? $item['semester']['nama_semester'] ?? '-' : $item['semester'] ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['peserta_terdaftar'] ?? ($item['jumlah_peserta'] ?? ($item['peserta_count'] ?? 0)) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada kelas ajar yang terdeteksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
