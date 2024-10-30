<?php

namespace Database\Seeders;

use App\Models\Image;
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
        $image1 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Class",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Class.com/',
            "image_id" => $image1->id,
            'position' => 0
        ]);

        $image2 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Ibiza",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Ibiza.com/',
            "image_id" => $image2->id,
            'position' => 1
        ]);
        $image3 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Inzim",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Inzim.com/',
            "image_id" => $image3->id,
            'position' => 2
        ]);
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
            'position' => 3
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
            'position' => 4
        ]);
        $image6 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Raccoon",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Raccoon.com/',
            "image_id" => $image6->id,
            'position' => 5
        ]);
        $image7 = Image::create([
            'image_name' => null,
            'image_type' => null,
            'image_path' => null
        ]);
        Website::create([
            'name' => "Mathematics",
            'description' => "מערכת לניהול אתרים פוריל",
            'link' => 'https://www.Mathematics.com/',
            "image_id" => $image7->id,
            'position' => 6
        ]);
    }
}
