<?php

namespace Database\Seeders;

use App\Models\General;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsData = [
            "hero" => [
                "content" => [
                    [
                        "type" => "paragraph",
                        "content" => [
                            ["type" => "text", "text" => "ייעוד החטיבה הטכנולוגית"]
                        ]
                    ],
                    [
                        "type" => "paragraph",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "להוות גוף טכנולוגי היוזם, מפתח ומאשר אמל״ח רב מימדי לכוחות היבשה בהתאם לצרכים המבצעיים ואחראי על מוכנות ואורך נשימה של משקי היבשה לשם יצירת"
                            ]
                        ]
                    ],
                    [
                        "type" => "paragraph",
                        "content" => [
                            ["type" => "text", "text" => "עליונות טכנולוגית בשדה הקרב "]
                        ]
                    ],
                    [
                        "type" => "paragraph",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => "להוות גוף טכנולוגי ורגולטור למערכות מידע, DATA SCIENCE ובינה מלאכותית באט״ל ובזרוע היבשה."
                            ]
                        ]
                    ]
                ],
                "type" => "doc"
            ],
            "description" => "עליונות טכנולוגית -  Hatal Inside",
            "icons" => []
        ];

        General::create([
            'content' => $settingsData,
        ]);
    }
}
