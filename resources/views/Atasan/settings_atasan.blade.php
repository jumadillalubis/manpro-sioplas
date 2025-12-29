@extends('layouts.app')

@section('title', 'Pengaturan Profil - SIOPLAS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">

  <!-- Judul -->
  <div>
    <h2 style="font-size: 22px; font-weight: 600; margin-bottom: 30px;">Pengaturan Profil</h2>
  </div>

</div>

<!-- Konten utama -->
<div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap;">

  <!-- Kiri -->
  <div style="flex: 1; min-width: 350px; max-width: 600px;">

    <!-- Profil -->
    <div style="text-align: center; margin-bottom: 40px;">
      <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png" 
           alt="Avatar" 
           style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 10px;">
      <h3 style="margin: 0;">Budi</h3>
      <p style="margin: 4px 0 0; color: gray; font-size: 14px;">081234567890</p>
      <p style="margin: 0; color: gray; font-size: 14px;">Budi@kipm.go.id</p>
    </div>

    <!-- Notifikasi -->
    <div style="margin-bottom: 30px;">
      <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Notifikasi</h4>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0;">Pemberitahuan Pengingat Laporan</p>
        <label class="switch" style="position: relative; display: inline-block; width: 38px; height: 20px;">
          <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
          <span class="slider round"></span>
        </label>
      </div>
    </div>

    <!-- Preferensi Pengingat -->
    <div style="margin-bottom: 30px;">
      <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Preferensi Pengingat</h4>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0;">Harian</p>
        <span style="font-size: 18px;">⌄</span>
      </div>
    </div>

    <!-- Bahasa -->
    <div style="margin-bottom: 30px;">
      <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Bahasa</h4>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0;">Bahasa Indonesia</p>
        <span style="font-size: 18px;">⌄</span>
      </div>
    </div>

    <!-- Password -->
    <div style="margin-bottom: 50px;">
      <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Password</h4>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <a href="#" style="text-decoration: underline; color: black;">Ubah Password</a>
        <span style="font-size: 18px;">›</span>
      </div>
    </div>

    <!-- Tombol keluar -->
    <div style="display: flex; justify-content: center;">
      <button style="
        width: 250px;
        background-color: #C62828;
        color: white;
        border: none;
        padding: 12px 0;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
      ">
        Keluar
      </button>
    </div>

  </div>
</div>

<!-- Style toggle -->
<style>
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
  position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc; transition: .4s; border-radius: 34px;
}
.slider:before {
  position: absolute; content: ""; height: 14px; width: 14px;
  left: 3px; bottom: 3px; background-color: white;
  transition: .4s; border-radius: 50%;
}
input:checked + .slider {
  background-color: #3DB9E3;
}
input:checked + .slider:before {
  transform: translateX(18px);
}
</style>
@endsection
