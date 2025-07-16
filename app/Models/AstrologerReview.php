<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerReview extends Model
{
    protected $fillable = [
        'astrologer_id', 'user_id', 'rating', 'review'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($review) {
            $review->astrologer->updateAverageRating();
        });
        static::updated(function ($review) {
            $review->astrologer->updateAverageRating();
        });
        static::deleted(function ($review) {
            $review->astrologer->updateAverageRating();
        });
    }
}
