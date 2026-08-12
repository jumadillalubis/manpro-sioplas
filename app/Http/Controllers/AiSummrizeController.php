<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiSummrizeController extends Controller
{
    public function summarize(Request $request)
    {
        // Remove time limit (unlimited) execution to prevent timeout
        set_time_limit(0);

        $request->validate([
            'indikator_id' => 'required|integer',
            'triwulan' => 'required|string|in:TW1,TW2,TW3,TW4'
        ]);

        $indikatorId = $request->indikator_id;
        $triwulan = $request->triwulan;

        try {
            \Log::info("Starting AI Summarize for Indikator $indikatorId ($triwulan) via Go Backend + Gemini API");

            // Kirim ke Go Backend (Port 8080) sebagai JSON
            $response = Http::timeout(120)
                ->connectTimeout(10)
                ->post('http://127.0.0.1:8080/api/ai/summarize', [
                    'indikator_id' => (int) $indikatorId,
                    'triwulan' => $triwulan,
                ]);

            if ($response->successful()) {
                $summary = $response->json()['summary'] ?? 'Ringkasan tidak tersedia.';

                return response()->json([
                    'status' => 'success',
                    'summary' => $summary,
                    'count' => $response->json()['count'] ?? 0
                ]);
            } else {
                $errorMsg = $response->json()['message'] ?? $response->body();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses di server AI: ' . $response->status() . ' - ' . $errorMsg
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function summarizeYearly(Request $request)
    {
        set_time_limit(0);

        $request->validate([
            'tahun' => 'required|string'
        ]);

        $tahun = $request->tahun;

        try {
            \Log::info("Starting AI Yearly Summarize for year $tahun via Go Backend + Gemini API");

            $response = Http::timeout(120)
                ->connectTimeout(10)
                ->post('http://127.0.0.1:8080/api/ai/summarize-yearly', [
                    'tahun' => $tahun,
                ]);

            if ($response->successful()) {
                $summary = $response->json()['summary'] ?? 'Rangkuman tidak tersedia.';

                return response()->json([
                    'status' => 'success',
                    'summary' => $summary
                ]);
            } else {
                $errorMsg = $response->json()['message'] ?? $response->body();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses di server AI: ' . $response->status() . ' - ' . $errorMsg
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
