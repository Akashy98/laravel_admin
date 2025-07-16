<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Vedic Astrology', 'description' => 'Traditional Indian astrology.', 'is_active' => true],
            ['name' => 'Tarot Reading', 'description' => 'Card-based divination.', 'is_active' => true],
            ['name' => 'Numerology', 'description' => 'Numbers and their influence.', 'is_active' => true],
            ['name' => 'Palmistry', 'description' => 'Reading palms for future.', 'is_active' => true],
            ['name' => 'Vastu', 'description' => 'Indian science of architecture.', 'is_active' => true],
            ['name' => 'Western Astrology', 'description' => 'Astrology based on Western traditions.', 'is_active' => true],
            ['name' => 'KP Astrology', 'description' => 'Krishnamurti Paddhati astrology.', 'is_active' => true],
            ['name' => 'Lal Kitab', 'description' => 'Astrology based on Lal Kitab.', 'is_active' => true],
            ['name' => 'Face Reading', 'description' => 'Reading faces for personality and future.', 'is_active' => true],
            ['name' => 'Prashna Kundali', 'description' => 'Horary astrology.', 'is_active' => true],
            ['name' => 'Nadi Astrology', 'description' => 'Ancient Indian predictive astrology.', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            \App\Models\AstrologerCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
