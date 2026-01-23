@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<style>
  .detail-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding-bottom: 60px;
  }

  .detail-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #111827;
  }

  /* Header Card */
  .header-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
  }

  .task-info h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 12px 0;
  }

  .meta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  .meta-item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #6b7280;
    margin-bottom: 4px;
  }

  .meta-item span {
    font-size: 15px;
    font-weight: 500;
    color: #111827;
  }

  /* Status Badge */
  .status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    display: inline-block;
  }
  .status-pending { background: #fef3c7; color: #b45309; }
  .status-review { background: #dbeafe; color: #1e40af; }
  .status-revisi { background: #fee2e2; color: #b91c1c; }
  .status-selesai { background: #dcfce7; color: #15803d; }

  /* Approval Section */
  .approval-section {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
  }

  .btn-approve {
    background: #26a67de0;
    color: #fff;
    padding: 10px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  .btn-approve:hover { background: #12815ee0; }
  
  .btn-disabled {
    background: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
  }

  /* Content & Files */
  .section-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    margin-bottom: 24px;
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f3f4f6;
  }

  .desc-text {
    line-height: 1.6;
    color: #374151;
  }

  .file-box {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }
  .file-box a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
  }
  .file-box a:hover { text-decoration: underline; }

  /* Revision Form */
  .revision-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    margin-bottom: 12px;
    font-family: inherit;
  }
  .revision-form textarea:focus {
    outline: none;
    border-color: #3b82f6;
    ring: 2px solid #3b82f6;
  }
  
  .btn-revise {
    background: #dc2626;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
  }
  .btn-revise:hover { background: #b91c1c; }

</style>

<div class="detail-wrapper">
  
  <div class="mb-6">
     <a href="{{ route('tugas.index') }}" class="text-sm text-gray-500 hover:text-blue-600 no-underline">&larr; Kembali ke Daftar Tugas</a>
  </div>

  <div class="detail-title">Detail & Review Tugas</div>

  <!-- Header & Status -->
  <div class="header-card">
    <div class="task-info">
      <h1>{{ $tugas['judul'] }}</h1>
      <div class="meta-grid">
        <div class="meta-item">
            <label>Penerima</label>
            <span>{{ $tugas['pegawai'] }}</span>
        </div>
        <div class="meta-item">
            <label>Deadline</label>
            <span style="color:#ef4444;">{{ $tugas['deadline'] ?? '-' }}</span>
        </div>
        <div class="meta-item">
            <label>Tanggal Dibuat</label>
            <span>{{ $tugas['tanggal'] }}</span>
        </div>
        <div class="meta-item">
            <label>Status</label>
            @php
                $statusClass = 'status-pending';
                $statusLabel = $tugas['status'];
                if($tugas['status'] == 'Selesai') $statusClass = 'status-selesai';
                elseif($tugas['status'] == 'Revisi') $statusClass = 'status-revisi';
                elseif($tugas['status'] == 'Menunggu Review') $statusClass = 'status-review';
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>
      </div>
    </div>

    <!-- Approve Button -->
    <div class="approval-section">
        @if($tugas['status'] == 'Selesai')
            <button class="btn-approve btn-disabled" disabled>Approve</button>
        @else
            <form method="POST" action="{{ route('tugas.approve', $tugas['id']) }}">
                @csrf
                <button type="submit" class="btn-approve" onclick="return confirm('Apakah Anda yakin ingin menyetujui tugas ini?')">
                    ✓ Approve Tugas
                </button>
            </form>
        @endif
    </div>
  </div>

  <!-- Deskripsi -->
  <div class="section-card">
    <h3 class="section-title">Deskripsi Tugas</h3>
    <div class="desc-text">
        {!! nl2br(e($tugas['deskripsi'])) !!}
    </div>
  </div>

  <!-- Hasil Pengerjaan (File dari Staff/Katimja) -->
  @if(!empty($tugas['file_selesai']))
  <div class="section-card" style="border-left: 4px solid #10b981;">
    <h3 class="section-title text-green-700">📂 Hasil Pengerjaan</h3>
    <div class="file-box">
        <div class="mr-3 text-2xl">📄</div>
        <div>
            <div class="text-sm text-gray-500">File Terlampir:</div>
            <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank">
                {{ $tugas['file_selesai'] }}
            </a>
        </div>
        <div class="ml-auto">
             <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" download class="text-gray-500 hover:text-blue-600">
                ⬇ Download
            </a>
        </div>
    </div>
  </div>
  @else
  <div class="section-card bg-gray-50">
      <h3 class="section-title text-gray-400">Hasil Pengerjaan</h3>
      <p class="text-gray-500 italic">Belum ada file yang diunggah oleh penerima tugas.</p>
  </div>
  @endif


  <!-- Form Revisi / Komentar -->
  <!-- Tampilkan form ini jika status BELUM Selesai, agar Atasan bisa minta revisi -->
  @if($tugas['status'] != 'Selesai')
  <div class="section-card" style="border-left: 4px solid #f59e0b;">
    <h3 class="section-title text-amber-700">📝 Minta Revisi / Kirim Catatan</h3>
    <p class="text-sm text-gray-600 mb-4">Jika hasil pengerjaan belum sesuai, silakan berikan catatan revisi di bawah ini.</p>
    
    <form method="POST" action="{{ route('tugas.respon', $tugas['id']) }}" enctype="multipart/form-data" class="revision-form">
        @csrf
        
        <textarea name="respon" rows="4" placeholder="Tulis detail revisi atau masukan.....">{{ $tugas['respon'] }}</textarea>
        
        <div class="flex items-center justify-between mt-2">
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Lampiran Revisi (Opsional)</label>
                <input type="file" name="file_respon" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>
            
            <button type="submit" class="btn-revise">
                Kirim Revisi
            </button>
        </div>
    </form>
  </div>
  @endif

  <!-- Riwayat Respon Terakhir (Jika ada) -->
  @if(!empty($tugas['respon']))
  <div class="section-card bg-amber-50 border-amber-200">
    <h3 class="section-title text-amber-800">Catatan Revisi Terakhir</h3>
    <p class="text-gray-800 italic">"{{ $tugas['respon'] }}"</p>
    @if(!empty($tugas['file_respon']))
        <div class="mt-3 text-sm">
            🔗 <a href="{{ asset('storage/tugas_respon/' . $tugas['file_respon']) }}" target="_blank" class="text-amber-700 underline">{{ $tugas['file_respon'] }}</a>
        </div>
    @endif
  </div>
  @endif

</div>
@endsection
