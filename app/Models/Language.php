<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name', 'code', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function astrologerLanguages()
    {
        return $this->hasMany(AstrologerLanguage::class);
    }
}
