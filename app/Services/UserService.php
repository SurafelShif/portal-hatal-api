<?php

namespace App\Services;

use App\Messages\ResponseMessages;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getLoggedUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    "message" => ResponseMessages::UNAUTHENTICATED,
                ], 401);
            }
            $role = $user->roles->first();
            //to make the format user->role->display_name
            $userData = json_decode(json_encode($user), true);
            $userData['role'] = [
                'name' => $role->name,
                'display_name' => $role->display_name
            ];

            return response()->json([
                "message" => ResponseMessages::SUCCESS_ACTION,
                'user' => $userData
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getAdmins()
    {
        try {
            $admins = User::where('is_deleted', false)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })
                ->get();
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
                'admins' => $admins
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function addAdmin($uuid)
    {
        try {
            $user = User::where('is_deleted', false)->where('uuid', $uuid)->first();
            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], 404);
            }
            if ($user->hasRole('admin')) {
                return response()->json([
                    'message' => ResponseMessages::NOT_USER
                ], 409);
            }
            if ($user->hasRole('user')) {
                $user->removeRole('user');
                $user->revokePermissionTo('משתמש');
            }
            $user->assignRole('admin');
            $user->givePermissionTo('מנהל מערכת');
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function deleteAdmin($uuid)
    {
        try {
            $user = User::where("uuid", $uuid)->where("is_deleted", 0)->first();

            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], 404);
            }
            if ($user->hasRole('user')) {
                return response()->json([
                    'message' => ResponseMessages::NOT_ADMIN
                ], 409);
            }
            $logged_user = Auth::user();
            if ($logged_user->personal_id === $user->personal_id) {
                return response()->json([
                    'message' => ResponseMessages::SELF_REMOVAL
                ], 403);
            }
            if ($user->hasRole('admin')) {
                $user->removeRole('admin');
                $user->revokePermissionTo('מנהל מערכת');
            }

            $user->assignRole('user');
            $user->givePermissionTo('משתמש');
            $user->save();
            return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = User::where('is_deleted', false)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })
                ->get();

            return response()->json([
                "message" => ResponseMessages::SUCCESS_ACTION,
                'users' => $users,

            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
