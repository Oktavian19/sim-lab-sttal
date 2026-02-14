<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use App\Models\PelaporanKerusakan;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        switch ($user->role) {
            case 'admin':
                $alat = [
                    'total' => Alat::count(),
                    'goodPercentage' => Alat::where('status_kondisi', 'baik')->count() / max(Alat::count(), 1) * 100,
                ];
                $peminjaman = [
                    'validasi' => Peminjaman::where('status_pengajuan', 'pending')->count(),
                    'validasiData' => Peminjaman::where('status_pengajuan', 'pending')->get(),
                ];
                $lab = [
                    'total' => Laboratorium::count(),
                    'used' => Laboratorium::where('status', 'digunakan')->count(),
                    'labData' => Laboratorium::all(),
                ];
                $lapKerusakan = [
                    'total' => PelaporanKerusakan::count(),
                ];

                return view('dashboard.admin', compact('alat', 'peminjaman', 'lab', 'lapKerusakan'));
            case 'user':
                return view('dashboard.user');
            default:
                return abort(403);
        }
    }
}
