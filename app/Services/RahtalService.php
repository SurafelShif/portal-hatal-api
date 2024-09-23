<?php

namespace App\Services;

use App\Messages\ResponseMessages;
use App\Models\Image;
use App\Models\Rahtal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RahtalService
{
    protected $CommonService;

    public function __construct(CommonService $CommonService)
    {
        $this->CommonService = $CommonService;
    }
    public function getCurrentRahtal()
    {
        try {
            $rahtal = Rahtal::find(1);
            $file = Image::find($rahtal->image_id)->first();
            $rahtalData = json_decode(json_encode($rahtal), true);
            $rahtalData['image_path'] =
                $file->image_path;
            return response()->json([
                "message" => ResponseMessages::SUCCESS_ACTION,
                "rahtal" => $rahtalData
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    public function updateRahtal(Request $request, $uuid)
    {
        try {
            $rahtal = Rahtal::where("uuid", $uuid)->first();

            if (!$rahtal) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], 404);
            }
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $associatedimageId = $rahtal->image_id;
                $this->CommonService->updateImage($associatedimageId, $request);
            }
            if ($request->has('full_name')) {
                $rahtal->full_name = $request->full_name;
            }
            $rahtal->save();
            DB::commit();
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
            ], 200);
            dd($request->all(), $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
