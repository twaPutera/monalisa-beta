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
        var table = $('#datatableLogService');
        $(document).ready(function() {
            table.DataTable({
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
                        d.month = $('.monthpicker').val();
                        d.year = $('.yearpicker').val();
                        d.status_service = $("input[name='status_services']:checked").val();
                        d.id_lokasi = $('#lokasiFilter').val();
                        d.keyword = $('#searchServices').val();
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
                        name: 'asset_data.is_inventaris',
                        data: 'asset_data.is_inventaris',
                        render: function(type) {
                            return type == 1 ? "Inventaris" : "Asset";
                        }
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
                columnDefs: [{
                        targets: [2, 8],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "on progress") {
                                element +=
                                    `<span class="kt-badge kt-badge--primary kt-badge--inline">Proses</span>`;
                            } else if (data == "backlog") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Tertunda</span>`;
                            } else if (data == "selesai") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                            }
                            return element;
                        },
                    }
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
                if (!errors.success) {
                    showToastError('Gagal', errors.message);
                }
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);

                }
            });
        });
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
                            History Services
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
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#</th>
                                    <th>Tgl. Mulai</th>
                                    <th>Deskripsi Asset</th>
                                    <th>Tipe</th>
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
