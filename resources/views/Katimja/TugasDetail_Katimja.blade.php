@extends('layouts.app')

@section('title', 'Detail Tugas Katimja')

@section('content')

@php
    $status = $tugas['status'] ?? 'Pending';
    $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
    if ($status == 'Selesai') $statusClass = 'bg-green-100 text-green-800 border-green-200';
    if ($status == 'Revisi') $statusClass = 'bg-red-100 text-red-800 border-red-200';
    if ($status == 'Menunggu Review') $statusClass = 'bg-blue-100 text-blue-800 border-blue-200';
@endphp

<div class="min-h-screen bg-gray-50 py-8 text-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Navigation Back --}}
        <div class="mb-6">
            <a href="{{ route('tugas.katimja') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Tugas
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT COLUMN: Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Header & Title --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between">
                         <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $tugas['judul'] ?? '-' }}</h1>
                            <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Penerima: <span class="font-medium text-gray-800 ml-1">{{ $tugas['penerima'] ?? 'Belum Ditugaskan' }}</span>
                                </span>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 rounded-full text-sm font-semibold border {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Deskripsi Tugas</h3>
                        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-100">
                            {!! nl2br(e($tugas['deskripsi'] ?? '-')) !!}
                        </div>
                    </div>

                    {{-- Lampiran Tugas (Dari Atasan/Creator) --}}
                     @if(isset($tugas['file_tugas']) && !empty($tugas['file_tugas']))
                     <div class="mt-6">
                         <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Lampiran Tugas</h3>
                         <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                             <div class="p-2 bg-white rounded-md border border-blue-100 mr-3">
                                 <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                             </div>
                             <div class="flex-1 min-w-0">
                                 <p class="text-sm font-medium text-gray-900 truncate">{{ $tugas['file_tugas'] }}</p>
                                 <p class="text-xs text-gray-500">File instruksi awal</p>
                             </div>
                             <div class="ml-2">
                                 {{-- Adjust path if necessary, assuming tugas_files --}}
                                 <a href="{{ asset('storage/tugas_files/' . $tugas['file_tugas']) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                     Download
                                 </a>
                             </div>
                         </div>
                     </div>
                     @endif
                </div>

                {{-- Feedback / Revisi from Atasan --}}
                @if(!empty($tugas['respon']) || !empty($tugas['file_respon']))
                <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-6 relative overflow-hidden">
                    <h3 class="text-lg font-bold text-amber-800 mb-2 flex items-center gap-2">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        Feedback / Revisi dari Atasan
                    </h3>
                    
                    @if(!empty($tugas['respon']))
                    <div class="text-amber-900 bg-white/50 p-4 rounded-lg border border-amber-100 text-sm italic mb-3">
                        "{{ $tugas['respon'] }}"
                    </div>
                    @endif

                    @if(!empty($tugas['file_respon']))
                    <div>
                        <a href="{{ asset('storage/tugas_respon/' . $tugas['file_respon']) }}" target="_blank" class="inline-flex items-center text-sm text-amber-700 font-medium hover:underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            Lampiran Feedback: {{ $tugas['file_respon'] }}
                        </a>
                    </div>
                    @endif
                </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: Action Cards --}}
            <div class="space-y-6">

                {{-- ASSIGNMENT CARD (Jika belum assigned ke personal) --}}
                @if(isset($staff) && count($staff) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Penugasan Staff</h3>
                    <p class="text-sm text-gray-500 mb-4">Tugaskan staff di divisi Anda untuk mengerjakan tugas ini.</p>
                    
                    <form action="{{ route('katimja.tugas.assign', $tugas['id']) }}" method="POST">
                        @csrf
                        <div class="flex space-x-2">
                             <select name="penerima" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Staff --</option>
                                @foreach($staff as $s)
                                    <option value="{{ $s->nama }}" {{ ($tugas['penerima'] ?? '') == $s->nama ? 'selected' : '' }}>{{ $s->nama }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                Assign
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- DEADLINE CARD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Deadline</h3>
                    <div class="flex items-center text-gray-900">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-lg font-bold">{{ $tugas['batas_waktu'] ?? '-' }}</span>
                    </div>
                </div>

                {{-- SUBMISSION / RESULT CARD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Hasil Pengerjaan</h3>

                    {{-- FILE DISPLAY --}}
                    @if(!empty($tugas['file_selesai']))
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg mb-6">
                            <p class="text-xs font-bold text-green-700 uppercase mb-2">File Selesai</p>
                            <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank" class="flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="truncate">{{ $tugas['file_selesai'] }}</span>
                            </a>
                            <div class="mt-2 text-xs text-gray-500">Oleh: {{ $tugas['file_selesai_oleh'] ?? 'Unknown' }}</div>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic mb-4">Belum ada file hasil pengerjaan.</div>
                    @endif

                    {{-- UPLOAD FORM (Only if not Selesai/Approved, allow Katimja to upload/forward) --}}
                     @if($status != 'Selesai')
                     <div class="border-t pt-4 mt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Aksi</h4>
                        
                        {{-- Logic: Using server-side flags for robustness --}}
                        
                        @if($tugas['is_me'])
                            {{-- Button Approve for Katimja's own task --}}
                            <form action="{{ route('katimja.tugas.approve', ['id' => $tugas['id'], 'source' => $tugas['source'] ?? 'katimja']) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                    Approve
                                </button>
                            </form>
                        @endif

                        {{-- Form Upload (Forward/Revision) --}}
                        <form action="{{ route('katimja.tugas.respon', ['id' => $tugas['id'], 'source' => $tugas['source'] ?? 'atasan']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="block text-xs font-medium text-gray-700 mb-1">Upload File / Feedback Tambahan</label>
                            <input type="file" name="upload-file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-3"/>
                            
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                @if($tugas['is_me'])
                                    Update Feedback
                                @else
                                    Teruskan ke Atasan / Update Feedback
                                @endif
                            </button>
                        </form>
                     </div>
                     @endif

                </div>

            </div>

        </div>
    </div>
</div>
@endsection