<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    /**
     * Ambil notifikasi dari Go Backend
     */
    public static function getNotifications()
    {
        $jabatan = strtolower(session('user_jabatan', ''));
        $userId  = session('user_id');

        // Tentukan prefix ID sesuai logic notifikasi di Go
        $prefix = 'staff';
        if (str_contains($jabatan, 'atasan') || str_contains($jabatan, 'kepala dinas')) {
            $prefix = 'atasan';
        } elseif (str_contains($jabatan, 'katimja') || str_contains($jabatan, 'kepala tim')) {
            $prefix = 'katimja';
        }

        $penerimaId = "{$prefix}-{$userId}";

        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:8080/api/notifications', [
                'penerima_id' => $penerimaId
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                return collect($data)->map(function ($item) use ($prefix) { // Use prefix to determine role
                    
                    $tugasId = $item['tugas_id'] ?? null;
                    $url = '#';

                    if ($tugasId) {
                        if ($prefix === 'atasan') {
                            $url = route('tugas.show', $tugasId);
                        } elseif ($prefix === 'katimja') {
                            $url = route('katimja.tugas.show', $tugasId);
                        } else {
                            $url = route('tugas.detail', $tugasId);
                        }
                    }

                    return [
                        'id' => $item['id'],
                        'title' => $item['judul'], 
                        'message' => $item['pesan'], 
                        'role' => 'user', 
                        'is_read' => (($item['status'] ?? 'unread') !== 'unread') || (!empty($item['read_at']) && $item['read_at'] !== '0001-01-01T00:00:00Z'),
                        'url' => $url 
                    ];
                });
            }
        } catch (\Exception $e) {
        }

        return collect([]);
    }

    /**
     * Hitung notifikasi belum dibaca
     */
    public static function unreadCount()
    {
        return self::getNotifications()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Tandai notifikasi sebagai dibaca dan redirect ke URL tujuan
     */
    public function markAsReadAndRedirect(Request $request, $id)
    {
        try {
            // Panggil Go Backend untuk set read
            \Illuminate\Support\Facades\Http::put("http://localhost:8080/api/notifications/{$id}/read");
        } catch (\Exception $e) {
            // Abaikan error jika backend mati, tetap redirect
        }

        $redirectUrl = $request->query('redirect', '#');
        return redirect($redirectUrl);
    }

    /**
     * Endpoint JSON ringan untuk polling notifikasi real-time dari JavaScript.
     * Dipanggil setiap 30 detik oleh frontend tanpa reload halaman.
     */
    public function liveNotifications()
    {
        // Pastikan user sudah login (ada session)
        if (!session('user_id')) {
            return response()->json(['count' => 0, 'notifications' => []], 401);
        }

        $allNotifications = self::getNotifications();
        $unread           = $allNotifications->where('is_read', false);

        // Format notifikasi untuk dikirim ke JavaScript
        $notifList = $unread->map(function ($notif) {
            return [
                'id'      => $notif['id'],
                'title'   => $notif['title'],
                'message' => $notif['message'],
                'url'     => $notif['url'],
            ];
        })->values();

        return response()->json([
            'count'         => $unread->count(),
            'notifications' => $notifList,
        ]);
    }
}

