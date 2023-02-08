<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetData;
use App\Helpers\QrCodeHelpers;

class GenerateQrAssetController extends Controller
{
    public function generateQrAsset(Request $request)
    {
        $asset = AssetData::query()
            ->where('is_draft', '0')
            ->where('is_pemutihan', '0')
            ->select('id', 'kode_asset')
            ->get();

        foreach ($asset as $key => $value) {
            if (\File::exists(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'))) {
                \File::delete(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'));
            }

            $qr_name = 'qr-asset-' . $value->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($value->kode_asset, $path);

            $update = AssetData::find($value->id);
            $update->qr_code = $qr_name;
            $update->save();
        }

        return true;
    }
}
