@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
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
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
@endsection
@section('custom_js')
    <script>
        var table = $('#logPermintaan');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                // ordering: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.permintaan-inventaris.datatable.log') }}",
                    data: function(d) {
                        d.request_inventori_id = "{{ $permintaan->id }}";
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
                        data: "created_at",
                        name: 'created_at'
                    },
                    {
                        name: 'created_by',
                        data: 'created_by',
                        orderable: false,
                    },
                    {
                        name: 'status',
                        data: 'status',
                        orderable: false,
                    },
                    {
                        name: 'message',
                        data: 'message',
                        orderable: false,
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "pending") {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>`;
                            } else if (data == "diproses") {
                                element +=
                                    `<span class="kt-badge kt-badge--primary kt-badge--inline">Diproses</span>`;

                            } else if (data == "ditolak") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Ditolak</span>`;
                            } else if (data == "selesai") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                            }
                            return element;
                        },
                    }
                    //Custom te
                ],
            });
        });
    </script>
@endsection
@section('main-content')
    <form action="" class="form-confirm d-inline" method="POST">
        @csrf
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Detail Peminjaman
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                @if ($permintaan->status == 'diproses')
                                    <input type="hidden" name="status" value="dipinjam">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check mr-2"></i>
                                        Proses</button>
                                @elseif($permintaan->status == 'pending')
                                    <div class="badge badge-warning"><i class="fas fa-clock-o mr-2"></i> Pending</div>
                                @elseif($permintaan->status == 'ditolak')
                                    <div class="badge badge-danger"><i class="fas fa-times mr-2"></i> Ditolak</div>
                                @elseif($permintaan->status == 'selesai')
                                    <div class="badge badge-success"><i class="fas fa-check mr-2"></i> Selesai</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Deskripsi Permintaan
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group">
                                        <label for="nama">Kode Permintaan</label>
                                        <input type="text" class="form-control" id="codePeminjam" readonly name="nama"
                                            placeholder="Kode" value="{{ $permintaan->kode_request }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Pengaju</label>
                                        <input type="text" class="form-control" id="namaPeminjam" readonly name="nama"
                                            placeholder="Nama" value="{{ $permintaan->pengaju }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">No Memo</label>
                                        <input type="text" class="form-control" id="namaPeminjam" readonly name="nama"
                                            placeholder="Nama" value="{{ $permintaan->no_memo }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Unit Kerja</label>
                                        <input type="text" class="form-control" id="namaPeminjam" readonly name="nama"
                                            placeholder="Nama" value="{{ $permintaan->unit_kerja }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Permintaan</label>
                                        <div class="d-flex">
                                            <input type="date" class="form-control" id="tanggalPeminjam" readonly
                                                name="" value="{{ $permintaan->tanggal_permintaan }}">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Pengambilan</label>
                                        <div class="d-flex">
                                            <input type="date" class="form-control" id="tanggalPengembalian" readonly
                                                name="" value="{{ $permintaan->tanggal_pengambilan }}">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alasan Permintaan</label>
                                        <textarea name="" class="form-control" readonly id="alasanPeminjaman" cols="30" rows="10">{{ $permintaan->alasan }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Realisasi Permintaan
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                            <div class="kt-portlet__head-actions">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="table-responsive">
                                        <table class="table dt_table table-striped table-bordered"
                                            id="tableDetailPeminjaman">
                                            <thead>
                                                <tr>
                                                    <th width="50px">No</th>
                                                    <th>Kode Inventori</th>
                                                    <th>Nama Inventori</th>
                                                    <th>Nama Kategori</th>
                                                    <th>Jumlah Permintaan</th>
                                                    <th>Realisasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permintaan->detail_request_inventori as $index => $item)
                                                    <tr>
                                                        <td>{{ $index += 1 }}</td>
                                                        <td>{{ $item->inventori->kode_inventori }}</td>
                                                        <td>{{ $item->inventori->nama_inventori }}</td>
                                                        <td>{{ $item->inventori->kategori_inventori->nama_kategori }}</td>
                                                        <td>{{ $item->qty }}</td>
                                                        <td><input type="number" class="form-control" name="">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Log Permintaan
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                            <div class="kt-portlet__head-actions">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="table-responsive">
                                        <table class="table table-striped dt_table" id="logPermintaan">
                                            <thead>
                                                <tr>
                                                    <th width="50px">No</th>
                                                    <th>Tanggal</th>
                                                    <th>Pembuat</th>
                                                    <th>Status</th>
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
            </div>
        </div>
    </form>
@endsection
