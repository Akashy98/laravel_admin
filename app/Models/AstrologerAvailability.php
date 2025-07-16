<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerAvailability extends Model
{
    protected $fillable = [
        'astrologer_id', 'day_of_week', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }
}
