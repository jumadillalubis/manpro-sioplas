<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'indikator',
        'tw1',
        'tw2',
        'tw3',
        'tw4'
    ];
}
