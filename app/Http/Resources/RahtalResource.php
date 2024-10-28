<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RahtalResource extends JsonResource
{
    public function toArray($request)
    {
        $imageUrl = $this->image->image_path ? config('filesystems.storage_path') . $this->image->image_path : null;

        if ($imageUrl && str_contains($this->image->image_path, 'placeholder')) {
            $imageUrl = null;
        }

        return [
            'uuid' => $this->uuid,
            'full_name' => $this->full_name,
            'image_url' => $imageUrl,
        ];
    }
}
