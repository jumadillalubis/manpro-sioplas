<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas');
            
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Filter: Hanya tampilkan tugas yang dibuat oleh saya (Atasan)
                $myName = session('user_nama');
                
                $data = array_filter($data, function($item) use ($myName) {
                    // Pastikan key 'pembuat' ada. Jika tidak, anggap bukan milik saya (atau sesuaikan logic)
                    return isset($item['pembuat']) && $item['pembuat'] === $myName;
                });

                // Format sesuai tampilan
                $tugas = array_map(function($item) {
                   $penerima = !empty($item['divisi']) ? $item['divisi'] : ($item['penerima'] ?? '-'); 
                   
                   return [
                       'id' => $item['id'],
                       'judul' => $item['judul'],
                       'tanggal_buat' => \Carbon\Carbon::parse($item['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                       'deadline' => \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y'),
                       'kepada' => $penerima
                   ];
                }, $data);
            } else {
                $tugas = [];
            }
        } catch (\Exception $e) {
            $tugas = [];
        }

        return view('Atasan.tugas', compact('tugas'));
    }

    public function create()
    {
        $staff = [];
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/staff');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $staff = array_map(function($item) {
                    return (object) $item;
                }, $data);
            }
        } catch (\Exception $e) {
            // handle error
        }
        return view('Atasan.tugas_create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tenggat' => 'required|date',
            // 'divisi' or 'pegawai' logic handled below
            'file' => 'nullable|file|max:10240'
        ]);

        $filePath = "";
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tugas_files', $filename, 'public');
            $filePath = $filename;
        }

        // Determine Divisi vs Penerima
        $divisi = $request->input('divisi');
        $penerima = $request->input('pegawai');

        // VALIDATION: Ensure at least one target is selected
        if (empty($divisi) && empty($penerima)) {
            return back()->with('error', 'Harap pilih Tujuan Tugas (Divisi atau Pegawai).')->withInput();
        }

        // Logic: If Divisi is selected, ensure Penerima is null (and vice versa) to avoid ambiguity
        // The frontend disables the other input, so usually one is null, but we enforce it here.
        if (!empty($divisi)) {
            $penerima = null; // Broadcast to Division
        } else {
            $divisi = null; // Specific Personal Assignment
        }
        
        try {
            $tenggatIso = \Carbon\Carbon::parse($request->tenggat)->startOfDay()->toIso8601String();

            $response = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/tugas', [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_tugas' => $filePath,
                'deadline' => $tenggatIso,
                'penerima' => $penerima,
                'divisi' => $divisi,
                'pembuat' => session('atasan_nama') ?? session('user_nama') ?? 'Atasan', 
                'status' => 'Pending'
            ]);

            if ($response->successful()) {
                return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat');
            } else {
                return back()->with('error', 'Gagal membuat tugas di server.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi.');
        }
    }

    public function show($id)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id);
            
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                $penerima = !empty($data['divisi']) ? $data['divisi'] : ($data['penerima'] ?? '-');

                $tugas = [
                    'id' => $data['id'],
                    'judul' => $data['judul'],
                    'pegawai' => $penerima,
                    'tanggal' => \Carbon\Carbon::parse($data['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                    'deadline' => \Carbon\Carbon::parse($data['deadline'])->translatedFormat('d F Y'),
                    'deskripsi' => $data['deskripsi'],
                    'status' => $data['status'] ?? 'Pending',
                    'file_selesai' => $data['file_selesai'] ?? null,
                    'respon' => $data['respon'] ?? null,
                    'file_respon' => $data['file_respon'] ?? null,
                ];
            } else {
                $tugas = [];
            }
        } catch (\Exception $e) {
            $tugas = [];
        }   

        return view('Atasan.tugas_detail', compact('tugas'));
    }

    public function approve($id)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/approve');
            
            if ($response->successful()) {
                return redirect()
                    ->route('tugas.show', $id)
                    ->with('success', 'Tugas berhasil disetujui (Approved).');
            } else {
                return back()->with('error', 'Gagal menyetujui tugas di server.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi.');
        }
    }

    public function lihat($id)
    {
        $tugas = [];
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/tugas/' . $id);
            
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                // Map API data to view structure
                // Note: 'dokumen' and 'komentar' might not exist in API yet, providing default empty arrays
                $tugas = [
                    'id' => $data['id'],
                    'judul' => $data['judul'],
                    'pegawai' => $data['penerima'], 
                    'tanggal' => \Carbon\Carbon::parse($data['tanggal_buat_tugas'])->translatedFormat('d F Y'),
                    'deadline' => \Carbon\Carbon::parse($data['deadline'])->translatedFormat('d F Y'), 
                    'deskripsi' => $data['deskripsi'],
                    'file_selesai' => $data['file_selesai'] ?? null,
                    'dokumen' => [], 
                    'komentar' => []
                ];

                // If API has file_tugas, add it to documents
                if (!empty($data['file_tugas'])) {
                    $tugas['dokumen'][] = [
                        'nama' => $data['file_tugas'],
                        'icon' => 'file' // generic icon
                    ];
                }

            }
        } catch (\Exception $e) {
            // handle error
        }

        return view('Atasan.tugas_lihat', compact('tugas'));
    }

    public function respon(Request $request, $id)
    {
        $request->validate([
            'respon' => 'nullable|string',
            'file_respon' => 'nullable|file|max:10240'
        ]);

        $filename = "";
        if ($request->hasFile('file_respon')) {
            $file = $request->file('file_respon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tugas_respon', $filename, 'public');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::put('http://localhost:8080/api/tugas/' . $id . '/respon', [
                'respon' => $request->respon ?? '',
                'file_respon' => $filename
            ]);

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengirim respon ke server.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi.');
        }

        return redirect()
            ->route('tugas.lihat', $id)
            ->with('success', 'Respon berhasil dikirim');
    }

}
