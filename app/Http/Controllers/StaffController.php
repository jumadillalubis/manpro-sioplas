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
        $nama = session('user_nama');
        $divisi = session('user_divisi'); 

        $tugas = [];
        try {
            // Fetch ALL tasks
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter: Only assigned to me
                $data = array_filter($data, function($item) use ($nama) {
                     return isset($item['penerima']) && $item['penerima'] === $nama;
                });

                $tugas = array_map(function($item) {
                   return (object) [
                       'id' => $item['id'],
                       'judul' => $item['judul'],
                       'status' => $item['status'] ?? 'Pending',
                       'tanggal_buat' => \Carbon\Carbon::parse($item['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                       'tenggat' => $item['deadline'], 
                       'kepada' => !empty($item['divisi']) ? $item['divisi'] : ($item['penerima'] ?? '-')
                   ];
                }, $data);
            }
        } catch (\Exception $e) {
            // silent fail
        }

        return view('Staff.tugas_pegawai', compact('tugas'));
    }

    public function detailTugas($id)
    {
        $data_tugas = [];
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id);
            if ($response->successful()) {
                $item = $response->json()['data'] ?? [];
                $data_tugas = [
                    'id' => $item['id'],
                    'judul' => $item['judul'],
                    'batas_waktu' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                    'pegawai' => $item['penerima'],
                    'deskripsi' => $item['deskripsi']
                ];
            }
        } catch (\Exception $e) {
            // handle error
        }

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
        \Illuminate\Support\Facades\Log::info('Staff Upload Hit', $request->all());

        $request->validate([
            'indikator_id' => 'required',
            'tw' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,jpg,png|max:5120',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('laporan', $filename, 'public');

        // Logic Save to DB (Laporan Table)
        try {
            \Illuminate\Support\Facades\Log::info('Attempting to save Laporan', [
                'indikator_id' => $request->indikator_id,
                'triwulan' => $request->tw,
                'filename' => $filename
            ]);

            $laporan = Laporan::updateOrCreate(
                [
                    'indikator_id' => $request->indikator_id,
                    'triwulan' => $request->tw,
                ],
                [
                    'lampiran' => 'laporan/' . $filename,
                    'status' => 'Menunggu Verifikasi',
                    'devisi' => session('user_divisi') ?? 'Unknown',
                    'tanggal' => now(),
                ]
            );
            
            \Illuminate\Support\Facades\Log::info('Laporan saved:', $laporan->toArray());

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving Laporan: ' . $e->getMessage());
            dd($e->getMessage()); // Force stop to show error to user if they try again
        }

        return back()->with('success', 'File berhasil diupload dan disimpan!');
    }

    public function uploadTugas(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tugas_selesai', $filename, 'public');

            // Call API to update status to "Selesai" and save filename
            try {
                \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/selesai', [
                   'file_selesai' => $filename,
                   'role' => 'staff'
                ]);
            } catch (\Exception $e) {
                // ignore or log
            }
        }
        
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
