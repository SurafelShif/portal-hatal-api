<?php

namespace App\Services;

use App\Enums\AdfsColumnsEnum;
use App\Enums\HttpStatusEnum;
use App\Enums\Permission;
use App\Enums\ResponseMessages;
use App\Enums\Role;
use App\Http\Resources\UserResource;
use App\Models\Rahtal;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
                ->get();
            return $admins;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function addAdmin(Request $request)
    {
        try {
            $uuids = $request->all();

            $users = User::whereIn('uuid', $uuids)->get();
            if (count($users) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            DB::beginTransaction();
            $failedUsers = [];
            foreach ($users as $user) {
                if ($user->hasRole(Role::ADMIN)) {
                    $failedUsers[] = $user->full_name;
                    continue;
                }
                if ($user->personal_number == -1) {
                    $failedUsers[] = $user->full_name;
                    continue;
                }
                if ($user->hasRole(Role::USER)) {
                    $user->removeRole(Role::USER);
                    $user->revokePermissionTo(Permission::VIEW_WEBSITE);
                }
                $user->assignRole(Role::ADMIN);
                $user->givePermissionTo(Permission::MANAGE_USERS);
            }
            if (!empty($failedUsers)) {
                DB::rollBack();
                return response()->json([
                    'failed_users' => $failedUsers,
                ], Response::HTTP_CONFLICT);
            }
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    public function deleteAdmin($uuids)
    {
        try {
            $admins = User::whereIn('uuid', $uuids)->get();
            if (count($admins) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            DB::beginTransaction();
            $failedUsers = [];
            foreach ($admins as $admin) {
                if ($admin->hasRole(Role::USER)) {
                    $failedUsers[] = $admin->full_name;
                    continue;
                }
                $logged_user = Auth::user();
                if ($logged_user->personal_number === $admin->personal_number) {
                    $failedUsers[] = $admin->full_name;
                    continue;
                }
                if ($admin->hasRole(Role::ADMIN)) {
                    $admin->removeRole(Role::ADMIN);
                    $admin->revokePermissionTo(Permission::MANAGE_USERS);
                }

                $admin->assignRole(Role::USER);
                $admin->givePermissionTo(Permission::VIEW_WEBSITE);
                $admin->save();
            }
            if (!empty($failedUsers)) {
                DB::rollBack();
                return response()->json([
                    'failed_users' => $failedUsers,
                ], Response::HTTP_CONFLICT);
            }
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function getUserByPersonalNumber($personal_number)
    {
        try {
            if ($personal_number && preg_match('/^\d{7,8}$/', $personal_number) !== 1) {
                return HttpStatusEnum::BAD_REQUEST;
            }
            $rahtal = Rahtal::find(1);
            $user = User::where('personal_number', $personal_number)->first();
            if (!$user) {

                // $user = $this->getUserFromVatican($personal_number);
                // if (!$user) {
                return HttpStatusEnum::NOT_FOUND;
                // }
            } else {
                if (!$user->hasRole('user')) {
                    return HttpStatusEnum::CONFLICT;
                }
            }
            if ($user?->personal_number === $rahtal->personal_number) {
                return HttpStatusEnum::CONFLICT;
            }
            return $user;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    private function getUserFromVatican($personalNumber)
    {
        try {
            $client = new Client();
            $vaticanUrl = env("VATICAN_URL");
            $vaticanToken = env("VATICAN_TOKEN");

            $queryParams = [
                'columns' => implode(',', [
                    AdfsColumnsEnum::PERSONAL_ID->value,
                    AdfsColumnsEnum::PERSONAL_NUMBER->value,
                    AdfsColumnsEnum::FIRST_NAME->value,
                    AdfsColumnsEnum::SURNAME->value,
                ]),
            ];

            $response = $client->get(
                $vaticanUrl . "/api/users/" . $personalNumber,
                [
                    'verify' => false,
                    'query' => $queryParams,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $vaticanToken,
                    ],
                ]
            );

            $user = json_decode($response->getBody(), true);
            return $user;
        } catch (\Exception $e) {
            $statusCode = $e->getCode();
            if ($statusCode === 0) {
                return null;
            }
        }
    }
}
