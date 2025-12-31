@php
    $jabatan = strtolower(session('user_jabatan', ''));

    // Deteksi role sederhana dari session
    $isKatimja = str_contains($jabatan, 'katimja') || str_contains($jabatan, 'kaptimja') || str_contains($jabatan, 'kepala tim');
    $isStaff   = str_contains($jabatan, 'staff') || str_contains($jabatan, 'pegawai') || str_contains($jabatan, 'bidang');
    $isAtasan  = str_contains($jabatan, 'atasan') || str_contains($jabatan, 'kepala dinas') || ($jabatan === 'kepala');

    // Default: kalau tidak terdeteksi, anggap staff (biar tidak blank)
    if(!$isKatimja && !$isStaff && !$isAtasan){
        $isStaff = true;
    }
@endphp

<aside class="w-64 bg-white border-r border-gray-200 min-h-screen pt-6 flex flex-col justify-between">

    {{-- Menu --}}
    <nav class="space-y-1 px-4">

        {{-- =========================
             MENU KATIMJA
        ========================== --}}
        @if($isKatimja)

            {{-- Beranda Katimja --}}
            <a href="{{ route('beranda.katimja') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('beranda.katimja') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2 7-7 7 7 2 2M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3" />
                </svg>
                Beranda
            </a>

            {{-- Laporan Katimja --}}
            <a href="{{ route('laporan.katimja') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('laporan.katimja') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 17v-6h13M9 5v4h13M5 5h.01M5 11h.01M5 17h.01" />
                </svg>
                Laporan
            </a>

            {{-- Tugas Katimja --}}
            <a href="{{ route('tugas.katimja') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('tugas.katimja') || request()->routeIs('tugas.katimja.show') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6M9 8h6M5 6h.01M5 12h.01M5 18h.01" />
                </svg>
                Tugas
            </a>

            <li>
                <a href="{{ route('tugas.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('tugas.create') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="bi bi-plus-circle"></i> Buat Tugas Baru
                </a>
            </li>


        {{-- =========================
             MENU STAFF (KODE LAMA KAMU)
        ========================== --}}
        @elseif($isStaff)

            {{-- Dashboard --}}
            <a href="{{ route('beranda.staff') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('beranda.staff') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2 7-7 7 7 2 2M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3" />
                </svg>
                Dashboard
            </a>

            {{-- Laporan --}}
            <a href="{{ route('laporan.pegawai') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('laporan.pegawai') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 17v-6h13M9 5v4h13M5 5h.01M5 11h.01M5 17h.01" />
                </svg>
                Laporan
            </a>

            {{-- Tugas --}}
            <a href="{{ route('tugas.pegawai') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('tugas.pegawai') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6M9 8h6M5 6h.01M5 12h.01M5 18h.01" />
                </svg>
                Tugas
            </a>

        {{-- =========================
             MENU ATASAN (placeholder aman)
             Kalau route atasan belum ada selain beranda, pakai url('#') supaya tidak error
        ========================== --}}
        @else

            <a href="{{ route('beranda.atasan') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('beranda.atasan') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2 7-7 7 7 2 2M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3" />
                </svg>
                Beranda
            </a>

            <a href="{{ url('#') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 17v-6h13M9 5v4h13M5 5h.01M5 11h.01M5 17h.01" />
                </svg>
                Laporan
            </a>

            <a href="{{ url('#') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6M9 8h6M5 6h.01M5 12h.01M5 18h.01" />
                </svg>
                Tugas
            </a>

        @endif
    </nav>

    {{-- Bottom --}}
    <div class="px-4 pb-6 space-y-1">

        {{-- Settings: hanya staff yang punya route pegawai.settings di project kamu sekarang --}}
        @if($isStaff)
            <a href="{{ route('pegawai.settings') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg
               {{ request()->routeIs('pegawai.settings') ? 'bg-sky-400 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0" />
                </svg>
                Settings
            </a>
        @else
            <a href="{{ url('#') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0" />
                </svg>
                Settings
            </a>
        @endif

        {{-- Logout (sesuaikan dengan metode logout kamu, ini contoh sederhana) --}}
        <a href="{{ route('logout') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 16l4-4m0 0l-4-4m4 4H7" />
            </svg>
            Logout
        </a>

    </div>
</aside>
