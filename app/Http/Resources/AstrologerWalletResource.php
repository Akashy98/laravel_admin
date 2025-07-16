<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerWalletResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'owner_type' => $this->owner_type,
            'balance' => $this->balance,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'transactions' => $this->when($this->transactions, function () {
                return WalletTransactionResource::collection($this->transactions);
            }),
        ];
    }
}
