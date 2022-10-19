<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginStoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthCommandServices
{
    public function login(LoginStoreRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return true;
        }

        return false;
    }
}
