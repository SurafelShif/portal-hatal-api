<?php

namespace Database\Seeders;

use App\Models\Header;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            ["image_name" => "hatal-inside-white1.png", "image_type" => "png", "image_path" => "images/hatal-inside-white1.png"],
            ["image_name" => "Hatal1.png", "image_type" => "png", "image_path" => "images/Hatal1.png"],
            ["image_name" => "Atal_logo1.png", "image_type" => "png", "image_path" => "images/Atal_logo1.png"],
            ["image_name" => "zroa-hayabasha1.png", "image_type" => "png", "image_path" => "images/zroa-hayabasha1.png"],
        ];

        $icons = [];
        foreach ($images as $index => $image) {
            $createdImage = Image::create($image);
            $icons[] = ["position" => (string)($index + 1), "id" => $createdImage->id];
        }
        Header::create(["icons" => $icons, "description" => "חטל יחידות או משהו"]);
    }
}
