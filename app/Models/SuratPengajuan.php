<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengajuan extends Model
{
    use HasFactory;

    public const JENIS_DOMISILI = 'domisili';
    public const JENIS_TIDAK_MAMPU = 'tidak_mampu';
    public const JENIS_KEMATIAN = 'kematian';
    public const JENIS_KELAHIRAN = 'kelahiran';

    protected $fillable = [
        'user_id',
        'jenis_surat',
        'nomor_surat',
        'nomor_urut',
        'tahun',
        'perihal',
        'keperluan',
        'catatan',
        'status_perkawinan',
        'pekerjaan',
        'tanggal_surat',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function jenisOptions(): array
    {
        return [
            self::JENIS_DOMISILI => 'Surat Keterangan Domisili',
            self::JENIS_TIDAK_MAMPU => 'Surat Keterangan Tidak Mampu',
            self::JENIS_KEMATIAN => 'Surat Keterangan Kematian',
            self::JENIS_KELAHIRAN => 'Surat Keterangan Kelahiran',
        ];
    }

    public static function kodeJenis(string $jenis): string
    {
        return match ($jenis) {
            self::JENIS_DOMISILI => 'SKD',
            self::JENIS_TIDAK_MAMPU => 'SKTM',
            self::JENIS_KEMATIAN => 'SKKM',
            self::JENIS_KELAHIRAN => 'SKK',
            default => 'SK',
        };
    }
}
