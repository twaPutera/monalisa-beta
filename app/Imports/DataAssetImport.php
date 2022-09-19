<?php

namespace App\Imports;

use App\Imports\SheetAsset\DataAssetSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataAssetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data Asset' => new DataAssetSheet(),
        ];
    }
}
