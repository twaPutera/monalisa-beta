@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/echarts/echarts.min.js') }}"></script>
@endsection
@section('custom_js')
    <script>
        var table = $('#datatableCriticalAduan');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                // set limit item per page
                pageLength: 3,
                orderable: true,
                paging: false,
                info: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.keluhan.datatable') }}",
                    data: function(d) {
                        d.status_pengaduan = 'dilaporkan';
                        d.limit = 10;
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
                        name: 'tanggal_pengaduan',
                        data: 'tanggal_pengaduan'
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name',
                        orderable: false
                    },
                    {
                        name: 'prioritas',
                        data: 'prioritas',
                        orderable: true
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset',
                        orderable: false
                    },

                ],
                order: [
                    [3, 'asc']
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == 10) {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">High</span>`;
                            } else if (data == 5) {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Medium</span>`;
                            } else if (data == 1) {
                                element +=
                                    `<span class="kt-badge kt-badge--info kt-badge--inline">Low</span>`;
                            } else {
                                element +=
                                    `<span class="kt-badge kt-badge--dark kt-badge--inline">Tidak Ada</span>`;
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
    </script>
    <script>
        $(document).ready(function() {
            getSummaryData();
        });

        const getSummaryData = () => {
            $.ajax({
                url: '{{ route('admin.get-summary-dashboard') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'is_pemutihan': '0',
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#totalAssetSummary').text(response.data.countAsset);
                        $('#lastUpdateAsset').text(formatDateIntoIndonesia(response.data.lastUpdateAsset));
                        $('#nilaiBeliAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_beli_asset));
                        $('#totalDepresiasiAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_depresiasi));
                        $('#totalValueAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_value_asset));
                        $('#keluhan-total').empty();
                        $('#keluhan-ditangani-total').empty();
                        $('#keluhan-belum-total').empty();
                        $('#keluhan-total').append(response.data.dataTotalPengaduan)
                        $('#keluhan-ditangani-total').append(response.data.dataSudahDitangani)
                        $('#keluhan-belum-total').append(response.data.dataBelumDitangani)
                        const dataSummaryAsset = response.data.dataSummaryChartAsset.map((item) => {
                            return {
                                name: item.name,
                                value: Math.ceil((item.value / response.data.countAsset) * 100),
                            }
                        });

                        const dataSummaryKondisi = response.data.dataSummaryChartAssetByKondisi.map((
                            item) => {
                            return {
                                name: item.name,
                                value: Math.ceil((item.value / response.data.countAsset) * 100),
                            }
                        });

                        generateChartAssetSummary(dataSummaryAsset);

                        generateChartAssetKondisi(dataSummaryKondisi);

                        generateChartPenerimaanAsset(response.data.dataSummaryChartAssetByMonthRegis);

                        generateChartService(response.data.dataSummaryServiceByStatus);

                    }
                },
            })
        }

        const formatNumberToMilion = (number) => {
            number = number / 1000000;
            return Math.ceil(number) + ' Jt';
        }

        const generateChartAssetSummary = (data) => {
            echarts.init(document.querySelector("#chartAssetSummary")).setOption({
                title: {
                    show: false,
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    show: false,
                },
                padding: 0,
                series: [{
                    name: 'Kelompok Aset',
                    type: 'pie',
                    radius: ['50%', '60%'],
                    data: data,
                    // color: ['#45C277', '#FA394C', '#FFC102'],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            });
        }

        const generateChartAssetKondisi = (data) => {
            echarts.init(document.querySelector("#chartAssetKondisi")).setOption({
                title: {
                    show: false,
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    show: false,
                },
                padding: 0,
                series: [{
                    name: 'Jumlah Asset',
                    type: 'pie',
                    radius: '60%',
                    data: data,
                    // color: ['#45C277', '#FA394C', '#FFC102'],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            });
        }

        const generateChartPenerimaanAsset = (data) => {
            echarts.init(document.querySelector("#chartPenerimaanAsset")).setOption({
                xAxis: {
                    type: 'category',
                    data: data.name,
                },
                yAxis: {
                    type: 'value'
                },
                legend: {
                    show: false,
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                series: [{
                    name: 'Penerimaan Aset',
                    data: data.value,
                    type: 'bar',
                    color: '#339AF0',
                }]
            });
        }

        const generateChartService = (data) => {
            echarts.init(document.querySelector("#chartSummaryService")).setOption({
                xAxis: {
                    type: 'value',
                },
                yAxis: {
                    type: 'category',
                    data: data.name,
                },
                grid: {
                    left: '10%',
                    containLabel: true
                },
                legend: {
                    show: false,
                },
                label: {
                    show: true,
                    position: 'inside',
                    fontWeight: 'bold',
                    color: '#fff',
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                series: [{
                    name: 'Services Aset',
                    data: data.data,
                    type: 'bar',
                }]
            });
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-12">
            <h5 class="text-primary mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.933" height="20.933" viewBox="0 0 20.933 20.933">
                    <path id="Icon_material-pie-chart-outlined" data-name="Icon material-pie-chart-outlined"
                        d="M13.466,3A10.466,10.466,0,1,0,23.933,13.466,10.5,10.5,0,0,0,13.466,3Zm1.047,2.167a8.386,8.386,0,0,1,7.253,7.253H14.513Zm-9.42,8.3a8.394,8.394,0,0,1,7.327-8.3v16.61A8.413,8.413,0,0,1,5.093,13.466Zm9.42,8.3V14.513h7.253A8.375,8.375,0,0,1,14.513,21.766Z"
                        transform="translate(-3 -3)" fill="#0067d4" />
                </svg>
                <strong>Data Summary</strong>
            </h5>
        </div>
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Asset Value
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body" style="height: 280px;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="mr-3 kt-badge kt-badge--unified-brand kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-money-bill-wave"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Nilai Beli Asset</p>
                                </div>
                                <h2 class="text-dark mb-0"><strong id="nilaiBeliAsset">0 Jt</strong></h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-dollar-sign"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Total Depresiasi</p>
                                </div>
                                <h2 class="text-dark mb-0"><strong id="totalDepresiasiAsset">0 Jt</strong></h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span style="background-color: #C3D8EC; border-color: #C3D8EC;"
                                        class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-dollar-sign text-light"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Value Asset</p>
                                </div>
                                <h2 class="text-dark mb-0"><strong id="totalValueAsset">0 Jt</strong></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Asset Summary
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body px-0">
                            <div id="chartAssetSummary" style="height: 260px; margin-top: -30px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Asset Data
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div>
                        <h6>Total Asset Data</h6>
                        <h1 class="text-dark text-right"><strong id="totalAssetSummary">0</strong></h1>
                    </div>
                    <div>
                        <h6>Last Change</h6>
                        <p class="text-primary text-right"><strong id="lastUpdateAsset">-</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Kondisi Asset
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body px-0">
                    <div id="chartAssetKondisi" style="height: 180px; margin-top: -30px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Pengaduan Status Tertinggi
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body ">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatableCriticalAduan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Aduan</th>
                                    <th>Nama Pembuat</th>
                                    <th>Level Aduan</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Upcoming Services
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Services</th>
                                    <th>Nama Aset</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Penerimaan Asset
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body p-0">
                    <div id="chartPenerimaanAsset" style="margin-top: -30px; height: 310px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Pemeliharaan
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-4">
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Keluhan User</strong></h6>
                            </div>
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-ditangani-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Ditangani</strong></h6>
                            </div>
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-belum-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Belum Ditangani</strong></h6>
                            </div>
                        </div>
                        <div class="col-8 p-0 m-0">
                            <div style="height: 200px;">
                                <div id="chartSummaryService" style="height: 280px; margin-top: -60px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
