<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    /**
     * Ambil notifikasi (dummy / sementara)
     */
    public static function getNotifications()
    {
        $jabatan = strtolower(session('user_jabatan', ''));
        $userId  = session('user_id');

        $notifications = collect([
            [
                'id' => 1,
                'role' => 'atasan',
                'title' => 'Laporan Baru',
                'message' => 'Ada laporan masuk dari staff',
                'url' => route('laporan.atasan'),
                'is_read' => false,
            ],
            [
                'id' => 2,
                'role' => 'katimja',
                'title' => 'Tugas Perlu Review',
                'message' => 'Ada tugas yang perlu dicek',
                'url' => route('tugas.katimja'),
                'is_read' => false,
            ],
            [
                'id' => 3,
                'role' => 'staff',
                'title' => 'Tugas Baru',
                'message' => 'Kamu mendapatkan tugas baru',
                'url' => route('tugas.staff'),
                'is_read' => true,
            ],
        ]);

        // Filter sesuai jabatan
        return $notifications->filter(function ($notif) use ($jabatan) {
            return str_contains($jabatan, $notif['role']);
        });
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
}
