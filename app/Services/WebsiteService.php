<?php

namespace App\Services;

use App\Http\Resources\WebsiteResource;
use App\Messages\ResponseMessages;
use App\Models\Image;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebsiteService
{
    protected $CommonService;

    public function __construct(CommonService $CommonService)
    {
        $this->CommonService = $CommonService;
    }
    public function getWebsites()
    {
        try {
            $websites = Website::where("is_deleted", false)->with('image')->get();
            return response()->json(['message' => ResponseMessages::SUCCESS_ACTION, "websites" => WebsiteResource::collection($websites)], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createWebsite(Request $request)
    {
        try {
            DB::beginTransaction();

            $image = $this->CommonService->uploadimage($request);
            $website = Website::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'link' => $request->input('link'),
                'image_id' => $image->id,
            ]);
            DB::commit();

            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
                'Website' => $website,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteWebsite($uuid)
    {
        try {
            $website = Website::where('uuid', $uuid)->first();
            if (!$website) {
                return response()->json([
                    'message' => ResponseMessages::WEBSITE_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
            if ($website->is_deleted) {
                return response()->json([
                    'message' => ResponseMessages::SUCCESS_NO_ACTION_NEEDED
                ], Response::HTTP_NO_CONTENT);
            }
            $website->is_deleted = true;
            $website->save();

            return   response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION
            ], Response::HTTP_OK);;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateWebsite(Request $request, $uuid)
    {
        try {
            DB::beginTransaction();
            $website = Website::where('uuid', $uuid)->where('is_deleted', false)->first();

            if (!$website) {
                return response()->json([
                    'message' => ResponseMessages::WEBSITE_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

            if (!$request->hasAny(['name', 'link', 'image', 'description'])) {
                return response()->json([
                    'message' => ResponseMessages::INVALID_REQUEST
                ], Response::HTTP_BAD_REQUEST);
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
            if ($request->hasFile('image')) {
                $associatedimageId = $website->image_id;
                $this->CommonService->updateImage($associatedimageId, $request);
            }
            $website->save();
            DB::commit();
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
