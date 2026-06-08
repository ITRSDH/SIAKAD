@extends('layouts.index')
@section('title', 'Workspace Pembimbing Akademik')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Workspace Pembimbing Akademik</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.pembimbing-akademik') }}">Workspace Pembimbing Akademik</a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.pembimbing-akademik') }}">Bimbingan KRS</a></li>
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
                        <h4 class="card-title mb-0">Alur Kerja Pembimbing Akademik</h4>
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

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">KRS Pending Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Semester</th>
                                        <th class="text-center">SKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentPending as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}
                                                </div>
                                                <div class="text-muted small">{{ $item['mahasiswa']['nim'] ?? '-' }}</div>
                                            </td>
                                            <td>{{ $item['semester']['nama_semester'] ?? '-' }}</td>
                                            <td class="text-center">{{ $item['total_sks'] ?? 0 }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada KRS pending.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Mahasiswa Bimbingan</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse ($recentBimbingan as $item)
                                <div class="list-group-item">
                                    <div class="fw-semibold p-2">{{ $item['nama_mahasiswa'] ?? '-' }}</div>
                                    <div class="text-muted small p-2">
                                        {{ $item['nim'] ?? '-' }} -
                                        ({{ $item['prodi']['jenjang_pendidikan'] ?? ($item['prodi'] ?? '-') }})
                                        {{ $item['prodi']['nama_prodi'] ?? ($item['prodi'] ?? '-') }}</div>
                                </div>
                            @empty
                                <div class="alert alert-warning mb-0">
                                    Belum ada mahasiswa bimbingan yang terdeteksi pada akun ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Snapshot Approval</h4>
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
                                        <td>Total Mahasiswa Wali</td>
                                        <td class="text-center">
                                            {{ number_format((int) ($statistics['total_mahasiswa_wali'] ?? count($mahasiswaBimbingan))) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pending Approval</td>
                                        <td class="text-center">
                                            {{ number_format((int) ($statistics['pending_approval'] ?? count($pendingKrs))) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Approved Semester Ini</td>
                                        <td class="text-center">
                                            {{ number_format((int) ($statistics['approved_this_semester'] ?? 0)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Revisi Semester Ini</td>
                                        <td class="text-center">
                                            {{ number_format((int) ($statistics['revised_this_semester'] ?? 0)) }}</td>
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
