<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\API\AndinApiServices;

class AndinApiController extends Controller
{
    protected $andinApiServices;

    public function __construct(AndinApiServices $andinApiServices)
    {
        $this->andinApiServices = $andinApiServices;
    }

    public function findAllMemorandum(Request $request)
    {
        try {
            $data = collect($this->andinApiServices->findAllMemorandum($request))->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'text' => $item['nomor_surat'],
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data['data'] ?? [],
                'debug' => [
                    'status' => $data->status(),
                    'body' => $data->body(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
