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

        // Prioritas 2: Tebak dari jabatan jika session kosong
        if (empty($divisi)) {
            $jabatan = session('user_jabatan');
            $divisionMap = [
                'Tata Usaha' => 'Tata Usaha', 
                'Produksi Primer' => 'Produksi Primer', 
                'Pasca Panen' => 'Pasca Panen', 
                'Labor' => 'Labor',
                'Lab' => 'Labor'
            ];

            foreach ($divisionMap as $key => $val) {
                if (stripos($jabatan, $key) !== false) {
                    $divisi = $val;
                    break;
                }
            }
        }

        // 1. Fetch & Filter Tasks (Assigned to Me or My Division)
        $assignedTasks = [];
        $totalTugas = 0;
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter Logic: Assigned to Me/Division
                $filtered = array_filter($data, function($item) use ($nama, $divisi) {
                     $isPersonal = isset($item['penerima']) && $item['penerima'] === $nama;
                     $isTeam = isset($item['penerima']) && $item['penerima'] === $divisi; // Assigned to Division Name
                     $isDivisionTask = isset($item['divisi']) && $item['divisi'] === $divisi; // Assigned by Divisi field

                     $source = $item['source'] ?? 'atasan';
                     if ($source === 'atasan') {
                         // Staff only sees Atasan task if delegated directly to them
                         return $isPersonal;
                     } else {
                         // Staff sees Katimja task if assigned directly to them or to their division
                         return $isPersonal || $isTeam || $isDivisionTask;
                     }
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

        // 2. Hitung Total Laporan (berdasarkan divisi)
        $totalLaporan = 0;
        try {
            if ($divisi) {
                $totalLaporan = Laporan::where('devisi', $divisi)->count();
            }
        } catch (\Exception $e) {
            $totalLaporan = 0;
        }

        return view('Staff.beranda_staff', compact('assignedTasks', 'totalTugas', 'totalLaporan'));
    }

    public function laporan(Request $request)
    {
        $divisi = session('user_divisi');
        if (empty($divisi)) {
            $jabatan = session('user_jabatan');
            $divisionMap = [
                'Tata Usaha' => 'Tata Usaha', 
                'Produksi Primer' => 'Produksi Primer', 
                'Pasca Panen' => 'Pasca Panen', 
                'Labor' => 'Labor',
                'Lab' => 'Labor'
            ];
            foreach ($divisionMap as $key => $val) {
                if (stripos($jabatan, $key) !== false) {
                    $divisi = $val;
                    break;
                }
            }
        }
        
        $tahun = $request->query('tahun', date('Y'));
        $laporan = Laporan::where('devisi', $divisi)
            ->whereYear('tanggal', $tahun)
            ->get();

        return view('Staff.laporan_pegawai', compact('laporan', 'tahun'));
    }

    public function tugas()
    {
        $nama = session('user_nama');
        $divisi = session('user_divisi'); 

        // Prioritas 2: Tebak dari jabatan jika session kosong
        if (empty($divisi)) {
            $jabatan = session('user_jabatan');
            $divisionMap = [
                'Tata Usaha' => 'Tata Usaha', 
                'Produksi Primer' => 'Produksi Primer', 
                'Pasca Panen' => 'Pasca Panen', 
                'Labor' => 'Labor',
                'Lab' => 'Labor'
            ];

            foreach ($divisionMap as $key => $val) {
                if (stripos($jabatan, $key) !== false) {
                    $divisi = $val;
                    break;
                }
            }
        }

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

                     $source = $item['source'] ?? 'atasan';
                     if ($source === 'atasan') {
                         // Staff only sees Atasan task if delegated directly to them
                         return $isPersonal;
                     } else {
                         // Staff sees Katimja task if assigned directly to them or to their division
                         return $isPersonal || $isTeam || $isDivisionTask;
                     }
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
                return view('Staff.detail_tugas', compact('data_tugas'));
            }
        } catch (\Exception $e) {
            // handle error
        }

        return redirect()->route('tugas.staff')->with('error', 'Tugas tidak ditemukan.');
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
            'tahun' => 'nullable|integer',
        ]);

        $divisi = session('user_divisi') ?? 'Unknown';
        $tahun = $request->input('tahun', date('Y'));

        // Cek jika laporan sudah ada untuk indikator, triwulan, devisi, dan tahun ini
        $existing = Laporan::where('indikator_id', $request->indikator_id)
            ->where('triwulan', $request->tw)
            ->where('devisi', $divisi)
            ->whereYear('tanggal', $tahun)
            ->first();
            
        if ($existing && ($existing->status === 'disetujui' || $existing->status === 'Terverifikasi')) {
            return back()->with('error', 'Laporan triwulan ini telah disetujui oleh Atasan dan tidak dapat diubah.');
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('laporan', $filename, 'public');

        $tanggal = now()->setYear($tahun);

        // Logic Save to DB (Laporan Table)
        try {
            \Illuminate\Support\Facades\Log::info('Attempting to save Laporan', [
                'indikator_id' => $request->indikator_id,
                'triwulan' => $request->tw,
                'filename' => $filename
            ]);

            if ($existing) {
                $existing->update([
                    'lampiran' => 'laporan/' . $filename,
                    'status' => 'Menunggu Verifikasi',
                    'tanggal' => $tanggal,
                ]);
            } else {
                Laporan::create([
                    'indikator_id' => $request->indikator_id,
                    'triwulan' => $request->tw,
                    'devisi' => $divisi,
                    'lampiran' => 'laporan/' . $filename,
                    'status' => 'Menunggu Verifikasi',
                    'tanggal' => $tanggal,
                ]);
            }

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
