<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Website extends Model
{
    use HasFactory;
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
        'name',
        'description',
        'link',
        'image_id'
    ];
    protected $hidden = [
        'image_id',
        'created_at',
        'updated_at',
        'is_deleted',
        'id'
    ];
}
