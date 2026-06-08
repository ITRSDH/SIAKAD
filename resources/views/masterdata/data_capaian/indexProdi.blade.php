@extends('layouts.index')
@section('title', 'Pilih Program Studi')
@push('styles-custom')
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pilih Program Studi</h3>
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
                    <a href="{{ route('prodi.index') }}">Program Studi</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="prodi-table" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">AKSI</th>
                                        <th width="10%" class="text-center">KODE PROGRAM STUDI</th>
                                        <th width="30%" class="text-center">PROGRAM STUDI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi ini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts-custom')
    {{-- <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script> --}}
    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            var tabel = $('#prodi-table').DataTable({
                ajax: {
                    url: "{{ route('capaian.dataProdi') }}"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            var detailUrl = "{{ route('capaian.detailProdi', ':id') }}".replace(
                                ':id',
                                row.id);
                            return `
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="${detailUrl}"
                               class="btn btn-primary btn-sm view-btn"
                               title="Lihat Detail Capaian">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_prodi',
                        className: 'text-center',
                    },
                    {
                        data: 'prodi',
                        className: 'text-center',
                    },
                ],
                language: {
                    url: '{{ asset('') }}template/assets/js/plugin/datatables/i18n/id.json'
                }
            });
        });
    </script>
@endpush
