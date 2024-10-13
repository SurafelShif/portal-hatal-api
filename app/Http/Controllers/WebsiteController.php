<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\StoreWebsiteRequest;
use App\Http\Requests\UpdateWebsiteRequest;
use App\Services\WebsiteService;
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
            "message" => ResponseMessages::SUCCESS_ACTION,
            'websites' => $result,

        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/websites",
     *      operationId="storeWebsite",
     *      tags={"Websites"},
     *      summary="Upload a website",
     *      description="Upload a website",
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
    public function store(StoreWebsiteRequest $request)
    {

        $result = $this->WebsiteService->createWebsite($request);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }
        return response()->json([
            "message" => ResponseMessages::SUCCESS_ACTION,
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/api/websites/{uuid}",
     *      operationId="delete website",
     *      tags={"Websites"},
     *      summary="delete website",
     *      description="delete website",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the website",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
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
     *          description="אתר לא נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function delete($uuid)
    {
        $result = $this->WebsiteService->deleteWebsite($uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::WEBSITE_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }
        return response()->json([
            "message" => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
    /**
     * @OA\Post(
     *      path="/api/websites/{uuid}",
     *      operationId="updateWebsite",
     *      tags={"Websites"},
     *      summary="Update a website",
     *      description="Update a website",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the website",
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

    public function update(UpdateWebsiteRequest $request, $uuid)
    {
        $result = $this->WebsiteService->updateWebsite($request, $uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR =>  response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::WEBSITE_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                default => response()->json('', Response::HTTP_NO_CONTENT)
            };
        }

        return response()->json([
            "message" => ResponseMessages::SUCCESS_ACTION,
        ]);
    }
}
