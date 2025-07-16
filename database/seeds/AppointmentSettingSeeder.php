<?php

use Illuminate\Database\Seeder;
use App\Models\AppointmentSetting;

class AppointmentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'instant_booking_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable instant booking for astrologers',
                'is_active' => true
            ],
            [
                'key' => 'scheduled_booking_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable scheduled booking for astrologers',
                'is_active' => true
            ],
            [
                'key' => 'payment_timing',
                'value' => 'on_accept',
                'type' => 'string',
                'description' => 'When to charge payment: on_accept, on_booking, or on_completion',
                'is_active' => true
            ],
            [
                'key' => 'max_wait_time',
                'value' => '300',
                'type' => 'integer',
                'description' => 'Maximum wait time in seconds before auto-cancellation',
                'is_active' => true
            ],
            [
                'key' => 'available_durations',
                'value' => '[10, 15, 20, 30, 45, 60]',
                'type' => 'json',
                'description' => 'Available appointment durations in minutes',
                'is_active' => true
            ],
            [
                'key' => 'service_pricing',
                'value' => '{"chat":{"base_price":100,"per_minute":10},"call":{"base_price":150,"per_minute":15},"video_call":{"base_price":200,"per_minute":20}}',
                'type' => 'json',
                'description' => 'Default pricing structure for different service types',
                'is_active' => true
            ],
            [
                'key' => 'discount_settings',
                'value' => '{"enabled":true,"first_time_discount":20,"bulk_discount":{"enabled":true,"threshold":3,"discount":10}}',
                'type' => 'json',
                'description' => 'Discount settings for bookings',
                'is_active' => true
            ],
            [
                'key' => 'broadcast_settings',
                'value' => '{"enabled":true,"max_astrologers":5,"auto_assign":true,"assignment_timeout":60}',
                'type' => 'json',
                'description' => 'Broadcast appointment settings',
                'is_active' => true
            ],
            [
                'key' => 'cancellation_settings',
                'value' => '{"free_cancellation_hours":24,"partial_refund_hours":2,"no_refund_hours":1}',
                'type' => 'json',
                'description' => 'Cancellation and refund policy settings',
                'is_active' => true
            ],
            [
                'key' => 'rating_settings',
                'value' => '{"enabled":true,"required":false,"auto_rating":5,"rating_reminder_hours":24}',
                'type' => 'json',
                'description' => 'Rating and review settings',
                'is_active' => true
            ],
            [
                'key' => 'booking_advance_hours',
                'value' => '24',
                'type' => 'integer',
                'description' => 'How many hours in advance users can book appointments',
                'is_active' => true
            ],
            [
                'key' => 'max_daily_bookings',
                'value' => '50',
                'type' => 'integer',
                'description' => 'Maximum number of bookings per day per astrologer',
                'is_active' => true
            ],
            [
                'key' => 'booking_buffer_minutes',
                'value' => '15',
                'type' => 'integer',
                'description' => 'Buffer time between appointments in minutes',
                'is_active' => true
            ],
            [
                'key' => 'auto_reminder_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable automatic appointment reminders',
                'is_active' => true
            ],
            [
                'key' => 'reminder_timing',
                'value' => '[60, 15]',
                'type' => 'json',
                'description' => 'Reminder timing in minutes before appointment',
                'is_active' => true
            ],
            [
                'key' => 'reschedule_limit',
                'value' => '2',
                'type' => 'integer',
                'description' => 'Maximum number of times an appointment can be rescheduled',
                'is_active' => true
            ],
            [
                'key' => 'reschedule_advance_hours',
                'value' => '2',
                'type' => 'integer',
                'description' => 'Minimum hours before appointment to allow rescheduling',
                'is_active' => true
            ],
            [
                'key' => 'emergency_booking_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable emergency/urgent booking option',
                'is_active' => true
            ],
            [
                'key' => 'emergency_booking_premium',
                'value' => '50',
                'type' => 'integer',
                'description' => 'Premium percentage for emergency bookings',
                'is_active' => true
            ],
            [
                'key' => 'timezone_handling',
                'value' => 'user_timezone',
                'type' => 'string',
                'description' => 'Timezone handling: user_timezone, astrologer_timezone, or utc',
                'is_active' => true
            ]
        ];

        foreach ($settings as $setting) {
            AppointmentSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
