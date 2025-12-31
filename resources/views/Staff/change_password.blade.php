@extends('layouts.app')

@section('title', 'Profile Settings - SIOPLAS')

@section('content')
    <!-- Judul Halaman -->
    <h2 style="font-size: 22px; font-weight: 600; margin-bottom: 30px;">
        Profile Settings
    </h2>

    <!-- Wrapper untuk Konten Utama -->
    <div style="display: flex; justify-content: center;">
        <div style="width: 100%; max-width: 600px;">

            <!-- Profil Pengguna -->
            <div style="text-align: center; margin-bottom: 40px;">
                <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png"
                    alt="Avatar"
                    style="width: 110px; height: 110px; border-radius: 50%; margin-bottom: 12px;">

                <h3 style="margin: 0; font-weight: 600;">{{ Auth::user()->name }}</h3>
                <p style="margin: 6px 0 0; color: #6B7280; font-size: 14px;">
                    {{ Auth::user()->phone }}
                </p>
                <p style="margin: 2px 0 0; color: #6B7280; font-size: 14px;">
                    {{ Auth::user()->email }}
                </p>
            </div>

            <!-- Notifikasi Pengingat Laporan -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">
                    Notifikasi
                </h4>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <p style="margin: 0;">Pemberitahuan Pengingat Laporan</p>
                    <label class="switch" style="position: relative; width: 38px; height: 20px;">
                        <input type="checkbox" {{ Auth::user()->notification_enabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <!-- Preferensi Pengingat -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 4px;">
                    Preferensi Pengingat
                </h4>
                <p style="margin: 0; font-size: 14px; color: #000000;">Harian</p>
            </div>

            <!-- Bahasa -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 4px;">
                    Bahasa
                </h4>
                <p style="margin: 0; font-size: 14px; color: #000000;">Bahasa Indonesia</p>
            </div>

            <!-- Ubah Password -->
            <div style="margin-bottom: 50px;">
                <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">
                    Password
                </h4>
                <div style="display: flex; justify-content: space-between;">
                    <a href="{{ route('password.change') }}" style="text-decoration: underline; color: black;">
                        Ubah Password
                    </a>
                    <span>›</span>
                </div>
            </div>

            <!-- Tombol Keluar -->
            <div style="display: flex; justify-content: center;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 260px; background-color: #C62828; color: white; border: none; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 500; cursor: pointer;">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Style for Switch -->
    <style>
        .switch input {
            display: none;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ccc;
            border-radius: 20px;
            transition: .3s;
        }
        .slider::before {
            content: "";
            position: absolute;
            width: 14px;
            height: 14px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }
        input:checked + .slider {
            background-color: #3DB9E3;
        }
        input:checked + .slider::before {
            transform: translateX(18px);
        }
    </style>

@endsection
