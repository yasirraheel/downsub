<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    protected $fillable = ['keyword', 'response', 'match_type', 'is_active'];
}
