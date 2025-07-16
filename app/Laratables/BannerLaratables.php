<?php

namespace App\Laratables;

use App\Models\Banner;

class BannerLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->select(['id', 'title', 'subtitle', 'description', 'type', 'status', 'sort_order', 'show_on', 'image', 'start_date', 'end_date', 'astrologer_id'])
            ->with(['astrologer.user', 'astrologer.skills.category'])
            ->orderBy('id', 'desc');
    }

    public static function laratablesCustomImage($banner)
    {
        if ($banner->image) {
            return '<img src="' . $banner->image . '" alt="Banner" style="max-height:40px; max-width:80px; object-fit: cover;">';
        }
        return '<span class="text-muted">No image</span>';
    }

    public static function laratablesCustomStatus($offer)
    {
        return $offer->status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    public static function laratablesCustomAstrologer($banner)
    {
        if ($banner->astrologer && $banner->astrologer->user) {
            $specialization = $banner->astrologer->skills->first() ? $banner->astrologer->skills->first()->category->name : 'General';
            return '<span class="badge bg-info">' . $banner->astrologer->user->name . ' (' . $specialization . ')</span>';
        }
        return '<span class="text-muted">Not assigned</span>';
    }

    public static function laratablesCustomActions($banner)
    {
        return view('admin.banners.actions', compact('banner'))->render();
    }

    public static function laratablesSearchableColumns()
    {
        return ['title', 'subtitle', 'description', 'type', 'show_on'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'title', 'subtitle', 'description', 'type', 'status', 'sort_order', 'show_on', 'start_date', 'end_date', 'astrologer_id'];
    }

    public static function laratablesColumns()
    {
        return ['title', 'subtitle', 'description', 'type', 'status', 'sort_order', 'show_on', 'start_date', 'end_date', 'astrologer', 'image', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['type', 'image', 'actions', 'status', 'start_date', 'end_date', 'astrologer'];
    }

    public static function laratablesCustomType($banner)
    {
        if ($banner->type === 'card') {
            return '<span class="badge bg-primary">Card</span>';
        } elseif ($banner->type === 'popup') {
            return '<span class="badge bg-purple" style="background:#6f42c1;">Popup</span>';
        }
        return $banner->type;
    }

    public static function laratablesCustomStartDate($banner)
    {
        if ($banner->start_date) {
            return $banner->start_date->format('M d, Y H:i');
        }
        return '<span class="text-muted">Not set</span>';
    }

    public static function laratablesCustomEndDate($banner)
    {
        if ($banner->end_date) {
            return $banner->end_date->format('M d, Y H:i');
        }
        return '<span class="text-muted">Not set</span>';
    }
}
