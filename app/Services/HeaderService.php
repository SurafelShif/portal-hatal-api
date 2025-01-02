<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Models\Header;
use App\Models\Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HeaderService
{
    public function __construct(private ImageService $ImageService) {}
    public function getSettings()
    {
        try {
            $settings = Header::find(1);
            if ($settings) {
                $icons = [];
                $settingsContent = $settings->first();
                $headerIcons = $settingsContent->icons;
                foreach ($headerIcons as $icon) {
                    $image = Image::find($icon['id']);
                    $icons[] = ["id" => $image->id, "position" => $icon['position'], "image" => $image->image_path ? config('filesystems.storage_path') . $image->image_path : null];
                }
                usort($icons, function ($a, $b) {
                    return $a['position'] <=> $b['position'];
                });
                $settingsContent['icons'] = $icons;
                return ["icons" => $icons, "description" => $settingsContent->description];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function update(?array $icons, ?string $description)
    {
        try {
            DB::beginTransaction();
            $headerSettings = Header::find(1);
            $uploadedImages = [];

            if (is_null($headerSettings)) {
                foreach ($icons as $icon) {
                    $image = $this->ImageService->uploadImage($icon['image']);
                    $uploadedImages[] = $image;
                    $updatedIcons[] = ['id' => $image->id, 'position' => $icon['position']];
                }
                Header::create([
                    'description' => $description,
                    'icons' => json_encode($updatedIcons)
                ]);
            } else {
                $generalData = $headerSettings->first();
                $updatedIcons = [];
                $existingIcons = $generalData['icons'] ?? [];
                foreach ($existingIcons as $icon) {
                    $image = Image::find($icon['id']);
                    if (!is_null($image)) {
                        $this->ImageService->deleteImage($image->image_name);
                        Image::destroy($image->id);
                    }
                }
                foreach ($icons as $icon) {
                    $image = $this->ImageService->uploadImage($icon['image']);
                    $uploadedImages[] = $image;
                    $updatedIcons[] = ['id' => $image->id, 'position' => $icon['position']];
                }
                if (!empty($description)) {
                    $generalData->update(['description' => $description]);
                }
                $generalData->update(['icons' => $updatedIcons]);
            }
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            foreach ($uploadedImages as $image) {
                $this->ImageService->deleteImage($image->image_name);
            }
            return HttpStatusEnum::ERROR;
        }
    }
}
