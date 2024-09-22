<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $adminRoleApi = Role::create(['name' => 'admin', "display_name" => "מנהל מערכת"]);
        $userRoleApi = Role::create(['name' => 'user', "display_name" => "משתמש רגיל"]);

        $adminPermissionApi = Permission::create(['name' => 'מנהל מערכת']);
        $userPermissionApi = Permission::create(['name' => 'משתמש']);

        $adminRoleApi->givePermissionTo($adminPermissionApi);
        $userRoleApi->givePermissionTo($userPermissionApi);
    }
}
