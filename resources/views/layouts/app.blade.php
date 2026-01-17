<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'SIOPLAS')</title>

   @yield('styles')
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    
    /* Semua CSS dari kode asli kamu */
    :root{
      --accent:#48CAE4;
      --bg-card:#f1f4f5;
      --text:#2a3b27;
      --muted:#7a7f83;
      --success:#2ea44f;
      --warning:#e49d1b;
      --danger:#e04b3a;
    }
    *{box-sizing: border-box;}
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      min-height: 100vh;
      color: var(--text);
      background: #fff;
    }

    /* SIDEBAR */
    aside {
      width: 230px;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid #e6e6e6;
    }
    .sidebar-top { text-align: center; padding: 20px 0; border-bottom: 1px solid #e6e6e6; }
    .logo { font-size: 22px; font-weight: 700; }
    .logo span:first-child { color: var(--accent); }
    .menu { padding: 20px; list-style: none; margin: 0; }
    .menu li { margin: 12px 0; }
    .menu a {
      text-decoration: none;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px;
      border-radius: 8px;
      font-weight: 500;
    }
    .menu a.active { background-color: var(--accent); color: #fff; }
    .sidebar-bottom { border-top: 1px solid #e6e6e6; padding: 16px; }
    .sidebar-bottom a { display:flex; align-items:center; gap:8px; text-decoration:none; color:var(--text); margin-bottom:8px; }

    /* MAIN */
    main { flex: 1; background-color: #fff; display: flex; flex-direction: column; overflow-y: auto; }
    .topbar { display: flex; justify-content: flex-end; align-items: center; padding: 15px 30px; border-bottom: 1px solid #e6e6e6; }
    .notif { position: relative; margin-right: 15px; cursor:pointer; font-size:20px; }
    .notif::after {
      content:'1';
      position:absolute; top:-6px; right:-8px; background:red; color:#fff; font-size:10px; padding:2px 5px; border-radius:50%;
    }
    .profile { display:flex; align-items:center; gap:10px; }
    .profile img { width:35px; height:35px; border-radius:50%; border:1px solid #ccc; }
    .content { padding: 30px; }

    /* Box stats */
    .stats { display:flex; gap:20px; margin: 20px 0; }
    .box { background: var(--bg-card); padding: 20px; border-radius: 10px; flex:1; text-align:center; }
    .box h3 { font-size:22px; margin-bottom:5px; }

    /* Team list */
    .team { margin-top: 30px; }
    .team ul { list-style:none; padding:0; margin:0; display:grid; gap:8px; max-width:600px; }
    .team li { margin:0; display:flex; align-items:center; gap:12px; cursor:pointer; padding:10px; border-radius:8px; transition: background .12s; }
    .team li img { width:36px; height:36px; border-radius:50%; }
    .team li:hover { background:#f7f8f8; }

    /* Modal & profil detail semua CSS tetap sama seperti kode asli kamu */
    /* ... kopi semua CSS modal, avatar, laporan, form-row, status dll ... */
  </style>
</head>
<body>
  <!-- Sidebar tetap sama -->
  <aside>
    <div>
      <div class="sidebar-top">
        <div class="logo"><span>SIO</span><span>PLAS</span></div>
      </div>
      <ul class="menu">
  <li>
    <a href="{{ route('beranda.atasan') }}" class="{{ request()->routeIs('beranda.atasan') ? 'active' : '' }}">📊 Dashboard</a>
  </li>
  <li>
    <a href="{{ route('laporan.atasan') }}" class="{{ request()->routeIs('laporan.atasan') ? 'active' : '' }}">📁 Laporan</a>
  </li>
<li>
  <a href="{{ route('tugas.index') }}"
     class="{{ request()->routeIs('tugas.*') ? 'active' : '' }}">
     📝 Tugas
  </a>
</li>

</ul>

    </div>

    <div class="sidebar-bottom">
      <a href="{{ route('atasan.settings') }}">
        <img src="https://img.icons8.com/?size=100&id=364&format=png" width="18" alt="settings" />
        Settings
      </a>
      <a href="/logout">
        <img src="https://img.icons8.com/?size=100&id=26215&format=png" width="18" alt="logout" />
        Logout
      </a>
    </div>
  </aside>

  <!-- Main content -->
  <main>
    <!-- Navbar tetap sama -->
    <div class="topbar">
  <div class="notif" style="position:relative; margin-right:20px; font-size:20px; cursor:pointer;">
    <a href="{{ route('notifikasi.atasan') }}" style="text-decoration:none; color:inherit; display:inline-block;">
      🔔
      <span style="position:absolute; top:-6px; right:-6px; background:red; color:#fff; font-size:10px; padding:2px 5px; border-radius:50%;">
        1
      </span>
    </a>
  </div>

</li>

      <div class="profile">
        <img 
          src="https://i.pravatar.cc/100?u=atasan-ade-samsudin" 
          alt="Profile" 
          onerror="this.src='https://i.pravatar.cc/100?u=default';"
        />
        <div class="profile-info">
          <h4 style="margin:0; font-size:14px;">{{ session('atasan_nama', 'Atasan') }}</h4>
          <p style="margin:0; font-size:12px; color:var(--muted);">{{ session('atasan_jabatan', 'Atasan') }}</p>
        </div>
      </div>
    </div>

    <!-- Content dari tiap halaman -->
    <div class="content">
      @yield('content')
    </div>
  </main>

  <!-- Semua modal tetap sama -->
  @yield('modals')

  <!-- JS tetap sama -->
  
  @yield('scripts')
</body>
</html>
