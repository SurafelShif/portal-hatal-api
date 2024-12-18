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
            "content" => [
                [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "ייעוד החטיבה הטכנולוגית",
                            "marks" => [
                                ["type" => "bold"],
                                ["type" => "italic"]
                            ]
                        ]
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
                        [
                            "type" => "text",
                            "text" => "עליונות טכנולוגית בשדה הקרב"
                        ]
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
        ];

        General::create([
            'content' => $settingsData["content"],
            'type' => $settingsData["type"]
        ]);
    }
}