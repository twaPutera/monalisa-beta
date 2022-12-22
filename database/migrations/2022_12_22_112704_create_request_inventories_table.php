<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('guid_pengaju');
            $table->date('tanggal_pengambilan');
            $table->string('unit_kerja');
            $table->string('no_memo')->nullable();
            $table->string('status', 50);
            $table->text('alasan')->nullable();
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
        Schema::dropIfExists('request_inventories');
    }
}
