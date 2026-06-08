@extends('layouts.index')
@section('title', 'Workspace BAAK')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Workspace BAAK</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Operasional Akademik</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('akademik.riwayat-studi.index') }}" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-history me-1"></i> Riwayat Studi Historis
            </a>
            <a href="{{ route('users.index', ['role' => 'baak']) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-users-cog me-1"></i> Kelola User BAAK
            </a>
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
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Alur Kerja BAAK</h4>
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
                        <h4 class="card-title mb-0">Snapshot Operasional</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="fw-semibold">Semester Aktif</div>
                            <div class="text-muted small">
                                {{ $semesterAktifLabel !== '-' ? $semesterAktifLabel : 'Belum ada semester aktif' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-semibold">Periode KRS</div>
                            <div class="text-muted small">
                                {{ $periodeAktifLabel !== '-' ? $periodeAktifLabel : 'Belum ada periode KRS aktif' }}
                            </div>
                        </div>
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
                                        <td>KHS</td>
                                        <td class="text-center">{{ number_format($statusSummary['khs']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Batch Import KHS</td>
                                        <td class="text-center">{{ number_format($statusSummary['khs_import'] ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Batch Import Gagal</td>
                                        <td class="text-center">{{ number_format($statusSummary['khs_import_failed'] ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td>KHS Belum Final</td>
                                        <td class="text-center">{{ number_format($statusSummary['khs_draft'] ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transkrip</td>
                                        <td class="text-center">{{ number_format($statusSummary['transkrip']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Yudisium</td>
                                        <td class="text-center">{{ number_format($statusSummary['yudisium']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelulusan</td>
                                        <td class="text-center">{{ number_format($statusSummary['kelulusan']) }}</td>
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
