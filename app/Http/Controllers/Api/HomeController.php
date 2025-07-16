<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Services\AstrologerService;
use App\Services\ProductService;
use App\Services\VideoService;
use App\Services\BannerService;
use App\Http\Resources\HomeResource;
use App\Http\Resources\AstrologerResource;
use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    protected $astrologerService;
    protected $productService;
    protected $videoService;
    protected $bannerService;

    public function __construct(AstrologerService $astrologerService, ProductService $productService, VideoService $videoService, BannerService $bannerService)
    {
        $this->astrologerService = $astrologerService;
        $this->productService = $productService;
        $this->videoService = $videoService;
        $this->bannerService = $bannerService;
    }

    /**
     * Home screen API - Resource-based version
     */
    public function home(Request $request)
    {
        $limit = $request->get('limit', 5);

        $chatAstrologers = $this->astrologerService->getAstrologersByService($this->convertServiceSlugToName('chat'), $limit, false, 'total_rating', 'desc');
        $callAstrologers = $this->astrologerService->getAstrologersByService($this->convertServiceSlugToName('call'), $limit, false, 'total_rating', 'desc');

        // Use AstrologerResource for formatting
        $chatAstrologersResource = AstrologerResource::collection($chatAstrologers);
        $callAstrologersResource = AstrologerResource::collection($callAstrologers);

        // Get banners using BannerService
        $banners = $this->bannerService->getActiveBannersWithAstrologer();
        $bannersResource = BannerResource::collection($banners);

        $products = $this->productService->getHomeProducts(10);
        $videos = $this->videoService->getHomeVideos(10);

        $data = [
            'chat_astrologers' => $chatAstrologersResource,
            'call_astrologers' => $callAstrologersResource,
            'products' => $products,
            'our_videos' => $videos,
            'banners' => $bannersResource,
        ];

        $homeResource = new HomeResource($data);
        return $this->successResponse($homeResource, 'Home data retrieved successfully');
    }

    /**
     * Get astrologers by service with pagination
     *
     * @param Request $request
     * @param string $request->service - Service slug (chat, call, etc.)
     * @param int $request->limit - Number of items per page (default: 5)
     * @param int $request->per_page - Alternative to limit parameter
     * @param int $request->page - Page number (default: 1)
     *
     * @return JsonResponse
     */
    public function getAstrologers(Request $request)
    {
        $limit = $request->get('limit', $request->get('per_page', 5));
        $page = $request->get('page', 1);
        $serviceSlug = $request->get('service');

        // Validate and convert slug to service name
        $validation = $this->validateServiceSlug($serviceSlug);

        if (!$validation['valid']) {
            return $this->errorResponse($validation['error']);
        }

        $astrologers = $this->astrologerService->getAstrologersByService($validation['service_name'], $limit, true, 'total_rating', 'desc');

        // Format the response with pagination information
        $data = [
            'astrologers' => AstrologerResource::collection($astrologers),
            'pagination' => [
                'current_page'    => $astrologers->currentPage(),
                'last_page'       => $astrologers->lastPage(),
                'per_page'        => $astrologers->perPage(),
                'total'           => $astrologers->total(),
                'from'            => $astrologers->firstItem(),
                'to'              => $astrologers->lastItem(),
                'next_page_url'   => $astrologers->nextPageUrl(),
                'prev_page_url'   => $astrologers->previousPageUrl(),
                'first_page_url'  => $astrologers->url(1),
                'last_page_url'   => $astrologers->url($astrologers->lastPage()),
                'path'            => $astrologers->path(),
            ]
        ];

        return $this->successResponse($data, 'Astrologers retrieved successfully');
    }
}
