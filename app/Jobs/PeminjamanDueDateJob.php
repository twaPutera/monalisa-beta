<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PeminjamanAsset;

class PeminjamanDueDateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_peminjaman;
    protected $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_peminjaman, $date)
    {
        $this->id_peminjaman = $id_peminjaman;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $peminjaman = PeminjamanAsset::find($this->id_peminjaman);
        $tanggal_pengembalian = $peminjaman->tanggal_pengembalian . ' ' . $peminjaman->jam_selesai;
        if ($this->date != $tanggal_pengembalian) {
            $peminjaman->status = 'duedate';
            $peminjaman->save();
        }
    }
}
