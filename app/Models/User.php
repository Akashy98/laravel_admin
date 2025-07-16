<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Models\DeviceToken;
use App\Traits\HasWallet;
use App\Traits\ExcludesAdminUsers;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasWallet, ExcludesAdminUsers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'first_name', 'last_name', 'gender', 'phone',
        'email', 'country_code', 'profile_image', 'password', 'status',
        'is_online', 'last_seen'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'integer',
        'role_id' => 'integer',
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
    ];

    /**
     * Constant containing all relationships used when loading user data with astrologer
     */
    public const ASTROLOGER_WITH_RELATIONS = [
        'astrologer.wallet.transactions',
        'astrologer.skills.category',
        'astrologer.languages.language',
        'astrologer.availability',
        'astrologer.pricing.service',
        'astrologer.documents',
        'astrologer.bankDetails',
        'astrologer.reviews.user'
    ];

    /**
     * Constant containing all relationships used when loading user data
     */
    public const USER_WITH_RELATIONS = [
        'profile',
        'addresses',
        'contacts',
        'deviceTokens'
    ];

    /**
     * Get the user's profile
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the user's addresses
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Get the user's contacts
     */
    public function contacts()
    {
        return $this->hasMany(UserContact::class);
    }

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 1;
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * Get the user's astrologer profile
     */
    public function astrologer()
    {
        return $this->hasOne(Astrologer::class);
    }

    /**
     * Check if user is online
     *
     * @return bool
     */
    public function isOnline()
    {
        return $this->is_online === true;
    }

    /**
     * Set user as online
     *
     * @return bool
     */
    public function setOnline()
    {
        return $this->update([
            'is_online' => true,
            'last_seen' => now()
        ]);
    }

    /**
     * Set user as offline
     *
     * @return bool
     */
    public function setOffline()
    {
        return $this->update([
            'is_online' => false,
            'last_seen' => now()
        ]);
    }

    /**
     * Update last seen timestamp
     *
     * @return bool
     */
    public function updateLastSeen()
    {
        return $this->update(['last_seen' => now()]);
    }

    /**
     * Get formatted last seen time
     *
     * @return string|null
     */
    public function getLastSeenFormatted()
    {
        if (!$this->last_seen) {
            return null;
        }

        return $this->last_seen->diffForHumans();
    }

    /**
     * Scope to get online users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    /**
     * Scope to get offline users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffline($query)
    {
        return $query->where('is_online', false);
    }

    /**
     * Scope to get recently active users (within last 5 minutes)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyActive($query)
    {
        return $query->where('last_seen', '>=', now()->subMinutes(5));
    }

}
