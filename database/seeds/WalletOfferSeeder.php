<?php

use Illuminate\Database\Seeder;
use App\Models\WalletOffer;

class WalletOfferSeeder extends Seeder
{
    public function run()
    {
        $offers = [
            [ 'amount' => 50, 'extra_percent' => 100, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 1 ],
            [ 'amount' => 100, 'extra_percent' => 100, 'label' => 'Most Popular', 'is_popular' => true, 'status' => 'active', 'sort_order' => 2 ],
            [ 'amount' => 200, 'extra_percent' => 50, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 3 ],
            [ 'amount' => 500, 'extra_percent' => 50, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 4 ],
            [ 'amount' => 1000, 'extra_percent' => 5, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 5 ],
            [ 'amount' => 2000, 'extra_percent' => 10, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 6 ],
            [ 'amount' => 3000, 'extra_percent' => 10, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 7 ],
            [ 'amount' => 4000, 'extra_percent' => 12, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 8 ],
            [ 'amount' => 8000, 'extra_percent' => 12, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 9 ],
            [ 'amount' => 15000, 'extra_percent' => 15, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 10 ],
            [ 'amount' => 20000, 'extra_percent' => 15, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 11 ],
            [ 'amount' => 50000, 'extra_percent' => 20, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 12 ],
            [ 'amount' => 100000, 'extra_percent' => 20, 'label' => null, 'is_popular' => false, 'status' => 'active', 'sort_order' => 13 ],
        ];
        foreach ($offers as $offer) {
            WalletOffer::create($offer);
        }
    }
}
