<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasWallet;

class Astrologer extends Model
{
    use HasWallet;

    protected $fillable = [
        'user_id', 'about_me', 'experience_years', 'status', 'is_online', 'total_rating', 'is_fake', 'is_test'
    ];

    /**
     * Constant containing all relationships used when loading astrologer data
     */
    public const WITH_RELATIONS = [
        'wallet.transactions',
        'skills.category',
        'languages.language',
        'availability',
        'pricing.service',
        'documents',
        'bankDetails',
        'reviews.user'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($astrologer) {
            // Create default service entries for all active services
            $services = Service::where('is_active', true)->get();

            foreach ($services as $service) {
                AstrologerService::create([
                    'astrologer_id' => $astrologer->id,
                    'service_id' => $service->id,
                    'is_enabled' => true
                ]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function skills()
    {
        return $this->hasMany(AstrologerSkill::class);
    }

    public function languages()
    {
        return $this->hasMany(AstrologerLanguage::class);
    }

    public function availability()
    {
        return $this->hasMany(AstrologerAvailability::class);
    }

    public function pricing()
    {
        return $this->hasMany(AstrologerPricing::class);
    }

    public function documents()
    {
        return $this->hasMany(AstrologerDocument::class);
    }

    public function bankDetails()
    {
        return $this->hasMany(AstrologerBankDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(AstrologerReview::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'astrologer_service')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }

    /**
     * Recalculate and update the astrologer's average rating.
     */
    public function updateAverageRating()
    {
        $avg = $this->reviews()->avg('rating');
        $this->total_rating = $avg ?: 0;
        $this->save();
    }
}
