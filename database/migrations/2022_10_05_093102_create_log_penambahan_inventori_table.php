<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPenambahanInventoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('detail_inventori_data');
        Schema::create('log_penambahan_inventori', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_inventori');
            $table->foreign('id_inventori')->references('id')->on('inventori_data');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->string('created_by');
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
        Schema::dropIfExists('log_penambahan_inventori');
    }
}
