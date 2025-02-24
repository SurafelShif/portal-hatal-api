<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\createPortalRequest;
use App\Services\PortalService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortalController extends Controller
{
    public function __construct(private PortalService $portalService) {}
    /**
     * @OA\Get(
     *      path="/api/portals",
     *      operationId="index portals",
     *      tags={"Portals"},
     *      summary="Retrieve all portals",
     *      description="Retrieve all portals",
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
        $result = $this->portalService->getPortals();
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
            'data' => $result,
        ], Response::HTTP_OK);
    }
    /**
     * @OA\Post(
     *      path="/api/portals",
     *      operationId="createportal",
     *      tags={"Portals"},
     *      summary="Add a portal",
     *      description="Creates a new portal record",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"display_name", "path"},
     *              @OA\Property(
     *                  property="display_name",
     *                  type="string",
     *                  example="חטל"
     *              ),
     *              @OA\Property(
     *                  property="path",
     *                  type="string",
     *                  example="hatal"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="הפעולה התבצעה בהצלחה"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה"
     *      )
     * )
     */

    public function create(createPortalRequest $request)
    {
        $result = $this->portalService->createPortal($request->validated());
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ], Response::HTTP_CREATED);
    }
}
