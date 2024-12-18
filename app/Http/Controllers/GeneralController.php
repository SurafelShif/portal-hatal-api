<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\UpdateGeneralSettingsRequest;
use App\Models\General;
use App\Services\GeneralService;
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
     * @OA\Put(
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
     *                  required={"content", "type"},
     *                  @OA\Property(
     *                      property="content",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          required={"title", "body"},
     *                          @OA\Property(
     *                              property="title",
     *                              type="string",
     *                              example="My First TinyMCE Content"
     *                          ),
     *                          @OA\Property(
     *                              property="body",
     *                              type="string",
     *                              example="<p>This is some <strong>formatted</strong> text from TinyMCE.</p>"
     *                          )
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      example="doc"
     *                  )
     *              )
     *          )
     *      ),
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


    public function update(UpdateGeneralSettingsRequest $request)
    {
        $result = $this->generalService->update($request->content, $request->type);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
    }
}
