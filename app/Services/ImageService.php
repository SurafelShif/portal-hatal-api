<?php

namespace App\Services;

use App\Enums\ResponseMessages;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ImageService
{

    public function uploadImage(Request $request)
    {
        try {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $randomFileName = uniqid() . '_' . Str::random(10) . '.' . $extension;
            $imagePath = $image->storeAs('images', $randomFileName, config('filesystems.storage_service'));
            return Image::create([
                'image_name' => $randomFileName,
                'image_path' => $imagePath,
                'image_type' => $image->getMimeType(),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateImage($associatedimageId, UploadedFile $newImage = null)
    {
        try {

            $oldImage = Image::find($associatedimageId);
            if ($newImage !== null) {
                $extension = $newImage->getClientOriginalExtension();
                $randomFileName = uniqid() . '_' . Str::random(10) . '.' . $extension;
                $imagePath = $newImage->storeAs('images', $randomFileName, config('filesystems.storage_service'));
            } else {
                $extension = 'png';
                $randomFileName = 'placeholder.png';
                $imagePath = 'constants/' . $randomFileName;
            }

            $this->deleteImage($oldImage->image_name);

            $oldImage->image_path = $imagePath;
            $oldImage->image_name = $randomFileName;
            $oldImage->image_type = $extension;
            $oldImage->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteImage($image_name)
    {
        try {
            if (Storage::disk(config('filesystems.storage_service'))->exists('images/' . $image_name)) {
                Storage::disk(config('filesystems.storage_service'))->delete('images/' . $image_name);
            } else {
                Log::info("image was not found");
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
