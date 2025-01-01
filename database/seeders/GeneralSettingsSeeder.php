<?php

namespace Database\Seeders;

use App\Models\General;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zroa = Image::create([
            'image_name' => "zroa-hayabasha1.png",
            'image_type' => "png",
            'image_path' => "images/zroa-hayabasha1.png"
        ]);
        $atal = Image::create([
            'image_name' => "Atal_logo1.png",
            'image_type' => "png",
            'image_path' => "images/Atal_logo1.png"
        ]);
        $hatal = Image::create([
            'image_name' => "Hatal1.png",
            'image_type' => "png",
            'image_path' => "images/Hatal1.png"
        ]);
        $inside = Image::create([
            'image_name' => "hatal-inside-white1.png",
            'image_type' => "png",
            'image_path' => "images/hatal-inside-white1.png"
        ]);
        $settingsData = [
            "hero" => [
                "content" =>  [
                    [
                        "type" => "heading",
                        "attrs" => ["level" => 1],
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "ייעוד החטיבה הטכנולוגית",
                                "marks" => [["type" => "bold"]],
                            ],
                        ],
                    ],
                    [
                        "type" => "paragraph",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "להוות גוף טכנולוגי היוזם, מפתח ומאשר אמל״ח רב מימדי לכוחות היבשה בהתאם לצרכים המבצעיים ואחראי על מוכנות ואורך נשימה של משקי היבשה לשם יצירת",
                            ],
                        ],
                    ],
                    [
                        "type" => "heading",
                        "attrs" => ["level" => 2],
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "עליונות טכנולוגית בשדה הקרב ",
                                "marks" => [["type" => "textStyle", "attrs" => ["color" => "#71B1FF"]]],
                            ],
                        ],
                    ],
                    [
                        "type" => "paragraph",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "להוות גוף טכנולוגי ורגולטור למערכות מידע, DATA SCIENCE ובינה מלאכותית באט״ל ובזרוע היבשה.",
                            ],
                        ],
                    ],
                ],
                "type" => "doc"
            ],
            "description" => "עליונות טכנולוגית -  Hatal Inside",
            "icons" => [["pos" => 1, "id" => $zroa->id], ["pos" => 2, "id" => $atal->id], ["pos" => 3, "id" => $hatal->id], ["pos" => 4, "id" => $inside->id]]
        ];

        General::create([
            'content' => $settingsData,
        ]);
    }
}
