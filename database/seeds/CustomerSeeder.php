<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserAddress;
use App\Models\UserContact;
use App\Models\Wallet;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Create 20 dummy customers
        for ($i = 0; $i < 20; $i++) {
            // Create user
            $user = User::create([
                'role_id' => 2, // 2 for customer
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'gender' => $faker->randomElement(['male', 'female']),
                'phone' => $faker->unique()->numerify('##########'),
                'email' => $faker->unique()->safeEmail,
                'country_code' => '+91',
                'password' => bcrypt('password'),
                'status' => $faker->randomElement([0, 1]), // 0 or 1
                'is_online' => $faker->boolean(20), // 20% chance of being online
                'last_seen' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'gender' => $user->gender,
                'marital_status' => $faker->randomElement(['single', 'married', 'divorced', 'widowed']),
                'religion' => $faker->randomElement(['Hindu', 'Muslim', 'Christian', 'Sikh', 'Buddhist', 'Jain', 'Other']),
                'about_me' => $faker->paragraph,
                'birth_date' => $faker->date('Y-m-d', '-18 years'),
                'birth_time' => $faker->time('H:i:s'),
                'birth_place' => $faker->city,
                'is_profile_complete' => $faker->boolean(80), // 80% chance of complete profile
                'is_active' => $user->status,
            ]);

            // Create wallet for customer
            Wallet::create([
                'owner_id' => $user->id,
                'owner_type' => User::class,
                'balance' => $faker->randomFloat(2, 0, 10000),
            ]);

            // Create user address (50% chance)
            if ($faker->boolean(50)) {
                UserAddress::create([
                    'user_id' => $user->id,
                    'address_type' => $faker->randomElement(['birth', 'current', 'permanent', 'temporary']),
                    'address' => $faker->streetAddress . ', ' . $faker->secondaryAddress,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'country' => 'India',
                    'postal_code' => $faker->postcode,
                    'is_primary' => true,
                    'is_active' => true,
                ]);
            }

            // Create user contact (30% chance)
            if ($faker->boolean(30)) {
                UserContact::create([
                    'user_id' => $user->id,
                    'contact_type' => $faker->randomElement(['emergency', 'phone', 'whatsapp', 'telegram']),
                    'country_code' => '+91',
                    'phone_number' => $faker->numerify('##########'),
                    'contact_name' => $faker->name,
                    'relationship' => $faker->randomElement(['spouse', 'parent', 'sibling', 'friend', 'colleague']),
                    'is_primary' => true,
                    'is_verified' => $faker->boolean(60),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('20 dummy customers created successfully!');
    }
}
