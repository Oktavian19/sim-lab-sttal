<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id_peminjam',
        'id_lab',
        'start_time',
        'end_time',
        'kegiatan',
        'jumlah_peserta',
        'status_pengajuan',
        'catatan_admin',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'id_peminjam');
    }

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab');
    }

    public function detailAlat()
    {
        return $this->hasMany(PeminjamanDetailAlat::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function scopeActiveCollision(Builder $query, $startTime, $endTime, $excludeId = null)
    {
        return $query->where('status_pengajuan', 'disetujui')
            ->doesntHave('pengembalian')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            });
    }

    /**
     * Returns array of error if bentrok, or null if safe.
     */
    public static function checkAvailability($labId, array $alatIds, $startTime, $endTime, $excludeId = null)
    {
        $errors = [];

        if ($labId) {
            $labConflict = self::activeCollision($startTime, $endTime, $excludeId)
                ->where('id_lab', $labId)
                ->exists();

            if ($labConflict) {
                $errors[] = 'Laboratorium ini sudah dipesan pada jam tersebut.';
            }
        }

        if (! empty($alatIds)) {
            $conflictingTools = PeminjamanDetailAlat::whereHas('peminjaman', function ($q) use ($startTime, $endTime, $excludeId) {
                $q->activeCollision($startTime, $endTime, $excludeId);
            })
                ->whereIn('id_alat', $alatIds)
                ->with('alat')
                ->get();

            if ($conflictingTools->isNotEmpty()) {
                $toolNames = $conflictingTools->map(fn ($item) => $item->alat->nama_alat)->unique()->implode(', ');
                $errors[] = "Alat berikut sedang digunakan pada jam tersebut: {$toolNames}.";
            }
        }

        return count($errors) > 0 ? $errors : null;
    }
}
