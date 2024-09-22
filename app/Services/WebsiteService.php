<?php

namespace App\Services;

use App\Models\image;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $websites = Website::all()->where("is_deleted", false);
            $websitesData = $websites->map(function ($website) {
                $file = Image::find($website->image_id);

                return [
                    'uuid' => $website->uuid,
                    'name' => $website->name,
                    'description' => $website->description,
                    'link' => $website->link,
                    'image' => $file->image_path
                ];
            });
            return response()->json($websitesData, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function createWebsite(Request $request, int $imageId)
    {
        try {
            $website = Website::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'link' => $request->input('link'),
                'image_id' => $imageId,
            ]);
            return response()->json([
                'message' => 'אתר נוצר בהצלחה',
                'Website' => $website,
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function deleteWebsite($uuid)
    {
        try {
            $website = Website::where('uuid', $uuid)->where('is_deleted', false)->first();
            if (!$website) {
                return response()->json([
                    'message' => 'אתר לא נמצא'
                ], 404);
            }

            $website->is_deleted = true;
            $website->save();

            return   response()->json([
                'message' => 'אתר נמחק בהצלחה'
            ], 200);;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function updateWebsite(Request $request, $uuid)
    {
        try {
            $website = Website::where('uuid', $uuid)->where('is_deleted', false)->first();

            if (!$website) {
                return response()->json([
                    'message' => 'אתר לא נמצא'
                ], 404);
            }

            if (!$request->hasAny(['name', 'link', 'image', 'description'])) {
                return response()->json([
                    'message' => 'הכנס לפחות שדה אחד לעדכון'
                ], 400);
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
            return response()->json([
                'message' => 'פרטי האתר עודכנו בהצלחה'
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
