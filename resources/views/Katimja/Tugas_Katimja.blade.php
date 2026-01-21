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
                        <th class="px-4 py-2 text-left">Tanggal Buat</th>
                        <th class="px-4 py-2 text-left">Deadline</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugas as $item)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-3">{{ $item['id'] }}</td>
                        <td class="px-4 py-3">{{ $item['judul'] }}</td>
                        <td class="px-4 py-3">{{ $item['tanggal_buat'] }}</td>
                        <td class="px-4 py-3 text-red-500">{{ $item['deadline'] }}</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-yellow-500 text-white px-3 py-1 rounded-full">{{ $item['status'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('katimja.tugas.show', $item['id']) }}" class="text-blue-500 hover:text-blue-700">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada tugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tombol untuk Buat Tugas Baru -->
        <div class="mt-6">
            <a href="{{ route('katimja.tugas.create') }}"
                class="inline-block px-6 py-2 bg-green-600 text-white rounded-full shadow-md hover:bg-green-700">Buat Tugas
                Baru</a>
        </div>
    </div>
@endsection