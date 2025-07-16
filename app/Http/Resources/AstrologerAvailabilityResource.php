<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerAvailabilityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'astrologer_id' => $this->astrologer_id,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time ? $this->start_time->format('H:i:s') : null,
            'end_time' => $this->end_time ? $this->end_time->format('H:i:s') : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
