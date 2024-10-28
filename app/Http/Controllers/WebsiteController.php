<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\StoreWebsiteRequest;
use App\Http\Requests\UpdateWebsiteRequest;
use App\Services\WebsiteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebsiteController extends Controller
{
    public function __construct(private WebsiteService $WebsiteService) {}

    /**
     * @OA\Get(
     *      path="/api/websites",
     *      operationId="index websites",
     *      tags={"Websites"},
     *      summary="Retrieve all websites",
     *      description="Retrieve all websites",
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
        $result = $this->WebsiteService->getWebsites();
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
            'data' => $result,

        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/websites",
     *      operationId="storeWebsite",
     *      tags={"Websites"},
     *      summary="Upload websites",
     *      description="Upload websites",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name", "link", "image", "description"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="אתר 1"
     *                  ),
     *                  @OA\Property(
     *                      property="link",
     *                      type="string",
     *                      example="https://www.google.com/"
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary",
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      example="אתר טכנולוגיה"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
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
    public function store(Request $request)
    {

        $result = $this->WebsiteService->createWebsite($request);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/api/websites",
     *      operationId="delete website",
     *      tags={"Websites"},
     *      summary="delete website",
     *      description="delete website",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="string",
     *                  format="uuid",
     *                  example="b143c4ab-91a7-481a-ab1a-cf4a00d2fc11" 
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="לא נדרש לבצע את הפעולה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="אתרים לא נמצאו",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function delete(Request $request)
    {
        $result = $this->WebsiteService->deleteWebsite($request->all());
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::WEBSITES_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
    /**
     * @OA\Post(
     *      path="/api/websites/update",
     *      operationId="updateWebsite",
     *      tags={"Websites"},
     *      summary="Update websites ",
     *      description="Update websites",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="array of update details",
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
     *                      property="name",
     *                      type="string",
     *                      example="אתר 1"
     *                  ),
     *                  @OA\Property(
     *                      property="link",
     *                      type="string",
     *                      example="https://www.google.com/"
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary",
     *                      description="The image file to upload"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      example="אתר טכנולוגיה"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="בקשה לא תקינה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="האתר לא נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request)
    {
        $result = $this->WebsiteService->updateWebsite($request);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR =>  response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::WEBSITE_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }

        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
}
