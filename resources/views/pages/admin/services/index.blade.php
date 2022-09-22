@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>

    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.0/chart.js" integrity="sha512-ohOeYvGoLlCxYkfMoPBKJh/wp4Oe76rEJDWOmQq1LLrJD6yCBSPVmhhXuZYvuxdYR3PiozsUf+TZZ6yhVBGYAQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('custom_js')
    <script>
        var table2 = $('#datatableLogService');
        $(document).ready(function() {
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
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
            });

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
                        //
                    }
                },
                columns: [
                    {
                        data: "checkbox",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'checkbox'
                    },
                    {
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
                        name: 'tanggal_mulai',
                        data: 'tanggal_mulai'
                    },
                    {
                        name: 'asset_data.deskripsi',
                        data: 'asset_data.deskripsi'
                    },
                    {
                        data: 'asset_data.nama_group'
                    },
                    {
                        data: 'asset_data.nama_kategori'
                    },
                    {
                        name: 'status_service',
                        data: 'status_service'
                    },
                    {
                        name: 'tanggal_selesai',
                        data: 'tanggal_selesai'
                    },
                ],
                columnDefs: [
                    {
                        targets: [3, 8],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "on progress") {
                                element += `<span class="kt-badge kt-badge--primary kt-badge--inline">Progress</span>`;
                            } else if (data == "backlog") {
                                element += `<span class="kt-badge kt-badge--danger kt-badge--inline">Backlog</span>`;
                            } else if (data == "done") {
                                element += `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                            }
                            return element;
                        },
                    }
                    //Custom template data
                ],
            });

            getDataOptionSelect2Lokasi();

            $('.filterLokasi').select2({
                width: '150px',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })

            generateMonthPicker();
            generateYearPicker();
        });

        const generateMonthPicker = () => {
            $('.monthpicker').datepicker({
                format: "mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true,
            })
        }

        const generateYearPicker = () => {
            $('.yearpicker').datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                autoclose: true,
            });
        }

        const getDataOptionSelect2Lokasi = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const select = $('.select2Lokasi');
                    select.empty();
                    response.data.forEach(element => {
                        let selected = '';
                        if (element.id == $('#lokasiParentId').val()) {
                            selected = 'selected';
                        }
                        select.append(
                            `<option ${selected} value="${element.id}">${element.text}</option>`
                        );
                    });
                }
            })
        }
    </script>
    <script>
        const ctxChartServices = document.getElementById('chartServices');
        const chartServices = new Chart(ctxChartServices, {
            type: 'pie',
            data: {
                labels: ['ON PROGRESS', 'BACKLOG', 'SELESAI'],
                datasets: [{
                    label: '# of Votes',
                    data: [50, 20, 30],
                    backgroundColor: [
                        '#0067D4',
                        '#F03E3E',
                        '#71C160',
                    ],
                    borderWidth: 1
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 40,
                        right: 40,
                        top: 40,
                        bottom: 40
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'rgba(118, 118, 118, 1)',
                        font: {
                            size: '10',
                        },
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex] + `\n (${value}%)`;
                        }
                    }
                }
            }
        });
    </script>
@endsection
@section('main-content')
    <input type="hidden" value="" id="lokasiParentId">
    <div class="row">
        <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Service
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body p-0">
                    <canvas id="chartServices" height="200px"></canvas>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Asset sedang diservice</h6>
                    <h6 class="text-primary mb-0"><strong>12</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Lokasi terbanyak</h6>
                    <h6 class="text-primary mb-0"><strong>Rektorat (22)</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Sedang on Progress</h6>
                    <h6 class="text-primary mb-0"><strong>10</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Selesai di kerjakan</h6>
                    <h6 class="text-primary mb-0"><strong>1</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Mengalami Backlog</h6>
                    <h6 class="text-primary mb-0"><strong>1</strong></h6>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchAsset" class="form-control form-control-sm"
                            placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButton"
                                type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <select name="" class="filterLokasi select2Lokasi form-control mr-2" style="width: 150px;" id="">

                    </select>
                    <input type="text" readonly class="form-control yearpicker mx-2" style="width: 150px;" placeholder="Tahun">
                    <input type="text" readonly class="form-control monthpicker mr-2" style="width: 150px;" placeholder="Bulan">
                    <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"     type="button"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><strong>Data Service</strong></h5>
                <div class="kt-radio-inline">
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" checked="checked" value="all" name="radio4"> Semua Services
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" name="radio4" value="on progress"> On Progress
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" name="radio4" value="backlog"> Backlog
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" name="radio4" value="selesai"> Selesai
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No</th>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Asset</th>
                                    <th>Kelompok</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tgl. Selesai</th>
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
@endsection
