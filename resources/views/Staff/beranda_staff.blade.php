@extends('layouts.app')

@section('content')
    <div class="p-8 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 mb-6">Selamat Datang, <span class="font-medium">Budi Susanto</span></p>

        {{-- RINGKASAN + DEADLINE --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            {{-- Ringkasan Aktivitas --}}
            <div class="lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Ringkasan Aktivitas Pegawai</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-100 rounded-xl p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Laporan Terkirim</p>
                        <p class="text-2xl font-bold text-gray-800">8 Laporan</p>
                    </div>

                    <div class="bg-gray-100 rounded-xl p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Tugas Terkirim</p>
                        <p class="text-2xl font-bold text-gray-800">15 Laporan</p>
                    </div>
                </div>
            </div>

            {{-- Notepad --}}
            <div class="bg-gradient-to-br from-cyan-400 to-sky-500 rounded-xl p-6 text-white shadow-lg">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <span>📝</span> Catatan Pribadi
                </h2>

                <div class="space-y-3">
                    <textarea id="notepad-content"
                        class="w-full h-32 p-3 rounded-lg text-gray-800 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-cyan-600 placeholder-gray-400"
                        placeholder="Tulis catatan Anda di sini..."></textarea>

                    <div class="flex gap-2">
                        <button onclick="saveNote()"
                            class="flex-1 bg-white text-cyan-600 font-semibold py-2 px-4 rounded-lg hover:bg-cyan-50 transition-colors text-sm flex items-center justify-center gap-2">
                            <span>💾</span> Simpan
                        </button>
                        <button onclick="clearNote()"
                            class="bg-white/20 text-white font-semibold py-2 px-4 rounded-lg hover:bg-white/30 transition-colors text-sm flex items-center justify-center gap-2">
                            <span>🗑️</span> Hapus
                        </button>
                    </div>

                    <p id="notepad-status" class="text-xs text-cyan-100 text-center hidden"></p>
                </div>
            </div>
        </div>

        {{-- Notepad Script --}}
        <script>
            // Load saved note on page load
            document.addEventListener('DOMContentLoaded', function () {
                const savedNote = localStorage.getItem('staff_notepad');
                if (savedNote) {
                    document.getElementById('notepad-content').value = savedNote;
                }
            });

            function saveNote() {
                const noteContent = document.getElementById('notepad-content').value;
                localStorage.setItem('staff_notepad', noteContent);
                showStatus('✅ Catatan tersimpan!');
            }

            function clearNote() {
                if (confirm('Apakah Anda yakin ingin menghapus catatan?')) {
                    document.getElementById('notepad-content').value = '';
                    localStorage.removeItem('staff_notepad');
                    showStatus('🗑️ Catatan dihapus!');
                }
            }

            function showStatus(message) {
                const statusEl = document.getElementById('notepad-status');
                statusEl.textContent = message;
                statusEl.classList.remove('hidden');
                setTimeout(() => {
                    statusEl.classList.add('hidden');
                }, 2000);
            }
        </script>

        {{-- DAFTAR TUGAS --}}
        <div class="bg-gray-100 rounded-xl p-6 shadow-sm">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Daftar Tugas</h2>

                {{-- Filter Bulan --}}
                <select class="bg-white border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none">
                    <option>October</option>
                    <option>September</option>
                    <option>August</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase border-b">
                        <tr>
                            <th class="py-3 px-4">Tugas</th>
                            <th class="py-3 px-4">Deadline</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">ID Tugas</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        {{-- Accepted --}}
                        <tr>
                            <td class="py-4 px-4 font-medium text-gray-800">Tinjau SOP Baru</td>
                            <td class="py-4 px-4">22 Juli 2024</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-xs">
                                    👤 Personal
                                </span>
                            </td>
                            <td class="py-4 px-4">T00128</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-green-500 text-white">
                                    Accepted
                                </span>
                            </td>
                        </tr>

                        {{-- Pending --}}
                        <tr>
                            <td class="py-4 px-4 font-medium text-gray-800">Evaluasi Kinerja Tim</td>
                            <td class="py-4 px-4">25 Juli 2024</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-xs">
                                    👥 Team
                                </span>
                            </td>
                            <td class="py-4 px-4">T00125</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-400 text-white">
                                    Pending
                                </span>
                            </td>
                        </tr>

                        {{-- Rejected --}}
                        <tr>
                            <td class="py-4 px-4 font-medium text-gray-800">Rapat Koordinasi Proyek</td>
                            <td class="py-4 px-4">28 Juli 2024</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-xs">
                                    👤 Personal
                                </span>
                            </td>
                            <td class="py-4 px-4">T00132</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-red-500 text-white">
                                    Rejected
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection