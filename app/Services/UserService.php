<?php

namespace App\Services;

use App\Messages\ResponseMessages;
use App\Models\User;
use Illuminate\Http\Response;
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
                ], Response::HTTP_UNAUTHORIZED);
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
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function addAdmin($uuid)
    {
        try {
            $user = User::where('is_deleted', false)->where('uuid', $uuid)->first();
            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
            if ($user->hasRole('admin')) {
                return response()->json([
                    'message' => ResponseMessages::NOT_USER
                ], Response::HTTP_CONFLICT);
            }
            if ($user->hasRole('user')) {
                $user->removeRole('user');
                $user->revokePermissionTo('משתמש');
            }
            $user->assignRole('admin');
            $user->givePermissionTo('מנהל מערכת');
            return response()->json([
                'message' => ResponseMessages::SUCCESS_ACTION,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteAdmin($uuid)
    {
        try {
            $user = User::where("uuid", $uuid)->where("is_deleted", false)->first();

            if (!$user) {
                return response()->json([
                    'message' => ResponseMessages::USER_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
            if ($user->hasRole('user')) {
                return response()->json([
                    'message' => ResponseMessages::NOT_ADMIN
                ], Response::HTTP_CONFLICT);
            }
            $logged_user = Auth::user();
            if ($logged_user->personal_id === $user->personal_id) {
                return response()->json([
                    'message' => ResponseMessages::SELF_REMOVAL
                ], Response::HTTP_CONFLICT);
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
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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

            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "message" => ResponseMessages::ERROR_OCCURRED,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
