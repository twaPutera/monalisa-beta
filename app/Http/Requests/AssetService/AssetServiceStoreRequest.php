<?php

namespace App\Http\Requests\AssetService;

use Illuminate\Foundation\Http\FormRequest;

class AssetServiceStoreRequest extends FormRequest
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
            'deskripsi_service' => 'required|string|max:255',
            'tanggal_mulai_service' => 'required|date|date_format:Y-m-d',
            'id_kategori_service' => 'required|uuid|exists:kategori_services,id',
            'kondisi_sebelum' => 'required|string|max:255',
        ];
    }
}
