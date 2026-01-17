@extends('layouts.app')

@section('title', 'Lihat Tugas')

@section('content')
<style>
  .lihat-wrapper {
    max-width: 900px;
  }

  .lihat-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 20px;
  }

  .doc-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
  }

  .doc-icon {
    width: 40px;
    height: 40px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 18px;
  }

  .comment-item {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
  }

  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
  }

  .response-box textarea {
    width: 100%;
    height: 120px;
    padding: 14px;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    resize: none;
    font-size: 14px;
  }

  .btn-group {
    margin-top: 20px;
    display: flex;
    justify-content: flex-start;
    gap: 14px;
  }

  .btn-cancel {
    padding: 10px 26px;
    border-radius: 24px;
    background: #e5e7eb;
    color: #111827;
    text-decoration: none;
    font-size: 14px;
  }

  .btn-save {
    padding: 10px 26px;
    border-radius: 24px;
    border: none;
    background: #2f9bff;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
  }
</style>

<div class="lihat-wrapper">

  <div class="lihat-title">Detail Tugas</div>

  <h4>{{ $tugas['judul'] }}</h4>
  <p><strong>Pegawai:</strong> {{ $tugas['pegawai'] }}</p>
  <p style="color:#64748b">{{ $tugas['tanggal'] }}</p>

  <p>{{ $tugas['deskripsi'] }}</p>

  <hr>

  <h4>Dokumen</h4>
  @foreach($tugas['dokumen'] as $doc)
    <div class="doc-item">
      <div class="doc-icon">📄</div>
      <span>{{ $doc['nama'] }}</span>
    </div>
  @endforeach

  <hr>

  <h4>Comments</h4>
  @foreach($tugas['komentar'] as $komen)
    <div class="comment-item">
      <div class="avatar"></div>
      <div>
        <strong>{{ $komen['nama'] }}</strong>
        <div style="font-size:12px;color:#64748b">{{ $komen['waktu'] }}</div>
        <p>{{ $komen['isi'] }}</p>
      </div>
    </div>
  @endforeach

  <hr>

  <h4>Area Respons Atasan</h4>

  <form method="POST" action="{{ route('tugas.respon', $tugas['id']) }}">
    @csrf

    <div class="response-box">
      <textarea name="respon" placeholder="Tambahkan komentar..."></textarea>
    </div>

    <div class="btn-group">
      <a href="{{ route('tugas.show', $tugas['id']) }}" class="btn-cancel">
        Batalkan
      </a>

      <button type="submit" class="btn-save">
        Simpan Respons
      </button>
    </div>
  </form>

</div>
@endsection
