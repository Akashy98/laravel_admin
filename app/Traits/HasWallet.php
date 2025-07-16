<?php

namespace App\Traits;

use App\Models\Wallet;

trait HasWallet
{
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    public function getWalletBalanceAttribute()
    {
        return $this->wallet ? $this->wallet->balance : 0;
    }

    public function creditWallet($amount, $description = null, $meta = null)
    {
        return app('App\Services\WalletService')->credit($this, $amount, $description, $meta);
    }

    public function debitWallet($amount, $description = null, $meta = null)
    {
        return app('App\Services\WalletService')->debit($this, $amount, $description, $meta);
    }
}
