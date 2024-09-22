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
        // Create roles for both 'api' and 'web' guards
        $adminRoleApi = Role::create(['name' => 'admin']);
        $userRoleApi = Role::create(['name' => 'user']);
        // Create permissions for both guards
        $adminPermissionApi = Permission::create(['name' => 'מנהל מערכת']);
        $userPermissionApi = Permission::create(['name' => 'משתמש']);
        // Assign permissions to roles for both guards
        $adminRoleApi->givePermissionTo($adminPermissionApi);
        $userRoleApi->givePermissionTo($userPermissionApi);
    }
}
