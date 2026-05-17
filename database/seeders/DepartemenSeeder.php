<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_departemen')->insert([
            ['nama_departemen' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'HRD', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Marketing', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Operasional', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'General Affair', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
