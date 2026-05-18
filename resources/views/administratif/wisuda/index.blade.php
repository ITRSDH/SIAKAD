@extends('layouts.index')
@section('title', 'Periode Wisuda')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Administratif</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('wisuda.periode.index') }}">Wisuda</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('wisuda.periode.index') }}">Periode</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Periode Wisuda</h4>
                            <small class="text-muted">Kelola periode wisuda dan akses daftar peserta per periode.</small>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodeModal" onclick="openCreatePeriode()">
                            <i class="fas fa-plus me-1"></i> Tambah Periode
                        </button>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Periode</th>
                                        <th>Pendaftaran</th>
                                        <th>Tanggal Wisuda</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Peserta</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($periodeWisuda as $periode)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $periode['nama_periode'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $periode['catatan'] ?? 'Tanpa catatan' }}</small>
                                            </td>
                                            <td>
                                                {{ !empty($periode['tanggal_mulai_pendaftaran']) ? \Carbon\Carbon::parse($periode['tanggal_mulai_pendaftaran'])->translatedFormat('d M Y') : '-' }}
                                                -
                                                {{ !empty($periode['tanggal_selesai_pendaftaran']) ? \Carbon\Carbon::parse($periode['tanggal_selesai_pendaftaran'])->translatedFormat('d M Y') : '-' }}
                                            </td>
                                            <td>{{ !empty($periode['tanggal_wisuda']) ? \Carbon\Carbon::parse($periode['tanggal_wisuda'])->translatedFormat('d M Y') : '-' }}</td>
                                            <td>{{ $periode['lokasi'] ?? '-' }}</td>
                                            <td>
                                                @php $status = $periode['status'] ?? 'draft'; @endphp
                                                @include('layouts.partials.status-badge', ['value' => $status, 'label' => ucfirst($status)])
                                            </td>
                                            <td>{{ $periode['peserta_count'] ?? 0 }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('wisuda.periode.show', $periode['id']) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('wisuda.peserta.index', $periode['id']) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-warning" onclick='openEditPeriode(@json($periode))'>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada periode wisuda.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="periodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="periodeForm">
                    @csrf
                    <input type="hidden" name="_method" id="periodeMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="periodeModalTitle">Tambah Periode Wisuda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Periode</label>
                                <input type="text" name="nama_periode" id="nama_periode" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="dibuka">Dibuka</option>
                                    <option value="ditutup">Ditutup</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai Pendaftaran</label>
                                <input type="date" name="tanggal_mulai_pendaftaran" id="tanggal_mulai_pendaftaran" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai Pendaftaran</label>
                                <input type="date" name="tanggal_selesai_pendaftaran" id="tanggal_selesai_pendaftaran" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Wisuda</label>
                                <input type="date" name="tanggal_wisuda" id="tanggal_wisuda" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" id="lokasi" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const periodeStoreUrl = @json(route('wisuda.periode.store'));
        const periodeUpdateTemplate = @json(route('wisuda.periode.update', ['id' => '__ID__']));

        function openCreatePeriode() {
            document.getElementById('periodeModalTitle').innerText = 'Tambah Periode Wisuda';
            document.getElementById('periodeForm').action = periodeStoreUrl;
            document.getElementById('periodeMethod').value = 'POST';
            document.getElementById('periodeForm').reset();
        }

        function openEditPeriode(periode) {
            document.getElementById('periodeModalTitle').innerText = 'Edit Periode Wisuda';
            document.getElementById('periodeForm').action = periodeUpdateTemplate.replace('__ID__', periode.id);
            document.getElementById('periodeMethod').value = 'PUT';
            document.getElementById('nama_periode').value = periode.nama_periode || '';
            document.getElementById('status').value = periode.status || 'draft';
            document.getElementById('tanggal_mulai_pendaftaran').value = periode.tanggal_mulai_pendaftaran || '';
            document.getElementById('tanggal_selesai_pendaftaran').value = periode.tanggal_selesai_pendaftaran || '';
            document.getElementById('tanggal_wisuda').value = periode.tanggal_wisuda || '';
            document.getElementById('lokasi').value = periode.lokasi || '';
            document.getElementById('catatan').value = periode.catatan || '';
            new bootstrap.Modal(document.getElementById('periodeModal')).show();
        }
    </script>
@endpush
