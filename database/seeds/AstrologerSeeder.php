<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\AstrologerCategory;
use App\Models\AstrologerSkill;
use App\Models\AstrologerLanguage;
use App\Models\Language;
use App\Models\AstrologerAvailability;
use App\Models\AstrologerPricing;
use App\Models\Service;
use App\Models\AstrologerDocument;
use App\Models\AstrologerBankDetail;
use App\Models\Wallet;
use Faker\Factory as Faker;

class AstrologerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get available categories, languages, and services
        $categories = AstrologerCategory::where('is_active', true)->get();
        $languages = Language::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        // Create 15 dummy astrologers
        for ($i = 0; $i < 15; $i++) {
            // Create user
            $user = User::create([
                'role_id' => 3, // 3 for astrologer
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'gender' => $faker->randomElement(['male', 'female']),
                'phone' => $faker->unique()->numerify('##########'),
                'email' => $faker->unique()->safeEmail,
                'country_code' => '+91',
                'password' => bcrypt('password'),
                'status' => $faker->randomElement([0, 1]), // 0 or 1
                'is_online' => $faker->boolean(30), // 30% chance of being online
                'last_seen' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);

            // Create astrologer profile
            $astrologer = Astrologer::create([
                'user_id' => $user->id,
                'about_me' => $faker->paragraphs(3, true),
                'experience_years' => $faker->numberBetween(1, 25),
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'is_online' => $user->is_online,
                'is_fake' => $faker->boolean(10), // 10% chance of being fake
                'is_test' => $faker->boolean(5), // 5% chance of being test
                'total_rating' => $faker->randomFloat(1, 1, 5),
            ]);

            // Create wallet for astrologer
            Wallet::create([
                'owner_id' => $user->id,
                'owner_type' => User::class,
                'balance' => $faker->randomFloat(2, 0, 50000),
            ]);

            // Assign random categories (1-3 categories)
            $categoryCount = $categories->count();
            $numCategories = min($categoryCount, $faker->numberBetween(1, 3));
            $selectedCategories = $categoryCount > 0 ? $categories->random($numCategories) : collect();
            foreach ($selectedCategories as $category) {
                AstrologerSkill::create([
                    'astrologer_id' => $astrologer->id,
                    'category_id' => $category->id,
                ]);
            }

            // Assign random languages (1-3 languages)
            $languageCount = $languages->count();
            $numLanguages = min($languageCount, $faker->numberBetween(1, 3));
            $selectedLanguages = $languageCount > 0 ? $languages->random($numLanguages) : collect();
            foreach ($selectedLanguages as $language) {
                AstrologerLanguage::create([
                    'astrologer_id' => $astrologer->id,
                    'language_id' => $language->id,
                ]);
            }

            // Create availability for each day of the week
            $daysOfWeek = [
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6,
            ];
            foreach ($daysOfWeek as $dayName => $dayIndex) {
                if ($faker->boolean(80)) { // 80% chance of being available
                    AstrologerAvailability::create([
                        'astrologer_id' => $astrologer->id,
                        'day_of_week' => $dayIndex,
                        'start_time' => $faker->time('H:i:s', '09:00:00'),
                        'end_time' => $faker->time('H:i:s', '18:00:00'),
                    ]);
                }
            }

            // Create pricing for each service
            foreach ($services as $service) {
                AstrologerPricing::create([
                    'astrologer_id' => $astrologer->id,
                    'service_id' => $service->id,
                    'price_per_minute' => $faker->numberBetween(50, 500),
                    'offer_price' => $faker->optional(0.3)->numberBetween(30, 400), // 30% chance of having offer
                ]);
            }

            // Create documents (50% chance)
            if ($faker->boolean(50)) {
                AstrologerDocument::create([
                    'astrologer_id' => $astrologer->id,
                    'document_type' => $faker->randomElement(['aadhar', 'pan', 'certificate', 'other']),
                    'document_url' => 'https://via.placeholder.com/300x200?text=Document',
                    'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                ]);
            }

            // Create bank details (70% chance)
            if ($faker->boolean(70)) {
                AstrologerBankDetail::create([
                    'astrologer_id' => $astrologer->id,
                    'bank_name' => $faker->randomElement(['HDFC Bank', 'ICICI Bank', 'SBI', 'Axis Bank', 'Kotak Bank']),
                    'account_number' => $faker->numerify('##########'),
                    'ifsc_code' => $faker->regexify('[A-Z]{4}0[A-Z0-9]{6}'),
                    'account_holder_name' => $user->name,
                ]);
            }
        }

        $this->command->info('15 dummy astrologers created successfully!');
    }
}
