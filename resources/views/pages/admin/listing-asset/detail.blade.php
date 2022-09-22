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
        .dataTables_wrapper .dataTable {
            margin: 0 !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        #tableProperti th,
        #tableProperti td {
            font-size: 12px;
        }

        th,
        td {
            vertical-align: middle;
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
        $(document).ready(function() {
            var table2 = $('#datatableLogService');
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
                        d.id_asset_data = "{{ $asset->id }}"
                    }
                },
                columns: [{
                        name: 'tanggal_mulai',
                        data: 'tanggal_mulai'
                    },
                    {
                        name: 'nama_service',
                        data: 'nama_service'
                    },
                    {
                        data: 'status_service'
                    },
                    {
                        data: 'deskripsi_service'
                    },
                    {
                        name: 'user',
                        data: 'user'
                    },
                    {
                        data: "btn_show_service",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    }
                    //Custom template data
                ],
            });
            $('#searchButton').on('click', function() {
                $('#lokasiTree').jstree('search', $('#searchTree').val());
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    table2.DataTable().ajax.reload();
                    showToastSuccess('Sukses', data.message);
                    if (data.form == 'editAsset') {
                        $('#modalEdit').modal('hide');
                        window.location.reload();
                    }
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "file_asset_service") {
                        $('#preview-file-image-error').html(errors[key][0]);
                    }
                }
            });

            setHeightPropertiAsset();
        });

        const setHeightPropertiAsset = () => {
            let height = $('.detailAssetBox').height();
            let minHeight = $('.assetPropertuTitle').height();
            let realHeight = height - (minHeight + 17);
            $('.assetProperti').css('height', realHeight);
        }

        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        const showAssetServices = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    const modal = $('.modalPreviewAssetService');
                    if (response.success) {
                        if (data.image.length > 0) {
                            $('#imgPreviewAssetService').attr('src', data.image[0].link);
                        } else {
                            $('#imgPreviewAssetService').attr('src',
                                'https://via.placeholder.com/400x250?text=Preview Image');
                        }
                        modal.modal('show');
                    }
                },
            })
        }
        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });
    </script>
    @include('pages.admin.listing-asset._script_modal_create')
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
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
                </div>
                <div class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalCreateAssetService')"
                        class="btn btn-primary shadow-custom btn-sm" type="button"><i class="fa fa-plus"></i>
                        Service</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="row">
                        <div class="col-md-6 col-12 detailAssetBox">
                            <div class="detail-asset-box">
                                <h5 class="title" id="assetNamePreview">{{ \Str::upper($asset->deskripsi) }}</h5>
                                <img id="imgPreviewAsset"
                                    src="{{ isset($asset->image[0]) ? $asset->image[0]->link : 'https://via.placeholder.com/400x250?text=No Image' }}"
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
                                <div class="d-flex justify-content-between mb-3 py-2 border-bottom">
                                    <h6 class="mb-0">Spesifikasi</h6>
                                    <h6 class="text-right mb-0">
                                        {{ $asset->spesifikasi }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex assetPropertuTitle justify-content-between align-items-center mb-3">
                                <h6 class="text-primary mb-0"><strong>Asset Properties</strong></h6>
                                <button onclick="openModalByClass('modalCreateAsset')"
                                    class="btn btn-primary btn-icon btn-sm shadow-custom" type="button"><i
                                        class="fa fa-edit"></i></button>
                            </div>
                            <div class="pt-3 pb-1 scroll-bar assetProperti"
                                style="border-radius: 9px; background: #E5F3FD;">
                                <table id="tableProperti" class="table table-striped">
                                    <tr>
                                        <td width="40%">Asset Group</td>
                                        <td><strong>{{ $asset->kategori_asset->group_kategori_asset->nama_group }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Kategori</td>
                                        <td><strong>{{ $asset->kategori_asset->nama_kategori }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Nilai perolehan</td>
                                        <td><strong>{{ $asset->nilai_perolehan }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Tgl. Perolehan</td>
                                        <td><strong>{{ $asset->tanggal_perolehan }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Jenis Penerimaan</td>
                                        <td><strong>{{ $asset->jenis_penerimaan }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Lokasi</td>
                                        <td><strong>{{ $asset->lokasi->nama_lokasi }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Ownership</td>
                                        <td><strong>{{ $asset->owner_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Tgl Register</td>
                                        <td><strong>{{ $asset->tgl_register }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Satuan</td>
                                        <td><strong>{{ $asset->satuan_asset->nama_satuan }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Vendor</td>
                                        <td><strong>{{ $asset->vendor->nama_vendor }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">No. Surat / Memo</td>
                                        <td><strong>{{ $asset->no_memo_surat ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">No. PO</td>
                                        <td><strong>{{ $asset->no_po ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">No. SP3</td>
                                        <td><strong>{{ $asset->no_sp3 ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">No. Seri</td>
                                        <td><strong>{{ $asset->no_seri ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Kode Akun</td>
                                        <td><strong>{{ $asset->kelas_asset->no_akun ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Last Update</td>
                                        <td><strong>{{ $asset->updated_at }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <img src="{{ route('admin.listing-asset.preview-qr') . '?filename=' . $asset->qr_code }}"
                                                class="my-3" width="200px" alt="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <a href="{{ route('admin.listing-asset.download-qr') . '?filename=' . $asset->qr_code }}"
                                                download class="btn btn-primary shadow-custom btn-sm"><i
                                                    class="fa fa-download"></i>
                                                Unduh QR</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <ul class="nav nav-tabs mb-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#" data-target="#kt_tabs_1_1">Log
                                Opname</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" data-target="#kt_tabs_1_2">Log
                                Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" data-target="#kt_tabs_1_3">Log
                                Moving</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" data-target="#kt_tabs_1_4">Log
                                Peminjaman</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status Awal</th>
                                            <th>Status Akhir</th>
                                            <th>Catatan</th>
                                            <th>User</th>
                                            <th>#</th>
                                            <th>Log</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>12 Januari 2022</td>
                                            <td>Baik</td>
                                            <td>Baik</td>
                                            <td>Asset Masih Aman</td>
                                            <td>User</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-icon"><i
                                                        class="fa fa-image"></i></a>
                                            </td>
                                            <td>12 Jan 2022, 12:45 Update</td>
                                        </tr>
                                        <tr>
                                            <td>12 Januari 2022</td>
                                            <td>Baik</td>
                                            <td>Baik</td>
                                            <td>Asset Masih Aman</td>
                                            <td>User</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-icon"><i
                                                        class="fa fa-image"></i></a>
                                            </td>
                                            <td>12 Jan 2022, 12:45 Update</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="datatableLogService">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis Service</th>
                                            <th>Status Service</th>
                                            <th>Catatan</th>
                                            <th>User</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status Awal</th>
                                        <th>Status Akhir</th>
                                        <th>Catatan</th>
                                        <th>User</th>
                                        <th>#</th>
                                        <th>Log</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12 Januari 2022</td>
                                        <td>Baik</td>
                                        <td>Baik</td>
                                        <td>Asset Masih Aman</td>
                                        <td>User</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></a>
                                        </td>
                                        <td>12 Jan 2022, 12:45 Update</td>
                                    </tr>
                                    <tr>
                                        <td>12 Januari 2022</td>
                                        <td>Baik</td>
                                        <td>Baik</td>
                                        <td>Asset Masih Aman</td>
                                        <td>User</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></a>
                                        </td>
                                        <td>12 Jan 2022, 12:45 Update</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status Awal</th>
                                        <th>Status Akhir</th>
                                        <th>Catatan</th>
                                        <th>User</th>
                                        <th>#</th>
                                        <th>Log</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12 Januari 2022</td>
                                        <td>Baik</td>
                                        <td>Baik</td>
                                        <td>Asset Masih Aman</td>
                                        <td>User</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></a>
                                        </td>
                                        <td>12 Jan 2022, 12:45 Update</td>
                                    </tr>
                                    <tr>
                                        <td>12 Januari 2022</td>
                                        <td>Baik</td>
                                        <td>Baik</td>
                                        <td>Asset Masih Aman</td>
                                        <td>User</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></a>
                                        </td>
                                        <td>12 Jan 2022, 12:45 Update</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.listing-asset._modal_edit')
    @include('pages.admin.listing-asset._modal_create_service')
    @include('pages.admin.listing-asset._modal_preview_service')
@endsection
