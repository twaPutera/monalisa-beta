<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelpers;
use App\Models\DetailPemindahanAsset;
use App\Services\UserSso\UserSsoQueryServices;

class AssetDataQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function findById(string $id)
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset.group_kategori_asset', 'image', 'detail_service'])
            ->where('id', $id)
            ->firstOrFail();
        if (is_null($data->qr_code)) {
            $qr_name = 'qr-asset-' . $data->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($data->kode_asset, $path);
            $data->qr_code = $qr_name;
            $data->save();
        }
        $user = null;

        if (isset($data->ownership)) {
            $user = $this->userSsoQueryServices->getUserByGuid($data->ownership);
        }

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.image.preview') . '?filename=' . $item->path;
            return $item;
        });

        $data->link_detail = route('admin.listing-asset.detail', $data->id);
        $data->owner_name = $user == null ? 'Tidak ada' : $user[0]['nama'];

        return $data;
    }

    public function findBykode(Request $request)
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image'])
            ->where('kode_asset', $request->kode_asset)
            ->first();
        return $data;
    }

    public function findAssetImageById(string $id)
    {
        return AssetImage::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getDataAssetSelect2(Request $request)
    {
        $data = AssetData::query();

        if (isset($request->keyword)) {
            $data->where('deskripsi', 'like', '%' . $request->keyword . '%')
                ->where(function ($query) use ($request) {
                    $query->orWhere('kode_asset', 'like', '%' . $request->keyword . '%');
                });
        }

        if (isset($request->id_kategori_asset)) {
            $data->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->id_lokasi)) {
            if ($request->id_lokasi != 'root') {
                $data->where('id_lokasi', $request->id_lokasi);
            }
        }

        $data = $data->orderby('deskripsi', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->deskripsi . ' (' . $item->kode_asset . ')',
            ];
        }

        return $results;
    }

    public function getDataAssetForDashboardUser(string $user_id)
    {
        $asset_by_ownership = AssetData::query()
            ->select([
                'id',
            ])
            ->where('ownership', $user_id)
            ->get()->toArray();

        $asset_from_pemindahan = DetailPemindahanAsset::query()
            ->select([
                'id_asset',
            ])
            ->whereHas('pemindahan_asset', function ($query) use ($user_id) {
                $query->where('guid_penerima_asset', $user_id)
                    ->where('status', 'pending');
            })
            ->get()->toArray();

        // * Tambah Query Peminjaman Asset

        $array_id_asset = \Arr::flatten(array_merge($asset_by_ownership, $asset_from_pemindahan));

        $asset_data = AssetData::query()
            ->select([
                'id',
                'kode_asset',
                'deskripsi',
                'tgl_register',
                'id_kategori_asset',
            ])
            ->with(['kategori_asset.group_kategori_asset'])
            ->whereIn('id', $array_id_asset)
            ->get();

        return $asset_data;
    }

    public function checkIsAssetOnPemindahanAsset(string $asset_id, string $user_id)
    {
        $asset_from_pemindahan = DetailPemindahanAsset::query()
            ->select([
                'id',
                'id_pemindahan_asset',
                'id_asset',
            ])
            ->with(['pemindahan_asset'])
            ->whereHas('pemindahan_asset', function ($query) use ($user_id) {
                $query->where('guid_penerima_asset', $user_id)
                    ->where('status', 'pending');
            })
            ->where('id_asset', $asset_id)
            ->first();

        return $asset_from_pemindahan;
    }

    public function countAsset(Request $request)
    {
        $data = AssetData::query();

        if (isset($request->id_kategori_asset)) {
            $data->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->id_lokasi)) {
            if ($request->id_lokasi != 'root') {
                $data->where('id_lokasi', $request->id_lokasi);
            }
        }

        if (isset($request->id_vendor)) {
            $data->where('id_vendor', $request->id_vendor);
        }

        if (isset($request->id_kelas_asset)) {
            $data->where('id_kelas_asset', $request->id_kelas_asset);
        }

        if (isset($request->id_satuan_asset)) {
            $data->where('id_satuan_asset', $request->id_satuan_asset);
        }

        if (isset($request->id_group_kategori_asset)) {
            $data->whereHas('kategori_asset', function ($query) use ($request) {
                $query->where('id_group_kategori_asset', $request->id_group_kategori_asset);
            });
        }

        if (isset($request->is_pemutihan)) {
            $data->where('is_pemutihan', $request->is_pemutihan);
        }

        if (isset($request->status_asset)) {
            $data->where('status_asset', $request->status_asset);
        }

        $data = $data->count();

        return $data;
    }
}
