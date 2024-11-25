<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function __construct(private AuthService $AuthService) {}
    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="Login user",
     *      description="Authenticate and login user based on personal number",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"personal_number"},
     *              @OA\Property(property="personal_number", type="string", example="1234567")
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
     *          description="משתמש אינו נמצא",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה",
     *      ),
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $result = $this->AuthService->login($request);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::OK =>  response()->json(ResponseMessages::SUCCESS_ACTION, Response::HTTP_OK),
                HttpStatusEnum::INVALID => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                HttpStatusEnum::BAD_REQUEST => response()->json(ResponseMessages::INVALID_REQUEST, Response::HTTP_BAD_REQUEST),
                HttpStatusEnum::NOT_FOUND => response()->json(ResponseMessages::USER_NOT_FOUND, Response::HTTP_NOT_FOUND),
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        $token = $result['token'];
        $tokenName = $result['tokenName'];
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
        ])->withCookie(Cookie::make($tokenName, $token->accessToken));
    }
}
