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
        $alats = [
            // ================= LAB MULTIMEDIA =================
            ['nama_alat' => 'Desktop PC', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 1, 'jumlah' => 25],
            ['nama_alat' => 'Tool Kit', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 1, 'jumlah' => 1],
            ['nama_alat' => 'Smart Board', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 1, 'jumlah' => 1],

            // ================= LAB SIBER =================
            ['nama_alat' => 'Digital Forensic System', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 2, 'jumlah' => 1],
            ['nama_alat' => 'Personal Computer Trainer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 2, 'jumlah' => 2],
            ['nama_alat' => 'Desktop PC', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 2, 'jumlah' => 10],
            ['nama_alat' => 'Smart TV LED 65"', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 2, 'jumlah' => 1],
            ['nama_alat' => 'Camera Webcam', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 2, 'jumlah' => 1],

            // ================= LAB 404 =================
            ['nama_alat' => 'Microcontroller Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 3, 'jumlah' => 9],
            ['nama_alat' => 'Spectrum Analyzer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 3, 'jumlah' => 2],
            ['nama_alat' => 'Eduscope 3000 / Oscilloscope', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 3, 'jumlah' => 1],
            ['nama_alat' => 'Modul Pembelajaran Digital Aplikasi', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 3, 'jumlah' => 8],
            ['nama_alat' => 'Manual Book Trainer Sensor dan Transduser', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 3, 'jumlah' => 4],
            ['nama_alat' => 'Trainer Sensor dan Transduser', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 3, 'jumlah' => 6],
            ['nama_alat' => 'Modul Microcontroller AT Mega 2560', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 3, 'jumlah' => 12],
            ['nama_alat' => 'Servo Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 3, 'jumlah' => 9],
            ['nama_alat' => 'Microwave Trainer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 3, 'jumlah' => 7],

            // ================= LAB 403 =================
            ['nama_alat' => 'Opto Electronic Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 4, 'jumlah' => 11],
            ['nama_alat' => 'CCTV Trainer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 4, 'jumlah' => 10],
            ['nama_alat' => 'Transformer Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 4, 'jumlah' => 9],
            ['nama_alat' => 'Digital Satelite Receiver', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 4, 'jumlah' => 8],
            ['nama_alat' => 'Trainer Interface', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 4, 'jumlah' => 4],

            // ================= LAB 402 =================
            ['nama_alat' => 'Digital Trainer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 5, 'jumlah' => 9],
            ['nama_alat' => 'Electrical Instalation Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 5, 'jumlah' => 40],

            // ================= LAB 401 =================
            ['nama_alat' => 'Fundamental Electrical Machine Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 6, 'jumlah' => 47],
            ['nama_alat' => 'Power Electrical Trainer', 'merk' => '-', 'tahun_pengadaan' => 2010, 'lokasi' => 6, 'jumlah' => 9],

            // ================= LAB MOTOR BAKAR =================
            ['nama_alat' => 'Electrical Engine Load Test', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 7, 'jumlah' => 1],
            ['nama_alat' => 'Injektor Test and Cleaner', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 7, 'jumlah' => 1],
            ['nama_alat' => 'Diesel Engine Compression Test Kit', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 7, 'jumlah' => 1],
            ['nama_alat' => 'Battery Charger', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 7, 'jumlah' => 1],

            // ================= LAB PRAKTIKUM TEKNIK =================
            ['nama_alat' => 'TIG Electric Welding', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 3],
            ['nama_alat' => 'Jangka Sorong', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 6],
            ['nama_alat' => 'Gerinda', 'merk' => 'Makita / Bosch', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 5],
            ['nama_alat' => 'Bor Tangan', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 4],
            ['nama_alat' => 'Dongkrak 4 Ton', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 3],
            ['nama_alat' => 'Micrometer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 8, 'jumlah' => 6],

            // ================= LAB PROCESSING DATA =================
            ['nama_alat' => 'Komputer i9-12 Ram 64Gb Rom 4TB + SSD 1TB', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 9, 'jumlah' => 7],

            // ================= LAB KARTOGRAFI =================
            ['nama_alat' => 'Komputer i7-11 Ram 64Gb Rom 4TB + SSD 1TB', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 10, 'jumlah' => 12],

            // ================= LAB TOPO PESISIR & PANTAI =================
            ['nama_alat' => 'Robotic Total Station System S7 1"', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 11, 'jumlah' => 1],
            ['nama_alat' => 'Global Navigation Satelite System R12i GNSS Geodetic', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 11, 'jumlah' => 1],
            ['nama_alat' => 'Aerial Photogrammetry With Multispectral Camera VTOL', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 11, 'jumlah' => 1],
            ['nama_alat' => 'Aerial LIDAR Mapper + LIDAR Sensor with GNSS Module', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 11, 'jumlah' => 1],
            ['nama_alat' => 'Software Topographic Reconnaissance Survey', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 11, 'jumlah' => 1],

            // ================= LAB BAHASA =================
            ['nama_alat' => 'Komputer', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 12, 'jumlah' => 25],
            ['nama_alat' => 'Headset', 'merk' => '-', 'tahun_pengadaan' => 2020, 'lokasi' => 12, 'jumlah' => 25],
        ];

        foreach ($alats as $alat) {
            Alat::create($alat);
        }
    }
}
