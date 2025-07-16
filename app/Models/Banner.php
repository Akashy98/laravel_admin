<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'cta_text', 'cta_url', 'type', 'image', 'show_on', 'status', 'sort_order', 'start_date', 'end_date', 'astrologer_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the astrologer associated with this banner
     */
    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    /**
     * Scope to get active banners based on date range
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
