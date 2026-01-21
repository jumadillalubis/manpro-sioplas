@extends('layouts.app')

@section('title', 'Detail Laporan - SIOPLAS')

@section('content')
<h1 style="font-size:22px; font-weight:600; margin-bottom:20px;">Detail Laporan</h1>

<div style="background-color:#A8D8E5; padding:15px; text-align:center; border-radius:6px; margin-bottom:25px;">
  <h2 style="margin:0; font-size:16px; text-transform:uppercase; font-weight:600; color:#2a3b27;">
    Perjanjian Kinerja Tahun 2025 Badan Mutu KKP Pekanbaru
  </h2>
</div>

<div style="overflow-x:auto;">
  <table style="width:100%; border-collapse:collapse; font-size:14px; border:1px solid #ccc;">
    <thead>
      <tr style="background:#f5f5f5;">
        <th style="border:1px solid #ccc; padding:8px; width:40px;">NO</th>
        <th style="border:1px solid #ccc; padding:8px;">INDIKATOR KINERJA KEGIATAN</th>
        <th colspan="4" style="border:1px solid #ccc; padding:8px; text-align:center;">Data Dukung</th>
      </tr>
      <tr style="background:#f5f5f5;">
        <th colspan="2" style="border:1px solid #ccc;"></th>
        <th style="border:1px solid #ccc; padding:8px; text-align:center;">TW1</th>
        <th style="border:1px solid #ccc; padding:8px; text-align:center;">TW2</th>
        <th style="border:1px solid #ccc; padding:8px; text-align:center;">TW3</th>
        <th style="border:1px solid #ccc; padding:8px; text-align:center;">TW4</th>
      </tr>
    </thead>
    <tbody>
      <tr data-indikator-id="1">
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">1</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td class="tw-cell" data-tw="TW1" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW2" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW3" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW4" style="border:1px solid #ccc; text-align:center;"></td>
      </tr>
      <tr data-indikator-id="2">
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">2</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td class="tw-cell" data-tw="TW1" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW2" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW3" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW4" style="border:1px solid #ccc; text-align:center;"></td>
      </tr>
      <tr data-indikator-id="3">
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">3</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Rasio ekspor ikan dan hasil perikanan memenuhi syarat mutu dan diterima oleh negara tujuan ekspor lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td class="tw-cell" data-tw="TW1" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW2" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW3" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW4" style="border:1px solid #ccc; text-align:center;"></td>
      </tr>
      <tr data-indikator-id="4">
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">4</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td class="tw-cell" data-tw="TW1" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW2" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW3" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW4" style="border:1px solid #ccc; text-align:center;"></td>
      </tr>
      <tr data-indikator-id="5">
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">5</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td class="tw-cell" data-tw="TW1" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW2" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW3" style="border:1px solid #ccc; text-align:center;"></td>
        <td class="tw-cell" data-tw="TW4" style="border:1px solid #ccc; text-align:center;"></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Pagination / Navigation (Static for now) -->
<div style="display:flex; justify-content:flex-end; margin-top:20px; gap:2px;">
  <button style="padding:6px 12px; background:#f1f1f1; border:none; border-radius:4px 0 0 4px;">Previous</button>
  <span style="padding:6px 12px; background:#48CAE4; color:#fff; border:none;">1</span>
  <button style="padding:6px 12px; background:#f1f1f1; border:none; border-radius:0 4px 4px 0;">Next</button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const laporanData = @json($laporan);

    if (Array.isArray(laporanData)) {
        // Group reports by Indikator and Triwulan
        const groupedReports = {};

        laporanData.forEach(laporan => {
            const key = `${laporan.indikator_id}-${laporan.triwulan}`;
            if (!groupedReports[key]) {
                groupedReports[key] = {
                    count: 0,
                    hasSummary: !!laporan.laporan_summary,
                    ids: []
                };
            }
            if (laporan.lampiran) {
                groupedReports[key].count++;
                groupedReports[key].ids.push(laporan.id);
            }
        });

        // Loop through cells and render buttons
        document.querySelectorAll('tr[data-indikator-id]').forEach(row => {
            const indikatorId = row.getAttribute('data-indikator-id');
            const periods = ['TW1', 'TW2', 'TW3', 'TW4'];

            periods.forEach(tw => {
                const cell = row.querySelector(`.tw-cell[data-tw='${tw}']`);
                if (cell) {
                    const key = `${indikatorId}-${tw}`;
                    const group = groupedReports[key];

                    if (group && group.count > 0) {
                        cell.innerHTML = `
                            <div style="display:flex; flex-direction:column; align-items:center; gap:5px;">
                                <div style="display:flex; gap:2px; flex-wrap:wrap; justify-content:center;">
                                    ${group.ids.map(id => `
                                        <a href="/atasan/laporan/${id}" title="Lihat Laporan">
                                            <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="18" alt="pdf">
                                        </a>
                                    `).join('')}
                                </div>
                                <button onclick="summarizeGroup(${indikatorId}, '${tw}')" 
                                        style="border:none; background:transparent; cursor:pointer; display:flex; flex-direction:column; align-items:center;" 
                                        title="Ringkas Semua Laporan ini dengan AI">
                                    <span style="font-size:16px;">✨</span>
                                    <span style="font-size:9px; color:purple; font-weight:600;">AI Summary</span>
                                </button>
                            </div>
                        `;
                    }
                }
            });
        });
    }
});

function closeAiModal() {
    document.getElementById('aiModal').style.display = 'none';
}

async function summarizeGroup(indikatorId, triwulan) {
    const modal = document.getElementById('aiModal');
    const resultText = document.getElementById('aiResultText');
    
    modal.style.display = 'flex';
    resultText.innerText = "Sedang menganalisis semua dokumen terkait dengan AI...";

    try {
        const formData = new FormData();
        formData.append('indikator_id', indikatorId);
        formData.append('triwulan', triwulan);
        formData.append('_token', '{{ csrf_token() }}');

        const res = await fetch('{{ route("ai.summarize") }}', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.status === 'success') {
            // Format hasil agar lebih rapi (ganti newline dengan <br>)
            const formattedSummary = data.summary.replace(/\n/g, '<br>');
            resultText.innerHTML = formattedSummary;
        } else {
            resultText.innerText = "Gagal: " + (data.message || 'Unknown error');
        }

    } catch (error) {
        console.error(error);
        // Show detailed error if available
        resultText.innerHTML = `<span style="color:red; font-weight:bold;">Error:</span> ${error.message}<br><br>Cek console browser untuk detail.`;
    }
}
</script>

<!-- MODAL AI -->
<div id="aiModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:90%; max-width:500px; position:relative;">
        <h3 style="margin-top:0; margin-bottom:10px; font-weight:600;">Ringkasan AI</h3>
        <p id="aiResultText" style="font-size:14px; text-align:justify; line-height:1.5; color:#333;">
            ...
        </p>
        <button onclick="closeAiModal()" style="margin-top:15px; padding:8px 16px; background:#ddd; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
            Tutup
        </button>
    </div>
</div>
@endsection
