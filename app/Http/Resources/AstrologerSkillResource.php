<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerSkillResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'astrologer_id' => $this->astrologer_id,
            'category_id' => $this->category_id,
            'category' => $this->when($this->category, function () {
                return new CategoryResource($this->category);
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
