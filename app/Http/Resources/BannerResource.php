<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AstrologerResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'cta_text' => $this->cta_text,
            'cta_url' => $this->cta_url,
            'type' => $this->type,
            'image' => $this->image,
            'show_on' => $this->show_on,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d H:i:s') : null,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d H:i:s') : null,
            'astrologer_id' => $this->astrologer_id,
            'astrologer_details' => $this->when($this->astrologer, function () {
                return new AstrologerResource($this->astrologer);
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
