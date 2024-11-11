<?php

namespace App\Http\Resources;

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
// TODO change the hard coded port to something else
class WebsiteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'position' => $this->position,
            'name' => $this->name,
            'description' => $this->description,
            'link' => $this->link,
            'image' => $this->image->image_path ? config('filesystems.storage_path') . $this->image->image_path : null,
        ];
    }
}
