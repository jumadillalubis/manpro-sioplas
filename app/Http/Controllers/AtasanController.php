<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atasan;
use App\Models\Tugas;
use App\Models\Laporan;
use Illuminate\Support\Facades\Hash;

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

        $atasan = Atasan::where('nama', $request->username)->first();

        if ($atasan && Hash::check($request->password, $atasan->password)) {
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

    // 🔴 LOGOUT (AMAN UNTUK GET)
    public function logout()
    {
        session()->flush();
        return redirect()->route('login.atasan');
    }

    /* =====================
     * BERANDA & TUGAS
     * ===================== */

    public function beranda()
    {
        $katimjas = \App\Models\Katimja::all();
        // Count tasks created by the current logged-in Atasan
        $totalTugas = Tugas::where('pembuat', session('atasan_nama'))->count();
        // Count all reports
        $totalLaporan = Laporan::count();

        return view('Atasan.beranda_atasan', compact('katimjas', 'totalTugas', 'totalLaporan'));
    }

    public function createTugas()
    {
        return view('Atasan.tugas_create');
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
            'pembuat' => session('atasan_nama'),
        ]);

        return back()->with('success', 'Tugas berhasil dibuat');
    }

    /* =====================
     * LAPORAN
     * ===================== */

    // ✅ INI YANG KEMARIN BIKIN ERROR
    public function laporan()
    {
        return view('Atasan.laporan_atasan');
    }

    public function notifikasi()
    {
        return view('Atasan.notifikasi_atasan');
    }

    /* =====================
     * SETTINGS & PASSWORD
     * ===================== */

    public function settings()
    {
        return view('Atasan.settings_atasan');
    }

    public function ubahPassword()
    {
        return view('Atasan.ubah_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $atasan = Atasan::find(session('atasan_id'));

        if (!$atasan) {
            return redirect()->route('login.atasan');
        }

        // cek password lama
        if (!Hash::check($request->current_password, $atasan->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // update password
        $atasan->password = Hash::make($request->new_password);
        $atasan->save();

        return redirect()
            ->route('atasan.settings')
            ->with('success', 'Password berhasil diubah');
    }
}
