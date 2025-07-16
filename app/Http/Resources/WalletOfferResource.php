<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $bonusAmount = ($this->amount * $this->extra_percent) / 100;
        $totalAmount = $this->amount + $bonusAmount;

        return [
            'id' => $this->id,
            'amount' => (float) $this->amount,
            'extra_percent' => (int) $this->extra_percent,
            'bonus_amount' => (float) $bonusAmount,
            'total_amount' => (float) $totalAmount,
            'is_popular' => (bool) $this->is_popular,
            'label' => $this->label,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'description' => "Get {$this->extra_percent}% extra on ₹{$this->amount} recharge",
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }
}
