<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatimjaController extends Controller
{
    // Menampilkan Beranda untuk Katimja
    public function beranda()
    {
        $nama = session('user_nama');
        $divisi = session('user_divisi'); 

        // Prioritas 2: Tebak dari jabatan jika session kosong (Logic reused from tugas())
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

        // 1. Hitung Total Tugas & Fetch Created Tasks
        $totalTugas = 0;
        $createdTasks = [];
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter for Total Count (Incoming + Created)
                $filteredTotal = array_filter($data, function($item) use ($nama, $divisi) {
                    $isPembuat = isset($item['pembuat']) && $item['pembuat'] === $nama;
                    $isDivisi = isset($item['divisi']) && $item['divisi'] === $divisi;
                    $isPenerima = isset($item['penerima']) && $item['penerima'] === $nama;
                    return $isPembuat || $isDivisi || $isPenerima;
                });
                $totalTugas = count($filteredTotal);

                // Filter for "Daftar Tugas Katimja" (Only Created by Me)
                $createdTasksRaw = array_filter($data, function($item) use ($nama) {
                     return isset($item['pembuat']) && $item['pembuat'] === $nama;
                });

                // Format for View
                foreach ($createdTasksRaw as $item) {
                    $createdTasks[] = [
                        'id' => $item['id'],
                        'judul' => $item['judul'],
                        'deadline' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                        'type' => !empty($item['divisi']) ? 'Team' : 'Personal',
                        'status' => $item['status'] ?? 'Pending',
                        'id_tugas' => 'T' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) // Format ID e.g., T00123
                    ];
                }
            }
        } catch (\Exception $e) {
            $totalTugas = 0;
            $createdTasks = [];
        }

        // 2. Hitung Total Laporan (berdasarkan divisi)
        $totalLaporan = 0;
        try {
            if ($divisi) {
                $totalLaporan = \App\Models\Laporan::where('devisi', $divisi)->count();
            }
        } catch (\Exception $e) {
             $totalLaporan = 0;
        }

        return view('Katimja.Beranda_Katimja', compact('totalTugas', 'totalLaporan', 'createdTasks'));
    }

    // Menampilkan Laporan untuk Katimja
    public function laporan()
    {
        return view('Katimja.Laporan_Katimja');
    }

    // Menampilkan Daftar Tugas untuk Katimja
    public function tugas()
    {
        $nama = session('user_nama');
        $divisi = session('user_divisi'); // Prioritas 1: Divisi dari session (Backend)

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
            // Fetch ALL tasks (so we can see both sent and received)
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter Logic
                // 1. Created by me (Sent)
                // 2. Assigned to my Division (Received from Atasan)
                // 3. Assigned to me (Directly received, though usually via division)
                
                $data = array_filter($data, function($item) use ($nama, $divisi) {
                    $isPembuat = isset($item['pembuat']) && $item['pembuat'] === $nama;
                    $isDivisi = isset($item['divisi']) && $item['divisi'] === $divisi;
                    $isPenerima = isset($item['penerima']) && $item['penerima'] === $nama;

                    // Show only if I am the recipient/division AND I am NOT the creator
                    // This creates the "Tugas dari Atasan" view.
                    return ($isDivisi || $isPenerima) && !$isPembuat;
                });

                $tugas = array_map(function($item) {
                   return [
                       'id' => $item['id'],
                       'judul' => $item['judul'],
                       'status' => $item['status'] ?? 'Pending',
                       'tanggal_buat' => \Carbon\Carbon::parse($item['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                       'deadline' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                       'kepada' => !empty($item['divisi']) ? $item['divisi'] : ($item['penerima'] ?? '-'),
                       'source' => $item['source'] ?? 'atasan' // Map source
                   ];
                }, $data);
            }
        } catch (\Exception $e) {
            // silent fail, empty tasks
        }

        return view('Katimja.Tugas_Katimja', compact('tugas'));
    }

    // Menampilkan Detail Tugas
    public function showTugas(Request $request, $id)
    {
        $tugas = [];
        $staff = [];
        $source = $request->query('source', 'atasan'); // Default to atasan if missing

        try {
            // Get Task Detail (PASS SOURCE)
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id . '?source=' . $source);
            
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Populate Tugas Array
                $tugas = [
                    'id' => $data['id'],
                    'source' => $data['source'] ?? $source,
                    'judul' => $data['judul'],
                    'deskripsi' => $data['deskripsi'],
                    'status' => $data['status'],
                    'batas_waktu' => \Carbon\Carbon::parse($data['deadline'])->translatedFormat('d F Y'),
                    'penerima' => $data['penerima'] ?: $data['divisi'],
                    'divisi' => $data['divisi'],
                    'file_selesai' => $data['file_selesai'] ?? null,
                    'file_selesai_oleh' => $data['file_selesai_oleh'] ?? null,
                    'respon' => $data['respon'] ?? null,
                    'file_respon' => $data['file_respon'] ?? null,
                    'pembuat' => $data['pembuat'] ?? null,
                    'is_me' => ($data['pembuat'] ?? '') === session('user_nama'),
                    'is_atasan_task' => $source === 'atasan',
                ];

                // Get Staff List (Only if task is found)
                $respStaff = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/staff');
                if ($respStaff->successful()) {
                    $staffData = $respStaff->json()['data'] ?? [];
                    $staff = array_map(function($item) {
                        return (object) $item;
                    }, $staffData);
                }

            } else {
                return redirect()->route('tugas.katimja')->with('error', 'Tugas tidak ditemukan atau terjadi kesalahan server.');
            }

        } catch (\Exception $e) {
            return redirect()->route('tugas.katimja')->with('error', 'Terjadi kesalahan koneksi: ' . $e->getMessage());
        }

        return view('Katimja.TugasDetail_Katimja', compact('tugas', 'staff'));
    }

    // Menampilkan Form untuk Buat Tugas Baru
    public function createTugas()
    {
        $staff = [];
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/staff');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                // Convert array to object to match view expectation ($s->nama)
                $staff = array_map(function($item) {
                    return (object) $item;
                }, $data);
            }
        } catch (\Exception $e) {
            // Fallback to empty list or handle error
        }

        return view('Katimja.TugasCreate_Katimja', compact('staff'));
    }

    // Menyimpan Tugas Baru
    public function storeTugas(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tenggat' => 'required|date',
            'tipe_tugas' => 'required|in:personal,tim',
            'penerima' => 'nullable|string',
            'file' => 'nullable|file|max:10240' 
        ]);

        // Logic Penentuan Penerima based on Type
        $penerima = $request->penerima;
        $divisiUser = session('user_divisi');

        if ($request->tipe_tugas === 'tim') {
            // Assign to whole division
            if (empty($divisiUser)) {
                return back()->with('error', 'Gagal: Divisi Anda tidak terdeteksi.');
            }
            $penerima = $divisiUser;
        } else {
            // Personal
            if (empty($penerima)) {
                return back()->with('error', 'Harap pilih staff untuk tugas personal.');
            }
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tugas_files', $filename, 'public');
            $filePath = $filename; // Send filename only, or relative path 'tugas_files/' . $filename
        }

        try {
            // Format date to ISO 8601 for Go binding
            $tenggatIso = \Carbon\Carbon::parse($request->tenggat)->startOfDay()->toIso8601String();

            $response = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/tugas', [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_tugas' => $filePath, // Matches json:"file_tugas"
                'deadline' => $tenggatIso, // Matches json:"deadline"
                'penerima' => $penerima,   // Matches json:"penerima"
                'pembuat' => session('user_nama'), // Matches json:"pembuat"
                'status' => 'Pending',
                'divisi' => $divisiUser ?? '' // Optional: Send division if available
            ]);

            if ($response->successful()) {
                return redirect()->route('tugas.katimja')->with('success', 'Tugas berhasil dibuat');
            } else {
                return back()->with('error', 'Gagal membuat tugas di server: ' . $response->body());
            }

        } catch (\Exception $e) {
             return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menyimpan Respon Tugas (Upload File & Kirim ke Atasan)
    public function respon(Request $request, $id)
    {
        $request->validate([
            'upload-file' => 'nullable|file|max:10240'
        ]);

        $filename = "";
        if ($request->hasFile('upload-file')) {
            $file = $request->file('upload-file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tugas_selesai', $filename, 'public');
        }

        // Kirim ke Backend API untuk update status & file
        // Kirim ke Backend API untuk update status & file
        // Pass source from request query or input
        $source = $request->query('source', 'atasan'); 

        try {
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/selesai?source=' . $source, [
                'file_selesai' => $filename,
                'role' => 'katimja',
                'uploader' => 'Katimja ' . session('user_nama')
            ]);

                if ($response->successful()) {
                    return back()->with('success', 'Tugas berhasil dikirim ke Atasan!');
                } else {
                    return back()->with('error', 'Gagal update status di server.');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan koneksi ke server.');
            }
        }
    // Assign Tugas ke Staff
    public function assign(Request $request, $id)
    {
        $request->validate([
            'penerima' => 'required|string'
        ]);

        try {
            $source = $request->query('source', 'atasan');
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '?source=' . $source, [
                'penerima' => $request->penerima
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Tugas berhasil ditugaskan ke ' . $request->penerima);
            } else {
                return back()->with('error', 'Gagal update tugas di server.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi.');
        }
    }

    public function settings()
    {
        return view('Katimja.Settings_Katimja');
    }

    public function changePassword()
    {
        return view('Katimja.password');
    }

    public function notifikasi()
    {
        return view('Katimja.Notifikasi_Katimja');
    }
    public function uploadLaporan(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Katimja Upload Hit', $request->all());

        $request->validate([
            'indikator_id' => 'required',
            'tw' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,jpg,png|max:5120',
        ]);

        \Illuminate\Support\Facades\Log::info('Validation Passed');

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('laporan', $filename, 'public');

        try {
            // Logic Save to DB (Same as Staff)
            \App\Models\Laporan::updateOrCreate(
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
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return back()->with('success', 'File laporan berhasil diupload!');
    }
    public function approveTugas($id)
    {
        // Call Go API to approve task (Katimja specific)
        $response = \Illuminate\Support\Facades\Http::put("http://localhost:8080/api/tugas/katimja/{$id}/approve");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Tugas berhasil disetujui!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyetujui tugas: ' . $response->body());
        }
    }
}
