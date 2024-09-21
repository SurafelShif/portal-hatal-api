<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Define guard names
        $apiGuard = 'api';
        $webGuard = 'web';

        // Create roles for both 'api' and 'web' guards
        $adminRoleApi = Role::create(['name' => 'admin', 'guard_name' => $apiGuard]);
        $userRoleApi = Role::create(['name' => 'user', 'guard_name' => $apiGuard]);

        $adminRoleWeb = Role::create(['name' => 'admin', 'guard_name' => $webGuard]);
        $userRoleWeb = Role::create(['name' => 'user', 'guard_name' => $webGuard]);

        // Create permissions for both guards
        $adminPermissionApi = Permission::create(['name' => 'מנהל מערכת', 'guard_name' => $apiGuard]);
        $userPermissionApi = Permission::create(['name' => 'משתמש', 'guard_name' => $apiGuard]);

        $adminPermissionWeb = Permission::create(['name' => 'מנהל מערכת', 'guard_name' => $webGuard]);
        $userPermissionWeb = Permission::create(['name' => 'משתמש', 'guard_name' => $webGuard]);

        // Assign permissions to roles for both guards
        $adminRoleApi->givePermissionTo($adminPermissionApi);
        $userRoleApi->givePermissionTo($userPermissionApi);

        $adminRoleWeb->givePermissionTo($adminPermissionWeb);
        $userRoleWeb->givePermissionTo($userPermissionWeb);
    }
}
