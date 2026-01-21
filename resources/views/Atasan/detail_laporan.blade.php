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

            <div class="mb-8">
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

            <div>
                <a href="{{ route('laporan.atasan') }}" class="text-gray-600 hover:text-gray-900 font-medium">← Kembali ke Daftar Laporan</a>
            </div>
        </div>
    </div>
</div>
@endsection
