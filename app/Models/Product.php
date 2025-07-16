<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;

class Product extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'offer_percentage',
        'rating',
        'stock',
        'image',
        'is_active',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
