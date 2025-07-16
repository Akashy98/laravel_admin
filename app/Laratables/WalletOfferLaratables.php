<?php

namespace App\Laratables;

use App\Models\WalletOffer;

class WalletOfferLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->select(['id', 'amount', 'extra_percent', 'label', 'is_popular', 'status', 'sort_order'])->orderBy('id', 'desc');
    }

    public static function laratablesCustomStatus($offer)
    {
        return $offer->status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    public static function laratablesCustomPopular($offer)
    {
        return $offer->is_popular
            ? '<span class="badge bg-success">Most Popular</span>'
            : '<span class="text-muted">-</span>';
    }

    public static function laratablesCustomIsPopular($offer)
    {
        if ($offer->is_popular) {
            return '<span class="badge" style="background: #ffd700; color: #222;" title="Most Popular"><i class=\"fas fa-star\"></i> ★ Popular</span>';
        } else {
            return '<span class="badge bg-secondary" title="Not Popular">—</span>';
        }
    }

    public static function laratablesCustomActions($offer)
    {
        return view('admin.wallet_offers.actions', compact('offer'))->render();
    }

    public static function laratablesSearchableColumns()
    {
        return ['amount', 'label'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'amount', 'extra_percent', 'label', 'is_popular', 'status', 'sort_order'];
    }

    public static function laratablesColumns()
    {
        return ['amount', 'extra_percent', 'label', 'is_popular', 'status', 'sort_order', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['is_popular', 'status', 'actions'];
    }
}
