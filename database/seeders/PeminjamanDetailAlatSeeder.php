<?php

namespace Database\Seeders;

use App\Models\PeminjamanDetailAlat;
use Illuminate\Database\Seeder;

class PeminjamanDetailAlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PeminjamanDetailAlat::create([
            'peminjaman_id' => 1,
            'id_alat' => 1,
            'jumlah' => 2,
            'kondisi_saat_pinjam' => 'baik',
            'kondisi_saat_kembali' => 'baik',
        ]);
        PeminjamanDetailAlat::create([
            'peminjaman_id' => 1,
            'id_alat' => 2,
            'jumlah' => 1,
            'kondisi_saat_pinjam' => 'baik',
            'kondisi_saat_kembali' => 'baik',
        ]);
    }
}
