@extends('layouts.app')

@section('title', 'Pengaturan Profil - SIOPLAS')

@section('content')
    <!-- Judul -->
    <h2 class="text-2xl font-semibold mb-8">Pengaturan Profil</h2>

    <!-- Wrapper tengah -->
    <div class="flex justify-center">

        <!-- Konten utama -->
        <div class="w-full max-w-2xl bg-white p-6 rounded-lg shadow-md">

            <!-- PROFIL -->
            <div class="text-center mb-8">
                <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png"
                     alt="Avatar"
                     class="w-28 h-28 rounded-full mb-4">
                <h3 class="text-xl font-semibold mb-2">Rini Rina</h3>
                <p class="text-sm text-gray-600 mb-1">081234567890</p>
                <p class="text-sm text-gray-600">RiniRina@kipm.go.id</p>
            </div>

            <!-- NOTIFIKASI -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-2">Notifikasi</h4>
                <div class="flex justify-between items-center">
                    <p class="text-gray-700">Pemberitahuan Pengingat Laporan</p>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <!-- PREFERENSI PENGINGAT -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-2">Preferensi Pengingat</h4>
                <p class="text-gray-700">Harian</p>
            </div>

            <!-- BAHASA -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-2">Bahasa</h4>
                <p class="text-gray-700">Bahasa Indonesia</p>
            </div>

            <!-- PASSWORD -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold mb-2">Password</h4>
                <div class="flex justify-between items-center">
                    <a href="{{ route('atasan.ubah.password') }}" class="text-blue-600 underline">Ubah Password</a>
                    <span class="text-gray-600">›</span>
                </div>
            </div>

            <!-- LOGOUT -->
            <div class="flex justify-center">
                <button class="w-64 bg-red-600 text-white py-2 px-4 rounded-full text-lg font-semibold hover:bg-red-700">
                    Keluar
                </button>
            </div>
        </div>
    </div>

    <!-- Toggle style -->
    <style>
        .switch input { display: none; }
        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ccc;
            border-radius: 20px;
            transition: .3s;
        }
        .slider::before {
            content: "";
            position: absolute;
            width: 14px;
            height: 14px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }
        input:checked + .slider {
            background-color: #3DB9E3;
        }
        input:checked + .slider::before {
            transform: translateX(18px);
        }
    </style>
@endsection
