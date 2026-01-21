<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas'; // nama tabel di database
    protected $fillable = ['penerima', 'judul', 'deskripsi', 'file_path', 'tenggat', 'pembuat'];
}
