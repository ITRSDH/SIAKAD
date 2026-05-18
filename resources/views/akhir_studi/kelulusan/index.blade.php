@extends('layouts.index')
@section('title', 'Kelulusan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Akhir Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('kelulusan.index') }}">Kelulusan</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Generate Kelulusan</h4>
                        <small class="text-muted">Buat data kelulusan dari mahasiswa yang sudah memenuhi syarat yudisium.</small>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <form method="POST" action="{{ route('kelulusan.generate') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Mahasiswa</label>
                                <select name="id_mahasiswa" class="form-select" required>
                                    <option value="">Pilih mahasiswa</option>
                                    @foreach ($mahasiswa as $item)
                                        <option value="{{ $item['id'] ?? '' }}">
                                            {{ $item['nim'] ?? '-' }} - {{ $item['nama_mahasiswa'] ?? 'Mahasiswa' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lulus</label>
                                <input type="date" name="tanggal_lulus" class="form-control" value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor SK</label>
                                <input type="text" name="nomor_sk" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Ijazah</label>
                                <input type="text" name="nomor_ijazah" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="ditetapkan">Ditetapkan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate Kelulusan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Daftar Kelulusan</h4>
                        <small class="text-muted">Data kelulusan final yang akan menjadi dasar administratif berikutnya.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Yudisium</th>
                                        <th>Tanggal Lulus</th>
                                        <th>Nomor Dokumen</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kelulusan as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $item['mahasiswa']['nim'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div>Status: {{ ucfirst(str_replace('_', ' ', $item['yudisium']['status'] ?? '-')) }}</div>
                                                <small class="text-muted">Predikat: {{ $item['yudisium']['predikat_lulus'] ?? '-' }}</small>
                                            </td>
                                            <td>{{ !empty($item['tanggal_lulus']) ? \Carbon\Carbon::parse($item['tanggal_lulus'])->translatedFormat('d M Y') : '-' }}</td>
                                            <td>
                                                <div>SK: {{ $item['nomor_sk'] ?? '-' }}</div>
                                                <small class="text-muted">Ijazah: {{ $item['nomor_ijazah'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @php $status = $item['status'] ?? 'draft'; @endphp
                                                @include('layouts.partials.status-badge', ['value' => $status, 'label' => ucfirst($status)])
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('kelulusan.show', $item['id']) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada data kelulusan.</td>
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
@endsection
