<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-20">
    {{-- Logo --}}
    <div class="text-xl font-bold text-sky-500">
        SIOPLAS
    </div>

    {{-- Right Section --}}
    <div class="flex items-center gap-6">
        {{-- Notification --}}
        <div class="relative">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
            </svg>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
        </div>

        {{-- User --}}
        <div class="flex items-center gap-3">
            <img src="https://i.pravatar.cc/40" class="w-10 h-10 rounded-full border-2 border-sky-400 object-cover">

            <div class="leading-tight">
                <p class="text-sm font-semibold text-gray-700">
                    {{ Auth::user()->name ?? 'Budi Santoso' }}
                </p>
                <p class="text-xs text-gray-500">Pegawai</p>
            </div>
        </div>
    </div>
</header>
