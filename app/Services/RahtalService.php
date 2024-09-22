<?php

namespace App\Services;

use App\Models\Rahtal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RahtalService
{

    public function getCurrentRahtal()
    {
        try {
            dd("dads");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function updateRahtal(Request $request, $imageId)
    {
        try {
            $website = Rahtal::create([
                'full_name' => $request->input('full_name'),
                'image_id' => $imageId,
            ]);
            return response()->json([
                'message' => 'רחטל נוצר בהצלחה',
                'Website' => $website,
            ], 201);
            dd($request->all(), $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
