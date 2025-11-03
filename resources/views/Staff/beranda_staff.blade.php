<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Staff - SIOPLAS</title>
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
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .container-custom {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card {
            text-align: center;
            padding: 25px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-speedometer2"></i> SIOPLAS - Staff Dashboard</h4>
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-person-circle"></i> {{ session('user_nama', 'Staff') }}</span>
                <a href="{{ route('logout') }}" class="btn btn-logout btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-custom">
        <!-- Welcome Card -->
        <div class="card welcome-card">
            <h2><i class="bi bi-emoji-smile"></i> Selamat Datang, {{ session('user_nama', 'Staff') }}!</h2>
            <p class="mb-0">Jabatan: {{ session('user_jabatan', 'Staff') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-file-text text-primary"></i>
                    <h3>0</h3>
                    <p class="text-muted mb-0">Total Laporan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-check-circle text-success"></i>
                    <h3>0</h3>
                    <p class="text-muted mb-0">Laporan Disetujui</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-clock-history text-warning"></i>
                    <h3>0</h3>
                    <p class="text-muted mb-0">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <h5 class="mb-3"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ session('user_nama', '-') }}</p>
                    <p><strong>Email:</strong> {{ session('user_email', '-') }}</p>
                    <p><strong>Jabatan:</strong> {{ session('user_jabatan', '-') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>NUP:</strong> {{ session('user_nup', '-') }}</p>
                    <p><strong>Pangkat/Gol:</strong> {{ session('user_pangkat_gol', '-') }}</p>
                    <p><strong>Pendidikan:</strong> {{ session('user_pendidikan', '-') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
