<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $productData = [
            [
                'name' => 'Rudraksha Mala',
                'description' => 'Authentic Rudraksha mala for spiritual protection and meditation. Made from natural Rudraksha beads.',
                'price' => 1500.00,
                'offer_price' => 1200.00,
                'offer_percentage' => 20,
                'rating' => 4.5,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/8B4513/ffffff?text=Rudraksha+Mala+1',
                    'https://via.placeholder.com/400x400/8B4513/ffffff?text=Rudraksha+Mala+2',
                ]
            ],
            [
                'name' => 'Crystal Healing Set',
                'description' => 'Complete crystal healing set including amethyst, rose quartz, and clear quartz crystals.',
                'price' => 2500.00,
                'offer_price' => null,
                'offer_percentage' => null,
                'rating' => 4.8,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/9370DB/ffffff?text=Crystal+Set+1',
                    'https://via.placeholder.com/400x400/9370DB/ffffff?text=Crystal+Set+2',
                    'https://via.placeholder.com/400x400/9370DB/ffffff?text=Crystal+Set+3',
                ]
            ],
            [
                'name' => 'Vedic Astrology Books',
                'description' => 'Collection of authentic Vedic astrology books including Brihat Parashara Hora Shastra.',
                'price' => 800.00,
                'offer_price' => 600.00,
                'offer_percentage' => 25,
                'rating' => 4.2,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/2F4F4F/ffffff?text=Vedic+Books+1',
                ]
            ],
            [
                'name' => 'Yantra for Success',
                'description' => 'Sacred Yantra for success and prosperity. Handcrafted with precision and blessed.',
                'price' => 3000.00,
                'offer_price' => 2400.00,
                'offer_percentage' => 20,
                'rating' => 4.7,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/FFD700/000000?text=Yantra+1',
                    'https://via.placeholder.com/400x400/FFD700/000000?text=Yantra+2',
                ]
            ],
            [
                'name' => 'Aromatherapy Essential Oils',
                'description' => 'Pure essential oils for meditation and spiritual practices. Includes sandalwood, jasmine, and rose.',
                'price' => 1200.00,
                'offer_price' => null,
                'offer_percentage' => null,
                'rating' => 4.3,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/32CD32/ffffff?text=Essential+Oils+1',
                    'https://via.placeholder.com/400x400/32CD32/ffffff?text=Essential+Oils+2',
                ]
            ],
            [
                'name' => 'Meditation Cushion Set',
                'description' => 'Comfortable meditation cushion and mat set for daily spiritual practices.',
                'price' => 1800.00,
                'offer_price' => 1440.00,
                'offer_percentage' => 20,
                'rating' => 4.6,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/DEB887/ffffff?text=Meditation+Cushion+1',
                ]
            ],
            [
                'name' => 'Astrological Gemstones',
                'description' => 'Authentic astrological gemstones including ruby, emerald, and sapphire.',
                'price' => 5000.00,
                'offer_price' => 4000.00,
                'offer_percentage' => 20,
                'rating' => 4.9,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/FF0000/ffffff?text=Ruby+Gemstone',
                    'https://via.placeholder.com/400x400/00FF00/ffffff?text=Emerald+Gemstone',
                    'https://via.placeholder.com/400x400/0000FF/ffffff?text=Sapphire+Gemstone',
                ]
            ],
            [
                'name' => 'Sacred Incense Sticks',
                'description' => 'Traditional Indian incense sticks for purification and spiritual practices.',
                'price' => 500.00,
                'offer_price' => null,
                'offer_percentage' => null,
                'rating' => 4.1,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/8B4513/ffffff?text=Incense+Sticks+1',
                    'https://via.placeholder.com/400x400/8B4513/ffffff?text=Incense+Sticks+2',
                ]
            ],
            [
                'name' => 'Vastu Consultation Kit',
                'description' => 'Complete Vastu consultation kit with compass, charts, and guidelines.',
                'price' => 3500.00,
                'offer_price' => 2800.00,
                'offer_percentage' => 20,
                'rating' => 4.4,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/4682B4/ffffff?text=Vastu+Kit+1',
                    'https://via.placeholder.com/400x400/4682B4/ffffff?text=Vastu+Kit+2',
                ]
            ],
            [
                'name' => 'Spiritual Art Prints',
                'description' => 'Beautiful spiritual art prints featuring deities and sacred symbols.',
                'price' => 800.00,
                'offer_price' => 600.00,
                'offer_percentage' => 25,
                'rating' => 4.0,
                'is_active' => true,
                'images' => [
                    'https://via.placeholder.com/400x400/FF69B4/ffffff?text=Spiritual+Art+1',
                    'https://via.placeholder.com/400x400/FF69B4/ffffff?text=Spiritual+Art+2',
                ]
            ],
        ];

        foreach ($productData as $product) {
            $slug =
                \Illuminate\Support\Str::slug($product['name']) . '-' . uniqid();
            $productModel = Product::create([
                'name' => $product['name'],
                'slug' => $slug,
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $faker->numberBetween(1, 100),
                'image' => $product['images'][0] ?? null,
                'is_active' => $product['is_active'],
            ]);

            // Create product images
            foreach ($product['images'] as $index => $imageUrl) {
                ProductImage::create([
                    'product_id' => $productModel->id,
                    'image_path' => $imageUrl,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        // Create some additional random products
        for ($i = 0; $i < 5; $i++) {
            $name = $faker->words(3, true);
            $slug = \Illuminate\Support\Str::slug($name) . '-' . uniqid();
            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 100, 5000),
                'stock' => $faker->numberBetween(1, 100),
                'image' => 'https://via.placeholder.com/400x400/' . $faker->hexColor . '/ffffff?text=Product+' . (($i + 11)) . '+1',
                'is_active' => $faker->boolean(80),
            ]);

            // Create 1-3 random images for each product
            $imageCount = $faker->numberBetween(1, 3);
            for ($j = 0; $j < $imageCount; $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://via.placeholder.com/400x400/' . $faker->hexColor . '/ffffff?text=Product+' . (($i + 11)) . '+' . ($j + 1),
                    'is_primary' => $j === 0,
                ]);
            }
        }

        $this->command->info('15 dummy products created successfully!');
    }
}
