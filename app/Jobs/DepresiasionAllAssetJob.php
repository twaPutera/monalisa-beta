<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Helpers\DepresiasiHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        logger(__METHOD__, ['handle']);
        return; // DEBUG

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
