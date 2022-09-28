<?php

namespace App\Services\UserSso;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserSsoQueryServices
{
    public function getUserSso(Request $request)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $siska_url = config('app.siska_url') . '/api/pegawai';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($siska_url, [
            'fields' => 'nama,token_user,email',
            'search' => 'nama:' . $request->keyword,
            'page' => 1,
            'perPage' => 20,
        ]);

        return $response_sso_siska['data']['nodes'];
    }

    public function getUserByGuid(string $guid)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $siska_url = config('app.siska_url') . '/api/pegawai';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($siska_url, [
            'fields' => 'nama,guid,email,no_induk,no_hp',
            'search' => 'token_user:' . $guid,
        ]);

        // dd($response_sso_siska->json());

        return $response_sso_siska['data']['nodes'];
    }

    public function getDataUnit()
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/unit/all';

        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getDataPosition()
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/position/all';
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }
}
