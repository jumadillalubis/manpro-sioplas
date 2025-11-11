<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatimjaController extends Controller
{
    public function beranda()
    {
        return view('Katimja.Beranda_Katimja');
    }

    public function laporan()
    {
        $user = Auth::user(); // Ambil user yang login

        // Cek role user
        if (strtolower($user->role) !== 'katimja') {
            // Jika bukan Katimja, redirect ke beranda mereka sesuai role
            switch (strtolower($user->role)) {
                case 'staff':
                    return redirect()->route('beranda.staff');
                case 'atasan':
                    return redirect()->route('beranda.atasan');
                default:
                    return redirect()->route('landing'); // fallback
            }
        }

        // Jika role Katimja, tampilkan halaman laporan
        return view('Katimja.Laporan_Katimja');
    }
}
