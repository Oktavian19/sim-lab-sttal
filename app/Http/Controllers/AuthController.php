<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login page
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Login process
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'nrp' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login berhasil',
                    'redirect' => '/dashboard',
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'NRP atau Password salah.',
            ], 401);
        }

        return back()->withErrors([
            'nrp' => 'NRP atau Password salah.',
        ])->onlyInput('nrp');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.register');
    }    

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nrp' => 'required|unique:users,nrp|min:3|max:20',
            'nama' => 'required|min:3|max:100',
            'password' => 'required|min:6',
            'pangkat' => 'required|min:2|max:50',
            'korps' => 'required|min:2|max:50',
            'jurusan' => 'required|min:2|max:100',
            'no_telepon' => 'required|min:10|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        User::create([
            'nrp' => $request->nrp,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'pangkat' => $request->pangkat,
            'korps' => $request->korps,
            'jurusan' => $request->jurusan,
            'no_telepon' => $request->no_telepon,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil ditambahkan.',
        ]);
    }

    /**
     * Logout process
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nrp' => 'required|string|unique:users,nrp,' . $user->id . '|min:3|max:20',
            'nama' => 'required|min:3|max:100',
            'password' => 'nullable|min:6',
            'pangkat' => 'required|min:2|max:50',
            'korps' => 'required|min:2|max:50',
            'jurusan' => 'required|min:2|max:100',
            'no_telepon' => 'required|min:10|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path_photo = $user->photo_path;


        if ($request->hasFile('photo')) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $path_photo = $request->file('photo')->store('uploads/user', 'public');
        }

        $user->update([
            'nrp' => $request->nrp,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'pangkat' => $request->pangkat,
            'korps' => $request->korps,
            'jurusan' => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'photo_path' => $path_photo,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }
}
