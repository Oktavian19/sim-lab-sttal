<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LaboratoriumController extends Controller
{
    public function index()
    {
        return view('laboratorium.index');
    }

    public function list()
    {
        $laboratorium = Laboratorium::select('laboratorium.*');

        return DataTables::of($laboratorium)
            ->addIndexColumn()
            ->filterColumn('nama_lab', function ($query, $keyword) {
                $query->where('nama_lab', 'like', "%{$keyword}%");
            })
            ->filterColumn('kapasitas', function ($query, $keyword) {
                $query->where('kapasitas', 'like', "%{$keyword}%");
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', 'like', "%{$keyword}%");
            })
            ->addColumn('nama_lab', function ($laboratorium) {
                return '
                <div class="font-medium text-slate-900">'.$laboratorium->nama_lab.'</div>
            ';
            })
            ->addColumn('kapasitas', function ($laboratorium) {
                return '
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-slate-400"></i>
                    <span>'.$laboratorium->kapasitas.' Orang</span>
                </div>
                ' ?? '-';
            })
            ->addColumn('status', function ($laboratorium) {
                $baseClass = 'text-xs font-medium px-2.5 py-0.5 rounded border';

                switch ($laboratorium->status) {
                    case 'aktif':
                        return '<span class="'.$baseClass.' bg-green-100 text-green-800 border-green-200">Tersedia</span>';
                    case 'perbaikan':
                        return '<span class="'.$baseClass.' bg-yellow-100 text-yellow-800 border-yellow-200">Perbaikan</span>';
                    case 'nonaktif':
                        return '<span class="'.$baseClass.' bg-red-100 text-red-800 border-red-200">Nonaktif</span>';
                    default:
                        return '<span class="'.$baseClass.' bg-slate-100 text-slate-600 border-slate-200">-</span>';
                }
            })
            ->addColumn('aksi', function ($laboratorium) {
                return '
            <div class="flex items-center justify-center gap-3">
                <a href="/laboratorium/'.$laboratorium->id.'/edit" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-amber-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                <a href="/laboratorium/'.$laboratorium->id.'/confirm" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-red-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
            ';
            })
            ->rawColumns(['nama_lab', 'kapasitas', 'status', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('laboratorium.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        Laboratorium::create([
            'nama_lab' => $request->nama_lab,
            'kapasitas' => $request->kapasitas,
            'status' => 'aktif',
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Laboratorium berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);

        if (! $laboratorium) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('laboratorium.edit', compact('laboratorium'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:aktif,perbaikan,nonaktif',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $laboratorium = Laboratorium::findOrFail($id);
        $laboratorium->update([
            'nama_lab' => $request->nama_lab,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Laboratorium berhasil diperbarui.',
        ]);
    }

    public function confirm($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);

        if (! $laboratorium) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return view('laboratorium.confirm', compact('laboratorium'));
    }

    public function destroy($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        $laboratorium->delete();

        return response()->json([
            'status' => true,
            'message' => 'Laboratorium berhasil dihapus.',
        ]);
    }
}
