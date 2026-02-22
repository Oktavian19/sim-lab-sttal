<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PelaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PelaporanKerusakanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function list()
    {
        $laporan = PelaporanKerusakan::select('pelaporan_kerusakan.*');

        return DataTables::of($laporan)
            ->addIndexColumn()
            ->addColumn('tanggal_lapor', function ($laporan) {
                return '
                <div class="text-slate-900 font-medium">'.$laporan->tanggal_lapor->translatedFormat('d M Y').'</div>
                <div class="text-xs text-slate-400">'.$laporan->tanggal_lapor->format('H:i').' WIB</div>
            ';
            })
            ->addColumn('pelapor', function ($laporan) {
                return '
                <div class="font-medium text-slate-900">'.$laporan->pelapor->pangkat.' '.$laporan->pelapor->nama.'</div>
                <div class="text-xs text-slate-400">NRP. '.$laporan->pelapor->nrp.'</div>
                ';
            })
            ->addColumn('alat', function ($laporan) {
                return '
                <div class="text-blue font-medium text-slate-900">'.$laporan->alat->nama_alat.'</div>
                <div class="text-xs text-slate-400">'.$laporan->alat->laboratorium->nama_lab.'</div>
                ';
            })
            ->addColumn('deskripsi_kerusakan', function ($laporan) {
                return '
                <p class="text-slate-900 line-clamp-2">'.$laporan->deskripsi_kerusakan.'</p>
                <a href="/laporan/'.$laporan->id.'/show" onclick="modalAction(this.href); return false;" class="text-xs text-blue-500 hover:underline mt-1 block"><i class="fa-regular fa-image mr-1"></i>Lihat Foto</a>
                ';
            })
            ->addColumn('status', function ($laporan) {
                $baseClass = 'text-xs font-medium px-2.5 py-0.5 rounded border';

                switch ($laporan->status_tindak_lanjut) {
                    case 'selesai':
                        return '<span class="'.$baseClass.' bg-green-100 text-green-800 border-green-200">Selesai</span>';
                    case 'sedang_diperbaiki':
                        return '<span class="'.$baseClass.' bg-yellow-100 text-yellow-800 border-yellow-200">Perbaikan</span>';
                    case 'menunggu':
                        return '<span class="'.$baseClass.' bg-red-100 text-red-800 border-red-200">Menunggu</span>';
                    default:
                        return '<span class="'.$baseClass.' bg-slate-100 text-slate-600 border-slate-200">-</span>';
                }
            })
            ->addColumn('keterangan_perbaikan', function ($laporan) {
                return $laporan->keterangan_perbaikan ? '
                <p class="text-slate-900 line-clamp-2">'.$laporan->keterangan_perbaikan.'</p>
                ' : '- Belum Ada -';
            })
            ->addColumn('aksi', function ($laporan) {
                return '
                <a href="/laporan/'.$laporan->id.'/edit" onclick="modalAction(this.href); return false;" class="bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 border border-slate-200 p-2 rounded-md transition text-xs flex items-center justify-center gap-1 mx-auto w-full">
                    <i class="fa-solid fa-pen"></i> Update
                </a>
            ';
            })
            ->rawColumns(['tanggal_lapor', 'pelapor', 'alat', 'deskripsi_kerusakan', 'status', 'keterangan_perbaikan', 'aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $laporan = PelaporanKerusakan::with('alat', 'alat.laboratorium')->findOrFail($id);

        if (! $laporan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('laporan.show', compact('laporan'));
    }

    public function create()
    {
        $laboratorium = Laboratorium::with('alat')->get();
        $alat = $laboratorium->flatMap(function ($lab) {
            return $lab->alat->map(function ($alat) use ($lab) {
                return [
                    'id' => $alat->id,
                    'nama_alat' => $alat->nama_alat,
                    'merk' => $alat->merk,
                    'lokasi' => $lab->id,
                    'laboratorium' => $lab->nama_lab,
                ];
            });
        });

        return view('laporan.user.create', compact('laboratorium', 'alat'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alat_id' => 'required|exists:alat,id',
            'deskripsi' => 'required|string|max:1000',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path_foto = null;
        if ($request->hasFile('foto')) {
            $path_foto = $request->file('foto')->store('uploads/foto_kerusakan', 'public');
        }

        PelaporanKerusakan::create([
            'id_pelapor' => Auth::id(),
            'id_alat' => $request->alat_id,
            'deskripsi_kerusakan' => $request->deskripsi,
            'foto_bukti' => $path_foto,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Laporan kerusakan berhasil dibuat.',
        ]);
    }

    public function edit($id)
    {
        $laporan = PelaporanKerusakan::with('alat', 'alat.laboratorium')->findOrFail($id);

        if (! $laporan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'keterangan_perbaikan' => 'required_if:status,selesai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $laporan = PelaporanKerusakan::findOrFail($id);

        if (! $laporan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $laporan->update([
            'status_tindak_lanjut' => $request->status,
            'keterangan_perbaikan' => $request->keterangan_perbaikan,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function riwayatUser()
    {
        $laporan = PelaporanKerusakan::where('id_pelapor', Auth::id())->with('alat', 'alat.laboratorium')->orderBy('created_at', 'desc')->get();

        return view('laporan.user.riwayat', compact('laporan'));
    }
}
