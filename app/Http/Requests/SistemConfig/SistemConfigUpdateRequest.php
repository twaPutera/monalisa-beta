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
            'config.*' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'config.*.required' => 'This value must not be empty',
            'config.*.string' => 'This value must be string',
            'config.*.max' => 'This value must not be more than 255 characters',
        ];
    }
}
