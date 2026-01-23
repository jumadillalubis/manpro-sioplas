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
        $nama = session('user_nama');
        $divisi = session('user_divisi');

        // 1. Fetch & Filter Tasks (Assigned to Me or My Division)
        $assignedTasks = [];
        $totalTugas = 0;
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter Logic: Assigned to Me/Division AND Source is 'katimja'
                $filtered = array_filter($data, function($item) use ($nama, $divisi) {
                     $isPersonal = isset($item['penerima']) && $item['penerima'] === $nama;
                     $isTeam = isset($item['penerima']) && $item['penerima'] === $divisi; // Assigned to Division Name
                     $isDivisionTask = isset($item['divisi']) && $item['divisi'] === $divisi; // Assigned by Divisi field
                     
                     // Must be from Katimja
                     $isFromKatimja = isset($item['source']) && $item['source'] === 'katimja';

                     return ($isPersonal || $isTeam || $isDivisionTask) && $isFromKatimja;
                });
                
                $totalTugas = count($filtered); // Total Assigned

                // Format for Dashboard
                foreach ($filtered as $item) {
                    $assignedTasks[] = [
                        'id' => $item['id'],
                        'judul' => $item['judul'],
                        'deadline' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                        // Determine Type: if 'divisi' field is set, it's likely Team, else Personal? 
                        // Or use 'type' field if available from backend (we added it recently)
                        'type' => (!empty($item['divisi']) || (isset($item['type']) && $item['type'] == 'Team')) ? 'Team' : 'Personal',
                        'status' => $item['status'] ?? 'Pending',
                        'id_tugas' => 'T' . str_pad($item['id'], 5, '0', STR_PAD_LEFT)
                    ];
                }
            }
        } catch (\Exception $e) {
            // silent fail
        }

        // 2. Count Laporan (Optional, keep 0 if not needed or fetch if logic exists)
        // Assuming Laporan model exists and has 'pembuat' or 'dibuat_oleh'? Or just count all?
        // For now, let's just use a placeholder or previous logic if available.
        $totalLaporan = 0; 
        // Example: $totalLaporan = Laporan::where('pembuat', $nama)->count(); (need to verify model)

        return view('Staff.beranda_staff', compact('assignedTasks', 'totalTugas', 'totalLaporan'));
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
                
                // Filter: Assigned to me OR my division
                $data = array_filter($data, function($item) use ($nama, $divisi) {
                     $isPersonal = isset($item['penerima']) && $item['penerima'] === $nama;
                     $isTeam = isset($item['penerima']) && $item['penerima'] === $divisi; // If assigned to Division Name
                     
                     // Also check 'divisi' field if your backend uses it for assignment logic
                     $isDivisionTask = isset($item['divisi']) && $item['divisi'] === $divisi;

                     return $isPersonal || $isTeam || $isDivisionTask;
                });

                $tugas = array_map(function($item) {
                   return (object) [
                       'id' => $item['id'],
                       'judul' => $item['judul'],
                       'status' => $item['status'] ?? 'Pending',
                       'tanggal_buat' => \Carbon\Carbon::parse($item['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                       'tenggat' => $item['deadline'], 
                       'kepada' => !empty($item['divisi']) ? $item['divisi'] : ($item['penerima'] ?? '-'),
                       'source' => $item['source'] ?? 'atasan'
                   ];
                }, $data);
            }
        } catch (\Exception $e) {
            // silent fail
        }

        return view('Staff.tugas_pegawai', compact('tugas'));
    }

    public function detailTugas(Request $request, $id)
    {
        $data_tugas = [];
        $source = $request->query('source', 'atasan');
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id . '?source=' . $source);
            if ($response->successful()) {
                $item = $response->json()['data'] ?? [];
                $data_tugas = [
                    'id' => $item['id'],
                    'judul' => $item['judul'],
                    'batas_waktu' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                    'pegawai' => $item['penerima'],
                    'deskripsi' => $item['deskripsi'],
                    'source' => $item['source'] ?? $source, // Pass source to view
                    'file_tugas' => $item['file_tugas'] ?? null,
                    'status' => $item['status'] ?? 'Pending',
                    'file_selesai' => $item['file_selesai'] ?? null,
                    'file_respon' => $item['file_respon'] ?? null,
                    'respon' => $item['respon'] ?? null
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
            $source = $request->query('source', 'atasan');
            try {
                \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/selesai?source=' . $source, [
                   'file_selesai' => $filename,
                   'role' => 'staff',
                   'uploader' => 'Staff ' . session('user_nama')
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
