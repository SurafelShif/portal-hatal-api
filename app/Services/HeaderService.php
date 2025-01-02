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
                if (is_null($icons) && is_null($description)) {
                    return HttpStatusEnum::BAD_REQUEST;
                }
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
                $updatedIcons = [];
                $existingIcons = $headerSettings['icons'];
                foreach ($icons as $icon) {
                    $image = $this->ImageService->uploadImage($icon['image']);
                    $uploadedImages[] = $image;
                    $updatedIcons[] = ['id' => $image->id, 'position' => $icon['position']];
                }
                if (!is_null($description)) {
                    $headerSettings->update(['description' => $description]);
                }
                $mergedIcons = array_merge($existingIcons, $updatedIcons);
                $headerSettings->update(['icons' => $mergedIcons]);
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
    public function deleteIcons(array $ids)
    {
        try {
            if (!count($ids)) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            DB::beginTransaction();
            $headerSettings = Header::find(1)->first();
            $icons = $headerSettings->icons ?? [];
            foreach ($ids as $id) {
                $image = Image::find($id);
                foreach ($icons as $key => $icon) {
                    if ($icon['id'] === $id) {
                        unset($icons[$key]);
                    }
                }
                if (!is_null($image)) {
                    $this->ImageService->deleteImage($image->image_name);
                    Image::destroy($image->id);
                } else {
                    return HttpStatusEnum::NOT_FOUND;
                }
            }
            $headerSettings->update(["icons" => $icons]);
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
