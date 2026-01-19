@php
    $jabatan = strtolower(session('user_jabatan', ''));

    $isAtasan  = str_contains($jabatan, 'atasan') || str_contains($jabatan, 'kepala dinas');
    $isKatimja = str_contains($jabatan, 'katimja') || str_contains($jabatan, 'kepala tim');
    $isStaff   = !$isAtasan && !$isKatimja;
@endphp

<aside>
    <div>
        <div class="sidebar-top">
            <div class="logo">
                <span>SIO</span><span>PLAS</span>
            </div>
        </div>

        <ul class="menu">

            {{-- ================= ATASAN ================= --}}
            @if($isAtasan)
                <li>
                    <a href="{{ route('beranda.atasan') }}"
                       class="{{ request()->routeIs('beranda.atasan') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan.atasan') }}"
                       class="{{ request()->routeIs('laporan.atasan*') ? 'active' : '' }}">
                        📁 Laporan
                    </a>
                </li>

                <li>
                    <a href="{{ route('tugas.index') }}"
                       class="{{ request()->routeIs('tugas.index','tugas.show','tugas.create') ? 'active' : '' }}">
                        📝 Tugas
                    </a>
                </li>

            {{-- ================= KATIMJA ================= --}}
            @elseif($isKatimja)
                <li>
                    <a href="{{ route('beranda.katimja') }}"
                       class="{{ request()->routeIs('beranda.katimja') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan.katimja') }}"
                       class="{{ request()->routeIs('laporan.katimja*') ? 'active' : '' }}">
                        📁 Laporan
                    </a>
                </li>

               <li>
        <a href="{{ route('tugas.katimja') }}"
           class="{{ request()->routeIs('tugas.katimja','tugas.create','tugas.store','tugas.show') ? 'active' : '' }}">
            📝 Tugas
        </a>
    </li>

            {{-- ================= STAFF ================= --}}
            @else
                <li>
                    <a href="{{ route('beranda.staff') }}"
                       class="{{ request()->routeIs('beranda.staff') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan.staff') }}"
                       class="{{ request()->routeIs('laporan.staff*') ? 'active' : '' }}">
                        📁 Laporan
                    </a>
                </li>

                <li>
                    <a href="{{ route('tugas.staff') }}"
                       class="{{ request()->routeIs('tugas.staff','tugas.detail') ? 'active' : '' }}">
                        📝 Tugas
                    </a>
                </li>
            @endif

        </ul>
    </div>

    {{-- ================= BOTTOM ================= --}}
    <div class="sidebar-bottom">

        <a href="{{ route('settings') }}"
           class="{{ request()->routeIs('settings*') ? 'active' : '' }}">
            <img src="https://img.icons8.com/?size=100&id=364&format=png" width="18">
            Settings
        </a>

        <a href="{{ route('logout') }}">
            <img src="https://img.icons8.com/?size=100&id=26215&format=png" width="18">
            Logout
        </a>

    </div>
</aside>
