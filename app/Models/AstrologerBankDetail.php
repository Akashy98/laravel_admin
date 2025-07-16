<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerBankDetail extends Model
{
    protected $fillable = [
        'astrologer_id', 'account_holder_name', 'account_number', 'ifsc_code', 'bank_name', 'upi_id'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }
}
