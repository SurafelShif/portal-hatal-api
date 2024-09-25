<?php

namespace App\Http\Resources;

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WebsiteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'link' => $this->link,
            'image_url' => $this->image ? Storage::url($this->image->image_path) : null,
        ];
    }
}
