<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info bg-opacity-25 py-2">
            <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"></h4>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn btn-primary btn-add-jadwal">
                        <i class="fas fa-plus me-1"></i> TAMBAH JADWAL KELAS
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 bg-light">
                        <div class="text-muted small">Kapasitas Peserta Kelas</div>
                        <div class="fs-5 fw-semibold">{{ $kelasKuliah['kapasitas_peserta'] ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 bg-light">
                        <div class="text-muted small">Nama Kelas</div>
                        <div class="fs-5 fw-semibold">{{ $kelasKuliah['nama_kelas'] ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 bg-light">
                        <div class="text-muted small">Mata Kuliah</div>
                        <div class="fs-6 fw-semibold">{{ $kelasKuliah['mata_kuliah']['nama_mk'] ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center" width="5%">Hari</th>
                            <th class="text-center" width="5%">Jam Mulai</th>
                            <th class="text-center" width="5%">Jam Selesai</th>
                            <th class="text-center" width="15%">Ruang</th>
                            <th class="text-center" width="12%">Kapasitas Ruang</th>
                            <th class="text-center" width="5%">Status Kapasitas</th>
                            <th class="text-center" width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalKelas as $index => $item)
                            @php
                                $kapasitasKelas = $kelasKuliah['kapasitas_peserta'] ?? null;
                                $kapasitasRuang = $item['ruang']['kapasitas'] ?? ($item['kapasitas_ruang'] ?? null);
                                $ruangCukup =
                                    filled($kapasitasKelas) && filled($kapasitasRuang)
                                        ? (int) $kapasitasRuang >= (int) $kapasitasKelas
                                        : null;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $item['hari'] ?? '-' }}</td>
                                <td class="text-center"> {{ \Carbon\Carbon::parse($item['jam_mulai'])->format('H:i') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item['jam_selesai'])->format('H:i') }}
                                </td>
                                <td class="text-center">
                                    {{ $item['ruang']['nama_ruang'] ?? ($item['nama_ruang'] ?? '-') }}
                                </td>
                                <td class="text-center">
                                    {{ $kapasitasRuang ?? '-' }}
                                </td>
                                <td class="text-center">
                                    @if (is_null($ruangCukup))
                                        <span class="badge bg-secondary">Belum Ada Data</span>
                                    @elseif ($ruangCukup)
                                        <span class="badge bg-success">Cukup</span>
                                    @else
                                        <span class="badge bg-danger">Kurang</span>
                                    @endif
                                </td>
                                <td class="text-center">

                                    <button class="btn btn-sm btn-warning btn-edit-jadwal text-white"
                                        data-id="{{ $item['id'] }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete-jadwal"
                                        data-id="{{ $item['id'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- MODAL --}}
<div class="modal fade" id="modalJadwal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">TAMBAH JADWAL KULIAH</h5>
                <div class="d-flex gap-2">
                    <button type="submit" form="formJadwal" class="btn btn-sm btn-success"><i
                            class="fas fa-save me-1"></i>
                        Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> <i
                            class="fas fa-times me-1"></i> Tutup</button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formJadwal">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="alert alert-warning d-none" id="jadwalValidationAlert"></div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Hari</label>
                            <select name="hari" id="hari" class="form-control select2">
                                <option value="">Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                            <div class="invalid-feedback d-block" data-error-for="hari"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jam Mulai</label>
                            <input type="time" step="1" name="jam_mulai" class="form-control">
                            <div class="invalid-feedback d-block" data-error-for="jam_mulai"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jam Selesai</label>
                            <input type="time" step="1" name="jam_selesai" class="form-control">
                            <div class="invalid-feedback d-block" data-error-for="jam_selesai"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Ruang Kuliah</label>
                            <select name="id_ruang" id="id_ruang" class="form-control select2">
                                <option value="">Pilih Ruang Kuliah</option>
                                @foreach ($ruangKuliah ?? [] as $ruang)
                                    <option value="{{ $ruang['id'] }}">
                                        {{ $ruang['kode_ruang'] ?? '-' }} - {{ $ruang['nama_ruang'] ?? '-' }}
                                        @if (!empty($ruang['kapasitas']))
                                            (Kapasitas: {{ $ruang['kapasitas'] }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih ruang untuk membantu validasi bentrok ruang dan kecukupan
                                kapasitas.</small>
                            <div class="invalid-feedback d-block" data-error-for="id_ruang"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
