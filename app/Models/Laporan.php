<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'judul',
        'isi',
        'status',
        'catatan',
        'indikator_id',
        'triwulan',
        'lampiran',
        'devisi',
        'tanggal',
        'laporan_summary'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
