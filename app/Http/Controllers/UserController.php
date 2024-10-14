<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $UserService) {}
    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="user",
     *      tags={"Users"},
     *      summary="Get authenticated user",
     *      description="Returns the authenticated user's details",
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="המשתמש לא מחובר",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function user()
    {
        $result = $this->UserService->getLoggedUser();
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
     * @OA\Get(
     *      path="/api/users/admins",
     *      operationId="index user",
     *      tags={"Users"},
     *      summary="Retrieve all admins",
     *      description="Retrieve all admins",
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
        $result = $this->UserService->getAdmins();
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
     *      path="/api/users/{uuid}",
     *      operationId="store",
     *      tags={"Users"},
     *      summary="add admin",
     *      description="add admin",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the user",
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
     *          response=404,
     *          description="משתמש אינו נמצא",
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="משתמש זה הינו מנהל מערכת",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function store($uuid)
    {
        $result = $this->UserService->addAdmin($uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::CONFLICT => response()->json(ResponseMessages::NOT_USER, Response::HTTP_CONFLICT),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USER_NOT_FOUND, Response::HTTP_NOT_FOUND),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION
        ], Response::HTTP_CREATED);
    }
    /**
     * @OA\Delete(
     *      path="/api/users/{uuid}",
     *      operationId="delete",
     *      tags={"Users"},
     *      summary="delete admin",
     *      description="delete admin role",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the admin",
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
     *          response=404,
     *          description="משתמש אינו נמצא",
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="המשתמש אינו מנהל מערכת",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function delete($uuid)
    {
        $result = $this->UserService->deleteAdmin($uuid);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::CONFLICT => response()->json(ResponseMessages::NOT_ADMIN, Response::HTTP_CONFLICT),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USER_NOT_FOUND, Response::HTTP_NOT_FOUND),
            };
        }
        return response()->json(['message' => ResponseMessages::SUCCESS_ACTION]);
    }
    /**
     * @OA\Get(
     *      path="/api/users/users",
     *      operationId="getUsers",
     *      tags={"Users"},
     *      summary="Retrieve all users but admins",
     *      description="Retrieve all users but admins",
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {

        $result = $this->UserService->getUsers();
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

    //
}
