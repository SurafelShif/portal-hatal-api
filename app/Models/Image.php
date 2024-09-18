<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    protected $fillable = [
        'image_name',
        'image_type',
        'image_path',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
    ];
}
