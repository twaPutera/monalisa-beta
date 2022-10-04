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
                ajax: "{{ route('admin.listing-inventaris.datatable') }}",
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
                        data: 'kode_inventori'
                    },
                    {
                        data: 'nama_inventori'
                    },
                    {
                        name: 'kategori',
                        data: 'kategori'
                    },
                    {
                        name: 'sebelumnya',
                        data: 'sebelumnya'
                    },
                    {
                        name: 'saat_ini',
                        data: 'saat_ini'
                    },
                    {
                        name: 'harga_beli',
                        data: 'harga_beli',
                        render: function(o) {
                            return "Rp. " + o;
                        }
                    },
                    {
                        data: 'deskripsi_inventori'
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
                    generateKategoriSelect();
                    generateSatuanSelect();
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

            generateKategoriSelect();
            generateSatuanSelect();

            $('.modalCreateInventarisData').on('shown.bs.modal', function(e) {
                generateKategoriSelect();
                generateSatuanSelect();
                $('.selectKategoriData').select2({
                    width: '100%',
                    placeholder: 'Pilih Kategori Inventaris',
                    allowClear: true,
                    parent: $(this)
                });

                $('.selectSatuanData').select2({
                    width: '100%',
                    placeholder: 'Pilih Satuan Inventaris',
                    allowClear: true,
                    parent: $(this)
                });
            })
        });

        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    const modal = $('.modalDetailInventarisData');
                    const detail = modal.find('.modalDetailBodyData');
                    detail.empty();
                    detail.append(response);
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
                    form.find('input[name=kode_inventori]').val(response.data.kode_inventori);
                    form.find('input[name=nama_inventori]').val(response.data.nama_inventori);
                    form.find('input[name=stok_sebelumnya]').val(response.data.jumlah_sebelumnya);
                    form.find('input[name=stok_saat_ini]').val(response.data.jumlah_saat_ini);
                    form.find('input[name=harga_beli]').val(response.data.harga_beli);
                    form.find('textarea[name=deskripsi_inventori]').val(response.data.deskripsi_inventori);
                    modal.on('shown.bs.modal', function(e) {
                        // generateKategoriSelect();
                        $('#selectKategoriDataEdit option[value="' + response.data
                            .id_kategori_inventori + '"]').attr('selected', 'selected');
                        $('#selectKategoriDataEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Kategori Inventaris',
                            allowClear: true,
                            parent: $(this)
                        });
                        // $('#selectGroupEdit').select2('val', response.data.id_group_kategori_asset);
                        $('#selectSatuanDataEdit option[value="' + response.data
                                .id_satuan_inventori + '"]')
                            .prop('selected', 'selected');
                        $('#selectSatuanDataEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Satuan Inventaris',
                            allowClear: true,
                            parent: $(this)
                        });
                    })
                    modal.modal('show');
                }
            })
        }

        const stokEdit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditStokData');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=jumlah_sebelumnya]').val(response.data.jumlah_sebelumnya);
                    form.find('input[name=jumlah_saat_ini]').val(response.data.jumlah_saat_ini);
                    modal.modal('show');
                }
            })
        }
        const generateKategoriSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-inventori.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectKategoriData');
                        select.empty();
                        select.append(`<option value="">Pilih Kategori Inventaris</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const generateSatuanSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.satuan-inventori.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectSatuanData');
                        select.empty();
                        select.append(`<option value="">Pilih Satuan Inventaris</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    </script>
@endsection
@section('main-content')
    <div class="row">

        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Listing Inventaris
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateInventarisData')"
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
                                    <th>Jenis Inventaris</th>
                                    <th>Kategori Inventaris</th>
                                    <th>Merk Inventaris</th>
                                    <th>Jumlah Inventaris Sebelumnya</th>
                                    <th>Jumlah Inventaris Saat Ini</th>
                                    <th>Harga Beli</th>
                                    <th>Deskripsi Inventaris</th>
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
    @include('pages.admin.listing-inventaris._modal_create')
    @include('pages.admin.listing-inventaris._modal_edit')
    @include('pages.admin.listing-inventaris._modal_edit_stok')
    @include('pages.admin.listing-inventaris._modal_detail')
@endsection
