<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RahtalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'full_name' => $this->full_name,
            'image_url' => $this->image ? Storage::url($this->image->image_path) : null,

        ];
    }
}
