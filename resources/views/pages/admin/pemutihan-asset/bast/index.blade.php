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
        var table = $('#datatableExample');
        var table2 = $('#addAssetData');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.pemutihan-asset.datatable') }}",
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
                        name: 'tanggal',
                        data: 'tanggal'
                    },
                    {
                        name: 'nama_pemutihan',
                        data: 'nama_pemutihan'
                    },
                    {
                        name: 'no_memo',
                        data: 'no_memo'
                    },
                    {
                        name: 'keterangan',
                        data: 'keterangan'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'created_by',
                        data: 'created_by'
                    },
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "Draft") {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Draft</span>`;
                            } else if (data == "Publish") {
                                element +=
                                    `<span class="kt-badge kt-badge--info kt-badge--inline">Publish</span>`;
                            } else if (data == "Diproses") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Disetujui</span>`;
                            } else if (data == "Ditolak") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Ditolak</span>`;
                            }
                            return element;
                        },
                    }
                ],
            });

            table2.DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                orderings: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable') }}",
                    data: function(d) {
                        d.is_pemutihan = 0;
                        d.jenis = $('.jenispicker').val();
                        d.status_kondisi = $('.kondisipicker').val();
                    }
                },
                columns: [{
                        name: 'checkbox',
                        data: 'checkbox',
                        class: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        name: 'kode_asset',
                        data: 'kode_asset'
                    },
                    {
                        name: 'deskripsi',
                        data: 'deskripsi'
                    },
                    {
                        name: 'nama_kategori',
                        data: 'nama_kategori'
                    },
                    {
                        name: 'type',
                        data: 'type',
                        render: function(type) {
                            return type === null ? 'Tidak Ada' : type;
                        }
                    },
                    {
                        name: 'nama_lokasi',
                        data: 'nama_lokasi'
                    },
                    {
                        name: 'status_kondisi',
                        data: 'status_kondisi'
                    },

                ],
                columnDefs: [{
                        targets: [0],
                        orderable: false,
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
                    if (data.redirect && data.redirect != null) {
                        setTimeout(function() {
                            var redirect = "{{ route('admin.pemutihan-asset.store.detail', '') }}" +
                                "/" +
                                data.data.id;
                            location.assign(redirect);
                        }, 1000);
                    }
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
                    if (key == "file_asset_service") {
                        $('#preview-file-image-error').html(errors[key][0]);
                        $('#preview-file-image-error-update').html(errors[key][0]);
                    }
                    if (key == "id_checkbox") {
                        console.log($('#alert-list-asset'));
                        $('#alert-list-asset').removeClass('d-none');
                    }
                }
            });
        });

        const filterTableService = () => {
            table2.DataTable().ajax.reload();
        }

        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('.check-all').on('change', function() {
            if ($(this).is(':checked')) {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', true);
                });
            } else {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', false);
                });
            }
        });


        $('.check-item').each(function(i, e) {
            $(e).prop('checked', false);
        });
        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalDetailInventarisData');
                    const form = modal.find('form');
                    form.find('input[name=tanggal]').val(response.data.tanggal);
                    form.find('input[name=no_memo]').val(response.data.no_memo);
                    form.find('input[name=status_pemutihan]').val(response.data.status);
                    form.find('textarea[name=keterangan_pemutihan]').val(response.data.keterangan);
                    form.find('.btn-download').attr('href',
                        "{{ route('admin.pemutihan-asset.store.detail.download') . '?filename=' . '' }}" +
                        response.data.file_bast)
                    var table4 = $('.detailAssetData');
                    modal.on('shown.bs.modal', function(e) {
                        table4.DataTable({
                            responsive: true,
                            processing: true,
                            serverSide: true,
                            destroy: true,
                            ajax: {
                                url: "{{ route('admin.pemutihan-asset.datatable.detail') }}",
                                data: function(d) {
                                    d.id_pemutihan_detail = response.data.id
                                }
                            },
                            columns: [{
                                    name: 'file_gambar',
                                    data: 'file_gambar'
                                },
                                {
                                    name: 'deskripsi',
                                    data: 'deskripsi'
                                },
                                {
                                    name: 'kode_asset',
                                    data: 'kode_asset'
                                },
                                {
                                    name: 'jenis_asset',
                                    data: 'jenis_asset'
                                },
                                {
                                    name: 'type',
                                    data: 'type',
                                    render: function(type) {
                                        return type === null ? 'Tidak Ada' : type;
                                    }
                                },
                                {
                                    name: 'lokasi_asset',
                                    data: 'lokasi_asset'
                                },
                                {
                                    name: 'kondisi_asset',
                                    data: 'kondisi_asset'
                                },
                                {
                                    name: 'keterangan_pemutihan',
                                    data: 'keterangan_pemutihan'
                                },

                            ],
                            columnDefs: [
                                //Custom template data
                            ],
                        });
                    })
                    modal.on('hidden.bs.modal', function() {
                        table4.dataTable().fnDestroy();
                    })
                    modal.modal('show');
                }
            })
        }

        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });

        const showPemutihanAsset = (button) => {
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

        const generateKategoriSelect2Create = (idElement) => {
            $('#' + idElement).select2({
                width: '100%',
                placeholder: 'Pilih Jenis',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
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

        $('.modalCreateInventarisData').on('shown.bs.modal', function() {
            setTimeout(() => {
                generateKategoriSelect2Create('groupAssetCreate');
            }, 2000);
        });
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.pemutihan-asset.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Daftar Berita Acara Pemutihan <span class="text-primary"><b>({{ $total_asset }} Berita
                                    Acara)</b></span>
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
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Tanggal Pemutihan</th>
                                    <th>Nama Pemutihan</th>
                                    <th>No Berita Acara</th>
                                    <th>Keterangan Pemutihan</th>
                                    <th>Status Pemutihan</th>
                                    <th>Diajukan Oleh</th>
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
    @include('pages.admin.pemutihan-asset.components.modal._modal_create')
    @include('pages.admin.pemutihan-asset.components.modal._modal_edit')
    @include('pages.admin.pemutihan-asset.components.modal._modal_detail')
    @include('pages.admin.pemutihan-asset.components.modal._modal_preview')
@endsection
