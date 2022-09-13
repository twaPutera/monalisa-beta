<?php

namespace App\Http\Requests\SatuanInventori;

use Illuminate\Foundation\Http\FormRequest;

class SatuanInventoriStoreRequest extends FormRequest
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
            'kode_satuan' => 'required|string|max:255|unique:satuan_inventories,kode_satuan',
            'nama_satuan' => 'required|string|max:255',
        ];
    }
}
