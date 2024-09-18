<?php

namespace App\Services;

use App\Models\image;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteService
{

    public function uploadImage(Request $request)
    {
        $image = $request->image('image');

        $imagePath = $image->store('images', 'public');
        return Image::create([
            'image_name' => $image->getClientOriginalName(),
            'image_path' => $imagePath,
            'image_type' => $image->getMimeType(),
        ]);

        throw new \Exception('No image uploaded');
    }
    public function createWebsite(Request $request, int $imageId)
    {
        return Website::create([
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'image_id' => $imageId,
        ]);
    }
    public function deleteWebsite($id)
    {

        $website = Website::where('id', $id)->where('is_deleted', false)->first();


        if (!$website) {

            return false;
        }

        $website->is_deleted = true;
        $website->save();

        return true;
    }
    public function updateWebsite(Request $request, $id)
    {

        if (!$request->hasAny(['name', 'link', 'image'])) {
            return response()->json([
                'message' => 'No valid parameters provided for update.'
            ], 400);
        }
        $website = Website::where('id', $id)->where('is_deleted', false)->first();

        if (!$website) {
            return response()->json([
                'message' => 'Website not found or already deleted.'
            ], 404);
        }
        if ($request->has('name')) {
            $website->name = $request->name;
        }
        if ($request->has('link')) {
            $website->link = $request->link;
        }
        if ($request->has('image')) {
            $associatedimageId = $website->image_id;
            $oldimage = Image::find($associatedimageId);

            $newimage = $request->image('image');
            $imagePath = $newimage->store('images', 'public');
            dd($oldimage);
        }
        $website->save();
        return response()->json([
            'message' => 'Website updated successfuly.'
        ], 200);
    }
}
