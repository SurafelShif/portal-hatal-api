<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Http\Resources\RahtalResource;
use App\Models\Rahtal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RahtalService
{
    protected $ImageService;

    public function __construct(ImageService $ImageService)
    {
        $this->ImageService = $ImageService;
    }
    public function getCurrentRahtal()
    {
        try {
            $rahtal = Rahtal::find(1);
            return new RahtalResource($rahtal);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function updateRahtal(Request $request, $uuid)
    {
        try {
            if (empty($request->all())) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            $rahtal = Rahtal::where("uuid", $uuid)->first();

            if (!$rahtal) {
                return HttpStatusEnum::NOT_FOUND;
            }
            DB::beginTransaction();
            if ($request->has('image')) {
                $associatedImageId = $rahtal->image_id;
                if ($request->image === null) {
                    $this->ImageService->updateImage($associatedImageId);
                } else if ($request->hasFile('image')) {
                    $this->ImageService->updateImage($associatedImageId, $request->image);
                }
            }
            if ($request->filled('full_name')) {
                $rahtal->full_name = $request->full_name;
            }
            $rahtal->save();
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
