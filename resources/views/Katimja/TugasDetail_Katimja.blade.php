@extends('layouts.app')

@section('title', 'Detail Tugas Katimja')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Detail Tugas: {{ $id }}</h4>

        <!-- Tugas Detail -->
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Penerima Tugas</h5>
            <p class="text-gray-600">Budi Santoso</p>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Deadline</h5>
            <p class="text-gray-600">22 Jul 2024</p>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700">Deskripsi</h5>
            <p class="text-gray-600">Evaluasi Kinerja Tim untuk meningkatkan hasil kerja dan kolaborasi antar tim.</p>
        </div>

        <!-- Form untuk Upload File -->
        <div class="mb-6">
            <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="upload-file" class="text-sm font-semibold text-gray-700">Upload File Tugas</label>
                <input type="file" name="upload-file" id="upload-file" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg">
                <button type="submit" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-full shadow-md hover:bg-blue-700">Kirim</button>
            </form>
        </div>
    </div>
@endsection
