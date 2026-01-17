<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atasan;
use App\Models\Tugas;
use Illuminate\Support\Facades\Storage;

class AtasanController extends Controller
{
    /* =====================
     * LANDING & AUTH
     * ===================== */

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
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $atasan = Atasan::where('nama', $request->username)
                        ->where('password', $request->password)
                        ->first();

        if ($atasan) {
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

            return redirect()->route('beranda.atasan')
                ->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }

    /* =====================
     * BERANDA & TUGAS
     * ===================== */

    public function beranda()
    {
        return view('Atasan.beranda_atasan');
    }

    public function storeTugas(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tenggat' => 'required|date',
            'file' => 'nullable|file|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas_files', 'public');
        }

        Tugas::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'tenggat' => $request->tenggat,
            'dibuat_oleh' => session('atasan_nama'),
        ]);

        return back()->with('success', 'Tugas berhasil dibuat');
    }

    public function notifikasi()
    {
        return view('Atasan.notifikasi_atasan');
    }

    /* =====================
     * SETTINGS & PASSWORD
     * ===================== */

    // halaman settings
    public function settings()
    {
        return view('Atasan.settings_atasan');
    }

    // halaman ubah password
    public function ubahPassword()
    {
        return view('Atasan.ubah_password');
    }

    // proses update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $atasan = Atasan::find(session('atasan_id'));

        if (!$atasan) {
            return redirect()->route('login');
        }

        // cek password lama
        if ($atasan->password !== $request->current_password) {
            return back()->withErrors(['Kata sandi saat ini salah']);
        }

        // update password
        $atasan->password = $request->new_password;
        $atasan->save();

        return redirect()
            ->route('atasan.settings')
            ->with('success', 'Password berhasil diubah');
    }

    public function createTugas()
{
    return view('Atasan.tugas_create');
}
}

