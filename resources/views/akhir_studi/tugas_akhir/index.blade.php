@extends('layouts.index')
@section('title', 'Tugas Akhir')

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
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Daftar Tugas Akhir</h4>
                            <small class="text-muted">Kelola pengajuan, pembimbing, dan ujian tugas akhir mahasiswa.</small>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tugasAkhirModal" onclick="openCreateTugasAkhir()">
                            <i class="fas fa-plus me-1"></i> Tambah Tugas Akhir
                        </button>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Pembimbing</th>
                                        <th>Ujian Terakhir</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tugasAkhir as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $item['mahasiswa']['nim'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $item['judul'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $item['jenis_tugas_akhir'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @php $status = $item['status'] ?? 'draft'; @endphp
                                                @include('layouts.partials.status-badge', ['value' => $status])
                                            </td>
                                            <td>
                                                @forelse (($item['pembimbing'] ?? []) as $pembimbing)
                                                    <div>{{ $pembimbing['dosen']['nama_dosen'] ?? '-' }} <small class="text-muted">({{ $pembimbing['peran'] ?? '-' }})</small></div>
                                                @empty
                                                    <span class="text-muted">Belum ada pembimbing</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                @php $ujian = collect($item['ujian'] ?? [])->first(); @endphp
                                                @if ($ujian)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span>{{ ucfirst($ujian['jenis_ujian'] ?? '-') }}</span>
                                                        <button class="btn btn-xs btn-outline-secondary" onclick='openEditUjianModal(@json($ujian))'>
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">{{ !empty($ujian['tanggal_ujian']) ? \Carbon\Carbon::parse($ujian['tanggal_ujian'])->translatedFormat('d M Y') : '-' }} | {{ ucfirst(str_replace('_', ' ', $ujian['keputusan'] ?? '-')) }}</small>
                                                @else
                                                    <span class="text-muted">Belum ada ujian</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                    <a href="{{ route('tugas-akhir.show', $item['id']) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-warning" onclick='openEditTugasAkhir(@json($item))'>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info" onclick='openPembimbingModal(@json($item))'>
                                                        <i class="fas fa-user-tie"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick='openUjianModal(@json($item))'>
                                                        <i class="fas fa-file-signature"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada data tugas akhir.</td>
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

    <div class="modal fade" id="tugasAkhirModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="tugasAkhirForm">
                    @csrf
                    <input type="hidden" name="_method" id="tugasAkhirMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tugasAkhirModalTitle">Tambah Tugas Akhir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Mahasiswa</label>
                                <select name="id_mahasiswa" id="ta_id_mahasiswa" class="form-select" required>
                                    <option value="">Pilih mahasiswa</option>
                                    @foreach ($mahasiswa as $mhs)
                                        <option value="{{ $mhs['id'] ?? '' }}">{{ $mhs['nim'] ?? '-' }} - {{ $mhs['nama_mahasiswa'] ?? 'Mahasiswa' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Tugas Akhir</label>
                                <input type="text" name="jenis_tugas_akhir" id="ta_jenis_tugas_akhir" class="form-control" placeholder="Skripsi / Tesis / KTI" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" id="ta_judul" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Topik</label>
                                <textarea name="topik" id="ta_topik" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" id="ta_status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="pengajuan">Pengajuan</option>
                                    <option value="bimbingan">Bimbingan</option>
                                    <option value="ujian">Ujian</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak_lulus">Tidak Lulus</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pengajuan</label>
                                <input type="date" name="tanggal_pengajuan" id="ta_tanggal_pengajuan" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Mulai Bimbingan</label>
                                <input type="date" name="tanggal_mulai_bimbingan" id="ta_tanggal_mulai_bimbingan" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lulus</label>
                                <input type="date" name="tanggal_lulus" id="ta_tanggal_lulus" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Aktif</label>
                                <select name="is_active" id="ta_is_active" class="form-select">
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" id="ta_catatan" class="form-control" rows="3"></textarea>
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

    <div class="modal fade" id="pembimbingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="pembimbingForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-header">
                        <h5 class="modal-title">Sinkronisasi Pembimbing</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            @for ($i = 0; $i < 2; $i++)
                                <div class="col-md-6">
                                    <label class="form-label">Dosen Pembimbing {{ $i + 1 }}</label>
                                    <select name="pembimbing[{{ $i }}][id_dosen]" id="pembimbing_{{ $i }}_id_dosen" class="form-select">
                                        <option value="">Pilih dosen</option>
                                        @foreach ($dosen as $dsn)
                                            <option value="{{ $dsn['id'] ?? '' }}">{{ $dsn['nama_dosen'] ?? '-' }} - {{ $dsn['nidn'] ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="pembimbing[{{ $i }}][peran]" id="pembimbing_{{ $i }}_peran" value="{{ $i === 0 ? 'pembimbing_1' : 'pembimbing_2' }}">
                                </div>
                            @endfor
                            <div class="col-12">
                                <label class="form-label">Catatan Pembimbing 1</label>
                                <textarea name="pembimbing[0][catatan]" id="pembimbing_0_catatan" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan Pembimbing 2</label>
                                <textarea name="pembimbing[1][catatan]" id="pembimbing_1_catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pembimbing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ujianModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="ujianForm">
                    @csrf
                    <input type="hidden" name="_method" id="ujianMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ujianModalTitle">Tambah Ujian Tugas Akhir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Ujian</label>
                            <select name="jenis_ujian" id="ujian_jenis" class="form-select">
                                <option value="proposal">Proposal</option>
                                <option value="hasil">Hasil</option>
                                <option value="akhir">Akhir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Ujian</label>
                            <input type="date" name="tanggal_ujian" id="ujian_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Ujian</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_ujian" id="ujian_nilai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keputusan</label>
                            <select name="keputusan" id="ujian_keputusan" class="form-select">
                                <option value="lulus">Lulus</option>
                                <option value="revisi">Revisi</option>
                                <option value="tidak_lulus">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" id="ujian_catatan" class="form-control" rows="3"></textarea>
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
        const taStoreUrl = @json(route('tugas-akhir.store'));
        const taUpdateTemplate = @json(route('tugas-akhir.update', ['id' => '__ID__']));
        const taPembimbingTemplate = @json(route('tugas-akhir.sync-pembimbing', ['id' => '__ID__']));
        const taUjianTemplate = @json(route('tugas-akhir.store-ujian', ['id' => '__ID__']));
        const taUjianUpdateTemplate = @json(route('tugas-akhir.update-ujian', ['id' => '__ID__']));

        function openCreateTugasAkhir() {
            document.getElementById('tugasAkhirModalTitle').innerText = 'Tambah Tugas Akhir';
            document.getElementById('tugasAkhirForm').action = taStoreUrl;
            document.getElementById('tugasAkhirMethod').value = 'POST';
            document.getElementById('tugasAkhirForm').reset();
            document.getElementById('ta_is_active').value = '1';
        }

        function openEditTugasAkhir(item) {
            document.getElementById('tugasAkhirModalTitle').innerText = 'Edit Tugas Akhir';
            document.getElementById('tugasAkhirForm').action = taUpdateTemplate.replace('__ID__', item.id);
            document.getElementById('tugasAkhirMethod').value = 'PUT';
            document.getElementById('ta_id_mahasiswa').value = item.id_mahasiswa || '';
            document.getElementById('ta_jenis_tugas_akhir').value = item.jenis_tugas_akhir || '';
            document.getElementById('ta_judul').value = item.judul || '';
            document.getElementById('ta_topik').value = item.topik || '';
            document.getElementById('ta_status').value = item.status || 'draft';
            document.getElementById('ta_tanggal_pengajuan').value = item.tanggal_pengajuan || '';
            document.getElementById('ta_tanggal_mulai_bimbingan').value = item.tanggal_mulai_bimbingan || '';
            document.getElementById('ta_tanggal_lulus').value = item.tanggal_lulus || '';
            document.getElementById('ta_is_active').value = item.is_active ? '1' : '0';
            document.getElementById('ta_catatan').value = item.catatan || '';
            new bootstrap.Modal(document.getElementById('tugasAkhirModal')).show();
        }

        function openPembimbingModal(item) {
            document.getElementById('pembimbingForm').action = taPembimbingTemplate.replace('__ID__', item.id);
            document.getElementById('pembimbingForm').reset();

            const pembimbing = item.pembimbing || [];
            pembimbing.forEach((entry, index) => {
                if (index < 2) {
                    document.getElementById(`pembimbing_${index}_id_dosen`).value = entry.id_dosen || '';
                    document.getElementById(`pembimbing_${index}_peran`).value = entry.peran || (index === 0 ? 'pembimbing_1' : 'pembimbing_2');
                    document.getElementById(`pembimbing_${index}_catatan`).value = entry.catatan || '';
                }
            });

            new bootstrap.Modal(document.getElementById('pembimbingModal')).show();
        }

        function openUjianModal(item) {
            document.getElementById('ujianForm').action = taUjianTemplate.replace('__ID__', item.id);
            document.getElementById('ujianForm').reset();
            document.getElementById('ujianMethod').value = 'POST';
            document.getElementById('ujianModalTitle').innerText = 'Tambah Ujian Tugas Akhir';
            new bootstrap.Modal(document.getElementById('ujianModal')).show();
        }

        function openEditUjianModal(ujian) {
            document.getElementById('ujianForm').action = taUjianUpdateTemplate.replace('__ID__', ujian.id);
            document.getElementById('ujianMethod').value = 'PUT';
            document.getElementById('ujianModalTitle').innerText = 'Edit Ujian Tugas Akhir';
            document.getElementById('ujian_jenis').value = ujian.jenis_ujian || 'proposal';
            document.getElementById('ujian_tanggal').value = ujian.tanggal_ujian || '';
            document.getElementById('ujian_nilai').value = ujian.nilai_ujian ?? '';
            document.getElementById('ujian_keputusan').value = ujian.keputusan || 'lulus';
            document.getElementById('ujian_catatan').value = ujian.catatan || '';
            new bootstrap.Modal(document.getElementById('ujianModal')).show();
        }
    </script>
@endpush
