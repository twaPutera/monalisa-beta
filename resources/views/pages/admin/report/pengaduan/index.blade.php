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
                // searchDelay: 500,
                searching: false,
                bLengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.keluhan.datatable') }}",
                    data: function(d) {
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
                        d.status_pengaduan = "selesai";
                        d.id_lokasi = $('#lokasiAssetCreateService').val();
                        d.id_kategori_asset = $('#listKategoriAssetLocation').val();
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

            $('#lokasiAssetCreateService').select2({
                width: '150px',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })
            $('#listKategoriAssetLocation').select2({
                width: '150px',
                placeholder: 'Pilih Jenis Asset',
                padding: '10px',
                allowClear: true,
            })
            generateLocationServiceSelect();
            generateLocationAsset();
            exportData();
        });

        const exportData = () => {
            let awal = $('.datepickerAwal').val();
            let akhir = $('.datepickerAkhir').val();
            let id_lokasi = $('#lokasiAssetCreateService').val();
            let id_kategori_asset = $('#listKategoriAssetLocation').val();
            $('#tgl_awal_export').val(awal);
            $('#tgl_akhir_export').val(akhir);
            $('#id_lokasi_export').val(id_lokasi);
            $('#id_kategori_asset_export').val(id_kategori_asset);

        }

        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    const modal = $('.modalDetailPengaduanData');
                    const detail = modal.find('.modalDetailBodyData');
                    detail.empty();
                    detail.append(response);
                    modal.modal('show');
                }
            })
        }

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
        const generateLocationServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#lokasiAssetCreateService');
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

        const generateLocationAsset = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-asset.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#listKategoriAssetLocation');
                        select.empty();
                        select.append(`<option value="">Pilih Jenis Asset</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const filterTableService = () => {
            exportData();
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
                            History Pengaduan
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <form action="{{ route('admin.report.history-pengaduan.download-export') }}" method="get">
                                    <div class="d-md-flex d-block align-items-center mt-2 mb-2">
                                        <div class="mr-2">
                                            <select name="" onchange="filterTableService()" class="form-control"
                                                style="width: 150px;" id="lokasiAssetCreateService">

                                            </select>
                                        </div>
                                        <select name="" onchange="filterTableService()" class="form-control"
                                            style="width: 150px;" id="listKategoriAssetLocation">

                                        </select>
                                        <input type="text" onchange="filterTableService()" name="tanggal_awal" readonly
                                            class="form-control datepickerAwal mx-2" style="width: 150px;"
                                            placeholder="Tanggal Awal">
                                        <input type="text" onchange="filterTableService()" name="tanggal_akhir" readonly
                                            class="form-control datepickerAkhir mr-2" style="width: 150px;"
                                            placeholder="Tanggal Akhir">
                                        <input type="hidden" name="id_lokasi" id="id_lokasi_export">
                                        <input type="hidden" name="id_kategori_asset" id="id_kategori_asset_export">
                                        <input type="hidden" name="tgl_awal" id="tgl_awal_export">
                                        <input type="hidden" name="tgl_akhir" id="tgl_akhir_export">
                                        <button class="btn btn-success shadow-custom btn-sm" type="submit"><i
                                                class="fas fa-print"></i>
                                            Export Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="input-group mr-3" style="width: 250px;">
                                <input type="text" id="searchServices" class="form-control form-control-sm"
                                    placeholder="Search for...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-icon" onclick="filterTableService()"
                                        id="searchButton" type="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="datatableLogService">
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
    @include('pages.admin.report.pengaduan.components.modal._modal_preview')
    @include('pages.admin.report.pengaduan.components.modal._modal_detail_keluhan')
@endsection
