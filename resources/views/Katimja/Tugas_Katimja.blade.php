@extends('layouts.app')

@section('title', 'Tugas Katimja')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Tugas Katimja</h4>
        
        <!-- Tabel Daftar Tugas -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-sky-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">ID Tugas</th>
                        <th class="px-4 py-2 text-left">Tugas</th>
                        <th class="px-4 py-2 text-left">Deadline</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows, you can replace it with dynamic data -->
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-3">T00127</td>
                        <td class="px-4 py-3">Revisi Laporan Mutu</td>
                        <td class="px-4 py-3">20 Jul 2024</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-green-500 text-white px-3 py-1 rounded-full">Completed</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('tugas.show', ['id' => 1]) }}" class="text-blue-500 hover:text-blue-700">Lihat Detail</a>
                        </td>
                    </tr>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-3">T00128</td>
                        <td class="px-4 py-3">Tinjau SOP Baru</td>
                        <td class="px-4 py-3">22 Jul 2024</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-yellow-500 text-white px-3 py-1 rounded-full">Pending</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('tugas.show', ['id' => 2]) }}" class="text-blue-500 hover:text-blue-700">Lihat Detail</a>
                        </td>
                    </tr>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-3">T00129</td>
                        <td class="px-4 py-3">Evaluasi Kinerja Tim</td>
                        <td class="px-4 py-3">25 Jul 2024</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-red-500 text-white px-3 py-1 rounded-full">Rejected</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('tugas.show', ['id' => 3]) }}" class="text-blue-500 hover:text-blue-700">Lihat Detail</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tombol untuk Buat Tugas Baru -->
        <div class="mt-6">
            <a href="{{ route('tugas.create') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-full shadow-md hover:bg-green-700">Buat Tugas Baru</a>
        </div>
    </div>
@endsection
