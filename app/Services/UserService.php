<?php

namespace App\Services;

use App\Enums\HttpStatusEnum;
use App\Enums\Permission;
use App\Enums\ResponseMessages;
use App\Enums\Role;
use App\Http\Resources\UserResource;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
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
    public function addAdmin(Request $request)
    {
        try {
            $uuids = $request->all();

            $users = User::whereIn('uuid', $uuids)->get();
            if (count($users) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            $changedCount = 0;
            foreach ($users as $user) {
                if ($user->hasRole(Role::ADMIN) || $user->personal_id == -1) {
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
    public function deleteAdmin($uuids)
    {
        try {
            $admins = User::whereIn('uuid', $uuids)->get();
            if (count($admins) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            $changedCount = 0;
            foreach ($admins as $admin) {
                if ($admin->hasRole(Role::USER)) {
                    continue;
                }
                $logged_user = Auth::user();
                if ($logged_user->personal_id === $admin->personal_id) {
                    continue;
                }
                if ($admin->hasRole(Role::ADMIN)) {
                    $admin->removeRole(Role::ADMIN);
                    $admin->revokePermissionTo(Permission::MANAGE_USERS);
                }

                $admin->assignRole(Role::USER);
                $admin->givePermissionTo(Permission::VIEW_WEBSITE);
                $changedCount++;
                $admin->save();
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
    public function getUserByPersonalId($personal_number)
    {
        try {
            if ($personal_number && preg_match('/^\d{7,8}$/', $personal_number) !== 1) {
                return HttpStatusEnum::BAD_REQUEST;
            }

            $user = User::where('personal_number', $personal_number)->first();
            if (!$user) {
                // $user = $this->getUserFromAdfs($personal_number);
                // if (!$user) {
                // }
                return HttpStatusEnum::NOT_FOUND;
            }
            return $user;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    private function getUserFromAdfs($personal_number)
    {
        try {
            $client = new Client();
            $adfsUrl = env("ADFS_URL");
            $adfsUser = env("ADFS_USER");
            $adfsPassword = env("ADFS_PASSWORD");

            $queryParams = [
                'columns' => implode(',', [
                    'personalId',
                    'personalNumber',
                    'firstName',
                    'surname',
                ])
            ];

            $response = $client->get(
                $adfsUrl . "/api/employees/" . $personal_number,
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

            // $user = self::FAKE_USER;

            return $user;
        } catch (\Exception $e) {
            $statusCode = $e->getCode();
            if ($statusCode === 0) {
                return null;
            }
        }
    }
}
