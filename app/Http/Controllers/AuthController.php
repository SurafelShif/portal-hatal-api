<?php

namespace App\Http\Controllers;

use App\Messages\ResponseMessages;
use App\Models\User;
use Illuminate\Http\Request;
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
     *          description="המשתמש התחבר בהצלחה",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="משתמש אינו קיים",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="אירעה שגיאה בהתחברות",
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="אין תוכן",
     *      ),
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            if (!$request->has('personal_id')) {
                return response()->json(["message" => ResponseMessages::INVALID_REQUEST], 400);
            }
            $user = User::where('personal_id', $request->personal_id)->first();
            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], 404);
            }
            $token = $user->createToken('PortalHatalToken')->accessToken;
            $cookie = cookie('PortalHatalToken', $token, 60);
            return response()->json([
                'message' => 'התחבר בהצלחה',
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    //
}
