<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            LaboratoriumSeeder::class,
            AlatSeeder::class,
            // PeminjamanSeeder::class,
            // PeminjamanDetailAlatSeeder::class,
            // PelaporanKerusakanSeeder::class,
            // PengembalianSeeder::class,
        ]);
    }
}
