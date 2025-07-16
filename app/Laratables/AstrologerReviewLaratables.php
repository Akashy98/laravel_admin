<?php

namespace App\Laratables;

use App\Models\AstrologerReview;

class AstrologerReviewLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->with(['astrologer.user', 'user'])->select(['id', 'astrologer_id', 'user_id', 'rating', 'review', 'created_at']);
    }

    public static function laratablesCustomAstrologer($review)
    {
        return $review->astrologer && $review->astrologer->user
            ? $review->astrologer->user->name
            : '-';
    }

    public static function laratablesCustomUser($review)
    {
        return $review->user ? $review->user->name : '-';
    }

    public static function laratablesCustomActions($review)
    {
        return view('admin.astrologer_reviews.actions', compact('review'))->render();
    }

    public static function laratablesSearchableColumns()
    {
        return ['review', 'rating'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'astrologer_id', 'user_id', 'rating', 'created_at'];
    }

    public static function laratablesColumns()
    {
        return ['id', 'astrologer', 'user', 'rating', 'review', 'created_at', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['actions'];
    }
}
