<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
    ];
}
