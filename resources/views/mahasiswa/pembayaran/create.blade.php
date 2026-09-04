@extends('layouts.index')
@section('title', 'Kirim Pembayaran')

@push('styles-custom')
<style>
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .upload-area:hover {
        border-color: #6861CE;
        background-color: #f8f9fa;
    }
    .upload-area.dragover {
        border-color: #6861CE;
        background-color: #e7f1ff;
    }
</style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pembayaran: {{ $tagihan->biaya->nama }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.pembayaran.index') }}">Pembayaran Mahasiswa</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.pembayaran.index') }}">Tagihan Saya</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Kirim Pembayaran</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-info">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle fs-3 me-3 text-white"></i>
                            <div class="text-white">
                                <h4 class="card-title mb-1 text-white">Sistem Pembayaran Fleksibel</h4>
                                <p class="mb-0">Anda dapat membayar lunas sebesar <strong>Rp {{ number_format($tagihan->sisa_pembayaran, 0, ',', '.') }}</strong> atau mencicil sesuai kemampuan Anda saat ini.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.pembayaran.store', $tagihan->id) }}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Sisa Tagihan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                        <input type="text"
                                            value="Rp {{ number_format($tagihan->sisa_pembayaran, 0, ',', '.') }}"
                                            disabled
                                            class="form-control fw-bold bg-light">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number"
                                            name="jumlah"
                                            min="0"
                                            max="{{ $tagihan->sisa_pembayaran }}"
                                            required
                                            class="form-control fw-bold @error('jumlah') is-invalid @enderror"
                                            placeholder="Masukkan nominal...">
                                    </div>
                                    @error('jumlah')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                                <div class="upload-area" id="uploadArea">
                                    <input type="file" 
                                        name="bukti_pembayaran" 
                                        id="bukti_pembayaran" 
                                        required
                                        accept="image/*,.pdf"
                                        class="d-none">
                                    <i class="fas fa-cloud-upload-alt fs-1 text-muted mb-3"></i>
                                    <p class="mb-1 fw-bold">Klik atau seret file ke sini</p>
                                    <p class="text-muted small mb-0">Format: JPG, PNG, PDF (Max 2MB)</p>
                                    <div id="file-name" class="mt-3 text-primary fw-bold d-none"></div>
                                </div>
                                @error('bukti_pembayaran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Catatan (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                                    <textarea name="catatan" 
                                        rows="3"
                                        class="form-control"
                                        placeholder="Masukkan catatan tambahan jika perlu..."></textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-2 pt-3 border-top">
                                <a href="{{ route('student.pembayaran.index') }}" 
                                   class="btn btn-secondary flex-fill">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit"
                                    class="btn btn-primary flex-fill">
                                    <i class="fas fa-paper-plane"></i> Kirim Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $transaksiList = collect($tagihan->transaksi ?? []);
                @endphp
                @if($transaksiList->isNotEmpty())
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-history text-primary me-2"></i>Riwayat Pembayaran Tagihan Ini
                        </h5>
                        <span class="badge bg-secondary">{{ $transaksiList->count() }} transaksi</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-nowrap text-center">
                                        <th>#</th>
                                        <th>No. Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksiList as $idx => $trx)
                                    @php $trx = (object) $trx; @endphp
                                    <tr>
                                        <td class="text-center text-muted small">{{ $idx + 1 }}</td>
                                        <td class="fw-semibold text-nowrap">{{ $trx->no_transaksi ?? '-' }}</td>
                                        <td class="text-nowrap small">
                                            @if(!empty($trx->tanggal_pembayaran))
                                                {{ \Carbon\Carbon::parse($trx->tanggal_pembayaran)->translatedFormat('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-nowrap fw-bold text-end">
                                            Rp {{ number_format($trx->jumlah ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @if(($trx->status ?? '') === 'disetujui')
                                                <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                            @elseif(($trx->status ?? '') === 'ditolak')
                                                <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                            @elseif(($trx->status ?? '') === 'menunggu')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Menunggu Verifikasi</span>
                                            @else
                                                <span class="badge bg-secondary text-white">{{ ucfirst($trx->status ?? '-') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($trx->catatan))
                                                <div class="small text-break">{{ $trx->catatan }}</div>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @if(!empty($trx->bukti_pembayaran))
                                                @php
                                                    $apiUrl = config('api.keuangan_url', 'http://localhost:8001');
                                                    $buktiUrl = rtrim($apiUrl, '/') . '/storage/' . $trx->bukti_pembayaran;
                                                @endphp
                                                <a href="{{ $buktiUrl }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
<script>
    // File upload handling
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('bukti_pembayaran');
    const fileNameDisplay = document.getElementById('file-name');

    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        if (fileName) {
            fileNameDisplay.innerText = "Terpilih: " + fileName;
            fileNameDisplay.classList.remove('d-none');
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileNameDisplay.innerText = "Terpilih: " + files[0].name;
            fileNameDisplay.classList.remove('d-none');
        }
    });
</script>
@endpush