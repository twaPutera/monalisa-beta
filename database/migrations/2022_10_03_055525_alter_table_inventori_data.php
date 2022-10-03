<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInventoriData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventori_data', function (Blueprint $table) {
            $table->dropColumn(['stok']);
        });

        Schema::table('inventori_data', function (Blueprint $table) {
            $table->decimal('harga_beli', 12, 2);
            $table->integer('jumlah_sebelumnya');
            $table->integer('jumlah_saat_ini');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
