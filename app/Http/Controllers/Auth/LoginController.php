<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $username = 'email';
        } else {
            $username = 'username';
        }

        $attempt = Auth::attempt([
            $username => $request->get('email'),
            'password' => $request->password,
            'admin' => 1,
        ], $request->get('rememberme') ? true : false);

        if ($attempt) {
            return redirect()->to('/');
        } else {
            return redirect()->back()->withErrors(['error-auth' => 'username or password incorrect']);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('loginForm');
    }
}
