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
            ->filterColumn('nama_alat', function ($query, $keyword) {
                $query->where('nama_alat', 'like', "%{$keyword}%")
                    ->orWhere('merk', 'like', "%{$keyword}%");
            })
            ->filterColumn('lokasi', function ($query, $keyword) {
                $query->whereHas('laboratorium', function ($q) use ($keyword) {
                    $q->where('nama_lab', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('nama_alat', function ($alat) {
                return '
                <div class="font-medium text-slate-900">'.$alat->nama_alat.'</div>
                <div class="text-xs text-slate-400">Merek: '.$alat->merk.'</div>
            ';
            })

            ->addColumn('lokasi', function ($alat) {
                return $alat->laboratorium ? $alat->laboratorium->nama_lab : '-';
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
            'jumlah' => $request->jumlah,
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
            'jumlah' => 'required|numeric|min:1',
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
            'jumlah' => $request->jumlah,
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
