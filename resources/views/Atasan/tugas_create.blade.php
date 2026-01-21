@extends('layouts.app')

@section('content')

<style>
/* ===== KHUSUS HALAMAN BUAT TUGAS ===== */
.task-create-page {
    max-width: 1000px;
    margin: 0 auto;
    margin-top: -50px; /* NAIKIN ISI KE ATAS */
    padding: 0 20px 40px;
}

/* Judul */
.task-create-page h2 {
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: 600;
}

/* Switch Divisi / Pegawai */
.task-switch {
    display: flex;
    background: #f1f3f5;
    border-radius: 10px;
    padding: 4px;
    margin-bottom: 25px;
}

.task-switch button {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px;
    font-size: 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.task-switch button.active {
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    font-weight: 600;
}

/* Form */
.task-form .form-group {
    margin-bottom: 16px;
}

.task-form label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
}

.task-form input,
.task-form select,
.task-form textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.task-form textarea {
    resize: none;
    height: 120px;
}

/* Button */
.submit-btn {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.submit-btn button {
    padding: 12px 40px;
    border: none;
    border-radius: 20px;
    background: #2696FF;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
}
</style>

<div class="task-create-page">

    <h2>Buat Tugas Baru</h2>

    <!-- SWITCH -->
    <div class="task-switch">
        <button id="btnDivisi" class="active" onclick="switchTask('divisi')">
            Divisi
        </button>
        <button id="btnPegawai" onclick="switchTask('pegawai')">
            Pegawai
        </button>
    </div>

    <!-- FORM -->
    <!-- FORM -->
    <form class="task-form" id="createTaskForm" action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- DIVISI -->
        <div id="formDivisi">
            <div class="form-group">
                <label>Penerima Tugas (Divisi)</label>
                <select id="selectDivisi" name="divisi">
                    <option value="">Pilih Divisi</option>
                    <option value="Tata Usaha">Tata Usaha</option>
                    <option value="Produksi Primer">Produksi Primer</option>
                    <option value="Pasca Panen">Pasca Panen</option>
                    <option value="Labor">Labor</option>
                </select>
            </div>
        </div>

        <!-- PEGAWAI -->
        <div id="formPegawai" style="display:none">
            <div class="form-group">
                <label>Penerima Tugas (Pegawai)</label>
                <select id="selectPegawai" name="pegawai" disabled>
                    <option value="">Pilih Pegawai</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->nama }}">{{ $s->nama }} - {{ $s->jabatan ?? '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Judul Tugas</label>
            <input type="text" id="inputJudul" name="judul" placeholder="Masukkan judul tugas" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="inputDeskripsi" name="deskripsi" placeholder="Deskripsi tugas" required></textarea>
        </div>

        <div class="form-group">
            <label>Upload File</label>
            <input type="file" id="inputFile" name="file">
        </div>

        <div class="form-group">
            <label>Tenggat Waktu</label>
            <input type="date" id="inputTenggat" name="tenggat" required>
        </div>

        <div class="submit-btn">
            <button type="submit" id="btnSubmit">Kirim Tugas</button>
        </div>
    </form>
</div>

<script>
function switchTask(type) {
    const btnDivisi = document.getElementById('btnDivisi');
    const btnPegawai = document.getElementById('btnPegawai');
    const formDivisi = document.getElementById('formDivisi');
    const formPegawai = document.getElementById('formPegawai');
    const selectDivisi = document.getElementById('selectDivisi');
    const selectPegawai = document.getElementById('selectPegawai');

    if (type === 'divisi') {
        btnDivisi.classList.add('active');
        btnPegawai.classList.remove('active');
        formDivisi.style.display = 'block';
        formPegawai.style.display = 'none';
        
        // Enable Divisi, Disable Pegawai
        selectDivisi.disabled = false;
        selectPegawai.disabled = true;
    } else {
        btnPegawai.classList.add('active');
        btnDivisi.classList.remove('active');
        formPegawai.style.display = 'block';
        formDivisi.style.display = 'none';

        // Enable Pegawai, Disable Divisi
        selectPegawai.disabled = false;
        selectDivisi.disabled = true;
    }
}
</script>

@endsection
