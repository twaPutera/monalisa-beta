<?php

namespace App\Http\Requests\AssetOpname;

use Illuminate\Foundation\Http\FormRequest;

class AssetOpnameStoreRequest extends FormRequest
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
            'tanggal_opname' => 'required|date',
            'tanggal_services' => 'required|date',
            'status_kondisi' => 'required|string|max:50',
            'status_akunting' => 'required|string|max:100',
            'gambar_asset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'catatan' => 'nullable|string|max:255',
        ];
    }
}
