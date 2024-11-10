<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UuidsArrayRequest;
use Illuminate\Http\Request;
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
     *      path="/api/users/admins",
     *      operationId="store",
     *      tags={"Users"},
     *      summary="add admins",
     *      description="add admins",
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
    public function store(UuidsArrayRequest $request)
    {

        $result = $this->UserService->addAdmin($request);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::CONFLICT => response()->json(ResponseMessages::NOT_USER, Response::HTTP_CONFLICT),
                HttpStatusEnum::FORBIDDEN => response()->json(ResponseMessages::SUCCESS_NO_ACTION_NEEDED, Response::HTTP_FORBIDDEN),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USERS_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::NO_CONTENT => response()->json(ResponseMessages::NO_CONTENT, Response::HTTP_NO_CONTENT),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION
        ], Response::HTTP_OK);
    }
    /**
     * @OA\Delete(
     *      path="/api/users/admins",
     *      operationId="delete",
     *      tags={"Users"},
     *      summary="delete admins",
     *      description="delete admin role",
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
    public function delete(UuidsArrayRequest $request)
    {
        $result = $this->UserService->deleteAdmin($request->all());
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
                HttpStatusEnum::FORBIDDEN => response()->json(ResponseMessages::FORBIDDEN, Response::HTTP_FORBIDDEN),
                HttpStatusEnum::CONFLICT => response()->json(ResponseMessages::NOT_ADMIN, Response::HTTP_CONFLICT),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USERS_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::NO_CONTENT => response()->json(ResponseMessages::NO_CONTENT, Response::HTTP_NO_CONTENT),
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
    /**
     * @OA\Get(
     *      path="/api/users/{personal_number}",
     *      operationId="getUserByPersonalNumber",
     *      tags={"Users"},
     *      summary="Retrieve user by personal number excluding admins",
     *      description="מחזיר משתמש על ידי מספר אישי",
     *      @OA\Parameter(
     *          name="personal_number",
     *          in="path",
     *          required=true,
     *          description="User personal number",
     *          @OA\Schema(
     *              type="integer",
     *              example=1111111
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
     *          description="משתמש לא נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByPersonalNumber($personal_number)
    {
        $result = $this->UserService->getUserByPersonalNumber($personal_number);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USER_NOT_FOUND, Response::HTTP_NOT_FOUND),
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
