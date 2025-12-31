<?php   

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatimjaController extends Controller
{
    // Menampilkan Beranda untuk Katimja
    public function beranda()
    {
        return view('Katimja.Beranda_Katimja');
    }

    // Menampilkan Laporan untuk Katimja
    public function laporan()
    {
        return view('Katimja.Laporan_Katimja');
    }

    // Menampilkan Daftar Tugas untuk Katimja
    public function tugas()
    {
        return view('Katimja.Tugas_Katimja');
    }

    // Menampilkan Detail Tugas
    public function showTugas($id)
    {
        return view('Katimja.TugasDetail_Katimja', ['id' => $id]);
    }

    // Menampilkan Form untuk Buat Tugas Baru
    public function createTugas()
    {
        return view('Katimja.TugasCreate_Katimja');
    }

    // Menyimpan Tugas Baru
    public function storeTugas(Request $request)
    {
        // Logic untuk menyimpan tugas baru
        return redirect()->route('tugas.katimja');
    }
    public function settings()
    {
        return view('Katimja.Setting_Katimja');
    }

    public function changePassword()
    {
        return view('Katimja.password');
    }
}

