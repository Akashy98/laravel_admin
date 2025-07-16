<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'video_file',
        'thumbnail',
        'views_count',
        'is_active',
        'sort_order',
    ];

    /**
     * Scope to get only active videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
