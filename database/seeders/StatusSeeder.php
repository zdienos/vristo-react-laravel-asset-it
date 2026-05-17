<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_status')->insert([
            ['nama_status' => 'Dipakai', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Ready', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Diperbaiki', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Rusak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Dijual', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
