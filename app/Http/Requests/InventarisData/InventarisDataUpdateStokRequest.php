<?php

namespace App\Http\Requests\InventarisData;

use Illuminate\Foundation\Http\FormRequest;

class InventarisDataUpdateStokRequest extends FormRequest
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
            'jumlah_saat_ini' => 'required|numeric',
            'no_memo' => 'nullable|string',
            'tanggal' => 'required|date',
        ];
    }
}
