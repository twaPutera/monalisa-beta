<?php

namespace App\Services\PemutihanAsset;

use App\Models\AssetData;
use App\Models\PemutihanAsset;
use App\Models\DetailPemutihanAsset;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateRequest;

class PemutihanAssetCommandServices
{
    public function store(PemutihanAssetStoreRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');

        $pemutihan = new PemutihanAsset();
        $pemutihan->guid_manager = $user->guid;
        $pemutihan->json_manager = 'Example Json';
        $pemutihan->tanggal = $request->tanggal;
        $pemutihan->no_memo = $request->no_memo;
        $pemutihan->status = $request->status_pemutihan;
        $pemutihan->created_by = $user->name;
        $pemutihan->keterangan = $request->keterangan_pemutihan;
        $pemutihan->save();

        for ($i = 0; $i < count($request->id_checkbox); $i++) {
            $id_checkbox = $request->id_checkbox[$i];
            $detail_pemutihan = new DetailPemutihanAsset();
            $detail_pemutihan->id_pemutihan_asset = $pemutihan->id;
            $detail_pemutihan->id_asset_data = $id_checkbox;
            $detail_pemutihan->save();

            if ($request->status_pemutihan == 'Publish') {
                $asset_data = AssetData::findOrFail($id_checkbox);
                $asset_data->is_pemutihan = 1;
                $asset_data->save();
            }
        }

        return $pemutihan;
    }

    public function destroy(string $id)
    {
        $pemutihan = PemutihanAsset::findOrFail($id);
        if ($pemutihan->status == 'Draft') {
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

            if ($request->status_pemutihan == 'Publish') {
                $asset_data = AssetData::findOrFail($id_checkbox);
                $asset_data->is_pemutihan = 1;
                $asset_data->save();
            }
        }

        return $pemutihan;
    }
}
