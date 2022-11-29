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

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
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
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    if (data.form == 'import') {
                        let modal = $('#modalImport');
                        let form = modal.find('form');
                        $('.error-import-container').empty();
                        $('.error-import-asset').hide();
                        form[0].reset();
                        modal.modal('hide');
                    }
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
                if (formElement.attr('id') == 'formImportAsset') {
                    $('.error-import-container').empty();
                    $(errors).each(function(index, value) {
                        let message =
                            `<li class="text-danger"><strong>Baris ${value.row} dalam kolom ${value.attribute} : </strong>${value.errors[0]}</li>`;
                        $('.error-import-container').append(message);
                    });
                    $('.error-import-asset').show();
                    // reset form
                    formElement[0].reset();
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
                    data: function(d) {
                        d.searchKeyword = $('#searchAsset').val();
                        d.is_draft = '1';
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
                        data: "id",
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
                            return type == '1' ? 'Inventaris' : 'Aset';
                        }
                    },
                    {
                        data: 'is_it',
                        render: function(type) {
                            return type == '1' ? 'Barang IT' : 'Barang Aset';
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
                    }
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            let url_detail = "{{ route('admin.listing-asset.show', ':id') }}";
                            let url_update = "{{ route('admin.listing-asset.update', ':id') }}";
                            let url_delete = "{{ route('admin.listing-asset.destroy', ':id') }}";
                            url_detail = url_detail.replace(':id', data);
                            url_update = url_update.replace(':id', data);
                            url_delete = url_delete.replace(':id', data);
                            let element = '';
                            element += `<form action="${url_delete}" method="POST">`;
                            element += `{{ csrf_field() }}`;
                            element += `
                                <button type="button" onclick="edit(this)" data-url_detail="${url_detail}" data-url_update="${url_update}" class="btn btn-sm btn-icon btn-warning"><i class="fa fa-edit"></i></button>
                            `;
                            element += `
                                <button type="button" onclick="deleteAsset(this)" data-url_delete="" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                            `;
                            element += `</form>`;
                            return element;
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        targets: 8,
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
                            } else if (data == 'pengembangan') {
                                element =
                                    `<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Pengembangan</span>`;
                            } else if (data == 'draft') {
                                element =
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Draft</span>`;
                            } else {
                                element =
                                    `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                            }

                            return element;
                        }
                    },
                    {
                        targets: 10,
                        render: function(data, type, full, meta) {
                            return formatNumber(data);
                        }
                    }
                ],
                createdRow: function(row, data, index) {
                    // $(row).attr('data-id', data.id).addClass('row-asset').attr("style",
                    //     "cursor: pointer;");
                    // $(row).on('click', function() {
                    //     let id = $(this).data('id');
                    //     showAsset(data.action);
                    // });
                },
                footerCallback: function(row, data, start, end, display) {
                    let totalRecord = data.length;
                    let target = $('#totalFilterAktif');
                    target.empty();
                    target.append("Total " + totalRecord);
                }
            });

            $('.datepickerCreate').datepicker({
                todayHighlight: true,
                width: '100%',
                format: 'yyyy-mm-dd',
                autoclose: true,
            });


            $("#searchAsset").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableAsset();
            });
        });

        const filterTableAsset = () => {
            table.DataTable().ajax.reload();
        }

        const edit = (button) => {
            const url_detail = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        const data = response.data;
                        const modal = $('.modalEditDraftAsset');
                        const form = modal.find('form');
                        form.attr('action', url_update);
                        form.find('input[name="id"]').val(data.asset.id);
                        form.find('input[name="deskripsi"]').val(data.asset.deskripsi);
                        form.find('input[name="tanggal_perolehan"]').val(data.asset.tanggal_perolehan);
                        form.find('input[name="kode_asset"]').val(data.asset.kode_asset);
                        form.find('input[name="nilai_perolehan"]').val(data.asset.nilai_perolehan);
                        form.find('input[name="nilai_buku_asset"]').val(data.asset.nilai_buku_asset);
                        form.find('input[name="no_seri"]').val(data.asset.no_seri);
                        form.find('input[name="no_inventaris"]').val(data.asset.no_inventaris);
                        form.find('select[name="id_group_asset"]').append(`<option value="${data.asset.kategori_asset.group_kategori_asset.id}" selected>${data.asset.kategori_asset.group_kategori_asset.nama_group}</option>`);
                        form.find('select[name="id_kategori_asset"]').append(`<option value="${data.asset.kategori_asset.id}" selected>${data.asset.kategori_asset.nama_kategori}</option>`);
                        form.find('select[name="id_lokasi"]').append(`<option value="${data.asset.lokasi.id}" selected>${data.asset.lokasi.nama_lokasi}</option>`);
                        form.find('select[name="id_satuan_asset"]').append(`<option value="${data.asset.satuan_asset.id}" selected>${data.asset.satuan_asset.nama_satuan}</option>`);
                        form.find('select[name="id_vendor"]').append(`<option value="${data.asset.vendor.id}" selected>${data.asset.vendor.nama_vendor}</option>`);
                        form.find(`select[name="jenis_penerimaan"] option[value="${data.asset.jenis_penerimaan}"]`).attr('selected', true);
                        form.find(`select[name="ownership"]`).append(`<option value="${data.asset.ownership}" selected>${data.asset.owner_name}</option>`);
                        form.find('select[name="id_kelas_asset"]').append(`<option value="${data.asset.kelas_asset.id}" selected>${data.asset.kelas_asset.nama_kelas}</option>`);
                        form.find(`select[name="status_kondisi"] option[value="${data.asset.status_kondisi}"]`).prop('selected', true);
                        form.find(`select[name="status_akunting"] option[value="${data.asset.status_akunting}"]`).prop('selected', true);
                        form.find('input[name="no_po"]').val(data.asset.no_po);
                        form.find('input[name="no_sp3"]').val(data.asset.no_sp3);
                        form.find('textarea[name="spesifikasi"]').val(data.asset.spesifikasi);

                        if (data.asset.is_sparepart == '1') {
                            form.find('input[name="is_sparepart"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_sparepart"]').prop('checked', false);
                        }

                        if (data.asset.is_pinjam == '1') {
                            form.find('input[name="is_pinjam"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_pinjam"]').prop('checked', false);
                        }

                        if (data.asset.is_it == '1') {
                            form.find('input[name="is_it"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_it"]').prop('checked', false);
                        }

                        if (data.asset.id_surat_memo_andin) {
                            form.find('select[name="id_surat_memo_andin"]').append(`<option value="${data.asset.id_surat_memo_andin}" selected>${data.asset.no_memo_surat}</option>`);
                        }

                        modal.on('shown.bs.modal', function() {
                            // setTimeout(() => {
                            //     generateGroupSelect2Edit();
                            //     generateSelect2LokasiEdit();
                            //     generateKelasAssetEdit();
                            //     generateSatuanAssetEdit();
                                // generateVendorAssetEdit();
                            //     generateOwnerAssetEdit();
                            //     generateMemorandumAndinSelect2();
                            // }, 2000);
                        }).modal('show');
                    }
                },
            })
        }

        const deleteAsset = (button) => {
            const form = $(button).closest('form');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.value) {
                    let formData = new FormData(form[0]);
                    let url = form.attr("action");
                    let method = form.attr("method");
                    let enctype = form.attr("enctype");
                    $.ajax({
                        url: url,
                        type: method,
                        enctype: enctype,
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $(".backdrop").show();
                        },
                        success: function (response) {
                            $(".backdrop").hide();
                            if (response.success) {
                                $("body").trigger("_EventAjaxSuccess", [
                                    form,
                                    response,
                                ]);
                            } else {
                                console.log(response);
                                // showToaster(response.error, "Error");
                            }
                        },
                        error: function (response) {
                            $(".backdrop").hide();
                        },
                    });
                }
            });
        }

        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });

        $('#fileImport').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-excel-text').text(file.name);
        });
    </script>

    @include('pages.admin.listing-asset.components.script-js._script_modal_create')
    @include('pages.admin.listing-asset.components.script-js._script_modal_edit')
    @include('pages.admin.listing-asset.components.script-js._script_modal_filter')
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchAsset" class="form-control form-control-sm"
                            placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButtonAsset"
                                type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button onclick="openModalByClass('modalFilterAsset')" class="btn btn-sm btn-secondary shadow-custom"
                        type="button"><i class="fas fa-sliders-h mr-2"></i>
                        Filter</button>
                </div>
                <div class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalImportAsset')" class="btn btn-success shadow-custom btn-sm mr-2"
                        type="button"><i class="fa fa-file"></i> Import Data</button>
                    <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"
                        type="button"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><strong class="text-primary">Data Asset</strong> <span class="text-gray"> -
                                Lokasi Asset (<span id="lokasiFilterAktif">Universitas Pertamina</span>)</span></h5>
                        <h5 class="text-primary"><strong id="totalFilterAktif">Total 0</strong></h5>
                    </div>
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped table-hover" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">Aksi</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="200px">Tipe</th>
                                    <th width="200px">Barang IT</th>
                                    <th width="150px">Asset Group</th>
                                    <th width="150px">Jenis Asset</th>
                                    <th width="180px">Status Kondisi</th>
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
            </div>
        </div>
    </div>
    @include('pages.admin.listing-asset.components.modal._modal_create')
    @include('pages.admin.listing-asset.components.modal._modal_edit_draft')
    @include('pages.admin.listing-asset.components.modal._modal_import')
    @include('pages.admin.listing-asset.components.modal._modal_filter')
@endsection
