<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Models\Portal;
use Illuminate\Support\Facades\Log;

class PortalService
{
    public function getPortals()
    {
        try {
            return Portal::all();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
