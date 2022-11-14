<?php

namespace App\Http\Requests\Pengaduan;

use Illuminate\Foundation\Http\FormRequest;

class AssetPengaduanStoreRequest extends FormRequest
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
            'tanggal_pengaduan' => 'required|date',
            'prioritas' => 'required|in:high,medium,low',
            'alasan_pengaduan' => 'required|max:255|string',
            'file_asset_service' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
