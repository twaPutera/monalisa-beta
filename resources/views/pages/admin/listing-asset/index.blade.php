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
        div.dataTables_wrapper {
            width: 200% !important;
        }
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
        var table = $('#datatableExample');
        $(document).ready(function() {
            $('#lokasiTree').jstree({
                "core": {
                    "themes": {
                        "responsive": false
                    },
                    // so that create works
                    "check_callback": function(e) {
                        console.log(e);
                    },
                    'data': {
                        url: "{{ route('admin.setting.lokasi.get-node-tree') }}"
                    }
                },
                "types": {
                    "default": {
                        "icon": "fa fa-map-marker kt-font-success"
                    },
                    "file": {
                        "icon": "fa fa-file  kt-font-success"
                    }
                },
                "plugins": ["dnd", "types", "search", "adv_search"]
            }).on('changed.jstree', function(e, data) {
                $('#lokasiParentId').val(data.selected[0]);
                $('.select2Lokasi option[value="' + data.selected[0] + '"]').attr('selected', 'selected');
                table.DataTable().ajax.reload();
            });

            $('#searchButton').on('click', function() {
                $('#lokasiTree').jstree('search', $('#searchTree').val());
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
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

            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                ordering: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable') }}",
                    // data: function(d) {
                    //     d.id_parent_lokasi = $('#lokasiParentId').val();
                    // }
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
                        data: 'kode_asset'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'kategori_asset.nama_kategori'
                    },
                    {
                        data: 'status_kondisi'
                    },
                    {
                        data: 'tanggal_perolehan'
                    },
                    {
                        data: 'nilai_perolehan'
                    },
                    {
                        data: 'lokasi.nama_lokasi'
                    },
                    {
                        data: 'owner_name'
                    },
                    {
                        data: 'register_oleh'
                    },
                    {
                        data: 'satuan_asset.nama_satuan'
                    },
                    {
                        data: 'vendor.nama_vendor'
                    }
                ],
                columnDefs: [

                ],
            });

            $('.datepickerCreate').datepicker({
                todayHighlight: true,
                width: '100%',
                format: 'yyyy-mm-dd',
                autoclose: true,
            })
        });

        const generateGroupSelect2 = (idElement) => {
            $('#' + idElement).select2({
                width: '100%',
                placeholder: 'Pilih Group',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.group-kategori-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        const generateKategoriSelect2Create = (idElement, idGroup) => {
            $('#' + idElement).removeAttr('disabled');
            $('#' + idElement).select2({
                width: '100%',
                placeholder: 'Pilih Kategori',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                            id_group_kategori_asset: idGroup,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        $('.modalCreateAsset').on('shown.bs.modal', function() {
            setTimeout(() => {
                generateGroupSelect2('groupAssetCreate');
                generateSelect2Lokasi();
                generateKelasAsset();
                generateSatuanAsset();
                generateVendorAsset();
            }, 2000);
        });

        $('#groupAssetCreate').on('change', function() {
            generateKategoriSelect2Create('kategoriAssetCreate', $(this).val());
        });

        const generateSelect2Lokasi = () => {
            $('#lokasiAssetCreate').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.lokasi.get-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        const generateKelasAsset = () => {
            $('#kelasAssetCreate').select2({
                width: '100%',
                placeholder: 'Pilih Kelas',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.kelas-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        const generateVendorAsset = () => {
            $('#vendorAssetCreate').select2({
                width: '100%',
                placeholder: 'Pilih Vendor',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.vendor.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        const generateSatuanAsset = () => {
            $('#satuanAssetCreate').select2({
                width: '100%',
                placeholder: 'Pilih Satuan',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.satuan-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        const showAsset = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    if (response.success) {
                        $('#assetNamePreview').text(data.deskripsi);
                        if (data.image.length > 0) {
                            $('#imgPreviewAsset').attr('src', data.image[0].link);
                        } else {
                            $('#imgPreviewAsset').attr('src',
                                'https://via.placeholder.com/400x250?text=Preview Image');
                        }
                        $('#linkDetailAsset').attr('href', data.link_detail);
                    }
                },
            })
        }
    </script>
@endsection
@section('main-content')
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
                <div class="kt-portlet__body scroll-bar">
                    <div class="input-group mb-2">
                        <input type="text" id="searchTree" class="form-control" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" id="searchButton" type="button"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div id="lokasiTree"></div>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchTree" class="form-control form-control-sm"
                            placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" id="searchButton" type="button"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-secondary shadow-custom" type="button"><i class="fas fa-filter mr-2"></i>
                        Filter</button>
                </div>
                <div class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalImportAsset')" class="btn btn-success shadow-custom btn-sm mr-2"
                        type="button"><i class="fa fa-file"></i> Import CSV</button>
                    <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"
                        type="button"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-8" id="colTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><strong class="text-primary">Data Asset</strong> <span class="text-gray"> -
                                Lokasi Asset(Rektorat)</span></h5>
                        <h5 class="text-primary"><strong>Total 4</strong></h5>
                    </div>
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped table-hover" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">Aksi</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="150px">Asset Group</th>
                                    <th width="150px">Kategori</th>
                                    <th width="100px">Status</th>
                                    <th width="100px">Tgl. Perolehan</th>
                                    <th width="150px">Nilai Perolehan</th>
                                    <th width="150px">Lokasi</th>
                                    <th width="150px">Ownership</th>
                                    <th width="150px">Register Oleh</th>
                                    <th width="150px">Satuan</th>
                                    <th width="150px">Vendor</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-4" id="colDetail">
                    <h5 class="text-primary mb-0 mb-3"><strong class="">Overview</strong></h5>
                    <div class="detail-asset-box">
                        <h5 class="title" id="assetNamePreview">ASSET NAME</h5>
                        <img id="imgPreviewAsset" src="https://via.placeholder.com/400x250?text=Preview Image"
                            alt="">
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Status saat ini</h6>
                            <span
                                class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Baik</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6 class="">Catatan</h6>
                            <h6 class="text-right">Perlu Pergantian Oli dan pergantian berbagai macam sparepart</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Log Terakhir</h6>
                            <h6 class="text-right">18 Agustus 2020</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Dicek Oleh</h6>
                            <h6 class="text-right"><strong>Rizal</strong></h6>
                        </div>
                        <div class="d-flex justify-content-between mb-3 py-2 align-items-center border-bottom">
                            <h6 class="mb-0">Status Peminjaman</h6>
                            <h6 class="text-right mb-0 text-success" style="font-size: 24px"><i
                                    class="fas fa-check-circle"></i></h6>
                        </div>
                        <div class="text-right">
                            <a href="#" class="text-primary" id="linkDetailAsset"><u>Lihat Detail</u></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.listing-asset._modal_create')
    @include('pages.admin.listing-asset._modal_import')
@endsection
