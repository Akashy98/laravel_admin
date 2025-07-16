<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletOffer extends Model
{
    protected $fillable = [
        'amount', 'extra_percent', 'is_popular', 'label', 'status', 'sort_order'
    ];
}
