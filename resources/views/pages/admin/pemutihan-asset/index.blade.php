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
        $(document).ready(function() {
            var table = $('#datatableExample');
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.pemutihan-asset.datatable') }}",
                columns: [{
                        data: "DT_RowIndex",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'DT_RowIndex'
                    },
                    {
                        data: "action",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'no_memo'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'created_by',
                        data: 'created_by'
                    },
                ],
                columnDefs: [
                    //Custom template data
                ],
            });

            var table2 = $('#addAssetData');
            table2.DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.pemutihan-asset.datatable.asset') }}",
                columns: [{
                        name: 'id',
                        data: 'id',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'kode_asset'
                    },
                    {
                        name: 'jenis_asset',
                        data: 'jenis_asset'
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset'
                    },
                    {
                        name: 'kondisi_asset',
                        data: 'kondisi_asset'
                    },

                ],
                columnDefs: [
                    //Custom template data
                ],
            });


            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    table.DataTable().ajax.reload();
                    showToastSuccess('Sukses', data.message);
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                }
            });
        });


        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('.check-all').on('change', function() {
            if ($(this).is(':checked')) {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', true);
                });
            } else {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', false);
                });
            }
        });


        $('.check-item').each(function(i, e) {
            $(e).prop('checked', false);
        });
        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalDetailInventarisData');
                    const form = modal.find('form');
                    form.find('input[name=tanggal]').val(response.data.tanggal);
                    form.find('input[name=no_memo]').val(response.data.no_memo);
                    form.find('input[name=status_pemutihan]').val(response.data.status);
                    form.find('textarea[name=keterangan_pemutihan]').val(response.data.keterangan);
                    var table4 = $('.detailAssetData');
                    modal.on('shown.bs.modal', function(e) {
                        table4.DataTable({
                            responsive: true,
                            processing: true,
                            serverSide: true,
                            destroy: true,
                            ajax: {
                                url: "{{ route('admin.pemutihan-asset.datatable.detail') }}",
                                data: function(d) {
                                    d.id_pemutihan_detail = response.data.id
                                }
                            },
                            columns: [{
                                    name: 'kode_asset',
                                    data: 'kode_asset'
                                },
                                {
                                    name: 'jenis_asset',
                                    data: 'jenis_asset'
                                },
                                {
                                    name: 'lokasi_asset',
                                    data: 'lokasi_asset'
                                },
                                {
                                    name: 'kondisi_asset',
                                    data: 'kondisi_asset'
                                },

                            ],
                            columnDefs: [
                                //Custom template data
                            ],
                        });
                    })
                    modal.on('hidden.bs.modal', function() {
                        table4.dataTable().fnDestroy();
                    })
                    modal.modal('show');
                }
            })
        }
        const edit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditInventarisData');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=tanggal]').val(response.data.tanggal);
                    form.find('input[name=no_memo]').val(response.data.no_memo);
                    form.find('textarea[name=keterangan_pemutihan]').val(response.data.keterangan);
                    var table3 = $('.editAssetData');
                    modal.on('shown.bs.modal', function(e) {
                        $('#status_pemutihan option[value="' + response.data
                            .status + '"]').attr('selected', 'selected');
                        table3.DataTable({
                            responsive: true,
                            processing: true,
                            serverSide: true,
                            destroy: true,
                            ajax: {
                                url: "{{ route('admin.pemutihan-asset.datatable.asset') }}",
                                data: function(d) {
                                    d.id_pemutihan = response.data.id
                                }
                            },
                            columns: [{
                                    name: 'id',
                                    data: 'id',
                                    orderable: false,
                                    searchable: false,
                                },
                                {
                                    data: 'kode_asset'
                                },
                                {
                                    name: 'jenis_asset',
                                    data: 'jenis_asset'
                                },
                                {
                                    name: 'lokasi_asset',
                                    data: 'lokasi_asset'
                                },
                                {
                                    name: 'kondisi_asset',
                                    data: 'kondisi_asset'
                                },

                            ],
                            columnDefs: [
                                //Custom template data
                            ],
                        });
                    })
                    modal.on('hidden.bs.modal', function() {
                        table3.dataTable().fnDestroy();
                    })
                    modal.modal('show');
                }
            })
        }
    </script>
@endsection
@section('main-content')
    <div class="row">

        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Daftar Asset Diputihkan <span class="text-primary"><b>({{ $total_asset }} Asset)</b></span>
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateInventarisData')"
                                    class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Tanggal Pemutihan</th>
                                    <th>No Memo</th>
                                    <th>Keterangan Pemutihan</th>
                                    <th>Status Pemutihan</th>
                                    <th>Diputihkan Oleh</th>
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
    @include('pages.admin.pemutihan-asset.components.modal._modal_create')
    @include('pages.admin.pemutihan-asset.components.modal._modal_edit')
    @include('pages.admin.pemutihan-asset.components.modal._modal_detail')
@endsection
