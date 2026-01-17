<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = [
            [
                'id' => 1,
                'judul' => 'Evaluasi Kinerja Tim',
                'batas_waktu' => '2025-05-15',
                'kepada' => 'Budi Susanto'
            ],
            [
                'id' => 2,
                'judul' => 'Tinjau SOP Baru',
                'batas_waktu' => '2024-07-22',
                'kepada' => 'Ahmad Dahan'
            ],
        ];

        return view('Atasan.tugas', compact('tugas'));
    }

    public function show($id)
    {
        $tugas = [
            'id' => $id,
            'judul' => 'Evaluasi Kinerja Tim',
            'pegawai' => 'Budi Susanto',
            'tanggal' => '15-05-2025',
            'deskripsi' => 'Segera susun laporan komprehensif mengenai hasil evaluasi kinerja tim kita. Laporan ini diharapkan tidak hanya menyajikan data dan metrik, tetapi juga memberikan wawasan mendalam mengenai efektivitas tim, faktor-faktor penunjang dan penghambat kinerja, serta rekomendasi yang dapat ditindaklanjuti untuk pengembangan dan optimalisasi tim ke depan. Mohon kirimkan laporannya.'
        ];

        return view('Atasan.tugas_detail', compact('tugas'));
    }

    public function approve($id)
    {
        return redirect()
            ->route('tugas.show', $id)
            ->with('approved', true);
    }

    public function lihat($id)
{
    $tugas = [
        'id' => $id,
        'judul' => 'Evaluasi Kinerja Tim',
        'pegawai' => 'Budi Susanto',
        'tanggal' => '15-05-2025',
        'deskripsi' => 'Laporan ini merinci kegiatan dan pencapaian selama bulan Januari 2025...',
        'dokumen' => [
            ['nama' => 'Document 1.pdf', 'icon' => 'pdf'],
            ['nama' => 'Image 1.jpg', 'icon' => 'image'],
        ],
        'komentar' => [
            [
                'nama' => 'Atasan',
                'waktu' => '2024-01-16 10:00 AM',
                'isi' => 'Kerja bagus, Budi! Lanjutkan terus ya.'
            ]
        ]
    ];

    return view('Atasan.tugas_lihat', compact('tugas'));
}

public function respon(Request $request, $id)
{
    // nanti bisa simpan ke database
    return redirect()
        ->route('tugas.lihat', $id)
        ->with('success', 'Respon berhasil dikirim');
}

}
