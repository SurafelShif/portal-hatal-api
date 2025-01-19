<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Image;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArmySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin1 = User::create([
            'personal_number' => '9165828',
            'full_name' => 'סוראפל שיפראוו',
        ]);
        $admin1->assignRole(Role::ADMIN);

        $admin2 = User::create([
            'personal_number' => '99804761',
            'full_name' => 'רועי סחייק',
        ]);
        $admin2->assignRole(Role::ADMIN);

        $admin3 = User::create([
            'personal_number' => '9205667',
            'full_name' => 'יונתן אליהו עוזיאל',
        ]);
        $admin3->assignRole(Role::ADMIN);

        $admin4 = User::create([
            'personal_number' => '99807387',
            'full_name' => 'אופיר גולדברג',
        ]);
        $admin4->assignRole(Role::ADMIN);
        $image = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Hero::create([
            'full_name' => "",
            'image_id' => $image->id,
        ]);
    }
}
