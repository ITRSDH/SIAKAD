@extends('layouts.index')
@section('title', 'Monitoring Akademik')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Akademik</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.monitoring') }}">Monitoring Akademik</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        @include('layouts.partials.monitoring.filter-card', [
            'title' => 'Filter Monitoring',
            'description' => 'Gunakan filter untuk mempersempit pembacaan data semester dan operasional akademik.',
            'action' => route('akademik.monitoring'),
            'resetRoute' => route('akademik.monitoring'),
            'fields' => [
                [
                    'name' => 'semester_id',
                    'label' => 'Semester',
                    'type' => 'select',
                    'columnClass' => 'col-md-3',
                    'value' => $filters['semester_id'] ?? '',
                    'placeholder' => 'Semua semester',
                    'options' => $semesterOptions->map(fn ($option) => [
                        'value' => $option['id'],
                        'label' => $option['label'],
                    ])->all(),
                ],
                [
                    'name' => 'prodi_id',
                    'label' => 'Program Studi',
                    'type' => 'select',
                    'columnClass' => 'col-md-3',
                    'value' => $filters['prodi_id'] ?? '',
                    'placeholder' => 'Semua prodi',
                    'options' => $prodiOptions->map(fn ($option) => [
                        'value' => $option['id'],
                        'label' => $option['label'],
                    ])->all(),
                ],
                [
                    'name' => 'status',
                    'label' => 'Status Periode',
                    'type' => 'select',
                    'columnClass' => 'col-md-2',
                    'value' => $filters['status'] ?? '',
                    'placeholder' => 'Semua status',
                    'options' => collect(['aktif', 'draft', 'ditutup'])->map(fn ($status) => [
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
                    'placeholder' => 'Nama mahasiswa, kelas, mata kuliah',
                ],
            ],
        ])

        <div class="row g-3 mb-4">
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Periode KRS',
                'value' => $summary['periode_krs_total'],
                'description' => 'periode yang tercatat',
                'actionRoute' => route('periode-krs.index'),
                'actionLabel' => 'Buka Periode KRS',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Kelas Kuliah',
                'value' => $summary['kelas_kuliah_total'],
                'description' => $kelasSummary['punya_pengajar'] . ' kelas sudah punya pengajar',
                'actionRoute' => route('kelas-kuliah.index'),
                'actionLabel' => 'Buka Kelas Kuliah',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'KHS',
                'value' => $summary['khs_total'],
                'description' => 'rekap hasil studi semester',
                'actionRoute' => route('student.khs.index'),
                'actionLabel' => 'Buka KHS',
            ])
            @include('layouts.partials.monitoring.kpi-card', [
                'title' => 'Transkrip',
                'value' => $summary['transkrip_total'],
                'description' => $summary['mahasiswa_lulus_sks'] . ' mahasiswa sudah punya SKS lulus',
                'actionRoute' => route('student.transkrip.index'),
                'actionLabel' => 'Buka Transkrip',
            ])
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-4">
                    @include('layouts.partials.monitoring.card-header', [
                        'title' => 'Snapshot Operasional',
                        'description' => 'Membaca kesiapan semester dari periode KRS, kelas, KHS, dan transkrip.',
                        'wrapperClass' => 'd-flex justify-content-between align-items-start',
                        'badge' => $periodeAktif ? '<span class="badge bg-info">Periode berjalan terdeteksi</span>' : null,
                    ])
                    <div class="card-body">
                        <div class="alert alert-light border mb-3">
                            Filter `Program Studi` paling memengaruhi data `Kelas Kuliah`. Filter `Semester` memengaruhi `Periode KRS`, `Kelas Kuliah`, dan `KHS`. `Transkrip` tetap ditampilkan berdasarkan pencarian teks karena sifatnya lintas semester. Ringkasan kelas membaca field ringkas dari API seperti pengajar dan peserta terdaftar, bukan jadwal detail.
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="fw-semibold mb-3">Periode KRS Aktif</div>
                                    @if ($periodeAktif)
                                        <div class="mb-2">{{ $periodeAktif['semester']['nama_semester'] ?? ($periodeAktif['semester']['kode_semester'] ?? 'Periode KRS') }}</div>
                                        <div class="small text-muted mb-2">
                                            {{ !empty($periodeAktif['tanggal_mulai']) ? \Carbon\Carbon::parse($periodeAktif['tanggal_mulai'])->translatedFormat('d M Y') : '-' }}
                                            -
                                            {{ !empty($periodeAktif['tanggal_selesai']) ? \Carbon\Carbon::parse($periodeAktif['tanggal_selesai'])->translatedFormat('d M Y') : '-' }}
                                        </div>
                                        @include('layouts.partials.status-badge', ['value' => $periodeAktif['status'] ?? 'aktif', 'label' => ucfirst($periodeAktif['status'] ?? 'aktif'), 'tone' => ($periodeAktif['status'] ?? 'aktif') === 'aktif' ? 'success' : 'info'])
                                    @else
                                        <div class="text-muted">Belum ada periode KRS yang bisa dibaca sebagai periode berjalan.</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="fw-semibold mb-3">Kesiapan Kelas dan Penilaian</div>
                                    <div class="mb-2">Total kelas terbaca: <strong>{{ $kelasSummary['total'] }}</strong></div>
                                    <div class="mb-2">Kelas punya pengajar: <strong>{{ $kelasSummary['punya_pengajar'] }}</strong></div>
                                    <div class="mb-2">Kelas sudah berisi peserta: <strong>{{ $kelasSummary['punya_peserta'] }}</strong></div>
                                    <div>Total peserta terdaftar: <strong>{{ $kelasSummary['total_peserta'] }}</strong></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="fw-semibold mb-3">Sebaran IP Semester</div>
                                    @foreach ($khsIpkBuckets as $label => $count)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $label }}</span>
                                            <strong>{{ $count }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="fw-semibold mb-3">Sebaran IPK Transkrip</div>
                                    @foreach ($transkripIpkBuckets as $label => $count)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $label }}</span>
                                            <strong>{{ $count }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('layouts.partials.monitoring.simple-table', [
                    'title' => 'Periode KRS Terbaru',
                    'description' => 'Daftar ringkas periode KRS untuk membantu baca kalender akademik.',
                    'columns' => ['Semester', 'Periode', 'Status', 'Catatan'],
                    'rows' => $recent['periode_krs']->map(function ($item) {
                        $statusBadge = view('layouts.partials.status-badge', [
                            'value' => $item['status'] ?? 'draft',
                            'label' => ucfirst($item['status'] ?? 'draft'),
                            'tone' => ($item['status'] ?? '') === 'aktif' ? 'success' : null,
                        ])->render();

                        return [
                            $item['semester']['nama_semester'] ?? ($item['semester']['kode_semester'] ?? '-'),
                            (!empty($item['tanggal_mulai']) ? \Carbon\Carbon::parse($item['tanggal_mulai'])->translatedFormat('d M Y') : '-') .
                                ' - ' .
                                (!empty($item['tanggal_selesai']) ? \Carbon\Carbon::parse($item['tanggal_selesai'])->translatedFormat('d M Y') : '-'),
                            $statusBadge,
                            $item['catatan'] ?? '-',
                        ];
                    })->all(),
                    'emptyText' => 'Belum ada data periode KRS.',
                ])
            </div>

            <div class="col-xl-4">
                <div class="card mb-4">
                    @include('layouts.partials.monitoring.card-header', [
                        'title' => 'Aktivitas Terbaru',
                        'description' => 'Ringkasan entri terbaru dari kelas, KHS, dan transkrip.',
                    ])
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="fw-semibold mb-2">Kelas Kuliah</div>
                            @forelse ($recent['kelas_kuliah'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['nama_kelas'] ?? ($item['nama'] ?? 'Kelas Kuliah'),
                                    'subtitle' => trim(implode(' | ', array_filter([
                                        $item['mata_kuliah']['nama_mk'] ?? $item['mata_kuliah']['nama_mata_kuliah'] ?? '-',
                                        $item['prodi'] ?? null,
                                        'Peserta ' . ($item['peserta_terdaftar'] ?? 0),
                                    ]))),
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>

                        <div class="mb-4">
                            <div class="fw-semibold mb-2">KHS</div>
                            @forelse ($recent['khs'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['mahasiswa']['nama_mahasiswa'] ?? '-',
                                    'subtitle' => 'IPS ' . ($item['ip_semester'] ?? '-') . ' | SKS ' . ($item['total_sks_lulus'] ?? ($item['total_sks_diambil'] ?? '-')),
                                ])
                            @empty
                                <div class="text-muted small">Belum ada data.</div>
                            @endforelse
                        </div>

                        <div>
                            <div class="fw-semibold mb-2">Transkrip</div>
                            @forelse ($recent['transkrip'] as $item)
                                @include('layouts.partials.monitoring.activity-item', [
                                    'title' => $item['mahasiswa']['nama_mahasiswa'] ?? '-',
                                    'subtitle' => 'IPK ' . ($item['ipk'] ?? '-') . ' | SKS ' . ($item['total_sks_lulus'] ?? '-'),
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
