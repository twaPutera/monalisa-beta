<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPeminjamanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_peminjaman_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_peminjaman_asset')->references('id')->on('peminjaman_assets');
            $table->uuid('id_kategori_asset')->references('id')->on('kategori_assets');
            $table->integer('jumlah')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_peminjaman_assets');
    }
}
