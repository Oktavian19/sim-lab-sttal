<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'nama_alat',
        'merk',
        'tahun_pengadaan',
        'lokasi',
        'jumlah',
        'foto_alat',
    ];

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'lokasi');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(PeminjamanDetailAlat::class, 'id_alat');
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(PelaporanKerusakan::class, 'id_alat');
    }
}
