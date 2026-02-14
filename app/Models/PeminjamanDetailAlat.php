<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDetailAlat extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_detail_alat';

    protected $fillable = [
        'peminjaman_id',
        'id_alat',
        'kondisi_saat_pinjam',
        'kondisi_saat_kembali',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }
}
