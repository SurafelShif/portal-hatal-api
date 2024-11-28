<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'full_name' => $this->full_name,
            'personal_number' => $this->personal_number,
            'role' => [
                $this->roles->first()->name ?? null,
            ],
        ];
    }
}
