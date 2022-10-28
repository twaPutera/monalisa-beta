@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>
        div.dataTables_wrapper {
            width: 300% !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
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
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                ordering: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable.report') }}",
                    data: function(d) {
                        d.id_lokasi = $('#lokasiFilter').val();
                        d.id_kategori_asset = $('#kategoriAssetFilter').val();
                        d.searchKeyword = $('#searchAsset').val();
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
                        data: 'kode_asset'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'is_inventaris',
                        render: function(type) {
                            return type == 1 ? 'Inventaris' : 'Aset';
                        }
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'nama_kategori'
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
                        data: 'nama_lokasi'
                    },
                    {
                        data: 'owner_name'
                    },
                    {
                        data: 'register_oleh'
                    },
                    {
                        data: 'nama_satuan'
                    },
                    {
                        data: 'nama_vendor'
                    },
                    {
                        name: 'tanggal_opname',
                        data: 'tanggal_opname'
                    },
                    {
                        name: 'catatan_opname',
                        data: 'catatan_opname'
                    },
                    {
                        name: 'user_opname',
                        data: 'user_opname'
                    },
                    {
                        name: 'tanggal_peminjaman',
                        data: 'tanggal_peminjaman'
                    },
                    {
                        name: 'tanggal_pengembalian',
                        data: 'tanggal_pengembalian'
                    },
                    {
                        name: 'status_peminjaman',
                        data: 'status_peminjaman'
                    },
                    {
                        name: 'user_peminjaman',
                        data: 'user_peminjaman'
                    },
                    {
                        name: 'tanggal_pemindahan',
                        data: 'tanggal_pemindahan'
                    },
                    {
                        name: 'user_penyerah',
                        data: 'user_penyerah'
                    },
                    {
                        name: 'user_penerima',
                        data: 'user_penerima'
                    }
                ],
                columnDefs: [{
                        targets: [8, 15, 18, 19, 22],
                        render: function(data, type, full, meta) {
                            if (data != '-') {
                                return formatDateIntoIndonesia(data);
                            }
                            return data;
                        }
                    },
                    {
                        targets: 20,
                        render: function(data, type, full, meta) {
                            let element = '-';
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
                                    '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">Sedang Dipinjam</span>';
                            } else if (data == 'terlambat') {
                                element =
                                    '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Terlambat</span>';
                            } else if (data == 'diproses') {
                                element =
                                    '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Diproses</span>';
                            } else if (data == 'selesai') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Selesai</span>';
                            }
                            return element;
                        },
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            let element = '';
                            if (data == 'rusak') {
                                element =
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Rusak</span>`;
                            } else if (data == 'maintenance') {
                                element =
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Maintenance</span>`;
                            } else if (data == 'tidak-lengkap') {
                                element =
                                    `<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Lengkap</span>`;
                            } else {
                                element =
                                    `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                            }

                            return element;
                        }
                    },
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
            $('#lokasiFilter').select2({
                width: '150px',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })
            $('#kategoriAssetFilter').select2({
                width: '150px',
                placeholder: 'Pilih Jenis Asset',
                allowClear: true,
            })
            generateLocationServiceSelect();
            generateKategoriAssetSelect();
        });
        const filterTableService = () => {
            table.DataTable().ajax.reload();
        }

        const generateLocationServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectLocationService');
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

        const generateKategoriAssetSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-asset.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectKategoriAsset');
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
                            Summary Asset
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <div class="d-flex align-items-center mt-2 mb-2">
                                    <div class="mr-2">
                                        <select name="" onchange="filterTableService()"
                                            class="filterLokasi selectLocationService form-control mr-2"
                                            style="width: 150px;" id="lokasiFilter">

                                        </select>
                                    </div>
                                    <select name="" onchange="filterTableService()"
                                        class="filterLokasi selectKategoriAsset form-control mr-2" style="width: 150px;"
                                        id="kategoriAssetFilter">

                                    </select>
                                    <button class="btn btn-success shadow-custom btn-sm" type="button"><i
                                            class="fas fa-print"></i>
                                        Export Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="input-group mr-3" style="width: 250px;">
                                <input type="text" id="searchAsset" class="form-control form-control-sm"
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
                                    <th width="50px">#</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="200px">Tipe</th>
                                    <th width="150px">Asset Group</th>
                                    <th width="150px">Kategori</th>
                                    <th width="180px">Status Kondisi</th>
                                    <th width="100px">Tgl. Perolehan</th>
                                    <th width="150px">Nilai Perolehan</th>
                                    <th width="150px">Lokasi</th>
                                    <th width="150px">Ownership</th>
                                    <th width="150px">Register Oleh</th>
                                    <th width="150px">Satuan</th>
                                    <th width="150px">Vendor</th>
                                    <th width="150px">Tgl. Opname Terakhir</th>
                                    <th width="150px">Catatan Opname Terakhir</th>
                                    <th width="150px">User Opname Terakhir</th>
                                    <th width="150px">Tgl. Peminjaman Terakhir</th>
                                    <th width="150px">Tgl. Pengembalian Peminjaman Terakhir</th>
                                    <th width="150px">Status Peminjaman Terakhir</th>
                                    <th width="150px">User Peminjaman Terakhir</th>
                                    <th width="150px">Tgl. Pemindahan Asset Terakhir</th>
                                    <th width="150px">User Penyerah Asset</th>
                                    <th width="150px">User Penerima Asset</th>
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
    @include('pages.admin.report.service.components.modal._modal_detail_service')
@endsection
