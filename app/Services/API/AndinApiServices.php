<?php

namespace App\Services\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AndinApiServices
{
    public function findAllMemorandum(Request $request)
    {
        $url_andin = config('app.andin_url') . '/api/external/v1/surat-internal/memorandum/index';

        $response_andin = Http::get($url_andin, [
            'search' => $request->keyword,
        ]);

        return $response_andin['data'];
    }
}
