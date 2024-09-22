<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommonService
{

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
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function updateImage($associatedimageId, $request)
    {
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
}
