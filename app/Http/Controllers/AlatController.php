<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AlatController extends Controller
{
    public function index()
    {
        $alat = Alat::all();

        return view('alat.index', compact('alat'));
    }

    public function list()
    {
        $alat = Alat::with('laboratorium')->select('alat.*');

        return DataTables::of($alat)
            ->addIndexColumn()
            ->addColumn('nama_alat', function ($alat) {
                return '
                <div class="font-medium text-slate-900">'.$alat->nama_alat.'</div>
                <div class="text-xs text-slate-400">Merek: '.$alat->merk.'</div>
            ';
            })

            ->addColumn('lokasi', function ($alat) {
                return $alat->laboratorium ? $alat->laboratorium->nama_lab : '-';
            })

            ->addColumn('kondisi', function ($alat) {
                $baseClass = 'text-xs font-medium px-2.5 py-0.5 rounded border';

                switch ($alat->status_kondisi) {
                    case 'baik':
                        return '<span class="'.$baseClass.' bg-green-100 text-green-800 border-green-200">Baik</span>';
                    case 'rusak_ringan':
                        return '<span class="'.$baseClass.' bg-yellow-100 text-yellow-800 border-yellow-200">Rusak Ringan</span>';
                    case 'rusak_berat':
                        return '<span class="'.$baseClass.' bg-red-100 text-red-800 border-red-200">Rusak Berat</span>';
                    default:
                        return '<span class="'.$baseClass.' bg-slate-100 text-slate-600 border-slate-200">-</span>';
                }
            })

            ->addColumn('status', function ($alat) {
                $template = '<span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium %s text-%s-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-%s-600"></span> %s
                         </span>';

                if ($alat->status_ketersediaan === 'tersedia') {
                    return sprintf($template, 'bg-green-100', 'green', 'green', 'Tersedia');
                }

                if ($alat->status_ketersediaan === 'dipinjam') {
                    return sprintf($template, 'bg-red-100', 'red', 'red', 'Dipinjam');
                }

                return sprintf($template, 'bg-yellow-100', 'yellow', 'yellow', 'Maintenance');
            })

            ->addColumn('aksi', function ($alat) {
                return '
            <div class="flex items-center justify-center gap-3">
                <a href="/alat/'.$alat->id.'/show" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-red-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="/alat/'.$alat->id.'/edit" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-amber-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                <a href="/alat/'.$alat->id.'/confirm" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-red-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
            ';
            })
            ->rawColumns(['kode_alat', 'nama_alat', 'kondisi', 'status', 'aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $alat = Alat::with('laboratorium')->findOrFail($id);

        if (! $alat) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('alat.show', compact('alat'));
    }

    public function create()
    {
        $lab = Laboratorium::select('id', 'nama_lab')->get();

        return view('alat.create', compact('lab'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_alat' => 'required|min:3|max:100',
            'merk' => 'required|min:3|max:100',
            'tahun_pengadaan' => 'required|numeric',
            'lokasi' => 'required',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path_foto = null;

        if ($request->hasFile('foto_alat')) {
            $path_foto = $request->file('foto_alat')->store('uploads/alat', 'public');
        }

        Alat::create([
            'nama_alat' => $request->nama_alat,
            'merk' => $request->merk,
            'tahun_pengadaan' => $request->tahun_pengadaan,
            'lokasi' => $request->lokasi,
            'status_kondisi' => 'baik',
            'status_ketersediaan' => 'tersedia',
            'foto_alat' => $path_foto,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Alat berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $alat = Alat::findOrFail($id);
        $lab = Laboratorium::select('id', 'nama_lab')->get();

        if (! $alat) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('alat.edit', compact('alat', 'lab'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_alat' => 'required|min:3|max:100',
            'merk' => 'required|min:3|max:100',
            'tahun_pengadaan' => 'required|numeric',
            'lokasi' => 'required',
            'status_kondisi' => 'required',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $alat = Alat::findOrFail($id);

        if (! $alat) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $path_foto = $alat->foto_alat;

        if ($request->hasFile('foto_alat')) {
            if ($alat->foto_alat && Storage::disk('public')->exists($alat->foto_alat)) {
                Storage::disk('public')->delete($alat->foto_alat);
            }

            $path_foto = $request->file('foto_alat')->store('uploads/alat', 'public');
        }

        $alat->update([
            'nama_alat' => $request->nama_alat,
            'merk' => $request->merk,
            'tahun_pengadaan' => $request->tahun_pengadaan,
            'lokasi' => $request->lokasi,
            'status_kondisi' => $request->status_kondisi,
            'foto_alat' => $path_foto ?? $alat->foto_alat,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function confirm($id)
    {
        $alat = Alat::findOrFail($id);

        if (! $alat) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('alat.confirm', compact('alat'));
    }

    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        if (! $alat) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($alat->foto_alat && Storage::disk('public')->exists($alat->foto_alat)) {
            Storage::disk('public')->delete($alat->foto_alat);
        }

        $alat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
