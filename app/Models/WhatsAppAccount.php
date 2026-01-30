<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'phone_number',
        'status',
    ];
}
