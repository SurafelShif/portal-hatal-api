<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminService
{
    public function getAdmins()
    {
        try {
            $admins = Admin::all()->where("is_deleted", false);
            return response()->json([
                $admins
            ], 200);
            return $admins;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function addAdmin(Request $request)
    {
        try {
            $admin = Admin::create([
                'full_name' => $request['full_name'],
                'personal_number' => $request['personal_number'],
                'personal_id' => $request['personal_id'],
            ]);
            return response()->json([
                'message' => 'מנהל מערכת נוצר בהצלחה',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
