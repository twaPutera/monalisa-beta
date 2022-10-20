<?php

namespace App\Services\PemutihanAsset;

use Exception;
use App\Models\Approval;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use App\Models\PemutihanAsset;
use App\Models\DetailPemutihanAsset;
use App\Services\UserSso\UserSsoQueryServices;
use App\Http\Requests\Approval\PemutihanApprovalUpdate;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreDetailRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetChangeStatusRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateListingRequest;
use Illuminate\Support\Facades\Session;
use App\Helpers\SsoHelpers;

class PemutihanAssetCommandServices
{
    protected $userSsoQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function store(PemutihanAssetStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 'manager');
        if (!isset($approver[0])) {
            throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        }
        $pemutihan = new PemutihanAsset();
        $pemutihan->guid_manager = config('app.sso_siska') ? $approver[0]['guid'] : $approver[0]->id;
        $pemutihan->json_manager = json_encode($approver[0]);
        $pemutihan->tanggal = $request->tanggal;
        $pemutihan->no_memo = $request->no_berita_acara;
        $pemutihan->status = 'Draft';
        $pemutihan->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $pemutihan->is_store = 0;
        $pemutihan->keterangan = $request->keterangan_pemutihan;
        $pemutihan->save();

        for ($i = 0; $i < count($request->id_checkbox); $i++) {
            $id_checkbox = $request->id_checkbox[$i];
            $detail_pemutihan = new DetailPemutihanAsset();
            $detail_pemutihan->id_pemutihan_asset = $pemutihan->id;
            $detail_pemutihan->id_asset_data = $id_checkbox;
            $detail_pemutihan->save();
        }

        if ($request->hasFile('file_berita_acara')) {
            $filename = self::generateNameFile($request->file('file_berita_acara')->getClientOriginalExtension(), $pemutihan->id);
            $path = storage_path('app/file/pemutihan');
            $filenamesave = FileHelpers::saveFile($request->file('file_berita_acara'), $path, $filename);
            $pemutihan->file_bast = $filenamesave;
            $pemutihan->save();
        }

        return $pemutihan;
    }

    public function changeApprovalStatus(PemutihanApprovalUpdate $request, $id)
    {
        $request->validated();

        $pemutihan = PemutihanAsset::findOrFail($id);
        $pemutihan->status = $request->status == 'disetujui' ? 'Diproses' : 'Ditolak';
        $pemutihan->save();

        foreach ($pemutihan->detail_pemutihan_asset as $item) {
            if ($request->status == 'disetujui') {
                $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $item->id_asset_data)->first();
                $asset_data->is_pemutihan = 1;
                $asset_data->save();
            }
        }

        $approval = $pemutihan->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = Session::get('user')->guid;
        $approval->is_approve = $request->status == 'disetujui' ? 1 : 2;
        $approval->keterangan = $request->keterangan;
        $approval->save();

        return $pemutihan;
    }

    public function storeDetailUpdate(PemutihanAssetStoreDetailRequest $request, $id)
    {
        $request->validated();
        $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 'manager');
        if (!isset($approver[0])) {
            throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        }
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

        if ($request->status_pemutihan == 'Publish') {
            $approval = new Approval();
            $approval->guid_approver = config('app.sso_siska') ? $approver[0]['guid'] : $approver[0]->id;
            $approval->approvable_type = get_class($pemutihan);
            $approval->approvable_id = $pemutihan->id;
            $approval->save();
        }
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

    public function updateListingPemutihan(PemutihanAssetUpdateListingRequest $request, string $id)
    {
        $request->validated();
        $pemutihan = PemutihanAsset::findOrFail($id);
        $detail_pemutihan = DetailPemutihanAsset::with(['image'])->where('id_pemutihan_asset', $pemutihan->id)->get();
        $request_checkbox = [];

        for ($i = 0; $i < count($request->id_checkbox); $i++) {
            $id_checkbox = $request->id_checkbox[$i];
            array_push($request_checkbox, $id_checkbox);
            $cek_detail_pemutihan = DetailPemutihanAsset::where('id_asset_data', $id_checkbox)->where('id_pemutihan_asset', $id)->first();
            if ($cek_detail_pemutihan == null) {
                $detail_pemutihan_create = new DetailPemutihanAsset();
                $detail_pemutihan_create->id_pemutihan_asset = $id;
                $detail_pemutihan_create->id_asset_data = $id_checkbox;
                $detail_pemutihan_create->save();
            }
        }

        foreach ($detail_pemutihan as $item_pemutihan) {
            if (!in_array($item_pemutihan->id_asset_data, $request_checkbox)) {
                $path = storage_path('app/images/asset-pemutihan');
                if (isset($item_pemutihan->image[0])) {
                    $pathOld = $path . '/' . $item_pemutihan->image[0]->path;
                    FileHelpers::removeFile($pathOld);
                    $item_pemutihan->image[0]->delete();
                }
                $item_pemutihan->delete();
            }
        }

        return $detail_pemutihan;
    }

    public function update(PemutihanAssetUpdateRequest $request, $id)
    {
        $request->validated();
        $pemutihan = PemutihanAsset::findOrFail($id);
        $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 'manager');
        if (!isset($approver[0])) {
            throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        }
        if ($request->hasFile('file_berita_acara')) {
            if (isset($pemutihan->file_bast)) {
                $path = storage_path('app/file/pemutihan');
                $pathOld = $path . '/' . $pemutihan->file_bast;
                FileHelpers::removeFile($pathOld);
            }
            $filename = self::generateNameFile($request->file('file_berita_acara')->getClientOriginalExtension(), $pemutihan->id);
            $path = storage_path('app/file/pemutihan');
            $filenamesave = FileHelpers::saveFile($request->file('file_berita_acara'), $path, $filename);
            $pemutihan->file_bast = $filenamesave;
            $pemutihan->save();
        }

        if ($request->hasFile('gambar_asset')) {
            foreach ($request->file('gambar_asset') as $i => $file) {
                $detail_pemutihan = DetailPemutihanAsset::with(['image'])->where('id', $request->id_asset[$i])->first();
                $path = storage_path('app/images/asset-pemutihan');
                if (isset($detail_pemutihan->image[0])) {
                    $pathOld = $path . '/' . $detail_pemutihan->image[0]->path;
                    FileHelpers::removeFile($pathOld);
                    $detail_pemutihan->image[0]->delete();
                }
                $filename = self::generateNameImage($file->getClientOriginalExtension(), $detail_pemutihan->id);
                $filenamesave = FileHelpers::saveFile($file, $path, $filename);

                $asset_images = new AssetImage();
                $asset_images->imageable_type = get_class($detail_pemutihan);
                $asset_images->imageable_id = $detail_pemutihan->id;
                $asset_images->path = $filenamesave;
                $asset_images->save();
            }
        }
        for ($i = 0; $i < count($request->keterangan_pemutihan_asset); $i++) {
            $detail_pemutihan = DetailPemutihanAsset::with(['image'])->where('id', $request->id_asset[$i])->first();
            $detail_pemutihan->keterangan_pemutihan = $request->keterangan_pemutihan_asset[$i];
            $detail_pemutihan->save();
        }

        $pemutihan->tanggal = $request->tanggal;
        $pemutihan->no_memo = $request->no_berita_acara;
        $pemutihan->keterangan = $request->keterangan_pemutihan;
        $pemutihan->status = $request->status_pemutihan;
        $pemutihan->save();

        if ($request->status_pemutihan == 'Publish') {
            $approval = new Approval();
            $approval->guid_approver = config('app.sso_siska') ? $approver[0]['guid'] : $approver[0]->id;
            $approval->approvable_type = get_class($pemutihan);
            $approval->approvable_id = $pemutihan->id;
            $approval->save();
        }
        return $pemutihan;
    }

    public function changeStatusApproval(PemutihanAssetChangeStatusRequest $request, string $id)
    {
        $request->validated();
        $user = Session::get('user');

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
                $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $item->id_asset_data)->first();
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
