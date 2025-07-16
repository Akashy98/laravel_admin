<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Verification extends Model
{
    protected $fillable = [
        'phone',
        'code',
        'country_code',
        'status',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Check if the verification code is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expired_at->isPast();
    }

    /**
     * Check if the verification code is still valid
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Mark verification as verified
     *
     * @return bool
     */
    public function markAsVerified()
    {
        return $this->update(['status' => 'verified']);
    }

    /**
     * Mark verification as expired
     *
     * @return bool
     */
    public function markAsExpired()
    {
        return $this->update(['status' => 'expired']);
    }

    /**
     * Mark verification as failed
     *
     * @return bool
     */
    public function markAsFailed()
    {
        return $this->update(['status' => 'failed']);
    }

    /**
     * Get full phone number with country code
     *
     * @return string
     */
    public function getFullPhoneNumber()
    {
        return $this->country_code . $this->phone;
    }

    /**
     * Scope to get only valid (non-expired) verifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
                    ->where('expired_at', '>', Carbon::now());
    }

    /**
     * Scope to get only expired verifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<=', Carbon::now());
    }

    /**
     * Scope to get verifications by phone number
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $phone
     * @param string $countryCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPhone($query, $phone, $countryCode = '+91')
    {
        return $query->where('phone', $phone)
                    ->where('country_code', $countryCode);
    }

    /**
     * Find a valid verification by phone and code
     *
     * @param string $phone
     * @param string $code
     * @param string $countryCode
     * @return static|null
     */
    public static function findValidByPhoneAndCode($phone, $code, $countryCode = '+91')
    {
        return static::where('phone', $phone)
                    ->where('code', $code)
                    ->where('country_code', $countryCode)
                    ->where('status', 'pending')
                    ->where('expired_at', '>', Carbon::now())
                    ->first();
    }

    /**
     * Create a new verification record
     *
     * @param string $phone
     * @param string $code
     * @param string $countryCode
     * @param int $expiryMinutes
     * @return static
     */
    public static function createVerification($phone, $code, $countryCode = '+91', $expiryMinutes = 10)
    {
        return static::create([
            'phone' => $phone,
            'code' => $code,
            'country_code' => $countryCode,
            'status' => 'pending',
            'expired_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);
    }

    /**
     * Clean up expired verifications
     *
     * @return int
     */
    public static function cleanupExpired()
    {
        return static::where('expired_at', '<=', Carbon::now())
                    ->where('status', 'pending')
                    ->update(['status' => 'expired']);
    }
}
