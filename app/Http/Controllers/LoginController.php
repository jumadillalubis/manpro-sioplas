<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function index()
    {
        // URL dari API Golang
        $apiUrl = 'http://localhost:8080/api/login';

        // Ambil data dari API menggunakan Laravel Http Client
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $data = $response->json()['data']; // ambil array 'data' dari JSON

            // kirim ke view Laravel
            return view('logins.index', compact('data'));
        } else {
            return view('logins.index')->with('error', 'Gagal mengambil data dari API');
        }
    }
}
