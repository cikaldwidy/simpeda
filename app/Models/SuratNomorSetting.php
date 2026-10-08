<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratNomorSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat',
        'kode_klasifikasi',
        'kode_wilayah',
    ];

    public static function defaultsFor(string $jenisSurat): array
    {
        return [
            'jenis_surat' => $jenisSurat,
            'kode_klasifikasi' => SuratPengajuan::kodeJenis($jenisSurat),
            'kode_wilayah' => SuratPengajuan::KODE_DESA,
        ];
    }
}
