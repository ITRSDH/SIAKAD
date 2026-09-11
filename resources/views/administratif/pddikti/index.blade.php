@extends('layouts.index')
@section('title', 'Pengaturan Integrasi Neo Feeder PDDikti')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Integrasi Neo Feeder PDDikti</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ url('/') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Pengaturan Integrasi Feeder</li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('pddikti.setting.update') }}" method="POST">
                    @csrf

                    <!-- Kartu Saklar Utama -->
                    <div class="card card-round shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1"><i class="fas fa-toggle-on text-primary me-2"></i>Status Saklar Integrasi Feeder</h4>
                                <p class="text-muted small mb-0">Aktifkan atau nonaktifkan komunikasi data antara SIAKAD dan Neo Feeder PDDikti.</p>
                            </div>
                            <div>
                                @if(!empty($setting['sync_enabled']))
                                    <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i>INTEGRASI AKTIF</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2"><i class="fas fa-power-off me-1"></i>DINONAKTIFKAN (OFF)</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-shield-alt fa-2x me-3 text-info"></i>
                                <div>
                                    <strong>Operasional Mandiri (Zero-Downtime):</strong><br>
                                    Saat saklar berada di posisi <strong>OFF</strong>, seluruh proses tambah mahasiswa, KRS, dan penilaian di SIAKAD tetap berjalan 100% cepat tanpa risiko lambat atau error akibat server Feeder offline.
                                </div>
                            </div>

                            <div class="form-check form-switch form-switch-lg mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="sync_enabled" name="sync_enabled" value="1" {{ !empty($setting['sync_enabled']) ? 'checked' : '' }} style="width: 3.5rem; height: 1.8rem; cursor: pointer;">
                                <label class="form-check-label fw-bold ms-3 fs-5" for="sync_enabled" style="cursor: pointer;">
                                    Aktifkan Integrasi Neo Feeder PDDikti
                                </label>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Mode Sinkronisasi</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="sync_mode" id="mode_manual" value="manual" {{ ($setting['sync_mode'] ?? 'manual') === 'manual' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="mode_manual">
                                                <i class="fas fa-hand-pointer text-primary me-1"></i> Mode Manual (Sangat Direkomendasikan)
                                            </label>
                                            <div class="text-muted small ms-4">Operator memeriksa data di SIAKAD terlebih dahulu, lalu menekan tombol sinkronisasi saat siap lapor.</div>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sync_mode" id="mode_auto" value="auto" {{ ($setting['sync_mode'] ?? '') === 'auto' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="mode_auto">
                                                <i class="fas fa-bolt text-warning me-1"></i> Mode Otomatis (Event-Driven Queue)
                                            </label>
                                            <div class="text-muted small ms-4">Perubahan data penting otomatis mengantrekan pengiriman di latar belakang ke Feeder.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Entitas yang Diizinkan Sinkron</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="auto_sync_mahasiswa" name="auto_sync_mahasiswa" value="1" {{ !empty($setting['auto_sync_mahasiswa']) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auto_sync_mahasiswa">Data Mahasiswa Baru & Registrasi</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="auto_sync_krs" name="auto_sync_krs" value="1" {{ !empty($setting['auto_sync_krs']) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auto_sync_krs">Kelas Kuliah & Peserta KRS</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="auto_sync_nilai" name="auto_sync_nilai" value="1" {{ !empty($setting['auto_sync_nilai']) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auto_sync_nilai">Nilai Akhir & AKM Semester</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Konfigurasi Endpoint & Kredensial -->
                    <div class="card card-round shadow-sm mb-4">
                        <div class="card-header">
                            <h4 class="card-title mb-0"><i class="fas fa-server text-primary me-2"></i>Endpoint & Kredensial WebService Feeder</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="feeder_url" class="form-label fw-bold">URL WebService Neo Feeder</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="url" class="form-control" id="feeder_url" name="feeder_url" value="{{ $setting['feeder_url'] ?? 'http://localhost:8100/ws/live.php' }}" placeholder="http://ip-server-feeder:8100/ws/live.php" required>
                                    </div>
                                    <small class="text-muted">Port default Neo Feeder PDDikti adalah <code>8100</code> dengan endpoint <code>/ws/live.php</code> atau <code>/ws/sandbox.php</code>.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="feeder_username" class="form-label fw-bold">Username / Kode PT</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="feeder_username" name="feeder_username" value="{{ $setting['feeder_username'] ?? '' }}" placeholder="Contoh: 043001">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="feeder_password" class="form-label fw-bold">Password WebService</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="feeder_password" name="feeder_password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    </div>
                                </div>
                            </div>

                            @if(!empty($setting['last_connected_at']))
                                <div class="mt-4 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small">Terakhir Terhubung:</span>
                                        <strong>{{ \Carbon\Carbon::parse($setting['last_connected_at'])->translatedFormat('d F Y, H:i') }} WIB</strong>
                                    </div>
                                    @if(!empty($setting['last_error_message']))
                                        <span class="badge bg-danger">Log Error: {{ $setting['last_error_message'] }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-info" id="btnTestKoneksi">
                                <i class="fas fa-network-wired me-1"></i> Uji Koneksi ke Feeder
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnTestKoneksi').on('click', function() {
        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menguji...');

        $.ajax({
            url: "{{ route('pddikti.setting.test-connection') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Koneksi Berhasil!',
                    text: res.message || 'Berhasil berkomunikasi dengan WebService Neo Feeder PDDikti.',
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Terhubung',
                    text: xhr.responseJSON?.message || 'Tidak dapat menghubungi server Feeder pada URL tersebut.',
                });
            },
            complete: function() {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>
@endpush
