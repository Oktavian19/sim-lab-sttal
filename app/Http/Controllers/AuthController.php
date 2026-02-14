<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function register()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.register');
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
}
