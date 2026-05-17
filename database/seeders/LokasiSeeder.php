<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_lokasi')->insert([
            ['nama_lokasi' => 'Kantor Pusat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Kantor Cabang 1', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Gudang', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Lab IT', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
