<?php

namespace App\Http\Requests\InventarisData;

use Illuminate\Foundation\Http\FormRequest;

class InventarisDataStoreRequest extends FormRequest
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
            'id_kategori_inventori' => 'required|uuid|exists:kategori_inventories,id',
            'id_satuan_inventori' => 'required|uuid|exists:satuan_inventories,id',
            'kode_inventori' => 'required|string|max:50|unique:inventori_data,kode_inventori',
            'nama_inventori' => 'required|string|max:255',
            'stok' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'deskripsi_inventori' => 'nullable|string|max:255',
        ];
    }
}
