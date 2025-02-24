<?php

namespace Database\Seeders;

use App\Models\Portal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Portal::create(["display_name" => "חטל", "path" => "hatal"]);
    }
}
