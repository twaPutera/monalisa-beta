<?php

namespace App\Http\Requests\DetailInventaris;

use Illuminate\Foundation\Http\FormRequest;

class DetailInventarisStoreRequest extends FormRequest
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
            'id_lokasi' => 'required|uuid|exists:lokasis,id',
            'stok' => 'required|numeric',
            'keterangan' => 'required|string|max:255'
        ];
    }
}
