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
      <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">1</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
      </tr>
      <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">2</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td style="border:1px solid #ccc; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
      </tr>
      <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">3</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Rasio ekspor ikan dan hasil perikanan memenuhi syarat mutu dan diterima oleh negara tujuan ekspor lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td style="border:1px solid #ccc; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
      </tr>
      <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">4</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td style="border:1px solid #ccc; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
      </tr>
      <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">5</td>
        <td style="border:1px solid #ccc; padding:8px;">
          Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)
        </td>
        <td style="border:1px solid #ccc; text-align:center;">
          <img src="https://img.icons8.com/?size=100&id=85486&format=png" width="22" alt="data dukung">
        </td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
        <td style="border:1px solid #ccc;"></td>
      </tr>
    </tbody>
  </table>
</div>

<div style="display:flex; justify-content:flex-end; margin-top:20px; gap:2px;">
  <button style="padding:6px 12px; background:#f1f1f1; border:none; border-radius:4px 0 0 4px;">Previous</button>
  <span style="padding:6px 12px; background:#48CAE4; color:#fff; border:none;">1</span>
  <button style="padding:6px 12px; background:#f1f1f1; border:none; border-radius:0 4px 4px 0;">Next</button>
</div>
@endsection
