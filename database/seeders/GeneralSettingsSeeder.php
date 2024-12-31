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
                "settings" => [
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
                "description" => [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "להוות גוף טכנולוגי היוזם, מפתח ומאשר אמל״ח רב מימדי לכוחות היבשה בהתאם לצרכים המבצעיים ואחראי על מוכנות ואורך נשימה של משקי היבשה לשם יצירת",
                        ],
                    ],
                ],
                "icons" => []
            ],
        ];

        // General::create([
        //     'content' => $settingsData["content"],
        // ]);
    }
}
