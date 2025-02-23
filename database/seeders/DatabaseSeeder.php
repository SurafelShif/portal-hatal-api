<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $this->call(RoleAndPermissionSeeder::class);
        // $this->call(HeaderSeeder::class);
        $this->call(PortalsSeeder::class);
        $this->call(GeneralSettingsSeeder::class);
        $this->call(ArmySeeder::class);
        // $this->call(UsersSeeder::class);
        $this->call(WebsitesSeeder::class);
    }
}
