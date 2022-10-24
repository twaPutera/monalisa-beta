<?php

namespace App\Services\AssetService;

use App\Models\Service;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\DetailService;
use App\Models\LogServiceAsset;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Services\ServicesStoreRequest;
use App\Http\Requests\Services\ServicesUpdateRequest;
use App\Http\Requests\AssetService\AssetServiceStoreRequest;
use App\Http\Requests\UserAssetService\UserAssetServiceStoreRequest;

class AssetServiceCommandServices
{
    public function store(string $id, AssetServiceStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_service = new Service();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $request->tanggal_mulai_service;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->keterangan = $request->keterangan_service;
        $asset_service->save();

        $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $id)->first();
        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        $log = self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_service;
    }

    public function storeServices(ServicesStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_service = new Service();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $request->tanggal_mulai_service;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->keterangan = $request->keterangan_service;
        $asset_service->save();

        $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $request->id_asset)->first();
        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        $log = self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_service;
    }

    public function storeUserServices(UserAssetServiceStoreRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_service = new Service();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $request->tanggal_mulai_service;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->keterangan = $request->keterangan_service;

        $asset_service->save();

        $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $id)->first();
        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        $log = self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_service;
    }

    public function updateServices(string $id, ServicesUpdateRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_service = Service::findOrFail($id);
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $request->tanggal_mulai_service;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->keterangan = $request->keterangan_service;
        $asset_service->save();

        $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $request->id_asset)->first();
        $detail_asset_service = DetailService::where('id_service', $asset_service->id)->firstOrFail();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        $log = self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service, 'Perubahan');

        if ($request->hasFile('file_asset_service')) {
            $path = storage_path('app/images/asset-service');
            if (isset($asset_service->image[0])) {
                $pathOld = $path . '/' . $asset_service->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset_service->image[0]->delete();
            }
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_service;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-service-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    protected static function storeLog($id_asset, $nama_asset, $status, $log = 'Penambahan')
    {
        $log_asset = new LogServiceAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_service = $id_asset;
        $log_asset->message_log = "$log Data Service untuk asset $nama_asset oleh " . Auth::user()->role;
        $log_asset->status = $status == 'onprogress' ? 'on progress' : $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();
        return $log_asset;
    }
}
