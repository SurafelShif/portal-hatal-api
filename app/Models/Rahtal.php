<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Rahtal extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Uuid::uuid4();
            }
        });
    }
    protected $fillable = [
        'full_name',
        'image_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
        'roles',
        'id'
    ];
}
