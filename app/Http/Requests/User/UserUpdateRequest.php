<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'username_sso' => 'required|string|max:255|unique:users,username_sso,' . $this->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|max:50|in:user,admin,manager,staff',
            'status' => 'nullable|string|max:50|in:1,0',
        ];
    }
}
