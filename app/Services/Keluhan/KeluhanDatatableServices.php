<?php

namespace App\Services\Keluhan;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\UserSso\UserSsoQueryServices;

class KeluhanDatatableServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }
    public function datatable(Request $request)
    {
        $query = Pengaduan::query();
        $query->with(['asset_data', 'asset_data.lokasi', 'image', 'lokasi']);
        $query->orderBy('tanggal_pengaduan', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_keluhan', function ($item) {
                return !empty($item->tanggal_pengaduan) ? $item->tanggal_pengaduan : '-';
            })
            ->addColumn('nama_asset', function ($item) {
                return !empty($item->asset_data->deskripsi) ? $item->asset_data->deskripsi : '-';
            })
            ->addColumn('lokasi_asset', function ($item) {
                if ($item->asset_data != null) {
                    return !empty($item->asset_data->lokasi->nama_lokasi) ? $item->asset_data->lokasi->nama_lokasi : '-';
                }
                return !empty($item->lokasi->nama_lokasi) ? $item->lokasi->nama_lokasi : '-';
            })
            ->addColumn('catatan_pengaduan', function ($item) {
                return !empty($item->catatan_pengaduan) ? $item->catatan_pengaduan : '-';
            })
            ->addColumn('created_by_name', function ($item) {
                $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                return isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            })
            ->addColumn('gambar_pengaduan', function ($item) {
                $data = '';
                $data .= '<button type="button" onclick="showKeluhanImage(this)"';
                $data .= 'data-url_detail="' . route('admin.keluhan.get-image', $item->id) . '"';
                $data .= 'class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $data;
            })
            ->addColumn('status_pengaduan', function ($item) {
                return !empty($item->status_pengaduan) ? $item->status_pengaduan : '-';
            })
            ->addColumn('catatan_admin', function ($item) {
                return !empty($item->catatan_admin) ? $item->catatan_admin : '-';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status_pengaduan != 'selesai') {
                    $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.keluhan.edit', $item->id) . '" data-url_update="' . route('admin.keluhan.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>';
                } else {
                    $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.keluhan.edit', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                    <i class="fa fa-eye"></i>
                                </button>';
                }
                return $element;
            })
            ->rawColumns(['action', 'gambar_pengaduan'])
            ->make(true);
    }
}
