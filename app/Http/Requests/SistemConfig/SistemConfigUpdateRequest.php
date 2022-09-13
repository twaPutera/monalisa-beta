<?php

namespace App\Http\Requests\SistemConfig;

use Illuminate\Foundation\Http\FormRequest;

class SistemConfigUpdateRequest extends FormRequest
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
            'config_name.*' => 'required|string|max:255',
            'value.*' => 'required|string|max:255',
        ];
    }
}
