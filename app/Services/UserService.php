<?php

namespace App\Services;

use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Resources\UserResource;
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
            if (!Auth::check()) {
                return response()->json([
                    "message" => ResponseMessages::UNAUTHENTICATED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = Auth::user();
            return response()->json([
                "message" => ResponseMessages::SUCCESS_ACTION,
                'user' => new UserResource($user),
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
            if ($user->hasRole(Role::ADMIN)) {
                return response()->json([
                    'message' => ResponseMessages::NOT_USER
                ], Response::HTTP_CONFLICT);
            }
            if ($user->hasRole(Role::USER)) {
                $user->removeRole(Role::USER);
                $user->revokePermissionTo(Permission::VIEW_WEBSITE);
            }
            $user->assignRole(Role::ADMIN);
            $user->givePermissionTo(Permission::MANAGE_USERS);
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
            if ($user->hasRole(Role::USER)) {
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
            if ($user->hasRole(Role::ADMIN)) {
                $user->removeRole(Role::ADMIN);
                $user->revokePermissionTo(Permission::MANAGE_USERS);
            }

            $user->assignRole(Role::USER);
            $user->givePermissionTo(Permission::VIEW_WEBSITE);
            $user->save();
            return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
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
