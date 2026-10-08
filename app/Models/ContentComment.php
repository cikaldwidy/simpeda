<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentComment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
