<?php

namespace Database\Seeders;

use App\Models\General;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsData = [
            [
                "content" => json_decode('[
                    {"type":"paragraph","content":[{"type":"text","marks":[{"type":"textStyle","attrs":{"fontFamily":null,"fontSize":"64px","color":null}},{"type":"bold"}],"text":"ייעוד החטיבה הטכנולוגית"}]},
                    {"type":"paragraph","content":[{"type":"text","marks":[{"type":"textStyle","attrs":{"fontFamily":null,"fontSize":"24px","color":null}}],"text":"להוות גוף טכנולוגי היוזם, מפתח ומאשר אמל״ח רב מימדי לכוחות היבשה בהתאם לצרכים המבצעיים ואחראי על מוכנות ואורך נשימה של משקי היבשה לשם יצירת"}]},
                    {"type":"paragraph","content":[{"type":"text","marks":[{"type":"textStyle","attrs":{"fontFamily":null,"fontSize":"40px","color":"#71B1FF"}},{"type":"bold"}],"text":"עליונות טכנולוגית בשדה הקרב"}]},
                    {"type":"paragraph","content":[{"type":"text","marks":[{"type":"textStyle","attrs":{"fontFamily":null,"fontSize":"24px","color":null}}],"text":"להוות גוף טכנולוגי ורגולטור למערכות מידע, DATA SCIENCE ובינה מלאכותית באט״ל ובזרוע היבשה."}]},
                    {"type":"paragraph"}
                ]', true),
                "type" => "doc",
                "created_at" => "2025-01-12 10:41:55",
                "updated_at" => "2025-01-19 11:24:13"
            ]
        ];

        General::create([
            'content' => $settingsData[0]['content'],
            'type' => $settingsData[0]['type'],
        ]);
    }
}
