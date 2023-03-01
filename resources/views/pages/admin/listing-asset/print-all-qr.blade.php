<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print All Qr</title>
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
                    <input type="number" name="limit" placeholder="Limit" value="{{ isset(request()->limit) ? request()->limit : 50 }}" class="form-control mr-2" style="width: 200px;">
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
    <script src="{{ asset('assets/js/scripts.bundle.min.js') }}" type="text/javascript"></script>
    <script>
        const printQr = () => {
            window.print();
        }
    </script>
</body>
</html>