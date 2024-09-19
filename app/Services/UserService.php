<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

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
            return $users;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function addUser(Request $request)
    {
        try {
            $user = User::create([
                'full_name' => $request['full_name'],
                'personal_number' => $request['personal_number'],
                'personal_id' => $request['personal_id'],
            ]);
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
    public function deleteUser($id)
    {
        try {
            $user = User::where("id", $id)->where("is_deleted", 0)->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->first();
            if (!$user) {
                return response()->json([
                    'message' => 'מנהל מערכת לא נמצא'
                ], 404);
            }

            $user->is_deleted = true;
            $user->save();
            return response()->json(["message" => "מנהל מערכת הוסר בצהלחה"], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
