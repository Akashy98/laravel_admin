<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerPricingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'astrologer_id' => $this->astrologer_id,
            'service_id' => $this->service_id,
            'price_per_minute' => $this->price_per_minute,
            'offer_price' => $this->offer_price,
            'is_active' => $this->is_active,
            'service' => $this->when($this->service, function () {
                return new ServiceResource($this->service);
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
