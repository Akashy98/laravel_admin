<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['name' => 'English', 'code' => 'en', 'is_active' => true],
            ['name' => 'Hindi', 'code' => 'hi', 'is_active' => true],
        ];

        foreach ($languages as $language) {
            \App\Models\Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
