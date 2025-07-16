<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $fillable = [
        'user_id', 'contact_type', 'country_code', 'phone_number', 'contact_name',
        'relationship', 'is_primary', 'is_verified', 'is_active'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the contact
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
