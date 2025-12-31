@extends('layouts.app')

@section('content')

<div class="p-6">

    {{-- HEADER HALAMAN --}}
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">List Tugas Pegawai</h1>

    {{-- CARD UTAMA --}}
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Tugas</h2>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse ($tugas as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item['id'] }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['judul'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item['type'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item['batas_waktu'] }}</td>

                            <td class="px-6 py-4">
                                @if ($item['status'] === 'Completed')
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Completed</span>
                                @elseif ($item['status'] === 'Rejected')
                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Rejected</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('tugas.detail', $item['id']) }}" class="text-blue-600 hover:underline">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada tugas tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
