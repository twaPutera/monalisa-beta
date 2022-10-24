<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\Request;

class UserQueryServices
{
    public function findAll(Request $request)
    {
        $users = User::query();

        if ($request->has('name')) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('keyword')) {
            $users->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('email')) {
            $users->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('username_sso')) {
            $users->where('username_sso', 'like', '%' . $request->username_sso . '%');
        }

        if ($request->has('role')) {
            $users->where('role', $request->role);
        }

        if ($request->has('status')) {
            $users->where('is_active', $request->status);
        }

        return $users->get();
    }

    public function findById($id)
    {
        return User::find($id);
    }
}
