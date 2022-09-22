<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\TestFront\TestingController;
use App\Http\Controllers\Admin\Setting\LokasiController;
use App\Http\Controllers\Admin\Setting\VendorController;
use App\Http\Controllers\Admin\Services\ServicesController;
use App\Http\Controllers\Admin\Setting\KelasAssetController;
use App\Http\Controllers\Admin\Setting\SatuanAssetController;
use App\Http\Controllers\Admin\Setting\SistemConfigController;
use App\Http\Controllers\Admin\Setting\KategoriAssetController;
use App\Http\Controllers\Admin\Setting\KategoriServiceController;
use App\Http\Controllers\Admin\Setting\SatuanInventoriController;
use App\Http\Controllers\Admin\ListingAsset\MasterAssetController;
use App\Http\Controllers\Admin\ListingAsset\AssetServiceController;
use App\Http\Controllers\Admin\Setting\KategoriInventoriController;
use App\Http\Controllers\Admin\Setting\GroupKategoriAssetController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/sso/redirect', [SsoController::class, 'redirectSso'])->name('sso.redirect');

Route::get('/callback', [SsoController::class, 'callback']);

Route::get('/', function () {
    return redirect()->route('sso.redirect');
});

Route::group(['prefix' => 'admin', 'middleware' => ['sso']], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [SsoController::class, 'logoutSso'])->name('sso.logout');
    # Listing Asset
    Route::group(['prefix' => 'listing-asset'], function () {
        Route::get('/', [MasterAssetController::class, 'index'])->name('admin.listing-asset.index');
        Route::get('/datatable', [MasterAssetController::class, 'datatable'])->name('admin.listing-asset.datatable');
        Route::post('/store', [MasterAssetController::class, 'store'])->name('admin.listing-asset.store');
        Route::get('/show/{id}', [MasterAssetController::class, 'show'])->name('admin.listing-asset.show');
        Route::get('/detail/{id}', [MasterAssetController::class, 'detail'])->name('admin.listing-asset.detail');
        Route::post('/update/{id}', [MasterAssetController::class, 'update'])->name('admin.listing-asset.update');
        Route::get('/download-template-import', [MasterAssetController::class, 'downloadTemplateImport'])->name('admin.listing-asset.download-template-import');
        Route::get('/get-all-data-owner-select2', [MasterAssetController::class, 'getDataAllOwnerSelect2'])->name('admin.listing-asset.get-all-data-owner-select2');
        Route::get('/get-all-data-asset-select2', [MasterAssetController::class, 'getDataAllAssetSelect2'])->name('admin.listing-asset.get-all-data-asset-select2');
        Route::post('/import-asset-data', [MasterAssetController::class, 'importAssetData'])->name('admin.listing-asset.import-asset-data');
        Route::get('/preview-qr', [MasterAssetController::class, 'previewQr'])->name('admin.listing-asset.preview-qr');
        Route::get('/download-qr', [MasterAssetController::class, 'downloadQr'])->name('admin.listing-asset.download-qr');

        # Asset Image
        Route::group(['asset-image'], function () {
            Route::get('/preview', [MasterAssetController::class, 'previewImage'])->name('admin.listing-asset.image.preview');
            Route::get('/preview-service', [AssetServiceController::class, 'previewImage'])->name('admin.listing-asset.service.image.preview');
        });

        # Service Asset
        Route::group(['prefix' => 'service-asset'], function () {
            Route::post('/store/{id}', [AssetServiceController::class, 'store'])->name('admin.listing-asset.service-asset.store');
            Route::get('/datatable', [AssetServiceController::class, 'datatable'])->name('admin.listing-asset.service-asset.datatable');
            Route::get('/show/{id}', [AssetServiceController::class, 'show'])->name('admin.listing-asset.service-asset.show');
        });
    });
    # Services
    Route::group(['prefix' => 'services'], function () {
        Route::get('/', [ServicesController::class, 'index'])->name('admin.services.index');
        Route::get('/datatable', [ServicesController::class, 'datatable'])->name('admin.services.datatable');
        Route::post('/store', [ServicesController::class, 'store'])->name('admin.services.store');
        Route::get('/edit/{id}', [ServicesController::class, 'edit'])->name('admin.services.edit');
        Route::post('/update/{id}', [ServicesController::class, 'update'])->name('admin.services.update');
    });
    # Setting
    Route::group(['prefix' => 'setting'], function () {
        // #Sistem Config
        Route::group(['prefix' => 'sistem-config'], function () {
            Route::get('/', [SistemConfigController::class, 'index'])->name('admin.sistem-config.index');
            Route::post('/store', [SistemConfigController::class, 'update'])->name('admin.sistem-config.update');
        });
        // #Lokasi
        Route::group(['prefix' => 'lokasi'], function () {
            Route::get('/', [LokasiController::class, 'index'])->name('admin.setting.lokasi.index');
            Route::post('/store', [LokasiController::class, 'store'])->name('admin.setting.lokasi.store');
            Route::get('/edit/{id}', [LokasiController::class, 'edit'])->name('admin.setting.lokasi.edit');
            Route::post('/update/{id}', [LokasiController::class, 'update'])->name('admin.setting.lokasi.update');
            Route::post('/delete/{id}', [LokasiController::class, 'destroy'])->name('admin.setting.lokasi.delete');
            Route::get('/datatable', [LokasiController::class, 'datatable'])->name('admin.setting.lokasi.datatable');
            Route::get('/get-node-tree', [LokasiController::class, 'getNodeTree'])->name('admin.setting.lokasi.get-node-tree');
            Route::get('/get-select2', [LokasiController::class, 'getAllSelect2'])->name('admin.setting.lokasi.get-select2');
        });
        // #Group-Kategori-Asset
        Route::group(['prefix' => 'group-kategori-asset'], function () {
            Route::get('/', [GroupKategoriAssetController::class, 'index'])->name('admin.setting.group-kategori-asset.index');
            Route::post('/store', [GroupKategoriAssetController::class, 'store'])->name('admin.setting.group-kategori-asset.store');
            Route::get('/edit/{id}', [GroupKategoriAssetController::class, 'edit'])->name('admin.setting.group-kategori-asset.edit');
            Route::post('/update/{id}', [GroupKategoriAssetController::class, 'update'])->name('admin.setting.group-kategori-asset.update');
            Route::post('/delete/{id}', [GroupKategoriAssetController::class, 'destroy'])->name('admin.setting.group-kategori-asset.delete');
            Route::get('/datatable', [GroupKategoriAssetController::class, 'datatable'])->name('admin.setting.group-kategori-asset.datatable');
            Route::get('/find-all', [GroupKategoriAssetController::class, 'findAll'])->name('admin.setting.group-kategori-asset.find-all');
            Route::get('/get-data-select2', [GroupKategoriAssetController::class, 'getDataSelect2'])->name('admin.setting.group-kategori-asset.get-data-select2');
        });
        // #Kategori-Asset
        Route::group(['prefix' => 'kategori-asset'], function () {
            Route::get('/', [KategoriAssetController::class, 'index'])->name('admin.setting.kategori-asset.index');
            Route::post('/store', [KategoriAssetController::class, 'store'])->name('admin.setting.kategori-asset.store');
            Route::get('/edit/{id}', [KategoriAssetController::class, 'edit'])->name('admin.setting.kategori-asset.edit');
            Route::post('/update/{id}', [KategoriAssetController::class, 'update'])->name('admin.setting.kategori-asset.update');
            Route::post('/delete/{id}', [KategoriAssetController::class, 'destroy'])->name('admin.setting.kategori-asset.delete');
            Route::get('/datatable', [KategoriAssetController::class, 'datatable'])->name('admin.setting.kategori-asset.datatable');
            Route::get('/get-data-select2', [KategoriAssetController::class, 'getDataSelect2'])->name('admin.setting.kategori-asset.get-data-select2');
        });
        // # Satuan Asset
        Route::group(['prefix' => 'satuan-asset'], function () {
            Route::get('/', [SatuanAssetController::class, 'index'])->name('admin.setting.satuan-asset.index');
            Route::post('/store', [SatuanAssetController::class, 'store'])->name('admin.setting.satuan-asset.store');
            Route::get('/edit/{id}', [SatuanAssetController::class, 'edit'])->name('admin.setting.satuan-asset.edit');
            Route::post('/update/{id}', [SatuanAssetController::class, 'update'])->name('admin.setting.satuan-asset.update');
            Route::post('/delete/{id}', [SatuanAssetController::class, 'destroy'])->name('admin.setting.satuan-asset.delete');
            Route::get('/datatable', [SatuanAssetController::class, 'datatable'])->name('admin.setting.satuan-asset.datatable');
            Route::get('/get-data-select2', [SatuanAssetController::class, 'getDataSelect2'])->name('admin.setting.satuan-asset.get-data-select2');
        });
        // # Vendor
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('/', [VendorController::class, 'index'])->name('admin.setting.vendor.index');
            Route::post('/store', [VendorController::class, 'store'])->name('admin.setting.vendor.store');
            Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('admin.setting.vendor.edit');
            Route::post('/update/{id}', [VendorController::class, 'update'])->name('admin.setting.vendor.update');
            Route::post('/delete/{id}', [VendorController::class, 'destroy'])->name('admin.setting.vendor.delete');
            Route::get('/datatable', [VendorController::class, 'datatable'])->name('admin.setting.vendor.datatable');
            Route::get('/get-data-select2', [VendorController::class, 'getDataSelect2'])->name('admin.setting.vendor.get-data-select2');
        });
        // #Kategori Inventori
        Route::group(['prefix' => 'kategori-inventori'], function () {
            Route::get('/', [KategoriInventoriController::class, 'index'])->name('admin.setting.kategori-inventori.index');
            Route::post('/store', [KategoriInventoriController::class, 'store'])->name('admin.setting.kategori-inventori.store');
            Route::get('/edit/{id}', [KategoriInventoriController::class, 'edit'])->name('admin.setting.kategori-inventori.edit');
            Route::post('/update/{id}', [KategoriInventoriController::class, 'update'])->name('admin.setting.kategori-inventori.update');
            Route::post('/delete/{id}', [KategoriInventoriController::class, 'destroy'])->name('admin.setting.kategori-inventori.delete');
            Route::get('/datatable', [KategoriInventoriController::class, 'datatable'])->name('admin.setting.kategori-inventori.datatable');
        });
        // # Satuan Inventori
        Route::group(['prefix' => 'satuan-inventori'], function () {
            Route::get('/', [SatuanInventoriController::class, 'index'])->name('admin.setting.satuan-inventori.index');
            Route::post('/store', [SatuanInventoriController::class, 'store'])->name('admin.setting.satuan-inventori.store');
            Route::get('/edit/{id}', [SatuanInventoriController::class, 'edit'])->name('admin.setting.satuan-inventori.edit');
            Route::post('/update/{id}', [SatuanInventoriController::class, 'update'])->name('admin.setting.satuan-inventori.update');
            Route::post('/delete/{id}', [SatuanInventoriController::class, 'destroy'])->name('admin.setting.satuan-inventori.delete');
            Route::get('/datatable', [SatuanInventoriController::class, 'datatable'])->name('admin.setting.satuan-inventori.datatable');
        });
        // # Kelas Asset
        Route::group(['prefix' => 'kelas-asset'], function () {
            Route::get('/', [KelasAssetController::class, 'index'])->name('admin.setting.kelas-asset.index');
            Route::post('/store', [KelasAssetController::class, 'store'])->name('admin.setting.kelas-asset.store');
            Route::get('/edit/{id}', [KelasAssetController::class, 'edit'])->name('admin.setting.kelas-asset.edit');
            Route::post('/update/{id}', [KelasAssetController::class, 'update'])->name('admin.setting.kelas-asset.update');
            Route::post('/delete/{id}', [KelasAssetController::class, 'destroy'])->name('admin.setting.kelas-asset.delete');
            Route::get('/datatable', [KelasAssetController::class, 'datatable'])->name('admin.setting.kelas-asset.datatable');
            Route::get('/get-data-select2', [KelasAssetController::class, 'getDataSelect2'])->name('admin.setting.kelas-asset.get-data-select2');
        });
        // # Kategori Service
        Route::group(['prefix' => 'kategori-service'], function () {
            Route::get('/', [KategoriServiceController::class, 'index'])->name('admin.setting.kategori-service.index');
            Route::post('/store', [KategoriServiceController::class, 'store'])->name('admin.setting.kategori-service.store');
            Route::get('/edit/{id}', [KategoriServiceController::class, 'edit'])->name('admin.setting.kategori-service.edit');
            Route::post('/update/{id}', [KategoriServiceController::class, 'update'])->name('admin.setting.kategori-service.update');
            Route::post('/delete/{id}', [KategoriServiceController::class, 'destroy'])->name('admin.setting.kategori-service.delete');
            Route::get('/datatable', [KategoriServiceController::class, 'datatable'])->name('admin.setting.kategori-service.datatable');
            Route::get('/get-data-select2', [KategoriServiceController::class, 'getDataSelect2'])->name('admin.setting.kategori-service.get-data-select2');
        });
    });
});

Route::group(['prefix' => 'test-front', 'namespace' => 'TestFront'], function () {
    Route::group(['prefix' => 'print'], function () {
        Route::post('/', [TestingController::class, 'print'])->name('admin.setting.print.index');
    });
    Route::get('/', 'TestingController@index');
    Route::get('/tree', 'TestingController@tree');
    Route::get('/form', 'TestingController@form');
    Route::get('/table', 'TestingController@table');
    Route::post('/form-post', 'TestingController@formPost')->name('test-front.form-post');
    Route::get('/select2-ajax-data', 'TestingController@select2AjaxData')->name('test-front.select2-ajax-data');
    Route::get('/datatable', 'TestingController@datatable')->name('test-front.datatable');
});
