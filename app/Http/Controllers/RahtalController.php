<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\StoreRahtalRequest;
use App\Services\RahtalService;
use Illuminate\Http\Response;

class RahtalController extends Controller
{

    public function __construct(private RahtalService $RahtalService) {}
    //
    /**
     * @OA\Get(
     *      path="/api/rahtal",
     *      operationId="rahtal",
     *      tags={"Rahtal"},
     *      summary="Get Rahtal",
     *      description="Returns the Rahtal details",
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
        $result = $this->RahtalService->getCurrentRahtal();
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
            'rahtal' => $result
        ]);
    }
    /**
     * @OA\Post(
     *      path="/api/rahtal/{uuid}",
     *      operationId="update rahtal",
     *      tags={"Rahtal"},
     *      summary="Update the rahtal",
     *      description="Update a rahtal",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the rahtal",
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
     *          description="הכנס לפחות שדה אחד לעדכון",
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
    public function update(StoreRahtalRequest $request, $uuid)
    {

        $result = $this->RahtalService->updateRahtal($request, $uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::RAHTAL_NOT_FOUND, Response::HTTP_NOT_FOUND),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
}
