<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Master data seeders
        $this->call([
            TipeSeeder::class,
            StatusSeeder::class,
            ManufakturSeeder::class,
            KategoriSeeder::class,
            DepartemenSeeder::class,
            LokasiSeeder::class,
            BrandSeeder::class,
        ]);
    }
}
