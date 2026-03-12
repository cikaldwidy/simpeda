<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogChatbot extends Model
{
    use HasFactory;

    protected $table = 'log_chatbot';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'sesi_id',
        'pertanyaan_user',
        'jawaban_bot',
        'waktu_interaksi',
        'id_permohonan',
        'id_faq',
    ];

    protected function casts(): array
    {
        return [
            'waktu_interaksi' => 'datetime',
        ];
    }
}
