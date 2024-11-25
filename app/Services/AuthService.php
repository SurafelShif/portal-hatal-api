<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthService
{

    public function login($request)
    {
        try {
            if (!isset($request->personal_number)  || preg_match('/^\d{7,8}$/', $request->personal_number) !== 1) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            $user = User::where('personal_number', $request->personal_number)->role('admin')->first();
            if (!$user) {
                return HttpStatusEnum::OK;
            }
            $tokenName = config('auth.access_token_name');
            $token = $user->createToken($tokenName);
            return ["token" => $token, "tokenName" => $tokenName, "user" => $user];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
