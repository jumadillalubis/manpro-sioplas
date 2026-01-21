@extends('layouts.app')

@section('content')

    @php
        $tugas = $data_tugas;
    @endphp

    <div class="p-6">

        {{-- HEADER --}}
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Detail Tugas Pegawai</h1>

        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">

            {{-- METADATA --}}
            <p class="text-sm text-gray-600">Batas Waktu: <strong>{{ $tugas['batas_waktu'] }}</strong></p>
            <p class="text-sm text-gray-600 mb-4">Pegawai: <strong>{{ $tugas['pegawai'] }}</strong></p>

            {{-- TITLE BOX --}}
            <div class="p-4 bg-gray-700 text-white rounded-t-lg mb-2">
                <h2 class="text-xl font-bold uppercase">{{ $tugas['judul'] }}</h2>
            </div>

            {{-- DESKRIPSI --}}
            <div class="border border-gray-200 p-4 rounded-b-lg mb-6">
                <p class="text-gray-700 leading-relaxed">
                    {{ $tugas['deskripsi'] }}
                </p>
            </div>

            <!-- STATUS & FILE -->
             <div class="mb-6">
                <span class="inline-block px-3 py-1 rounded-full text-white {{ ($tugas['status'] ?? '') === 'Selesai' ? 'bg-green-500' : 'bg-yellow-500' }}">
                    Status: {{ $tugas['status'] ?? 'Pending' }}
                </span>
             </div>

             @if(isset($tugas['file_selesai']) && !empty($tugas['file_selesai']))
             <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-700 font-semibold">Tugas Selesai! File Anda:</p>
                <a href="{{ asset('storage/tugas_selesai/' . $tugas['file_selesai']) }}" target="_blank" class="text-blue-600 underline text-sm mt-1 block">
                    {{ $tugas['file_selesai'] }}
                </a>
             </div>
             @endif

            {{-- FORM UPLOAD --}}
            <form action="{{ route('tugas.upload', $tugas['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-4 border border-gray-200 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Upload File</h3>

                    <label for="task_file_upload"
                        class="flex items-center justify-between p-3 border border-gray-300 rounded-lg cursor-pointer">
                        <span id="file-chosen-text" class="text-gray-500">Upload File</span>

                        {{-- Icon --}}
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </label>

                    <input type="file" name="file" id="task_file_upload" class="hidden">
                </div>

                {{-- SUBMIT BUTTON --}}
                <button class="w-full py-3 text-lg font-bold text-white bg-blue-500 hover:bg-blue-600 rounded-lg shadow-lg">
                    Submit Tugas
                </button>

            </form>

        </div>

    </div>

    <script>
        document.getElementById('task_file_upload').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : "Upload File";
            document.getElementById('file-chosen-text').textContent = fileName;
        });
    </script>

@endsection