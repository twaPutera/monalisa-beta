<?php

namespace App\Http\Requests\PeminjamanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanAssetStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
            'alasan_peminjaman' => 'required|string',
            'id_jenis_asset' => 'required|array',
            'id_jenis_asset.*' => 'required|uuid',
            'jumlah.*' => 'required|numeric|min:1',
        ];
    }
}
