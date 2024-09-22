<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
