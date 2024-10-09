<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="Login user",
     *      description="Authenticate and login user based on personal id",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"personal_id"},
     *              @OA\Property(property="personal_id", type="string", example="123456789")
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
        try {
            if (!$request->filled('personal_id') || strlen($request->personal_id) !== 9 || !ctype_digit($request->personal_id)) {
                return response()->json(["message" => ResponseMessages::INVALID_REQUEST], Response::HTTP_BAD_REQUEST);
            }
            $user = User::where('personal_id', $request->personal_id)->first();
            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
            $tokenName = config('auth.access_token_name');
            $token = $user->createToken($tokenName);
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
            ])->withCookie(Cookie::make($tokenName, $token->accessToken));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //
}
