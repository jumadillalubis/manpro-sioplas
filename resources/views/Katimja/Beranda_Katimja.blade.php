<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIOPLAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        
        .sidebar-logo {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 30px;
        }
        
        .sidebar-logo span:first-child {
            color: #3498db;
        }
        
        .sidebar-logo span:last-child {
            color: #2c3e50;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            padding: 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #7f8c8d;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #e3f2fd;
            color: #2196f3;
            border-left: 4px solid #2196f3;
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
        }
        
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .notification-icon {
            position: relative;
            font-size: 20px;
            color: #7f8c8d;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .content-area {
            padding: 30px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .page-header p {
            color: #7f8c8d;
            margin: 0;
        }
        
        .stats-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .stats-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        
        .stat-card h4 {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .stat-card .number {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .deadline-card {
            background: linear-gradient(135deg, #56CCF2 0%, #2F80ED 100%);
            border-radius: 10px;
            padding: 25px;
            color: white;
            margin-bottom: 30px;
        }
        
        .deadline-card h5 {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .deadline-item {
            background: rgba(255,255,255,0.2);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .deadline-item:last-child {
            margin-bottom: 0;
        }
        
        .task-table {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .task-table h5 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #2c3e50;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .badge-success {
            background: #27ae60;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .badge-warning {
            background: #f39c12;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .badge-danger {
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <span>SIO</span><span>PLAS</span>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('beranda.katimja') }}" class="active">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.katimja') }}">
                    <i class="bi bi-file-text"></i>
                    Laporan
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-list-check"></i>
                    Tugas
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <ul class="sidebar-menu">
                <li>
                    <a href="#">
                        <i class="bi bi-gear"></i>
                        Settings
                    </a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i>
                        Logout
                    </a>
                </li>
            </ul>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div></div>
            <div class="user-profile">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-avatar">{{ substr(session('user_nama', 'K'), 0, 1) }}</div>
                <div>
                    <div style="font-weight: 600; font-size: 14px;">{{ session('user_nama', 'Katimja') }}</div>
                    <div style="font-size: 12px; color: #7f8c8d;">{{ session('user_jabatan', 'Katimja') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Page Header -->
            <div class="page-header">
                <h2>Dashboard</h2>
                <p>Selamat Datang, {{ session('user_nama', 'Katimja') }}</p>
            </div>
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-7">
                    <!-- Stats Section -->
                    <div class="stats-section">
                        <div class="stats-title">Ringkasan Aktivitas Pegawai</div>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <h4>Total Tugas</h4>
                                <div class="number">5 Tugas</div>
                            </div>
                            <div class="stat-card">
                                <h4>Laporan Pegawai</h4>
                                <div class="number">20 Laporan</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Task Table -->
                    <div class="task-table">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Daftar Tugas</h5>
                            <a href="#" style="color: #3498db; text-decoration: none; font-size: 14px;">Lihat Semua</a>
                        </div>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tugas</th>
                                    <th>Deadline</th>
                                    <th>Team</th>
                                    <th>ID Tugas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Desain SOP Baru</td>
                                    <td>12 Jul 2024</td>
                                    <td>2 Personel</td>
                                    <td>T00126</td>
                                    <td><span class="badge-success">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>Evaluasi Kinerja Tim</td>
                                    <td>26 Jul 2024</td>
                                    <td>5 Team</td>
                                    <td>T00125</td>
                                    <td><span class="badge-warning">Proses</span></td>
                                </tr>
                                <tr>
                                    <td>Rapat Koordinasi Proyek</td>
                                    <td>09 Agu 2024</td>
                                    <td>2 Personel</td>
                                    <td>T00132</td>
                                    <td><span class="badge-danger">Terlambat</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-5">
                    <div class="deadline-card">
                        <h5>⏰ Tugas Deadline Terdekat</h5>
                        <div class="deadline-item">
                            <div>
                                <div style="font-weight: 600;">Desain SOP Baru</div>
                                <div style="font-size: 12px; opacity: 0.9;">12 Jul 2024</div>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <div>
                                <div style="font-weight: 600;">Evaluasi Kinerja Tim</div>
                                <div style="font-size: 12px; opacity: 0.9;">26 Jul 2024</div>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <div>
                                <div style="font-weight: 600;">Rapat Koordinasi Proyek</div>
                                <div style="font-size: 12px; opacity: 0.9;">09 Agu 2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>