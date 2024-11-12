<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RahtalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'full_name' => $this->full_name,
            'image' => $this->image->image_path ? config('filesystems.storage_path') . $this->image->image_path : null,
        ];
    }
}
