<?php

namespace App\Services\InventarisData;

use App\Helpers\SsoHelpers;
use App\Models\InventoriData;
use App\Models\LogPenambahanInventori;
use App\Models\LogPenguranganInventori;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateStokRequest;
use App\Http\Requests\InventarisData\InventarisDataStoreUpdateRequest;
use App\Http\Requests\InventarisData\UserRequestInventoriStoreRequest;
use App\Http\Requests\InventarisData\UserRequestInventoriUpdateRequest;
use App\Models\Approval;
use App\Models\DetailRequestInventori;
use App\Models\LogRequestInventori;
use App\Models\RequestInventori;

class InventarisDataCommandServices
{
    public function store(InventarisDataStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = new InventoriData();
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_saat_ini = $request->stok;
        $inventori_data->jumlah_sebelumnya = $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

        return $inventori_data;
    }
    public function storeFromUser(UserRequestInventoriStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $request_inventaris = new RequestInventori();
        $request_inventaris->guid_pengaju = config('app.sso_siska') ? $user->guid : $user->id;
        $request_inventaris->tanggal_pengambilan = $request->tanggal_pengambilan;
        $request_inventaris->unit_kerja = $request->unit_kerja;
        $request_inventaris->no_memo = $request->no_memo;
        $request_inventaris->status = "pending";
        $request_inventaris->kode_request = self::generateCode();
        $request_inventaris->alasan = $request->alasan_permintaan;
        $request_inventaris->save();

        foreach ($request->id_bahan_habis_pakai as $id_bahan_habis_pakai) {
            $request_kategori_detail = $request->data_bahan_habis_pakai[$id_bahan_habis_pakai];
            $detail_request_inventaris = new DetailRequestInventori();
            $detail_request_inventaris->request_inventori_id = $request_inventaris->id;
            $detail_request_inventaris->inventori_id = $id_bahan_habis_pakai;
            $detail_request_inventaris->qty = $request_kategori_detail['jumlah'];
            $detail_request_inventaris->save();
        }
        $message = "Permintaan bahan habis pakai baru dengan kode " . $request_inventaris->kode_request . " dibuat oleh " . $user->name;
        $this->storeLogRequestInventori($request_inventaris->id, $message, "pending");

        $approval = new Approval();
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($request_inventaris);
        $approval->approvable_id = $request_inventaris->id;
        $approval->save();

        return $request_inventaris;
    }

    public function updateFromUser(UserRequestInventoriUpdateRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $request_inventaris = RequestInventori::find($id);
        $request_inventaris->guid_pengaju = config('app.sso_siska') ? $user->guid : $user->id;
        $request_inventaris->tanggal_pengambilan = $request->tanggal_pengambilan;
        $request_inventaris->unit_kerja = $request->unit_kerja;
        $request_inventaris->no_memo = $request->no_memo;
        $request_inventaris->status = "pending";
        $request_inventaris->alasan = $request->alasan_permintaan;
        $request_inventaris->save();

        foreach ($request_inventaris->detail_request_inventori as $item) {
            $item->delete();
        }

        foreach ($request->id_bahan_habis_pakai as $id_bahan_habis_pakai) {
            $request_kategori_detail = $request->data_bahan_habis_pakai[$id_bahan_habis_pakai];
            $detail_request_inventaris = new DetailRequestInventori();
            $detail_request_inventaris->request_inventori_id = $request_inventaris->id;
            $detail_request_inventaris->inventori_id = $id_bahan_habis_pakai;
            $detail_request_inventaris->qty = $request_kategori_detail['jumlah'];
            $detail_request_inventaris->save();
        }
        $message = "Permintaan bahan habis pakai dengan kode " . $request_inventaris->kode_request . " berhasil diperbaharui oleh " . $user->name;
        $this->storeLogRequestInventori($request_inventaris->id, $message, "pending");

        $approval = new Approval();
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($request_inventaris);
        $approval->approvable_id = $request_inventaris->id;
        $approval->save();

        return $request_inventaris;
    }

    public function generateCode()
    {
        $code = 'RBHP-' . date('Ymd') . '-' . rand(1000, 9999);
        $check_code = RequestInventori::where('kode_request', $code)->first();

        if ($check_code) {
            return self::generateCode();
        }

        return $code;
    }

    public function storeLogRequestInventori($request_inventori_id, $message, $status)
    {
        $user = SsoHelpers::getUserLogin();

        $log = new LogRequestInventori();
        $log->request_inventori_id = $request_inventori_id;
        $log->message = $message;
        $log->status = $status;
        $log->created_by = $user->name;
        $log->save();
    }
    public function storeUpdate(InventarisDataStoreUpdateRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($request->id_inventaris);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
        $inventori_data->jumlah_saat_ini = $inventori_data->jumlah_saat_ini + $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

        return $inventori_data;
    }

    public function update(string $id, InventarisDataUpdateRequest $request)
    {
        $request->validated();

        $inventori_data = InventoriData::findOrFail($id);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function updateStok(string $id, InventarisDataUpdateStokRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($id);
        $selisih = $inventori_data->jumlah_saat_ini - $request->jumlah_keluar;
        if ($selisih >= 0) {
            $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
            $inventori_data->jumlah_saat_ini = $selisih;
            $inventori_data->save();

            $detailInventori = new LogPenguranganInventori();
            $detailInventori->id_inventori = $id;
            $detailInventori->id_surat_memo_andin = $request->id_surat_memo_andin;
            $detailInventori->no_memo = $request->no_memo;
            $detailInventori->jumlah = $request->jumlah_keluar;
            $detailInventori->tanggal = $request->tanggal;
            $detailInventori->created_by = $user->name;
            $detailInventori->save();
            return $inventori_data;
        }
        return false;
    }
}
