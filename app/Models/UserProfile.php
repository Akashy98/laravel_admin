<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'birth_date', 'birth_time', 'birth_time_accuracy', 'birth_notes',
        'birth_place', 'gender', 'marital_status', 'marriage_date', 'religion', 'caste', 'gotra',
        'nakshatra', 'rashi', 'about_me', 'additional_notes', 'is_profile_complete', 'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_time' => 'time',
        'marriage_date' => 'date',
        'is_profile_complete' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
