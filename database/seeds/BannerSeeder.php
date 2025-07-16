<?php

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\Astrologer;
use Faker\Factory as Faker;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get some astrologers for banner association
        $astrologers = Astrologer::where('status', 'approved')->get();

        $bannerData = [
            [
                'title' => 'Get Your Free Horoscope',
                'subtitle' => 'Discover what the stars have in store for you',
                'description' => 'Get your personalized daily horoscope and discover what the stars have in store for you today.',
                'image' => 'https://via.placeholder.com/800x400/667eea/ffffff?text=Free+Horoscope',
                'cta_url' => '/horoscope',
                'cta_text' => 'Check Now',
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'title' => 'Expert Astrologers Online',
                'subtitle' => 'Connect with certified astrologers 24/7',
                'description' => 'Get instant consultation from our team of expert astrologers available round the clock.',
                'image' => 'https://via.placeholder.com/800x400/ff6b6b/ffffff?text=Expert+Astrologers',
                'cta_url' => '/astrologers',
                'cta_text' => 'View Astrologers',
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'title' => 'Special Offer - 50% Off',
                'subtitle' => 'First consultation at half price',
                'description' => 'New users get 50% off on their first consultation with any astrologer.',
                'image' => 'https://via.placeholder.com/800x400/4ecdc4/ffffff?text=50%25+Off',
                'cta_url' => '/offers',
                'cta_text' => 'Grab Offer',
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'title' => 'Vedic Astrology Consultation',
                'subtitle' => 'Ancient wisdom meets modern technology',
                'description' => 'Experience the power of Vedic astrology with our certified practitioners.',
                'image' => 'https://via.placeholder.com/800x400/45b7d1/ffffff?text=Vedic+Astrology',
                'cta_url' => '/vedic-astrology',
                'cta_text' => 'Book Now',
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'title' => 'Love & Relationship Guidance',
                'subtitle' => 'Find answers to your relationship questions',
                'description' => 'Get expert advice on love, marriage, and relationships from our experienced astrologers.',
                'image' => 'https://via.placeholder.com/800x400/96ceb4/ffffff?text=Love+Guidance',
                'cta_url' => '/love-consultation',
                'cta_text' => 'Get Guidance',
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => 5,
            ],
        ];

        foreach ($bannerData as $index => $banner) {
            $bannerModel = Banner::create($banner);

            // Associate with a random astrologer (30% chance)
            if ($astrologers->count() > 0 && $faker->boolean(30)) {
                $bannerModel->update([
                    'astrologer_id' => $astrologers->random()->id
                ]);
            }
        }

        // Create some additional random banners
        for ($i = 0; $i < 3; $i++) {
            $banner = Banner::create([
                'title' => $faker->sentence(3),
                'subtitle' => $faker->sentence(6),
                'description' => $faker->paragraph,
                'image' => 'https://via.placeholder.com/800x400/' . $faker->hexColor . '/ffffff?text=Banner+' . ($i + 6),
                'cta_url' => '/' . $faker->slug,
                'cta_text' => $faker->words(2, true),
                'type' => 'card',
                'show_on' => 'home',
                'status' => 'active',
                'sort_order' => $i + 6,
            ]);

            // Associate with a random astrologer (20% chance)
            if ($astrologers->count() > 0 && $faker->boolean(20)) {
                $banner->update([
                    'astrologer_id' => $astrologers->random()->id
                ]);
            }
        }

        $this->command->info('8 dummy banners created successfully!');
    }
}
