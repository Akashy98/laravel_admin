<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerDocument extends Model
{
    protected $fillable = [
        'astrologer_id', 'document_type', 'document_url', 'status'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }
}
