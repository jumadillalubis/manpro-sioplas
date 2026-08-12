@extends('layouts.app')

@section('title', 'Beranda Katimja - SIOPLAS')

@section('content')

<!-- HEADER & WELCOME -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-200">
  <div>
    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
      Selamat Datang, {{ session('user_nama', 'Katimja') }}
    </h1>
    <p class="text-sm text-slate-500 mt-1">
      Dashboard Kepala Tim Kerja: Kelola delegasi tugas tim dan pantau progres laporan pegawai.
    </p>
  </div>

  <div class="flex items-center gap-2 text-xs font-medium text-slate-600 bg-white px-3.5 py-2 rounded-lg border border-slate-200 shadow-sm self-start md:self-auto">
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
  </div>
</div>

<!-- RINGKASAN + NOTEPAD -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

  <!-- Ringkasan Aktivitas -->
  <div class="lg:col-span-2 space-y-4">
    <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ringkasan Aktivitas Tim</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-medium text-slate-500">Tugas Kelolaan</span>
          <span class="p-2 rounded bg-slate-100 text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          </span>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-bold text-slate-900">{{ $totalTugas ?? 0 }}</span>
          <span class="text-sm font-medium text-slate-500">Tugas</span>
        </div>
      </div>

      <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-medium text-slate-500">Laporan Pegawai</span>
          <span class="p-2 rounded bg-slate-100 text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </span>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-bold text-slate-900">{{ $totalLaporan ?? 0 }}</span>
          <span class="text-sm font-medium text-slate-500">Laporan</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Notepad Widget -->
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
        class="w-full h-28 p-3 rounded-lg bg-slate-900/80 text-slate-200 text-xs resize-none focus:outline-none focus:ring-1 focus:ring-slate-500 placeholder-slate-500 border border-slate-700 font-mono"
        placeholder="Tulis catatan atau instruksi di sini..."></textarea>
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

<!-- DAFTAR TUGAS -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 pb-3 border-b border-slate-100">
    <div>
      <h2 class="text-base font-bold text-slate-800">Daftar Tugas Katimja</h2>
      <p class="text-xs text-slate-500 mt-0.5">Daftar tugas yang sedang berjalan beserta statusnya.</p>
    </div>

    <!-- Filter Bulan -->
    <div class="flex items-center gap-2">
      <label for="filterBulan" class="text-xs font-medium text-slate-500">Filter Bulan:</label>
      <select id="filterBulan" onchange="filterTugasByMonth(this.value)" class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 focus:outline-none cursor-pointer">
        <option value="">Semua Bulan</option>
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
      </select>
    </div>
  </div>

  <div class="overflow-x-auto rounded-lg border border-slate-200">
    <table class="w-full text-sm text-left text-slate-600">
      <thead class="text-xs font-semibold text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Judul Tugas</th>
          <th class="py-3 px-4">Batas Waktu</th>
          <th class="py-3 px-4">Tipe</th>
          <th class="py-3 px-4">ID Tugas</th>
          <th class="py-3 px-4 text-center">Status</th>
        </tr>
      </thead>

      <tbody id="tugasTableBody" class="divide-y divide-slate-100">
        @forelse($createdTasks as $task)
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="py-3 px-4 font-semibold text-slate-800 text-xs md:text-sm">{{ $task['judul'] }}</td>
          <td class="py-3 px-4 task-deadline text-xs text-slate-500">{{ $task['deadline'] }}</td>
          <td class="py-3 px-4 text-xs text-slate-600">
            <span class="inline-block px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-medium">
              {{ $task['type'] }}
            </span>
          </td>
          <td class="py-3 px-4 font-mono text-xs text-slate-500">#{{ $task['id_tugas'] }}</td>
          <td class="py-3 px-4 text-center">
            @php
              $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200'; // Default Pending
              if(in_array($task['status'], ['Accepted', 'Selesai', 'Approved'])) $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
              if(in_array($task['status'], ['Rejected', 'Revisi'])) $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
            @endphp
            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded border {{ $badgeClass }}">
              {{ $task['status'] }}
            </span>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
            Belum ada tugas terdaftar.
          </td>
        </tr>
        @endforelse
        <tr id="tugasEmptyRow" class="empty-row" style="display: none;">
          <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
            Tidak ada tugas untuk bulan ini.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    fetch("{{ route('note.get') }}")
      .then(res => res.json())
      .then(data => {
        if (data && data.content !== undefined) {
          document.getElementById('notepad-content').value = data.content;
        } else {
          const savedNote = localStorage.getItem('katimja_notepad');
          if (savedNote) document.getElementById('notepad-content').value = savedNote;
        }
      })
      .catch(() => {
        const savedNote = localStorage.getItem('katimja_notepad');
        if (savedNote) document.getElementById('notepad-content').value = savedNote;
      });
  });

  function saveNote() {
    const noteContent = document.getElementById('notepad-content').value;
    localStorage.setItem('katimja_notepad', noteContent);
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
      localStorage.removeItem('katimja_notepad');
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

  function filterTugasByMonth(selectedMonth) {
    const rows = document.querySelectorAll('#tugasTableBody tr');
    let hasVisibleRows = false;
    let totalRowsCount = 0;

    rows.forEach(row => {
      if (row.classList.contains('empty-row')) return;
      totalRowsCount++;
      const deadlineText = row.querySelector('.task-deadline').textContent;
      
      if (selectedMonth === "" || deadlineText.includes(selectedMonth)) {
        row.style.display = "";
        hasVisibleRows = true;
      } else {
        row.style.display = "none";
      }
    });

    const emptyRow = document.getElementById('tugasEmptyRow');
    if (emptyRow) {
      emptyRow.style.display = (!hasVisibleRows && totalRowsCount > 0) ? "" : "none";
    }
  }
</script>

@endsection