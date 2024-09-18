<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;
    public function image()
    {
        return $this->hasOne(Image::class);
    }
    protected $fillable = [
        'name',
        'link',
        "image_id"

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
    ];
}
