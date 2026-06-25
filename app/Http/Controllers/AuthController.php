<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function tampilLogin()
    {
        return view('login');
    }

    public function tampilRegister()
    {
        return view('register');
    }

    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } else {
                return redirect()->intended('dashboard');
            }
        }

        return back()->with('error', 'Email tidak ditemukan di sirkuit atau password salah! Rem mendadak.');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nomor_hp' => 'required|string|max:20',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:3',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user',
        ]);

        return redirect('/login')->with('success', 'Lisensi berhasil dibuat! Silakan masuk ke sirkuit.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}