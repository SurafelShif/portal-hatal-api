<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'message' => "הפעולה התבצעה בהצלחה",
                'users' => $admins
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function addAdmin($uuid)
    {
        try {
            $user = User::where('is_deleted', false)->where('uuid', $uuid)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'משתמש לא נמצא'
                ], 404);
            }
            if ($user->hasRole('admin')) {
                return response()->json([
                    'message' => 'משתמש הינו מנהל מערכת'
                ], 409);
            }
            if ($user->hasRole('user')) {
                $user->removeRole('user');
                $user->revokePermissionTo('משתמש');
            }
            $user->assignRole('admin');
            $user->givePermissionTo('מנהל מערכת');
            return response()->json([
                'message' => 'מנהל מערכת נוצר בהצלחה',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function deleteAdmin($uuid)
    {
        try {
            $user = User::where("uuid", $uuid)->where("is_deleted", 0)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'מנהל מערכת לא נמצא'
                ], 404);
            }
            if ($user->hasRole('user')) {
                return response()->json([
                    'message' => 'משתמש הינו מנהל מערכת'
                ], 409);
            }
            if ($user->hasRole('admin')) {
                $user->removeRole('admin');
                $user->revokePermissionTo('מנהל מערכת');
            }

            $user->assignRole('user');
            $user->givePermissionTo('משתמש');
            $user->save();
            return response()->json(["message" => "מנהל מערכת הוסר בצהלחה"], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function getLoggedUser()
    {
        try {
            $user = Auth::user();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'message' => "הפעולה התבצעה בהצלחה",
            'user' => $user
        ], 200);
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
                'message' => "הפעולה התבצעה בהצלחה",
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
