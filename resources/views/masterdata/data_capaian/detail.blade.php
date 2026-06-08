@extends('layouts.index')
@section('title', 'Detail Capaian')
@push('styles-custom')
    @stack('styles-tab')
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Capaian</h3>
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
                    <a href="{{ route('prodi.index') }}">Detail Capaian</a>
                </li>
            </ul>
        </div>

        {{-- INFO NOTE --}}
        <div class="card shadow-sm border">
            <div class="card-header">
                <div class="fs-4 fw-semibold d-flex justify-content-between align-items-center">
                    <h4 class="card-title"> Informasi Program Studi</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('capaian.indexProdi') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Kode Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['kode_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Program Studi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['nama_prodi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Akreditasi
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['akreditasi'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Jenjang Pendidikan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['jenjang_pendidikan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Tahun Berdiri
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['tahun_berdiri'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="row align-items-center">
                                <div class="col-6 fw-semibold fs-5">
                                    Gelar Lulusan
                                </div>
                                <div class="col-6 fs-5 fw-semibold">
                                    : {{ $prodi['gelar_lulusan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">Data Capaian</h4>
                    </div>

                    <div class="card-body">

                        <!-- NAV TAB -->
                        <ul class="nav nav-tabs nav-line nav-color-secondary">

                            <li class="nav-item">
                                <a class="nav-link active tab-link" data-tab="pl" href="#">
                                    Profile Lulusan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link tab-link" data-tab="cpl" href="#">
                                    CPL
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link tab-link" data-tab="pl-cpl" href="#">
                                    Pemetaan PL → CPL
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link tab-link" data-tab="cpl-mk" href="#">
                                    Pemetaan CPL → MK
                                </a>
                            </li>

                        </ul>


                        <div class="tab-content mt-3">

                            <div id="tab-content-area">

                                <div class="text-center p-5">
                                    Loading...
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- @stack('script-tab') --}}

    <script>
        let id_prodi = "{{ $id_prodi }}";

        // Global function to ensure DataTables is available
        window.ensureDataTables = function (callback) {
            if (typeof $.fn.DataTable !== 'undefined') {
                callback();
            } else {
                // console.warn('DataTables not available, loading...');
                $.getScript('{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}', function () {
                    // console.log('DataTables loaded successfully');
                    callback();
                }).fail(function () {
                    // console.error('Failed to load DataTables');
                });
            }
        };

        function loadTab(tab) {

            // Reset CPL table initialization flag when switching away from CPL tab
            if (tab !== 'cpl') {
                window.cplTableInitialized = false;
            }

            $("#tab-content-area").html(`
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height:200px">

                                        <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem"></div>

                                        <div class="text-muted">Memuat data...</div>

                                    </div>
                                    `);

            $.get(`/capaian/${tab}/${id_prodi}?t=` + Date.now(), function (res) {

                $("#tab-content-area").html(res);

                // Ensure DataTables is available before initializing
                setTimeout(function () {
                    if (typeof $.fn.DataTable !== 'undefined') {
                        // console.log('DataTables is available');
                        // Trigger initialization for CPL tab specifically
                        if (tab === 'cpl' && typeof initCPLTable === 'function') {
                            initCPLTable();
                        }
                    } else {
                        // console.warn('DataTables is not available, reloading...');
                        // Reload DataTables script
                        $.getScript(
                            '{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}',
                            function () {
                                // console.log('DataTables loaded dynamically');
                                // Trigger initialization for CPL tab specifically
                                if (tab === 'cpl' && typeof initCPLTable === 'function') {
                                    initCPLTable();
                                }
                            });
                    }
                }, 500); // Increased delay to ensure content is fully loaded

            });

        }

        // load pertama
        loadTab('pl');

        // klik tab
        $(document).on('click', '.tab-link', function (e) {

            e.preventDefault();

            $('.tab-link').removeClass('active');
            $(this).addClass('active');

            let tab = $(this).data('tab');

            loadTab(tab);

        });
    </script>
@endpush
