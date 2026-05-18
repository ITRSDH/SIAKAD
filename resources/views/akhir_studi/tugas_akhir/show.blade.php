@extends('layouts.index')
@section('title', 'Detail Tugas Akhir')

@php
    $status = $tugasAkhir['status'] ?? 'draft';
    $mahasiswa = $tugasAkhir['mahasiswa'] ?? [];
    $kurikulum = $tugasAkhir['kurikulum'] ?? [];
    $pembimbing = $tugasAkhir['pembimbing'] ?? [];
    $ujian = $tugasAkhir['ujian'] ?? [];
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
                <li class="nav-item"><a href="{{ route('tugas-akhir.index') }}">Tugas Akhir</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('tugas-akhir.show', $tugasAkhir['id'] ?? '') }}">Detail</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">{{ $tugasAkhir['judul'] ?? 'Detail Tugas Akhir' }}</h4>
                            <small class="text-muted">{{ $tugasAkhir['jenis_tugas_akhir'] ?? '-' }}</small>
                        </div>
                        @include('layouts.partials.status-badge', ['value' => $status])
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Mahasiswa</div>
                                <div>{{ $mahasiswa['nama_mahasiswa'] ?? '-' }}</div>
                                <small class="text-muted">{{ $mahasiswa['nim'] ?? '-' }}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Kurikulum</div>
                                <div>{{ $kurikulum['nama_kurikulum'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Pengajuan</div>
                                <div>{{ !empty($tugasAkhir['tanggal_pengajuan']) ? \Carbon\Carbon::parse($tugasAkhir['tanggal_pengajuan'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Mulai Bimbingan</div>
                                <div>{{ !empty($tugasAkhir['tanggal_mulai_bimbingan']) ? \Carbon\Carbon::parse($tugasAkhir['tanggal_mulai_bimbingan'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Lulus</div>
                                <div>{{ !empty($tugasAkhir['tanggal_lulus']) ? \Carbon\Carbon::parse($tugasAkhir['tanggal_lulus'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Aktif</div>
                                <div>{{ !empty($tugasAkhir['is_active']) ? 'Ya' : 'Tidak' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Topik</div>
                                <div>{{ $tugasAkhir['topik'] ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Catatan</div>
                                <div>{{ $tugasAkhir['catatan'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Riwayat Ujian</h4>
                        <small class="text-muted">Daftar tahapan ujian dan keputusan yang pernah dicatat.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Jenis Ujian</th>
                                        <th>Tanggal</th>
                                        <th>Nilai</th>
                                        <th>Keputusan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ujian as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span>{{ ucfirst($item['jenis_ujian'] ?? '-') }}</span>
                                                    <button class="btn btn-xs btn-outline-secondary" onclick='openEditUjianModal(@json($item))'>
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>{{ !empty($item['tanggal_ujian']) ? \Carbon\Carbon::parse($item['tanggal_ujian'])->translatedFormat('d M Y') : '-' }}</td>
                                            <td>{{ $item['nilai_ujian'] ?? '-' }}</td>
                                            <td>@include('layouts.partials.status-badge', ['value' => $item['keputusan'] ?? 'draft'])</td>
                                            <td>{{ $item['catatan'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data ujian tugas akhir.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Pembimbing</h4>
                        <small class="text-muted">Daftar dosen pembimbing yang aktif pada tugas akhir ini.</small>
                    </div>
                    <div class="card-body">
                        @forelse ($pembimbing as $item)
                            <div class="border rounded p-3 mb-3">
                                <div class="fw-semibold">{{ $item['dosen']['nama_dosen'] ?? '-' }}</div>
                                <div class="small text-muted">{{ $item['dosen']['nidn'] ?? '-' }}</div>
                                <div class="mt-2">@include('layouts.partials.status-badge', ['value' => $item['peran'] ?? 'draft', 'label' => ucfirst(str_replace('_', ' ', $item['peran'] ?? '-')), 'tone' => 'info'])</div>
                                <div class="small mt-2">{{ $item['catatan'] ?? 'Tanpa catatan pembimbing.' }}</div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada pembimbing yang ditetapkan.</div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Navigasi Cepat</h4>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick='openCreateUjianModal("{{ $tugasAkhir['id'] ?? '' }}")'>
                            <i class="fas fa-plus me-1"></i> Tambah Ujian
                        </button>
                        <a href="{{ route('tugas-akhir.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailUjianModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="detailUjianForm">
                    @csrf
                    <input type="hidden" name="_method" id="detailUjianMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailUjianModalTitle">Tambah Ujian Tugas Akhir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Ujian</label>
                            <select name="jenis_ujian" id="detail_ujian_jenis" class="form-select">
                                <option value="proposal">Proposal</option>
                                <option value="hasil">Hasil</option>
                                <option value="akhir">Akhir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Ujian</label>
                            <input type="date" name="tanggal_ujian" id="detail_ujian_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Ujian</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_ujian" id="detail_ujian_nilai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keputusan</label>
                            <select name="keputusan" id="detail_ujian_keputusan" class="form-select">
                                <option value="lulus">Lulus</option>
                                <option value="revisi">Revisi</option>
                                <option value="tidak_lulus">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" id="detail_ujian_catatan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Ujian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const detailStoreUjianTemplate = @json(route('tugas-akhir.store-ujian', ['id' => '__ID__']));
        const detailUpdateUjianTemplate = @json(route('tugas-akhir.update-ujian', ['id' => '__ID__']));

        function openCreateUjianModal(tugasAkhirId) {
            document.getElementById('detailUjianForm').action = detailStoreUjianTemplate.replace('__ID__', tugasAkhirId);
            document.getElementById('detailUjianForm').reset();
            document.getElementById('detailUjianMethod').value = 'POST';
            document.getElementById('detailUjianModalTitle').innerText = 'Tambah Ujian Tugas Akhir';
            new bootstrap.Modal(document.getElementById('detailUjianModal')).show();
        }

        function openEditUjianModal(ujian) {
            document.getElementById('detailUjianForm').action = detailUpdateUjianTemplate.replace('__ID__', ujian.id);
            document.getElementById('detailUjianMethod').value = 'PUT';
            document.getElementById('detailUjianModalTitle').innerText = 'Edit Ujian Tugas Akhir';
            document.getElementById('detail_ujian_jenis').value = ujian.jenis_ujian || 'proposal';
            document.getElementById('detail_ujian_tanggal').value = ujian.tanggal_ujian || '';
            document.getElementById('detail_ujian_nilai').value = ujian.nilai_ujian ?? '';
            document.getElementById('detail_ujian_keputusan').value = ujian.keputusan || 'lulus';
            document.getElementById('detail_ujian_catatan').value = ujian.catatan || '';
            new bootstrap.Modal(document.getElementById('detailUjianModal')).show();
        }
    </script>
@endpush
