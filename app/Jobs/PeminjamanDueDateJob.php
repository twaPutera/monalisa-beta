<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\PeminjamanAsset;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PeminjamanDueDateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_peminjaman;
    protected $date;

    /**
     * Create a new job instance.
     *
     * @param mixed $id_peminjaman
     * @param mixed $date
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
