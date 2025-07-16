<?php

namespace App\Laratables;

use App\Models\Product;

class ProductLaratables
{
    public static function laratablesQueryConditions($query)
    {
        return $query->select(['id', 'name', 'slug', 'price', 'offer_percentage', 'rating', 'stock', 'is_active', 'image'])->orderBy('id', 'desc');
    }

    public static function laratablesCustomImage($product)
    {
        if ($product->image) {
            return '<img src="' . $product->image . '" alt="Product" style="max-height:40px; max-width:80px; object-fit: cover;">';
        }
        return '<span class="text-muted">No image</span>';
    }

    public static function laratablesCustomIsActive($product)
    {
        return $product->is_active
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    public static function laratablesCustomOfferPercentage($product)
    {
        if ($product->offer_percentage > 0) {
            return '<span class="badge bg-warning">' . $product->offer_percentage . '% OFF</span>';
        }
        return '<span class="text-muted">No offer</span>';
    }

    public static function laratablesCustomRating($product)
    {
        if ($product->rating > 0) {
            $stars = str_repeat('★', floor($product->rating)) . str_repeat('☆', 5 - floor($product->rating));
            return '<span class="text-warning">' . $stars . '</span> <small>(' . $product->rating . ')</small>';
        }
        return '<span class="text-muted">No rating</span>';
    }

    public static function laratablesCustomActions($product)
    {
        return view('admin.products.actions', compact('product'))->render();
    }

    public static function laratablesSearchableColumns()
    {
        return ['name', 'slug'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'name', 'slug', 'price', 'offer_percentage', 'rating', 'stock', 'is_active'];
    }

    public static function laratablesColumns()
    {
        return ['id', 'name', 'slug', 'price', 'offer_percentage', 'rating', 'stock', 'is_active', 'image', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['is_active', 'offer_percentage', 'rating', 'image', 'actions'];
    }
}
