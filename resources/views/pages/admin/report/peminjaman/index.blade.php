@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        var table = $('#datatableExample');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                // ordering: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.history-peminjaman.datatable') }}",
                    data: function(d) {
                        d.start_date = $('#filterStartDate').val();
                        d.end_date = $('#filterEndDate').val();
                        d.searchKeyword = $('#searchDepresiasi').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'DT_RowIndex'
                    },
                    {
                        data: "created_at",
                        name: 'created_at'
                    },
                    {
                        name: 'code',
                        data: 'code'
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name',
                        orderable: false,
                    },
                    {
                        name: 'log_message',
                        data: 'log_message',
                        orderable: false,
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    }
                ],
            });
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    //
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //
            });

            $("#searchDepresiasi").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableAsset();
            });
        });

        const filterTableAsset = () => {
            table.DataTable().ajax.reload();
        }

        $('.datepickerAwal').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerAkhir').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        const exportPeminjaman = () => {
            let start_date = $('#exportStartDate').val();
            let end_date = $('#exportEndDate').val();
            let url = "{{ route('admin.report.history-peminjaman.download-export') }}" + '?start_date=' + start_date +
                '&end_date=' + end_date;
            window.open(url, '_blank');
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.report.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            History Peminjaman
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalFilterPeminjamanAsset')"
                                    class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter </button>
                                <button onclick="openModalByClass('modalExportPeminjamanAsset')"
                                    class="btn btn-success ml-1 shadow-custom btn-sm" type="button"><i
                                        class="fas fa-print"></i>Export Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="d-flex align-items-center">
                        <div class="input-group mr-3" style="width: 250px;">
                            <input type="text" id="searchDepresiasi" class="form-control form-control-sm"
                                placeholder="Search for...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButton"
                                    type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Tanggal</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Pembuat</th>
                                    <th>Log</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.report.peminjaman._modal_filter_service')
    @include('pages.admin.report.peminjaman._modal_export')
@endsection
