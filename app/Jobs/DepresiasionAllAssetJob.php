<?php

namespace App\Jobs;

use App\Models\AssetData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\DepresiasiHelpers;
use Illuminate\Support\Facades\DB;

class DepresiasionAllAssetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assets = DepresiasiHelpers::getDataAssetDepresiasi();

        foreach ($assets as $asset) {
            try {
                DB::beginTransaction();
                $data = DepresiasiHelpers::depresiasiAsset($asset, date('Y-m-d'));
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                logger('Error Depresiasi Asset: ' . $asset->id, [$th->getMessage()]);
            }
        }

        logger('Depresiasi Berjalan pada Tanggal ' . date('Y-m-d'), []);
    }
}
