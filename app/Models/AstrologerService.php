<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerService extends Model
{
    protected $table = 'astrologer_service';

    protected $fillable = [
        'astrologer_id',
        'service_id',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
