@extends('layouts.app')

@section('title', 'Ubah Password - SIOPLAS')

@section('content')

<h2 style="font-size: 22px; font-weight: 600; margin-bottom: 40px;">
  Pengaturan Profil
</h2>

<div style="display: flex; justify-content: center;">
  <div style="width: 100%; max-width: 520px;">

    <!-- PROFIL -->
    <div style="text-align: center; margin-bottom: 40px;">
      <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png"
           style="width: 90px; height: 90px; border-radius: 50%; margin-bottom: 12px;">
      <h3 style="margin: 0; font-weight: 600;">Budi</h3>
      <p style="margin: 6px 0 0; font-size: 14px; color: #6B7280;">
        081234567890
      </p>
      <p style="margin: 2px 0 0; font-size: 14px; color: #6B7280;">
        Budi@kipm.go.id
      </p>
    </div>

    <!-- KEMBALI -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 30px;">
      <a href="{{ route('atasan.settings') }}"
         style="font-size: 20px; text-decoration: none; color: black;">
        ←
      </a>
      <span style="font-size: 16px; font-weight: 500;">Password</span>
    </div>

    <!-- ERROR MESSAGE -->
    @if ($errors->any())
      <div style="background:#FEE2E2;color:#991B1B;padding:12px;border-radius:8px;margin-bottom:20px;">
        {{ $errors->first() }}
      </div>
    @endif

    <!-- FORM -->
    <form method="POST" action="{{ route('atasan.update.password') }}">
      @csrf

      <!-- Password saat ini -->
      <div class="field">
        <label>Kata sandi saat ini</label>
        <div class="input-wrapper">
          <input type="password" id="current_password" name="current_password" required>
          <span class="eye" onclick="togglePassword('current_password', this)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M1 12C1 12 5 5 12 5C19 5 23 12 23 12C23 12 19 19 12 19C5 19 1 12 1 12Z"
                    stroke="black" stroke-width="2"/>
              <circle cx="12" cy="12" r="3"
                      stroke="black" stroke-width="2"/>
            </svg>
          </span>
        </div>
      </div>

      <!-- Password baru -->
      <div class="field">
        <label>Kata sandi baru</label>
        <div class="input-wrapper">
          <input type="password" id="new_password" name="new_password" required>
          <span class="eye" onclick="togglePassword('new_password', this)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M1 12C1 12 5 5 12 5C19 5 23 12 23 12C23 12 19 19 12 19C5 19 1 12 1 12Z"
                    stroke="black" stroke-width="2"/>
              <circle cx="12" cy="12" r="3"
                      stroke="black" stroke-width="2"/>
            </svg>
          </span>
        </div>
      </div>

      <!-- Konfirmasi -->
      <div class="field">
        <label>Konfirmasi kata sandi baru</label>
        <div class="input-wrapper">
          <input type="password" id="confirm_password" name="new_password_confirmation" required>
          <span class="eye" onclick="togglePassword('confirm_password', this)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M1 12C1 12 5 5 12 5C19 5 23 12 23 12C23 12 19 19 12 19C5 19 1 12 1 12Z"
                    stroke="black" stroke-width="2"/>
              <circle cx="12" cy="12" r="3"
                      stroke="black" stroke-width="2"/>
            </svg>
          </span>
        </div>
      </div>

      <!-- BUTTON -->
      <div style="display: flex; justify-content: center; margin-top: 50px;">
        <button type="submit" class="btn-confirm">
          Konfirmasi
        </button>
      </div>

    </form>

  </div>
</div>

<style>
.field {
  margin-bottom: 28px;
}

.field label {
  display: block;
  font-size: 14px;
  margin-bottom: 10px;
}

.input-wrapper {
  position: relative;
}

.input-wrapper input {
  width: 100%;
  border: none;
  border-bottom: 1px solid #000;
  padding: 8px 0;
  font-size: 14px;
  outline: none;
}

.eye {
  position: absolute;
  right: 0;
  bottom: 8px;
  cursor: pointer;
}

.btn-confirm {
  background-color: #4F8EF7;
  color: white;
  border: none;
  padding: 12px 50px;
  border-radius: 10px;
  font-size: 15px;
  cursor: pointer;
}
</style>

<script>
function togglePassword(inputId, el) {
  const input = document.getElementById(inputId);
  const svg = el.querySelector('svg');

  if (input.type === "password") {
    input.type = "text";
    svg.style.opacity = "0.4";
  } else {
    input.type = "password";
    svg.style.opacity = "1";
  }
}
</script>

@endsection
