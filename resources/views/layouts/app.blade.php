<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'SIOPLAS')</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  @yield('styles')

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root{
      --accent:#2563eb;
      --bg-card:#ffffff;
      --text:#0f172a;
      --muted:#64748b;
    }

    *{box-sizing:border-box;}
    body{
      margin:0;
      font-family:'Plus Jakarta Sans', sans-serif;
      display:flex;
      min-height:100vh;
      color:var(--text);
      background:#f8fafc;
    }

    aside{
      width:240px;
      background:#ffffff;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      border-right:1px solid #e2e8f0;
      box-shadow: 4px 0 24px rgba(0,0,0,0.02);
      z-index: 20;
    }

    .sidebar-top{
      text-align:left;
      padding:24px 20px;
      border-bottom:1px solid #f1f5f9;
    }

    .logo{
      font-size:24px;
      font-weight:800;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 2px;
    }

    .logo span:first-child{color:#0284c7;}
    .logo span:last-child{color:#1e3a8a;}

    .menu{
      padding:16px 14px;
      list-style:none;
      margin:0;
    }

    .menu li{margin:6px 0;}

    .menu a{
      text-decoration:none;
      color:#475569;
      display:flex;
      align-items:center;
      gap:12px;
      padding:12px 16px;
      border-radius:12px;
      font-weight:600;
      font-size:14px;
      transition: all 0.2s ease;
    }

    .menu a:hover{
      background:#f1f5f9;
      color:#0f172a;
    }

    .menu a.active{
      background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
      color:#ffffff;
      box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
    }

    .sidebar-bottom{
      border-top:1px solid #f1f5f9;
      padding:16px 14px;
    }

    .sidebar-bottom a{
      display:flex;
      align-items:center;
      gap:10px;
      text-decoration:none;
      color:#64748b;
      padding:10px 14px;
      border-radius:10px;
      font-size:13px;
      font-weight:600;
      transition: all 0.2s ease;
    }

    .sidebar-bottom a:hover{
      background:#f1f5f9;
      color:#0f172a;
    }

    main{
      flex:1;
      display:flex;
      flex-direction:column;
      overflow-y:auto;
      background: #f8fafc;
    }

    .topbar{
      display:flex;
      justify-content:flex-end;
      align-items:center;
      padding:16px 32px;
      background: #ffffff;
      border-bottom:1px solid #e2e8f0;
    }

    .content{
      padding:32px;
    }
  </style>
</head>

<body>

  @include('layouts.partials.sidebar')

  <main>
    @include('layouts.partials.header')

    <div class="content">
      @yield('content')
    </div>
  </main>

  @yield('scripts')
</body>
</html>
