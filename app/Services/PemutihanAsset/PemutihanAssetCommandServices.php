<?php

namespace App\Services\PemutihanAsset;

use Exception;
use App\Models\Approval;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use App\Models\PemutihanAsset;
use App\Models\DetailPemutihanAsset;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreDetailRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetChangeStatusRequest;

class PemutihanAssetCommandServices
{
    public function store(PemutihanAssetStoreRequest $request)
    {
        $request->validated();

        $user = \Session::get('user');

        $pemutihan = new PemutihanAsset();
        $pemutihan->guid_manager = $user->guid;
        $pemutihan->json_manager = json_encode($user);
        $pemutihan->tanggal = $request->tanggal;
        $pemutihan->no_memo = $request->no_memo;
        $pemutihan->status = 'Draft';
        $pemutihan->created_by = $user->guid;
        $pemutihan->is_store = 0;
        $pemutihan->keterangan = $request->keterangan_pemutihan;
        $pemutihan->save();

        for ($i = 0; $i < count($request->id_checkbox); $i++) {
            $id_checkbox = $request->id_checkbox[$i];
            $detail_pemutihan = new DetailPemutihanAsset();
            $detail_pemutihan->id_pemutihan_asset = $pemutihan->id;
            $detail_pemutihan->id_asset_data = $id_checkbox;
            $detail_pemutihan->save();

            // if ($request->status_pemutihan == 'Publish') {
            //     $asset_data = AssetData::findOrFail($id_checkbox);
            //     $asset_data->is_pemutihan = 1;
            //     $asset_data->save();
            // }
        }

        if ($request->hasFile('file_berita_acara')) {
            $filename = self::generateNameFile($request->file('file_berita_acara')->getClientOriginalExtension(), $pemutihan->id);
            $path = storage_path('app/file/pemutihan');
            $filenamesave = FileHelpers::saveFile($request->file('file_berita_acara'), $path, $filename);
            $pemutihan->file_bast = $filenamesave;
            $pemutihan->save();
        }

        $approval = new Approval();
        // ! nanti ubah guid_approver nya dengan guid dari manager
        $approval->guid_approver = $user->guid;
        $approval->approvable_type = get_class($pemutihan);
        $approval->approvable_id = $pemutihan->id;
        $approval->save();

        return $pemutihan;
    }

    public function storeDetailUpdate(PemutihanAssetStoreDetailRequest $request, $id)
    {
        $request->validated();
        $pemutihan = PemutihanAsset::findOrFail($id);
        if ($request->hasFile('gambar_asset')) {
            foreach ($request->file('gambar_asset') as $i => $file) {
                $detail_pemutihan = DetailPemutihanAsset::findOrFail($request->id_asset[$i]);
                $filename = self::generateNameImage($file->getClientOriginalExtension(), $detail_pemutihan->id);
                $path = storage_path('app/images/asset-pemutihan');
                $filenamesave = FileHelpers::saveFile($file, $path, $filename);

                $asset_images = new AssetImage();
                $asset_images->imageable_type = get_class($detail_pemutihan);
                $asset_images->imageable_id = $detail_pemutihan->id;
                $asset_images->path = $filenamesave;
                $asset_images->save();

                $detail_pemutihan->keterangan_pemutihan = $request->keterangan_pemutihan_asset[$i];
                $detail_pemutihan->save();
            }
        }
        $pemutihan->status = $request->status_pemutihan;
        $pemutihan->is_store = 1;
        $pemutihan->save();
        return $pemutihan;
    }

    public function destroy(string $id)
    {
        $pemutihan = PemutihanAsset::findOrFail($id);
        if ($pemutihan->status == 'Draft') {
            if (isset($pemutihan->file_bast)) {
                $path = storage_path('app/file/pemutihan');
                $pathOld = $path . '/' . $pemutihan->file_bast;
                FileHelpers::removeFile($pathOld);
            }
            $detail_pemutihan = DetailPemutihanAsset::where('id_pemutihan_asset', $pemutihan->id)->get();
            foreach ($detail_pemutihan as $item) {
                $item->delete();
            }
            return $pemutihan->delete();
        }
        return false;
    }

    public function update(PemutihanAssetUpdateRequest $request, string $id)
    {
        $request->validated();
        $user = \Session::get('user');

        $pemutihan = PemutihanAsset::findOrFail($id);
        $pemutihan->tanggal = $request->tanggal;
        $pemutihan->no_memo = $request->no_memo;
        $pemutihan->status = $request->status_pemutihan;
        $pemutihan->keterangan = $request->keterangan_pemutihan;
        $pemutihan->save();

        foreach ($pemutihan->detail_pemutihan_asset as $item) {
            $item->delete();
        }

        for ($i = 0; $i < count($request->id_checkbox); $i++) {
            $id_checkbox = $request->id_checkbox[$i];
            $detail_pemutihan = new DetailPemutihanAsset();
            $detail_pemutihan->id_pemutihan_asset = $pemutihan->id;
            $detail_pemutihan->id_asset_data = $id_checkbox;
            $detail_pemutihan->save();

            // if ($request->status_pemutihan == 'Publish') {
            //     $asset_data = AssetData::findOrFail($id_checkbox);
            //     $asset_data->is_pemutihan = 1;
            //     $asset_data->save();
            // }
        }

        return $pemutihan;
    }

    public function changeStatusApproval(PemutihanAssetChangeStatusRequest $request, string $id)
    {
        $request->validated();
        $user = \Session::get('user');

        $pemutihan = PemutihanAsset::findOrFail($id);

        if ($pemutihan->status != 'pending') {
            throw new Exception('Pemutihan asset tidak dapat diubah statusnya');
        }

        if ($user->guid != $pemutihan->approval->guid_approver) {
            throw new Exception('Anda tidak dapat mengubah status pemindahan asset ini');
        }

        $pemutihan->status = $request->status;
        $pemutihan->save();

        $approval = Approval::where('approvable_type', get_class($pemutihan))
            ->where('guid_approver', $user->guid)
            ->where('approvable_id', $pemutihan->id)
            ->first();

        $approval->status = $request->status == 'disetujui' ? '1' : '0';
        $approval->tanggal_approval = date('Y-m-d');
        $approval->keterangan = $request->keterangan;
        $approval->save();

        if ($request->status == 'disetujui') {
            foreach ($pemutihan->detail_pemutihan_asset as $item) {
                $asset_data = AssetData::findOrFail($item->id_asset_data);
                $asset_data->is_pemutihan = 1;
                $asset_data->save();
            }
        }

        return $pemutihan;
    }

    protected static function generateNameFile($extension, $kodeasset)
    {
        $name = 'berita-acara-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-pemutihan-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
