<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AppointmentSettingController extends Controller
{
    /**
     * Display appointment settings
     */
    public function index()
    {
        $settings = AppointmentSetting::orderBy('key')->get();

        // Group settings by category for better organization
        $groupedSettings = [
            'booking' => $settings->whereIn('key', [
                'instant_booking_enabled',
                'scheduled_booking_enabled',
                'booking_advance_hours',
                'max_daily_bookings',
                'booking_buffer_minutes',
                'reschedule_limit',
                'reschedule_advance_hours',
                'emergency_booking_enabled',
                'emergency_booking_premium'
            ]),
            'payment' => $settings->whereIn('key', [
                'payment_timing',
                'service_pricing'
            ]),
            'timing' => $settings->whereIn('key', [
                'max_wait_time',
                'available_durations'
            ]),
            'discounts' => $settings->whereIn('key', [
                'discount_settings'
            ]),
            'broadcast' => $settings->whereIn('key', [
                'broadcast_settings'
            ]),
            'cancellation' => $settings->whereIn('key', [
                'cancellation_settings'
            ]),
            'rating' => $settings->whereIn('key', [
                'rating_settings'
            ]),
            'reminders' => $settings->whereIn('key', [
                'auto_reminder_enabled',
                'reminder_timing'
            ]),
            'system' => $settings->whereIn('key', [
                'timezone_handling'
            ])
        ];

        return view('admin.appointments.settings', compact('groupedSettings'));
    }

    /**
     * Update appointment settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.type' => 'required|in:string,integer,boolean,json'
        ]);

        foreach ($request->settings as $settingData) {
            $setting = AppointmentSetting::where('key', $settingData['key'])->first();

            if ($setting) {
                $value = $settingData['value'];

                // Handle different data types
                switch ($settingData['type']) {
                    case 'boolean':
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                        break;
                    case 'json':
                        if (is_string($value)) {
                            $value = $value; // Keep as is if already JSON string
                        } else {
                            $value = json_encode($value);
                        }
                        break;
                    case 'integer':
                        $value = (int) $value;
                        break;
                    default:
                        $value = (string) $value;
                }

                $setting->update([
                    'value' => $value,
                    'type' => $settingData['type']
                ]);
            }
        }

        // Clear cache to ensure settings are refreshed
        Cache::forget('appointment_settings');

        return redirect()->back()->with('success', 'Appointment settings updated successfully');
    }

    /**
     * Reset settings to defaults
     */
    public function reset()
    {
        // Get default settings from seeder
        $defaultSettings = [
            'instant_booking_enabled' => 'true',
            'scheduled_booking_enabled' => 'true',
            'payment_timing' => 'on_accept',
            'max_wait_time' => '300',
            'available_durations' => '[10, 15, 20, 30, 45, 60]',
            'service_pricing' => '{"chat":{"base_price":100,"per_minute":10},"call":{"base_price":150,"per_minute":15},"video_call":{"base_price":200,"per_minute":20}}',
            'discount_settings' => '{"enabled":true,"first_time_discount":20,"bulk_discount":{"enabled":true,"threshold":3,"discount":10}}',
            'broadcast_settings' => '{"enabled":true,"max_astrologers":5,"auto_assign":true,"assignment_timeout":60}',
            'cancellation_settings' => '{"free_cancellation_hours":24,"partial_refund_hours":2,"no_refund_hours":1}',
            'rating_settings' => '{"enabled":true,"required":false,"auto_rating":5,"rating_reminder_hours":24}',
            'booking_advance_hours' => '24',
            'max_daily_bookings' => '50',
            'booking_buffer_minutes' => '15',
            'auto_reminder_enabled' => 'true',
            'reminder_timing' => '[60, 15]',
            'reschedule_limit' => '2',
            'reschedule_advance_hours' => '2',
            'emergency_booking_enabled' => 'true',
            'emergency_booking_premium' => '50',
            'timezone_handling' => 'user_timezone'
        ];

        foreach ($defaultSettings as $key => $value) {
            $setting = AppointmentSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        // Clear cache
        Cache::forget('appointment_settings');

        return redirect()->back()->with('success', 'Appointment settings reset to defaults');
    }

    /**
     * Toggle setting status
     */
    public function toggle(Request $request, $id)
    {
        $setting = AppointmentSetting::findOrFail($id);
        $setting->update(['is_active' => !$setting->is_active]);

        // Clear cache
        Cache::forget('appointment_settings');

        return response()->json([
            'success' => true,
            'is_active' => $setting->is_active,
            'message' => 'Setting ' . ($setting->is_active ? 'enabled' : 'disabled') . ' successfully'
        ]);
    }
}
