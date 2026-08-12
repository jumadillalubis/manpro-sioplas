<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Halaman utama Settings
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Halaman ubah password
     */
    public function password()
    {
        return view('settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:4|confirmed',
        ]);

        $nama = session('user_nama') ?? session('atasan_nama');

        if (empty($nama)) {
            return back()->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        }

        // Verifikasi password lama terlebih dahulu via Go Backend login check
        try {
            $loginCheck = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/login', [
                'username' => $nama,
                'password' => $request->password_lama,
            ]);

            if (!$loginCheck->successful() || ($loginCheck->json()['status'] ?? '') !== 'otp_sent') {
                return back()->with('error', 'Password lama yang Anda masukkan salah.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend untuk verifikasi.');
        }

        // Update password via Go Backend (menggunakan endpoint reset-password yang sudah terbukti berfungsi)
        try {
            $response = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/reset-password', [
                'name' => $nama,
                'new_password' => $request->password_baru,
            ]);

            if ($response->successful()) {
                return redirect()->route('settings')
                    ->with('success', 'Password berhasil diubah! Silakan gunakan password baru saat login berikutnya.');
            } else {
                $errorMsg = $response->json()['error'] ?? 'Gagal mengubah password.';
                return back()->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend. Silakan coba lagi.');
        }
    }
}

