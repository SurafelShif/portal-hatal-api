<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    protected $fillable = [
        'file_name',
        'file_type',
        'file_path',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
    ];
}
