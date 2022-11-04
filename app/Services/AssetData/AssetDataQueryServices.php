<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelpers;
use App\Models\PeminjamanAsset;
use App\Models\GroupKategoriAsset;
use App\Models\DetailPemindahanAsset;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class AssetDataQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function findById(string $id, array $request = [])
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset.group_kategori_asset', 'image', 'detail_service', 'log_asset_opname'])
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
        $created_by = null;
        if (isset($data->ownership)) {
            if (config('app.sso_siska')) {
                $user = $this->userSsoQueryServices->findById($data->ownership);
                $user = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($data->ownership);
            }
        }

        if ($data->log_asset_opname->count() > 0) {
            if (config('app.sso_siska')) {
                $created_by = $this->userSsoQueryServices->getUserByGuid($data->log_asset_opname->sortByDesc('created_at')->first()->created_by);
                $created_by = isset($created_by[0]) ? collect($created_by[0]) : null;
            } else {
                $created_by = $this->userQueryServices->findById($data->log_asset_opname->sortByDesc('created_at')->first()->created_by);
            }
        }
        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.image.preview') . '?filename=' . $item->path;
            return $item;
        });

        if (isset($request['peminjaman'])) {
            $peminjaman = PeminjamanAsset::query()
                ->wherehas('detail_peminjaman_asset', function ($query) use ($id) {
                    $query->where('id_asset', $id);
                })
                ->where('status', 'diproses')
                ->first();
            $data->peminjam = null;
            if (isset($peminjaman)) {
                $peminjam = json_decode($peminjaman->json_peminjam_asset);
                $data->peminjam = $peminjam;
            }
        }

        $data->link_detail = route('admin.listing-asset.detail', $data->id);
        $data->owner_name = $user == null ? 'Tidak ada' : $user->name ?? $user->nama;
        $data->created_by_opname = $created_by == null ? 'Tidak Ada' : $created_by->name ?? $created_by->nama;
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

        $data->where('is_pemutihan', 0); //To get all data asset is not pemutihan
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

    public function lastUpdateAsset()
    {
        $data = AssetData::query()
            ->max('updated_at');

        return $data;
    }

    public function getValueAsset()
    {
        $nilai_beli_asset = AssetData::query()
            ->where('is_pemutihan', '0')
            ->sum('nilai_perolehan');

        $nilai_value_asset = AssetData::query()
            ->where('is_pemutihan', '0')
            ->sum('nilai_buku_asset');

        $nilai_depresiasi = $nilai_beli_asset - $nilai_value_asset;

        return [
            'nilai_beli_asset' => $nilai_beli_asset,
            'nilai_value_asset' => $nilai_value_asset,
            'nilai_depresiasi' => $nilai_depresiasi,
        ];
    }

    public function getDataChartSummaryAssetByGroup(Request $request)
    {
        $data = [];
        $group_kategori_asset = GroupKategoriAsset::query()
            ->select([
                'id',
                'nama_group',
            ])
            ->get();

        foreach ($group_kategori_asset as $item) {
            $count_asset = AssetData::query()
                ->whereHas('kategori_asset', function ($query) use ($item) {
                    $query->where('id_group_kategori_asset', $item->id);
                })
                ->where('is_pemutihan', '0')
                ->count();

            $data[] = [
                'name' => $item->nama_group,
                'value' => $count_asset,
            ];
        }

        return $data;
    }

    public function getDataChartSummaryAssetByStatus(Request $request)
    {
        $status = ['bagus', 'rusak', 'maintenance', 'tidak-lengkap'];
        $data = [];
        foreach ($status as $item) {
            $count_asset = AssetData::query()
                ->where('status_kondisi', $item)
                ->where('is_pemutihan', '0')
                ->count();

            $data[] = [
                'name' => $item,
                'value' => $count_asset,
            ];
        }

        return $data;
    }

    public function getDataChartSummaryAssetByMonthRegister(Request $request)
    {
        $data = [];
        $month = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Ags',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];

        foreach ($month as $key => $item) {
            $count_asset = AssetData::query()
                ->whereMonth('tgl_register', $key + 1)
                ->whereYear('tgl_register', date('Y'))
                ->where('is_pemutihan', '0')
                ->count();

            $data['name'][] = $item;
            $data['value'][] = $count_asset;
        }

        return $data;
    }
}
