@extends('layouts.app')

@section('content')

<div class="p-8 max-w-4xl mx-auto">

    {{-- HEADER HALAMAN --}}
    <h1 class="text-2xl font-semibold text-gray-800 mb-8">Profile Settings</h1>

    {{-- CARD UTAMA --}}
    <div class="bg-white p-8 rounded-lg shadow-xl border border-gray-100 flex">

        {{-- BAGIAN KIRI: PROFILE SUMMARY --}}
        <div class="w-1/3 flex flex-col items-center border-r border-gray-200 pr-8">
            {{-- Foto Profil --}}
            <div class="w-28 h-28 mb-4 rounded-full overflow-hidden border-4 border-gray-300">
                {{-- Anda dapat mengganti ini dengan <img src="..."> --}}
                <svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>

            {{-- Nama dan Info Kontak --}}
            <h2 class="text-xl font-semibold text-gray-800">Budi Susanto</h2>
            <p class="text-sm text-gray-500">081234567890</p>
            <p class="text-sm text-blue-600">budi@kipm.go.id</p>
        </div>

        {{-- BAGIAN KANAN: SETTINGS FORM --}}
        <div class="w-2/3 pl-8 space-y-8">

            {{-- 1. NOTIFIKASI --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Notifikasi</h3>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Pemberitahuan Pengingat Laporan</span>
                    {{-- Toggle Switch --}}
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            {{-- 2. PREFERENSI PENGINGAT --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Preferensi Pengingat</h3>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Harian</span>
                    {{-- Dropdown Icon --}}
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            {{-- 3. BAHASA --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Bahasa</h3>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Bahasa Indonesia</span>
                    {{-- Dropdown Icon --}}
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            {{-- 4. PASSWORD --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Password</h3>
                <a href="{{ route('pegawai.change.password') }}" class="flex justify-between items-center py-2 border-b border-gray-100 cursor-pointer">
                    <span class="text-gray-600">Ubah Password</span>
                    {{-- Chevron Right Icon --}}
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
            </div>

            {{-- Tombol Keluar --}}
            <div class="pt-8">
                <button class="w-1/2 py-3 text-lg font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150 shadow-lg mx-auto block">
                    Keluar
                </button>
            </div>
        </div>

    </div>

</div>

@endsection