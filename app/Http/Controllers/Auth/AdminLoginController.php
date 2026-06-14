<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'login_as' => ['required', 'in:ssc,logistik'],
        ], [
            'login_as.required' => 'Pilih role login (SSC atau Logistik).',
            'login_as.in' => 'Role tidak valid.',
        ]);

        // Cek kredensial akun admin
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin',
        ];

        if (!Auth::attempt($credentials, false)) {
            return back()->withErrors([
                'email' => 'Email atau password salah, atau akun bukan admin.',
            ])->withInput($request->only('email', 'login_as'));
        }

        // Set session role sesuai pilihan
        $request->session()->put('admin_role', $request->login_as);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_role');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
