@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<style>
  .detail-wrapper {
    width: 100%;
    position: relative;
  }

  .detail-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 30px;
  }

  .detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .approve-btn {
    padding: 10px 26px;
    border-radius: 24px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    background: #e5e7eb;
    color: #111827;
  }

  .approve-btn.approved {
    background: #39ff14;
    color: #0f172a;
  }

  .detail-info h3 {
    margin: 0 0 8px 0;
    font-size: 20px;
    font-weight: 600;
  }

  .detail-info p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
  }

  .detail-desc {
    margin-top: 24px;
    max-width: 900px;
    line-height: 1.7;
  }

  /* ===== PERBAIKAN BUTTON ===== */
  .action-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 40px;
  }

  .btn-primary {
    background: #2f9bff;
    color: #fff;
    padding: 12px 28px;
    border-radius: 26px;
    text-decoration: none;
    font-size: 14px;
  }
</style>

<div class="detail-wrapper">
  <div class="detail-title">Detail Tugas</div>

  <div class="detail-header">
    <div class="detail-info">
      <h3>{{ $tugas['judul'] }}</h3>
      <p>Pegawai: {{ $tugas['pegawai'] }}</p>
      <p>Batas Waktu: {{ $tugas['deadline'] ?? '-' }}</p>
      <p>Tanggal Dibuat: {{ $tugas['tanggal'] }}</p>
    </div>

    <form method="POST" action="{{ route('tugas.approve', $tugas['id']) }}">
      @csrf
      <button
        type="submit"
        class="approve-btn {{ session('approved') ? 'approved' : '' }}"
        {{ session('approved') ? 'disabled' : '' }}
      >
        {{ session('approved') ? 'approved' : 'approve' }}
      </button>
    </form>
  </div>

  <div class="detail-desc">
    {{ $tugas['deskripsi'] }}
  </div>

  <!-- BUTTON DIPERBAIKI (POSISI + BENTUK) -->
  <div class="action-footer">
    <a href="{{ route('tugas.lihat', $tugas['id']) }}" class="btn-primary">
      Lihat Tugas
    </a>
  </div>
</div>
@endsection
