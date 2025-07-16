<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Get active products with pagination
     */
    public function getActiveProducts(int $limit = 20, string $orderBy = 'created_at', string $orderDirection = 'desc'): LengthAwarePaginator
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            }])
            ->orderBy($orderBy, $orderDirection)
            ->paginate($limit);
    }

    /**
     * Get active products for home screen (limited)
     */
    public function getHomeProducts(int $limit = 10): Collection
    {
        return Product::active()
            // ->with(['images' => function ($query) {
            //     $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            // }])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get single product by ID
     */
    public function getProductById(int $id): ?Product
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            }])
            ->find($id);
    }

    /**
     * Search products by name or description
     */
    public function searchProducts(string $query, int $limit = 20): LengthAwarePaginator
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            }])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get products by price range
     */
    public function getProductsByPriceRange(float $minPrice, float $maxPrice, int $limit = 20): LengthAwarePaginator
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            }])
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->orderBy('price', 'asc')
            ->paginate($limit);
    }

    /**
     * Get products in stock
     */
    public function getInStockProducts(int $limit = 20): LengthAwarePaginator
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
            }])
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get product gallery images
     */
    public function getProductGallery(int $productId): Collection
    {
        return ProductImage::where('product_id', $productId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get primary image for a product
     */
    public function getProductPrimaryImage(int $productId): ?ProductImage
    {
        return ProductImage::where('product_id', $productId)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Add image to product gallery
     */
    public function addProductImage(int $productId, string $imagePath, bool $isPrimary = false, int $sortOrder = 0): ProductImage
    {
        // If this is a primary image, unset other primary images
        if ($isPrimary) {
            ProductImage::where('product_id', $productId)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        return ProductImage::create([
            'product_id' => $productId,
            'image_path' => $imagePath,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
        ]);
    }

    /**
     * Update product image
     */
    public function updateProductImage(int $imageId, array $data): bool
    {
        $image = ProductImage::find($imageId);

        if (!$image) {
            return false;
        }

        // If setting as primary, unset other primary images for this product
        if (isset($data['is_primary']) && $data['is_primary']) {
            ProductImage::where('product_id', $image->product_id)
                ->where('id', '!=', $imageId)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        return $image->update($data);
    }

    /**
     * Delete product image
     */
    public function deleteProductImage(int $imageId): bool
    {
        $image = ProductImage::find($imageId);

        if (!$image) {
            return false;
        }

        // Delete the actual image file from storage
        if (Storage::exists($image->image_path)) {
            Storage::delete($image->image_path);
        }

        return $image->delete();
    }

    /**
     * Reorder product images
     */
    public function reorderProductImages(int $productId, array $imageIds): bool
    {
        foreach ($imageIds as $index => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $productId)
                ->update(['sort_order' => $index]);
        }

        return true;
    }

    /**
     * Set primary image for product
     */
    public function setPrimaryImage(int $productId, int $imageId): bool
    {
        // Unset all primary images for this product
        ProductImage::where('product_id', $productId)
            ->update(['is_primary' => false]);

        // Set the new primary image
        return ProductImage::where('id', $imageId)
            ->where('product_id', $productId)
            ->update(['is_primary' => true]);
    }

    /**
     * Get products with primary images only
     */
    public function getProductsWithPrimaryImages(int $limit = 20): LengthAwarePaginator
    {
        return Product::active()
            ->with(['images' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get products with gallery count
     */
    public function getProductsWithGalleryCount(int $limit = 20): LengthAwarePaginator
    {
        return Product::active()
            ->withCount('images')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
