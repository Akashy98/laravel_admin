<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerLanguage extends Model
{
    protected $fillable = [
        'astrologer_id', 'language_id'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
