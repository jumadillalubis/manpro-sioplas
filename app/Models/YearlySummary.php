<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlySummary extends Model
{
    use HasFactory;

    protected $table = 'yearly_summaries';

    protected $fillable = [
        'tahun',
        'summary_text'
    ];
}
