<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    protected $fillable = [
        'name',
        'url',
        'channel_id',
        'status',
        'video_count',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
