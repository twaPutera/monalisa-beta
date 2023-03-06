<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print All Qr</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link href="{{ asset('assets/css/style.bundle.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body style="background: white; padding: 20px;">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <nav aria-label="Page navigation example">
                    @if($assets->lastPage() > 1)
                        <ul class="pagination">
                            <li class="page-item" <?php if($assets->currentPage() == 1): ?> style="display: none;" <?php endif; ?>><a class="page-link" href="{{$assets->url($assets->currentPage()-1)}}">Previous</a></li>
                            @for($i=1; $i<=$assets->lastPage(); $i++)
                                <li class="page-item"><a class="page-link <?php if($assets->currentPage() == $i):?>active <?php endif; ?>" href="{{$assets->url($i)}}">{{ $i }}</a></li>
                            @endfor
                            <li class="page-item" <?php if($assets->currentPage() == $assets->lastPage()): ?> style="display: none;" <?php endif; ?>><a class="page-link" href="{{$assets->url($assets->currentPage()+1)}}">Next</a></li>
                        </ul>
                    @endif
                </nav>
                <form action="" method="GET" class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalFilterAsset')" class="btn btn-info mr-2 shadow-custom" type="button">Filter</button>
                    <button type="button" onclick="printQr()" class="btn btn-success">Print QR</button>
                </form>
            </div>
        </div>
        @foreach ($assets as $item)
            <div class="col-md-3 border border-dark p-2">
                <img src="{{ route('admin.listing-asset.preview-qr') . '?filename=' . $item->qr_code }}" class="my-3" width="100%" alt="">
                <div class="mt-3 text-center">
                    <h5>{{ $item->kode_asset }}</h5>
                    <h5>{{ $item->deskripsi }}</h5>
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Filter Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="la la-remove"></span>
                    </button>
                </div>
                <form action="">
                    <div class="modal-body row">
                        <div class="form-group col-md-6 col-12">
                            <label for="">Limit</label>
                            <input type="number" name="limit" placeholder="Limit" value="{{ isset(request()->limit) ? request()->limit : 50 }}" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Nama Asset / Kode Asset</label>
                            <input type="text" id="searchAsset" name="deskripsi" value="{{ isset(request()->deskripsi) ? request()->deskripsi : '' }}" class="form-control form-control-sm" placeholder="Search for...">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Jenis Asset</label>
                            <select name="id_kategori_asset" class="form-control" id="kategoriAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Satuan</label>
                            <select name="id_satuan_asset" class="form-control" id="satuanAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Vendor</label>
                            <select name="id_vendor" class="form-control" id="vendorAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Lokasi</label>
                            <select name="id_lokasi" id="" class="form-control select2Lokasi">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Jenis</label>
                            <select name="is_sparepart" class="form-control" id="isSparepartFilter">
                                <option value="">Semua Jenis</option>
                                <option value="0">Asset</option>
                                <option value="1">Sparepart</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Pemutihan</label>
                            <select name="is_pemutihan" class="form-control" id="isPemutihanFilter">
                                <option value="all">Semua Asset</option>
                                <option value="0" selected>Asset Yang Tidak Diputihkan</option>
                                <option value="1">Asset Yang Diputihkan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Tanggal Perolehan</label>
                            <div class="d-flex align-items-center" style="gap: 10px">
                                <input type="text" readonly placeholder="Awal" name="tgl_perolehan_awal" class="form-control datepicker w-50" id="">
                                <input type="text" readonly placeholder="Akhir" name="tgl_perolehan_akhir" class="form-control datepicker w-50" id="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/general/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        const printQr = () => {
            window.print();
        }

        const openModalByClass = (className) => {
            $(`.${className}`).modal('show');
        }

        const getDataOptionSelect = (id = null) => {
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
                        if (id != null && id != element.id) {
                            select.append(
                                `<option ${selected} value="${element.id}">${element.text}</option>`
                            );
                        }

                        if (id == null) {
                            select.append(
                                `<option ${selected} value="${element.id}">${element.text}</option>`
                            );
                        }
                    });
                }
            })
        }

        const generateSelect2Lokasi = () => {
            $('.select2Lokasi').select2({
                'placeholder': 'Pilih Lokasi',
                'allowClear': true,
                'width': '100%'
            });
            // select2('val', $('#lokasiParentId').val());
        }

        $(document).ready(function() {
            getDataOptionSelect();
            generateSelect2Lokasi();

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
    @include('pages.admin.listing-asset.components.script-js._script_modal_filter')
</body>
</html>