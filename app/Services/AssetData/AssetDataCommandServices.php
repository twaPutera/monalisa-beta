<?php

namespace App\Services\AssetData;

use App\Http\Requests\AssetData\AssetStoreRequest;

class AssetDataCommandServices
{
    public function store(AssetStoreRequest $request)
    {
        $request->validated();
        // * Store Asset

        return $request;
    }
}
