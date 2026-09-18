@extends('layouts.index')
@section('title', 'Mahasiswa Management')

@push('styles-custom')
    <style>
        .loader-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 5;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #007bff;
            border-top: 4px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none !important;
        }

        .modal-xxl {
            max-width: 95% !important;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Mahasiswa Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Data Mahasiswa</h3>
                        <div class="d-flex gap-2">
                            <button id="exportMahasiswaBtn" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </button>
                            <button id="importMahasiswaBtn" class="btn btn-success btn-sm">
                                <i class="fas fa-file-import me-1"></i> Import / Template Excel
                            </button>
                            <button id="addMahasiswaBtn" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tableLoader" class="loader-overlay">
                            <div class="loader-spinner"></div>
                        </div>

                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-md-3">
                                <label for="filterProdiMahasiswa" class="form-label">Filter Program Studi</label>
                                <select id="filterProdiMahasiswa" class="form-control">
                                    <option value="">Semua Program Studi</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filterStatusMahasiswa" class="form-label">Status Mahasiswa</label>
                                <select id="filterStatusMahasiswa" class="form-control">
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="">Semua Status</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="DO">DO</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filterAngkatanMahasiswa" class="form-label">Filter Angkatan</label>
                                <input type="number" id="filterAngkatanMahasiswa" class="form-control"
                                    placeholder="Contoh: 2024" min="1900" max="{{ date('Y') + 10 }}">
                            </div>
                            <div class="col-md-2">
                                <label for="filterJalurMahasiswa" class="form-label">Jalur Pendaftaran</label>
                                <select id="filterJalurMahasiswa" class="form-control">
                                    <option value="">Semua Jalur (Reguler & RPL)</option>
                                    <option value="Reguler">Reguler</option>
                                    <option value="RPL">RPL / Alih Jenjang</option>
                                    <option value="Pindahan">Pindahan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        id="selectFilteredMahasiswaBtn">
                                        <i class="fas fa-check-square me-1"></i>Pilih
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="clearSelectedMahasiswaBtn">
                                        <i class="fas fa-eraser me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteMahasiswaBtn"
                                        disabled>
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="alert alert-light border d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div id="bulkSelectionInfoMahasiswa" class="mb-0 text-muted">
                                Belum ada mahasiswa yang dipilih.
                            </div>
                            <small class="text-muted">Gunakan filter prodi, status, angkatan, dan jalur untuk memudahkan pencarian.</small>
                        </div>

                        <div class="table-responsive">
                            <table id="mahasiswa-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="selectAllMahasiswaPage">
                                        </th>
                                        <th>No</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Prodi</th>
                                        <th>Jalur</th>
                                        <th>Status</th>
                                        <th>Sync Feeder</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    <div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="mahasiswaForm">
                        @csrf
                        <input type="hidden" id="mahasiswaId">

                        <!-- Nav Tabs 5 Klaster -->
                        <ul class="nav nav-tabs nav-line nav-color-primary mb-3" id="mahasiswaModalTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-biodata-btn" data-bs-toggle="tab" href="#tab-biodata" role="tab">
                                    <i class="fas fa-id-card me-1"></i> 1. Biodata
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-domisili-btn" data-bs-toggle="tab" href="#tab-domisili" role="tab">
                                    <i class="fas fa-map-marker-alt me-1"></i> 2. Domisili & Kontak
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-ortu-btn" data-bs-toggle="tab" href="#tab-ortu" role="tab">
                                    <i class="fas fa-users me-1"></i> 3. Orang Tua & Wali
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-akademik-btn" data-bs-toggle="tab" href="#tab-akademik" role="tab">
                                    <i class="fas fa-graduation-cap me-1"></i> 4. Akademik & RPL
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-feeder-btn" data-bs-toggle="tab" href="#tab-feeder" role="tab">
                                    <i class="fas fa-sync-alt me-1"></i> 5. Status Feeder
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-2 mb-3" id="mahasiswaModalTabContent">
                            <!-- TAB 1: BIODATA -->
                            <div class="tab-pane fade show active" id="tab-biodata" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                                        <input type="text" id="nama_mahasiswa" class="form-control" placeholder="Nama lengkap sesuai ijazah/KTP" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                        <input type="text" id="nim" class="form-control" placeholder="Nomor Induk Mahasiswa" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">NIK (KTP) <span class="text-danger">*</span></label>
                                        <input type="text" id="nik" class="form-control" maxlength="16" placeholder="16 digit NIK" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">NISN</label>
                                        <input type="text" id="nisn" class="form-control" maxlength="15" placeholder="Nomor Induk Siswa Nasional">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select id="jenis_kelamin" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                                        <select id="agama" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" id="tempat_lahir" class="form-control" placeholder="Kota/Kabupaten lahir" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" id="tanggal_lahir" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email Akun (User)</label>
                                        <input type="email" id="email" class="form-control" placeholder="Email akun login">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Password Akun</label>
                                        <input type="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: DOMISILI & KONTAK -->
                            <div class="tab-pane fade" id="tab-domisili" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">No Handphone / WhatsApp</label>
                                        <input type="text" id="handphone" class="form-control" placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email Pribadi</label>
                                        <input type="email" id="email_pribadi" class="form-control" placeholder="email.pribadi@gmail.com">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">Alamat Jalan</label>
                                        <textarea id="alamat_jalan" class="form-control" rows="2" placeholder="Nama jalan, nomor rumah, perumahan"></textarea>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">RT / RW</label>
                                        <div class="input-group">
                                            <input type="text" id="rt" class="form-control" placeholder="RT">
                                            <span class="input-group-text">/</span>
                                            <input type="text" id="rw" class="form-control" placeholder="RW">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Kelurahan / Desa</label>
                                        <input type="text" id="kelurahan" class="form-control" placeholder="Nama kelurahan/desa">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Kecamatan / ID Wilayah</label>
                                        <input type="text" id="id_wilayah" class="form-control" placeholder="Nama kecamatan">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Kode Pos</label>
                                        <input type="text" id="kode_pos" class="form-control" maxlength="7" placeholder="5 digit">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: ORANG TUA & WALI -->
                            <div class="tab-pane fade" id="tab-ortu" role="tabpanel">
                                <div class="row">
                                    <!-- SEKSI A: DATA IBU KANDUNG -->
                                    <div class="col-12 mb-2">
                                        <div class="d-flex align-items-center bg-light p-2 rounded border-start border-4 border-danger">
                                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-female text-danger me-2"></i>A. Data Ibu Kandung</h6>
                                            <span class="badge bg-danger ms-auto"><i class="fas fa-exclamation-triangle me-1"></i>Wajib PDDikti Feeder</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-primary">Nama Ibu Kandung <span class="text-danger">*</span></label>
                                        <input type="text" id="nama_ibu_kandung" class="form-control" placeholder="Nama lengkap ibu kandung (wajib)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIK Ibu</label>
                                        <input type="text" id="nik_ibu" class="form-control" maxlength="16" placeholder="16 digit NIK ibu kandung">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Pendidikan Ibu</label>
                                        <select id="pendidikan_ibu" class="form-control">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="PAUD / TK">PAUD / TK</option>
                                            <option value="SD / Sederajat">SD / Sederajat</option>
                                            <option value="SMP / Sederajat">SMP / Sederajat</option>
                                            <option value="SMA / Sederajat">SMA / Sederajat</option>
                                            <option value="D1">D1</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="D4 / S1">D4 / S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-primary">Pekerjaan / Profesi Ibu</label>
                                        <select id="pekerjaan_ibu" class="form-control">
                                            <option value="">-- Pilih Pekerjaan / Profesi --</option>
                                            <option value="Tidak bekerja">Tidak bekerja</option>
                                            <option value="Nelayan">Nelayan</option>
                                            <option value="Petani">Petani</option>
                                            <option value="Peternak">Peternak</option>
                                            <option value="PNS/TNI/Polri">PNS/TNI/Polri</option>
                                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                                            <option value="Pedagang Besar">Pedagang Besar</option>
                                            <option value="Wiraswasta">Wiraswasta</option>
                                            <option value="Wirausaha">Wirausaha</option>
                                            <option value="Buruh">Buruh</option>
                                            <option value="Pensiunan">Pensiunan</option>
                                            <option value="Tenaga Medis / Kesehatan">Tenaga Medis / Kesehatan</option>
                                            <option value="Sudah Meninggal">Sudah Meninggal</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Penghasilan Ibu</label>
                                        <select id="penghasilan_ibu" class="form-control">
                                            <option value="">-- Pilih Penghasilan --</option>
                                            <option value="Kurang dari Rp 500.000">Kurang dari Rp 500.000</option>
                                            <option value="Rp 500.000 - Rp 999.999">Rp 500.000 - Rp 999.999</option>
                                            <option value="Rp 1.000.000 - Rp 1.999.999">Rp 1.000.000 - Rp 1.999.999</option>
                                            <option value="Rp 2.000.000 - Rp 4.999.999">Rp 2.000.000 - Rp 4.999.999</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                            <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                        </select>
                                    </div>

                                    <!-- SEKSI B: DATA AYAH KANDUNG -->
                                    <div class="col-12 mt-2 mb-2">
                                        <div class="d-flex align-items-center bg-light p-2 rounded border-start border-4 border-primary">
                                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-male text-primary me-2"></i>B. Data Ayah Kandung</h6>
                                            <span class="badge bg-secondary ms-auto">Opsional (Jika ada / hidup)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Nama Ayah</label>
                                        <input type="text" id="nama_ayah" class="form-control" placeholder="Nama lengkap ayah kandung">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIK Ayah</label>
                                        <input type="text" id="nik_ayah" class="form-control" maxlength="16" placeholder="16 digit NIK ayah">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Pendidikan Ayah</label>
                                        <select id="pendidikan_ayah" class="form-control">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="PAUD / TK">PAUD / TK</option>
                                            <option value="SD / Sederajat">SD / Sederajat</option>
                                            <option value="SMP / Sederajat">SMP / Sederajat</option>
                                            <option value="SMA / Sederajat">SMA / Sederajat</option>
                                            <option value="D1">D1</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="D4 / S1">D4 / S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-primary">Pekerjaan / Profesi Ayah</label>
                                        <select id="pekerjaan_ayah" class="form-control">
                                            <option value="">-- Pilih Pekerjaan / Profesi --</option>
                                            <option value="Tidak bekerja">Tidak bekerja</option>
                                            <option value="Nelayan">Nelayan</option>
                                            <option value="Petani">Petani</option>
                                            <option value="Peternak">Peternak</option>
                                            <option value="PNS/TNI/Polri">PNS/TNI/Polri</option>
                                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                                            <option value="Pedagang Besar">Pedagang Besar</option>
                                            <option value="Wiraswasta">Wiraswasta</option>
                                            <option value="Wirausaha">Wirausaha</option>
                                            <option value="Buruh">Buruh</option>
                                            <option value="Pensiunan">Pensiunan</option>
                                            <option value="Tenaga Medis / Kesehatan">Tenaga Medis / Kesehatan</option>
                                            <option value="Sudah Meninggal">Sudah Meninggal</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Penghasilan Ayah</label>
                                        <select id="penghasilan_ayah" class="form-control">
                                            <option value="">-- Pilih Penghasilan --</option>
                                            <option value="Kurang dari Rp 500.000">Kurang dari Rp 500.000</option>
                                            <option value="Rp 500.000 - Rp 999.999">Rp 500.000 - Rp 999.999</option>
                                            <option value="Rp 1.000.000 - Rp 1.999.999">Rp 1.000.000 - Rp 1.999.999</option>
                                            <option value="Rp 2.000.000 - Rp 4.999.999">Rp 2.000.000 - Rp 4.999.999</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                            <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                        </select>
                                    </div>

                                    <!-- SEKSI C: DATA WALI -->
                                    <div class="col-12 mt-2 mb-2">
                                        <div class="d-flex align-items-center bg-light p-2 rounded border-start border-4 border-info">
                                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-user-shield text-info me-2"></i>C. Data Wali (Opsional)</h6>
                                            <span class="badge bg-secondary ms-auto">Diisi bila diasuh wali / bukan orang tua</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Nama Wali</label>
                                        <input type="text" id="nama_wali" class="form-control" placeholder="Nama lengkap wali jika ada">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Pendidikan Wali</label>
                                        <select id="pendidikan_wali" class="form-control">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="PAUD / TK">PAUD / TK</option>
                                            <option value="SD / Sederajat">SD / Sederajat</option>
                                            <option value="SMP / Sederajat">SMP / Sederajat</option>
                                            <option value="SMA / Sederajat">SMA / Sederajat</option>
                                            <option value="D1">D1</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="D4 / S1">D4 / S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-primary">Pekerjaan / Profesi Wali</label>
                                        <select id="pekerjaan_wali" class="form-control">
                                            <option value="">-- Pilih Pekerjaan / Profesi --</option>
                                            <option value="Tidak bekerja">Tidak bekerja</option>
                                            <option value="Nelayan">Nelayan</option>
                                            <option value="Petani">Petani</option>
                                            <option value="Peternak">Peternak</option>
                                            <option value="PNS/TNI/Polri">PNS/TNI/Polri</option>
                                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                                            <option value="Pedagang Besar">Pedagang Besar</option>
                                            <option value="Wiraswasta">Wiraswasta</option>
                                            <option value="Wirausaha">Wirausaha</option>
                                            <option value="Buruh">Buruh</option>
                                            <option value="Pensiunan">Pensiunan</option>
                                            <option value="Tenaga Medis / Kesehatan">Tenaga Medis / Kesehatan</option>
                                            <option value="Sudah Meninggal">Sudah Meninggal</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Penghasilan Wali</label>
                                        <select id="penghasilan_wali" class="form-control">
                                            <option value="">-- Pilih Penghasilan --</option>
                                            <option value="Kurang dari Rp 500.000">Kurang dari Rp 500.000</option>
                                            <option value="Rp 500.000 - Rp 999.999">Rp 500.000 - Rp 999.999</option>
                                            <option value="Rp 1.000.000 - Rp 1.999.999">Rp 1.000.000 - Rp 1.999.999</option>
                                            <option value="Rp 2.000.000 - Rp 4.999.999">Rp 2.000.000 - Rp 4.999.999</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                            <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 4: AKADEMIK & RPL -->
                            <div class="tab-pane fade" id="tab-akademik" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                                        <select id="id_prodi" class="form-control" required>
                                            <option value="">-- Pilih Prodi --</option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Angkatan <span class="text-danger">*</span></label>
                                        <input type="number" id="angkatan" class="form-control" min="1990" max="{{ date('Y') + 10 }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Status Mahasiswa <span class="text-danger">*</span></label>
                                        <select id="status" class="form-control" required>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Cuti">Cuti</option>
                                            <option value="DO">Drop Out</option>
                                            <option value="Lulus">Lulus</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Masuk</label>
                                        <input type="date" id="tanggal_masuk" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-primary">Jenis Pendaftaran (Jalur) <span class="text-danger">*</span></label>
                                        <select id="jenis_pendaftaran" class="form-control fw-bold border-primary" required>
                                            <option value="Reguler">Reguler (Mahasiswa Baru Murni)</option>
                                            <option value="RPL">RPL (Alih Jenjang D3 ke S1)</option>
                                            <option value="Pindahan">Pindahan (Transfer Kampus Lain)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Jalur Masuk</label>
                                        <input type="text" id="jalur_masuk" class="form-control" placeholder="Contoh: Mandiri, PMDK">
                                    </div>

                                    <!-- Panel Dinamis Khusus Mahasiswa RPL / Pindahan -->
                                    <div class="col-12" id="rplFieldsContainer" style="display: none;">
                                        <div class="card border border-info bg-light mb-2">
                                            <div class="card-header bg-info text-white py-2">
                                                <h6 class="mb-0"><i class="fas fa-exchange-alt me-1"></i> Informasi Rekognisi Pembelajaran Lampau (RPL / Alih Jenjang)</h6>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-semibold">Perguruan Tinggi Asal</label>
                                                        <input type="text" id="perguruan_tinggi_asal" class="form-control" placeholder="Contoh: Akper Dustira Cimahi">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label fw-semibold">Program Studi Asal</label>
                                                        <input type="text" id="prodi_asal" class="form-control" placeholder="Contoh: D3 Keperawatan">
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <label class="form-label fw-semibold">SKS Diakui</label>
                                                        <input type="number" id="sks_diakui" class="form-control" min="0" placeholder="0">
                                                    </div>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>SKS yang diakui akan otomatis tercatat pada transkrip mahasiswa dan tidak perlu dikontrak ulang pada KRS reguler.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 5: INTEGRASI FEEDER -->
                            <div class="tab-pane fade" id="tab-feeder" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">ID Mahasiswa PDDikti (Biodata UUID)</label>
                                        <input type="text" id="id_mahasiswa_pddikti" class="form-control" placeholder="Diisi otomatis oleh Feeder" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">ID Registrasi Mahasiswa (PT UUID)</label>
                                        <input type="text" id="id_registrasi_mahasiswa_pddikti" class="form-control" placeholder="Diisi otomatis oleh Feeder" readonly>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-light border">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Status sinkronisasi data ini akan dikelola melalui modul <strong>Integrasi Neo Feeder</strong> pada menu BAAK.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-1"></i>Batal</button>
                    <button type="button" class="btn btn-primary" id="submitMahasiswaBtn"><i
                            class="fas fa-save me-1"></i>Simpan</button>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="importMahasiswaModal" tabindex="-1" aria-labelledby="importMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <form id="importMahasiswaForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importMahasiswaModalLabel">
                            <i class="fas fa-file-import me-2"></i>Import Data Mahasiswa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Petunjuk Import:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pilih Program Studi terlebih dahulu</li>
                                <li>Download template dengan klik tombol "Download Template"</li>
                                <li>Isi data sesuai format yang tersedia</li>
                                <li>File yang diperbolehkan: .xlsx, .xls, .csv (maksimal 10MB)</li>
                                <li>Jika kolom angkatan kosong, sistem akan mencoba membaca angkatan dari NIM</li>
                                <li>Pada saat menggunakan fitur ini, sistem akan otomatis membuat akun user mahasiswa
                                    dengan password default <strong>12345678</strong></li>
                                <li>Bisa Dihimbau Mahasiswa Untuk Mengganti Password nya setelah pertama kali login</li>
                            </ul>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="importProdi" class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>Program Studi
                                </label>
                                <select id="importProdi" class="form-control" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['nama_prodi'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" id="downloadTemplateBtn" class="btn btn-info w-100" disabled>
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="importFile" class="form-label">
                                <i class="fas fa-file-excel me-1"></i>Pilih File Import
                            </label>
                            <input type="file" class="form-control" id="importFile" name="file"
                                accept=".xlsx,.xls,.csv" disabled>
                            <div class="form-text">
                                Format file: .xlsx, .xls, .csv (maksimal 10MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-eye me-1"></i>Preview Data
                            </label>
                            <div id="importPreview" class="border rounded p-3 bg-light"
                                style="max-height: 200px; overflow-y: auto;">
                                <p class="text-muted text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Pilih Program Studi terlebih dahulu
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success" id="submitImportBtn">
                            <i class="fas fa-upload me-1"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Hasil Import --}}
    <div class="modal fade" id="importResultModal" tabindex="-1" aria-labelledby="importResultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importResultModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Hasil Import
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="importResultContent">
                        <!-- Content akan diisi via JavaScript -->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-check me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {

            const modal = new bootstrap.Modal('#mahasiswaModal');
            const importModal = new bootstrap.Modal('#importMahasiswaModal');
            const resultModal = new bootstrap.Modal('#importResultModal');
            let mahasiswa = @json($mahasiswa ?? []);
            const kurikulumList = @json($kurikulum ?? []);
            let importRequestInProgress = false;
            let importNeedsManualCheck = false;
            let selectedMahasiswaIds = [];

            function resolveAngkatanFromNim(nim) {
                const rawNim = String(nim || '').trim().replace(/\s+/g, '');
                if (!rawNim) {
                    return null;
                }

                const normalizedNim = /^\d+$/.test(rawNim) && rawNim.length < 7 ?
                    rawNim.padStart(7, '0') :
                    rawNim;

                if (normalizedNim.length < 4) {
                    return null;
                }

                const kodeAngkatan = normalizedNim.substring(2, 4);
                if (!/^\d{2}$/.test(kodeAngkatan)) {
                    return null;
                }

                const angkatan = 2000 + Number(kodeAngkatan);
                const maxYear = new Date().getFullYear() + 1;

                return angkatan >= 2000 && angkatan <= maxYear ? angkatan : null;
            }

            function syncAngkatanFromNim($nimInput, $angkatanInput, { force = false } = {}) {
                const currentAngkatan = String($angkatanInput.val() || '').trim();
                if (!force && currentAngkatan !== '') {
                    return;
                }

                const resolvedAngkatan = resolveAngkatanFromNim($nimInput.val());
                if (resolvedAngkatan !== null) {
                    $angkatanInput.val(resolvedAngkatan);
                }
            }

            function getFilteredMahasiswaData() {
                const prodiId = $('#filterProdiMahasiswa').val();
                const status = ($('#filterStatusMahasiswa').val() || '').trim();
                const angkatan = ($('#filterAngkatanMahasiswa').val() || '').trim();
                const jalur = $('#filterJalurMahasiswa').val();

                return mahasiswa.filter(row => {
                    const matchProdi = !prodiId || row.id_prodi === prodiId;
                    const rowStatus = String(row.status || 'Aktif').trim();
                    const matchStatus = !status || rowStatus.toLowerCase() === status.toLowerCase();
                    const matchAngkatan = !angkatan || String(row.angkatan ?? '') === angkatan;
                    const rowJalur = row.jenis_pendaftaran || 'Reguler';
                    const matchJalur = !jalur || rowJalur === jalur;
                    return matchProdi && matchStatus && matchAngkatan && matchJalur;
                });
            }

            function getSelectedMahasiswaCountOnFilter(filteredRows = getFilteredMahasiswaData()) {
                const filteredIds = filteredRows.map(row => row.id);
                return filteredIds.filter(id => selectedMahasiswaIds.includes(id)).length;
            }

            function syncBulkSelectionInfo(filteredRows = getFilteredMahasiswaData()) {
                const totalSelected = selectedMahasiswaIds.length;
                const selectedOnFilter = getSelectedMahasiswaCountOnFilter(filteredRows);

                $('#bulkDeleteMahasiswaBtn').prop('disabled', totalSelected === 0);
                $('#bulkSelectionInfoMahasiswa').text(
                    totalSelected ?
                    `${totalSelected} mahasiswa dipilih (${selectedOnFilter} pada hasil filter saat ini).` :
                    'Belum ada mahasiswa yang dipilih.'
                );
            }

            const table = $('#mahasiswa-table').DataTable({
                data: getFilteredMahasiswaData(),
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle',
                        render: row =>
                            `<input type="checkbox" class="mahasiswa-checkbox" value="${row.id}" ${selectedMahasiswaIds.includes(row.id) ? 'checked' : ''}>`
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'nama_mahasiswa',
                        render: (data, type, row) => {
                            const hp = row.handphone ? `<br><small class="text-muted"><i class="fas fa-phone-alt me-1"></i>${row.handphone}</small>` : '';
                            return `<strong>${data || '-'}</strong>${hp}`;
                        }
                    },
                    {
                        data: 'nim',
                        render: data => `<code>${data || '-'}</code>`
                    },
                    {
                        data: null,
                        render: row => {
                            const prodiNama = row.prodi?.nama_prodi ?? '-';
                            const angkatan = row.angkatan ? ` <span class="badge bg-light text-dark border">Angk. ${row.angkatan}</span>` : '';
                            return `<div>${prodiNama}${angkatan}</div>`;
                        }
                    },
                    {
                        data: 'jenis_pendaftaran',
                        render: (val, type, row) => {
                            const jalur = val || row.jenis_pendaftaran || 'Reguler';
                            if (jalur === 'RPL') {
                                return `<span class="badge bg-info text-dark" title="Rekognisi Pembelajaran Lampau"><i class="fas fa-exchange-alt me-1"></i>RPL</span>`;
                            } else if (jalur === 'Pindahan') {
                                return `<span class="badge bg-warning text-dark" title="Mahasiswa Transfer"><i class="fas fa-route me-1"></i>Pindahan</span>`;
                            }
                            return `<span class="badge bg-primary">Reguler</span>`;
                        }
                    },
                    {
                        data: 'status',
                        render: status => {
                            const badgeClass = {
                                'Aktif': 'success',
                                'Cuti': 'warning',
                                'DO': 'danger',
                                'Lulus': 'info'
                            } [status] || 'secondary';
                            return `<span class="badge bg-${badgeClass}">${status || 'Aktif'}</span>`;
                        }
                    },
                    {
                        data: 'feeder_sync_status',
                        render: (status, type, row) => {
                            if (status === 'sukses') {
                                return `<span class="badge bg-success" title="Tersinkron PDDikti"><i class="fas fa-check-circle me-1"></i>Sinkron</span>`;
                            } else if (status === 'gagal') {
                                return `<span class="badge bg-danger" title="${row.feeder_last_error || 'Gagal sinkron'}"><i class="fas fa-exclamation-triangle me-1"></i>Gagal</span>`;
                            } else {
                                return `<span class="badge bg-secondary" title="Belum pernah disinkron"><i class="fas fa-clock me-1"></i>Belum</span>`;
                            }
                        }
                    },
                    {
                        data: null,
                        render: row => {
                            const isRpl = ['RPL', 'Pindahan'].includes(row.jenis_pendaftaran);
                            const konversiBtn = isRpl ? `
                                <a href="{{ route('akademik.nilai-transfer.index') }}?mahasiswa_id=${row.id}" class="btn btn-info btn-sm text-dark" title="Kelola Konversi Nilai RPL/Transfer">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                            ` : '';

                            return `
                                <div class="d-flex justify-content-center gap-2">
                                    ${konversiBtn}
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>`;
                        }
                    }
                ],
                language: {
                    url: '{{ asset('template/assets/js/plugin/datatables/i18n/id.json ') }}'
                },
                drawCallback: () => {
                    $('#tableLoader').addClass('hidden');
                    syncSelectAllCurrentPageState();
                    syncBulkSelectionInfo();
                }
            });

            setTimeout(() => $('#tableLoader').addClass('hidden'), 500);

            function syncSelectAllCurrentPageState() {
                const rows = table.rows({
                    page: 'current'
                }).data().toArray();
                const ids = rows.map(row => row.id);
                const checkedCount = ids.filter(id => selectedMahasiswaIds.includes(id)).length;

                $('#selectAllMahasiswaPage')
                    .prop('checked', ids.length > 0 && checkedCount === ids.length)
                    .prop('indeterminate', checkedCount > 0 && checkedCount < ids.length);
            }

            function refreshMahasiswaTable() {
                const filteredRows = getFilteredMahasiswaData();
                table.clear().rows.add(filteredRows).draw();
                syncBulkSelectionInfo(filteredRows);
            }

            $('#filterProdiMahasiswa, #filterStatusMahasiswa, #filterAngkatanMahasiswa, #filterJalurMahasiswa').on('change keyup', function() {
                refreshMahasiswaTable();
            });

            $('#selectFilteredMahasiswaBtn').on('click', function() {
                const filteredIds = getFilteredMahasiswaData().map(row => row.id);
                selectedMahasiswaIds = [...new Set([...selectedMahasiswaIds, ...filteredIds])];
                refreshMahasiswaTable();
            });

            $('#clearSelectedMahasiswaBtn').on('click', function() {
                selectedMahasiswaIds = [];
                refreshMahasiswaTable();
            });

            $('#selectAllMahasiswaPage').on('change', function() {
                const checked = $(this).is(':checked');
                const pageRows = table.rows({
                    page: 'current'
                }).data().toArray();
                const pageIds = pageRows.map(row => row.id);

                if (checked) {
                    selectedMahasiswaIds = [...new Set([...selectedMahasiswaIds, ...pageIds])];
                } else {
                    selectedMahasiswaIds = selectedMahasiswaIds.filter(id => !pageIds.includes(id));
                }

                refreshMahasiswaTable();
            });

            $(document).on('change', '.mahasiswa-checkbox', function() {
                const id = $(this).val();

                if ($(this).is(':checked')) {
                    if (!selectedMahasiswaIds.includes(id)) {
                        selectedMahasiswaIds.push(id);
                    }
                } else {
                    selectedMahasiswaIds = selectedMahasiswaIds.filter(item => item !== id);
                }

                syncSelectAllCurrentPageState();
                syncBulkSelectionInfo();
            });

            $('#bulkDeleteMahasiswaBtn').on('click', function() {
                if (!selectedMahasiswaIds.length) {
                    Swal.fire('Peringatan', 'Pilih minimal satu mahasiswa terlebih dahulu.', 'warning');
                    return;
                }

                const selectedRows = mahasiswa.filter(row => selectedMahasiswaIds.includes(row.id));
                const previewNames = selectedRows.slice(0, 3).map(row => row.nama_mahasiswa).join(', ');
                const extraLabel = selectedRows.length > 3 ? ` dan ${selectedRows.length - 3} lainnya` : '';
                const button = $(this);

                Swal.fire({
                    title: 'Hapus mahasiswa terpilih?',
                    html: `Total <strong>${selectedRows.length}</strong> mahasiswa akan dihapus.<br><small>${previewNames}${extraLabel}</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus semua',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    button.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i>Menghapus...');

                    $.ajax({
                        url: "{{ route('mahasiswa.bulk-destroy') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedMahasiswaIds
                        },
                        success: res => {
                            const deletedIds = res.data?.deleted_ids ??
                                selectedMahasiswaIds;
                            mahasiswa = mahasiswa.filter(row => !deletedIds.includes(row
                                .id));
                            selectedMahasiswaIds = selectedMahasiswaIds.filter(id => !
                                deletedIds.includes(id));
                            refreshMahasiswaTable();
                            Swal.fire('Berhasil', res.message ??
                                'Data mahasiswa terpilih berhasil dihapus.',
                                'success');
                        },
                        error: xhr => {
                            logError(xhr);
                            Swal.fire('Gagal', xhr.responseJSON?.message ||
                                'Tidak dapat menghapus data mahasiswa terpilih.',
                                'error');
                        },
                        complete: () => {
                            button.prop('disabled', selectedMahasiswaIds.length === 0)
                                .html(
                                     '<i class="fas fa-trash me-1"></i>Hapus Terpilih');
                        }
                    });
                });
            });

            function toggleRplFields() {
                const val = $('#jenis_pendaftaran').val();
                if (val === 'RPL' || val === 'Pindahan') {
                    $('#rplFieldsContainer').slideDown(200);
                } else {
                    $('#rplFieldsContainer').slideUp(200);
                }
            }

            $('#jenis_pendaftaran').on('change', toggleRplFields);

            // Tambah
            $('#addMahasiswaBtn').click(() => {
                $('#mahasiswaForm')[0].reset();
                clearFormErrors('#mahasiswaForm');
                $('#mahasiswaId').val('');
                $('#modalTitle').text('Tambah Mahasiswa');
                $('#password').prop('required', true).attr('placeholder', '');

                // Reset dropdown orang tua & wali
                $('#pendidikan_ibu, #pekerjaan_ibu, #penghasilan_ibu').val('');
                $('#pendidikan_ayah, #pekerjaan_ayah, #penghasilan_ayah').val('');
                $('#pendidikan_wali, #pekerjaan_wali, #penghasilan_wali').val('');

                // Reset tab aktif ke tab 1 (Biodata)
                const firstTabTrigger = document.querySelector('#tab-biodata-btn');
                if (firstTabTrigger) {
                    bootstrap.Tab.getOrCreateInstance(firstTabTrigger).show();
                }

                $('#jenis_pendaftaran').val('Reguler');
                toggleRplFields();
                $('#id_mahasiswa_pddikti').val('');
                $('#id_registrasi_mahasiswa_pddikti').val('');
                modal.show();
            });

            $('#nim').on('input blur', function() {
                syncAngkatanFromNim($('#nim'), $('#angkatan'));
            });

            function clearFormErrors(formSelector) {
                const form = $(formSelector);
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback.dynamic-error').remove();
            }

            function normalizeDateInputValue(value) {
                if (!value) {
                    return '';
                }

                const stringValue = String(value).trim();
                const match = stringValue.match(/^(\d{4}-\d{2}-\d{2})/);

                return match ? match[1] : '';
            }

            function applyFormErrors(fieldMap, errors = {}) {
                Object.entries(errors).forEach(([field, messages]) => {
                    const selector = fieldMap[field];
                    if (!selector) {
                        return;
                    }

                    const input = $(selector);
                    if (!input.length) {
                        return;
                    }

                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback.dynamic-error').remove();
                    input.after(
                        `<div class="invalid-feedback dynamic-error">${Array.isArray(messages) ? messages[0] : messages}</div>`
                    );
                });
            }

            // Simpan / Update
            $('#submitMahasiswaBtn').on('click', function(e) {
                e.preventDefault();

                const id = $('#mahasiswaId').val();
                const url = id ?
                    "{{ route('mahasiswa.update', ':id') }}".replace(':id', id) :
                    "{{ route('mahasiswa.store') }}";
                const method = 'POST';

                const submitBtn = $(this);
                const originalHtml = submitBtn.html();
                clearFormErrors('#mahasiswaForm');

                // Disable button dan tampilkan loading
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');

                $.ajax({
                    url,
                    type: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        ...(id ? {
                            _method: 'PUT'
                        } : {}),
                        // Tab 1: Biodata
                        nim: $('#nim').val(),
                        nik: $('#nik').val(),
                        nisn: $('#nisn').val() || null,
                        nama_mahasiswa: $('#nama_mahasiswa').val(),
                        jenis_kelamin: $('#jenis_kelamin').val(),
                        agama: $('#agama').val(),
                        tempat_lahir: $('#tempat_lahir').val() || null,
                        tanggal_lahir: $('#tanggal_lahir').val() || null,
                        handphone: $('#handphone').val() || null,
                        email: $('#email').val() || null,
                        email_pribadi: $('#email_pribadi').val() || null,
                        password: $('#password').val() || null,

                        // Tab 2: Domisili & Kontak
                        alamat: $('#alamat_jalan').val() || null,
                        alamat_jalan: $('#alamat_jalan').val() || null,
                        rt: $('#rt').val() || null,
                        rw: $('#rw').val() || null,
                        kelurahan: $('#kelurahan').val() || null,
                        id_wilayah: $('#id_wilayah').val() || null,
                        kode_pos: $('#kode_pos').val() || null,

                        // Tab 3: Orang Tua & Wali
                        nama_ibu_kandung: $('#nama_ibu_kandung').val() || null,
                        nik_ibu: $('#nik_ibu').val() || null,
                        pendidikan_ibu: $('#pendidikan_ibu').val() || null,
                        pekerjaan_ibu: $('#pekerjaan_ibu').val() || null,
                        penghasilan_ibu: $('#penghasilan_ibu').val() || null,
                        nama_ayah: $('#nama_ayah').val() || null,
                        nik_ayah: $('#nik_ayah').val() || null,
                        pendidikan_ayah: $('#pendidikan_ayah').val() || null,
                        pekerjaan_ayah: $('#pekerjaan_ayah').val() || null,
                        penghasilan_ayah: $('#penghasilan_ayah').val() || null,
                        nama_wali: $('#nama_wali').val() || null,
                        pendidikan_wali: $('#pendidikan_wali').val() || null,
                        pekerjaan_wali: $('#pekerjaan_wali').val() || null,
                        penghasilan_wali: $('#penghasilan_wali').val() || null,

                        // Tab 4: Akademik & RPL
                        id_prodi: $('#id_prodi').val(),
                        angkatan: $('#angkatan').val() || null,
                        status: $('#status').val(),
                        tanggal_masuk: $('#tanggal_masuk').val() || null,
                        jenis_pendaftaran: $('#jenis_pendaftaran').val(),
                        jalur_masuk: $('#jalur_masuk').val() || null,
                        perguruan_tinggi_asal: $('#perguruan_tinggi_asal').val() || null,
                        prodi_asal: $('#prodi_asal').val() || null,
                        sks_diakui: $('#sks_diakui').val() || 0
                    },
                    success: res => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message ?? 'Data disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        modal.hide();
                        location.reload();
                    },
                    error: err => {
                        const response = err.responseJSON || {};
                        const backendErrors = response.errors?.errors || response.errors || {};
                        let firstError = null;
                        if (typeof backendErrors === 'object' && backendErrors !== null) {
                            for (const val of Object.values(backendErrors)) {
                                if (Array.isArray(val) && typeof val[0] === 'string') {
                                    firstError = val[0];
                                    break;
                                } else if (typeof val === 'string' && val.length > 0) {
                                    firstError = val;
                                    break;
                                }
                            }
                        }

                        applyFormErrors({
                            nim: '#nim',
                            nik: '#nik',
                            nisn: '#nisn',
                            nama_mahasiswa: '#nama_mahasiswa',
                            id_prodi: '#id_prodi',
                            jenis_kelamin: '#jenis_kelamin',
                            tempat_lahir: '#tempat_lahir',
                            tanggal_lahir: '#tanggal_lahir',
                            tanggal_masuk: '#tanggal_masuk',
                            handphone: '#handphone',
                            email_pribadi: '#email_pribadi',
                            alamat_jalan: '#alamat_jalan',
                            nama_ibu_kandung: '#nama_ibu_kandung',
                            nik_ibu: '#nik_ibu',
                            pekerjaan_ibu: '#pekerjaan_ibu',
                            nama_ayah: '#nama_ayah',
                            nik_ayah: '#nik_ayah',
                            pekerjaan_ayah: '#pekerjaan_ayah',
                            nama_wali: '#nama_wali',
                            pekerjaan_wali: '#pekerjaan_wali',
                            agama: '#agama',
                            status: '#status',
                            angkatan: '#angkatan',
                            email: '#email',
                            password: '#password',
                            jenis_pendaftaran: '#jenis_pendaftaran',
                            sks_diakui: '#sks_diakui'
                        }, backendErrors);

                        const alertMsg = firstError || response.error || response.message ||
                            'Terjadi kesalahan saat menyimpan data.';
                        Swal.fire('Gagal', alertMsg, 'error');
                    },
                    complete: () => {
                        // Enable button kembali
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.show', ':id') }}".replace(':id', id);

                $.get(url, res => {
                    clearFormErrors('#mahasiswaForm');
                    const m = res.data ?? res;

                    // Tab 1: Biodata
                    $('#mahasiswaId').val(m.id);
                    $('#nama_mahasiswa').val(m.nama_mahasiswa || '');
                    $('#nim').val(m.nim || '');
                    $('#nik').val(m.nik || '');
                    $('#nisn').val(m.nisn || '');
                    $('#jenis_kelamin').val(m.jenis_kelamin || 'L');
                    $('#agama').val(m.agama || 'Islam');
                    $('#tempat_lahir').val(m.tempat_lahir || '');
                    $('#tanggal_lahir').val(normalizeDateInputValue(m.tanggal_lahir));
                    $('#handphone').val(m.handphone || '');
                    $('#email').val(m.user?.email || '');
                    $('#email_pribadi').val(m.email_pribadi || '');

                    // Tab 2: Domisili & Kontak
                    $('#alamat_jalan').val(m.alamat_jalan || m.alamat || '');
                    $('#rt').val(m.rt || '');
                    $('#rw').val(m.rw || '');
                    $('#kelurahan').val(m.kelurahan || '');
                    $('#id_wilayah').val(m.id_wilayah || '');
                    $('#kode_pos').val(m.kode_pos || '');

                    // Tab 3: Orang Tua & Wali
                    $('#nama_ibu_kandung').val(m.nama_ibu_kandung || '');
                    $('#nik_ibu').val(m.nik_ibu || '');
                    $('#pendidikan_ibu').val(m.pendidikan_ibu || '');
                    $('#pekerjaan_ibu').val(m.pekerjaan_ibu || '');
                    $('#penghasilan_ibu').val(m.penghasilan_ibu || '');
                    $('#nama_ayah').val(m.nama_ayah || '');
                    $('#nik_ayah').val(m.nik_ayah || '');
                    $('#pendidikan_ayah').val(m.pendidikan_ayah || '');
                    $('#pekerjaan_ayah').val(m.pekerjaan_ayah || '');
                    $('#penghasilan_ayah').val(m.penghasilan_ayah || '');
                    $('#nama_wali').val(m.nama_wali || '');
                    $('#pendidikan_wali').val(m.pendidikan_wali || '');
                    $('#pekerjaan_wali').val(m.pekerjaan_wali || '');
                    $('#penghasilan_wali').val(m.penghasilan_wali || '');

                    // Tab 4: Akademik & RPL
                    $('#id_prodi').val(m.id_prodi || '');
                    $('#angkatan').val(m.angkatan || '');
                    $('#status').val(m.status || 'Aktif');
                    $('#tanggal_masuk').val(normalizeDateInputValue(m.tanggal_masuk));
                    $('#jenis_pendaftaran').val(m.jenis_pendaftaran || 'Reguler');
                    $('#jalur_masuk').val(m.jalur_masuk || '');
                    $('#perguruan_tinggi_asal').val(m.perguruan_tinggi_asal || '');
                    $('#prodi_asal').val(m.prodi_asal || '');
                    $('#sks_diakui').val(m.sks_diakui || '');
                    toggleRplFields();

                    // Tab 5: Feeder Sync Info
                    $('#id_mahasiswa_pddikti').val(m.id_mahasiswa_pddikti || '-');
                    $('#id_registrasi_mahasiswa_pddikti').val(m.id_registrasi_mahasiswa_pddikti || '-');

                    if (!m.angkatan) {
                        syncAngkatanFromNim($('#nim'), $('#angkatan'), { force: true });
                    }

                    // Reset password field untuk edit (tidak wajib diisi)
                    $('#password').prop('required', false).attr('placeholder',
                        'Kosongkan jika tidak ingin mengubah password');

                    // Reset tab aktif ke tab 1 (Biodata)
                    const firstTabTrigger = document.querySelector('#tab-biodata-btn');
                    if (firstTabTrigger) {
                        bootstrap.Tab.getOrCreateInstance(firstTabTrigger).show();
                    }

                    $('#modalTitle').text('Edit Mahasiswa');
                    modal.show();

                }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data', 'error'));
            });

            // Hapus
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('mahasiswa.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Hapus mahasiswa ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: res => {
                                Swal.fire('Berhasil', res.message ?? 'Data dihapus',
                                    'success');
                                mahasiswa = mahasiswa.filter(row => row.id !== id);
                                selectedMahasiswaIds = selectedMahasiswaIds.filter(
                                    item => item !== id);
                                refreshMahasiswaTable();
                            },
                            error: xhr => {
                                const errorMessage =
                                    xhr.responseJSON?.message ||
                                    xhr.responseJSON?.errors?.message ||
                                    'Tidak dapat menghapus data.';

                                Swal.fire('Gagal', errorMessage, 'error');
                            }
                        });
                    }
                });
            });

            // Import Mahasiswa
            $('#importMahasiswaBtn').click(() => {
                $('#importMahasiswaForm')[0].reset();
                $('#importProdi').val('').trigger('change');
                importRequestInProgress = false;
                importNeedsManualCheck = false;
                $('#submitImportBtn').prop('disabled', false).html(
                    '<i class="fas fa-upload me-1"></i>Import Data');
                $('#importPreview').html(`
                                    <p class="text-muted text-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pilih Program Studi terlebih dahulu
                                    </p>
                                `);
                importModal.show();
            });

            // Handle pemilihan prodi
            $('#importProdi').on('change', function() {
                const prodiId = $(this).val();
                const downloadBtn = $('#downloadTemplateBtn');
                const fileInput = $('#importFile');
                const preview = $('#importPreview');

                if (prodiId) {
                    // Enable download template button
                    downloadBtn.prop('disabled', false).html(`
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    `);

                    // Enable file input
                    fileInput.prop('disabled', false);

                    // Update preview
                    preview.html(`
                                        <p class="text-muted text-center">
                                            <i class="fas fa-check-circle me-2 text-success"></i>
                                            Program Studi dipilih. Silakan download template atau pilih file import.
                                        </p>
                                    `);
                } else {
                    // Disable buttons
                    downloadBtn.prop('disabled', true).html(`
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    `);
                    fileInput.prop('disabled', true);

                    // Reset preview
                    preview.html(`
                                        <p class="text-muted text-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Pilih Program Studi terlebih dahulu
                                        </p>
                                    `);
                }
            });

            // Export Data Mahasiswa
            $('#exportMahasiswaBtn').on('click', function() {
                const prodiId = $('#filterProdiMahasiswa').val();
                const status = $('#filterStatusMahasiswa').val();
                const jalur = $('#filterJalurMahasiswa').val();
                let url = "{{ route('mahasiswa.export.data') }}";
                const params = new URLSearchParams();
                if (prodiId) params.append('id_prodi', prodiId);
                if (status) params.append('status', status);
                if (jalur) params.append('jenis_pendaftaran', jalur);
                const queryString = params.toString();
                if (queryString) {
                    url += '?' + queryString;
                }
                window.open(url, '_blank');
            });

            // Download template
            $('#downloadTemplateBtn').on('click', function() {
                const prodiId = $('#importProdi').val();

                if (!prodiId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pilih Program Studi terlebih dahulu!'
                    });
                    return;
                }

                // Show loading
                const btn = $(this);
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Mengunduh...');

                // Download template
                const templateUrl = `/mahasiswa/export/template/${prodiId}`;
                window.open(templateUrl, '_blank');

                // Reset button after 1 second
                setTimeout(() => {
                    btn.prop('disabled', false).html(originalHtml);
                }, 1000);
            });

            // File preview saat file dipilih
            $('#importFile').on('change', function() {
                const file = this.files[0];
                const preview = $('#importPreview');

                if (file) {
                    // Validasi file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        preview.html(
                            '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Ukuran file terlalu besar! Maksimal 10MB</div>'
                        );
                        $(this).val('');
                        return;
                    }

                    // Validasi file type
                    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv'
                    ];
                    if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                        preview.html(
                            '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Format file tidak didukung! Gunakan .xlsx, .xls, atau .csv</div>'
                        );
                        $(this).val('');
                        return;
                    }

                    // Tampilkan info file
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    preview.html(`
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>File Siap Diimport:</strong><br>
                                            Nama: ${file.name}<br>
                                            Ukuran: ${fileSize} MB<br>
                                            Tipe: ${file.type || 'Unknown'}
                                        </div>
                                    `);
                } else {
                    preview.html('<p class="text-muted text-center">Belum ada file yang dipilih</p>');
                }
            });

            // Submit import form
            $('#importMahasiswaForm').on('submit', function(e) {
                e.preventDefault();

                if (importRequestInProgress) {
                    return;
                }

                if (importNeedsManualCheck) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cek Data Terlebih Dahulu',
                        html: 'Import sebelumnya mengalami timeout. Silakan cek dulu data mahasiswa yang sudah masuk sebelum mengulangi import file yang sama.'
                    });
                    return;
                }

                const prodiId = $('#importProdi').val();
                if (!prodiId) {
                    Swal.fire('Perhatian', 'Pilih Program Studi terlebih dahulu.', 'warning');
                    return;
                }

                const fileInput = $('#importFile')[0];
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    Swal.fire('Perhatian', 'Pilih file yang akan diimport.', 'warning');
                    return;
                }

                const file = fileInput.files[0];
                const fileSizeMB = file.size / (1024 * 1024);
                if (fileSizeMB > 10) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: `Ukuran file (${fileSizeMB.toFixed(2)} MB) melebihi batas maksimal 10 MB.`
                    });
                    return;
                }

                const formData = new FormData(this);
                const submitBtn = $('#submitImportBtn');
                importRequestInProgress = true;

                // Disable button dan tampilkan loading
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Mengimport...');

                $.ajax({
                    url: "{{ route('mahasiswa.import', ':id_prodi') }}".replace(':id_prodi', prodiId),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            // Tampilkan hasil import
                            showImportResult(res.data.data);
                            importModal.hide();
                            resultModal.show();

                            // Tangani event ketika modal ditutup (misalnya dengan tombol close atau klik di luar modal)
                            $(resultModal._element).one('hidden.bs.modal', function() {
                                // Refresh halaman ketika modal ditutup
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Import Gagal',
                                text: res.message || 'Terjadi kesalahan saat import',
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sesi Telah Berakhir',
                                html: 'Sesi browser Anda telah kedaluwarsa atau token keamanan tidak valid.<br>Halaman akan dimuat ulang untuk memperbarui sesi.',
                                confirmButtonText: '<i class="fas fa-sync-alt me-1"></i> Muat Ulang Halaman',
                                allowOutsideClick: false
                            }).then(() => {
                                window.location.reload();
                            });
                            return;
                        }

                        let errorMessage = 'Terjadi kesalahan saat import';

                        if (xhr.responseJSON) {
                            const errors = xhr.responseJSON.errors;
                            if (errors && Object.keys(errors).length > 0) {
                                errorMessage = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        if (xhr.status === 504) {
                            importNeedsManualCheck = true;
                            errorMessage +=
                                '<br><br><strong>Catatan:</strong> Cek dulu data mahasiswa di tabel. Bisa jadi proses import di server sudah selesai walaupun browser timeout. Tombol import akan ditahan sementara untuk mencegah double import.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Import Gagal',
                            html: errorMessage,
                            timer: 5000
                        });
                    },
                    complete: function() {
                        importRequestInProgress = false;

                        if (importNeedsManualCheck) {
                            submitBtn.prop('disabled', true).html(
                                '<i class="fas fa-exclamation-triangle me-1"></i>Cek Data Dulu'
                            );
                            return;
                        }

                        // Enable button kembali
                        submitBtn.prop('disabled', false).html(
                            '<i class="fas fa-upload me-1"></i>Import Data');
                    }
                });
            });

            // Fungsi untuk menampilkan hasil import
            function showImportResult(data) {
                const resultContent = $('#importResultContent');

                const errors = Array.isArray(data.errors) ? data.errors : [];
                const angkatanErrors = errors.filter(error =>
                    /angkatan|format nim|nim .*tidak dapat digunakan untuk menentukan angkatan/i.test(String(error))
                );
                const otherErrors = errors.filter(error => !angkatanErrors.includes(error));

                let html = `
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">${data.total_rows || 0}</h5>
                                                    <p class="card-text">Total Data</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-success">${data.success_count || 0}</h5>
                                                    <p class="card-text">Berhasil</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-danger">${data.error_count || 0}</h5>
                                                    <p class="card-text">Gagal</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                if (errors.length > 0) {
                    html += `
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title text-warning mb-1">${angkatanErrors.length}</h5>
                                                    <p class="card-text mb-0">Error Angkatan / Format NIM</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-secondary">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title text-secondary mb-1">${otherErrors.length}</h5>
                                                    <p class="card-text mb-0">Error Data Lainnya</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                }

                // Tampilkan error jika ada
                if (errors.length > 0) {
                    if (angkatanErrors.length > 0) {
                        html += `
                                        <div class="mt-3">
                                            <h6><i class="fas fa-user-graduate me-2 text-warning"></i>Error Angkatan / Format NIM</h6>
                                            <div class="alert alert-warning" style="max-height: 220px; overflow-y: auto;">
                                                <div class="small text-muted mb-2">
                                                    Periksa kembali kolom NIM atau isi kolom angkatan secara manual pada baris yang gagal.
                                                </div>
                                                <ul class="mb-0">
                                                    ${angkatanErrors.map(error => `<li>${error}</li>`).join('')}
                                                </ul>
                                            </div>
                                        </div>
                                    `;
                    }

                    if (otherErrors.length > 0) {
                        html += `
                                        <div class="mt-3">
                                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Detail Error Lainnya:</h6>
                                            <div class="alert alert-warning" style="max-height: 300px; overflow-y: auto;">
                                                <ul class="mb-0">
                                                    ${otherErrors.map(error => `<li>${error}</li>`).join('')}
                                                </ul>
                                            </div>
                                        </div>
                                    `;
                    }
                }

                resultContent.html(html);
            }

        });
    </script>
@endpush
