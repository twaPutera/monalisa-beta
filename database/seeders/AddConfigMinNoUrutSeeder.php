<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SistemConfig;

class AddConfigMinNoUrutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = new SistemConfig();
        $config->config = 'min_no_urut';
        $config->value = '5';
        $config->config_name = 'Minimum Nomor Urut';
        $config->save();
    }
}
