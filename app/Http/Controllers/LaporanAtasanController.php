<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Staff;
use App\Models\YearlySummary;

class LaporanAtasanController extends Controller
{
    /**
     * Menampilkan daftar laporan semua staff (halaman utama laporan atasan)
     */
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        
        // Ambil semua laporan dengan relasi staff difilter berdasarkan tahun
        $laporan = Laporan::with('staff')
            ->whereYear('tanggal', $tahun)
            ->get();

        // Ambil rangkuman tahunan jika ada
        $yearlySummary = YearlySummary::where('tahun', $tahun)->first();

        return view('atasan.laporan_atasan', compact('laporan', 'tahun', 'yearlySummary'));
    }

    /**
     * Menampilkan detail laporan tertentu
     */
    public function show($id)
    {
        $laporan = Laporan::with('staff')->findOrFail($id);
        return view('atasan.detail_laporan', compact('laporan'));
    }

    /**
     * Memverifikasi laporan (disetujui atau ditolak)
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->catatan = $request->catatan ?? '-';
        $laporan->save();

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * (Opsional) Menghapus laporan tertentu
     */
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }
}
