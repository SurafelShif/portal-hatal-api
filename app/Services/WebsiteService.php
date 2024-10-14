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

    public function createWebsite(Request $request)
    {
        try {
            DB::beginTransaction();
            $image = $this->ImageService->uploadimage($request->image);
            Website::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'link' => $request->input('link'),
                'image_id' => $image->id,
            ]);
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

    public function deleteWebsite($uuid)
    {
        try {
            $website = Website::where('uuid', $uuid)->where('is_deleted', false)->first();
            if (!$website) {
                return HttpStatusEnum::NOT_FOUND;
            }

            $website->is_deleted = true;
            $website->save();

            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function updateWebsite(Request $request, $uuid)
    {
        try {
            if (empty($request->all())) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            DB::beginTransaction();
            $website = Website::where('uuid', $uuid)->where('is_deleted', false)->first();

            if (!$website) {
                return HttpStatusEnum::NOT_FOUND;
            }


            if ($request->filled('name')) {
                $website->name = $request->name;
            }
            if ($request->filled('description')) {
                $website->description = $request->description;
            }
            if ($request->filled('link')) {
                $website->link = $request->link;
            }
            if ($request->has('image')) {
                $associatedImageId = $website->image_id;
                if ($request->image === null) {
                    $this->ImageService->updateImage($associatedImageId);
                } else if ($request->hasFile('image')) {
                    $this->ImageService->updateImage($associatedImageId, $request->image);
                }
            }
            $website->save();
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
