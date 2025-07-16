<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AstrologerBankDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'astrologer_id' => $this->astrologer_id,
            'account_holder_name' => $this->account_holder_name,
            'account_number' => $this->account_number,
            'ifsc_code' => $this->ifsc_code,
            'bank_name' => $this->bank_name,
            'upi_id' => $this->upi_id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
