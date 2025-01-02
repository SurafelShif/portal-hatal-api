<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\UpdateHeaderRequest;
use App\Services\HeaderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HeaderController extends Controller
{
    //
    public function __construct(private HeaderService $headerService) {}
    /**
     * @OA\Get(
     *      path="/api/header",
     *      operationId="web header",
     *      tags={"Header"},
     *      summary="Retrieve Web header",
     *      description="Retrieve Web header",
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
        $result = $this->headerService->getSettings();
        if ($result instanceof HttpStatusEnum) {
            return response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $result;
    }
    /**
     * @OA\Post(
     *      path="/api/header",
     *      operationId="updateOrCreateWebHeader",
     *      tags={"Header"},
     *      summary="Update or create web header",
     *      description="Update or create web header",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Web header content",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="icons",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="position",
     *                              type="string",
     *                              example="1"
     *                          ),
     *                          @OA\Property(
     *                              property="image",
     *                              type="string",
     *                              example="image"
     *                          )
     *                  )
     *                      ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              example="sewy"
     *                          )
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

    public function update(UpdateHeaderRequest $request)
    {
        $isEmptyString = array_key_exists('description', $request->all()) && empty($request->description);
        $result = $this->headerService->update($request->icons ?? [], $isEmptyString ? "" : $request->description);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(["message" => ResponseMessages::ERROR_OCCURRED], Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::BAD_REQUEST => response()->json(["message" => ResponseMessages::INVALID_REQUEST], Response::HTTP_BAD_REQUEST),
            };
        }
        return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
    }
    /**
     * @OA\Delete(
     *     path="/api/header",
     *     operationId="delete icons",
     *     tags={"Header"},
     *     summary="Delete icons",
     *     description="Delete icons by their IDs",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Array of icon IDs to delete",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example=2
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="הפעולה התבצעה בהצלחה",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="בקשה לא תקינה",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="אירעה שגיאה",
     *     )
     * )
     */

    public function delete(Request $request)
    {
        $result = $this->headerService->deleteIcons($request->all());
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(["message" => ResponseMessages::ERROR_OCCURRED], Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::BAD_REQUEST => response()->json(["message" => ResponseMessages::INVALID_REQUEST], Response::HTTP_BAD_REQUEST),
                HttpStatusEnum::NOT_FOUND => response()->json(["message" => ResponseMessages::IMAGE_NOT_FOUND], Response::HTTP_NOT_FOUND),
            };
        }
        return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
    }
}
