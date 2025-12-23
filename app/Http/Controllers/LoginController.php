<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('Auth.Login_SIOPLAS');
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
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    $userData = $data['data'] ?? [];
                    
                    // Ambil jabatan dari response atau dari data user
                    $jabatan = $data['jabatan'] ?? $userData['jabatan'] ?? '';
                    
                    if (empty($jabatan)) {
                        return back()->with('error', 'Jabatan tidak ditemukan dalam response server.');
                    }
                    
                    // Simpan data ke session berdasarkan jabatan
                    $sessionData = [
                        'user_id' => $userData['id'] ?? null,
                        'user_nama' => $userData['nama'] ?? '',
                        'user_email' => $userData['email'] ?? '',
                        'user_jabatan' => $jabatan, 
                    ];

                    // Tambahkan data khusus berdasarkan field yang ada
                    if (isset($userData['nip']) && !empty($userData['nip'])) {
                        $sessionData['user_nip'] = $userData['nip'];
                    }
                    
                    if (isset($userData['nup']) && !empty($userData['nup'])) {
                        $sessionData['user_nup'] = $userData['nup'];
                    }
                    
                    if (isset($userData['pangkat_gol']) && !empty($userData['pangkat_gol'])) {
                        $sessionData['user_pangkat_gol'] = $userData['pangkat_gol'];
                    }
                    
                    if (isset($userData['pendidikan']) && !empty($userData['pendidikan'])) {
                        $sessionData['user_pendidikan'] = $userData['pendidikan'];
                    }

                    session($sessionData);

                    // Redirect berdasarkan jabatan dari database
                    $jabatanLower = strtolower(trim($jabatan));
                    
                    // Helper function untuk menentukan route beranda berdasarkan jabatan
                    $berandaRoute = $this->getBerandaRouteByJabatan($jabatanLower);
                    
                    $userName = $userData['nama'] ?? 'User';
                    return redirect()->route($berandaRoute)->with('success', 'Login berhasil! Selamat datang ' . $userName . ' (' . $jabatan . ')');
                } else {
                    // Jika status bukan success
                    $errorMessage = $data['message'] ?? $data['error'] ?? 'Login gagal';
                    return back()->with('error', $errorMessage);
                }
            }

            // Jika response tidak successful
            $errorData = $response->json();
            $errorMessage = $errorData['error'] ?? $errorData['message'] ?? 'Username atau password salah!';
            return back()->with('error', $errorMessage);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }

    /**
     * Tentukan route beranda berdasarkan jabatan dari database
     */
    private function getBerandaRouteByJabatan($jabatan)
    {
        // Normalisasi jabatan ke lowercase untuk perbandingan
        $jabatan = strtolower(trim($jabatan));
        
        // Prioritas 1: Mapping khusus untuk jabatan spesifik
        // Jabatan untuk Staff (cek dulu untuk menghindari konflik)
        if (strpos($jabatan, 'staff') !== false || 
            strpos($jabatan, 'pegawai') !== false ||
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
        // Kirim request logout ke backend Golang jika ada email di session
        $userEmail = session('user_email');
        
        if ($userEmail) {
            try {
                Http::post('http://localhost:8080/api/logout', [
                    'email' => $userEmail,
                ]);
            } catch (\Exception $e) {
                // Jika gagal kirim logout ke backend, tetap lanjutkan logout di Laravel
            }
        }
        
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
