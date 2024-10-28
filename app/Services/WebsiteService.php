<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebsiteService
{

    public function __construct(private ImageService $ImageService) {}

    public function getWebsites()
    {
        try {
            $websites = Website::where("is_deleted", false)->with('image')->get();
            return WebsiteResource::collection($websites);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }

    public function createWebsite($request)
    {
        try {
            $websites = $request->all();
            if (count($websites) === 0) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            DB::beginTransaction();
            foreach ($websites as  $website) {
                $image = $this->ImageService->uploadimage($website['image']);
                Website::create([
                    'name' => $website['name'],
                    'description' => $website['description'],
                    'link' => $website[('link')],
                    'image_id' => $image->id,
                ]);
            }
            DB::commit();

            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($image) && $image) {
                $this->ImageService->deleteImage($image->image_name);
                dd("image deleted:" . $image->image_name);
            }
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }

    public function deleteWebsite($uuids)
    {
        try {
            if (count($uuids) === 0) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            $websites = Website::whereIn('uuid', $uuids)->get();
            if (count($websites) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            foreach ($websites as $website) {
                $website->is_deleted = true;
                $website->save();
            }

            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function updateWebsite(Request $request)
    {
        try {
            if (empty($request->all())) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            DB::beginTransaction();
            foreach ($request->all() as $index => $updateInfo) {

                $website = Website::where('uuid', $updateInfo['uuid'])->where('is_deleted', false)->first();

                if (!$website) {
                    continue;
                }

                if (isset($updateInfo['name']) && !empty($updateInfo['name'])) {
                    $website->name = $updateInfo['name'];
                }

                if (isset($updateInfo['description']) && !empty($updateInfo['description'])) {
                    $website->description = $updateInfo['description'];
                }

                if (isset($updateInfo['link']) && !empty($updateInfo['link'])) {
                    $website->link = $updateInfo['link'];
                }
                if (isset($request[$index]['image'])) {
                    $associatedImageId = $website->image_id;
                    if ($request[$index]['image'] === null) {
                        $this->ImageService->updateImage($associatedImageId, null);
                    } else if (is_file($request[$index]['image'])) {
                        $this->ImageService->updateImage($associatedImageId, $updateInfo['image']);
                    }
                }

                $website->save();
            }





            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
