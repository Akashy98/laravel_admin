<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'chat_astrologers' => $this['chat_astrologers'],
            'call_astrologers' => $this['call_astrologers'],
            'products' => $this['products'],
            'our_videos' => $this['our_videos'],
            'banners' => $this['banners'],
        ];
    }
}
