@extends('layouts.user.master-detail')
@section('page-title', 'Detail Pemindahan')
@section('custom-js')
    <script>

    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
<div class="section mt-2">
    <h2 style="color: #6F6F6F;">{{ $asset_data->deskripsi }}</h2>

    <div class="mt-2">
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Group</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ $asset_data->kategori_asset->group_kategori_asset->nama_group ?? "Tidak Ada Group" }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Kategori</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ $asset_data->kategori_asset->nama_kategori ?? "Tidak Ada Kategori" }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Lokasi</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ $asset_data->lokasi->nama_lokasi ?? "Tidak Ada Lokasi" }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Tanggal Register</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ App\Helpers\DateIndoHelpers::formatDateToIndo($asset_data->tgl_register) }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Status Terakhir</p>
                </div>
                <div class="col text-end">
                    <span class="badge badge-success px-3">Baik</span>
                    {{-- <p class="mb-0 text-green text-end">Baik</p> --}}
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Catatan</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ $last_service->detail_service->catatan ?? "-" }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Log Terakhir</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ isset($last_service) ? App\Helpers\DateIndoHelpers::formatDateToIndo($last_service->tanggal_selesai) : "-" }}</p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Dicek Oleh</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end"><strong>{{ isset($last_service) ? $last_service->guid_pembuat : "-" }}</strong></p>
                </div>
            </div>
        </div>
        <div class="py-2 border-bottom border-secondary">
            <div class="row">
                <div class="col">
                    <p class="mb-0 text-green">Spesifikasi</p>
                </div>
                <div class="col">
                    <p class="mb-0 text-green text-end">{{ $asset_data->spesifikasi }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('button-menu')
    <form action="#" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary btn-block border-radius-sm px-5">Terima</button>
    </form>
@endsection