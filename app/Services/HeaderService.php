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
                // $headerIcons = json_decode($settingsContent->icons, true);
                $headerIcons = $settingsContent->icons;
                foreach ($headerIcons as $icon) {
                    $image = Image::find($icon['id']);
                    $icons[] = ["id" => $image->id, "position" => $icon['position'], "image" => $image->image_path ? config('filesystems.storage_path') . $image->image_path : null];
                }
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
                $existingContent = $generalData;
                $existingIcons = $existingContent['icons'] ?? [];
                if (!empty($icons)) {
                    foreach ($icons as $icon) {
                        $image = $this->ImageService->updateImage($icon['replace'], $icon['image']);
                        if (is_null($icon['image'])) {
                            Image::destroy($icon['replace']);
                            $existingContent['icons'] = array_filter($existingContent['icons'], function ($existingIcon) use ($icon) {
                                return !($existingIcon['id'] == $icon['replace']);
                            });
                            $existingContent['icons'] = array_values($existingContent['icons']);
                        } else {
                            $uploadedImages[] = $image;
                            $updatedIcons[] = ['id' => $image->id, 'position' => $icon['position']];
                        }
                    }
                    foreach ($existingIcons as $existingIcon) {
                        $isReplaced = false;
                        foreach ($icons as $icon) {
                            if (isset($icon['replace']) && $icon['replace'] == $existingIcon['id']) {
                                $isReplaced = true;
                                break;
                            }
                        }
                        if (!$isReplaced) {
                            $updatedIcons[] = $existingIcon;
                        }
                    }
                    $existingContent['icons'] = $updatedIcons;
                }
                if (!empty($description)) {
                    $existingContent['description'] = $description;
                }
                $generalData->update(['content' => $existingContent]);
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
