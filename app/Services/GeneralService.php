<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Models\General;
use Illuminate\Support\Facades\Log;

class GeneralService
{
    public function getSettings()
    {
        try {
            $settings = General::find(1);
            if ($settings) {
                // return json_decode($settings->content, true);
                return $settings->content;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function update(array $content)
    {
        try {
            $result = General::updateOrCreate(
                ['id' => 1],
                ['content' => json_encode($content)]
            );
            return $result;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
