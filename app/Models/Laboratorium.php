<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    use HasFactory;

    protected $table = 'laboratorium';

    protected $fillable = [
        'nama_lab',
        'kapasitas',
        'status',
        'deskripsi',
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_lab');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_lab');
    }
}
