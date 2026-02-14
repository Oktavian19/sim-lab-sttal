<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected array $allowedRoles = ['admin', 'user'];

    protected function validRole(string $role)
    {
        abort_unless(in_array($role, $this->allowedRoles), 404);
    }

    public function index($role)
    {
        $this->validRole($role);

        return view('user.index', compact('role'));
    }

    public function list($role)
    {
        $this->validRole($role);
        $user = User::where('role', $role)->select('users.*');

        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('nrp', function ($user) {
                return '
                <div class="font-medium text-slate-900">'.$user->nrp.'</div>
            ';
            })
            ->addColumn('nama', function ($user) {
                return '
                <div class="font-medium text-slate-900">'.$user->nama.'</div>
            ';
            })
            ->addColumn('pangkat_korps', function ($user) {
                return '
                <span class="block text-slate-800 font-medium">'.$user->pangkat.'</span>
                <span class="text-xs text-slate-500 bg-slate-100 px-1 rounded">'.$user->korps.'</span>
            ';
            })
            ->addColumn('aksi', function ($user) use ($role) {
                return '
            <div class="flex items-center justify-center gap-3">
                <a href="/'.$role.'/'.$user->id.'/edit" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-amber-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>

                <a href="/'.$role.'/'.$user->id.'/confirm" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-red-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
            ';
            })
            ->rawColumns(['nrp', 'nama', 'pangkat_korps', 'aksi'])
            ->make(true);
    }

    public function create($role)
    {
        $this->validRole($role);

        return view('user.create', compact('role'));
    }

    public function store(Request $request, $role)
    {
        $this->validRole($role);
        $validator = Validator::make($request->all(), [
            'nrp' => 'required|unique:users,nrp|min:3|max:20',
            'nama' => 'required|min:3|max:100',
            'password' => 'required|min:6',
            'pangkat' => 'required|min:2|max:50',
            'korps' => 'required|min:2|max:50',
            'jurusan' => 'required|min:2|max:100',
            'no_telepon' => 'required|min:10|max:15',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path_foto = null;

        if ($request->hasFile('photo_path')) {
            $path_foto = $request->file('photo_path')->store('uploads/user', 'public');
        }

        User::create([
            'nrp' => $request->nrp,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'role' => $role,
            'pangkat' => $request->pangkat,
            'korps' => $request->korps,
            'jurusan' => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'photo_path' => $path_foto,
        ]);

        return response()->json([
            'status' => true,
            'message' => ucfirst($role).' berhasil ditambahkan.',
        ]);
    }

    public function edit($role, $id)
    {
        $this->validRole($role);

        $user = User::where('id', $id)
            ->where('role', $role)
            ->firstOrFail();

        return view('user.edit', compact('user', 'role'));
    }

    public function update(Request $request, $role, $id)
    {
        $this->validRole($role);

        $validator = Validator::make($request->all(), [
            'nrp' => 'required|unique:users,nrp,'.$id.'|min:3|max:20',
            'nama' => 'required|min:3|max:100',
            'password' => 'nullable|min:6',
            'pangkat' => 'required|min:2|max:50',
            'korps' => 'required|min:2|max:50',
            'jurusan' => 'required|min:2|max:100',
            'no_telepon' => 'required|min:10|max:15',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('id', $id)
            ->where('role', $role)
            ->firstOrFail();

        $path_foto = $user->photo_path;

        if ($request->hasFile('photo_path')) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $path_foto = $request->file('photo_path')->store('uploads/user', 'public');
        }

        $user->update([
            'nrp' => $request->nrp,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'pangkat' => $request->pangkat,
            'korps' => $request->korps,
            'jurusan' => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'photo_path' => $path_foto,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function confirm($role, $id)
    {
        $this->validRole($role);

        $user = User::where('id', $id)
            ->where('role', $role)
            ->firstOrFail();

        return view('user.confirm', compact('user', 'role'));
    }

    public function destroy($role, $id)
    {
        $this->validRole($role);

        $user = User::where('id', $id)
            ->where('role', $role)
            ->firstOrFail();

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => ucfirst($user->role).' berhasil dihapus.',
        ]);
    }
}
