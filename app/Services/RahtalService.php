<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
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
            return Rahtal::find(1);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function updateRahtal(Request $request, $uuid)
    {
        try {
            $rahtal = Rahtal::where("uuid", $uuid)->first();

            if (!$rahtal) {
                return HttpStatusEnum::NOT_FOUND;
            }
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $associatedimageId = $rahtal->image_id;
                $this->ImageService->updateImage($associatedimageId, $request);
            }
            if ($request->has('full_name')) {
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
