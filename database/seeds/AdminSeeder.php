<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserAddress;
use App\Models\UserContact;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'role_id' => 1, // 1 for admin
            'name' => 'Super Admin',
            'first_name' => 'Super Admin',
            'last_name' => '',
            'email' => 'admin@astroindia.com',
            'phone' => '',
            'country_code' => '+91',
            'password' => Hash::make('admin@123'),
            'status' => 1, // 1 for active
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        UserProfile::create([
            'user_id' => $admin->id,
            'gender' => 'male',
            'marital_status' => 'single',
            'religion' => 'Hindu',
            'about_me' => 'System Administrator',
            'is_profile_complete' => true,
            'is_active' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@astroindia.com');
        $this->command->info('Password: admin@123');
        $this->command->info('Phone: +91 9876543210');
        $this->command->info('Role: Administrator');
    }
}
