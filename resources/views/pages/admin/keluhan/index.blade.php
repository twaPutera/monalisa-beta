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
                ajax: "{{ route('admin.keluhan.datatable') }}",
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
                        name: 'tanggal_keluhan',
                        data: 'tanggal_keluhan'
                    },
                    {
                        data: 'nama_asset',
                        data: 'nama_asset'
                    },
                    {
                        data: 'lokasi_asset',
                        data: 'lokasi_asset'
                    },
                    {
                        data: 'catatan_pengaduan',
                        data: 'catatan_pengaduan'
                    },
                    {
                        data: 'gambar_pengaduan',
                        data: 'gambar_pengaduan'
                    },
                    {
                        data: 'created_by_name',
                        data: 'created_by_name'
                    },
                    {
                        data: 'status_pengaduan',
                        data: 'status_pengaduan'
                    },
                    {
                        data: 'catatan_admin',
                        data: 'catatan_admin'
                    },

                ],
                columnDefs: [{
                    targets: 8,
                    render: function(data, type, full, meta) {
                        let element = "";
                        if (data == "dilaporkan") {
                            element +=
                                `<span class="kt-badge kt-badge--warning kt-badge--inline">Laporan Masuk</span>`;
                        } else if (data == "diproses") {
                            element +=
                                `<span class="kt-badge kt-badge--info kt-badge--inline">Diproses</span>`;
                        } else if (data == "selesai") {
                            element +=
                                `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                        }
                        return element;
                    },
                }],
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

        const showKeluhanImage = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    const modal = $('.modalPreviewAsset');
                    if (response.success) {
                        if (data.image.length > 0) {
                            $('#imgPreviewAsset').attr('src', data.image[0].link);
                        } else {
                            $('#imgPreviewAsset').attr('src',
                                'https://via.placeholder.com/400x250?text=Preview Image');
                        }
                        modal.modal('show');
                    }
                },
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
                    const modal = $('.modalEditKeluhanData');
                    const form = modal.find('form');
                    if (response.data.status_pengaduan === "dilaporkan") {
                        var status = '<button class="btn btn-warning btn-sm">Laporan Masuk</button>';
                    } else if (response.data.status_pengaduan === "diproses") {
                        var status = '<button class="btn btn-info btn-sm">Diproses</button>';
                    } else if (response.data.status_pengaduan === "selesai") {
                        var status = '<button class="btn btn-success btn-sm">Selesai</button>';
                    }
                    form.attr('action', url_update);
                    if (response.data.asset_data != null) {
                        form.find('input[name=nama_asset]').val(response.data.asset_data.deskripsi);
                        form.find('input[name=lokasi_asset]').val(response.data.asset_data.lokasi
                            .nama_lokasi);
                        form.find('input[name=kelompok_asset]').val(response.data.asset_data.kategori_asset
                            .group_kategori_asset.nama_group);
                        form.find('input[name=jenis_asset]').val(response.data.asset_data.kategori_asset
                            .nama_kategori);
                    } else {
                        form.find('input[name=nama_asset]').val("-");
                        form.find('input[name=kelompok_asset]').val("-");
                        form.find('input[name=jenis_asset]').val("-");
                        form.find('input[name=lokasi_asset]').val(response.data.lokasi
                            .nama_lokasi);
                    }
                    form.find('input[name=tanggal_pengaduan]').val(response.data.tanggal_pengaduan);
                    form.find('#status_laporan').empty();
                    form.find('#status_laporan').append(status);
                    form.find('textarea[name=catatan_pengaduan]').val(response.data.catatan_pengaduan);
                    form.find('input[name=diajukan_oleh]').val(response.data.created_by_name);
                    modal.modal('show');
                }
            })
        }

        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalDetailKeluhanData');
                    const form = modal.find('form');
                    if (response.data.status_pengaduan === "dilaporkan") {
                        var status = '<button class="btn btn-warning btn-sm">Laporan Masuk</button>';
                    } else if (response.data.status_pengaduan === "diproses") {
                        var status = '<button class="btn btn-info btn-sm">Diproses</button>';
                    } else if (response.data.status_pengaduan === "selesai") {
                        var status = '<button class="btn btn-success btn-sm">Selesai</button>';
                    }
                    if (response.data.asset_data != null) {
                        form.find('input[name=nama_asset]').val(response.data.asset_data.deskripsi);
                        form.find('input[name=lokasi_asset]').val(response.data.asset_data.lokasi
                            .nama_lokasi);
                        form.find('input[name=kelompok_asset]').val(response.data.asset_data.kategori_asset
                            .group_kategori_asset.nama_group);
                        form.find('input[name=jenis_asset]').val(response.data.asset_data.kategori_asset
                            .nama_kategori);
                    } else {
                        form.find('input[name=nama_asset]').val("-");
                        form.find('input[name=kelompok_asset]').val("-");
                        form.find('input[name=jenis_asset]').val("-");
                        form.find('input[name=lokasi_asset]').val(response.data.lokasi
                            .nama_lokasi);
                    }
                    form.find('input[name=tanggal_pengaduan]').val(response.data.tanggal_pengaduan);
                    form.find('#status_laporan').empty();
                    form.find('#status_laporan').append(status);
                    form.find('textarea[name=catatan_pengaduan]').val(response.data.catatan_pengaduan);
                    form.find('input[name=diajukan_oleh]').val(response.data.created_by_name);
                    form.find('textarea[name=catatan_admin]').val(response.data.catatan_admin);
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
                            Data Keluhan
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Nama Asset</th>
                                    <th>Lokasi Asset</th>
                                    <th>Catatan Pengaduan</th>
                                    <th>Gambar Pengaduan</th>
                                    <th>Dilaporkan Oleh</th>
                                    <th>Status Pengaduan</th>
                                    <th>Catatan Admin</th>
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
    @include('pages.admin.keluhan._modal_preview')
    @include('pages.admin.keluhan._modal_edit_keluhan')
    @include('pages.admin.keluhan._modal_detail_keluhan')
@endsection
