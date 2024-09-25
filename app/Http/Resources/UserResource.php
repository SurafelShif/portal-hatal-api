<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'full_name' => $this->full_name,
            'role' => [
                'name' => $this->roles->first()->name ?? null,
                'display_name' => $this->roles->first()->display_name ?? null,
            ],
        ];
    }
}
