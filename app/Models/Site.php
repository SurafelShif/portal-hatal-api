<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    public function file()
    {
        return $this->hasOne(File::class);
    }
    protected $fillable = [
        'name',
        'link',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
    ];
}
