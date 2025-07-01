<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    private const STORAGE_DIR = "public";
    use HasFactory;

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {

            if (Storage::disk(config('filesystems.storage_service'))->exists(self::STORAGE_DIR . '/' . $image->image_name)) {
                Storage::disk(config('filesystems.storage_service'))->delete(self::STORAGE_DIR . '/' . $image->image_name);
            } else {
                Log::info("image was not found");
            }
        });
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
        'id'
    ];
}
