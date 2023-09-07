<?php

namespace App\Jobs;

use App\Models\ZipFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteZipFileJob implements ShouldQueue
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
        $files = ZipFile::query()->get();

        foreach ($files as $file) {
            if (\File::exists(storage_path($file->path))) {
                // Delete the file
                \File::delete(storage_path($file->path));
            }
            // Delete the record
            $file->delete();
        }

        logger("Deleted all zip files at " . date("Y-m-d H:i:s"), []);
    }
}
