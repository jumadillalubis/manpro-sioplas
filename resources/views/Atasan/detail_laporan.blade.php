@extends('layouts.app')

@section('title', 'Detail Laporan - SIOPLAS')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Detail Laporan - {{ $laporan->triwulan }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-sm font-medium text-gray-500">Judul Indikator</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $laporan->judul ?? 'Indikator ' . $laporan->indikator_id }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Tanggal Upload</p>
                    <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y, H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Divisi Pengirim</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $laporan->devisi }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $laporan->status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                          ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($laporan->status) }}
                    </span>
                </div>
            </div>

            <!-- TABS NAVIGATION -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex gap-6" aria-label="Tabs">
                        <button onclick="switchTab('detail', event)" id="tab-btn-detail" 
                                class="tab-btn border-b-2 py-4 px-1 text-sm font-medium text-blue-600 border-blue-600 outline-none transition-colors duration-200">
                            Detail Laporan
                        </button>
                        <button onclick="switchTab('summary', event)" id="tab-btn-summary" 
                                class="tab-btn border-b-2 py-4 px-1 text-sm font-medium text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 outline-none transition-colors duration-200">
                            Ringkasan AI 
                            @if($laporan->laporan_summary)
                                <span class="ml-2 bg-purple-100 text-purple-600 py-0.5 px-2 rounded-full text-xs">Tersedia</span>
                            @endif
                        </button>
                    </nav>
                </div>
            </div>

            <!-- TAB 1: FILE LAMPIRAN (ORIGINAL) -->
            <div id="tab-content-detail" class="tab-content block animate-fade-in">
                <h3 class="text-lg font-medium text-gray-900 mb-4">File Lampiran</h3>
                @if($laporan->lampiran)
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-600 truncate">{{ basename($laporan->lampiran) }}</span>
                            <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download
                            </a>
                        </div>
                        
                        {{-- Preview IF PDF or Image --}}
                        @php
                            $ext = pathinfo($laporan->lampiran, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset('storage/' . $laporan->lampiran) }}" alt="Preview" class="max-w-full h-auto rounded shadow-sm">
                        @elseif(strtolower($ext) === 'pdf')
                            <iframe src="{{ asset('storage/' . $laporan->lampiran) }}" width="100%" height="600px" class="border rounded"></iframe>
                        @else
                            <p class="text-gray-500 italic">Preview tidak tersedia untuk tipe file ini.</p>
                        @endif
                    </div>
                @else
                    <p class="text-red-500 italic">Tidak ada lampiran.</p>
                @endif
            </div>

            <!-- TAB 2: RINGKASAN AI -->
            <div id="tab-content-summary" class="tab-content hidden animate-fade-in">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6 border-b pb-4">
                        <div class="flex items-center gap-2">
                             <span class="text-2xl">✨</span>
                             <h3 class="text-lg font-bold text-gray-800">Ringkasan Eksekutif (AI)</h3>
                        </div>
                        <span class="text-xs text-gray-500 bg-white px-2 py-1 border rounded">Model: Qwen2.5-3B</span>
                    </div>
                    
                    @if($laporan->laporan_summary)
                        <div class="prose max-w-none text-gray-800 leading-relaxed text-justify whitespace-pre-line font-sans" style="font-size: 15px;">
                            {!! $laporan->laporan_summary !!}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mb-4 text-4xl">🤖</div>
                            <p class="text-gray-500 mb-2 font-medium">Belum ada ringkasan AI untuk laporan ini.</p>
                            <p class="text-sm text-gray-400">Silakan kembali ke daftar laporan dan klik tombol "AI Summary" untuk membuatnya.</p>
                        </div>
                    @endif
                </div>
            </div>

            <script>
                function switchTab(tabName, event) {
                    // Hide all contents
                    document.querySelectorAll('.tab-content').forEach(el => {
                        el.classList.add('hidden');
                        el.classList.remove('block');
                    });
                    
                    // Show target content
                    const targetContent = document.getElementById('tab-content-' + tabName);
                    if(targetContent) {
                        targetContent.classList.remove('hidden');
                        targetContent.classList.add('block');
                    }
                    
                    // Reset all buttons
                    document.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('text-blue-600', 'border-blue-600');
                        btn.classList.add('text-gray-500', 'border-transparent');
                    });
                    
                    // Activate clicked button (or find by ID if event is null)
                    const activeBtn = event ? event.currentTarget : document.getElementById('tab-btn-' + tabName);
                    if(activeBtn) {
                        activeBtn.classList.remove('text-gray-500', 'border-transparent');
                        activeBtn.classList.add('text-blue-600', 'border-blue-600');
                    }
                }

                // Check URL Param on Load
                document.addEventListener("DOMContentLoaded", function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if(urlParams.get('tab') === 'summary') {
                        switchTab('summary');
                    }
                });
            </script>

            <div>
                <a href="{{ route('laporan.atasan') }}" class="text-gray-600 hover:text-gray-900 font-medium">← Kembali ke Daftar Laporan</a>
            </div>
        </div>
    </div>
</div>
@endsection
