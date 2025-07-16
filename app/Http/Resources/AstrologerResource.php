<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'about_me' => $this->about_me,
            'experience_years' => $this->experience_years,
            'total_rating' => $this->total_rating,
            'status' => $this->status,
            'is_online' => $this->is_online,
            'is_fake' => $this->is_fake,
            'is_test' => $this->is_test,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,

            // User information using UserResource
            'user' => $this->when($this->user, function () {
                return new UserResource($this->user);
            }),

            // Wallet information using general WalletResource
            'wallet' => $this->when($this->wallet, function () {
                return new WalletResource($this->wallet);
            }),

            // Skills using separate resource
            'skills' => $this->when($this->skills, function () {
                return AstrologerSkillResource::collection($this->skills);
            }),

            // Languages using separate resource
            'languages' => $this->when($this->languages, function () {
                return AstrologerLanguageResource::collection($this->languages);
            }),

            // Availability using separate resource
            'availability' => $this->when($this->availability, function () {
                return AstrologerAvailabilityResource::collection($this->availability);
            }),

            // Pricing using separate resource
            'pricing' => $this->when($this->pricing, function () {
                return AstrologerPricingResource::collection($this->pricing);
            }),

            // Documents using separate resource
            'documents' => $this->when($this->documents, function () {
                return AstrologerDocumentResource::collection($this->documents);
            }),

            // Bank details using separate resource
            'bank_details' => $this->when($this->bankDetails, function () {
                return AstrologerBankDetailResource::collection($this->bankDetails);
            }),

            // Reviews using separate resource
            'reviews' => $this->when($this->reviews, function () {
                return AstrologerReviewResource::collection($this->reviews);
            }),
        ];
    }
}
