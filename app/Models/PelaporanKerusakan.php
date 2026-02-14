<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'pelaporan_kerusakan';

    protected $fillable = [
        'id_alat',
        'id_pelapor',
        'tanggal_lapor',
        'deskripsi_kerusakan',
        'foto_bukti',
        'status_tindak_lanjut',
        'keterangan_perbaikan',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_pelapor', 'id');
    }
}
