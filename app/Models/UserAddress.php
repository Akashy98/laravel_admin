<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'address_type', 'country', 'state', 'city', 'address',
        'postal_code', 'latitude', 'longitude', 'timezone', 'is_primary', 'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
