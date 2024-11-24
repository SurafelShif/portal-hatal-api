<?php

namespace App\Http\Controllers;

use App\Enums\AdfsColumnsEnum;
use App\Enums\Role;

use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Laravel\Passport\Token;
use GuzzleHttp\Client;

use Symfony\Component\HttpFoundation\Response;

use Exception;

class AuthController extends Controller
{

    public function login(Request $request): JsonResponse|string
    {
        try {
            $client = new Client();
            $adfsUrl = env("ADFS_URL");
            $adfsUser = env("ADFS_USER");
            $adfsPassword = env("ADFS_PASSWORD");

            $userFromADFS = $client->get(
                $adfsUrl . "/api/token/" . $request->token,
                [
                    'verify' => false,
                    'auth' => [
                        $adfsUser,
                        $adfsPassword,
                        'ntlm'
                    ],
                ]
            );

            $userDecoded = json_decode($userFromADFS->getBody(), true);
            $personalNumber = $userDecoded['personal_number'] ?? null;
            $user = null;
            if (!is_null($personalNumber)) {
                $user = User::where('personal_number', $personalNumber)->first();
            }
            if (is_null($user)) {
                $user = User::where('personal_number', -1)->first();
            }

            // revoking old token before creating a new one.
            Token::where('user_id', $user->id)->update(['revoked' => true]);
            $tokenName = config('auth.access_token_name');
            $token = $user->createToken($tokenName);

            $roles = [];
            $userRoles = $user->roles()->orderBy('id')->pluck('name')->toArray();
            if (count($userRoles) > 0) $roles[] = $userRoles[0];

            $permissions = $user?->getDirectPermissions();
            $userPermissions = [];

            foreach ($permissions as $permission) {
                $userPermissions[] = $permission->name;
            }

            return response()->json(
                [
                    "full name" => $user->full_name,
                    "personal_number" => $user->personal_number,
                    "role" => $roles,
                    "permissions" => $userPermissions
                ],
                Response::HTTP_CREATED
            )
                ->withCookie(Cookie::make($tokenName, $token->accessToken));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json('חלה שגיאה בעת ההתחברות', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
