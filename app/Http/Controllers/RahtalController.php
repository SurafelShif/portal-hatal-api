<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRahtalRequest;
use App\Services\CommonService;
use App\Services\RahtalService;


class RahtalController extends Controller
{
    protected $RahtalService;
    // protected $CommonService;

    public function __construct(RahtalService $RahtalService)
    {
        $this->RahtalService = $RahtalService;
    }
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
        $rahtal = $this->RahtalService->getCurrentRahtal();
        return $rahtal;
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

        $rahtal = $this->RahtalService->updateRahtal($request, $uuid);
        return $rahtal;
    }
}
