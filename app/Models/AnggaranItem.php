<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranItem extends Model
{
    protected $fillable = [
        'jenis',
        'kategori',
        'uraian',
        'jumlah',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'urutan' => 'integer',
        ];
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(LaporanAnggaran::class, 'laporan_anggaran_id');
    }
}
