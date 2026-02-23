<?php

namespace Database\Seeders;

use App\Models\Laboratorium;
use Illuminate\Database\Seeder;

class LaboratoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laboratoriums = [
            // PRODI INFORMATIKA
            ['nama_lab' => 'Lab Multimedia', 'deskripsi' => 'Lab untuk praktikum desain, editing, dan multimedia Prodi Informatika.'],
            ['nama_lab' => 'Lab Siber', 'deskripsi' => 'Lab untuk praktikum keamanan siber dan digital forensik.'], 
            
            // PRODI ELEKTRONIKA
            ['nama_lab' => 'Lab 404', 'deskripsi' => 'Laboratorium Prodi Elektronika'],
            ['nama_lab' => 'Lab 403', 'deskripsi' => 'Laboratorium Prodi Elektronika'],
            ['nama_lab' => 'Lab 402', 'deskripsi' => 'Laboratorium Prodi Elektronika'],
            ['nama_lab' => 'Lab 401', 'deskripsi' => 'Laboratorium Prodi Elektronika'],
            
            // PRODI MESIN
            ['nama_lab' => 'Lab Motor Bakar', 'deskripsi' => 'Laboratorium Motor Bakar Prodi Mesin.'],
            ['nama_lab' => 'Lab Praktikum Teknik', 'deskripsi' => 'Laboratorium Praktikum Teknik Prodi Mesin.'],
            
            // PRODI HIDROGRAFI
            ['nama_lab' => 'Lab Processing Data', 'deskripsi' => 'Laboratorium Processing Data Prodi Hidrografi.'],
            ['nama_lab' => 'Lab Kartografi', 'deskripsi' => 'Laboratorium Kartografi Prodi Hidrografi.'],
            ['nama_lab' => 'Lab Topografi Pesisir dan Lingkungan Pantai', 'deskripsi' => 'Laboratorium Topografi Prodi Hidrografi.'],
            
            // UMUM
            ['nama_lab' => 'Lab Bahasa', 'deskripsi' => 'Laboratorium bahasa dan TOEFL.'],
        ];

        foreach ($laboratoriums as $lab) {
            Laboratorium::create([
                'nama_lab' => $lab['nama_lab'],
                'kapasitas' => 30,
                'status' => 'aktif',
                'deskripsi' => $lab['deskripsi'],
            ]);
        }
    }
}
