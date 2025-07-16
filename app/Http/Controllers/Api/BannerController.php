<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Banner;
use App\Models\Astrologer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends BaseController
{
    /**
     * Get active banners
     */
    public function index(Request $request)
    {
        try {
            $showOn = $request->get('show_on', 'home');

            $banners = Banner::active()
                ->where('show_on', $showOn)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'desc')
                ->get();

            $formattedBanners = $banners->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle,
                    'description' => $banner->description,
                    'cta_text' => $banner->cta_text,
                    'cta_url' => $banner->cta_url,
                    'type' => $banner->type,
                    'image' => $banner->image,
                    'astrologer_id' => $banner->astrologer_id,
                    'astrologer' => $banner->astrologer ? [
                        'id' => $banner->astrologer->id,
                        'name' => $banner->astrologer->user->name,
                        'specialization' => $banner->astrologer->skills->first() ? $banner->astrologer->skills->first()->category->name : 'General',
                        'experience' => $banner->astrologer->experience_years,
                        'rating' => $banner->astrologer->total_rating,
                        'profile_image' => $banner->astrologer->user->profile_image,
                    ] : null,
                ];
            });

            return $this->successResponse($formattedBanners, 'Banners retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving banners', $e->getMessage());
        }
    }

    /**
     * Handle banner click and initiate call/chat session
     */
    public function click(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'banner_id' => 'required|exists:banners,id',
                'session_type' => 'required|in:call,chat',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $banner = Banner::with(['astrologer.user', 'astrologer.skills.category'])->find($request->banner_id);

            if (!$banner) {
                return $this->notFoundResponse('Banner not found');
            }

            if (!$banner->astrologer) {
                return $this->errorResponse('No astrologer assigned to this banner');
            }

            // Check if astrologer is available
            if ($banner->astrologer->status !== 'approved') {
                return $this->errorResponse('Astrologer is not available at the moment');
            }

            // Here you would typically:
            // 1. Create a session/booking record
            // 2. Send notification to astrologer
            // 3. Return session details

            $sessionData = [
                'banner_id' => $banner->id,
                'astrologer_id' => $banner->astrologer->id,
                'user_id' => $request->user_id,
                'session_type' => $request->session_type,
                'session_id' => 'SESS_' . time() . '_' . rand(1000, 9999),
                'status' => 'initiated',
                'astrologer' => [
                    'id' => $banner->astrologer->id,
                    'name' => $banner->astrologer->user->name,
                    'specialization' => $banner->astrologer->skills->first() ? $banner->astrologer->skills->first()->category->name : 'General',
                    'experience' => $banner->astrologer->experience_years,
                    'rating' => $banner->astrologer->total_rating,
                    'profile_image' => $banner->astrologer->user->profile_image,
                ]
            ];

            return $this->successResponse($sessionData, 'Session initiated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error initiating session', $e->getMessage());
        }
    }

    /**
     * Get banner details
     */
    public function show($id)
    {
        try {
            $banner = Banner::with(['astrologer.user', 'astrologer.skills.category'])->find($id);

            if (!$banner) {
                return $this->notFoundResponse('Banner not found');
            }

            $bannerData = [
                'id' => $banner->id,
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'description' => $banner->description,
                'cta_text' => $banner->cta_text,
                'cta_url' => $banner->cta_url,
                'type' => $banner->type,
                'image' => $banner->image,
                'astrologer_id' => $banner->astrologer_id,
                'astrologer' => $banner->astrologer ? [
                    'id' => $banner->astrologer->id,
                    'name' => $banner->astrologer->user->name,
                    'specialization' => $banner->astrologer->skills->first() ? $banner->astrologer->skills->first()->category->name : 'General',
                    'experience' => $banner->astrologer->experience_years,
                    'rating' => $banner->astrologer->total_rating,
                    'profile_image' => $banner->astrologer->user->profile_image,
                ] : null,
            ];

            return $this->successResponse($bannerData, 'Banner details retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving banner details', $e->getMessage());
        }
    }
}
