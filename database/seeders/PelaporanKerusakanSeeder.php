<?php

namespace Database\Seeders;

use App\Models\PelaporanKerusakan;
use Illuminate\Database\Seeder;

class PelaporanKerusakanSeeder extends Seeder
{
    public function run(): void
    {
        PelaporanKerusakan::create([
            'id_alat' => 2,
            'id_pelapor' => 3,
            'tanggal_lapor' => now(),
            'deskripsi_kerusakan' => 'Layar multimeter tidak menyala',
            'foto_bukti' => 'kerusakan/router.jpg',
            'status_tindak_lanjut' => 'menunggu',
            'keterangan_perbaikan' => null,
        ]);
    }
}
