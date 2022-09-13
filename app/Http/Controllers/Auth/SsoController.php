<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SSO\SSOServices;

class SsoController extends Controller
{
    protected $ssoServices;

    public function __construct(SSOServices $ssoServices)
    {
        $this->ssoServices = $ssoServices;
    }

    public function redirectSso(Request $request)
    {
        return redirect($this->ssoServices->redirectSso($request));
    }

    public function callback(Request $request)
    {
        try {
            $response = $this->ssoServices->generateTokenFromSso($request);

            if ($response) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('sso.redirect');
            }
            //code...
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'message' => 'error',
                'data' => $th->getMessage()
            ], 500);
        }
    }

    public function logoutSso(Request $request)
    {
        $response = $this->ssoServices->logoutSso($request);

        if ($response) {
            return redirect()->route('sso.redirect');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
}
