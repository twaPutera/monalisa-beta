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
        logger('id: ', [$this->id]);
        return [
            'kode_asset' => 'required_if:status_kondisi,draft|string|unique:asset_data,kode_asset,' . $this->id,
            'id_vendor' => 'nullable|uuid|exists:vendors,id',
            'id_lokasi' => 'nullable|uuid|exists:lokasis,id',
            'id_kelas_asset' => 'required_if:status_kondisi,draft|uuid|exists:kelas_assets,id',
            'id_kategori_asset' => 'required_if:status_kondisi,draft|uuid|exists:kategori_assets,id',
            'id_satuan_asset' => 'required|uuid|exists:satuan_assets,id',
            'deskripsi' => 'required|string|max:255',
            'tanggal_perolehan' => 'required_if:status_kondisi,draft|date',
            'nilai_perolehan' => 'required_if:status_kondisi,draft|numeric',
            'jenis_penerimaan' => 'required|string|max:255',
            'ownership' => 'nullable|uuid',
            // 'tgl_register' => 'required_if:status_kondisi,draft|date|date_format:Y-m-d',
            // 'register_oleh' => 'required_if:status_kondisi,draft|uuid',
            'no_memo_surat' => 'nullable|string|max:50',
            'id_surat_memo_andin' => 'nullable|uuid',
            'no_po' => 'nullable|string|max:50',
            'no_sp3' => 'nullable|string|max:50',
            'status_kondisi' => 'required|string|max:50',
            'no_seri' => 'nullable|string|max:50',
            'no_urut' => 'nullable|string|max:50',
            'cost_center' => 'required|string|max:255',
            'spesifikasi' => 'required|string',
            // 'nilai_depresiasi' => 'required_if:status_kondisi,draft|numeric',
            // 'umur_manfaat_fisikal' => 'nullable|numeric',
            // 'umur_manfaat_komersial' => 'nullable|numeric',
            'is_sparepart' => 'nullable|in:0,1',
            'is_pinjam' => 'nullable|in:0,1',
            'gambar_asset' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
