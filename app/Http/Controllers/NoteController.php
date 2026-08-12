<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NoteController extends Controller
{
    /**
     * Dapatkan email user aktif dari session
     */
    private function getUserEmail()
    {
        return session('user_email') ?? session('atasan_email') ?? session('temp_user_email') ?? 'guest@sioplas.id';
    }

    /**
     * Ambil catatan pengguna dari database (via Go Backend)
     */
    public function getNote()
    {
        $email = $this->getUserEmail();

        try {
            $response = Http::get('http://localhost:8080/api/note', [
                'email' => $email,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            // Error handling
        }

        return response()->json([
            'status'  => 'success',
            'content' => '',
        ]);
    }

    /**
     * Simpan catatan pengguna ke database (via Go Backend)
     */
    public function saveNote(Request $request)
    {
        $email   = $this->getUserEmail();
        $content = $request->input('content', '');

        try {
            $response = Http::post('http://localhost:8080/api/note', [
                'email'   => $email,
                'content' => $content,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke server backend.',
            ], 500);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Gagal menyimpan catatan.',
        ], 400);
    }
}
