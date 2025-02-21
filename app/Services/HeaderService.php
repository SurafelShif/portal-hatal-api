<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Models\Header;
use App\Models\Image;
use Symfony\Component\HttpFoundation\Response;
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
    public function update(array $icons, ?string $description)
    {
        try {
            DB::beginTransaction();
            $headerSettings = Header::find(1);
            $existingIcons = $headerSettings->icons;
            if (!is_null($description)) {
                $headerSettings->update(['description' => $description]);
            }
            $isFound = false;
            foreach ($icons as $icon) {
                $isFound = false;
                foreach ($existingIcons as $index => $existingIcon) {
                    if ($existingIcon['id'] === $icon['id']) {
                        $isFound = true;
                        $existingIcons[$index]['position'] = $icon['position'];
                    }
                }
                if (!$isFound) {
                    return HttpStatusEnum::NOT_FOUND;
                }
            }
            $headerSettings->icons = $existingIcons;
            $headerSettings->save();
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
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
    public function upload(array $icons)
    {
        try {
            if (!count($icons)) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            $uploadedImages = [];
            $headerSettings = Header::find(1);
            $updatedIcons = [];
            $existingIcons = $headerSettings['icons'];
            foreach ($icons as $icon) {
                $image = $this->ImageService->uploadImage($icon['image']);
                $uploadedImages[] = $image;
                $updatedIcons[] = ['id' => $image->id, 'position' => $icon['position']];
            }

            $mergedIcons = array_merge($existingIcons, $updatedIcons);
            $headerSettings->update(["icons" => $mergedIcons]);
            return Response::HTTP_CREATED;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            foreach ($uploadedImages as $image) {
                $this->ImageService->deleteImage($image->image_name);
            }
            return HttpStatusEnum::ERROR;
        }
    }
}
