<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemutihanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemutihan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guid_manager');
            $table->uuid('guid_atasan');
            $table->text('json_manager');
            $table->text('json_atasan');
            $table->date('tanggal');
            $table->string('status');
            $table->string('created_by', 50);
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
        Schema::dropIfExists('pemutihan_assets');
    }
}
