<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiSummrizeController extends Controller
{
    public function summarize(Request $request)
    {
        // Remove time limit (unlimited) execution to prevent timeout on very slow CPUs/large files
        set_time_limit(0);

        $request->validate([
            'indikator_id' => 'required|integer',
            'triwulan' => 'required|string|in:TW1,TW2,TW3,TW4'
        ]);

        $indikatorId = $request->indikator_id;
        $triwulan = $request->triwulan;

        // Ambil semua laporan yang sesuai dan punya lampiran
        $laporans = \App\Models\Laporan::where('indikator_id', $indikatorId)
                                       ->where('triwulan', $triwulan)
                                       ->whereNotNull('lampiran')
                                       ->get();

        if ($laporans->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada laporan dengan lampiran ditemukan.'], 404);
        }

        try {
            \Log::info("Starting AI Summarize for Indikator $indikatorId ($triwulan) with " . $laporans->count() . " reports.");
            
            // Siapkan request ke Python service
            // Use 127.0.0.1 to avoid localhost resolution issues
            // Timeout increased to 3600s (1 hour)
            $httpRequests = Http::asMultipart()->timeout(3600)->connectTimeout(10);
            $fileAttached = false;

            foreach ($laporans as $laporan) {
                $path = storage_path('app/public/' . $laporan->lampiran);
                if (file_exists($path)) {
                    $httpRequests->attach(
                        'files', file_get_contents($path), basename($path)
                    );
                    $fileAttached = true;
                } else {
                    \Log::warning("File laporan tidak ditemukan di path: " . $path);
                }
            }

            if (!$fileAttached) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal: File fisik laporan tidak ditemukan di server.'
                ], 404);
            }

            // Kirim ke Python (Port 8002) using IPv4 explicitly
            $response = $httpRequests->post('http://127.0.0.1:8002/summarize-pdf');

            if ($response->successful()) {
                $summary = $response->json()['summary'] ?? 'Ringkasan tidak tersedia.';
                
                // Simpan summary ke SEMUA laporan yang terlibat (agar sinkron)
                \App\Models\Laporan::where('indikator_id', $indikatorId)
                                   ->where('triwulan', $triwulan)
                                   ->update(['laporan_summary' => $summary]);

                return response()->json([
                    'status' => 'success',
                    'summary' => $summary,
                    'count' => $laporans->count()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses di server AI (Python): ' . $response->status() . ' - ' . $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
