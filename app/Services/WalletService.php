<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletOffer;
use Illuminate\Database\Eloquent\Model;

class WalletService
{
    public function credit(Model $owner, $amount, $description = null, $meta = null)
    {
        $wallet = $owner->wallet ?: $owner->wallet()->create(['balance' => 0]);
        $wallet->balance += $amount;
        $wallet->save();
        return $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
            'meta' => $meta ? json_encode($meta) : null,
        ]);
    }

    public function debit(Model $owner, $amount, $description = null, $meta = null)
    {
        $wallet = $owner->wallet;
        if (!$wallet || $wallet->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }
        $wallet->balance -= $amount;
        $wallet->save();
        return $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'description' => $description,
            'meta' => $meta ? json_encode($meta) : null,
        ]);
    }

    public function applyOffer($amount)
    {
        $offer = WalletOffer::where('amount', $amount)->first();
        if ($offer) {
            $bonus = ($amount * $offer->extra_percent) / 100;
            return [
                'bonus' => $bonus,
                'is_popular' => $offer->is_popular,
                'offer' => $offer,
            ];
        }
        return ['bonus' => 0, 'is_popular' => false, 'offer' => null];
    }
}
