<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelasBaseSeeder extends Seeder
{
    public function run(): void
    {
        $sql = file_get_contents(base_path('base_scripts.sql'));
        DB::unprepared($sql);
    }
}
