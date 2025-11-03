<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatimjaController extends Controller
{
    public function beranda()
    {
        return view('Katimja.Beranda_Katimja');
    }

    public function laporan()
    {
        return view('Katimja.Laporan_Katimja');
    }
}
