<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print All Qr</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
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
                    <div class="form-group mb-0 mr-2">
                        <select name="id_kategori_asset" class="form-control" id="kategoriAssetFilter">
                            @if (isset(request()->id_kategori_asset))
                                <option value="{{ request()->id_kategori_asset }}" selected>{{ \App\Models\KategoriAsset::find(request()->id_kategori_asset)->nama_kategori ?? 'No Kategori' }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group mb-0 mr-2">
                        <select name="id_satuan_asset" class="form-control" id="satuanAssetFilter">
                            @if (isset(request()->id_satuan_asset))
                                <option value="{{ request()->id_satuan_asset }}" selected>{{ \App\Models\SatuanAsset::find(request()->id_satuan_asset)->nama_satuan ?? 'No Satuan' }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group mb-0 mr-2">
                        <select name="id_vendor" class="form-control" id="vendorAssetFilter">
                            @if (isset(request()->id_vendor))
                                <option value="{{ request()->id_vendor }}" selected>{{ \App\Models\Vendor::find(request()->id_vendor)->nama_vendor ?? 'No vendor' }}</option>
                            @endif
                        </select>
                    </div>
                    <input type="number" name="limit" placeholder="Limit" value="{{ isset(request()->limit) ? request()->limit : 50 }}" class="form-control mr-2" style="width: 100px;">
                    <button type="submit" class="btn btn-primary mr-2">Refresh</button>
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
    <script src="{{ asset('assets/vendors/general/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        const printQr = () => {
            window.print();
        }
    </script>
    <script>
        const generateSatuanAssetFilter = () => {
            $('#satuanAssetFilter').select2({
                width: '150px',
                placeholder: 'Pilih Satuan',
                ajax: {
                    url: '{{ route('admin.setting.satuan-asset.get-data-select2') }}',
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

        const generateVendorAssetFilter = () => {
            $('#vendorAssetFilter').select2({
                width: '150px',
                placeholder: 'Pilih Vendor',
                ajax: {
                    url: '{{ route('admin.setting.vendor.get-data-select2') }}',
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

        const generateKategoriSelect2Filter = () => {
            $('#kategoriAssetFilter').select2({
                width: '150px',
                placeholder: 'Pilih Kategori',
                ajax: {
                    url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                            // id_group_kategori_asset: idGroup,
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

        $(document).ready(function() {
            generateKategoriSelect2Filter();
            generateSatuanAssetFilter();
            generateVendorAssetFilter();
        });
    </script>
</body>
</html>