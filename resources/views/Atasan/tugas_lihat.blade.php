@extends('layouts.app')

@section('title', 'Lihat Tugas')

@section('content')
<style>
  /* Menggunakan style yang konsisten dengan tugas_detail */
  .detail-wrapper {
    max-width: 900px;
    margin: 0 auto;
  }

  .detail-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 30px;
    color: #111827;
  }

  /* Header Section */
  .task-header {
    background: #f9fafb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
  }

  .task-title-text {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #1f2937;
  }

  .task-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .meta-item {
    font-size: 14px;
  }

  .meta-label {
    color: #6b7280;
    margin-bottom: 4px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  .meta-value {
    color: #111827;
    font-weight: 500;
  }

  /* Description & Content */
  .task-body {
    font-size: 16px;
    line-height: 1.7;
    color: #374151;
    margin-bottom: 40px;
    padding: 0 10px;
  }

  /* Section Dividers */
  .divider-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f3f4f6;
  }

  /* File Lists */
  .file-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 30px;
  }

  .file-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s;
  }

  .file-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .file-icon {
    font-size: 20px;
    margin-right: 12px;
  }

  .file-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
  }

  .file-link:hover {
    text-decoration: underline;
  }

  /* Response Area */
  .response-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-top: 40px;
  }

  .btn-submit {
    background: #2563eb;
    color: white;
    padding: 10px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
  }

  .btn-submit:hover {
    background: #1d4ed8;
  }
</style>

<div class="detail-wrapper">
  
  <div class="mb-4">
     <a href="{{ route('tugas.index') }}" class="text-sm text-gray-500 hover:text-blue-600 no-underline">&larr; Kembali</a>
  </div>

  <div class="detail-title">Detail Tugas</div>

  <!-- Header Info -->
  <div class="task-header">
    <h1 class="task-title-text">{{ $tugas['judul'] }}</h1>
    <div class="task-meta grid grid-cols-3 gap-4 mt-4">
        <div class="meta-item">
            <span class="meta-label">Penerima</span>
            <span class="meta-value">{{ $tugas['pegawai'] }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Tanggal Dibuat</span>
            <span class="meta-value">{{ $tugas['tanggal'] }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Batas Waktu</span>
            <span class="meta-value text-red-500">{{ $tugas['deadline'] ?? '-' }}</span>
        </div>
    </div>
  </div>

  <!-- Deskripsi -->
  <div class="task-body">
    {!! nl2br(e($tugas['deskripsi'])) !!}
  </div>

  <!-- Dokumen Lampiran -->
  @if(count($tugas['dokumen']) > 0)
    <h3 class="divider-title">Lampiran Dokumen</h3>
    <div class="file-list">
      @foreach($tugas['dokumen'] as $doc)
        <div class="file-item">
          <span class="text-gray-700">{{ $doc['nama'] }}</span>
        </div>
      @endforeach
    </div>
  @endif

  <!-- Hasil Pengerjaan -->
  @if(!empty($tugas['file_selesai']))
    <h3 class="divider-title" style="color:#16a34a; border-color:#dcfce7;">✅ Hasil Pengerjaan</h3>
    <div class="file-list">
      <div class="file-item" style="border-left: 4px solid #16a34a;">
        <div class="flex flex-col">
            <span class="text-xs text-gray-500 uppercase font-bold">File Selesai</span>
            <div class="flex items-center gap-3">
                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank" class="file-link">
                    {{ $tugas['file_selesai'] }}
                </a>
                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" download class="text-gray-500 hover:text-blue-600" title="Download File">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>
            </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Form Respons -->
  <div class="response-section">
    <h3 class="divider-title">Kirim Respons / Revisi</h3>
    
    <form method="POST" action="{{ route('tugas.respon', $tugas['id']) }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <textarea name="respon" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" rows="4" placeholder="Tulis catatan atau revisi untuk staff..."></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lampirkan File (Opsional)</label>
                <input type="file" name="file_respon" class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100">
            </div>
            
            <button type="submit" class="btn-submit w-full sm:w-auto">
                Kirim Pembahasan
            </button>
        </div>
    </form>
  </div>

</div>
@endsection
