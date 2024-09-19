<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $useRole = Role::create(['name' => 'user']);

        // Create permissions
        Permission::create(['name' => 'מנהל מערכת']);
        Permission::create(['name' => 'משתמש']);

        $adminRole->givePermissionTo('מנהל מערכת');

        $useRole->givePermissionTo('משתמש');
    }
}
