<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faq_chatbot';
    protected $primaryKey = 'id_faq';
    public $timestamps = false;

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'kategori',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }
}
