<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerWalletTransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'description' => $this->description,
            'meta' => $this->meta ? json_decode($this->meta, true) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
