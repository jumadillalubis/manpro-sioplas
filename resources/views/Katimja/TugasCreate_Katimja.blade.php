@extends('layouts.app')

@section('title', 'Buat Tugas Baru')

@section('content')
    <div class="card bg-white shadow-lg rounded-lg p-6">
        <h4 class="text-2xl font-semibold text-gray-800 mb-6">Buat Tugas Baru</h4>

        <!-- Form untuk Buat Tugas Baru -->
        <form action="{{ route('katimja.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Task Type Toggle --}}
            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-700">Jenis Tugas</label>
                <div class="mt-2 flex items-center space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="tipe_tugas" value="personal" class="form-radio text-indigo-600" checked onchange="toggleRecipient('personal')">
                        <span class="ml-2 text-gray-700">Personal (Perorangan)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="tipe_tugas" value="tim" class="form-radio text-indigo-600" onchange="toggleRecipient('tim')">
                        <span class="ml-2 text-gray-700">Tim Kerja (Satu Divisi)</span>
                    </label>
                </div>
            </div>

            {{-- Recipient Selection (Only for Personal) --}}
            <div class="mb-4" id="div-penerima">
                <label for="penerima" class="text-sm font-semibold text-gray-700">Pilih Staff</label>
                <select name="penerima" id="penerima" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200">
                    <option value="">-- Pilih Staff --</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->nama }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4" id="div-tim" style="display: none;">
                <label class="text-sm font-semibold text-gray-700">Penerima</label>
                <div class="mt-2 p-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600">
                    Akan ditugaskan kepada seluruh anggota <strong>{{ session('user_divisi') }}</strong>
                </div>
                {{-- Hidden input to send division name as recipient if needed, handled in Controller --}}
            </div>

            <div class="mb-4">
                <label for="judul" class="text-sm font-semibold text-gray-700">Judul Tugas</label>
                <input type="text" name="judul" id="judul"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200" required>
            </div>
            
            <div class="mb-4">
                <label for="deskripsi" class="text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="block w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                    required></textarea>
            </div>
            
            <div class="mb-4">
                <label for="tenggat" class="text-sm font-semibold text-gray-700">Tanggal Deadline</label>
                <input type="date" name="tenggat" id="tenggat"
                    class="block w-full mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200" required>
            </div>
            
            <div class="mb-6">
                <label for="file" class="text-sm font-semibold text-gray-700">Lampiran (Opsional)</label>
                <div class="mt-2 relative border border-gray-300 rounded-lg shadow-sm bg-gray-50">
                    <input type="file" name="file" id="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('tugas.katimja') }}" class="mr-3 px-6 py-2 border border-gray-300 rounded-full text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-full shadow-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Buat Tugas
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleRecipient(type) {
            const divPenerima = document.getElementById('div-penerima');
            const divTim = document.getElementById('div-tim');
            const selectPenerima = document.getElementById('penerima');

            if (type === 'personal') {
                divPenerima.style.display = 'block';
                divTim.style.display = 'none';
                selectPenerima.required = true;
            } else {
                divPenerima.style.display = 'none';
                divTim.style.display = 'block';
                selectPenerima.required = false;
                selectPenerima.value = ""; // Reset selection
            }
        }
    </script>
@endsection