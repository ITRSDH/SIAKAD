@extends('layouts.index')
@section('title', 'Detail Peserta Wisuda')

@php
    $mahasiswa = $peserta['mahasiswa'] ?? [];
    $kelulusan = $peserta['kelulusan'] ?? [];
    $statusPeserta = $peserta['status'] ?? 'draft';
    $statusAdministrasi = $peserta['status_validasi_administrasi'] ?? 'belum';
@endphp

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
                @if (!empty($periode['id']))
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('wisuda.peserta.index', $periode['id']) }}">Peserta</a></li>
                @endif
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('wisuda.peserta.show', $peserta['id'] ?? '') }}">Detail Peserta</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">{{ $mahasiswa['nama_mahasiswa'] ?? 'Detail Peserta Wisuda' }}</h4>
                            <small class="text-muted">{{ $mahasiswa['nim'] ?? '-' }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            @include('layouts.partials.status-badge', ['value' => $statusPeserta])
                            @include('layouts.partials.status-badge', ['value' => $statusAdministrasi])
                        </div>
                    </div>
                    <div class="card-body">
                        @include('layouts.partials.flash-messages')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Periode Wisuda</div>
                                <div>{{ $periode['nama_periode'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Wisuda</div>
                                <div>{{ !empty($periode['tanggal_wisuda']) ? \Carbon\Carbon::parse($periode['tanggal_wisuda'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Daftar</div>
                                <div>{{ !empty($peserta['tanggal_daftar']) ? \Carbon\Carbon::parse($peserta['tanggal_daftar'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Nomor Peserta</div>
                                <div>{{ $peserta['nomor_peserta'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Status Peserta</div>
                                <div>@include('layouts.partials.status-badge', ['value' => $statusPeserta])</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Validasi Administrasi</div>
                                <div>@include('layouts.partials.status-badge', ['value' => $statusAdministrasi])</div>
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold">Catatan</div>
                                <div>{{ $peserta['catatan'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Data Kelulusan</h4>
                        <small class="text-muted">Ringkasan kelulusan yang menjadi dasar pendaftaran wisuda.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fw-semibold">Status Kelulusan</div>
                                <div>@include('layouts.partials.status-badge', ['value' => $kelulusan['status'] ?? 'draft'])</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Tanggal Lulus</div>
                                <div>{{ !empty($kelulusan['tanggal_lulus']) ? \Carbon\Carbon::parse($kelulusan['tanggal_lulus'])->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">Nomor Ijazah</div>
                                <div>{{ $kelulusan['nomor_ijazah'] ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-semibold">ID Kelulusan</div>
                                <div>{{ $kelulusan['id'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Ringkasan Periode</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="fw-semibold">Lokasi</div>
                            <div>{{ $periode['lokasi'] ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-semibold">Status Periode</div>
                            <div>@include('layouts.partials.status-badge', ['value' => $periode['status'] ?? 'draft'])</div>
                        </div>
                        <div>
                            <div class="fw-semibold">Catatan Periode</div>
                            <div>{{ $periode['catatan'] ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Navigasi Cepat</h4>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button type="button" class="btn btn-warning" onclick='openEditPeserta(@json($peserta))'>
                            <i class="fas fa-pen me-1"></i> Edit Peserta
                        </button>
                        @if (!empty($periode['id']))
                            <a href="{{ route('wisuda.peserta.index', $periode['id']) }}" class="btn btn-primary">
                                <i class="fas fa-users me-1"></i> Kembali ke Peserta
                            </a>
                            <a href="{{ route('wisuda.periode.show', $periode['id']) }}" class="btn btn-light">
                                <i class="fas fa-calendar-alt me-1"></i> Detail Periode
                            </a>
                        @endif
                        <a href="{{ route('wisuda.periode.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Daftar Periode
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailPesertaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="detailPesertaForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Peserta Wisuda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Daftar</label>
                                <input type="date" name="tanggal_daftar" id="detail_tanggal_daftar" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Peserta</label>
                                <input type="text" name="nomor_peserta" id="detail_nomor_peserta" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Peserta</label>
                                <select name="status" id="detail_status_peserta" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="terdaftar">Terdaftar</option>
                                    <option value="terverifikasi">Terverifikasi</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Validasi Administrasi</label>
                                <select name="status_validasi_administrasi" id="detail_status_validasi_administrasi" class="form-select">
                                    <option value="belum">Belum</option>
                                    <option value="memenuhi">Memenuhi</option>
                                    <option value="tidak_memenuhi">Tidak Memenuhi</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" id="detail_catatan_peserta" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const detailPesertaUpdateTemplate = @json(route('wisuda.peserta.update', ['id' => '__ID__']));

        function openEditPeserta(peserta) {
            document.getElementById('detailPesertaForm').action = detailPesertaUpdateTemplate.replace('__ID__', peserta.id);
            document.getElementById('detail_tanggal_daftar').value = peserta.tanggal_daftar || '';
            document.getElementById('detail_nomor_peserta').value = peserta.nomor_peserta || '';
            document.getElementById('detail_status_peserta').value = peserta.status || 'draft';
            document.getElementById('detail_status_validasi_administrasi').value = peserta.status_validasi_administrasi || 'belum';
            document.getElementById('detail_catatan_peserta').value = peserta.catatan || '';
            new bootstrap.Modal(document.getElementById('detailPesertaModal')).show();
        }
    </script>
@endpush
