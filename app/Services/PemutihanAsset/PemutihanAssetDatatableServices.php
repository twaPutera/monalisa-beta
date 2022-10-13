<?php

namespace App\Services\PemutihanAsset;

use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Models\PemutihanAsset;
use Yajra\DataTables\DataTables;
use App\Models\DetailPemutihanAsset;
use App\Services\UserSso\UserSsoQueryServices;

class PemutihanAssetDatatableServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }
    public $assetInPemutihan = [];
    public function datatable(Request $request)
    {
        $query = PemutihanAsset::query();
        $query->orderBy('created_at', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('keterangan', function ($item) {
                return empty($item->keterangan) ? 'Tidak Ada' : $item->keterangan;
            })
            ->addColumn('tanggal', function ($item) {
                return empty($item->tanggal) ? 'Tidak Ada' : $item->tanggal;
            })
            ->addColumn('no_memo', function ($item) {
                return empty($item->no_memo) ? 'Tidak Ada' : $item->no_memo;
            })
            ->addColumn('status', function ($item) {
                return empty($item->status) ? 'Tidak Ada' : $item->status;
            })
            ->addColumn('created_by', function ($item) {
                $user = null;
                if (isset($item->created_by)) {
                    $user = $this->userSsoQueryServices->getUserByGuid($item->created_by);
                }
                return $user == null ? 'Tidak ada' : $user[0]['nama'];
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status == 'Draft') {
                    $element .= '<form action="' . route('admin.pemutihan-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<a href="' . route('admin.pemutihan-asset.edit', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                } else {
                    $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.pemutihan-asset.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                    <i class="fa fa-eye"></i>
                    </button>';
                }
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function datatableAsset(Request $request)
    {
        $query = AssetData::query();
        $query->orderBy('created_at', 'desc');

        if (isset($request->id_pemutihan)) {
            $pemutihan = PemutihanAsset::with(['detail_pemutihan_asset'])->findOrFail($request->id_pemutihan);
            foreach ($pemutihan->detail_pemutihan_asset as $item) {
                array_push($this->assetInPemutihan, $item->id_asset_data);
            }
        } else {
            $this->assetInPemutihan = [];
        }
        if (isset($request->status_kondisi)) {
            if ($request->status_kondisi != "semua") {
                $query->where('status_kondisi', $request->status_kondisi);
            }
        }

        if (isset($request->jenis)) {
            $query->where('id_kategori_asset', $request->jenis);
        }
        $query->where('is_pemutihan', 0);
        return DataTables::of($query)
            ->addColumn('id', function ($item) {
                if (in_array($item->id, $this->assetInPemutihan)) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $data = '';
                $data .= '<div class="form-check text-center">
                            <input type="checkbox" class="form-check-input check-item" value="' . $item->id . '"  name="id_checkbox[]" multiple id="exampleCheck1" ' . $checked . '>
                            <label class="form-check-label" for="exampleCheck1"></label>
                         </div>';
                return $data;
            })
            ->addColumn('kode_asset', function ($item) {
                return empty($item->kode_asset) ? 'Tidak Ada' : $item->kode_asset;
            })
            ->addColumn('jenis_asset', function ($item) {
                return empty($item->kategori_asset->nama_kategori) ? 'Tidak Ada' : $item->kategori_asset->nama_kategori;
            })
            ->addColumn('lokasi_asset', function ($item) {
                return empty($item->lokasi->nama_lokasi) ? 'Tidak Ada' : $item->lokasi->nama_lokasi;
            })
            ->addColumn('kondisi_asset', function ($item) {
                return empty($item->status_kondisi) ? 'Tidak Ada' : $item->status_kondisi;
            })
            ->rawColumns(['id'])
            ->make(true);
    }

    public function datatableDetail(Request $request)
    {
        $query = DetailPemutihanAsset::query();
        $query->with(['asset_data']);
        $query->where('id_pemutihan_asset', $request->id_pemutihan_detail);
        $query->orderBy('created_at', 'desc');
        return DataTables::of($query)
            ->addColumn('file_gambar', function ($item) {
                $data = '';
                $data .= '<button type="button" onclick="showPemutihanAsset(this)"';
                $data .= 'data-url_detail="' . route('admin.pemutihan-asset.edit.listing-asset.get-image', $item->id) . '"';
                $data .= 'class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $data;
            })
            ->addColumn('kode_asset', function ($item) {
                return empty($item->asset_data->kode_asset) ? 'Tidak Ada' : $item->asset_data->kode_asset;
            })
            ->addColumn('jenis_asset', function ($item) {
                return empty($item->asset_data->kategori_asset->nama_kategori) ? 'Tidak Ada' : $item->asset_data->kategori_asset->nama_kategori;
            })
            ->addColumn('lokasi_asset', function ($item) {
                return empty($item->asset_data->lokasi->nama_lokasi) ? 'Tidak Ada' : $item->asset_data->lokasi->nama_lokasi;
            })
            ->addColumn('kondisi_asset', function ($item) {
                return empty($item->asset_data->status_kondisi) ? 'Tidak Ada' : $item->asset_data->status_kondisi;
            })
            ->addColumn('keterangan_pemutihan', function ($item) {
                return empty($item->keterangan_pemutihan) ? 'Tidak Ada' : $item->keterangan_pemutihan;
            })
            ->rawColumns(['file_gambar'])
            ->make(true);
    }
}
