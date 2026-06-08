@extends('layouts.index')
@section('title', 'Detail KHS BAAK')

@php
    $semester = $khs['semester'] ?? [];
    $tahunAkademik = $semester['tahun_akademik'] ?? $semester['tahunAkademik'] ?? [];
    $semesterLabel = trim((string) (($semester['nama_semester'] ?? '-') . ' ' . ($tahunAkademik['tahun_akademik'] ?? '')));
    $mahasiswa = $khs['mahasiswa'] ?? [];
    $details = $khs['details'] ?? [];
    $importBatchIds = collect($details)->pluck('id_import_batch')->filter()->unique()->values()->all();
    $revisionItems = $revisionItems ?? [];
    $isFinal = !empty($khs['is_final']);
@endphp

@push('styles-custom')
    <style>
        .khs-shell-card {
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .khs-shell-header {
            padding: 1.4rem 1.5rem 0;
        }

        .khs-shell-body {
            padding: 1.5rem;
        }

        .khs-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .khs-hero {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.22), transparent 25%),
                linear-gradient(135deg, #0f172a 0%, #0f766e 48%, #1d4ed8 100%);
            color: #fff;
        }

        .khs-hero .card-body {
            padding: 1.75rem;
        }

        .khs-hero-copy {
            color: rgba(255, 255, 255, 0.82);
            max-width: 60ch;
        }

        .khs-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .khs-stat-card {
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            height: 100%;
        }

        .khs-stat-card .label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.45rem;
        }

        .khs-stat-card .value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .khs-note-box {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
            padding: 1rem;
            height: 100%;
        }

        .khs-mini-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            padding: 1rem;
            height: 100%;
        }

        .khs-mini-card .title {
            font-size: 0.78rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }

        .khs-mini-card .value {
            color: #0f172a;
            font-weight: 700;
        }

        .khs-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
        }

        .khs-table-wrap table {
            margin-bottom: 0;
        }

        .khs-batch-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
        }

        .khs-batch-link {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            padding: 1rem;
            background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .khs-batch-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08);
        }

        @media (max-width: 991.98px) {
            .khs-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .khs-stat-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail KHS BAAK</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.import.history') }}">Riwayat Import KHS</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.khs.show', $khs['id'] ?? '') }}">Detail KHS</a></li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        <div class="card khs-hero mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <span class="khs-chip">
                            <i class="fas fa-user-graduate"></i>
                            Detail hasil KHS mahasiswa
                        </span>
                        <h2 class="fw-bold mt-3 mb-2">{{ $mahasiswa['nama_mahasiswa'] ?? 'Mahasiswa' }}</h2>
                        <div class="text-white-50 mb-1">{{ $mahasiswa['nim'] ?? '-' }} • {{ $semesterLabel ?: '-' }}</div>
                        <p class="khs-hero-copy mb-0">
                            Halaman ini merangkum status KHS, ringkasan nilai, riwayat revisi, dan detail mata kuliah dalam satu tampilan yang lebih mudah dipindai.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('student.khs.print', $khs['id'] ?? '') }}" target="_blank" class="btn btn-light btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak
                        </a>
                        @if (!empty($importBatchIds))
                            <a href="{{ route('akademik.khs.import.show', $importBatchIds[0]) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-clock-rotate-left me-1"></i> Lihat Batch
                            </a>
                        @endif
                        @if (!$isFinal)
                            <form method="POST" action="{{ route('akademik.khs.finalize', $khs['id']) }}">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Finalisasi KHS ini sekarang?')">
                                    <i class="fas fa-lock me-1"></i> Finalisasi
                                </button>
                            </form>
                        @else
                            <span class="btn btn-success btn-sm disabled">
                                <i class="fas fa-check-circle me-1"></i> Sudah Final
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card khs-shell-card mb-4">
            <div class="khs-shell-header">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">Ringkasan KHS</h4>
                        <p class="text-muted mb-0">Bagian atas ini saya buat untuk menjawab cepat: statusnya apa, nilainya berapa, dan berapa SKS yang sudah lulus.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="editSummaryBtn">
                        <i class="fas fa-pen-to-square me-1"></i> Edit IPK
                    </button>
                </div>
            </div>
            <div class="khs-shell-body">
                <div class="khs-stat-grid">
                    <div class="khs-stat-card">
                        <div class="label">Status</div>
                        <div class="value">{{ $isFinal ? 'Final' : 'Draft' }}</div>
                    </div>
                    <div class="khs-stat-card">
                        <div class="label">IPS</div>
                        <div class="value">{{ $khs['ips'] ?? '0.00' }}</div>
                    </div>
                    <div class="khs-stat-card">
                        <div class="label">IPK</div>
                        <div class="value">{{ $khs['ipk'] ?? '0.00' }}</div>
                    </div>
                    <div class="khs-stat-card">
                        <div class="label">SKS Lulus</div>
                        <div class="value">{{ $khs['total_sks_lulus'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-lg-6">
                        <div class="khs-note-box">
                            <div class="fw-semibold mb-2">Informasi akademik</div>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="38%">Program Studi</th>
                                    <td>{{ $mahasiswa['prodi']['nama_prodi'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>{{ $semesterLabel ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $khs['keterangan'] ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="khs-note-box">
                            <div class="fw-semibold mb-2">Riwayat proses</div>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="38%">SKS Diambil</th>
                                    <td>{{ $khs['total_sks_diambil'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Generated At</th>
                                    <td>{{ $khs['generated_at'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Finalized At</th>
                                    <td>{{ $khs['finalized_at'] ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="alert alert-light border mt-3 mb-0">
                    Semester 1 akan tetap mengikuti hasil IPS. Untuk semester berikutnya, nilai IPK pada KHS ini bisa
                    dikoreksi manual dari tombol <strong>Edit IPK</strong> tanpa mengubah detail mata kuliah.
                </div>
            </div>
        </div>

        @if (!empty($importBatchIds))
            <div class="card khs-shell-card mb-4">
                <div class="khs-shell-header">
                    <h4 class="fw-bold mb-1">Batch import yang terkait</h4>
                    <p class="text-muted mb-0">Bagian ini memudahkan Anda melacak batch mana yang berkontribusi ke detail KHS aktif saat ini.</p>
                </div>
                <div class="khs-shell-body">
                    <div class="khs-batch-links">
                        @foreach ($importBatchIds as $batchId)
                            <a href="{{ route('akademik.khs.import.show', $batchId) }}" class="khs-batch-link">
                                <div class="small text-muted mb-2">Sumber batch</div>
                                <div class="fw-bold">Batch {{ \Illuminate\Support\Str::limit($batchId, 8, '') }}</div>
                                <div class="small text-primary mt-2">Buka detail batch</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="card khs-shell-card h-100">
                    <div class="khs-shell-header">
                        <h4 class="fw-bold mb-1">Riwayat revisi</h4>
                        <p class="text-muted mb-0">Riwayat ini dirangkum dari snapshot revisi yang terkait dengan KHS ini.</p>
                    </div>
                    <div class="khs-shell-body">
                        @if (empty($revisionItems))
                            <div class="alert alert-light border mb-0">
                                Belum ada revisi yang tercatat untuk KHS ini.
                            </div>
                        @else
                            <div class="khs-table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Revision</th>
                                                <th>Batch</th>
                                                <th>Pembuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($revisionItems as $item)
                                                <tr>
                                                    <td>#{{ $item['revision_number'] ?? '-' }}</td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $item['batch_file_name'] ?? 'Batch Import' }}</div>
                                                        <small class="text-muted">{{ $item['created_at'] ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <div>{{ $item['creator']['name'] ?? '-' }}</div>
                                                        <small class="text-muted">{{ $item['reason'] ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        @if (!empty($item['batch_id']))
                                                            <a href="{{ route('akademik.khs.import.show', $item['batch_id']) }}" class="btn btn-sm btn-outline-info">Detail Batch</a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card khs-shell-card h-100">
                    <div class="khs-shell-header">
                        <h4 class="fw-bold mb-1">Ringkasan akademik</h4>
                        <p class="text-muted mb-0">Beberapa poin penting saya pisahkan ke kartu kecil agar tidak tenggelam di tabel detail mata kuliah.</p>
                    </div>
                    <div class="khs-shell-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="khs-mini-card">
                                    <div class="title">Mahasiswa</div>
                                    <div class="value">{{ $mahasiswa['nama_mahasiswa'] ?? '-' }}</div>
                                    <div class="text-muted small mt-1">{{ $mahasiswa['nim'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="khs-mini-card">
                                    <div class="title">Semester Aktif KHS</div>
                                    <div class="value">{{ $semesterLabel ?: '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="khs-mini-card">
                                    <div class="title">Mutu dan Bobot</div>
                                    <div class="value">Ditampilkan terpisah</div>
                                    <div class="text-muted small mt-1">Agar aman saat pengecekan dan revisi.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="khs-mini-card">
                                    <div class="title">Mode Edit</div>
                                    <div class="value">{{ $isFinal ? 'Revisi terkontrol' : 'Masih bisa diedit' }}</div>
                                    <div class="text-muted small mt-1">{{ $isFinal ? 'KHS final tetap bisa direvisi dengan alasan.' : 'Finalisasi belum dilakukan.' }}</div>
                                </div>
                            </div>
                        </div>

                        @if (empty($importBatchIds) && empty($revisionItems))
                            <div class="alert alert-light border mt-3 mb-0">
                                KHS ini belum memiliki referensi batch import aktif pada detailnya, sehingga jejak batch dan revisi masih minim di halaman ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card khs-shell-card">
            <div class="khs-shell-header">
                <h4 class="fw-bold mb-1">Detail mata kuliah</h4>
                <p class="text-muted mb-0">Bobot nilai dan mutu saya pertahankan terpisah agar tidak tertukar saat Anda melakukan pengecekan atau revisi.</p>
            </div>
            <div class="khs-shell-body">
                <div class="khs-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="khsDetailsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Nilai Angka</th>
                                    <th>Nilai Huruf</th>
                                    <th>Bobot</th>
                                    <th>Mutu</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail['kode_mk'] ?? '-' }}</td>
                                        <td>{{ $detail['nama_mk'] ?? '-' }}</td>
                                        <td>{{ $detail['sks'] ?? 0 }}</td>
                                        <td>{{ $detail['nilai_akhir'] ?? '-' }}</td>
                                        <td>{{ $detail['nilai_huruf'] ?? '-' }}</td>
                                        <td>{{ $detail['bobot_nilai'] ?? '-' }}</td>
                                        <td>{{ $detail['mutu'] ?? '-' }}</td>
                                        <td>@include('layouts.partials.status-badge', ['value' => $detail['status'] ?? 'terdaftar'])</td>
                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary edit-detail-btn"
                                                data-detail-id="{{ $detail['id'] ?? '' }}"
                                                data-kode-mk="{{ $detail['kode_mk'] ?? '' }}"
                                                data-nama-mk="{{ $detail['nama_mk'] ?? '' }}"
                                                data-nilai-akhir="{{ $detail['nilai_akhir'] ?? '' }}"
                                                data-nilai-huruf="{{ $detail['nilai_huruf'] ?? '' }}"
                                                data-bobot-nilai="{{ $detail['bobot_nilai'] ?? '' }}"
                                                data-mutu="{{ $detail['mutu'] ?? '' }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Belum ada detail mata kuliah pada KHS ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editDetailForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Nilai KHS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editDetailId">
                        <div class="mb-3">
                            <label class="form-label">Mata Kuliah</label>
                            <input type="text" id="editMataKuliah" class="form-control" disabled>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nilai Angka</label>
                                <input type="number" min="0" max="100" step="0.01" id="editNilaiAkhir" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nilai Huruf</label>
                                <input type="text" maxlength="2" id="editNilaiHuruf" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bobot Nilai</label>
                                <input type="number" min="0" max="4" step="0.01" id="editBobotNilai" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mutu</label>
                                <input type="number" min="0" step="0.01" id="editMutu" class="form-control">
                            </div>
                        </div>
                        @if ($isFinal)
                            <div class="mt-3">
                                <label class="form-label">Alasan Revisi</label>
                                <textarea id="editReason" class="form-control" rows="3" placeholder="Wajib diisi karena KHS sudah final."></textarea>
                            </div>
                        @endif
                        <div id="editDetailMessage" class="small mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitEditDetailBtn">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSummaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editSummaryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ringkasan KHS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">IPK</label>
                            <input type="number" min="0" max="4" step="0.01" id="editSummaryIpk" class="form-control" value="{{ $khs['ipk'] ?? '' }}">
                            <div class="form-text">Semester 1 akan tetap mengikuti IPS. Nilai ini terutama dipakai untuk koreksi IPK manual semester di atas 1.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Revisi</label>
                            <textarea id="editSummaryReason" class="form-control" rows="3" placeholder="{{ $isFinal ? 'Wajib diisi karena KHS sudah final.' : 'Opsional, isi bila perlu.' }}"></textarea>
                        </div>
                        <div id="editSummaryMessage" class="small"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitEditSummaryBtn">Simpan Ringkasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script>
        const editModalInstance = new bootstrap.Modal(document.getElementById('editDetailModal'));
        const editSummaryModalInstance = new bootstrap.Modal(document.getElementById('editSummaryModal'));
        const updateDetailUrlTemplate = @json(route('akademik.khs.details.update', ['khsId' => $khs['id'] ?? '', 'detailId' => '__DETAIL__']));
        const updateSummaryUrl = @json(route('akademik.khs.summary.update', ['khsId' => $khs['id'] ?? '']));

        $('.edit-detail-btn').on('click', function() {
            $('#editDetailId').val($(this).data('detail-id'));
            $('#editMataKuliah').val(`${$(this).data('kode-mk')} - ${$(this).data('nama-mk')}`);
            $('#editNilaiAkhir').val($(this).data('nilai-akhir'));
            $('#editNilaiHuruf').val($(this).data('nilai-huruf'));
            $('#editBobotNilai').val($(this).data('bobot-nilai'));
            $('#editMutu').val($(this).data('mutu'));
            $('#editReason').val('');
            $('#editDetailMessage').html('');
            editModalInstance.show();
        });

        $('#editDetailForm').on('submit', function(e) {
            e.preventDefault();

            const detailId = $('#editDetailId').val();
            const submitBtn = $('#submitEditDetailBtn');
            const payload = {
                _token: @json(csrf_token()),
                nilai_akhir: $('#editNilaiAkhir').val(),
                nilai_huruf: $('#editNilaiHuruf').val(),
                bobot_nilai: $('#editBobotNilai').val(),
                mutu: $('#editMutu').val(),
                reason: $('#editReason').val()
            };

            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: updateDetailUrlTemplate.replace('__DETAIL__', detailId),
                type: 'PUT',
                data: payload,
                success: function(response) {
                    if (!response.success) {
                        $('#editDetailMessage').html(`<span class="text-danger">${response.message || 'Gagal menyimpan revisi nilai.'}</span>`);
                        return;
                    }

                    window.location.reload();
                },
                error: function(xhr) {
                    $('#editDetailMessage').html(`<span class="text-danger">${xhr.responseJSON?.message || 'Gagal menyimpan revisi nilai.'}</span>`);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Simpan Perubahan');
                }
            });
        });

        $('#editSummaryBtn').on('click', function() {
            $('#editSummaryIpk').val(@json($khs['ipk'] ?? ''));
            $('#editSummaryReason').val('');
            $('#editSummaryMessage').html('');
            editSummaryModalInstance.show();
        });

        $('#editSummaryForm').on('submit', function(e) {
            e.preventDefault();

            const submitBtn = $('#submitEditSummaryBtn');
            const normalizedIpk = String($('#editSummaryIpk').val() ?? '')
                .trim()
                .replace(',', '.');
            const payload = {
                _token: @json(csrf_token()),
                ipk: normalizedIpk,
                reason: $('#editSummaryReason').val()
            };

            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: updateSummaryUrl,
                type: 'PUT',
                data: payload,
                success: function(response) {
                    if (!response.success) {
                        $('#editSummaryMessage').html(`<span class="text-danger">${response.message || 'Gagal menyimpan ringkasan KHS.'}</span>`);
                        return;
                    }

                    window.location.reload();
                },
                error: function(xhr) {
                    $('#editSummaryMessage').html(`<span class="text-danger">${xhr.responseJSON?.message || 'Gagal menyimpan ringkasan KHS.'}</span>`);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Simpan Ringkasan');
                }
            });
        });
    </script>
@endpush
