<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends Controller
{
    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }
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
     *          response=404,
     *          description="משתמש לא נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function user()
    {
        $logged_user = $this->UserService->getLoggedUser();
        return $logged_user;
    }
    /**
     * @OA\Get(
     *      path="/api/users/admins",
     *      operationId="index user",
     *      tags={"Users"},
     *      summary="Retrieve all users",
     *      description="Retrieve all users",
     *      @OA\Response(
     *          response=200,
     *          description="הפעולה התבצעה בהצלחה",
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->UserService->getAdmins();
        return $users;
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
     *          description="הרשאה נוספה בהצלחה",
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
        $user = $this->UserService->addAdmin($uuid);
        return $user;
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
     *          description="הרשאה נמחקה בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="משתמש אינו נמצא",
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="למשתמש זה אין הרשאת מנהל מערכת",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      )
     * )
     */
    public function delete($uuid)
    {
        $deleted_user = $this->UserService->deleteAdmin($uuid);
        return $deleted_user;
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
     *          description="פעולה נמחקה בהצלחה",
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

        $users = $this->UserService->getUsers();
        return $users;
    }

    //
}
