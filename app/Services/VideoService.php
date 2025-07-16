<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    /**
     * Get videos for home screen
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHomeVideos($limit = 10)
    {
        return Video::active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($video) {
                return $this->formatVideo($video);
            });
    }

    /**
     * Format video data for API response
     *
     * @param Video $video
     * @return array
     */
    private function formatVideo($video)
    {
        return [
            'id' => $video->id,
            'title' => $video->title,
            'description' => $video->description,
            'video_url' => $video->video_url,
            'video_file' => $video->video_file ? Storage::url($video->video_file) : null,
            'thumbnail' => $video->thumbnail ? Storage::url($video->thumbnail) : null,
            'views_count' => $video->views_count,
            'sort_order' => $video->sort_order,
            'created_at' => $video->created_at,
            'updated_at' => $video->updated_at,
        ];
    }
}
