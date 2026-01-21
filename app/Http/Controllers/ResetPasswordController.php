<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('Auth.ResetPassword');
    }

    public function processReset(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|same:new_password'
        ]);

        try {
            // Kirim request ke backend Golang
            $response = Http::post('http://localhost:8080/api/reset-password', [
                'name' => $request->username,
                'new_password' => $request->new_password,
            ]);

            if ($response->successful()) {
                return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
            }

            $errorData = $response->json();
            return back()->with('error', $errorData['error'] ?? 'Gagal mereset password.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat terhubung ke server backend.');
        }
    }
}
