<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi OTP - SIOPLAS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      background: linear-gradient(135deg, #e8f7fb 0%, #d0edf6 40%, #e0f3f8 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .card {
      background: #ffffff;
      border-radius: 24px;
      padding: 48px 44px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 20px 60px rgba(110, 200, 224, 0.2), 0 4px 20px rgba(0,0,0,0.06);
      animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
      from { transform: translateY(24px); opacity: 0; }
      to   { transform: translateY(0); opacity: 1; }
    }

    .logo-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      margin-bottom: 32px;
    }

    .logo-row img { height: 56px; }

    .icon-lock {
      width: 72px;
      height: 72px;
      background: linear-gradient(135deg, #6ec8e0, #4ab3cc);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 8px 24px rgba(110, 200, 224, 0.4);
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { box-shadow: 0 8px 24px rgba(110, 200, 224, 0.4); }
      50%       { box-shadow: 0 8px 32px rgba(110, 200, 224, 0.65); }
    }

    .icon-lock svg { width: 32px; height: 32px; fill: white; }

    h1 {
      text-align: center;
      font-size: 22px;
      font-weight: 700;
      color: #1a3a4a;
      margin-bottom: 8px;
    }

    .subtitle {
      text-align: center;
      color: #6b8fa0;
      font-size: 13.5px;
      line-height: 1.6;
      margin-bottom: 28px;
    }

    .subtitle strong {
      color: #1a7fa3;
      font-weight: 600;
    }

    /* Alert Messages */
    .alert {
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .alert-error {
      background: #fff2f2;
      border: 1px solid #f5c6c6;
      color: #c0392b;
    }

    .alert-info {
      background: #f0f9fc;
      border: 1px solid #b3dfe8;
      color: #1a7fa3;
    }

    /* Timer */
    .timer-wrap {
      text-align: center;
      margin-bottom: 24px;
    }

    .timer-label { font-size: 12px; color: #999; margin-bottom: 4px; }

    #countdown {
      font-size: 28px;
      font-weight: 700;
      color: #1a7fa3;
      font-variant-numeric: tabular-nums;
      transition: color 0.3s;
    }

    #countdown.warning { color: #e05050; }

    /* OTP Inputs */
    .otp-label {
      font-size: 12px;
      font-weight: 600;
      color: #8aa6b5;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      text-align: center;
      margin-bottom: 14px;
    }

    .otp-inputs {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-bottom: 28px;
    }

    .otp-inputs input {
      width: 54px;
      height: 62px;
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      color: #1a3a4a;
      border: 2px solid #d4eaf2;
      border-radius: 14px;
      background: #f4f9fc;
      outline: none;
      transition: all 0.2s ease;
      font-family: 'Poppins', monospace;
      caret-color: transparent;
    }

    .otp-inputs input:focus {
      border-color: #6ec8e0;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(110, 200, 224, 0.18);
      transform: translateY(-2px);
    }

    .otp-inputs input.filled {
      border-color: #6ec8e0;
      background: #f0fafd;
    }

    /* Hidden input untuk form submit */
    #otp-hidden { display: none; }

    /* Submit Button */
    .btn-verify {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 14px;
      background: linear-gradient(135deg, #6ec8e0, #4ab3cc);
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 4px 16px rgba(110, 200, 224, 0.35);
      font-family: 'Poppins', sans-serif;
      letter-spacing: 0.3px;
    }

    .btn-verify:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(110, 200, 224, 0.5);
    }

    .btn-verify:active { transform: translateY(0); }

    .btn-verify:disabled {
      background: linear-gradient(135deg, #b0dce8, #9bcedd);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Back Link */
    .back-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: #8aa6b5;
    }

    .back-link a {
      color: #1a7fa3;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .back-link a:hover { color: #4ab3cc; }

    /* Expired state */
    .expired-msg {
      text-align: center;
      color: #e05050;
      font-size: 13.5px;
      font-weight: 500;
      margin-bottom: 12px;
      display: none;
    }
  </style>
</head>
<body>

  <div class="card">

    <div class="logo-row">
      <img src="{{ asset('images/logo-left.png') }}" alt="Logo Kiri">
      <img src="{{ asset('images/logo-right.png') }}" alt="Logo Kanan">
    </div>

    <div class="icon-lock">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
      </svg>
    </div>

    <h1>Verifikasi Dua Langkah</h1>

    <p class="subtitle">
      Kode OTP 6-digit telah dikirim ke email<br>
      <strong>{{ $emailMasked ?: session('otp_email_masked', 'email Anda') }}</strong>
    </p>

    {{-- Flash Messages --}}
    @if (session('error'))
      <div class="alert alert-error">
        <span>⚠️</span>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @if (session('info'))
      <div class="alert alert-info">
        <span>✉️</span>
        <span>{{ session('info') }}</span>
      </div>
    @endif

    {{-- Timer Countdown --}}
    <div class="timer-wrap">
      <div class="timer-label">Kode berlaku selama</div>
      <div id="countdown">05:00</div>
    </div>

    <p class="expired-msg" id="expired-msg">⏰ Kode OTP telah kedaluwarsa. Silakan login ulang.</p>

    {{-- Form OTP --}}
    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form">
      @csrf

      <p class="otp-label">Masukkan Kode OTP</p>

      {{-- 6 Kotak Input OTP --}}
      <div class="otp-inputs" id="otp-boxes">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d1" autocomplete="off">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d2" autocomplete="off">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d3" autocomplete="off">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d4" autocomplete="off">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d5" autocomplete="off">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" id="d6" autocomplete="off">
      </div>

      {{-- Hidden input yang berisi nilai OTP gabungan --}}
      <input type="hidden" name="otp" id="otp-hidden">

      <button type="submit" class="btn-verify" id="btn-submit" disabled>
        ✓ Verifikasi & Masuk
      </button>
    </form>

    <div class="back-link">
      <a href="{{ route('login') }}">← Kembali ke halaman login</a>
    </div>

  </div>

  <script>
    // ─── OTP Input Handler ────────────────────────────────────────────────────
    const digits  = document.querySelectorAll('.otp-digit');
    const hidden  = document.getElementById('otp-hidden');
    const btnSubmit = document.getElementById('btn-submit');

    function syncHiddenInput() {
      const val = Array.from(digits).map(d => d.value).join('');
      hidden.value = val;
      // Aktifkan tombol hanya jika 6 digit sudah terisi semua
      const allFilled = val.length === 6 && /^\d{6}$/.test(val);
      btnSubmit.disabled = !allFilled;
      digits.forEach(d => {
        d.classList.toggle('filled', d.value !== '');
      });
    }

    digits.forEach((input, idx) => {
      input.addEventListener('input', (e) => {
        // Hanya terima angka
        input.value = input.value.replace(/\D/g, '').slice(-1);

        if (input.value && idx < digits.length - 1) {
          digits[idx + 1].focus();
        }
        syncHiddenInput();
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && idx > 0) {
          digits[idx - 1].focus();
          digits[idx - 1].value = '';
          syncHiddenInput();
        }
        // Navigasi dengan arrow keys
        if (e.key === 'ArrowLeft' && idx > 0) digits[idx - 1].focus();
        if (e.key === 'ArrowRight' && idx < digits.length - 1) digits[idx + 1].focus();
      });

      // Handle paste (tempel 6 digit sekaligus)
      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        [...text].forEach((ch, i) => {
          if (digits[i]) digits[i].value = ch;
        });
        const nextIdx = Math.min(text.length, digits.length - 1);
        digits[nextIdx].focus();
        syncHiddenInput();
      });
    });

    // Fokus ke kotak pertama saat halaman dimuat
    digits[0].focus();

    // Submit form saat Enter ditekan di kotak manapun
    document.getElementById('otp-form').addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !btnSubmit.disabled) {
        document.getElementById('otp-form').submit();
      }
    });

    // ─── Countdown Timer (5 menit) ────────────────────────────────────────────
    let totalSeconds = 5 * 60; // 300 detik
    const countdownEl  = document.getElementById('countdown');
    const expiredMsg   = document.getElementById('expired-msg');

    function formatTime(secs) {
      const m = String(Math.floor(secs / 60)).padStart(2, '0');
      const s = String(secs % 60).padStart(2, '0');
      return m + ':' + s;
    }

    const timer = setInterval(() => {
      totalSeconds--;
      countdownEl.textContent = formatTime(totalSeconds);

      // Warnakan merah saat < 60 detik
      if (totalSeconds < 60) {
        countdownEl.classList.add('warning');
      }

      if (totalSeconds <= 0) {
        clearInterval(timer);
        countdownEl.textContent = '00:00';
        // Nonaktifkan form
        digits.forEach(d => { d.disabled = true; d.style.opacity = '0.5'; });
        btnSubmit.disabled = true;
        expiredMsg.style.display = 'block';
      }
    }, 1000);
  </script>

</body>
</html>
