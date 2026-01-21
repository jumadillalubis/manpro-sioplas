@extends('layouts.app')

@section('title', 'Buat Tugas Baru')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Buat Tugas Baru</h4>

        <!-- Form untuk Buat Tugas Baru -->
        <form action="{{ route('katimja.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="penerima" class="text-sm font-semibold text-gray-700">Penerima Tugas</label>
                <select name="penerima" id="penerima" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    <option value="">Pilih Karyawan</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->nama }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="judul" class="text-sm font-semibold text-gray-700">Judul Tugas</label>
                <input type="text" name="judul" id="judul"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
                <textarea name="deskripsi" id="deskripsi" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg"
                    required></textarea>
            </div>
            <div class="mb-4">
                <label for="tenggat" class="text-sm font-semibold text-gray-700">Tanggal Deadline</label>
                <input type="date" name="tenggat" id="tenggat"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="file" class="text-sm font-semibold text-gray-700">Lampiran (Opsional)</label>
                <input type="file" name="file" id="file" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg">
            </div>
            <button type="submit"
                class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-full shadow-md hover:bg-green-700">Kirim
                Tugas</button>
        </form>
    </div>
@endsection