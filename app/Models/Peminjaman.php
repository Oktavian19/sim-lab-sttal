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
        'bukti_pengambilan',
        'bukti_pengembalian',
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
        return $query->whereIn('status_pengajuan', ['disetujui', 'dipinjam'])
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
    public static function checkAvailability($labId, array $alatIds, array $jumlahAlat, $startTime, $endTime, $excludeId = null)
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
            $borrowedTools = PeminjamanDetailAlat::selectRaw('id_alat, SUM(jumlah) as total_dipinjam')
                ->whereHas('peminjaman', function ($q) use ($startTime, $endTime, $excludeId) {
                    $q->activeCollision($startTime, $endTime, $excludeId);
                })
                ->whereIn('id_alat', $alatIds)
                ->groupBy('id_alat')
                ->get()
                ->keyBy('id_alat');

            $masterAlat = Alat::whereIn('id', $alatIds)->get()->keyBy('id');

            foreach ($alatIds as $alatId) {
                $alat = $masterAlat->get($alatId);
                if (! $alat) {
                    continue;
                }

                $requestedQty = isset($jumlahAlat[$alatId]) ? (int) $jumlahAlat[$alatId] : 1;

                $borrowedQty = $borrowedTools->has($alatId) ? (int) $borrowedTools->get($alatId)->total_dipinjam : 0;

                $availableQty = $alat->jumlah - $borrowedQty;

                if ($requestedQty > $availableQty) {
                    if ($availableQty > 0) {
                        $errors[] = "Stok alat '{$alat->nama_alat}' tidak mencukupi. Tersedia: {$availableQty}, Diminta: {$requestedQty}.";
                    } else {
                        $errors[] = "Alat '{$alat->nama_alat}' sedang habis/terpakai penuh pada jam tersebut.";
                    }
                }
            }
        }

        return count($errors) > 0 ? $errors : null;
    }
}
