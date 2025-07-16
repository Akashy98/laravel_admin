<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerSkill extends Model
{
    protected $fillable = [
        'astrologer_id', 'category_id'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function category()
    {
        return $this->belongsTo(AstrologerCategory::class, 'category_id');
    }
}
