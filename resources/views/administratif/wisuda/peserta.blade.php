@extends('layouts.index')
@section('title', 'Peserta Wisuda')

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
                <li class="nav-item"><a href="{{ route('wisuda.peserta.index', $periode['id']) }}">Peserta</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">{{ $periode['nama_periode'] ?? 'Periode Wisuda' }}</h4>
                            <small class="text-muted">
                                Wisuda: {{ !empty($periode['tanggal_wisuda']) ? \Carbon\Carbon::parse($periode['tanggal_wisuda'])->translatedFormat('d F Y') : '-' }}
                                | Lokasi: {{ $periode['lokasi'] ?? '-' }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('wisuda.periode.index') }}" class="btn btn-light">Kembali</a>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pesertaModal" onclick="openCreatePeserta()">
                                <i class="fas fa-plus me-1"></i> Tambah Peserta
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status Peserta</th>
                                        <th>Validasi Administrasi</th>
                                        <th>Nomor Peserta</th>
                                        <th>Kelulusan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesertaWisuda as $peserta)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $peserta['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $peserta['mahasiswa']['nim'] ?? '-' }}</small>
                                            </td>
                                            <td>{{ !empty($peserta['tanggal_daftar']) ? \Carbon\Carbon::parse($peserta['tanggal_daftar'])->translatedFormat('d M Y') : '-' }}</td>
                                            <td>
                                                @include('layouts.partials.status-badge', ['value' => $peserta['status'] ?? 'draft'])
                                            </td>
                                            <td>
                                                @php $validasi = $peserta['status_validasi_administrasi'] ?? 'belum'; @endphp
                                                @include('layouts.partials.status-badge', ['value' => $validasi])
                                            </td>
                                            <td>{{ $peserta['nomor_peserta'] ?? '-' }}</td>
                                            <td>
                                                <div>{{ $peserta['kelulusan']['status'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $peserta['kelulusan']['nomor_ijazah'] ?? 'Ijazah belum ada' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('wisuda.peserta.show', $peserta['id']) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-warning" onclick='openEditPeserta(@json($peserta))'>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada peserta wisuda pada periode ini.</td>
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

    <div class="modal fade" id="pesertaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="pesertaForm">
                    @csrf
                    <input type="hidden" name="_method" id="pesertaMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pesertaModalTitle">Tambah Peserta Wisuda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12" id="mahasiswaSelectWrapper">
                                <label class="form-label">Mahasiswa Lulus</label>
                                <select name="id_mahasiswa" id="id_mahasiswa" class="form-select">
                                    <option value="">Pilih mahasiswa</option>
                                    @foreach ($kelulusan as $item)
                                        <option value="{{ $item['id_mahasiswa'] ?? '' }}">
                                            {{ $item['mahasiswa']['nim'] ?? '-' }} - {{ $item['mahasiswa']['nama_mahasiswa'] ?? 'Mahasiswa' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Daftar</label>
                                <input type="date" name="tanggal_daftar" id="tanggal_daftar" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Peserta</label>
                                <input type="text" name="nomor_peserta" id="nomor_peserta" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Peserta</label>
                                <select name="status" id="status_peserta" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="terdaftar">Terdaftar</option>
                                    <option value="terverifikasi">Terverifikasi</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Validasi Administrasi</label>
                                <select name="status_validasi_administrasi" id="status_validasi_administrasi" class="form-select">
                                    <option value="belum">Belum</option>
                                    <option value="memenuhi">Memenuhi</option>
                                    <option value="tidak_memenuhi">Tidak Memenuhi</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan_peserta" class="form-control" rows="3"></textarea>
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
        const pesertaStoreUrl = @json(route('wisuda.peserta.store', $periode['id']));
        const pesertaUpdateTemplate = @json(route('wisuda.peserta.update', ['id' => '__ID__']));

        function openCreatePeserta() {
            document.getElementById('pesertaModalTitle').innerText = 'Tambah Peserta Wisuda';
            document.getElementById('pesertaForm').action = pesertaStoreUrl;
            document.getElementById('pesertaMethod').value = 'POST';
            document.getElementById('pesertaForm').reset();
            document.getElementById('mahasiswaSelectWrapper').classList.remove('d-none');
            document.getElementById('id_mahasiswa').disabled = false;
        }

        function openEditPeserta(peserta) {
            document.getElementById('pesertaModalTitle').innerText = 'Edit Peserta Wisuda';
            document.getElementById('pesertaForm').action = pesertaUpdateTemplate.replace('__ID__', peserta.id);
            document.getElementById('pesertaMethod').value = 'PUT';
            document.getElementById('tanggal_daftar').value = peserta.tanggal_daftar || '';
            document.getElementById('nomor_peserta').value = peserta.nomor_peserta || '';
            document.getElementById('status_peserta').value = peserta.status || 'draft';
            document.getElementById('status_validasi_administrasi').value = peserta.status_validasi_administrasi || 'belum';
            document.getElementById('catatan_peserta').value = peserta.catatan || '';
            document.getElementById('mahasiswaSelectWrapper').classList.add('d-none');
            document.getElementById('id_mahasiswa').disabled = true;
            new bootstrap.Modal(document.getElementById('pesertaModal')).show();
        }
    </script>
@endpush
