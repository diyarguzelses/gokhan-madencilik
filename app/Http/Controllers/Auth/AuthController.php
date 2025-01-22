<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        return back()->withErrors(['message' => 'Kullanıcı adı veya şifre hatalı']);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}

