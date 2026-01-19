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
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
      --accent:#48CAE4;
      --bg-card:#f1f4f5;
      --text:#2a3b27;
      --muted:#7a7f83;
    }

    *{box-sizing:border-box;}
    body{
      margin:0;
      font-family:'Poppins',sans-serif;
      display:flex;
      min-height:100vh;
      color:var(--text);
      background:#fff;
    }

    aside{
      width:230px;
      background:#fff;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      border-right:1px solid #e6e6e6;
    }

    .sidebar-top{
      text-align:center;
      padding:20px 0;
      border-bottom:1px solid #e6e6e6;
    }

    .logo{
      font-size:22px;
      font-weight:700;
    }

    .logo span:first-child{color:var(--accent);}

    .menu{
      padding:20px;
      list-style:none;
      margin:0;
    }

    .menu li{margin:12px 0;}

    .menu a{
      text-decoration:none;
      color:var(--text);
      display:flex;
      align-items:center;
      gap:10px;
      padding:8px;
      border-radius:8px;
      font-weight:500;
    }

    .menu a.active{
      background:var(--accent);
      color:#fff;
    }

    .sidebar-bottom{
      border-top:1px solid #e6e6e6;
      padding:16px;
    }

    .sidebar-bottom a{
      display:flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
      color:var(--text);
      margin-bottom:8px;
    }

    main{
      flex:1;
      display:flex;
      flex-direction:column;
      overflow-y:auto;
    }

    .topbar{
      display:flex;
      justify-content:flex-end;
      align-items:center;
      padding:15px 30px;
      border-bottom:1px solid #e6e6e6;
    }

    .notif{
      position:relative;
      margin-right:20px;
      font-size:20px;
      cursor:pointer;
    }

    .notif span{
      position:absolute;
      top:-6px;
      right:-6px;
      background:red;
      color:#fff;
      font-size:10px;
      padding:2px 5px;
      border-radius:50%;
    }

    .profile{
      display:flex;
      align-items:center;
      gap:10px;
    }

    .profile img{
      width:35px;
      height:35px;
      border-radius:50%;
      border:1px solid #ccc;
    }

    .content{
      padding:30px;
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
