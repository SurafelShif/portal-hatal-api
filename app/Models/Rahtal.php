<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;

class Rahtal extends Model
{
    use
        HasFactory,
        HasRoles;

    protected $table = 'rahtal';
    protected $guard_name = 'api';

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

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
        'image_id',
    ];

    protected $hidden = [
        'created_at',
        "image_id",
        'updated_at',
        'is_deleted',
        'roles',
        'id'
    ];
}
