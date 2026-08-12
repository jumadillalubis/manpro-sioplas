@extends('layouts.app')

@section('title', 'Detail Laporan - SIOPLAS')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
  <h1 style="font-size:22px; font-weight:600; margin:0;">Detail Laporan</h1>
  
  <div style="display:flex; align-items:center; gap:12px;">
    <!-- Rangkuman Tahunan Button -->
    @if(isset($yearlySummary))
        <button id="yearlySummaryBtn" onclick="showYearlySummaryModal()" 
                style="padding:8px 16px; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; border-radius:8px; font-weight:600; cursor:pointer; font-size:14px; display:flex; align-items:center; gap:6px; transition: all 0.2s;"
                onmouseover="this.style.background='#bae6fd'" onmouseout="this.style.background='#e0f2fe'">
            <span>📋</span> Lihat Rangkuman Tahunan
        </button>
    @else
        <button id="yearlySummaryBtn" onclick="generateYearlySummary()" 
                style="padding:8px 16px; background:#8a2be2; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:14px; display:flex; align-items:center; gap:6px; transition: all 0.2s;"
                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            <span>✨</span> Rangkum Tahunan (AI)
        </button>
    @endif

    <label for="filterTahun" style="font-size:14px; font-weight:500; color:#333;">Filter Tahun:</label>
    <select id="filterTahun" onchange="filterByYear(this.value)" style="padding:8px 16px; border:1px solid #ccc; border-radius:8px; background:#fff; cursor:pointer; font-size:14px; outline:none;">
      @for ($y = date('Y'); $y >= 2021; $y--)
          <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
      @endfor
    </select>
  </div>
</div>

<div style="background-color:#A8D8E5; padding:15px; text-align:center; border-radius:6px; margin-bottom:25px;">
  <h2 style="margin:0; font-size:16px; text-transform:uppercase; font-weight:600; color:#2a3b27;">
    Perjanjian Kinerja Tahun {{ $tahun }} Badan Mutu KKP Pekanbaru
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

// Global Yearly Summary State
let yearlySummaryText = @json($yearlySummary ? $yearlySummary->summary_text : null);

function showYearlySummaryModal() {
    const modal = document.getElementById('yearlySummaryModal');
    const content = document.getElementById('yearlySummaryContent');
    if (yearlySummaryText) {
        content.innerHTML = marked.parse(yearlySummaryText);
    } else {
        content.innerHTML = '<p style="text-align:center; color:#64748b;">Rangkuman tidak tersedia.</p>';
    }
    modal.style.display = 'flex';
}

function closeYearlySummaryModal() {
    document.getElementById('yearlySummaryModal').style.display = 'none';
}

async function generateYearlySummary() {
    const modal = document.getElementById('yearlySummaryModal');
    const content = document.getElementById('yearlySummaryContent');
    const btn = document.getElementById('yearlySummaryBtn');
    const regenBtn = document.getElementById('regenerateYearlyBtn');
    
    // Set loading state
    modal.style.display = 'flex';
    if (regenBtn) regenBtn.style.display = 'none';
    
    content.innerHTML = `
        <div style="text-align:center; padding:40px 20px;">
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid #8a2be2; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px auto;"></div>
            <p style="font-weight: 600; color: #475569; margin-bottom: 5px; font-size:15px;">Sedang Menyusun Rangkuman Kinerja Tahunan...</p>
            <p style="font-size: 13px; color: #94a3b8; max-width:280px; margin:0 auto;">Mengompilasi data ringkasan triwulan menggunakan Gemini AI.</p>
        </div>
    `;

    try {
        const formData = new FormData();
        formData.append('tahun', '{{ $tahun }}');
        formData.append('_token', '{{ csrf_token() }}');

        const res = await fetch('{{ route("ai.summarize_yearly") }}', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.status === 'success') {
            yearlySummaryText = data.summary;
            content.innerHTML = marked.parse(yearlySummaryText);
            
            // Update the main button so it shows "Lihat Rangkuman"
            if (btn) {
                btn.innerHTML = `<span>📋</span> Lihat Rangkuman Tahunan`;
                btn.onclick = showYearlySummaryModal;
                btn.style.background = '#e0f2fe';
                btn.style.color = '#0369a1';
                btn.style.border = '1px solid #bae6fd';
                btn.setAttribute('onmouseover', "this.style.background='#bae6fd'");
                btn.setAttribute('onmouseout', "this.style.background='#e0f2fe'");
            }
        } else {
            content.innerHTML = `
                <div style="text-align:center; padding:30px 20px; color: #ef4444;">
                    <span style="font-size: 40px; display:block; margin-bottom:10px;">⚠️</span>
                    <p style="font-weight: 600; font-size:15px;">Gagal Membuat Rangkuman</p>
                    <p style="font-size: 13px; margin-top:5px; max-width: 320px; margin-left: auto; margin-right: auto; line-height:1.4;">
                        ${data.message || 'Terjadi kesalahan pada server AI.'}
                    </p>
                </div>
            `;
        }
    } catch (error) {
        console.error(error);
        content.innerHTML = `
            <div style="text-align:center; padding:30px 20px; color: #ef4444;">
                <span style="font-size: 40px; display:block; margin-bottom:10px;">❌</span>
                <p style="font-weight: 600; font-size:15px;">Kesalahan Sistem</p>
                <p style="font-size: 13px; margin-top:5px;">
                    Error: ${error.message}
                </p>
            </div>
        `;
    } finally {
        if (regenBtn) regenBtn.style.display = 'flex';
    }
}

function filterByYear(year) {
    window.location.href = '?tahun=' + year;
}
</script>

<!-- Load Marked.js for Premium Markdown Rendering -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Styles for Premium Markdown and Spin Animation -->
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .yearly-summary-markdown h1 { font-size: 1.6rem; font-weight: 700; margin-top: 1.2rem; margin-bottom: 0.8rem; color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.4rem; }
    .yearly-summary-markdown h2 { font-size: 1.3rem; font-weight: 600; margin-top: 1.1rem; margin-bottom: 0.6rem; color: #1e293b; border-left: 4px solid #8a2be2; padding-left: 8px; }
    .yearly-summary-markdown h3 { font-size: 1.1rem; font-weight: 600; margin-top: 0.9rem; margin-bottom: 0.4rem; color: #334155; }
    .yearly-summary-markdown p { font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; color: #334155; text-align: justify; }
    .yearly-summary-markdown ul { margin-left: 1.5rem; margin-bottom: 1rem; list-style-type: disc; }
    .yearly-summary-markdown li { font-size: 0.95rem; line-height: 1.5; margin-bottom: 0.4rem; color: #475569; }
    .yearly-summary-markdown hr { margin: 1.2rem 0; border: 0; border-top: 1px solid #e2e8f0; }
    .yearly-summary-markdown strong { color: #0f172a; font-weight: 600; }
</style>

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

<!-- MODAL RANGKUMAN TAHUNAN -->
<div id="yearlySummaryModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding: 20px;">
    <div style="background:white; padding:25px; border-radius:16px; width:100%; max-width:800px; position:relative; display:flex; flex-direction:column; max-height:85vh; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:24px;">📋</span>
                <h3 style="margin:0; font-size:18px; font-weight:700; color:#0f172a;">Laporan Rangkuman Kinerja Tahunan</h3>
                <span style="background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:12px; font-size:12px; font-weight:600;">Tahun {{ $tahun }}</span>
            </div>
            <button onclick="closeYearlySummaryModal()" style="background:none; border:none; font-size:24px; color:#94a3b8; cursor:pointer; line-height:1; padding:0; hover:color:#475569;">&times;</button>
        </div>
        
        <!-- Content -->
        <div id="yearlySummaryContent" class="yearly-summary-markdown" style="overflow-y:auto; flex:1; padding:15px; background:#f8fafc; border-radius:12px; margin-bottom:15px; border:1px solid #f1f5f9;">
            <!-- Rendered markdown is inserted here -->
        </div>

        <!-- Footer -->
        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:10px; border-top:1px solid #e2e8f0;">
            <button id="regenerateYearlyBtn" onclick="generateYearlySummary()" style="padding:10px 20px; background:#f3e8ff; color:#6b21a8; border:1px solid #e9d5ff; border-radius:8px; font-weight:600; cursor:pointer; font-size:14px; transition: all 0.2s; display:flex; align-items:center; gap:6px;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#f3e8ff'">
                <span>🔄</span> Rangkum Ulang (AI)
            </button>
            <button onclick="closeYearlySummaryModal()" style="padding:10px 20px; background:#f1f5f9; border:1px solid #e2e8f0; color:#475569; border-radius:8px; font-weight:600; cursor:pointer; font-size:14px; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection
