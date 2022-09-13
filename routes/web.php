<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\Setting\LokasiController;
use App\Http\Controllers\Admin\Setting\SatuanAssetController;
use App\Http\Controllers\Admin\Setting\KategoriAssetController;
use App\Http\Controllers\Admin\Setting\SistemConfigController;
use App\Http\Controllers\Admin\Setting\VendorController;
use App\Http\Controllers\Auth\SsoController;
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

Route::group(['prefix' => 'admin', 'middleware' => ['sso']], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [SsoController::class, 'logoutSso'])->name('sso.logout');
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
        // #Kategori-Asset
        Route::group(['prefix' => 'kategori-asset'], function () {
            Route::get('/', [KategoriAssetController::class, 'index'])->name('admin.setting.kategori-asset.index');
            Route::post('/store', [KategoriAssetController::class, 'store'])->name('admin.setting.kategori-asset.store');
            Route::get('/edit/{id}', [KategoriAssetController::class, 'edit'])->name('admin.setting.kategori-asset.edit');
            Route::post('/update/{id}', [KategoriAssetController::class, 'update'])->name('admin.setting.kategori-asset.update');
            Route::post('/delete/{id}', [KategoriAssetController::class, 'destroy'])->name('admin.setting.kategori-asset.delete');
            Route::get('/datatable', [KategoriAssetController::class, 'datatable'])->name('admin.setting.kategori-asset.datatable');
        });
        // # Satuan Asset
        Route::group(['prefix' => 'satuan-asset'], function () {
            Route::get('/', [SatuanAssetController::class, 'index'])->name('admin.setting.satuan-asset.index');
            Route::post('/store', [SatuanAssetController::class, 'store'])->name('admin.setting.satuan-asset.store');
            Route::get('/edit/{id}', [SatuanAssetController::class, 'edit'])->name('admin.setting.satuan-asset.edit');
            Route::post('/update/{id}', [SatuanAssetController::class, 'update'])->name('admin.setting.satuan-asset.update');
            Route::post('/delete/{id}', [SatuanAssetController::class, 'destroy'])->name('admin.setting.satuan-asset.delete');
            Route::get('/datatable', [SatuanAssetController::class, 'datatable'])->name('admin.setting.satuan-asset.datatable');
        });
        // # Vendor
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('/', [VendorController::class, 'index'])->name('admin.setting.vendor.index');
            Route::post('/store', [VendorController::class, 'store'])->name('admin.setting.vendor.store');
            Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('admin.setting.vendor.edit');
            Route::post('/update/{id}', [VendorController::class, 'update'])->name('admin.setting.vendor.update');
            Route::post('/delete/{id}', [VendorController::class, 'destroy'])->name('admin.setting.vendor.delete');
            Route::get('/datatable', [VendorController::class, 'datatable'])->name('admin.setting.vendor.datatable');
        });
    });
});

Route::group(['prefix' => 'test-front', 'namespace' => 'TestFront'], function () {
    Route::get('/', 'TestingController@index');
    Route::get('/tree', 'TestingController@tree');
    Route::get('/form', 'TestingController@form');
    Route::get('/table', 'TestingController@table');
    Route::post('/form-post', 'TestingController@formPost')->name('test-front.form-post');
    Route::get('/select2-ajax-data', 'TestingController@select2AjaxData')->name('test-front.select2-ajax-data');
    Route::get('/datatable', 'TestingController@datatable')->name('test-front.datatable');
});
