<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerPricing extends Model
{
    protected $fillable = [
        'astrologer_id', 'service_id', 'price_per_minute', 'offer_price'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
    }
}
