@extends('layouts.app')

@section('content')

    @php
        $tugas = $data_tugas;
        $status = $tugas['status'] ?? 'Pending';
        
        $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
        if ($status == 'Selesai' || $status == 'Completed') {
            $statusClass = 'bg-green-100 text-green-800 border-green-200';
        } elseif ($status == 'Revisi' || $status == 'Rejected') {
            $statusClass = 'bg-red-100 text-red-800 border-red-200';
        } elseif ($status == 'Menunggu Review' || $status == 'Menunggu Approval') {
            $statusClass = 'bg-blue-100 text-blue-800 border-blue-200';
        }
    @endphp

    <div class="min-h-screen bg-gray-50 py-8 text-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Navigation Back --}}
            <div class="mb-6">
                <a href="{{ route('tugas.staff') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
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
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $tugas['judul'] }}</h1>
                                <p class="text-sm text-gray-500 mt-1">Diberikan oleh: <span class="font-medium text-gray-700">{{ $tugas['pembuat'] ?? 'Atasan' }}</span></p>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold border {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Deskripsi Tugas</h3>
                            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-100">
                                {!! nl2br(e($tugas['deskripsi'])) !!}
                            </div>
                        </div>

                        {{-- Attachment / File Tugas --}}
                        @if(isset($tugas['file_tugas']) && !empty($tugas['file_tugas']))
                        <div class="mt-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Lampiran Tugas</h3>
                            <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="p-2 bg-white rounded-md border border-blue-100 mr-3">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $tugas['file_tugas'] }}</p>
                                    <p class="text-xs text-gray-500">File pendukung dari {{ $tugas['pembuat'] ?? 'Atasan/Katimja' }}</p>
                                </div>
                                <div class="ml-2">
                                    {{-- Assuming the path is 'storage/tugas_files/' based on KatimjaController storeTugas logic --}}
                                    <a href="{{ asset('storage/tugas_files/' . $tugas['file_tugas']) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Feedback / Revisi from Atasan --}}
                    @if(!empty($tugas['respon']))
                    <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-amber-800 mb-2 flex items-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            Catatan / Revisi dari Atasan
                        </h3>
                        <div class="text-amber-900 bg-white/50 p-4 rounded-lg border border-amber-100 text-sm italic">
                            "{{ $tugas['respon'] }}"
                        </div>
                        @if(!empty($tugas['file_respon']))
                        <div class="mt-3">
                            <a href="{{ asset('storage/tugas_respon/' . $tugas['file_respon']) }}" target="_blank" class="inline-flex items-center text-sm text-amber-700 font-medium hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Lampiran Revisi: {{ $tugas['file_respon'] }}
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN: Submission & Metadata --}}
                <div class="space-y-6">
                    
                    {{-- Deadline Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Informasi Deadline</h3>
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-red-50 text-red-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Batas Waktu</p>
                                <p class="text-lg font-bold text-gray-900">{{ $tugas['batas_waktu'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Submission Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Pengumpulan Tugas</h3>

                        {{-- If already submitted --}}
                        @if(isset($tugas['file_selesai']) && !empty($tugas['file_selesai']))
                             <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
                                <p class="text-xs font-bold text-green-700 uppercase mb-2">File Terkirim</p>
                                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank" class="flex items-center p-2 bg-white rounded border border-green-200 hover:shadow-sm transition-shadow">
                                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm font-medium text-gray-700 truncate w-full">{{ $tugas['file_selesai'] }}</span>
                                </a>
                                @if($status != 'Selesai')
                                    <p class="text-xs text-green-600 mt-2 text-center">Menunggu review dari atasan.</p>
                                @else
                                    <p class="text-xs text-green-600 mt-2 text-center font-bold">Tugas Disetujui!</p>
                                @endif
                             </div>
                        
                             @if($status != 'Selesai')
                                <div class="text-center text-xs text-gray-400 mb-2">- atau upload ulang untuk revisi -</div>
                             @endif
                        @endif
                        
                        {{-- Upload Form --}}
                        @if($status != 'Selesai')
                        <form action="{{ route('tugas.upload', ['id' => $tugas['id'], 'source' => $tugas['source'] ?? 'atasan']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="relative group">
                                <label for="task_file_upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-indigo-400 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold text-indigo-600">Klik untuk upload</span></p>
                                        <p class="text-xs text-gray-400" id="file-chosen-text">PDF, DOCX, JPG (Max 5MB)</p>
                                    </div>
                                    <input type="file" name="file" id="task_file_upload" class="hidden" required>
                                </label>
                            </div>
                            <button type="submit" class="w-full mt-4 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kirim Tugas
                            </button>
                        </form>
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        document.getElementById('task_file_upload').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : "PDF, DOCX, JPG (Max 5MB)";
            document.getElementById('file-chosen-text').textContent = "Terpilih: " + fileName;
            document.getElementById('file-chosen-text').classList.add('text-indigo-600', 'font-medium');
        });
    </script>

@endsection