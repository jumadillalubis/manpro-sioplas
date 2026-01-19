@extends('layouts.app')

@section('title', 'Buat Tugas Baru')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Buat Tugas Baru</h4>

        <!-- Form untuk Buat Tugas Baru -->
        <form action="{{ route('tugas.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="recipient" class="text-sm font-semibold text-gray-700">Penerima Tugas</label>
                <select name="recipient" id="recipient" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    <option value="">Pilih Karyawan</option>
                    <!-- Populate the list of employees dynamically -->
                    <option value="budi">Budi Santoso</option>
                    <option value="agus">Agus Subrata</option>
                    <option value="rini">Rini Rina</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="task-title" class="text-sm font-semibold text-gray-700">Judul Tugas</label>
                <input type="text" name="task-title" id="task-title"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="task-desc" class="text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
                <textarea name="task-desc" id="task-desc" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg"
                    required></textarea>
            </div>
            <div class="mb-4">
                <label for="deadline" class="text-sm font-semibold text-gray-700">Tanggal Deadline</label>
                <input type="date" name="deadline" id="deadline"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit"
                class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-full shadow-md hover:bg-green-700">Kirim
                Tugas</button>
        </form>
    </div>
@endsection