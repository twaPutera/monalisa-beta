@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link href="{{ asset('assets/vendors/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>

    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
@endsection
@section('custom_js')
    <script>
        var table2 = $('#datatableLogService');
        $(document).ready(function() {
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    showToastSuccess('Sukses', data.message);
                    $('#preview-file-error').html('');
                    table.DataTable().ajax.reload();
                } else {

                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "gambar_asset") {
                        $('#preview-file-error').html(errors[key][0]);
                    }
                }
            });

            table2.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.service-asset.datatable') }}",
                    data: function(d) {
                        //
                    }
                },
                columns: [
                    {
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
                        name: 'tanggal_mulai',
                        data: 'tanggal_mulai'
                    },
                    {
                        name: 'asset_data.deskripsi',
                        data: 'asset_data.deskripsi'
                    },
                    {
                        data: 'asset_data.nama_group'
                    },
                    {
                        data: 'asset_data.nama_kategori'
                    },
                    {
                        name: 'status_service',
                        data: 'status_service'
                    },
                    {
                        name: 'tanggal_selesai',
                        data: 'tanggal_selesai'
                    },
                ],
                columnDefs: [
                    {
                        targets: [2, 7],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    }
                    //Custom template data
                ],
            });

            getDataOptionSelect2Lokasi();

            $('.filterLokasi').select2({
                width: '150px',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })

            generateMonthPicker();
            generateYearPicker();
        });

        const generateMonthPicker = () => {
            $('.monthpicker').datepicker({
                format: "mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true,
            })
        }

        const generateYearPicker = () => {
            $('.yearpicker').datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                autoclose: true,
            });
        }

        const getDataOptionSelect2Lokasi = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const select = $('.select2Lokasi');
                    select.empty();
                    response.data.forEach(element => {
                        let selected = '';
                        if (element.id == $('#lokasiParentId').val()) {
                            selected = 'selected';
                        }
                        select.append(
                            `<option ${selected} value="${element.id}">${element.text}</option>`
                        );
                    });
                }
            })
        }
    </script>

    @include('pages.admin.listing-asset._script_modal_create')
    @include('pages.admin.listing-asset._script_modal_filter')
@endsection
@section('main-content')
    <input type="hidden" value="" id="lokasiParentId">
    <div class="row">
        <div class="col-md-2 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Tree Lokasi
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {{-- TODO * Show Chart Services --}}
                </div>
            </div>
            {{-- TODO * Summary Service yang sedang berlangsung --}}
        </div>
        <div class="col-md-10 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchAsset" class="form-control form-control-sm"
                            placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButton"
                                type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <select name="" class="filterLokasi select2Lokasi form-control mr-2" style="width: 150px;" id="">

                    </select>
                    <input type="text" readonly class="form-control yearpicker mx-2" style="width: 150px;" placeholder="Tahun">
                    <input type="text" readonly class="form-control monthpicker mr-2" style="width: 150px;" placeholder="Bulan">
                    <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"     type="button"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><strong>Data Service</strong></h5>
                <div class="kt-radio-inline">
                    <label class="kt-radio">
                        <input type="radio" checked="checked" value="all" name="radio4"> Semua Services
                        <span></span>
                    </label>
                    <label class="kt-radio">
                        <input type="radio" name="radio4" value="on progress"> On Progress
                        <span></span>
                    </label>
                    <label class="kt-radio">
                        <input type="radio" name="radio4" value="backlog"> Backlog
                        <span></span>
                    </label>
                    <label class="kt-radio">
                        <input type="radio" name="radio4" value="selesai"> Selesai
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Asset</th>
                                    <th>Kelompok</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tgl. Selesai</th>
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
@endsection
