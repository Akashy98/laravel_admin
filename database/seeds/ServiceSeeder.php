<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            ['name' => 'Chat', 'is_active' => true],
            ['name' => 'Call', 'is_active' => true],
            ['name' => 'Video Call', 'is_active' => true],
        ];

        foreach ($services as $service) {
            \App\Models\Service::updateOrCreate(
                ['name' => $service['name']],
                ['is_active' => $service['is_active']]
            );
        }
    }
}
