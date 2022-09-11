<?php

namespace App\Http\Requests\KategoriAsset;

use Illuminate\Foundation\Http\FormRequest;

class KategoriAssetStoreRequest extends FormRequest
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
            'kode_kategori' => 'required|string|max:255|unique:kategori_assets,kode_kategori',
            'nama_kategori' => 'required|string|max:255',
        ];
    }
}
