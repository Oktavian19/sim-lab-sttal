<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alat::create([
            'nama_alat' => 'PC Desktop',
            'merk' => 'Dell',
            'tahun_pengadaan' => 2022,
            'lokasi' => 1,
            'status_kondisi' => 'baik',
            'status_ketersediaan' => 'tersedia',
        ]);
        Alat::create([
            'nama_alat' => 'Router Mikrotik',
            'merk' => 'Mikrotik',
            'tahun_pengadaan' => 2021,
            'lokasi' => 2,
            'status_kondisi' => 'baik',
            'status_ketersediaan' => 'tersedia',
        ]);
    }
}
