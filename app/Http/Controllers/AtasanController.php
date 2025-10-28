<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Atasan;

class AtasanController extends Controller
{
    public function landing()
    {
        return view('Atasan.landing_page');
    }

    public function showLogin()
    {
        return view('Auth.Login_Atasan');
    }

    public function authenticate(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        try {
            // Cari atasan berdasarkan email (username) dan password
            $atasan = Atasan::where('nama', $username)
                           ->where('password', $password)
                           ->first();

            if ($atasan) {
                // Simpan data atasan ke session
                session([
                    'atasan_id' => $atasan->id,
                    'atasan_nama' => $atasan->nama,
                    'atasan_email' => $atasan->email,
                    'atasan_jabatan' => $atasan->jabatan,
                    'atasan_role' => $atasan->role,
                    'atasan_nip' => $atasan->nip,
                    'atasan_pangkat_gol' => $atasan->pangkat_gol,
                    'atasan_pendidikan' => $atasan->pendidikan
                ]);
                
                return redirect()->route('beranda')->with('success', 'Login berhasil! Selamat datang ' . $atasan->nama);
            } else {
                return back()->with('error', 'Username atau password salah!');
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan pada server. Silakan coba lagi.');
        }
    }

    public function beranda()
    {
        return view('Atasan.beranda_atasan');
    }

    public function logout()
    {
        return redirect()->route('login');
    }
}
