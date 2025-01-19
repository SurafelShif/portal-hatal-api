<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\UpdateHeroRequest;
use App\Services\HeroService;
use Illuminate\Http\Response;

class HeroController extends Controller
{

    public function __construct(private HeroService $HeroService) {}
    //
    /**
     * @OA\Get(
     *      path="/api/hero",
     *      operationId="hero",
     *      tags={"Hero"},
     *      summary="Get Hero",
     *      description="Returns the Hero details",
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="משתמש אינו נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function index()
    {
        $result = $this->HeroService->getCurrentHero();
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
            'data' => $result
        ]);
    }
    /**
     * @OA\Post(
     *      path="/api/hero/{uuid}",
     *      operationId="update hero",
     *      tags={"Hero"},
     *      summary="Update the hero",
     *      description="Update a hero",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the hero",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="full_name",
     *                      type="string",
     *                      example="רחטל"
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary",
     *                      description="The image file to upload"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="רחטל עודכן בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="בקשה לא תקינה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="רחטל לא נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateHeroRequest $request, $uuid)
    {

        $result = $this->HeroService->updateHero($request, $uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::HERO_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
}
