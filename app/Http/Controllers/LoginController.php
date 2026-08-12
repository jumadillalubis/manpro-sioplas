<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('Auth.Login_Atasan');
    }

    /**
     * Tahap 1 Login: Verifikasi Username & Password
     * Jika berhasil → Go Backend kirim OTP ke email → redirect ke halaman OTP
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        try {
            // Kirim request ke backend Golang
            $response = Http::post('http://localhost:8080/api/login', [
                'username' => $username,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Tahap 1 sukses: Go Backend sudah kirim OTP ke email
                if ($data['status'] === 'otp_sent') {
                    $userData = $data['data'];
                    $jabatan  = $data['user_jabatan'] ?? ($userData['jabatan'] ?? '');

                    // Simpan data user ke session SEMENTARA (belum login resmi)
                    session([
                        'temp_user_id'       => $data['user_id'] ?? ($userData['id'] ?? null),
                        'temp_user_nama'     => $userData['nama'] ?? '',
                        'temp_user_email'    => $data['email'] ?? ($userData['email'] ?? ''),
                        'temp_user_jabatan'  => $jabatan,
                        'temp_user_divisi'   => $data['user_divisi'] ?? ($userData['divisi'] ?? ''),
                        'temp_user_pangkat_gol' => $userData['pangkat_gol'] ?? '',
                        'temp_user_pendidikan'  => $userData['pendidikan'] ?? '',
                        'temp_user_nip'      => $userData['nip'] ?? '',
                        'temp_user_nup'      => $userData['nup'] ?? '',
                        'temp_user_tahun_masuk' => $userData['TahunMasuk'] ?? '',
                        'otp_email_masked'   => $data['email_masked'] ?? '',
                    ]);

                    // Redirect ke halaman input OTP
                    return redirect()->route('otp.show')
                        ->with('info', 'Kode OTP telah dikirim ke email Anda.');
                }
            }

            // Jika response gagal (username/password salah)
            $errorData = $response->json();
            return back()->with('error', $errorData['message'] ?? $errorData['error'] ?? 'Username atau password salah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman input OTP
     */
    public function showOtp()
    {
        // Pastikan pengguna memang baru melewati tahap 1 login
        if (!session('temp_user_email')) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        $emailMasked = session('otp_email_masked', '');
        return view('Auth.OTP_Verify', compact('emailMasked'));
    }

    /**
     * Tahap 2 Login: Verifikasi kode OTP
     * Jika OTP benar → pindahkan dari session temp ke session utama → login resmi
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('temp_user_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi telah habis. Silakan login kembali.');
        }

        $otpCode = $request->input('otp');

        try {
            // Kirim OTP ke Go Backend untuk diverifikasi
            $response = Http::post('http://localhost:8080/api/otp/verify', [
                'email' => $email,
                'code'  => $otpCode,
            ]);

            if ($response->successful() && $response->json()['status'] === 'success') {
                // OTP valid → Pindahkan session temp ke session utama (user resmi login)
                $sessionData = [
                    'user_id'        => session('temp_user_id'),
                    'user_nama'      => session('temp_user_nama'),
                    'user_email'     => session('temp_user_email'),
                    'user_jabatan'   => session('temp_user_jabatan'),
                    'user_divisi'    => session('temp_user_divisi'),
                    'user_pangkat_gol' => session('temp_user_pangkat_gol'),
                    'user_pendidikan'  => session('temp_user_pendidikan'),
                    'user_nip'       => session('temp_user_nip'),
                    'user_nup'       => session('temp_user_nup'),
                    'user_tahun_masuk' => session('temp_user_tahun_masuk'),
                ];

                // Hapus semua session sementara
                session()->forget([
                    'temp_user_id', 'temp_user_nama', 'temp_user_email',
                    'temp_user_jabatan', 'temp_user_divisi', 'temp_user_pangkat_gol',
                    'temp_user_pendidikan', 'temp_user_nip', 'temp_user_nup',
                    'temp_user_tahun_masuk', 'otp_email_masked',
                ]);

                // Set session utama
                session($sessionData);

                // Redirect ke dashboard sesuai jabatan
                $jabatanLower  = strtolower(trim($sessionData['user_jabatan']));
                $berandaRoute  = $this->getBerandaRouteByJabatan($jabatanLower);

                return redirect()->route($berandaRoute)
                    ->with('success', 'Login berhasil! Selamat datang ' . $sessionData['user_nama'] . '.');
            }

            // OTP tidak valid atau expired
            $errorData = $response->json();
            return back()->with('error', $errorData['message'] ?? 'Kode OTP tidak valid atau sudah kedaluwarsa.');

        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }

    /**
     * Tentukan route beranda berdasarkan jabatan dari database
     */
    private function getBerandaRouteByJabatan($jabatan)
    {
        // Prioritas 1: Staff
        if (strpos($jabatan, 'kepala bidang') !== false ||
            strpos($jabatan, 'pegawai') !== false ||
            strpos($jabatan, 'staff') !== false ||
            strpos($jabatan, 'bidang') !== false) {
            return 'beranda.staff';
        }

        // Prioritas 2: Katimja
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

        // Prioritas 3: Atasan
        if (strpos($jabatan, 'atasan') !== false ||
            strpos($jabatan, 'kepala dinas') !== false ||
            strpos($jabatan, 'kepala') !== false) {
            return 'beranda.atasan';
        }

        // Default
        return 'beranda.atasan';
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
