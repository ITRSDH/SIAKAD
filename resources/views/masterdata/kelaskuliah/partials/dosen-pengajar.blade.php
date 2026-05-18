<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info bg-opacity-25 py-2">
            <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"></h4>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn btn-primary btn-add">
                        <i class="fas fa-plus me-1"></i> TAMBAH AKTIVITAS DOSEN MENGAJAR
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2" width="5%">No</th>
                            <th class="text-center" rowspan="2" width="5%">NIDN</th>
                            <th class="text-center" rowspan="2" width="15%">Nama Dosen</th>
                            <th class="text-center" rowspan="2" width="5%">Bobot(SKS)</th>

                            <th class="text-center" colspan="2" width="10%">Pertemuan</th>

                            <th class="text-center" rowspan="2" width="5%">Aksi</th>

                        </tr>
                        <tr>
                            <th class="text-center">Rencana</th>
                            <th class="text-center">Realisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosen_pa as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $item['nidn'] ?? '-' }}</td>
                                <td>{{ $item['nama_dosen'] ?? '-' }}</td>
                                <td class="text-center">{{ $item['sks_substansi_total'] ?? '-' }}</td>
                                <td class="text-center">{{ $item['rencana_tatap_muka'] ?? '-' }}</td>
                                <td class="text-center">{{ $item['realisasi_tatap_muka'] ?? '-' }}</td>
                                <td class="text-center">

                                    <button class="btn btn-sm btn-warning btn-edit text-white"
                                        data-id="{{ $item['id'] }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $item['id'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL SKS</td>
                            <td class="text-center fw-bold">{{ $totalSks }}</td>
                            <td colspan="3">
                                @unless ($isValid)
                                    <span class="text-danger fst-italic">
                                        Jumlah sks Dosen berbeda dengan sks Matakuliah
                                    </span>
                                @endunless
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- MODAL --}}
<div class="modal fade" id="modalDosen">
    <div class="modal-dialog modal-dialog-centered modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">TAMBAH AKTIVITAS DOSEN MENGAJAR</h5>
                <div class="d-flex gap-2">
                    <button type="submit" form="formDosen" class="btn btn-sm btn-success"><i
                            class="fas fa-save me-1"></i>
                        Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> <i
                            class="fas fa-times me-1"></i> Tutup</button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formDosen">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="alert alert-warning d-none" id="dosenValidationAlert"></div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Dosen</label>
                            <select name="id_registrasi_dosen" class="form-control select2" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d['id'] }}">{{ $d['dosen_pengajar'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block" data-error-for="id_registrasi_dosen"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>SKS</label>
                            <input type="number" name="sks_substansi_total" class="form-control" step="0.01"
                                min="0">
                            <div class="invalid-feedback d-block" data-error-for="sks_substansi_total"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Rencana Pertemuan</label>
                            <div class="input-group">
                                <input type="number" name="rencana_tatap_muka" class="form-control" max="16"
                                    min="1">
                                <span class="input-group-text">MINGGU</span>
                            </div>
                            <div class="invalid-feedback d-block" data-error-for="rencana_tatap_muka"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jumlah Realisasi Pertemuan</label>
                            <div class="input-group">
                                <input type="number" name="realisasi_tatap_muka" class="form-control" max="16"
                                    min="1">
                                <span class="input-group-text">MINGGU</span>
                            </div>
                            <div class="invalid-feedback d-block" data-error-for="realisasi_tatap_muka"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Urutan</label>
                            <input type="number" name="urutan" class="form-control">
                            <div class="invalid-feedback d-block" data-error-for="urutan"></div>
                        </div>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button class="btn btn-success">Simpan</button>
            </div> --}}

        </div>
    </div>
</div>
