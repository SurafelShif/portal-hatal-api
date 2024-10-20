<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Enums\Permission;
use App\Enums\ResponseMessages;
use App\Enums\Role;
use App\Http\Resources\UserResource;
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
            return new UserResource($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
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
            return $admins;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function addAdmin($uuids)
    {
        try {

            if (count($uuids) === 0) {
                return HttpStatusEnum::BAD_REQUEST;
            }

            $users = User::whereIn('uuid', $uuids)->get();
            if (count($users) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            $changedCount = 0;

            foreach ($users as $user) {
                if ($user->hasRole(Role::ADMIN)) {
                    continue;
                }
                if ($user->hasRole(Role::USER)) {
                    $user->removeRole(Role::USER);
                    $user->revokePermissionTo(Permission::VIEW_WEBSITE);
                }
                $user->assignRole(Role::ADMIN);
                $user->givePermissionTo(Permission::MANAGE_USERS);
                $changedCount++;
            }
            if ($changedCount === 0) {
                return HttpStatusEnum::NO_CONTENT;
            }
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function deleteAdmin($uuid)
    {
        try {
            $user = User::where("uuid", $uuid)->where("is_deleted", false)->first();

            if (!$user) {
                return HttpStatusEnum::NOT_FOUND;
            }
            if ($user->hasRole(Role::USER)) {
                return HttpStatusEnum::CONFLICT;
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
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
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

            return $users;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
}
