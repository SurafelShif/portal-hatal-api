<?php

namespace App\Services;

use App\Enums\AdfsColumnsEnum;
use App\Enums\HttpStatusEnum;
use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Resources\UserResource;
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
            $admins = User::select(['uuid', 'personal_number', 'full_name'])->where('is_deleted', false)
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
            $users = $request->all();
            if (count($users) === 0) {
                return HttpStatusEnum::NOT_FOUND;
            }
            foreach ($users as $user) {
                $userData = User::where('personal_number', $user['personal_number'])->first();
                if (!is_null($userData)) {
                    if ($userData->hasRole(Role::ADMIN)) {
                        continue;
                    }
                    if ($userData->hasRole(Role::USER)) {
                        $userData->removeRole(Role::USER);
                        $userData->revokePermissionTo(Permission::VIEW_WEBSITE);
                    }
                    $userData->assignRole(Role::ADMIN);
                    $userData->givePermissionTo(Permission::MANAGE_USERS);
                } else {
                    $createdUser = User::create(["personal_number" => $user['personal_number'], "full_name" => $user['full_name']]);
                    $createdUser->assignRole(Role::ADMIN);
                    $createdUser->givePermissionTo(Permission::MANAGE_USERS);
                }
            }
            return Response::HTTP_CREATED;
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
            $logged_user = Auth::user();
            foreach ($admins as $admin) {
                if ($admin->hasRole(Role::USER)) {
                    continue;
                }
                if ($logged_user->personal_number === $admin->personal_number) {
                    DB::rollBack();
                    return HttpStatusEnum::FORBIDDEN;
                }
                if ($admin->hasRole(Role::ADMIN)) {
                    $admin->removeRole(Role::ADMIN);
                    $admin->revokePermissionTo(Permission::MANAGE_USERS);
                }

                $admin->assignRole(Role::USER);
                $admin->givePermissionTo(Permission::VIEW_WEBSITE);
                $admin->save();
            }
            DB::commit();
            return Response::HTTP_OK;
        } catch (\Exception $e) {
            DB::rollBack();
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
            $user = User::where('personal_number', $personal_number)->first();
            if (is_null($user)) {
                $user = $this->getUserFromVatican($personal_number);
                if (is_null($user)) {
                    return HttpStatusEnum::NOT_FOUND;
                }
            } else {
                if ($user->hasRole(Role::ADMIN)) {
                    return HttpStatusEnum::CONFLICT;
                }
            }
            return [
                'full_name' => $user['full_name'],
                'personal_number' => $user['personal_number'],
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return HttpStatusEnum::ERROR;
        }
    }
    private function getUserFromVatican($personalNumber)
    {
        try {
            $client = new Client();
            $vaticanUrl = config('services.vatican.url');
            $vaticanToken = config('services.vatican.token');

            $queryParams = [
                'columns' => implode(',', [
                    AdfsColumnsEnum::PERSONAL_NUMBER->value,
                    AdfsColumnsEnum::FIRST_NAME->value,
                    AdfsColumnsEnum::SURNAME->value,
                ]),
            ];

            $response = $client->post(
                $vaticanUrl . "/api/systems/login",
                [
                    'verify' => false,
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'token' => $vaticanToken,
                    ]
                ]
            );
            $loginToVatican = $response->getBody()->getContents();
            $loginToVatican = json_decode($loginToVatican, true);
            $vaticanAccessToken = $loginToVatican['access_token'];

            $response = $client->get(
                $vaticanUrl . "/api/users/" . $personalNumber,
                [
                    'verify' => false,
                    'query' => $queryParams,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $vaticanAccessToken,
                    ],
                ]
            );

            $userFromAdfs = json_decode($response->getBody(), true);
            return [
                'full_name' => $userFromAdfs['first_name'] . ' ' . $userFromAdfs['surname'],
                'personal_number' => $userFromAdfs['personal_number'],
            ];
        } catch (\Exception $e) {
            $statusCode = $e->getCode();
            if ($statusCode === 0) {
                return null;
            }
        }
    }
}
