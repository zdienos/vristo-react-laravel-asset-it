<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufakturSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_manufaktur')->insert([
            ['nama_manufaktur' => 'Apple', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Samsung', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Dell', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'HP', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Lenovo', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Asus', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Logitech', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Canon', 'created_at' => now(), 'updated_at' => now()],
            ['nama_manufaktur' => 'Epson', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
