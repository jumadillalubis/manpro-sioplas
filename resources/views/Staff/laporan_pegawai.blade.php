@extends('layouts.app')

@section('content')
    <div class="p-6 bg-white">

        {{-- HEADER BIRU --}}
        <div class="w-full bg-cyan-400 px-6 py-4 mb-6">
            <h1 class="text-white text-lg font-semibold uppercase tracking-wide text-center">
                PERJANJIAN KINERJA TAHUN 2025 BADAN MUTU KKP PEKANBARU
            </h1>
        </div>

        {{-- JUDUL --}}
        <h2 class="text-sm font-semibold text-gray-800 mb-3">Detail Laporan</h2>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full border border-black text-sm">

                {{-- HEADER --}}
                <thead class="bg-gray-100 border border-black">
                    <tr>
                        <th rowspan="2" class="border border-black px-3 py-2 text-center w-12">NO</th>
                        <th rowspan="2" class="border border-black px-4 py-2 text-left">
                            INDIKATOR KINERJA KEGIATAN
                        </th>
                        <th colspan="4" class="border border-black px-4 py-2 text-center">
                            Data Dukung
                        </th>
                    </tr>
                    <tr>
                        <th class="border border-black px-2 py-1 w-20">TW1</th>
                        <th class="border border-black px-2 py-1 w-20">TW2</th>
                        <th class="border border-black px-2 py-1 w-20">TW3</th>
                        <th class="border border-black px-2 py-1 w-20">TW4</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody>
                    @php
                        $indikator = [
                            'Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)',
                            'Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)',
                            'Rasio ekspor ikan dan hasil perikanan memenuhi syarat mutu dan diterima oleh negara tujuan ekspor lingkup UPT Stasiun KIPM Pekanbaru (%)',
                            'Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)',
                            'Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)',
                        ];
                    @endphp

                    @foreach ($indikator as $i => $row)
                        <tr>
                            <td class="border border-black px-2 py-3 text-center">{{ $i + 1 }}</td>
                            <td class="border border-black px-3 py-3">{{ $row }}</td>

                            @for ($tw = 1; $tw <= 4; $tw++)
                                <td class="border border-black text-center">
                                    <button class="open-upload-modal" data-indikator-id="{{ $i + 1 }}" data-tw="TW{{ $tw }}">
                                        ⬆️
                                    </button>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL UPLOAD --}}
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white w-96 p-6 border border-black">
            <h3 class="font-semibold mb-4">Upload Data Dukung</h3>

            <form action="{{ route('staff.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="indikator_id" id="indikatorInput">
                <input type="hidden" name="tw" id="twInput">

                <input type="file" name="file" required class="mb-4 w-full border">

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal" class="px-4 py-1 border">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-1 bg-cyan-400 text-white">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const modal = document.getElementById('uploadModal');
        const indikatorInput = document.getElementById('indikatorInput');
        const twInput = document.getElementById('twInput');

        document.querySelectorAll('.open-upload-modal').forEach(btn => {
            btn.onclick = () => {
                indikatorInput.value = btn.dataset.indikatorId;
                twInput.value = btn.dataset.tw;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });

        document.getElementById('closeModal').onclick = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection