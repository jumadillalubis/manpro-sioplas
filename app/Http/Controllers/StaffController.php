<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function beranda()
    {
        // Cek apakah view Staff beranda ada
        if (view()->exists('Staff.beranda_staff')) {
            return view('Staff.beranda_staff');
        }
        
        // Jika belum ada, buat view sederhana
        return view('Staff.beranda_staff');
    }
}
