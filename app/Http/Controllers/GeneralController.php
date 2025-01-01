<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\UpdateGeneralSettingsRequest;
use App\Models\General;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GeneralController extends Controller
{
    public function __construct(private GeneralService $generalService) {}
    /**
     * @OA\Get(
     *      path="/api/general",
     *      operationId="web settings",
     *      tags={"General"},
     *      summary="Retrieve Web Settings",
     *      description="Retrieve Web Settings",
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->generalService->getSettings();
        if ($result instanceof HttpStatusEnum) {
            return response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $result;
    }
    /**
     * @OA\Post(
     *      path="/api/general",
     *      operationId="updateOrCreateWebSettings",
     *      tags={"General"},
     *      summary="Update or create web settings",
     *      description="Update or create web settings",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Web settings content",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"settings", "description"},
     *                  @OA\Property(
     *                      property="hero",
     *                      type="object",
     *                      @OA\Property(
     *                          property="stuff",
     *                          type="object",
     *                          @OA\Property(
     *                              property="bold",
     *                              type="string",
     *                              example="fdsoigjdfog"
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="string",
     *                          example="sdfsd"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      example="sdfsd"
     *                  ),
     *                  @OA\Property(
     *                      property="icons",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1
     *                          ),
     *                          @OA\Property(
     *                              property="pos",
     *                              type="string",
     *                              example="1"
     *                          ),
     *                          @OA\Property(
     *                              property="image",
     *                              type="string",
     *                              example="image"
     *                          )
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The operation was successful"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="An error occurred"
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */



    public function update(UpdateGeneralSettingsRequest $request)
    {
        $result = $this->generalService->update($request->icons ?? null, $request->description ?? null);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(["message" => ResponseMessages::ERROR_OCCURRED], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
    }
}
