<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanAnggaran extends Model
{
    protected $fillable = [
        'judul',
        'tahun',
        'jenis_laporan',
        'template',
        'keterangan',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(AnggaranItem::class, 'laporan_anggaran_id')->orderBy('urutan')->orderBy('id');
    }

    public function getTotalByJenis(string $jenis): float
    {
        return (float) $this->items->where('jenis', $jenis)->sum('jumlah');
    }
}
