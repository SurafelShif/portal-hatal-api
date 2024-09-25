<?php

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
use App\Enums\Role as EnumsRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $adminRoleApi = Role::create(['name' => EnumsRole::ADMIN, "display_name" => "מנהל מערכת"]);
        $userRoleApi = Role::create(['name' => EnumsRole::USER, "display_name" => "משתמש רגיל"]);

        $adminPermissionApi = Permission::create(['name' => EnumsPermission::MANAGE_USERS]);
        $userPermissionApi = Permission::create(['name' => EnumsPermission::VIEW_WEBSITE]);

        $adminRoleApi->givePermissionTo($adminPermissionApi);
        $userRoleApi->givePermissionTo($userPermissionApi);
    }
}
