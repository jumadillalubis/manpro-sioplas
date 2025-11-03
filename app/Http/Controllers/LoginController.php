<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
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
            // Kirim request ke backend Golang
            $response = Http::post('http://localhost:8080/api/login', [
                'username' => $username,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    $userData = $data['data'];
                    $jabatan = $data['jabatan'] ?? $userData['jabatan'] ?? ''; // Jabatan dari database sebagai patokan
                    
                    // Simpan data ke session berdasarkan jabatan
                    $sessionData = [
                        'user_id' => $userData['id'],
                        'user_nama' => $userData['nama'],
                        'user_email' => $userData['email'] ?? '',
                        'user_jabatan' => $jabatan, // Jabatan dari database sebagai patokan
                        'user_pangkat_gol' => $userData['pangkat_gol'] ?? '',
                        'user_pendidikan' => $userData['pendidikan'] ?? '',
                    ];

                    // Tambahkan data khusus berdasarkan field yang ada
                    if (isset($userData['nip'])) {
                        $sessionData['user_nip'] = $userData['nip'];
                    }
                    
                    if (isset($userData['nup'])) {
                        $sessionData['user_nup'] = $userData['nup'];
                    }
                    
                    if (isset($userData['TahunMasuk'])) {
                        $sessionData['user_tahun_masuk'] = $userData['TahunMasuk'];
                    }

                    session($sessionData);

                    // Redirect berdasarkan jabatan dari database
                    $jabatanLower = strtolower(trim($jabatan));
                    
                    // Helper function untuk menentukan route beranda berdasarkan jabatan
                    $berandaRoute = $this->getBerandaRouteByJabatan($jabatanLower);
                    
                    return redirect()->route($berandaRoute)->with('success', 'Login berhasil! Selamat datang ' . $userData['nama'] . ' (' . $jabatan . ')');
                }
            }

            $errorData = $response->json();
            return back()->with('error', $errorData['error'] ?? 'Username atau password salah!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }

    /**
     * Tentukan route beranda berdasarkan jabatan dari database
     */
    private function getBerandaRouteByJabatan($jabatan)
    {
        // Prioritas 1: Mapping khusus untuk jabatan spesifik
        // Jabatan untuk Staff (cek dulu untuk menghindari konflik)
        if (strpos($jabatan, 'kepala bidang') !== false || 
            strpos($jabatan, 'pegawai') !== false ||
            strpos($jabatan, 'staff') !== false ||
            strpos($jabatan, 'bidang') !== false) {
            return 'beranda.staff';
        }
        
        // Jabatan untuk Katimja (prioritas 2, setelah Staff)
        if (strpos($jabatan, 'katimja') !== false || 
            strpos($jabatan, 'kaptimja') !== false ||
            strpos($jabatan, 'kepala tim kerja') !== false ||
            strpos($jabatan, 'sekretaris dinas') !== false ||
            strpos($jabatan, 'sekretaris') !== false ||
            strpos($jabatan, 'wakil kepala dinas') !== false ||
            strpos($jabatan, 'wakil kepala') !== false ||
            strpos($jabatan, 'wakil') !== false) {
            return 'beranda.katimja';
        }
        
        // Jabatan untuk Atasan (prioritas terakhir karena "kepala" bisa ambigu)
        if (strpos($jabatan, 'atasan') !== false || 
            strpos($jabatan, 'kepala dinas') !== false ||
            strpos($jabatan, 'kepala') !== false) {
            return 'beranda.atasan';
        }
        
        // Default: redirect ke beranda atasan jika jabatan tidak dikenali
        return 'beranda.atasan';
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
