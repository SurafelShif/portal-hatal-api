<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'icons',
        'description',
    ];
    protected $hidden = ['updated_at', 'created_at', 'id'];

    protected $casts = [
        'icons' => 'array',
    ];
}
