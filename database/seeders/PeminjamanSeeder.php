<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peminjaman::create([
            'id_peminjam' => 3,
            'id_lab' => 1,
            'start_time' => Carbon::yesterday()->setHour(9)->setMinute(0),
            'end_time' => Carbon::yesterday()->setHour(11)->setMinute(0),
            'kegiatan' => 'Praktikum Pemrograman Web',
            'jumlah_peserta' => 30,
            'status_pengajuan' => 'disetujui',
            'catatan_admin' => 'Silakan gunakan lab sesuai prosedur.',
        ]);

        Peminjaman::create([
            'id_peminjam' => 3,
            'id_lab' => 2,
            'start_time' => Carbon::yesterday()->setHour(9)->setMinute(0),
            'end_time' => Carbon::yesterday()->addDay(1)->setHour(11)->setMinute(0),
            'kegiatan' => 'Penelitian Skripsi',
            'jumlah_peserta' => 5,
            'status_pengajuan' => 'pending',
            'catatan_admin' => null,
        ]);
    }
}
