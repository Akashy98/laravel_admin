<?php

namespace App\Services;

use App\Models\Astrologer;
use App\Models\AstrologerSkill;
use App\Models\AstrologerLanguage;
use App\Models\AstrologerAvailability;
use App\Models\AstrologerPricing;
use App\Models\AstrologerDocument;
use App\Models\AstrologerBankDetail;
use App\Models\AstrologerReview;
use App\Models\AstrologerService as AstrologerServiceModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AstrologerService
{
    // Astrologer CRUD
    public function getAll($with = []) {
        return Astrologer::with($with)->paginate(20);
    }

    public function find($id, $with = []) {
        return Astrologer::with($with)->findOrFail($id);
    }

    public function create(array $data) {
        return Astrologer::create($data);
    }

    public function update(Astrologer $astrologer, array $data) {
        $astrologer->update($data);
        return $astrologer;
    }

    public function delete(Astrologer $astrologer) {
        return $astrologer->delete();
    }

    // Skills CRUD
    public function addSkill($astrologerId, $categoryId) {
        return AstrologerSkill::create([
            'astrologer_id' => $astrologerId,
            'category_id' => $categoryId,
        ]);
    }

    public function removeSkill($skillId) {
        return AstrologerSkill::destroy($skillId);
    }

    // Languages CRUD
    public function addLanguage($astrologerId, $languageId) {
        return AstrologerLanguage::create([
            'astrologer_id' => $astrologerId,
            'language_id' => $languageId,
        ]);
    }

    public function removeLanguage($languageId) {
        return AstrologerLanguage::destroy($languageId);
    }

    // Availability CRUD
    public function addAvailability($astrologerId, $dayOfWeek, $startTime, $endTime) {
        return AstrologerAvailability::create([
            'astrologer_id' => $astrologerId,
            'day_of_week' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    public function removeAvailability($availabilityId) {
        return AstrologerAvailability::destroy($availabilityId);
    }

    // Pricing CRUD
    public function addPricing($astrologerId, $serviceId, $pricePerMinute, $offerPrice = null) {
        return AstrologerPricing::create([
            'astrologer_id' => $astrologerId,
            'service_id' => $serviceId,
            'price_per_minute' => $pricePerMinute,
            'offer_price' => $offerPrice,
        ]);
    }

    public function updatePricing($pricingId, $pricePerMinute, $offerPrice = null) {
        $pricing = AstrologerPricing::findOrFail($pricingId);
        $pricing->update([
            'price_per_minute' => $pricePerMinute,
            'offer_price' => $offerPrice,
        ]);
        return $pricing;
    }

    public function removePricing($pricingId) {
        return AstrologerPricing::destroy($pricingId);
    }

    // Documents CRUD
    public function addDocument($astrologerId, $type, $url, $status = 'pending') {
        return AstrologerDocument::create([
            'astrologer_id' => $astrologerId,
            'document_type' => $type,
            'document_url' => $url,
            'status' => $status,
        ]);
    }

    public function removeDocument($documentId) {
        return AstrologerDocument::destroy($documentId);
    }

    // Bank Details CRUD
    public function addOrUpdateBankDetail($astrologerId, array $data) {
        return AstrologerBankDetail::updateOrCreate(
            ['astrologer_id' => $astrologerId],
            $data
        );
    }

    // Reviews (read only)
    public function getReviews($astrologerId) {
        return AstrologerReview::where('astrologer_id', $astrologerId)->with('user')->get();
    }

    /**
     * Add or update a review for an astrologer by a user.
     */
    public function addOrUpdateReview($astrologerId, $userId, $rating, $reviewText)
    {
        $review = AstrologerReview::updateOrCreate(
            [
                'astrologer_id' => $astrologerId,
                'user_id' => $userId,
            ],
            [
                'rating' => $rating,
                'review' => $reviewText,
            ]
        );
        return $review->fresh(['user', 'astrologer']);
    }

    public function forceDeleteAstrologer(Astrologer $astrologer)
    {
        try {
            // Delete related data
            $astrologer->skills()->delete();
            $astrologer->languages()->delete();
            $astrologer->availability()->delete();
            $astrologer->pricing()->delete();
            $astrologer->documents()->delete();
            $astrologer->bankDetails()->delete();
            $astrologer->reviews()->delete();
            $astrologer->services()->detach();
            // Delete related user (and wallet, etc.)
            if ($astrologer->user) {
                if (method_exists($astrologer->user, 'wallet')) {
                    $wallet = $astrologer->user->wallet;
                    if ($wallet) {
                        if (method_exists($wallet, 'transactions')) {
                            $wallet->transactions()->delete();
                        }
                        $wallet->delete();
                    }
                }
                $astrologer->user->delete();
            }
            // Force delete the astrologer
            $astrologer->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error('Astrologer force deletion failed: ' . $e->getMessage(), ['astrologer_id' => $astrologer->id]);
            throw $e;
        }
    }

    /**
     * Optimized astrologer list for home screen
     */
    public function getHomeList($perPage = 10)
    {
        $query = Astrologer::with([
            'user' => function($q) {
                $q->select('id', 'name', 'first_name', 'last_name', 'profile_image', 'status');
            },
            'skills.category',
            'languages.language',
            'pricing.service',
            'reviews',
        ])
        ->where('status', 'approved')
        ->whereHas('user', function($q) {
            $q->where('status', 1);
        })
        ->select('id', 'user_id', 'about_me', 'experience_years', 'is_online', 'total_rating');

        return $query->paginate($perPage);
    }

    /**
     * Get astrologers by service name (e.g., Chat, Call)
     *
     * @param string $serviceName
     * @param int $perPage Number of results per page (for pagination) or limit (for limited results)
     * @param bool $paginate Whether to return paginated results or limited results
     * @param string $orderBy Order by field (default: 'total_rating')
     * @param string $orderDirection Order direction (default: 'desc')
     */
    public function getAstrologersByService($serviceName, $perPage = 10, $paginate = true, $orderBy = 'total_rating', $orderDirection = 'desc')
    {
        $query = Astrologer::with([
            'user' => function($q) {
                $q->select('id', 'name', 'first_name', 'last_name', 'profile_image', 'status');
            },
            'skills.category',
            'languages.language',
            'pricing' => function($q) use ($serviceName) {
                $q->whereHas('service', function($sq) use ($serviceName) {
                    $sq->where('name', $serviceName);
                });
            },
            'pricing.service',
            'reviews',
        ])
        ->where('status', 'approved')
        ->whereHas('user', function($q) {
            $q->where('status', 1);
        })
        ->whereHas('pricing.service', function($q) use ($serviceName) {
            $q->where('name', $serviceName);
        })
        ->whereHas('services', function($q) use ($serviceName) {
            $q->where('name', $serviceName)
              ->where('astrologer_service.is_enabled', true);
        })
        ->select('id', 'user_id', 'about_me', 'experience_years', 'is_online', 'total_rating')
        ->orderBy($orderBy, $orderDirection);

        if ($paginate) {
            return $query->paginate($perPage);
        } else {
            return $query->limit($perPage)->get();
        }
    }

    /**
     * Enable or disable a service for an astrologer
     */
    public function toggleService($astrologerId, $serviceId, $isEnabled = true)
    {
        return AstrologerServiceModel::updateOrCreate(
            [
                'astrologer_id' => $astrologerId,
                'service_id' => $serviceId,
            ],
            [
                'is_enabled' => $isEnabled
            ]
        );
    }

    /**
     * Get enabled services for an astrologer
     */
    public function getEnabledServices($astrologerId)
    {
        return AstrologerServiceModel::where('astrologer_id', $astrologerId)
            ->where('is_enabled', true)
            ->with('service')
            ->get();
    }

    /**
     * Get all services (enabled and disabled) for an astrologer
     */
    public function getAllServices($astrologerId)
    {
        return AstrologerServiceModel::where('astrologer_id', $astrologerId)
            ->with('service')
            ->get();
    }
}
