<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_tipe')->insert([
            ['nama_tipe' => 'Aset', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipe' => 'Asesoris', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipe' => 'Inventori', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipe' => 'Komponen', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
