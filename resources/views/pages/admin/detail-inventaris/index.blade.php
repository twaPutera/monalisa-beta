@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
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
                ajax: {
                    url: "{{ route('admin.detail-inventaris.datatable', $inventaris->id) }}",
                    data: function(d) {
                        d.id_inventaris = "{{ $inventaris->id }}"
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
                        name: 'kode_inventori',
                        data: 'kode_inventori'
                    },
                    {
                        name: 'nama_inventori',
                        data: 'nama_inventori'
                    },
                    {
                        name: 'lokasi',
                        data: 'lokasi'
                    },
                    {
                        data: 'stok'
                    },

                    {
                        data: 'keterangan'
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
                    generateLokasiSelect();
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

            generateLokasiSelect();

            $('.modalCreateDetailInventaris').on('shown.bs.modal', function(e) {
                generateLokasiSelect();

                $('.selectLokasi').select2({
                    width: '100%',
                    placeholder: 'Pilih Lokasi',
                    allowClear: true,
                    parent: $(this)
                });
            })
        });

        const edit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditDetailInventaris');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=stok]').val(response.data.stok);
                    form.find('textarea[name=keterangan]').val(response.data.keterangan);
                    modal.on('shown.bs.modal', function(e) {
                        $('#selectLokasiEdit option[value="' + response.data
                                .id_lokasi + '"]')
                            .prop('selected', 'selected');
                        $('#selectLokasiEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Lokasi Inventaris',
                            allowClear: true,
                            parent: $(this)
                        });
                    })
                    modal.modal('show');
                }
            })
        }



        const generateLokasiSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectLokasi');
                        select.empty();
                        select.append(`<option value="">Pilih Lokasi</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
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
                            Data Listing Inventaris --
                            {{ $inventaris->nama_inventori . ' (' . $inventaris->kode_inventori . ')' }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateDetailInventaris')"
                                    class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Data </button>
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
                                    <th>Kode Inventaris</th>
                                    <th>Nama Inventaris</th>
                                    <th>Lokasi Inventaris</th>
                                    <th>Stok Inventaris</th>
                                    <th>Keterangan</th>
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
    @include('pages.admin.detail-inventaris._modal_create')
    @include('pages.admin.detail-inventaris._modal_edit')
@endsection
