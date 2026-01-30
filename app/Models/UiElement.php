<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiElement extends Model
{
    protected $fillable = [
        'name',
        'class_name',
        'html_code',
        'css_code',
    ];
}
