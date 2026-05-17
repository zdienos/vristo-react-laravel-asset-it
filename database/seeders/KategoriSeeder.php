<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Tipe 1: Aset
        DB::table('tb_kategori')->insert([
            ['nama_kategori' => 'Laptop', 'id_tipe' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Monitor', 'id_tipe' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'PC Desktop', 'id_tipe' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Printer', 'id_tipe' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Server', 'id_tipe' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tipe 2: Asesoris
        DB::table('tb_kategori')->insert([
            ['nama_kategori' => 'Mouse', 'id_tipe' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Keyboard', 'id_tipe' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Headset', 'id_tipe' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Webcam', 'id_tipe' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tipe 3: Inventori
        DB::table('tb_kategori')->insert([
            ['nama_kategori' => 'Kabel LAN', 'id_tipe' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Toner', 'id_tipe' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kertas', 'id_tipe' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tipe 4: Komponen
        DB::table('tb_kategori')->insert([
            ['nama_kategori' => 'RAM', 'id_tipe' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'HDD/SSD', 'id_tipe' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'VGA', 'id_tipe' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
