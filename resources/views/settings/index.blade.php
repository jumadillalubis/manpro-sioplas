@extends('layouts.app')

@section('content')
@php
    $nama       = session('user_nama', 'User');
    $email      = session('user_email', '-');
    $jabatan    = strtoupper(session('user_jabatan', 'STAFF'));
    $divisi     = session('user_divisi', '');
    $nip        = session('user_nip', '');
    $nup        = session('user_nup', '');
    $pangkat    = session('user_pangkat_gol', '');
    $pendidikan = session('user_pendidikan', '');

    // Inisial nama untuk avatar (maks 2 huruf)
    $words    = explode(' ', trim($nama));
    $inisial  = strtoupper(substr($words[0], 0, 1));
    if (count($words) > 1) $inisial .= strtoupper(substr($words[1], 0, 1));

    // Warna avatar berdasarkan jabatan
    $avatarColor = '#6ec8e0'; // default biru SIOPLAS
    $jabatanLower = strtolower($jabatan);
    if (str_contains($jabatanLower, 'atasan') || str_contains($jabatanLower, 'kepala dinas')) {
        $avatarColor = '#8b5cf6'; // ungu untuk Atasan
    } elseif (str_contains($jabatanLower, 'katimja') || str_contains($jabatanLower, 'kepala tim') || str_contains($jabatanLower, 'sekretaris')) {
        $avatarColor = '#f59e0b'; // amber untuk Katimja
    }
@endphp

<style>
    .settings-wrap {
        padding: 32px;
        max-width: 860px;
        margin: 0 auto;
        font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    .settings-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a3a4a;
        margin-bottom: 28px;
    }

    .settings-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        display: flex;
    }

    /* ── Panel Kiri ── */
    .profile-panel {
        width: 260px;
        flex-shrink: 0;
        background: linear-gradient(160deg, #f0fafd 0%, #e8f4f8 100%);
        border-right: 1px solid #e5eef2;
        padding: 36px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 800;
        color: white;
        margin-bottom: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        letter-spacing: 1px;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a3a4a;
        margin-bottom: 4px;
    }

    .profile-email {
        font-size: 12px;
        color: #6b8fa0;
        margin-bottom: 12px;
        word-break: break-all;
    }

    .badge-jabatan {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .profile-meta {
        width: 100%;
        margin-top: 20px;
        text-align: left;
    }

    .meta-row {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        padding: 10px 12px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5eef2;
    }

    .meta-label {
        font-size: 10px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    /* ── Panel Kanan ── */
    .settings-panel {
        flex: 1;
        padding: 36px 32px;
    }

    .settings-section {
        margin-bottom: 28px;
    }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 10px;
    }

    .settings-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #f9fafb;
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        margin-bottom: 8px;
        text-decoration: none;
        color: #374151;
        transition: all 0.15s ease;
    }

    .settings-row:hover {
        background: #f0f9fc;
        border-color: #b3dfe8;
    }

    .row-left { display: flex; align-items: center; gap: 12px; }
    .row-icon { font-size: 18px; }
    .row-title { font-size: 14px; font-weight: 600; color: #1f2937; }
    .row-desc  { font-size: 12px; color: #9ca3af; margin-top: 1px; }
    .row-arrow { color: #d1d5db; font-size: 18px; }

    /* Flash messages */
    .flash-success {
        background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46;
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: 13px; display: flex; align-items: center; gap: 8px;
    }
    .flash-error {
        background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: 13px; display: flex; align-items: center; gap: 8px;
    }

    /* Logout */
    .btn-logout {
        display: block;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #ef4444;
        color: white;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background 0.2s ease;
        margin-top: 8px;
        font-family: inherit;
    }
    .btn-logout:hover { background: #dc2626; }
</style>

<div class="settings-wrap">
    <h1 class="settings-title">⚙️ Pengaturan Akun</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">❌ {{ session('error') }}</div>
    @endif

    <div class="settings-card">

        {{-- ──────── PANEL KIRI: Profil ──────── --}}
        <div class="profile-panel">
            {{-- Avatar Inisial --}}
            <div class="avatar-circle" style="background: {{ $avatarColor }};">
                {{ $inisial }}
            </div>

            <div class="profile-name">{{ $nama }}</div>
            <div class="profile-email">{{ $email ?: '-' }}</div>
            <span class="badge-jabatan">{{ $jabatan }}</span>

            {{-- Info Tambahan --}}
            <div class="profile-meta">
                @if($divisi)
                    <div class="meta-row">
                        <span class="meta-label">Divisi</span>
                        <span class="meta-value">{{ $divisi }}</span>
                    </div>
                @endif
                @if($nip)
                    <div class="meta-row">
                        <span class="meta-label">NIP</span>
                        <span class="meta-value">{{ $nip }}</span>
                    </div>
                @endif
                @if($nup)
                    <div class="meta-row">
                        <span class="meta-label">NUP</span>
                        <span class="meta-value">{{ $nup }}</span>
                    </div>
                @endif
                @if($pangkat)
                    <div class="meta-row">
                        <span class="meta-label">Pangkat / Gol</span>
                        <span class="meta-value">{{ $pangkat }}</span>
                    </div>
                @endif
                @if($pendidikan)
                    <div class="meta-row">
                        <span class="meta-label">Pendidikan</span>
                        <span class="meta-value">{{ $pendidikan }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ──────── PANEL KANAN: Pengaturan ──────── --}}
        <div class="settings-panel">

            {{-- Keamanan --}}
            <div class="settings-section">
                <p class="section-label">🔐 Keamanan</p>

                <a href="{{ route('settings.password') }}" class="settings-row">
                    <div class="row-left">
                        <span class="row-icon">🔑</span>
                        <div>
                            <div class="row-title">Ubah Password</div>
                            <div class="row-desc">Ganti password akun Anda</div>
                        </div>
                    </div>
                    <span class="row-arrow">›</span>
                </a>
            </div>

            {{-- Informasi Sesi --}}
            <div class="settings-section">
                <p class="section-label">📋 Informasi Sesi</p>

                <div class="settings-row" style="cursor:default;">
                    <div class="row-left">
                        <span class="row-icon">👤</span>
                        <div>
                            <div class="row-title">Login sebagai</div>
                            <div class="row-desc">{{ $nama }} · {{ $jabatan }}</div>
                        </div>
                    </div>
                </div>

                <div class="settings-row" style="cursor:default;">
                    <div class="row-left">
                        <span class="row-icon">✉️</span>
                        <div>
                            <div class="row-title">Email</div>
                            <div class="row-desc">{{ $email ?: 'Tidak tersedia' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logout --}}
            <div class="settings-section">
                <p class="section-label">🚪 Sesi</p>
                <a href="{{ route('logout') }}" class="btn-logout">
                    Keluar dari Akun
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
