<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nrp' => 'ADM001',
            'nama' => 'Admin Sistem',
            'password' => Hash::make('password'),
            'pangkat' => 'Letkol',
            'korps' => 'Teknik',
            'jurusan' => 'Informatika',
            'no_telepon' => '081234567890',
            'role' => 'admin',
        ]);
        User::create([
            'nrp' => 'USR001',
            'nama' => 'Taruna Contoh',
            'password' => Hash::make('password'),
            'pangkat' => 'Taruna',
            'korps' => 'Teknik',
            'jurusan' => 'Informatika',
            'no_telepon' => '081211111111',
            'role' => 'user',
        ]);
    }
}
