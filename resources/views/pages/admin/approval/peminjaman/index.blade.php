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
                searching: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.approval.datatable') }}",
                    data: function(d) {
                        // d.is_approve = null;
                        d.approvable_type = 'App\\Models\\PeminjamanAsset';
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
                        data: "link_detail",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'approvable.tanggal_peminjaman'
                    },
                    {
                        data: 'pembuat_approval'
                    },
                    {
                        data: 'pembuat_approval'
                    },
                    {
                        data: 'approvable.tanggal_pengembalian'
                    },
                    {
                        data: 'is_approve'
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return `
                                <button onclick="showDetail(this)" data-url_detail="` + data + `" data-url_update="` +
                                full.link_update + `" type="button" class="btn btn-sm btn-primary btn-icon" title="Detail">
                                    <i class="la la-eye"></i>
                                </button>
                            `;
                        },
                    },
                    {
                        targets: [2, 5],
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    },
                    {
                        targets: [4],
                        render: function(data, type, full, meta) {
                            return sumDiffFromTwoDate(new Date(full.approvable
                                .tanggal_pengembalian), new Date(full.approvable
                                .tanggal_peminjaman)) + " Hari";
                        },
                    },
                    {
                        targets: [6],
                        render: function(data, type, full, meta) {
                            let element =
                                '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Pending</span>';
                            if (data == '1') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Disetujui</span>';
                            } else if (data == '2') {
                                element =
                                    '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Ditolak</span>';
                            }
                            return element;
                        },
                    }
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
                    if (data.data.approval.is_approve == 1) {
                        setTimeout(() => {
                            window.location.href =
                                '{{ route('admin.peminjaman.detail', '') }}' + '/' + data.data.id;
                        }, 2000);
                    }
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
        });

        const showDetail = (button) => {
            const url = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        let user_peminjam = JSON.parse(data.json_peminjam_asset);
                        let modal = $('#modalDetailPeminjaman');
                        let form = modal.find('form');
                        $('#tanggalApproval').hide();
                        form.attr('action', url_update);
                        $('.isDisabled').attr('disabled', false);
                        if (data.approval.is_approve == 1) {
                            console.log(data.approval.is_approve);
                            $('.isDisabled').attr('disabled', true);
                            $('#tanggalApproval').val(data.approval.tanggal_approval).show();
                            const status_approval = data.approval.is_approve == '1' ? 'disetujui' :
                                'ditolak';
                            $('#statusApproval option[value=' + status_approval + ']').attr('selected',
                                true);
                            $('#keteranganApproval').val(data.approval.keterangan);
                        }
                        $('#namaPeminjam').val(user_peminjam.name);
                        $('#tanggalPeminjam').val(data.tanggal_peminjaman);
                        $('#tanggalPengembalian').val(data.tanggal_pengembalian);
                        $('#alasanPeminjaman').val(data.alasan_peminjaman);
                        $('#tableBodyDetailPeminjaman').html('');
                        $(data.request_peminjaman_asset).each(function(index, value) {
                            let element = `
                                <tr>
                                    <td>` + (index + 1) + `</td>
                                    <td>` + value.kategori_asset.nama_kategori + `</td>
                                    <td>` + value.jumlah + `</td>
                                </tr>
                            `;
                            $('#tableBodyDetailPeminjaman').append(element);
                        });

                        $('#modalDetailPeminjaman').modal('show');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4" style="box-shadow: unset !important;">
                    <div class="kt-portlet__head-label">
                        <h4>Approval Task (<strong style="text-primary">{{ $total_approval }} Task</strong>)</h4>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div>
                        @include('pages.admin.approval.tab-header')
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Tanggal</th>
                                    <th>Nama Peminjam</th>
                                    <th>Durasi Peminjaman</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
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
    @include('pages.admin.approval.peminjaman._modal_detail')
@endsection
