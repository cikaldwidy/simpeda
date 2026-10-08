<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'alamat_lengkap',
        'jenis_kelamin',
        'isi_aspirasi',
        'gambar',
    ];
}
