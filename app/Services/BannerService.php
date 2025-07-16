<?php

namespace App\Services;

use App\Models\Banner;

class BannerService
{
    /**
     * Get active banners with astrologer relationships
     *
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBannersWithAstrologer($limit = null)
    {
        $query = Banner::active()
            ->with([
                'astrologer.user',
                'astrologer.skills.category',
                'astrologer.pricing.service',
            ])
            ->orderBy('sort_order');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
