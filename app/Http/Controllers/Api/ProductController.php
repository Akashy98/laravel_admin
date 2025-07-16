<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;

class ProductController extends BaseController
{
    protected $productService;

    /**
     * Validation rules for product search
     */
    public const SEARCH_RULES = [
        'query' => 'required|string|min:2',
        'limit' => 'nullable|integer|min:1|max:100'
    ];

    /**
     * Validation rules for price range filter
     */
    public const PRICE_RANGE_RULES = [
        'min_price' => 'required|numeric|min:0',
        'max_price' => 'required|numeric|min:0|gte:min_price',
        'limit' => 'nullable|integer|min:1|max:100'
    ];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products with pagination
     *
     * @param Request $request
     * @param int $request->limit - Number of items per page (default: 20)
     * @param int $request->per_page - Alternative to limit parameter
     * @param string $request->order_by - Order by field (default: created_at)
     * @param string $request->order_direction - Order direction (asc/desc, default: desc)
     * @param int $request->page - Page number (default: 1)
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', $request->get('per_page', 20));
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');

        $products = $this->productService->getActiveProducts($limit, $orderBy, $orderDirection);

        $data = [
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'path' => $products->path(),
            ]
        ];

        return $this->successResponse($data, 'Products retrieved successfully');
    }

    /**
     * Get single product by ID
     */
    public function show($id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return $this->errorResponse('Product not found', 404);
        }

        return $this->successResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    /**
     * Search products with pagination
     *
     * @param Request $request
     * @param string $request->query - Search query (required)
     * @param int $request->limit - Number of items per page (default: 20)
     * @param int $request->per_page - Alternative to limit parameter
     * @param int $request->page - Page number (default: 1)
     *
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate(self::SEARCH_RULES);

        $query = $request->get('query');
        $limit = $request->get('limit', $request->get('per_page', 20));

        $products = $this->productService->searchProducts($query, $limit);

        $data = [
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'path' => $products->path(),
            ]
        ];

        return $this->successResponse($data, 'Search results retrieved successfully');
    }

    /**
     * Get products by price range with pagination
     *
     * @param Request $request
     * @param float $request->min_price - Minimum price (required)
     * @param float $request->max_price - Maximum price (required)
     * @param int $request->limit - Number of items per page (default: 20)
     * @param int $request->per_page - Alternative to limit parameter
     * @param int $request->page - Page number (default: 1)
     *
     * @return JsonResponse
     */
    public function byPriceRange(Request $request)
    {
        $request->validate(self::PRICE_RANGE_RULES);

        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $limit = $request->get('limit', $request->get('per_page', 20));

        $products = $this->productService->getProductsByPriceRange($minPrice, $maxPrice, $limit);

        $data = [
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'path' => $products->path(),
            ]
        ];

        return $this->successResponse($data, 'Products filtered by price range');
    }

    /**
     * Get products in stock with pagination
     *
     * @param Request $request
     * @param int $request->limit - Number of items per page (default: 20)
     * @param int $request->per_page - Alternative to limit parameter
     * @param int $request->page - Page number (default: 1)
     *
     * @return JsonResponse
     */
    public function inStock(Request $request)
    {
        $limit = $request->get('limit', $request->get('per_page', 20));

        $products = $this->productService->getInStockProducts($limit);

        $data = [
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'path' => $products->path(),
            ]
        ];

        return $this->successResponse($data, 'In-stock products retrieved successfully');
    }
}
