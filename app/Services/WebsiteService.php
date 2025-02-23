<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebsiteService
{

    public function __construct(private ImageService $ImageService) {}

    public function getWebsites($portal_id)
    {
        try {
            $websites = Website::with(["portal", "image"])->where("is_deleted", false)->where("portal_id", $portal_id)->orderBy('position', 'asc')->get();
            return WebsiteResource::collection($websites);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }

    public function createWebsite($request, $portal_id)
    {
        $uploadedImages = [];
        try {
            $websites = $request->all();
            DB::beginTransaction();

            foreach ($websites as  $website) {
                $image = $this->ImageService->uploadimage($website['image']);
                $uploadedImages[] = $image;
                Website::create([
                    'name' => $website['name'],
                    'description' => $website['description'],
                    'link' => $website['link'],
                    'position' => $website['position'],
                    'image_id' => $image->id,
                    'portal_id' => $portal_id
                ]);
            }
            DB::commit();

            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($uploadedImages as $image) {
                $this->ImageService->deleteImage($image->image_name);
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
            if ($websites->isEmpty()) {
                return HttpStatusEnum::NOT_FOUND;
            }

            DB::beginTransaction();

            foreach ($websites as $website) {
                $website->delete();
            }
            $allWebsites = Website::orderBy('position')->get();
            foreach ($allWebsites as $index => $website) {
                $website->position = $index;
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

    public function updateWebsite(Request $request)
    {
        try {
            DB::beginTransaction();
            $changedCount = 0;
            foreach ($request->all() as $index => $updateInfo) {

                $website = Website::where('uuid', $updateInfo['uuid'])->first();
                if (!$website) {
                    DB::rollBack();
                    return HttpStatusEnum::BAD_REQUEST;
                }
                //TODO add website connected to logged user
                if (isset($updateInfo['name']) && !empty($updateInfo['name'])) {
                    $website->name = $updateInfo['name'];
                }

                if (isset($updateInfo['description']) && !empty($updateInfo['description'])) {
                    $website->description = $updateInfo['description'];
                }

                if (isset($updateInfo['link']) && !empty($updateInfo['link'])) {
                    $website->link = $updateInfo['link'];
                }

                if (isset($updateInfo['position'])) {
                    $website->position = $updateInfo['position'];
                }
                if (array_key_exists('image', $updateInfo)) {
                    $associatedImageId = $website->image_id;
                    $this->ImageService->updateImage($associatedImageId, $updateInfo['image']);
                }
                $changedCount++;
                $website->save();
            }
            if ($changedCount === 0) {
                return HttpStatusEnum::NO_CONTENT;
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
