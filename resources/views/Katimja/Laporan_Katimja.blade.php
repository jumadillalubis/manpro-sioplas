<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - SIOPLAS</title>
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
            margin-bottom: 20px;
        }
        
        .sidebar-logo span:first-child {
            color: #3498db;
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
            background: linear-gradient(135deg, #56CCF2 0%, #2F80ED 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .page-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        
        .detail-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            font-weight: 600;
            color: #2c3e50;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        
        .table tbody td:first-child {
            text-align: center;
            font-weight: 600;
        }
        
        .upload-cell {
            text-align: center;
        }
        
        .btn-upload {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-upload:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-upload.uploaded {
            background: #27ae60;
        }
        
        .btn-upload.uploaded:hover {
            background: #229954;
        }
        
        .file-info {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }
        
        .pagination button {
            border: 1px solid #dee2e6;
            background: white;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .pagination button.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-modal {
            font-size: 24px;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: #3498db;
            background: #f8f9fa;
        }
        
        .upload-area.dragover {
            border-color: #27ae60;
            background: #e8f5e9;
        }
        
        .btn-submit {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            font-weight: 600;
        }
        
        .btn-submit:hover {
            background: #229954;
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
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/laporan" class="active">
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
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div style="font-weight: 600; font-size: 14px;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 12px; color: #7f8c8d;">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Page Header -->
            <div class="page-header">
                <h3>PERJANJIAN KINERJA TAHUN 2025 BADAN MUTU KKP PEKANBARU</h3>
            </div>
            
            <div class="detail-title">Detail Laporan</div>
            
            <!-- Table -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 50px;">NO</th>
                            <th rowspan="2" style="min-width: 300px;">INDIKATOR KINERJA KEGIATAN</th>
                            <th colspan="4">Data Dukung</th>
                        </tr>
                        <tr>
                            <th style="width: 150px;">TW1</th>
                            <th style="width: 150px;">TW2</th>
                            <th style="width: 150px;">TW3</th>
                            <th style="width: 150px;">TW4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)</td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(1, 1)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-1-1"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(1, 2)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-1-2"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(1, 3)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-1-3"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(1, 4)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-1-4"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)</td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(2, 1)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-2-1"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(2, 2)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-2-2"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(2, 3)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-2-3"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(2, 4)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-2-4"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Rasio ekspor ikan dan hasil perikanan memenuhi syarat mutu dan diterima oleh negara tujuan ekspor lingkup UPT Stasiun KIPM Pekanbaru (%)</td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(3, 1)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-3-1"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(3, 2)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-3-2"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(3, 3)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-3-3"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(3, 4)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-3-4"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)</td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(4, 1)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-4-1"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(4, 2)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-4-2"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(4, 3)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-4-3"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(4, 4)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-4-4"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)</td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(5, 1)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-5-1"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(5, 2)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-5-2"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(5, 3)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-5-3"></div>
                            </td>
                            <td class="upload-cell">
                                <button class="btn-upload" onclick="openUploadModal(5, 4)">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                                <div class="file-info" id="file-info-5-4"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <span>|<</span>
                    <button class="active">1</button>
                    <span>>|</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Upload File TW<span id="modal-tw"></span> - Item <span id="modal-item"></span></h5>
                <span class="close-modal" onclick="closeUploadModal()">&times;</span>
            </div>
            
            <div class="upload-area" id="dropArea" onclick="document.getElementById('fileInput').click()">
                <i class="bi bi-cloud-upload" style="font-size: 48px; color: #3498db;"></i>
                <p style="margin: 10px 0;">Klik atau drag & drop file di sini</p>
                <small style="color: #7f8c8d;">PDF, Word, Excel (Max 10MB)</small>
                <input type="file" id="fileInput" style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx" onchange="handleFileSelect(event)">
            </div>
            
            <div id="selectedFileName" style="margin-top: 15px; color: #27ae60; font-weight: 600; text-align: center;"></div>
            
            <button class="btn-submit" onclick="uploadFile()">
                <i class="bi bi-check-circle"></i> Simpan File
            </button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRow = 0;
        let currentTW = 0;
        let selectedFile = null;
        
        function openUploadModal(row, tw) {
            currentRow = row;
            currentTW = tw;
            document.getElementById('modal-item').textContent = row;
            document.getElementById('modal-tw').textContent = tw;
            document.getElementById('uploadModal').style.display = 'block';
            document.getElementById('selectedFileName').textContent = '';
            selectedFile = null;
        }
        
        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }
        
        function handleFileSelect(event) {
            selectedFile = event.target.files[0];
            if (selectedFile) {
                document.getElementById('selectedFileName').textContent = '✓ ' + selectedFile.name;
            }
        }
        
        function uploadFile() {
            if (!selectedFile) {
                alert('Pilih file terlebih dahulu!');
                return;
            }
            
            // Simulasi upload (di production, gunakan FormData dan AJAX/fetch ke server)
            const formData = new FormData();
            formData.append('file', selectedFile);
            formData.append('row', currentRow);
            formData.append('tw', currentTW);
            
            // Tampilkan info file yang di-upload
            const fileInfo = document.getElementById(`file-info-${currentRow}-${currentTW}`);
            fileInfo.textContent = '✓ ' + selectedFile.name;
            
            // Ubah button jadi hijau
            const buttons = document.querySelectorAll(`button[onclick="openUploadModal(${currentRow}, ${currentTW})"]`);
            buttons.forEach(btn => {
                btn.classList.add('uploaded');
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Uploaded';
            });
            
            alert('File berhasil diupload!');
            closeUploadModal();
            
            // UNTUK PRODUCTION: Gunakan fetch API
            /*
            fetch('/api/upload-laporan', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('File berhasil diupload!');
                closeUploadModal();
            })
            .catch(error => {
                alert('Upload gagal: ' + error);
            });
            */
        }
        
        // Drag & Drop functionality
        const dropArea = document.getElementById('dropArea');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('dragover');
            }, false);
        });
        
        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                selectedFile = files[0];
                document.getElementById('selectedFileName').textContent = '✓ ' + selectedFile.name;
            }
        }, false);
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('uploadModal');
            if (event.target == modal) {
                closeUploadModal();
            }
        }
    </script>
</body>
</html>