<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'isi',
        'gambar',
        'is_published'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $berita->slug = self::generateUniqueSlug($berita->judul);
        });
    }

    public static function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $counter = 2;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(ContentComment::class, 'commentable');
    }
}
