@extends('layouts.user.master')
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
        <h2 class="text-grey"><strong>(2)</strong></h2>
    </div>
</div>
<div class="section mt-2">
    <div class="card p-1 bg-light-grey border-radius-sm">
        <div class="card-body p-1 bg-light-grey">
            <a href="#" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper pt-1 bg-primary rounded-circle text-center" style="width: 40px; height: 40px;">
                        <ion-icon name="checkmark-circle" style="font-size: 23px;"></ion-icon>
                    </div>
                    <div class="ms-2">
                        <p class="text-dark mb-0">Mobil Xpander</p>
                        <p class="text-primary mb-0"><i>Vechicle, Mobil</i></p>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-2">
                        <p class="text-grey mb-0 text-end">Diterima</p>
                        <span class="text-grey text-end">12/05/2022</span>
                    </div>
                    <div class="mb-0 text-grey text-end" style="font-size: 27px !important;">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <a href="#" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
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
            </a>
        </div>
    </div>
</div>
@endsection