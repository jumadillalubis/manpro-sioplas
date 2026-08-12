@extends('layouts.app')

@section('content')
@php
    $nama = session('user_nama', 'User');
@endphp

<style>
    .pw-wrap {
        padding: 32px;
        max-width: 520px;
        margin: 0 auto;
        font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    .pw-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b8fa0;
        text-decoration: none;
        margin-bottom: 20px;
        transition: color 0.15s;
    }
    .pw-back:hover { color: #1a7fa3; }

    .pw-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a3a4a;
        margin-bottom: 24px;
    }

    .pw-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        border: 1px solid #f0f0f0;
        padding: 32px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #1f2937;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f9fafb;
        font-family: inherit;
        box-sizing: border-box;
    }
    .form-input:focus {
        border-color: #6ec8e0;
        box-shadow: 0 0 0 3px rgba(110, 200, 224, 0.15);
        background: #fff;
    }

    .hint {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 5px;
    }

    .divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 24px 0;
    }

    .btn-save {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #6ec8e0, #4ab3cc);
        color: white;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(110,200,224,0.35);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(110,200,224,0.45);
    }

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
    .flash-warning {
        background: #fef3c7; border: 1px solid #fcd34d; color: #92400e;
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: 13px;
    }
    .flash-warning ul { margin: 6px 0 0 18px; padding: 0; }
</style>

<div class="pw-wrap">

    <a href="{{ route('settings') }}" class="pw-back">
        ← Kembali ke Pengaturan
    </a>

    <h1 class="pw-title">🔑 Ubah Password</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="flash-warning">
            ⚠️ Mohon periksa kembali:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pw-card">

        <form method="POST" action="{{ route('settings.password.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Password Lama</label>
                <input type="password" name="password_lama" class="form-input"
                       placeholder="Masukkan password saat ini" autocomplete="current-password">
            </div>

            <hr class="divider">

            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password_baru" class="form-input"
                       placeholder="Masukkan password baru" autocomplete="new-password">
                <p class="hint">Minimal 4 karakter</p>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" class="form-input"
                       placeholder="Ulangi password baru" autocomplete="new-password">
            </div>

            <button type="submit" class="btn-save">
                Simpan Password Baru
            </button>
        </form>

    </div>
</div>
@endsection
