<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (!$request->has('personal_id')) {
                return response()->json(["message" => "נא לספק תעודת זהות"], 400);
            }
            $user = User::where('personal_id', $request->personal_id)->first();
            if (!$user) {
                return response()->json(["message" => "משתמש לא נמצא"], 404);
            }
            Auth::login($user);
            $token = $user->createToken('PortalHatalToken')->accessToken;
            $cookie = cookie('PortalHatalToken', $token, 60);
            return response()->json([
                'message' => 'התחבר בהצלחה',
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    //
}
