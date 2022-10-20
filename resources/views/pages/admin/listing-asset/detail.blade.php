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
            var tableServices = $('#datatableLogService');
            var tableLogAsset = $('#logDataTable');
            var tableLogPemindahan = $('#tableLogPemindahan');
            var tableLogOpname = $('#tableLogOpname');
            var tableLogPeminjaman = $('#tableLogPeminjaman');

            tableLogPeminjaman.DataTable({
                responsive: true,
                // searchDelay: 500,
                bLengthChange: false,
                paging: false,
                info: false,
                processing: true,
                searching: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.peminjaman.datatable') }}",
                    data: function(d) {
                        d.id_asset_data = '{{ $asset->id }}';
                    }
                },
                columns: [
                    {
                        data: 'tanggal_peminjaman'
                    },
                    {
                        data: 'tanggal_pengembalian'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'nama_peminjam'
                    },
                    {
                        data: "action",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [0, 1],
                        render: function (data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        targets: [2],
                        render: function(data, type, full, meta) {
                            let element = '';
                            if (data == 'disetujui') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Disetujui</span>';
                            } else if (data == 'ditolak') {
                                element =
                                    '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Ditolak</span>';
                            } else if (data == 'pending') {
                                element =
                                    '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Pending</span>';
                            } else if (data == 'dipinjam') {
                                element =
                                    '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">Dipinjam</span>';
                            } else if (data == 'terlambat') {
                                element =
                                    '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Terlambat</span>';
                            } else if (data == 'diproses') {
                                element =
                                    '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Diproses</span>';
                            }
                            return element;
                        },
                    },
                ],
            });

            tableServices.DataTable({
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
                        name: 'status_service',
                        data: 'status_service'
                    },
                    {
                        name: 'deskripsi_service',
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
                columnDefs: [{
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    }
                    //Custom template data
                ],
            });

            tableLogAsset.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.log-asset.datatable') }}",
                    data: function(d) {
                        d.asset_id = "{{ $asset->id }}"
                    }
                },
                columns: [{
                        name: 'created_at',
                        data: 'created_at',
                        width: '150px'
                    },
                    {
                        name: 'created_by',
                        data: 'created_by',
                        width: '100px'
                    },
                    {
                        name: 'log',
                        data: 'log'
                    },
                ],
                columnDefs: [{
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    }
                    //Custom template data
                ],
            });

            tableLogOpname.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.log-opname.datatable') }}",
                    data: function(d) {
                        d.asset_id = "{{ $asset->id }}"
                    }
                },
                columns: [{
                        name: 'created_at',
                        data: 'created_at',
                        width: '150px'
                    },
                    {
                        data: 'status_awal',
                    },
                    {
                        data: 'status_akhir',
                    },
                    {
                        data: 'keterangan',
                    },
                    {
                        name: 'created_by',
                        data: 'created_by',
                        width: '100px'
                    },
                    {
                        name: 'action',
                        data: 'action'
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    }
                    //Custom template data
                ],
            });

            tableLogPemindahan.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.pemindahan-asset.datatable') }}",
                    data: function(d) {
                        d.id_asset = "{{ $asset->id }}"
                    }
                },
                columns: [{
                        name: 'created_at',
                        data: 'created_at',
                        width: '100px'
                    },
                    {
                        data: "btn_download",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'btn_download'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'penyerah',
                        data: 'penyerah'
                    },
                    {
                        name: 'penerima',
                        data: 'penerima'
                    },
                    {
                        name: 'pembuat',
                        data: 'pembuat',
                        width: '100px'
                    },
                ],
                columnDefs: [{
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "pending") {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>`;
                            } else if (data == "ditolak") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Ditolak</span>`;
                            } else if (data == "disetujui") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Disetujui</span>`;
                            }
                            return element;
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
                    tableServices.DataTable().ajax.reload();
                    tableLogPemindahan.DataTable().ajax.reload();
                    tableLogAsset.DataTable().ajax.reload();
                    tableLogOpname.DataTable().ajax.reload();
                    showToastSuccess('Sukses', data.message);
                    if (data.form == 'editAsset') {
                        $('#modalEdit').modal('hide');
                        window.location.reload();
                    }
                } else {
                    showToastError('Gagal', data.message);
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                console.log(errors);
                if (errors.success && !errors.success) {
                    showToastError('Gagal', errors.message);
                }
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

            getPositionPenyerah();

            // generateOptionUnit();

            // generateOptionPosition();
        });

        const generateNewOwnerAsset = () => {
            $('#newOwnership').select2({
                width: '100%',
                placeholder: 'Pilih Pemilik Selanjutnya',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.listing-asset.get-all-data-owner-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                            except_id: '{{ $asset->ownership }}'
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

        const generateOptionUnit = () => {
            $.ajax({
                url: '{{ route('sso-api.get-data-unit') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $('.unitKerjaSelect').html(option);
                    }
                }
            })
        }

        const generateOptionPosition = () => {
            $.ajax({
                url: '{{ route('sso-api.get-data-position') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $('.positionSelect').html(option);
                    }
                }
            })
        }

        $('#modalCreatePemindahan').on('shown.bs.modal', function() {
            generateNewOwnerAsset();
            $(`#positionPenyerahSelect`).select2({
                width: '100%',
                placeholder: 'Pilih Jabatan',
                dropdownParent: $('.modal.show'),
            });
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
        $('.datepickerCreateSelesai').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        let buttonService = $('#create-service');
        let buttonPemutihan = $('#pemutihan');
        const showButton = (elementId) => {
            $(".btn-log").hide();
            $('#' + elementId).show();
        }

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

        const showOpname = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    const modal = $('.modalPreviewOpname');
                    if (response.success) {
                        if (data.image.length > 0) {
                            $('#imgPreviewOpname').attr('src', data.image[0].link);
                        } else {
                            $('#imgPreviewOpname').attr('src',
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
    <script>
        const getPositionPenyerah = () => {
            $.ajax({
                url: "{{ route('sso-api.get-data-position-by-guid') }}",
                data: {
                    guid: "{{ $asset->ownership }}"
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $(`#positionPenyerahSelect`).html('<option value="0">Pilih Jabatan</option>');
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $(`#positionPenyerahSelect`).append(option);
                    }
                }
            })
        }

        const getPositionByGuid = (select, idElement) => {
            let guid = $(select).val();
            $.ajax({
                url: "{{ route('sso-api.get-data-position-by-guid') }}",
                data: {
                    guid: guid
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $(`#${idElement}`).html('<option value="0">Pilih Jabatan</option>');
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $(`#${idElement}`).append(option);
                        $(`#${idElement}`).select2({
                            width: '100%',
                            placeholder: 'Pilih Jabatan',
                            dropdownParent: $('.modal.show'),
                        });
                    }
                }
            })
        }

        const getUnitByPosition = (select, idElement) => {
            let guid = $(select).val();
            $.ajax({
                url: "{{ route('sso-api.get-data-unit-by-position') }}",
                data: {
                    guid_position: guid
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $(`#${idElement}`).html('<option value="0">Pilih Unit</option>');
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $(`#${idElement}`).append(option);
                        $(`#${idElement}`).select2({
                            width: '100%',
                            placeholder: 'Pilih Unit',
                            dropdownParent: $('.modal.show'),
                        });
                    }
                }
            })
        }
    </script>
    @include('pages.admin.listing-asset.components.script-js._script_modal_create')
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
                    @if ($asset->is_pemutihan != 1)
                        <button onclick="openModalByClass('modalCreatePemindahan')"
                            class="btn btn-primary shadow-custom btn-sm mr-2 btn-log" style="display: none;" id="pemindahan"
                            type="button">
                            <i class="fas fa-sync-alt"></i>
                            Pindahkan Asset</button>
                        <button onclick="openModalByClass('modalCreateAssetService')" style="display: none;"
                            id="create-service" class="btn btn-primary shadow-custom btn-sm btn-log" type="button"><i
                                class="fa fa-plus"></i>
                            Service</button>
                    @endif
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
                                    <h6>Status Kondisi Asset</h6>
                                    @php
                                        if ($asset->status_kondisi == 'rusak') {
                                            $kondisi = '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Rusak</span>';
                                        } elseif ($asset->status_kondisi == 'maintenance') {
                                            $kondisi = '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Maintenance</span>';
                                        } elseif ($asset->status_kondisi == 'tidak-lengkap') {
                                            $kondisi = '<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Lengkap</span>';
                                        } else {
                                            $kondisi = '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>';
                                        }
                                    @endphp
                                    {!! $kondisi !!}
                                </div>
                                <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                                    <h6>Status Pemutihan</h6>
                                    @php
                                        if ($asset->is_pemutihan == 0) {
                                            $pemutihan = '<h6 class="text-center text-danger" style="font-size: 24px"><i
                                    class="fas fa-times-circle"></i></h6>';
                                        } elseif ($asset->is_pemutihan == 1) {
                                            $pemutihan = '<h6 class="text-center text-success" style="font-size: 24px"><i
                                    class="fas fa-check-circle"></i></h6>';
                                        }
                                    @endphp
                                    {!! $pemutihan !!}
                                </div>
                                <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                                    <h6 class="">Catatan</h6>
                                    <h6 class="text-right">
                                        {{ $asset->log_asset_opname->count() > 0 ? $asset->log_asset_opname->sortByDesc('created_at')->first()->keterangan : 'Tidak Ada' }}
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                                    <h6>Log Terakhir</h6>
                                    <h6 class="text-right">
                                        {{ $asset->log_asset_opname->count() > 0 ? \Carbon\Carbon::parse($asset->log_asset_opname->sortByDesc('created_at')->first()->tanggal_opname)->format('d F Y') : 'Tidak Ada' }}
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                                    <h6>Dicek Oleh</h6>
                                    <h6 class="text-right">
                                        {{ $asset->created_by_opname }}
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 py-2 align-items-center border-bottom">
                                    <h6 class="mb-0">Status Peminjaman</h6>
                                    @php

                                        if ($asset->is_pinjam == 0) {
                                            $pinjam = '<h6 class="text-center text-danger" style="font-size: 24px"><i
                                                                                                                                                                                                    class="fas fa-times-circle"></i></h6>';
                                        } elseif ($asset->is_pinjam == 1) {
                                            $pinjam = '<h6 class="text-center text-success" style="font-size: 24px"><i
                                                                                                                                                                                                    class="fas fa-check-circle"></i></h6>';
                                        } else {
                                            $pinjam = '<h6 class="text-center text-secondary" style="font-size: 24px"><i
                                                                                                                                                                                                    class="fas fa-question-circle"></i></h6>';
                                        }
                                    @endphp

                                    {!! $pinjam !!}
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
                                @if ($asset->is_pemutihan != 1)
                                    <button onclick="openModalByClass('modalCreateAsset')"
                                        class="btn btn-primary btn-icon btn-sm shadow-custom" type="button"><i
                                            class="fa fa-edit"></i></button>
                                @endif
                            </div>
                            <div class="pt-3 pb-1 scroll-bar assetProperti"
                                style="border-radius: 9px; background: #E5F3FD;">
                                <table id="tableProperti" class="table table-striped">
                                    <tr>
                                        <td width="40%">Kelompok</td>
                                        <td><strong>{{ $asset->kategori_asset->group_kategori_asset->nama_group ?? 'Kelompok Tidak Ada' }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Jenis</td>
                                        <td><strong>{{ $asset->kategori_asset->nama_kategori ?? 'Jenis Tidak Ada' }}</strong>
                                        </td>
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
                                        <td><strong>{{ $asset->lokasi->nama_lokasi ?? 'Tidak dalam lokasi' }}</strong></td>
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
                                        <td><strong>{{ $asset->satuan_asset->nama_satuan ?? 'Satuan Tidak Ada' }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Vendor</td>
                                        <td><strong>{{ $asset->vendor->nama_vendor ?? 'Tidak Memiliki Vendor' }}</strong>
                                        </td>
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
                            <a class="nav-link active" data-toggle="tab" href="#" onclick="hideButton()"
                                data-target="#kt_tabs_1_1">Log
                                Opname</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" onclick="showButton('create-service')"
                                data-target="#kt_tabs_1_2">Log
                                Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" onclick="showButton('pemindahan')"
                                data-target="#kt_tabs_1_3">Log
                                Pemindahan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" onclick="showButton('pemutihan')"
                                data-target="#kt_tabs_1_4">Log
                                Peminjaman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" onclick="showButton('pemutihan')"
                                data-target="#kt_tabs_1_5">Log
                                Riwayat</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="tableLogOpname">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status Awal</th>
                                            <th>Status Akhir</th>
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
                            <table class="table table-striped mb-0" id="tableLogPemindahan">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>#</th>
                                        <th>Status</th>
                                        <th>Pemberi</th>
                                        <th>Penerima Terakhirnya</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                            <table class="table table-striped mb-0" id="tableLogPeminjaman">
                                <thead>
                                    <tr>
                                        <th>Tgl Peminjaman</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Status</th>
                                        <th>Peminjam</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_5" role="tabpanel">
                            <table class="table table-striped mb-0" id="logDataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 150px">Tanggal</th>
                                        <th style="width: 100px">Pembuat</th>
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
    </div>
    @include('pages.admin.listing-asset.components.modal._modal_edit')
    @include('pages.admin.listing-asset.components.modal._modal_create_service')
    @include('pages.admin.listing-asset.components.modal._modal_preview_service')
    @include('pages.admin.listing-asset.components.modal._modal_preview_opname')
    @include('pages.admin.listing-asset.components.modal._modal_create_pemindahan')
@endsection
