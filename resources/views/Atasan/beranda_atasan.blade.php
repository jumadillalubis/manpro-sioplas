@extends('layouts.app')

@section('title', 'Beranda Atasan - SIOPLAS')

@section('content')

<!-- HEADER & WELCOME -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-200">
  <div>
    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
      Selamat Datang, {{ $myName ?? 'Atasan' }}
    </h1>
    <p class="text-sm text-slate-500 mt-1">
      Ringkasan laporan masuk, status distribusi tugas, dan daftar tim manajemen.
    </p>
  </div>

  <div class="flex items-center gap-2 text-xs font-medium text-slate-600 bg-white px-3.5 py-2 rounded-lg border border-slate-200 shadow-sm self-start md:self-auto">
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
  </div>
</div>

<!-- RINGKASAN STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
  
  <!-- Card 1: Total Laporan -->
  <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Laporan Terkirim</span>
      <span class="p-2 rounded-lg bg-blue-50 text-blue-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
      </span>
    </div>

    <div class="flex items-baseline gap-2 mb-4">
      <span class="text-3xl font-bold text-slate-900">{{ $totalLaporan }}</span>
      <span class="text-sm font-medium text-slate-500">Laporan</span>
    </div>

    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
      <span class="text-slate-500">Laporan dari seluruh divisi</span>
      <a href="{{ route('laporan.atasan') }}" class="text-blue-600 font-semibold inline-flex items-center gap-1 hover:text-blue-800">
        Lihat Laporan
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </a>
    </div>
  </div>

  <!-- Card 2: Total Tugas -->
  <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Tugas Terkirim</span>
      <span class="p-2 rounded-lg bg-slate-100 text-slate-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
      </span>
    </div>

    <div class="flex items-baseline justify-between mb-4">
      <div class="flex items-baseline gap-2">
        <span class="text-3xl font-bold text-slate-900">{{ $totalTugas }}</span>
        <span class="text-sm font-medium text-slate-500">Tugas</span>
      </div>

      <div class="flex items-center gap-2 text-xs">
        <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-medium">Katimja: {{ $tugasKeKatimja }}</span>
        <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-medium">Staff: {{ $tugasKeStaff }}</span>
      </div>
    </div>

    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
      <span class="text-slate-500">Distribusi tugas aktif</span>
      <a href="{{ route('tugas.index') }}" class="text-blue-600 font-semibold inline-flex items-center gap-1 hover:text-blue-800">
        Kelola Tugas
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </a>
    </div>
  </div>

</div>


<!-- TIM MANAJEMEN & CATATAN PRIBADI -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  
  <!-- Tim Manajemen (2 Kolom) -->
  <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
    <div class="mb-5 pb-3 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="text-base font-bold text-slate-800">Tim Manajemen Divisi</h2>
        <p class="text-xs text-slate-500 mt-0.5">Daftar divisi dan anggota Katimja yang bertugas.</p>
      </div>
    </div>

    <div class="space-y-3">
      @php
          $divisions = [
              'Tata Usaha' => [
                  'icon' => 'https://cdn-icons-png.flaticon.com/128/8921/8921211.png',
                  'keywords' => ['tata usaha', 'tu']
              ],
              'Produksi Primer' => [
                  'icon' => 'https://cdn-icons-png.flaticon.com/128/2257/2257185.png',
                  'keywords' => ['produksi primer']
              ],
              'Pasca Panen' => [
                  'icon' => 'https://cdn-icons-png.flaticon.com/128/88/88528.png',
                  'keywords' => ['pasca panen']
              ],
              'LABOR' => [
                  'icon' => 'https://cdn-icons-png.flaticon.com/128/10557/10557880.png',
                  'keywords' => ['labor', 'laboratorium']
              ],
          ];
      @endphp

      @foreach($divisions as $label => $data)
          @php
              $members = $katimjas->filter(function($k) use ($data) {
                  foreach($data['keywords'] as $keyword) {
                      if (stripos($k->divisi, $keyword) !== false) {
                          return true;
                      }
                  }
                  return false;
              });
              $count = $members->count();
          @endphp

          <div class="rounded-lg border border-slate-200 overflow-hidden bg-slate-50/50 hover:bg-white transition-colors">
            <div class="team-header flex items-center justify-between p-4 cursor-pointer select-none">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded bg-white border border-slate-200 flex items-center justify-center p-1.5 shrink-0">
                  <img src="{{ $data['icon'] }}" alt="{{ $label }}" class="w-6 h-6 object-contain">
                </div>
                <div>
                  <h3 class="font-bold text-slate-800 text-sm">{{ $label }}</h3>
                  <span class="text-xs text-slate-500">{{ $count }} Anggota Terdaftar</span>
                </div>
              </div>

              <div class="chevron-icon text-slate-400 transition-transform duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </div>
            </div>

            <div class="member-panel hidden px-4 pb-4 pt-1 border-t border-slate-100 bg-white">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-3">
                @forelse($members as $member)
                    <div class="flex items-center gap-3 p-2.5 rounded border border-slate-100 bg-slate-50">
                      <div class="w-7 h-7 rounded bg-slate-700 text-white font-bold text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($member->nama, 0, 2)) }}
                      </div>
                      <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-slate-800 truncate">{{ $member->nama }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $member->jabatan ?? 'Katimja' }}</p>
                      </div>
                    </div>
                @empty
                    <div class="col-span-full py-3 text-center text-xs text-slate-400 italic bg-slate-50 rounded">
                      Belum ada anggota Katimja pada divisi ini.
                    </div>
                @endforelse
              </div>
            </div>
          </div>
      @endforeach
    </div>
  </div>

  <!-- Catatan Pribadi (1 Kolom) -->
  <div class="bg-slate-800 rounded-xl p-5 text-white shadow-sm flex flex-col justify-between border border-slate-700">
    <div>
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-bold text-slate-100 flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Catatan Pribadi
        </h3>
        <span class="text-[10px] text-emerald-400 font-medium">Tersimpan di Server</span>
      </div>

      <textarea id="notepad-content"
        class="w-full h-44 p-3 rounded-lg bg-slate-900/80 text-slate-200 text-xs resize-none focus:outline-none focus:ring-1 focus:ring-slate-500 placeholder-slate-500 border border-slate-700 font-mono"
        placeholder="Tulis catatan atau instruksi rahasia Anda di sini..."></textarea>
    </div>

    <div class="mt-4 space-y-2">
      <div class="flex gap-2">
        <button onclick="saveNote()"
          class="flex-1 bg-blue-600 text-white font-semibold py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-xs">
          Simpan Catatan
        </button>
        <button onclick="clearNote()"
          class="bg-slate-700 text-slate-300 font-medium py-2 px-3 rounded-lg hover:bg-slate-600 transition-colors text-xs">
          Hapus
        </button>
      </div>

      <p id="notepad-status" class="text-xs text-emerald-400 text-center hidden font-medium"></p>
    </div>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Accordion toggle
  document.querySelectorAll('.team-header').forEach(header => {
    header.addEventListener('click', function () {
      const panel = this.nextElementSibling;
      const chevron = this.querySelector('.chevron-icon');
      const isHidden = panel.classList.contains('hidden');
      
      document.querySelectorAll('.member-panel').forEach(p => p.classList.add('hidden'));
      document.querySelectorAll('.chevron-icon').forEach(c => c.classList.remove('rotate-180'));

      if (isHidden) {
        panel.classList.remove('hidden');
        chevron.classList.add('rotate-180');
      }
    });
  });

  // Load Note from Database / Server
  fetch("{{ route('note.get') }}")
    .then(res => res.json())
    .then(data => {
      if (data && data.content !== undefined) {
        document.getElementById('notepad-content').value = data.content;
      } else {
        const savedNote = localStorage.getItem('user_notepad');
        if (savedNote) document.getElementById('notepad-content').value = savedNote;
      }
    })
    .catch(() => {
      const savedNote = localStorage.getItem('user_notepad');
      if (savedNote) document.getElementById('notepad-content').value = savedNote;
    });
});

function saveNote() {
  const noteContent = document.getElementById('notepad-content').value;
  localStorage.setItem('user_notepad', noteContent);
  showStatus('Menyimpan ke database...');

  fetch("{{ route('note.save') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ content: noteContent })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showStatus('Tersimpan di Database!');
    } else {
      showStatus('Tersimpan (Lokal)');
    }
  })
  .catch(() => {
    showStatus('Tersimpan (Lokal)');
  });
}

function clearNote() {
  if (confirm('Apakah Anda yakin ingin menghapus catatan?')) {
    document.getElementById('notepad-content').value = '';
    localStorage.removeItem('user_notepad');
    saveNote();
  }
}

function showStatus(message) {
  const statusEl = document.getElementById('notepad-status');
  if (!statusEl) return;
  statusEl.textContent = message;
  statusEl.classList.remove('hidden');
  setTimeout(() => {
    statusEl.classList.add('hidden');
  }, 2500);
}
</script>

@endsection



