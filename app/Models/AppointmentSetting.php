<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Helper methods
    public function getValue()
    {
        switch ($this->type) {
            case 'integer':
                return (int) $this->value;
            case 'boolean':
                return (bool) $this->value;
            case 'json':
                return json_decode($this->value, true);
            case 'float':
                return (float) $this->value;
            default:
                return $this->value;
        }
    }

    public function setValue($value)
    {
        switch ($this->type) {
            case 'json':
                $this->value = json_encode($value);
                break;
            default:
                $this->value = (string) $value;
        }
    }

    // Static methods
    public static function getSetting(string $key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->getValue() : $default;
    }

    public static function setSetting(string $key, $value, string $type = 'string', string $description = null)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->type = $type;
        $setting->description = $description;
        $setting->is_active = true;
        return $setting->save();
    }

    // Common settings methods
    public static function getInstantBookingEnabled(): bool
    {
        return static::getSetting('instant_booking_enabled', true);
    }

    public static function getScheduledBookingEnabled(): bool
    {
        return static::getSetting('scheduled_booking_enabled', true);
    }

    public static function getPaymentTiming(): string
    {
        return static::getSetting('payment_timing', 'on_accept');
    }

    public static function getMaxWaitTime(): int
    {
        return static::getSetting('max_wait_time', 300); // 5 minutes
    }

    public static function getAvailableDurations(): array
    {
        return static::getSetting('available_durations', [10, 15, 20]);
    }

    public static function getServicePricing(): array
    {
        return static::getSetting('service_pricing', [
            'chat' => [
                'base_price' => 100,
                'per_minute' => 10
            ],
            'call' => [
                'base_price' => 150,
                'per_minute' => 15
            ],
            'video_call' => [
                'base_price' => 200,
                'per_minute' => 20
            ]
        ]);
    }

    public static function getDiscountSettings(): array
    {
        return static::getSetting('discount_settings', [
            'enabled' => true,
            'first_time_discount' => 20, // 20% off for first booking
            'bulk_discount' => [
                'enabled' => true,
                'threshold' => 3, // 3+ bookings
                'discount' => 10 // 10% off
            ]
        ]);
    }

    public static function getBroadcastSettings(): array
    {
        return static::getSetting('broadcast_settings', [
            'enabled' => true,
            'max_astrologers' => 5,
            'auto_assign' => true,
            'assignment_timeout' => 60 // 60 seconds
        ]);
    }

    public static function getCancellationSettings(): array
    {
        return static::getSetting('cancellation_settings', [
            'free_cancellation_hours' => 24,
            'partial_refund_hours' => 2,
            'no_refund_hours' => 1
        ]);
    }

    public static function getRatingSettings(): array
    {
        return static::getSetting('rating_settings', [
            'enabled' => true,
            'required' => false,
            'auto_rating' => 5, // Auto 5-star if no rating given
            'rating_reminder_hours' => 24
        ]);
    }
}
