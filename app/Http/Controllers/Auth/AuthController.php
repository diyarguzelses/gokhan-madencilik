<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $login = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $password])) {
            return redirect('FT23BA23DG12/');
        }

        return back()->with(['error' => 'Kullanıcı adı veya şifre hatalı']);
    }


    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();

        return redirect('/login');
    }

}

