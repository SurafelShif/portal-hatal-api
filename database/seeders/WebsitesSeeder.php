<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Portal;
use App\Models\Website;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $image4 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Lo yodea",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Loyodea.com/',
            "image_id" => $image4->id,
            'position' => 3,
            'portal_id' => 1
        ]);
        $image5 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Ronaldinio",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Ronaldinio.com/',
            "image_id" => $image5->id,
            'position' => 4,
            'portal_id' => 1
        ]);
    }
}
