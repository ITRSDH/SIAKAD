@extends('layouts.index')
@section('title', 'Monitoring Akhir Studi')

@php
    $periodeAktif = $periodeWisuda->first(function ($item) {
        return strtolower((string) ($item['status'] ?? '')) === 'dibuka';
    }) ?? $periodeWisuda->first(function ($item) {
        return strtolower((string) ($item['status'] ?? '')) === 'draft';
    });
@endphp

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Akhir Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akhir-studi.monitoring') }}">Monitoring</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        @include('layouts.partials.monitoring.filter-card', [
            'title' => 'Filter Monitoring',
            'description' => 'Saring data akhir studi berdasarkan status tiap tahap atau pencarian umum.',
            'action' => route('akhir-studi.monitoring'),
            'resetRoute' => route('akhir-studi.monitoring'),
            'fields' => [
                [
                    'name' => 'tugas_akhir_status',
                    'label' => 'Tugas Akhir',
                    'type' => 'select',
                    'columnClass' => 'col-md-2',
                    'value' => $filters['tugas_akhir_status'] ?? '',
                    'placeholder' => 'Semua',
                    'options' => collect(['draft', 'pengajuan', 'bimbingan', 'ujian', 'lulus', 'revisi'])->map(fn ($status) => [
                        'value' => $status,
                        'label' => ucfirst($status),
                    ])->all(),
                ],
                [
                    'name' => 'yudisium_status',
                    'label' => 'Yudisium',
                    'type' => 'select',
                    'columnClass' => 'col-md-2',
                    'value' => $filters['yudisium_status'] ?? '',
                    'placeholder' => 'Semua',
                    'options' => collect(['memenuhi', 'belum_memenuhi'])->map(fn ($status) => [
                        'value' => $status,
                        'label' => ucfirst(str_replace('_', ' ', $status)),
                    ])->all(),
                ],
                [
                    'name' => 'kelulusan_status',
                    'label' => 'Kelulusan',
                    'type' => 'select',
                    'columnClass' => 'col-md-2',
                    'value' => $filters['kelulusan_status'] ?? '',
                    'placeholder' => 'Semua',
                    'options' => collect(['draft', 'ditetapkan'])->map(fn ($status) => [
                        'value' => $status,
                        'label' => ucfirst($status),
                    ])->all(),
                ],
                [
                    'name' => 'wisuda_status',
                    'label' => 'Wisuda',
                    'type' => 'select',
                    'columnClass' => 'col-md-2',
                    'value' => $filters['wisuda_status'] ?? '',
                    'placeholder' => 'Semua',
                    'options' => collect(['draft', 'dibuka', 'ditutup', 'selesai'])->map(fn ($status) => [
                        'value' => $status,
                        'label' => ucfirst($status),
                    ])->all(),
                ],
                [
                    'name' => 'search',
                    'label' => 'Cari',
                    'type' => 'text',
                    'columnClass' => 'col-md-3',
                    'value' => $filters['search'] ?? '',
                    'placeholder' => 'Nama mahasiswa, judul, periode',
                ],
            ],
        ])

        <div class="row g-3 mb-4">
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Tugas Akhir Lulus',
                'value' => $summary['tugas_akhir_lulus'],
                'description' => 'dari ' . $summary['tugas_akhir_total'] . ' data tugas akhir',
                'actionRoute' => route('tugas-akhir.index'),
                'actionLabel' => 'Buka Tugas Akhir',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Yudisium Memenuhi',
                'value' => $summary['yudisium_memenuhi'],
                'description' => 'mahasiswa siap lanjut ke kelulusan',
                'actionRoute' => route('yudisium.index'),
                'actionLabel' => 'Buka Yudisium',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Kelulusan Ditetapkan',
                'value' => $summary['kelulusan_ditetapkan'],
                'description' => 'data kelulusan final',
                'actionRoute' => route('kelulusan.index'),
                'actionLabel' => 'Buka Kelulusan',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Peserta Wisuda',
                'value' => $summary['peserta_wisuda_total'],
                'description' => $summary['periode_wisuda_aktif'] . ' periode masih aktif',
                'actionRoute' => route('wisuda.periode.index'),
                'actionLabel' => 'Buka Wisuda',
            ])
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-4">
                    @include('layouts.partials.monitoring.card-header', [
                        'title' => 'Status Modul',
                        'description' => 'Distribusi status utama untuk memantau bottleneck akhir studi.',
                    ])
                    <div class="card-body">
                        <div class="alert alert-light border mb-3">
                            Filter `Cari` memengaruhi seluruh tahap akhir studi. Filter status bekerja per modul, jadi angka pada kartu dan daftar terbaru akan menyesuaikan hanya untuk tahap yang dipilih.
                        </div>
                        <div class="row g-3">
                            @foreach ([
                                'Tugas Akhir' => $statusBreakdown['tugas_akhir'],
                                'Yudisium' => $statusBreakdown['yudisium'],
                                'Kelulusan' => $statusBreakdown['kelulusan'],
                                'Wisuda' => $statusBreakdown['wisuda'],
                            ] as $section => $items)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="fw-semibold mb-3">{{ $section }}</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($items as $status => $count)
                                                <div class="border rounded px-2 py-1 bg-light">
                                                    @include('layouts.partials.status-badge', ['value' => $status])
                                                    <span class="ms-2 fw-semibold">{{ $count }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    @include('layouts.partials.monitoring.card-header', [
                        'title' => 'Periode Wisuda Aktif',
                        'description' => 'Snapshot periode wisuda yang sedang berjalan atau terdekat.',
                        'wrapperClass' => 'd-flex justify-content-between align-items-start',
                        'badge' => $periodeAktif ? view('layouts.partials.status-badge', ['value' => $periodeAktif['status'] ?? 'draft'])->render() : null,
                    ])
                    <div class="card-body">
                        @if ($periodeAktif)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="fw-semibold">Nama Periode</div>
                                    <div>{{ $periodeAktif['nama_periode'] ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold">Tanggal Wisuda</div>
                                    <div>{{ !empty($periodeAktif['tanggal_wisuda']) ? \Carbon\Carbon::parse($periodeAktif['tanggal_wisuda'])->translatedFormat('d F Y') : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold">Lokasi</div>
                                    <div>{{ $periodeAktif['lokasi'] ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold">Jumlah Peserta</div>
                                    <div>{{ $periodeAktif['peserta_count'] ?? 0 }} peserta</div>
                                </div>
                                <div class="col-12">
                                    <a href="{{ route('wisuda.periode.show', $periodeAktif['id']) }}" class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail Periode
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-muted">Belum ada periode wisuda yang aktif atau draft.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card mb-4">
                    @include('layouts.partials.monitoring.card-header', [
                        'title' => 'Aktivitas Terbaru',
                        'description' => 'Ringkasan cepat dari modul terakhir yang terisi.',
                    ])
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="fw-semibold mb-2">Tugas Akhir</div>
                            @forelse ($recent['tugas_akhir'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['mahasiswa']['nama_mahasiswa'] ?? '-',
                                    'subtitle' => $item['judul'] ?? '-',
                                    'status' => $item['status'] ?? 'draft',
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>

                        <div class="mb-4">
                            <div class="fw-semibold mb-2">Yudisium</div>
                            @forelse ($recent['yudisium'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['mahasiswa']['nama_mahasiswa'] ?? '-',
                                    'subtitle' => 'IPK ' . ($item['ipk'] ?? ($item['transkrip']['ipk'] ?? '-')),
                                    'status' => $item['status'] ?? 'belum_memenuhi',
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>

                        <div class="mb-4">
                            <div class="fw-semibold mb-2">Kelulusan</div>
                            @forelse ($recent['kelulusan'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['mahasiswa']['nama_mahasiswa'] ?? '-',
                                    'subtitle' => $item['nomor_ijazah'] ?? 'Nomor ijazah belum ada',
                                    'status' => $item['status'] ?? 'draft',
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>

                        <div>
                            <div class="fw-semibold mb-2">Wisuda</div>
                            @forelse ($recent['wisuda'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['nama_periode'] ?? '-',
                                    'subtitle' => ($item['peserta_count'] ?? 0) . ' peserta',
                                    'status' => $item['status'] ?? 'draft',
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
