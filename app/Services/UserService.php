<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getAdmins()
    {
        try {
            $admins = User::where('is_deleted', false)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })
                ->get();
            return response()->json([
                $admins,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function addAdmin($uuid)
    {
        try {
            $user = User::where('is_deleted', false)->where('uuid', $uuid)->whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })->first();
            if (!$user) {
                return response()->json([
                    'message' => 'משתמש לא נמצא'
                ], 404);
            }
            $user->removeRole('admin');
            $user->assignRole('admin');
            return response()->json([
                'message' => 'מנהל מערכת נוצר בהצלחה',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function deleteUser($uuid)
    {
        try {
            $user = User::where("uuid", $uuid)->where("is_deleted", 0)->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->first();
            if (!$user) {
                return response()->json([
                    'message' => 'מנהל מערכת לא נמצא'
                ], 404);
            }
            $user->removeRole('admin');
            $user->assignRole('user');
            $user->save();
            return response()->json(["message" => "מנהל מערכת הוסר בצהלחה"], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function getLoggedUser()
    {
        $user = Auth::user();

        return response()->json([
            $user,
        ], 200);;
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
                $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
