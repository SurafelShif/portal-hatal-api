<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Image;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'personal_number' => '0000000',
            'full_name' => 'משתמש רגיל חט"ל',

        ]);
        $user->assignRole(Role::USER);

        $user2 = User::create([
            'personal_number' => '1111111',
            'full_name' => 'משתמש רגיל 1 חט"ל',

        ]);
        $user2->assignRole(Role::USER);

        $user3 = User::create([
            'personal_number' => '2222222',
            'full_name' => 'משתמש רגיל 2 חט"ל',
        ]);
        $user3->assignRole(Role::USER);

        $admin1 = User::create([
            'personal_number' => '1234567',
            'full_name' => 'מנהל מערכת 1',

        ]);
        $admin1->assignRole(Role::ADMIN);

        $admin2 = User::create([
            'personal_number' => '1234568',
            'full_name' => 'מנהל מערכת 2',
        ]);
        $admin2->assignRole(Role::ADMIN);

        // admin 3
        $admin3 = User::create([
            'personal_number' => '1234569',
            'full_name' => 'מנהל מערכת 3',
        ]);
        $admin3->assignRole(Role::ADMIN);
        // admin 3
        $image = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);

        Hero::create([
            'full_name' => 'שלמה העברי',
            'image_id' => $image->id,
        ]);
    }
}
