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
            'id_jenis_penambahan' => 'required|in:baru',
            'tanggal' => 'required|date',
            'id_kategori_inventori' => 'required|uuid|exists:kategori_inventories,id',
            'id_satuan_inventori' => 'required|uuid|exists:satuan_inventories,id',
            'kode_inventori' => 'required|string|max:50|unique:inventori_data,kode_inventori',
            'nama_inventori' => 'required|string|max:255',
            'stok' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'deskripsi_inventori' => 'nullable|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'id_kategori_inventori' => 'Id kategori bahan habis pakai',
            'id_satuan_inventori' => 'Id satuan bahan habis pakai',
            'kode_inventori' => 'Jenis bahan habis pakai',
            'nama_inventori' => 'Merk bahan habis pakai',
        ];
    }
}
