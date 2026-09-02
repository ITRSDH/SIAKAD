@extends('layouts.index')
@section('title', 'Input Nilai — Administrasi Studi')

@php
    $summaryCards = $workspaceSummary['summary_cards'] ?? [];
    $semesterOptions = $filters['semester'] ?? [];
    $prodiOptions = $filters['prodi'] ?? [];
    $semesterKeOptions = $filters['semester_ke_options'] ?? [];
@endphp

@push('styles-custom')
    <link rel="stylesheet" href="{{ asset('css/admin-studi.css') }}">
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Input Nilai</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item active">Input Nilai</li>
            </ul>
        </div>

        @include('layouts.partials.flash-messages')

        {{-- Filter konteks bersama --}}
        <div class="card study-shell mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select select2" id="studySemesterId">
                            <option value="">Semua semester</option>
                            @foreach ($semesterOptions as $item)
                                @php
                                    $tahun =
                                        $item['tahun_akademik']['tahun_akademik'] ??
                                        ($item['tahunAkademik']['tahun_akademik'] ?? '');
                                    $label = trim(($item['nama_semester'] ?? 'Semester') . ' ' . $tahun);
                                @endphp
                                <option value="{{ $item['id'] }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program Studi</label>
                        <select class="form-select select2" id="studyProdiId">
                            <option value="">Semua prodi</option>
                            @foreach ($prodiOptions as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama_prodi'] ?? 'Program Studi' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Angkatan</label>
                        <input type="number" id="studyAngkatan" class="form-control" placeholder="Contoh 2024">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semester Ke</label>
                        <select class="form-select select2" id="studySemesterKe">
                            <option value="">Semua</option>
                            @foreach ($semesterKeOptions as $item)
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-soft-primary w-100" id="nilaiManualLoadClassesBtn">
                            <i class="fas fa-sync me-1"></i> Muat Kelas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pilihan jalur --}}
        <div class="nav nav-pills study-jalur-nav mb-4" id="studyJalurTabs" role="tablist">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#jalur-import" type="button">
                <i class="fas fa-file-import me-1"></i> Import Excel
            </button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#jalur-manual" type="button">
                <i class="fas fa-pen me-1"></i> Manual (per filter)
            </button>
        </div>

        <div class="tab-content">
            {{-- ================== JALUR IMPORT ================== --}}
            <div class="tab-pane fade show active" id="jalur-import">
                <div class="row g-4">
                    <div class="col-xl-4 order-xl-2">
                        <div class="card study-shell h-100">
                            <div class="card-body p-4">
                                <div class="fw-semibold mb-2">Alur import nilai</div>
                                <div class="small text-muted mb-3">
                                    Unduh template, isi sesuai format, lalu unggah kembali. Hasil pengecekan dibuka di
                                    halaman ringkasan.
                                </div>
                                <ol class="small text-muted mb-3 ps-3">
                                    <li>Lengkapi filter di atas (semester wajib).</li>
                                    <li>Unduh template nilai untuk konteks tersebut.</li>
                                    <li>Unggah file hasil isian.</li>
                                    <li>Cek hasil & simpan nilai.</li>
                                </ol>
                                <a href="{{ route('akademik.administrasi-studi.khs') }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i> Ke Generate KHS
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card study-shell h-100">
                            <div class="card-body p-4">
                                <div class="fw-semibold mb-3">Unduh template & unggah nilai</div>

                                <form method="GET"
                                    action="{{ route('akademik.administrasi-studi.import.template-export') }}"
                                    class="mb-4" id="studyImportTemplateForm">
                                    <input type="hidden" name="angkatan" id="studyImportTemplateAngkatan">
                                    <input type="hidden" name="id_prodi" id="studyImportTemplateProdi">
                                    <input type="hidden" name="id_semester" id="studyImportTemplateSemester">
                                    <input type="hidden" name="semester_ke" id="studyImportTemplateSemesterKe">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-success" id="studyExportTemplateBtn">
                                            <i class="fas fa-file-export me-1"></i> Unduh Template
                                        </button>
                                    </div>
                                    <div class="form-text mt-2">
                                        Template mengikuti angkatan, prodi, semester, dan semester ke pada filter. Semester
                                        1 memakai kolom
                                        gabungan <code>IP/IPK</code>; semester berikutnya memakai <code>IP</code> dan
                                        <code>IPK</code> terpisah.
                                    </div>
                                </form>

                                <hr>

                                <form method="POST" action="{{ route('akademik.administrasi-studi.import.upload') }}"
                                    enctype="multipart/form-data" id="studyImportUploadForm">
                                    @csrf
                                    <input type="hidden" name="id_semester" id="studyImportUploadSemester">
                                    <div class="mb-3">
                                        <label class="form-label">File Excel Nilai</label>
                                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls"
                                            required>
                                        <div class="form-text">
                                            Gunakan file <code>.xlsx</code> / <code>.xls</code> maksimal 10MB. Kolom
                                            <code>IPK</code> semester di atas 1
                                            diisi manual dari Excel, tidak dihitung otomatis.
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="studyUploadImportBtn">
                                        <i class="fas fa-cloud-upload-alt me-1"></i> Unggah File Nilai
                                    </button>
                                </form>

                                <hr class="my-4">

                                <div class="study-mini-item">
                                    <div class="fw-semibold mb-2">Perlu melihat proses lama?</div>
                                    <div class="small text-muted mb-3">Riwayat import tetap tersedia untuk audit.</div>
                                    <a href="{{ route('akademik.administrasi-studi.batches') }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        Lihat Riwayat Import
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================== JALUR MANUAL PER KELAS ================== --}}
            <div class="tab-pane fade" id="jalur-manual">
                <div class="card study-shell mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <div class="fw-semibold">Isi nilai akhir secara masal</div>
                                {{-- <div class="small text-muted">
                                    Menampilkan mahasiswa yang KRS-nya sudah <strong>di-approve</strong> pada semester &amp; prodi terpilih.
                                    Baris dengan <span class="badge bg-success">KHS Final</span> sudah memiliki KHS final — dicegat agar
                                    tidak tertambah/diubah (stabilo hijau). Satu kali <strong>Simpan Nilai</strong> akan menulis
                                    langsung ke KRS detail (sama seperti import, tanpa memicu tabel nilai/komponen).
                                </div> --}}
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-primary-soft text-primary py-2 px-3" id="nilaiManualSelSummary">
                                    Belum ada mahasiswa dipilih
                                </span>
                                <button type="button" class="btn btn-success" id="nilaiManualSaveBtn" disabled>
                                    <i class="fas fa-save me-1"></i> Simpan Nilai (0)
                                </button>
                            </div>
                        </div>

                        <div class="study-filter-strip">
                            <div class="summary" id="nilaiManualSearchSummary">
                                Cari mahasiswa berdasarkan <strong>nama</strong> atau <strong>NIM</strong>.
                            </div>
                            <div class="search-input-wrap">
                                <i class="fas fa-search search-input-icon"></i>
                                <input type="text" class="form-control form-control-sm" id="nilaiManualSearch"
                                    placeholder="Cari nama / NIM mahasiswa...">
                            </div>
                        </div>

                        <div class="table-responsive study-collective-table-wrap">
                            <table class="table table-bordered align-middle mb-0 study-collective-table">
                                <thead id="nilaiManualGridHead">
                                    <tr>
                                        <th style="width: 42px;"><input type="checkbox" id="nilaiManualSelectAll"></th>
                                        <th style="min-width: 220px;">Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody id="nilaiManualGridBody">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Klik <strong>Muat Data</strong> pada filter untuk menampilkan mahasiswa &amp;
                                            mata kuliah.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="study-pager" id="nilaiManualPager">
                            <div class="info" id="nilaiManualPagerInfo">Belum ada halaman untuk ditampilkan.</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="nilaiManualPrevPage">
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="nilaiManualNextPage">
                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="small text-muted mt-2" id="nilaiManualHelptext">
                            {{-- Kolom <strong>paket</strong> mengikuti kelas paket semester dari filter. Kolom <strong>Ulang</strong> adalah mata
                            kuliah yang diulang mahasiswa (tampil sebagai kolom tersendiri walau kelasnya berbeda). Kosongkan sel yang tidak
                            ingin diisi. Nilai tersimpan ke kelas asli mahasiswa yang bersangkutan. --}}
                            <span class="d-block mt-1">
                                <span class="badge bg-secondary-subtle text-secondary">× / abu-abu</span> = Mata kuliah
                                tidak
                                diambil mahasiswa
                                &bull; <span class="badge bg-warning-subtle text-warning">Ulang</span> / kuning = Mata
                                kuliah Mengulang.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.studyConfig = {
            csrfToken: '{{ csrf_token() }}',
            summaryRoute: '{{ route('akademik.administrasi-studi.summary') }}',
            historicalPackageClassesRoute: '{{ route('akademik.administrasi-studi.historical.package-classes') }}',
            manualNilaiSaveRoute: '{{ route('akademik.administrasi-studi.nilai-manual.save') }}',
            manualNilaiContextRoute: '{{ route('akademik.administrasi-studi.nilai-manual.context') }}',
            khsUrl: '{{ url('akademik/khs') }}',
        };
    </script>
    <script src="{{ asset('js/admin-studi-common.js') }}"></script>
    <script src="{{ asset('js/admin-studi-nilai.js') }}"></script>
@endpush
