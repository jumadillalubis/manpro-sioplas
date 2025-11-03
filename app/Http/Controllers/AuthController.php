<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * 🔹 Halaman Login (satu untuk semua user)
     */
    public function showLogin()
    {
        return view('Auth.login'); 
    }

    /**
     * 🔹 Proses Autentikasi Login
     */
    public function authenticate(Request $request)
    {
        // Validasi input dari form login
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        try {
            // 🔸 Kirim request ke backend Golang
            $response = Http::post('http://localhost:8081/api/login', [
                'username' => $request->username,
                'password' => $request->password
            ]);

            // 🔸 Jika berhasil login
            if ($response->successful()) {
                $data = $response->json();

                // Tangkap user dan jabatan dari respons Golang
                $user = $data['data'] ?? $data['user'] ?? null;
                $jabatan = $data['jabatan'] ?? ($user['jabatan'] ?? null);

                if (!$user || !$jabatan) {
                    return back()->with('error', 'Data user tidak valid dari server.');
                }

                // 🔸 Simpan data user ke session Laravel
                session([
                    'user_id' => $user['id'] ?? null,
                    'user_nama' => $user['nama'] ?? '',
                    'user_email' => $user['email'] ?? '',
                    'user_jabatan' => $jabatan,
                ]);

                // 🔸 Tentukan arah redirect berdasarkan jabatan
                $jabatanLower = strtolower($jabatan);

                if (str_contains($jabatanLower, 'atasan')) {
                    return redirect()->route('beranda.atasan')->with('success', 'Selamat datang, ' . $user['nama']);
                } elseif (str_contains($jabatanLower, 'katimja')) {
                    return redirect()->route('beranda.katimja')->with('success', 'Selamat datang, ' . $user['nama']);
                } elseif (str_contains($jabatanLower, 'staff')) {
                    return redirect()->route('beranda.staff')->with('success', 'Selamat datang, ' . $user['nama']);
                } else {
                    return redirect()->route('login')->with('error', 'Jabatan tidak dikenali.');
                }
            }

            // Jika gagal login (HTTP 401, 400, dst.)
            return back()->with('error', $response->json('error') ?? 'Username atau password salah!');
        } catch (\Exception $e) {
            // Jika server Golang tidak bisa dihubungi
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }

    /**
     * 🔹 Logout User
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    /**
     * 🔹 Halaman Beranda untuk masing-masing jabatan
     */
    public function berandaAtasan()
    {
        return view('Atasan.beranda');
    }

    public function berandaKatimja()
    {
        return view('Katimja.beranda');
    }

    public function berandaStaff()
    {
        return view('Staff.beranda');
    }
}
