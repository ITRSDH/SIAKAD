@extends('layouts.index')
@section('title', 'Yudisium')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Akhir Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('yudisium.index') }}">Yudisium</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Preview Yudisium</h4>
                        <small class="text-muted">Pilih mahasiswa untuk melihat kelayakan sebelum generate final.</small>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <form method="POST" action="{{ route('yudisium.generate') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Mahasiswa</label>
                                <select name="id_mahasiswa" id="id_mahasiswa" class="form-select" required>
                                    <option value="">Pilih mahasiswa</option>
                                    @foreach ($mahasiswa as $item)
                                        <option value="{{ $item['id'] ?? '' }}">
                                            {{ $item['nim'] ?? '-' }} - {{ $item['nama_mahasiswa'] ?? 'Mahasiswa' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Yudisium</label>
                                <input type="date" name="tanggal_yudisium" class="form-control" value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="previewYudisium()">Preview</button>
                                <button type="submit" class="btn btn-primary">Generate Yudisium</button>
                            </div>
                        </form>

                        <div id="previewBox" class="mt-4 d-none">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="mb-3">Hasil Preview</h5>
                                <div id="previewContent" class="small text-muted">Belum ada data preview.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Daftar Yudisium</h4>
                        <small class="text-muted">Riwayat hasil yudisium mahasiswa yang sudah digenerate.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Transkrip</th>
                                        <th>Status</th>
                                        <th>Predikat</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yudisium as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $item['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $item['mahasiswa']['nim'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div>SKS Lulus: {{ $item['total_sks_lulus'] ?? ($item['transkrip']['total_sks_lulus'] ?? 0) }}</div>
                                                <small class="text-muted">IPK: {{ $item['ipk'] ?? ($item['transkrip']['ipk'] ?? 0) }}</small>
                                            </td>
                                            <td>
                                                @php $status = $item['status'] ?? 'belum_memenuhi'; @endphp
                                                @include('layouts.partials.status-badge', ['value' => $status])
                                            </td>
                                            <td>{{ $item['predikat_lulus'] ?? '-' }}</td>
                                            <td>{{ !empty($item['tanggal_yudisium']) ? \Carbon\Carbon::parse($item['tanggal_yudisium'])->translatedFormat('d M Y') : '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('yudisium.show', $item['id']) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada data yudisium.</td>
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

@push('scripts-custom')
    <script>
        const previewUrl = @json(route('yudisium.preview'));

        function previewYudisium() {
            const mahasiswaId = document.getElementById('id_mahasiswa').value;
            const previewBox = document.getElementById('previewBox');
            const previewContent = document.getElementById('previewContent');

            if (!mahasiswaId) {
                previewBox.classList.remove('d-none');
                previewContent.innerHTML = '<span class="text-danger">Pilih mahasiswa terlebih dahulu.</span>';
                return;
            }

            previewBox.classList.remove('d-none');
            previewContent.innerHTML = 'Memuat preview...';

            fetch(`${previewUrl}?id_mahasiswa=${encodeURIComponent(mahasiswaId)}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const payload = await response.json();
                if (!response.ok || payload.success === false) {
                    throw new Error(payload.message || 'Gagal memuat preview yudisium.');
                }

                const summary = payload.data?.summary || {};
                previewContent.innerHTML = `
                    <div class="mb-2"><strong>Total SKS Lulus:</strong> ${summary.total_sks_lulus ?? 0}</div>
                    <div class="mb-2"><strong>Target SKS:</strong> ${summary.target_sks_lulus ?? 0}</div>
                    <div class="mb-2"><strong>IPK:</strong> ${summary.ipk ?? 0}</div>
                    <div class="mb-2"><strong>Status:</strong> ${summary.status ?? '-'}</div>
                    <div><strong>Predikat:</strong> ${summary.predikat_lulus ?? '-'}</div>
                `;
            })
            .catch(error => {
                previewContent.innerHTML = `<span class="text-danger">${error.message}</span>`;
            });
        }
    </script>
@endpush
