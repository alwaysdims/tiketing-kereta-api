<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // validasi input
        $request->validate([
            'login'    => ['required'], // bisa username atau email
            'password' => ['required'],
        ]);

        $login = $request->input('login');

        // cek apakah input email atau username
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $login,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'petugas':
                    return redirect()->intended(route('petugas.dashboard'));
                case 'penumpang':
                    return redirect()->intended(route('penumpang.dashboard'));
                default:
                    Auth::logout();
                    return back()->withErrors([
                        'login' => 'Role tidak dikenali.',
                    ])->onlyInput('login');
            }
        }

        return back()->withErrors([
            'login' => 'Username/email atau password salah.',
        ])->onlyInput('login');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
