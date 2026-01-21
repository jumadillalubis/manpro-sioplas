@extends('layouts.app')

@section('content')
    <div class="p-6 bg-white">

        {{-- FILTER TAHUN --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Laporan Perjanjian Kinerja</h2>
            
            <div class="flex items-center gap-3">
                <label for="filterTahun" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                <select id="filterTahun" onchange="filterByYear(this.value)" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white cursor-pointer">
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>
            </div>
        </div>

        {{-- HEADER BIRU --}}
        <div class="w-full bg-cyan-400 px-6 py-4 mb-6 rounded-lg">
            <h1 id="headerTahun" class="text-white text-lg font-semibold uppercase tracking-wide text-center">
                PERJANJIAN KINERJA TAHUN 2025 BADAN MUTU KKP PEKANBARU
            </h1>
        </div>


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

            <form action="{{ route('katimja.laporan.upload') }}" method="POST" enctype="multipart/form-data">
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

        // Filter by Year Function
        function filterByYear(year) {
            // Update header text
            document.getElementById('headerTahun').textContent = 
                'PERJANJIAN KINERJA TAHUN ' + year + ' BADAN MUTU KKP PEKANBARU';
            
            // Show notification
            showFilterNotification('Filter diterapkan untuk tahun ' + year);
            
            // Here you can add AJAX call to fetch data based on year
            // For now it just updates the header
            console.log('Filtering data for year:', year);
        }

        // Show notification function
        function showFilterNotification(message) {
            // Create notification element
            const notif = document.createElement('div');
            notif.className = 'fixed top-4 right-4 bg-cyan-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
            notif.innerHTML = '<span>✅</span> ' + message;
            document.body.appendChild(notif);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transition = 'opacity 0.3s';
                setTimeout(() => notif.remove(), 300);
            }, 2000);
        }
    </script>
@endsection