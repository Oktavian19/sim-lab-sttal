<?php

namespace Database\Seeders;

use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengembalianSeeder extends Seeder
{
    public function run(): void
    {
        Pengembalian::create([
            'peminjaman_id' => 2,
            'id_petugas' => 1,
            'tanggal_kembali_realisasi' => Carbon::now(),
            'status_pengembalian' => 'tepat_waktu',
            'catatan' => 'Dikembalikan dalam kondisi baik',
        ]);
    }
}
