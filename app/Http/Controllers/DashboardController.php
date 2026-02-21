<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use App\Models\PelaporanKerusakan;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetailAlat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        switch ($user->role) {
            case 'admin':
                $alat = [
                    'total' => Alat::sum('jumlah'),
                    'sedang_dipinjam' => PeminjamanDetailAlat::whereHas('peminjaman', function ($query) {
                        $query->where('status_pengajuan', 'dipinjam');
                    })->sum('jumlah'),
                ];
                $peminjaman = [
                    'validasi' => Peminjaman::where('status_pengajuan', 'pending')->count(),
                    'validasiData' => Peminjaman::with('laboratorium')->where('status_pengajuan', 'pending')->limit(6)->orderBy('start_time', 'asc')->get(),
                ];
                $activeLoans = Peminjaman::where('status_pengajuan', 'disetujui')
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->pluck('id_lab')
                    ->unique();
                $jadwalHariIni = Peminjaman::whereDate('start_time', $now->toDateString())->get();
                $lab = [
                    'total' => Laboratorium::count(),
                    'used' => $activeLoans->count(),
                    'active_lab_ids' => $activeLoans->toArray(),
                    'labData' => Laboratorium::all(),
                ];
                $lapKerusakan = [
                    'total' => PelaporanKerusakan::count(),
                ];

                return view('dashboard.admin', compact('alat', 'peminjaman', 'jadwalHariIni', 'lab', 'lapKerusakan'));
            case 'user':
                return view('dashboard.user');
            default:
                return abort(403);
        }
    }
}
