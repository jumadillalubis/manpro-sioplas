@extends('layouts.app')

@section('content')
    <div class="p-6 bg-white">

        {{-- FILTER TAHUN --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Laporan Perjanjian Kinerja</h2>
            
            <div class="flex items-center gap-3">
                <label for="filterTahun" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                <select id="filterTahun" onchange="filterByYear(this.value)" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white cursor-pointer">
                    @for ($y = date('Y'); $y >= 2021; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- HEADER BIRU --}}
        <div class="w-full bg-cyan-400 px-6 py-4 mb-6 rounded-lg">
            <h1 class="text-white text-lg font-semibold uppercase tracking-wide text-center">
                PERJANJIAN KINERJA TAHUN {{ $tahun }} BADAN MUTU KKP PEKANBARU
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

                        $laporansIndexed = [];
                        foreach ($laporan as $lap) {
                            $laporansIndexed[$lap->indikator_id][$lap->triwulan] = $lap;
                        }
                    @endphp

                    @foreach ($indikator as $i => $row)
                        <tr>
                            <td class="border border-black px-2 py-3 text-center">{{ $i + 1 }}</td>
                            <td class="border border-black px-3 py-3">{{ $row }}</td>

                            @for ($tw = 1; $tw <= 4; $tw++)
                                @php
                                    $twKey = 'TW' . $tw;
                                    $existing = $laporansIndexed[$i + 1][$twKey] ?? null;
                                @endphp
                                <td class="border border-black text-center p-2">
                                    <div class="flex flex-col items-center gap-1 justify-center">
                                         @if ($existing && !empty($existing->lampiran))
                                             @php
                                                 $statusColor = 'text-blue-500';
                                                 if ($existing->status == 'disetujui' || $existing->status == 'Terverifikasi') {
                                                     $statusColor = 'text-green-600';
                                                 } elseif ($existing->status == 'ditolak') {
                                                     $statusColor = 'text-red-600';
                                                 }
                                             @endphp
                                             <span class="text-xs font-semibold {{ $statusColor }}" style="font-size: 10px;">
                                                 @if($existing->status == 'disetujui' || $existing->status == 'Terverifikasi')
                                                     ✅ Disetujui
                                                 @elseif($existing->status == 'ditolak')
                                                     ❌ Ditolak
                                                 @else
                                                     ⏳ Pending
                                                 @endif
                                             </span>
                                             
                                             <a href="{{ asset('storage/' . $existing->lampiran) }}" target="_blank" class="text-xs text-blue-600 hover:underline" style="font-size: 10px;" title="Unduh Laporan">
                                                 📂 Lihat File
                                             </a>
                                             
                                             @if ($existing->status !== 'disetujui' && $existing->status !== 'Terverifikasi')
                                                 <button class="open-upload-modal text-xs text-amber-600 hover:underline mt-1" data-indikator-id="{{ $i + 1 }}" data-tw="TW{{ $tw }}" style="font-size: 9px;">
                                                     ✏️ Ubah File
                                                 </button>
                                             @endif
                                         @else
                                             <button class="open-upload-modal px-2 py-1 bg-gray-100 hover:bg-gray-200 border rounded" data-indikator-id="{{ $i + 1 }}" data-tw="TW{{ $tw }}" title="Upload Laporan">
                                                 ⬆️
                                             </button>
                                         @endif
                                    </div>
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
                <input type="hidden" name="tahun" value="{{ $tahun }}">

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

        // Filter by Year Function
        function filterByYear(year) {
            window.location.href = '?tahun=' + year;
        }
    </script>
@endsection