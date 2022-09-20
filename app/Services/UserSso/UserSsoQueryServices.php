<?php

namespace App\Services\UserSso;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserSsoQueryServices
{
    public function getUserSso(Request $request)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $sso_siska_url = config('app.sso_siska_url') . '/api/users';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($sso_siska_url, [
            'fields' => 'name,guid,email',
            'search' => 'type:staff,name:' . $request->keyword,
            'page' => 1,
            'perPage' => 10,
        ]);

        return $response_sso_siska['data']['nodes'];
    }

    public function getUserByGuid(string $guid)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $sso_siska_url = config('app.sso_siska_url') . '/api/users';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($sso_siska_url, [
            'fields' => 'name,guid,email',
            'search' => 'guid:' . $guid,
        ]);

        return $response_sso_siska['data']['nodes'];
    }
}
