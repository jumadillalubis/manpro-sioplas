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
                                <button onclick="summarizeGroup(${indikatorId}, '${tw}', ${group.ids[0]})" 
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
    // Reset to default state just in case
    const contentDiv = document.getElementById('aiContent');
    if (contentDiv) {
        contentDiv.innerHTML = '<p id="aiResultText" style="font-size:14px; text-align:justify; line-height:1.5; color:#333;">...</p>';
    }
}

async function summarizeGroup(indikatorId, triwulan, redirectId) {
    const modal = document.getElementById('aiModal');
    const contentDiv = document.getElementById('aiContent');
    const footerDiv = document.getElementById('aiFooter');
    const title = document.getElementById('aiTitle');
    
    modal.style.display = 'flex';
    
    // Set Loading State
    title.innerText = "Ringkasan AI";
    contentDiv.innerHTML = '<div style="text-align:center; padding:20px;">sedang menganalisis dokumen...<br><span style="font-size:24px;">⏳</span></div>';
    footerDiv.innerHTML = ''; 

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
            title.innerText = "Selesai!";
            contentDiv.innerHTML = `
                <div style="text-align:center; padding:10px;">
                    <span style="font-size:50px; display:block; margin-bottom:10px;">✅</span>
                    <p style="font-weight:600; font-size:16px;">AI sudah meringkas laporan tersebut!</p>
                    <p style="font-size:13px; color:#555; margin-top:5px;">
                        Silakan lihat detail laporan untuk melihat dokumen asli dan hasil ringkasan AI.
                    </p>
                </div>
            `;
            
            // Show "Lihat Detail" button
            footerDiv.innerHTML = `
                <button onclick="closeAiModal()" style="margin-right:10px; padding:8px 16px; background:#ddd; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
                    Tutup
                </button>
                <a href="/atasan/laporan/${redirectId}?tab=summary" 
                   style="padding:8px 16px; background:#48CAE4; color:white; border:none; border-radius:4px; font-weight:600; text-decoration:none; display:inline-block;">
                   Lihat Detail
                </a>
            `;

        } else {
            contentDiv.innerHTML = `<p style="color:red; text-align:center;">Gagal: ${data.message || 'Unknown error'}</p>`;
            footerDiv.innerHTML = `
                <button onclick="closeAiModal()" style="padding:8px 16px; background:#ddd; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
                    Tutup
                </button>
            `;
        }

    } catch (error) {
        console.error(error);
        contentDiv.innerHTML = `<p style="color:red; font-weight:bold; text-align:center;">Error: ${error.message}</p>`;
        footerDiv.innerHTML = `
            <button onclick="closeAiModal()" style="padding:8px 16px; background:#ddd; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
                Tutup
            </button>
        `;
    }
}
</script>

<!-- MODAL AI -->
<div id="aiModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:90%; max-width:400px; position:relative; display:flex; flex-direction:column; max-height:80vh;">
        <h3 id="aiTitle" style="margin-top:0; margin-bottom:10px; font-weight:600; text-align:center;">Ringkasan AI</h3>
        
        <div id="aiContent" style="overflow-y:auto; flex:1; margin-bottom:15px;">
            <p id="aiResultText" style="font-size:14px; text-align:justify; line-height:1.5; color:#333;">...</p>
        </div>

        <div id="aiFooter" style="display:flex; justify-content:center; padding-top:10px; border-top:1px solid #eee;">
            <button onclick="closeAiModal()" style="padding:8px 16px; background:#ddd; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection
