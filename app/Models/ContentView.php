<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
    ];

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}