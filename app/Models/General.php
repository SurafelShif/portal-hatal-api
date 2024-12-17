<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class General extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'type'
    ];
    protected $hidden = ['updated_at', 'created_at', 'id'];
    protected $casts = [
        'content' => 'array',
    ];
}
