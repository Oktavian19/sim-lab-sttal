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
        Laboratorium::create([
            'nama_lab' => 'Laboratorium Informatika',
            'kapasitas' => 30,
            'status' => 'aktif',
            'deskripsi' => 'Digunakan untuk praktikum pemrograman dan jaringan',
        ]);
        Laboratorium::create([
            'nama_lab' => 'Laboratorium Elektronika',
            'kapasitas' => 25,
            'status' => 'aktif',
            'deskripsi' => 'Digunakan untuk praktikum rangkaian dan elektronika dasar',
        ]);
    }
}
