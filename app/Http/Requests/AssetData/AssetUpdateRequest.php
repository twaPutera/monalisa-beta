<?php

namespace App\Http\Requests\AssetData;

use Illuminate\Foundation\Http\FormRequest;

class AssetUpdateRequest extends FormRequest
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
            // 'kode_asset' => 'required|string|unique:asset_data,kode_asset|max:255',
            'id_vendor' => 'nullable|uuid|exists:vendors,id',
            'id_lokasi' => 'nullable|uuid|exists:lokasis,id',
            // 'id_kelas_asset' => 'required|uuid|exists:kelas_assets,id',
            // 'id_kategori_asset' => 'required|uuid|exists:kategori_assets,id',
            'id_satuan_asset' => 'required|uuid|exists:satuan_assets,id',
            'deskripsi' => 'required|string|max:255',
            // 'tanggal_perolehan' => 'required|date',
            // 'nilai_perolehan' => 'required|numeric',
            'jenis_penerimaan' => 'required|string|max:255',
            // 'ownership' => 'nullable|uuid',
            // 'tgl_register' => 'required|date|date_format:Y-m-d',
            // 'register_oleh' => 'required|uuid',
            'no_memo_surat' => 'nullable|string|max:50',
            'no_po' => 'nullable|string|max:50',
            'no_sp3' => 'nullable|string|max:50',
            // 'status_kondisi' => 'required|string|max:50',
            'no_seri' => 'required|string|max:50',
            'spesifikasi' => 'required|string',
            // 'nilai_depresiasi' => 'required|numeric',
            // 'umur_manfaat_fisikal' => 'nullable|numeric',
            // 'umur_manfaat_komersial' => 'nullable|numeric',
            'gambar_asset' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
