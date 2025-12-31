<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Laporan;
use App\Models\TugasPegawai;

class StaffController extends Controller
{
    public function beranda()
    {
        return view('Staff.beranda_staff');
    }

    public function laporan()
    {
        return view('Staff.laporan_pegawai');
    }

    public function tugas()
    {
        $tugas = [
            [
                "id" => 1,
                "judul" => "Laporan Keuangan",
                "type" => "Dokumen",
                "batas_waktu" => "2025-01-20",
                "status" => "Pending"
            ],
            [
                "id" => 2,
                "judul" => "Rekap Presensi",
                "type" => "Spreadsheet",
                "batas_waktu" => "2025-01-21",
                "status" => "Completed"
            ]
        ];

        return view('Staff.tugas_pegawai', compact('tugas'));
    }

    public function detailTugas($id)
    {
        $data_tugas = [
            'id' => $id,
            'judul' => 'Evaluasi Kinerja Tim',
            'batas_waktu' => '25 Juli 2024',
            'pegawai' => 'Budi Santoso',
            'deskripsi' => 'Segera susun laporan komprehensif mengenai hasil evaluasi kinerja tim...'
        ];

        return view('Staff.detail_tugas', compact('data_tugas'));
    }

    public function settings()
    {
        return view('Staff.profile_settings');
    }

    public function changePassword()
    {
        return view('Staff.change_password');
    }

    public function uploadTW(Request $request, $id, $tw)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png,xlsx,docx|max:20480',
        ]);

        $laporan = Laporan::findOrFail($id);

        $file = $request->file('file');
        $filename = time() . "_" . $file->getClientOriginalName();
        $file->storeAs('laporan', $filename, 'public');

        $laporan->$tw = $filename;
        $laporan->save();

        return back()->with('success', 'File berhasil diupload ke ' . strtoupper($tw));
    }


    public function uploadFile(Request $request)
    {
        $request->validate([
            'indikator' => 'required',
            'tw' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,jpg,png|max:5120',
        ]);

        $request->file('file')->store('staff_uploads', 'public');

        return back()->with('success', 'File berhasil diupload!');
    }

    public function uploadTugas(Request $request, $id)
    {
        $request->validate([
            'file_tugas' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ]);

        $tugas = TugasPegawai::findOrFail($id);

        $file = $request->file('file_tugas');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/tugas'), $namaFile);

        $tugas->file_tugas = $namaFile;
        $tugas->save();

        return back()->with('success', 'File tugas berhasil diupload');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:4|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
