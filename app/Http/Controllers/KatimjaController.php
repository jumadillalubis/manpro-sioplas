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

                    return $isPembuat || $isDivisi || $isPenerima;
                });

                $tugas = array_map(function($item) {
                   return [
                       'id' => $item['id'],
                       'judul' => $item['judul'],
                       'status' => $item['status'] ?? 'Pending',
                       'tanggal_buat' => \Carbon\Carbon::parse($item['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                       'deadline' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                       'kepada' => !empty($item['divisi']) ? $item['divisi'] : ($item['penerima'] ?? '-')
                   ];
                }, $data);
            }
        } catch (\Exception $e) {
            // silent fail, empty tasks
        }

        return view('Katimja.Tugas_Katimja', compact('tugas'));
    }

    // Menampilkan Detail Tugas
    public function showTugas($id)
    {
        $tugas = [];
        $staff = [];
        try {
            // Get Task Detail
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id);
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $tugas = [
                    'id' => $data['id'],
                    'judul' => $data['judul'],
                    'deskripsi' => $data['deskripsi'],
                    'status' => $data['status'],
                    'batas_waktu' => \Carbon\Carbon::parse($data['deadline'])->translatedFormat('d F Y'),
                    'penerima' => $data['penerima'] ?: $data['divisi'],
                    'divisi' => $data['divisi'], // Pass divisi for logic check
                    'file_selesai' => $data['file_selesai'] ?? null,
                    'respon' => $data['respon'] ?? null,
                    'file_respon' => $data['file_respon'] ?? null
                ];
            }

            // Get Staff List (for assignment dropdown)
            $respStaff = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/staff');
            if ($respStaff->successful()) {
                $staffData = $respStaff->json()['data'] ?? [];
                $staff = array_map(function($item) {
                    return (object) $item;
                }, $staffData);
            }

        } catch (\Exception $e) {
            // Error handling
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
            'penerima' => 'required|string',
            'file' => 'nullable|file|max:10240' 
        ]);

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
                'penerima' => $request->penerima,
                'pembuat' => session('user_nama'), // Matches json:"pembuat"
                'status' => 'Pending',
                'divisi' => session('user_divisi') ?? '' // Optional: Send division if available
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
        try {
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/selesai', [
                'file_selesai' => $filename,
                'role' => 'katimja'
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
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id, [
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
}
