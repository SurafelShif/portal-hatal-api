<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Http\Resources\HeroResource;
use App\Models\Hero;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HeroService
{
    protected $ImageService;

    public function __construct(ImageService $ImageService)
    {
        $this->ImageService = $ImageService;
    }
    public function getCurrentHero()
    {
        try {

            $hero = Hero::find(1);
            return new HeroResource($hero);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function updateHero(Request $request, $uuid)
    {
        try {
            $hero = Hero::where("uuid", $uuid)->first();

            if (!$hero) {
                return HttpStatusEnum::NOT_FOUND;
            }
            DB::beginTransaction();
            if ($request->has('image')) {
                $associatedImageId = $hero->image_id;
                $this->ImageService->updateImage($associatedImageId, $request->image);
            }
            if ($request->has('full_name')) {
                $hero->full_name = $request->full_name;
            }
            $hero->save();
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
