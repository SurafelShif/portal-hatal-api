<?php

namespace App\Http\Controllers;

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
            $personalId = $userDecoded['personal_id'] ?? null;

            $user = null;
            if (!is_null($personalId)) {
                $user = User::where('personal_id', $personalId)->first();
            }

            if (is_null($user)) {
                $queryParams = [
                    'columns' => implode(',', [
                        'personalId',
                        'personalNumber',
                        'firstName',
                        'surname',
                    ])
                ];

                $response = $client->get(
                    $adfsUrl . "/api/employees/" . $personalId,
                    [
                        'verify' => false,
                        'auth' => [
                            $adfsUser,
                            $adfsPassword,
                            'ntlm'
                        ],
                        'query' => $queryParams
                    ]
                );

                $user = json_decode($response->getBody(), true);


                $user = User::create($user);
                $user->assignRole(Role::USER->value);
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

    // --------------------- Private Functions ---------------------

    private function formatUserDetails(
        $personalId,
        $personalNumber,
        $firstName,
        $surname,
        $rank,
        $classification,
        $securityClassStartDate,
        $phoneNumber,
        $division
    ): array {
        if (
            is_null($personalId) ||
            is_null($personalNumber) ||
            is_null($firstName) ||
            is_null($surname) ||
            !is_array($rank) || !isset($rank['id']) ||
            !is_array($classification) || !isset($classification['id']) ||
            is_null($securityClassStartDate) ||
            is_null($phoneNumber) ||
            !is_array($division) || !isset($division['id'])
        ) {
            return [];
        }

        return [
            'uuid' => Str::uuid(),
            'personal_number' => $personalNumber,
            'personal_id' => $personalId,
            'full_name' => $firstName . " " . $surname,
            'rank' => $rank['id'],
            'classification' => $classification['id'],
            'class_start_date' => $securityClassStartDate,
            'phone' => $phoneNumber,
            'unit' => $division['id'],
            'is_deleted' => 0,
        ];
    }
}
