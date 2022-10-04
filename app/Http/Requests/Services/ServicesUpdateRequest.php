<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServicesUpdateRequest extends FormRequest
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
            'id_lokasi' => 'nullable|uuid|exists:lokasis,id',
            'id_asset' => 'required|uuid|exists:asset_data,id',
            'tanggal_mulai_service' => 'required|date|date_format:Y-m-d',
            'tanggal_selesai_service' => 'required_if:status_service,selesai',
            'permasalahan' => 'required|string|max:255',
            'id_kategori_service' => 'required|uuid|exists:kategori_services,id',
            'tindakan' => 'required|string|max:255',
            'catatan' => 'required|string|max:255',
            'status_service' => 'required|in:onprogress,backlog,selesai',
            'status_kondisi' => 'required|in:baik,rusak',
            'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
