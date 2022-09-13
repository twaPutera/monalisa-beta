<?php

namespace App\Http\Requests\GroupKategoriAsset;

use Illuminate\Foundation\Http\FormRequest;

class GroupKategoriAssetStoreRequest extends FormRequest
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
            'kode_group' => 'required|unique:group_kategori_assets,kode_group',
            'nama_group' => 'required|string|max:255',
        ];
    }
}
