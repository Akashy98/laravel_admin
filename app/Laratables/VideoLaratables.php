<?php

namespace App\Laratables;

use App\Models\Video;

class VideoLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->select(['id', 'title', 'thumbnail', 'video_url', 'video_file', 'is_active', 'sort_order']);
    }

    public static function laratablesCustomThumbnail($video)
    {
        if ($video->thumbnail) {
            return '<img src="' . asset('storage/' . $video->thumbnail) . '" alt="Thumbnail" style="max-width:80px; max-height:40px; object-fit:cover;">';
        }
        return '<span class="text-muted">No image</span>';
    }

    public static function laratablesCustomType($video)
    {
        if ($video->video_url) {
            return '<span class="badge bg-info">URL</span>';
        } elseif ($video->video_file) {
            return '<span class="badge bg-success">File</span>';
        }
        return '<span class="badge bg-secondary">N/A</span>';
    }

    public static function laratablesCustomIsActive($video)
    {
        return $video->is_active
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    public static function laratablesCustomActions($video)
    {
        return view('admin.videos.actions', compact('video'))->render();
    }

    public static function laratablesColumns()
    {
        return ['id', 'thumbnail', 'title', 'type', 'is_active', 'sort_order', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['thumbnail', 'type', 'is_active', 'actions'];
    }
}
