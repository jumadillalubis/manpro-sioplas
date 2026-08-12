@extends('layouts.app')

@section('title', 'Buat Tugas Baru')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Alert Messages --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3 text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
            <div class="font-semibold mb-1">Terdapat kesalahan pada isian form:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Tugas Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi data di bawah ini untuk mengirimkan tugas baru.</p>
        </div>
        <div>
            <a href="{{ route('tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-sm rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">

        {{-- Switch Divisi / Pegawai --}}
        <div class="mb-6">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Tujuan Penugasan</label>
            <div class="bg-slate-100 p-1.5 rounded-xl grid grid-cols-2 gap-1 max-w-md">
                <button type="button" id="btnDivisi" class="py-2.5 px-4 text-sm font-semibold rounded-lg transition-all text-blue-600 bg-white shadow-sm" onclick="switchTask('divisi')">
                    Divisi (Satu Tim)
                </button>
                <button type="button" id="btnPegawai" class="py-2.5 px-4 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all" onclick="switchTask('pegawai')">
                    Pegawai (Perorangan)
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form class="space-y-5" id="createTaskForm" action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- DIVISI --}}
            <div id="formDivisi">
                <label for="selectDivisi" class="block text-sm font-semibold text-slate-700 mb-2">Penerima Tugas (Divisi) <span class="text-rose-500">*</span></label>
                <select id="selectDivisi" name="divisi" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 text-sm bg-white transition-all">
                    <option value="">Pilih Divisi Target</option>
                    <option value="Tata Usaha">Tata Usaha</option>
                    <option value="Produksi Primer">Produksi Primer</option>
                    <option value="Pasca Panen">Pasca Panen</option>
                    <option value="Labor">Labor</option>
                </select>
            </div>

            {{-- PEGAWAI --}}
            <div id="formPegawai" style="display:none">
                <label for="selectPegawai" class="block text-sm font-semibold text-slate-700 mb-2">Penerima Tugas (Pegawai) <span class="text-rose-500">*</span></label>
                <select id="selectPegawai" name="pegawai" disabled class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 text-sm bg-white transition-all">
                    <option value="">Pilih Pegawai Target</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->nama }}">{{ $s->nama }} {{ isset($s->jabatan) ? '- ' . $s->jabatan : '' }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Judul Tugas --}}
            <div>
                <label for="inputJudul" class="block text-sm font-semibold text-slate-700 mb-2">Judul Tugas <span class="text-rose-500">*</span></label>
                <input type="text" id="inputJudul" name="judul" placeholder="Masukkan judul tugas yang jelas" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 placeholder-slate-400 text-sm transition-all">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="inputDeskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Tugas <span class="text-rose-500">*</span></label>
                <textarea id="inputDeskripsi" name="deskripsi" rows="4" placeholder="Tuliskan instruksi detail mengenai tugas..." required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 placeholder-slate-400 text-sm transition-all resize-none"></textarea>
            </div>

            {{-- Upload File --}}
            <div>
                <label for="inputFile" class="block text-sm font-semibold text-slate-700 mb-2">Upload Lampiran / Dokumen <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <div class="relative flex items-center justify-center w-full border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl p-4 transition-colors bg-slate-50/50 hover:bg-blue-50/20 group">
                    <input type="file" id="inputFile" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mt-1 text-sm text-slate-600" id="fileNameText">Klik atau seret file ke sini untuk mengunggah</p>
                        <p class="text-xs text-slate-400 mt-0.5">PDF, DOCX, XLSX, JPG, PNG (Maks 10MB)</p>
                    </div>
                </div>
            </div>

            {{-- Tenggat Waktu --}}
            <div>
                <label for="inputTenggat" class="block text-sm font-semibold text-slate-700 mb-2">Tenggat Waktu (Deadline) <span class="text-rose-500">*</span></label>
                <input type="date" id="inputTenggat" name="tenggat" required class="w-full sm:w-64 px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 text-sm transition-all bg-white">
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:ring-2 focus:ring-blue-500/40 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Tugas
                </button>
            </div>
        </form>
    </div>
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
        btnDivisi.className = "py-2.5 px-4 text-sm font-semibold rounded-lg transition-all text-blue-600 bg-white shadow-sm";
        btnPegawai.className = "py-2.5 px-4 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all";
        formDivisi.style.display = 'block';
        formPegawai.style.display = 'none';
        
        selectDivisi.disabled = false;
        selectDivisi.required = true;
        selectPegawai.disabled = true;
        selectPegawai.required = false;
        selectPegawai.value = "";
    } else {
        btnPegawai.className = "py-2.5 px-4 text-sm font-semibold rounded-lg transition-all text-blue-600 bg-white shadow-sm";
        btnDivisi.className = "py-2.5 px-4 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all";
        formPegawai.style.display = 'block';
        formDivisi.style.display = 'none';

        selectPegawai.disabled = false;
        selectPegawai.required = true;
        selectDivisi.disabled = true;
        selectDivisi.required = false;
        selectDivisi.value = "";
    }
}

// Display chosen file name
document.getElementById('inputFile').addEventListener('change', function(e) {
    const fileNameText = document.getElementById('fileNameText');
    if (e.target.files && e.target.files.length > 0) {
        fileNameText.textContent = 'File terpilih: ' + e.target.files[0].name;
        fileNameText.classList.add('font-semibold', 'text-blue-600');
    } else {
        fileNameText.textContent = 'Klik atau seret file ke sini untuk mengunggah';
        fileNameText.classList.remove('font-semibold', 'text-blue-600');
    }
});
</script>
@endsection
