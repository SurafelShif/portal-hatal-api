<?php

namespace App\Services;

use App\Http\Resources\RahtalResource;
use App\Messages\ResponseMessages;
use App\Models\Image;
use App\Models\Rahtal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

            return response()->json([
                "message" => ResponseMessages::SUCCESS_ACTION,
                "rahtal" => new RahtalResource($rahtal)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateRahtal(Request $request, $uuid)
    {
        try {
            $rahtal = Rahtal::where("uuid", $uuid)->first();

            if (!$rahtal) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
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
            ], Response::HTTP_OK);
            dd($request->all(), $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
