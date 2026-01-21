@extends('layouts.app')

@section('title', 'Detail Tugas Katimja')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Detail Tugas: {{ $tugas['judul'] ?? '-' }}</h4>

        <!-- Tugas Detail -->
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Penerima Tugas</h5>
            <p class="text-gray-600 mb-2">{{ $tugas['penerima'] ?? '-' }}</p>
            
            <!-- Logic: Jika penerima == divisi atau belum ada staff, tampilkan form assign -->
             @if(isset($staff) && count($staff) > 0)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-2">
                <form action="{{ route('katimja.tugas.assign', $tugas['id']) }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tugaskan ke Staff:</label>
                        <select name="penerima" class="block w-full mt-1 p-2 bg-white border border-gray-300 rounded-md text-sm">
                            <option value="">-- Pilih Staff --</option>
                            @foreach($staff as $s)
                                <option value="{{ $s->nama }}" {{ $tugas['penerima'] == $s->nama ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Kirim
                    </button>
                </form>
            </div>
            @endif
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Deadline</h5>
            <p class="text-gray-600">{{ $tugas['batas_waktu'] ?? '-' }}</p>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Deskripsi</h5>
            <p class="text-gray-600">{{ $tugas['deskripsi'] ?? '-' }}</p>
        </div>

        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Status</h5>
            <span class="inline-block px-3 py-1 rounded-full text-white {{ $tugas['status'] === 'Selesai' ? 'bg-green-500' : 'bg-yellow-500' }}">
                {{ $tugas['status'] ?? 'Pending' }}
            </span>
        </div>
        
        @if(!empty($tugas['file_selesai']))
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">File Hasil Pengerjaan (Staff)</h5>
            <div class="flex items-center gap-3 mt-1">
                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    {{ $tugas['file_selesai'] }}
                </a>
                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" download class="text-gray-500 hover:text-blue-600" title="Download File">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endif

        @if(!empty($tugas['respon']) || !empty($tugas['file_respon']))
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="text-lg font-semibold text-yellow-800 mb-2">📢 Respon dari Atasan</h5>
            
            @if(!empty($tugas['respon']))
            <p class="text-gray-800 italic mb-3">"{{ $tugas['respon'] }}"</p>
            @endif

            @if(!empty($tugas['file_respon']))
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-500 uppercase">Lampiran:</span>
                <a href="{{ asset('storage/tugas_respon/' . $tugas['file_respon']) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-semibold">
                    {{ $tugas['file_respon'] }}
                </a>
                <a href="{{ asset('storage/tugas_respon/' . $tugas['file_respon']) }}" download class="text-gray-500 hover:text-blue-600" title="Download">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- Form untuk Upload File / Approve -->
        <div class="mb-6">
            <form action="{{ route('katimja.tugas.respon', $tugas['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="upload-file" class="text-sm font-semibold text-gray-700">Upload File Tugas</label>
                <input type="file" name="upload-file" id="upload-file"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg">
                <button type="submit"
                    class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-full shadow-md hover:bg-blue-700">Kirim Atasan</button>
            </form>
        </div>
    </div>
@endsection