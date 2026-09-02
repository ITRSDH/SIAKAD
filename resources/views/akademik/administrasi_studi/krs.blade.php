@extends('layouts.index')
@section('title', 'Daftarkan KRS — Administrasi Studi')

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
            <h3 class="fw-bold mb-3">Daftarkan KRS Historis</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('workspace.baak') }}">Workspace BAAK</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('akademik.administrasi-studi.index') }}">Administrasi Studi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item active">Daftarkan KRS</li>
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
                        <button type="button" class="btn btn-primary w-100" id="studyHistoricalLoadBtn">
                            <i class="fas fa-book-open me-1"></i> Muat Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan konteks --}}
        <div class="row g-3 mb-4" id="studySummaryCards">
            @foreach ($summaryCards as $card)
                <div class="col-md-6 col-xl-3">
                    <div class="study-stat">
                        <div class="small text-muted text-uppercase mb-2">{{ $card['label'] ?? '-' }}</div>
                        <div class="study-stat-value text-{{ $card['tone'] ?? 'primary' }}">{{ $card['value'] ?? 0 }}
                        </div>
                        <div class="small text-muted mt-2">{{ $card['description'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="study-flow-strip mb-4">
            <div class="study-flow-steps">
                <span class="study-flow-label">Alur</span>
                <div class="study-flow-step"><span class="ic"><i class="fas fa-users"></i></span><span>Pilih
                        Mahasiswa</span></div>
                <span class="study-flow-arrow"><i class="fas fa-arrow-right"></i></span>
                <div class="study-flow-step"><span class="ic"><i class="fas fa-pen"></i></span><span>Atur Form</span>
                </div>
                <span class="study-flow-arrow"><i class="fas fa-arrow-right"></i></span>
                <div class="study-flow-step"><span class="ic"><i class="fas fa-rocket"></i></span><span>Pratinjau &amp;
                        Jalankan</span></div>
            </div>
            <span class="badge bg-primary-soft text-primary py-2 px-3" id="studyHistoricalSelectionSummary">
                Belum ada mahasiswa yang dipilih
            </span>
        </div>

        {{-- Stepper --}}
        <div class="study-wizard-steps mb-4">
            <div class="study-wizard-step active" data-wstep="1"><span class="num">1</span><span>Pilih Mahasiswa</span>
            </div>
            <div class="study-wizard-step" data-wstep="2"><span class="num">2</span><span>Atur Build &amp; Isi
                    Form</span></div>
            <div class="study-wizard-step" data-wstep="3"><span class="num">3</span><span>Pratinjau &amp; Jalankan</span>
            </div>
        </div>

        {{-- STEP 1: Pilih mahasiswa --}}
        <div class="study-wizard-pane" data-wstep="1">
            <div class="card study-shell mb-3">
                <div class="card-header border-0 pt-4 px-4 pb-1">
                    <div class="fw-semibold">Kelas paket semester</div>
                    <div class="small text-muted">Referensi mata kuliah untuk pembentukan KRS historis.</div>
                </div>
                <div class="card-body p-4">
                    <div id="studyHistoricalPackageClasses" class="d-grid gap-2">
                        <div class="text-muted">Klik <strong>Muat Data</strong> untuk menampilkan daftar kelas.</div>
                    </div>
                </div>
            </div>

            <div class="card study-shell mb-3">
                <div class="card-header border-0 pt-4 px-4 pb-1">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <div class="fw-semibold">Pilih mahasiswa yang akan diproses</div>
                            <div class="small text-muted">Centang mahasiswa, lalu klik <strong>Siapkan Form KRS</strong>
                                untuk lanjut ke langkah berikutnya.</div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="studyHistoricalPrepareBuilderBtn">
                            <i class="fas fa-pen-ruler me-1"></i> Siapkan Form KRS
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="study-filter-strip">
                        <div class="summary" id="studyHistoricalEligibleSummary">Belum ada mahasiswa yang dimuat.</div>
                        <div class="search-input-wrap">
                            <i class="fas fa-search search-input-icon"></i>
                            <input type="text" class="form-control form-control-sm" id="studyHistoricalEligibleSearch"
                                placeholder="Cari nama atau NIM mahasiswa" disabled>
                        </div>
                    </div>
                    <div class="study-collective-table-wrap">
                        <table class="table table-bordered align-middle mb-0 study-collective-table">
                            <thead>
                                <tr>
                                    <th style="width: 42px;"><input type="checkbox" id="studyHistoricalSelectAll"></th>
                                    <th>Mahasiswa</th>
                                    <th>Semester Target</th>
                                    <th>KRS Saat Ini</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="studyHistoricalEligibleTableBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada mahasiswa yang
                                        dimuat.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="study-pager">
                        <div class="info" id="studyHistoricalEligiblePagerInfo">Menampilkan halaman 1.</div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                id="studyHistoricalEligiblePrevPage">
                                <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                id="studyHistoricalEligibleNextPage">
                                Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Atur build & isi form --}}
        <div class="study-wizard-pane d-none" data-wstep="2">
            <div class="card study-shell mb-3">
                <div class="card-header border-0 pt-4 px-4 pb-1">
                    <div class="fw-semibold">Atur bentuk build</div>
                    <div class="small text-muted">Pilih membuat KRS saja atau sekaligus mengisi nilai historis.</div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Mode Build KRS</label>
                            <select class="form-select" id="studyHistoricalBuildMode">
                                <option value="krs_only" selected>Daftarkan KRS Saja</option>
                                <option value="krs_with_scores">Daftarkan KRS + Nilai Historis (jarang dipakai)</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="study-action-note h-100 d-flex align-items-center small text-muted"
                                id="studyHistoricalBuildModeHelper">
                                Mode ini akan membuat KRS dan detail KRS tanpa harus mengisi nilai akhir setiap mata kuliah.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card study-shell mb-3">
                <div class="card-header border-0 pt-4 px-4 pb-1">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div class="fw-semibold">Form mata kuliah per mahasiswa</div>
                            <div class="small text-muted">Centang/atur mata kuliah yang ingin dimasukkan, lalu lanjut ke
                                pratinjau.</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="studyWizardBackStep1">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="studyWizardToStep3">
                                Lanjut ke Pratinjau <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="studyHistoricalBuilderCards" class="d-grid gap-3">
                        <div class="text-muted">Centang mahasiswa yang ingin diproses, lalu klik <strong>Siapkan Form
                                KRS</strong>.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 3: Pratinjau & jalankan --}}
        <div class="study-wizard-pane d-none" data-wstep="3">
            <div class="card study-shell">
                <div class="card-header border-0 pt-4 px-4 pb-1">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div class="fw-semibold">Pratinjau &amp; jalankan proses</div>
                            <div class="small text-muted">Periksa hasil lalu jalankan KRS historis jika sudah sesuai.</div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="studyWizardBackStep2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Catatan Proses</label>
                            <input type="text" class="form-control" id="studyHistoricalBuildNotes"
                                placeholder="Opsional, misalnya: histori semester 2 angkatan 2023">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary"
                                    id="studyHistoricalPreviewBuildBtn">
                                    <i class="fas fa-magnifying-glass me-1"></i> Lihat Pratinjau
                                </button>
                                <button type="button" class="btn btn-success" id="studyHistoricalExecuteBuildBtn"
                                    disabled>
                                    <i class="fas fa-play me-1"></i> Jalankan Proses
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="studyHistoricalBuildPreviewResults" class="d-grid gap-2 mt-3">
                        <div class="text-muted">Belum ada hasil pratinjau yang ditampilkan.</div>
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
            historicalEligibleRoute: '{{ route('akademik.administrasi-studi.historical.eligible') }}',
            historicalPackageClassesRoute: '{{ route('akademik.administrasi-studi.historical.package-classes') }}',
            historicalRepeatCandidatesRoute: '{{ route('akademik.administrasi-studi.historical.repeat-candidates') }}',
            historicalPreviewRoute: '{{ route('akademik.administrasi-studi.historical.preview') }}',
            historicalExecuteRoute: '{{ route('akademik.administrasi-studi.historical.execute') }}',
        };
    </script>
    <script src="{{ asset('js/admin-studi-common.js') }}"></script>
    <script src="{{ asset('js/admin-studi-krs.js') }}"></script>
    <script>
        // Wizard bertahap Daftarkan KRS (frontend hanya; API tidak diubah).
        (function() {
            'use strict';

            var $panes = $('.study-wizard-pane');
            var $steps = $('.study-wizard-step');

            function goStep(step) {
                $panes.addClass('d-none').filter('[data-wstep="' + step + '"]').removeClass('d-none');
                $steps.removeClass('active done');
                for (var i = 1; i <= step; i++) {
                    var $s = $steps.filter('[data-wstep="' + i + '"]');
                    $s.addClass(i < step ? 'done' : 'active');
                }
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function selectedCount() {
                return $('.study-historical-student-checkbox:checked').length;
            }

            // Setelah "Siapkan Form KRS" menyiapkan form, maju ke langkah 2.
            $('#studyHistoricalPrepareBuilderBtn').on('click', function() {
                setTimeout(function() {
                    if (selectedCount() > 0) {
                        goStep(2);
                    }
                }, 400);
            });

            $('#studyWizardToStep3').on('click', function() {
                if (!$('#studyHistoricalBuilderCards').find('.study-student-builder-card').length) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Belum ada form yang disiapkan. Kembali ke langkah 1 lalu klik Siapkan Form KRS.'
                    });
                    return;
                }
                goStep(3);
            });

            $('#studyWizardBackStep1').on('click', function() {
                goStep(1);
            });
            $('#studyWizardBackStep2').on('click', function() {
                goStep(2);
            });

            // Kembali ke langkah 1 saat filter berubah atau data dimuat ulang.
            $('#studyHistoricalLoadBtn').on('click', function() {
                goStep(1);
            });
            $('#studySemesterId, #studyProdiId, #studyAngkatan, #studySemesterKe').on('change input', function() {
                goStep(1);
            });

            window.studyWizard = {
                goStep: goStep
            };
        })();
    </script>
@endpush
