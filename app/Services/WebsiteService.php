<?php

namespace App\Services;

use App\Models\image;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteService
{

    public function getWebsites()
    {
        try {
            $websites = Website::all()->where("is_deleted", false);
            $websitesData = $websites->map(function ($website) {
                $file = Image::find($website->image_id);

                return [
                    'id' => $website->id,
                    'name' => $website->name,
                    'description' => $website->description,
                    'link' => $website->link,
                    'image' => $file->image_path
                ];
            });
            return response()->json($websitesData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function uploadImage(Request $request)
    {

        try {
            $image = $request->file('image');

            $imagePath = $image->store('images', 'public');
            return Image::create([
                'image_name' => $image->getClientOriginalName(),
                'image_path' => $imagePath,
                'image_type' => $image->getMimeType(),
            ]);
        } catch (\Exception $e) {
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
                'message' => 'קישור נוצר בהצלחה',
                'Website' => $website,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function deleteWebsite($id)
    {
        try {
            $website = Website::where('id', $id)->where('is_deleted', false)->first();
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
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function updateWebsite(Request $request, $id)
    {
        try {
            if (!$request->hasAny(['name', 'link', 'image', 'description'])) {
                return response()->json([
                    'message' => 'הכנס לפחות שדה אחד לעדכון'
                ], 400);
            }
            $website = Website::where('id', $id)->where('is_deleted', false)->first();

            if (!$website) {
                return response()->json([
                    'message' => 'אתר לא נמצא'
                ], 404);
            }
            if ($request->has('name')) {
                $website->name = $request->name;
            }
            if ($request->has('description')) {
                $website->description = $request->description;
            }
            if ($request->has('link')) {
                $website->link = $request->link;
            }
            if ($request->has('image')) {
                $associatedimageId = $website->image_id;
                $oldImage = Image::find($associatedimageId);
                $newimage = $request->file('image');
                $imagePath = $newimage->store('images', 'public');
                if (Storage::disk('public')->exists($oldImage->image_path)) {
                    Storage::disk('public')->delete($oldImage->image_path);
                }
                $oldImage->image_path = $imagePath;
                $oldImage->image_name = $request->image->getClientOriginalName();
                $oldImage->image_type =  $request->image->getMimeType();
                $oldImage->save();
            }
            $website->save();
            return response()->json([
                'message' => 'פרטי האתר עודכנו בהצלחה'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
