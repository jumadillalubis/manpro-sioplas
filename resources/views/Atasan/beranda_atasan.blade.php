@extends('layouts.app')

@section('title', 'Beranda Atasan')

@section('content')

<h2 class="page-title">
  Selamat Datang, {{ session('atasan_nama', 'Atasan') }}
</h2>

<!-- STATISTIK -->
<div class="stats-wrapper">
  <div class="stats">
    <div class="box">
      <p class="label">Total Laporan Terkirim</p>
      <h3>28 Laporan</h3>
    </div>
    <div class="box">
      <p class="label">Total Tugas Terkirim</p>
      <h3>15 Laporan</h3>
    </div>
  </div>
</div>

<!-- BUTTON -->
<div class="action-center">
  <a href="{{ route('tugas.create') }}" class="btn-task">Buat Tugas</a>
</div>

<!-- TEAM MANAJEMEN -->
<div class="team">
  <h3>Team Manajemen</h3>

  <ul class="team-list">

    <li class="team-item">
      <div class="team-header">
        <img src="https://cdn-icons-png.flaticon.com/128/8921/8921211.png">
        <span>Tata Usaha</span>
      </div>
      <ul class="member-list" style="display:none;">
        <li>Ahmad Fauzi</li>
        <li>Siti Rahma</li>
        <li>Rina Putri</li>
      </ul>
    </li>

    <li class="team-item">
      <div class="team-header">
        <img src="https://cdn-icons-png.flaticon.com/128/2257/2257185.png">
        <span>Produksi Primer</span>
      </div>
      <ul class="member-list" style="display:none;">
        <li>Budi Santoso</li>
        <li>Andi Wijaya</li>
      </ul>
    </li>

    <li class="team-item">
      <div class="team-header">
        <img src="https://cdn-icons-png.flaticon.com/128/88/88528.png">
        <span>Pasca Panen</span>
      </div>
      <ul class="member-list" style="display:none;">
        <li>Dewi Lestari</li>
        <li>Nina Amelia</li>
      </ul>
    </li>

    <li class="team-item">
      <div class="team-header">
        <img src="https://cdn-icons-png.flaticon.com/128/10557/10557880.png">
        <span>LABOR</span>
      </div>
      <ul class="member-list" style="display:none;">
        <li>Rizky Pratama</li>
        <li>Agus Saputra</li>
      </ul>
    </li>

  </ul>
</div>

<!-- SCRIPT LANGSUNG DI SINI (PASTI JALAN) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.team-header').forEach(header => {
    header.addEventListener('click', function () {
      const list = this.nextElementSibling;
      list.style.display = (list.style.display === 'none') ? 'block' : 'none';
    });
  });
});
</script>

@endsection

@section('styles')
<style>
.page-title {
  margin-bottom: 36px;
  font-size: 26px;
  font-weight: 600;
}

.stats {
  display: flex;
  gap: 24px;
  margin-bottom: 36px;
}

.stats .box {
  flex: 1;
  background: #f1f3f5;
  padding: 24px;
  border-radius: 16px;
}

.action-center {
  display: flex;
  justify-content: center;
  margin: 60px 0;
}

.btn-task {
  background: #4ccbe6;
  color: #fff;
  text-decoration: none;
  padding: 14px 36px;
  border-radius: 999px;
  font-weight: 600;
}

.team h3 {
  margin-bottom: 22px;
}

.team-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.team-item {
  background: #fff;
  border-radius: 14px;
  margin-bottom: 14px;
  padding: 16px 20px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.05);
  cursor: pointer;
}

.team-header {
  display: flex;
  align-items: center;
  gap: 16px;
}

.team-header img {
  width: 36px;
  height: 36px;
}

.member-list {
  list-style: none;
  padding-left: 52px;
  margin-top: 12px;
}

.member-list li {
  font-size: 14px;
  color: #555;
  padding: 6px 0;
}
</style>
@endsection
