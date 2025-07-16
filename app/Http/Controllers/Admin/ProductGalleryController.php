<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\AzureStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductGalleryController extends Controller
{
    protected $azureStorageService;

    public function __construct(AzureStorageService $azureStorageService)
    {
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Display the gallery for a specific product
     */
    public function index(Product $product)
    {
        $images = $product->images()->orderBy('sort_order', 'asc')->get();

        return view('admin.products.gallery.index', compact('product', 'images'));
    }

    /**
     * Upload gallery images for a product
     */
    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedImages = [];

            foreach ($request->file('images') as $image) {
                // Upload to Azure
                $azurePath = 'products/gallery/' . $product->id . '/' . time() . '_' . $image->getClientOriginalName();
                $azureResult = $this->azureStorageService->uploadFile($image, $azurePath);

                // Save to database
                $productImage = $product->images()->create([
                    'image_path' => $azureResult['file_url'],
                    'sort_order' => $product->images()->count() + 1
                ]);

                $uploadedImages[] = [
                    'id' => $productImage->id,
                    'url' => $azureResult['file_url'],
                    'thumbnail_url' => $azureResult['file_url'] // You can generate thumbnail if needed
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'images' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

        /**
     * Delete a gallery image
     */
    public function destroy(Product $product, ProductImage $image)
    {
        try {
            // Delete from Azure (extract path from URL)
            $azurePath = $this->extractBlobPathFromUrl($image->image_path);
            if ($azurePath) {
                $this->azureStorageService->deleteFile($azurePath);
            }

            // Delete from database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract blob path from Azure URL
     */
    private function extractBlobPathFromUrl(string $url): ?string
    {
        // Extract the path after the container URL
        $containerUrl = \App\Constants\AzureConstants::getContainerUrl();
        if (str_starts_with($url, $containerUrl)) {
            return substr($url, strlen($containerUrl) + 1); // +1 for the slash
        }
        return null;
    }

    /**
     * Get gallery images for AJAX requests
     */
    public function getImages(Product $product)
    {
        $images = $product->images()->orderBy('sort_order', 'asc')->get();

        return response()->json([
            'success' => true,
            'images' => $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->image_path,
                    'thumbnail_url' => $image->image_path
                ];
            })
        ]);
    }
}
