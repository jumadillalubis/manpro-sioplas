@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
<style>
  .tugas-wrapper {
  width: 100%;
}

  .tugas-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 40px;
  }

  .tugas-item {
  display: grid;
  grid-template-columns: 1fr auto;
  align-items: center;
  margin-bottom: 36px;
}

  .tugas-info small {
    display: block;
    font-size: 14px;
    color: #64748b;
    margin-bottom: 4px;
  }

  .tugas-info h4 {
    font-size: 18px;
    font-weight: 500;
    margin: 4px 0;
    color: #0f172a;
  }

  .tugas-info p {
    margin: 0;
    font-size: 14px;
    color: #64748b;
  }

  .btn-detail {
    background: #f1f5f9;
    color: #0f172a;
    padding: 8px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    transition: background .2s;
  }

  .btn-detail:hover {
    background: #e2e8f0;
  }
</style>

<div class="tugas-wrapper">
  <div class="tugas-title">Daftar Tugas</div>

  @foreach ($tugas as $item)
    <div class="tugas-item">
      <div class="tugas-info">
        <small>Batas Waktu: {{ $item['batas_waktu'] }}</small>
        <h4>{{ $item['judul'] }}</h4>
        <p>Ke: {{ $item['kepada'] }}</p>
      </div>

      <a href="{{ route('tugas.show', $item['id']) }}" class="btn-detail">
        Lihat Detail
      </a>
    </div>
  @endforeach
</div>
@endsection
