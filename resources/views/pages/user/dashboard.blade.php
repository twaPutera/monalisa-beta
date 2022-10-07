@extends('layouts.user.master')
@section('page-title', 'Dashboard')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataAssetOwner();
        });
        const getAllDataAssetOwner = () => {
            $.ajax({
                url: '{{ route("user.asset-data.get-all-data-asset-by-user") }}',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function (index, value) {
                                $('#assetContainer').append(generateTemplateAsset(value));
                            });
                        }
                        $('#countAsset').text(response.data.length);
                    }
                }
            })
        }
        const generateTemplateAsset = (data) => {
            return `
                <a href="${data.link_detail}" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                    <div class="d-flex align-items-center" style="width: 60%;">
                        <div style="width: 50px;">
                            <div class="icon-wrapper pt-1 bg-primary rounded-circle text-center" style="width: 40px; height: 40px;">
                                <ion-icon name="checkmark-circle" style="font-size: 22px;"></ion-icon>
                            </div>
                        </div>
                        <div class="ms-1" style="width: calc(100% - 50px);">
                            <p class="text-dark mb-0 asset-deskripsi">${data.deskripsi}</p>
                            <p class="text-primary mb-0 asset-deskripsi"><i>${data.kategori_asset ? data.kategori_asset.group_kategori_asset.nama_group : 'Not Found'}, ${data.kategori_asset ? data.kategori_asset.nama_kategori : "Not Found"}</i></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                        <div class="me-1 text-end">
                            <p class="text-grey mb-0 text-end">${data.status_diterima}</p>
                            <span class="text-grey text-end">${data.tanggal_diterima}</span>
                        </div>
                        <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </div>
                    </div>
                </a>
            `;
        }
    </script>
@endsection
@section('content')
<div class="section wallet-card-section pt-1">
    <div class="wallet-card">
        <!-- Balance -->
        <div class="balance">
            <div class="left">
                <span class="title text-primary">Selamat Datang</span>
                <h1 class="text-muted" style="font-size: 20px;">Rika Candra</h1>
                <span class="text-muted">Jenis Role</span>
            </div>
            <div class="right">
                <img alt="Logo" src="{{ asset('assets/images/logo-Press-103x75.png') }}" class="kt-header__brand-logo-default" width="80px" />
            </div>
        </div>
        <!-- * Balance -->
        <!-- Wallet Footer -->
        <div class="wallet-footer justify-content-between">
            <div class="item">
                <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
                    <div class="icon-wrapper bg-primary">
                        2
                    </div>
                    <strong>Aset</strong>
                </a>
            </div>
            <div class="item">
                <a href="#" data-bs-toggle="modal" data-bs-target="#sendActionSheet">
                    <div class="icon-wrapper bg-danger">
                        12
                    </div>
                    <strong>Aduan</strong>
                </a>
            </div>
            <div class="item">
                <a href="app-cards.html">
                    <div class="icon-wrapper bg-info">
                        1
                    </div>
                    <strong>Peminjaman</strong>
                </a>
            </div>
        </div>
        <!-- * Wallet Footer -->
    </div>
</div>
<div class="section mt-2">
    <div class="d-flex justify-content-between">
        <h2 class="text-grey"><strong>Daftar Aset Anda</strong></h2>
        <h2 class="text-grey"><strong id="countAsset">(2)</strong></h2>
    </div>
</div>
<div class="section mt-2">
    <div class="card p-1 bg-light-grey border-radius-sm">
        <div class="card-body p-1 bg-light-grey" id="assetContainer">

            {{-- <a href="#" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper pt-1 bg-danger rounded-circle text-center" style="width: 40px; height: 40px;">
                        <ion-icon name="remove-circle-outline" style="font-size: 23px;"></ion-icon>
                    </div>
                    <div class="ms-2">
                        <p class="text-dark mb-0">Lenovo Yoga</p>
                        <span class="text-primary"><i>Elektronik, Laptop</i></span>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-2">
                        <p class="text-grey mb-0 text-end">Belum Diterima</p>
                        <p class="text-grey mb-0 text-end">-</p>
                    </div>
                    <div class="mb-0 text-grey text-end" style="font-size: 27px !important;">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a> --}}
        </div>
    </div>
</div>
@endsection