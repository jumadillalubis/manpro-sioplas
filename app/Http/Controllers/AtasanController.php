<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtasanController extends Controller
{
    public function landing()
    {
        return view('atasan.landing');
    }

    public function showLogin()
    {
        return view('atasan.login');
    }

    public function authenticate(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Login testing tanpa database
        if ($username === 'Ade Samsudin' && $password === '123') {
            return redirect()->route('beranda');
        } else {
            return back()->with('error', 'Username atau Password salah.');
        }
    }

    public function beranda()
    {
        return view('atasan.beranda');
    }

    public function logout()
    {
        return redirect()->route('login');
    }
}
