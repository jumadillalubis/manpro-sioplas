@extends('layouts.app')

@section('content')
@php
    $nama    = session('user_nama', 'User');
    $email   = session('user_email', 'user@email.com');
    $phone   = session('user_phone', '-');
    $jabatan = strtoupper(session('user_jabatan', 'STAFF'));
@endphp

<div class="p-8 max-w-4xl mx-auto">

    {{-- HEADER --}}
    <h1 class="text-2xl font-semibold text-gray-800 mb-8">Profile Settings</h1>

    {{-- CARD --}}
    <div class="bg-white p-8 rounded-lg shadow-xl border border-gray-100 flex">

        {{-- KIRI --}}
        <div class="w-1/3 flex flex-col items-center border-r border-gray-200 pr-8">
            <div class="w-28 h-28 mb-4 rounded-full overflow-hidden border-4 border-gray-300">
                <svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>

            <h2 class="text-xl font-semibold text-gray-800">{{ $nama }}</h2>
            <p class="text-sm text-gray-500">{{ $phone }}</p>
            <p class="text-sm text-blue-600">{{ $email }}</p>

            <span class="mt-4 px-4 py-1 rounded-full bg-gray-100 text-sm font-semibold">
                {{ $jabatan }}
            </span>
        </div>

        {{-- KANAN --}}
        <div class="w-2/3 pl-8 space-y-8">

            {{-- NOTIFIKASI --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Notifikasi</h3>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Pengingat Laporan</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:h-5 after:w-5 after:rounded-full
                            peer-checked:after:translate-x-full after:transition-all">
                        </div>
                    </label>
                </div>
            </div>

            {{-- PREFERENSI --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Preferensi Pengingat</h3>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Harian</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            {{-- BAHASA --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Bahasa</h3>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Bahasa Indonesia</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Password</h3>
                <a href="{{ route('settings.password') }}"
                   class="flex justify-between items-center py-2 border-b hover:bg-gray-50 px-2 rounded">
                    <span class="text-gray-600">Ubah Password</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            {{-- LOGOUT --}}
            <div class="pt-8">
                <a href="{{ route('logout') }}">
             <button
                        class="w-1/2 py-3 text-lg font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow mx-auto block">
                        Keluar
                    </button>
        </a>
            </div>

        </div>
    </div>
</div>
@endsection
