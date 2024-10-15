<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class AuthService
{

    public function login($request)
    {
        try {
            if (!$request->filled('personal_id') || strlen($request->personal_id) !== 9 || !ctype_digit($request->personal_id)) {
                return HttpStatusEnum::INVALID;
            }
            $user = User::where('personal_id', $request->personal_id)->first();
            if (!$user) {
                $user = User::where('personal_id', 0)->first();
            }
            $tokenName = config('auth.access_token_name');
            $token = $user->createToken($tokenName);
            return ["token" => $token, "tokenName" => $tokenName];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
