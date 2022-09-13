<?php

namespace App\Services\SistemConfig;

use App\Models\SistemConfig;
use App\Http\Requests\SistemConfig\SistemConfigUpdateRequest;

class SistemConfigCommandServices
{
    public function update(SistemConfigUpdateRequest $request)
    {
        return $request;
        $request->validated();
        // $configs = $request->all();
        // foreach ($configs['config'] as $key => $config_name) {
        //     $config = SistemConfig::where('config_name', $config_name)->first();
        //     if ($config) {
        //         $config->value = $configs['value'][$key];
        //         $config->save();
        //     }
        // }
    }
}
