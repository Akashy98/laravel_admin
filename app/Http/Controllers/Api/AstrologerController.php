<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Services\AstrologerService;
use App\Traits\SharedHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AstrologerResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\AstrologerPricing;
use App\Models\Service;
use App\Services\AzureStorageService;

class AstrologerController extends BaseController
{
    use SharedHelpers;

    protected $userService;
    protected $astrologerService;

    /**
     * Default country code for phone numbers
     */
    public const DEFAULT_COUNTRY_CODE = '+91';

    /**
     * Validation rules for astrologer login
     */
    public const LOGIN_RULES = [
        'phone' => 'required|string',
        'country_code' => 'nullable|string',
    ];

    /**
     * Validation rules for OTP operations
     */
    public const OTP_RULES = [
        'phone' => 'required|string',
        'country_code' => 'nullable|string',
    ];

    /**
     * Validation rules for OTP verification
     */
    public const OTP_VERIFY_RULES = [
        'phone' => 'required|string',
        'code' => 'required|string',
        'country_code' => 'nullable|string',
    ];

    /**
     * Validation rules for astrologer creation
     */
    public const CREATE_RULES = [
        'name' => 'nullable|string',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'required|string|unique:users,phone',
        'country_code' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'about_me' => 'nullable|string',
        'experience_years' => 'nullable|integer|min:0',
        'birth_date' => 'nullable|date',
        'birth_time' => 'nullable',
        'birth_place' => 'nullable|string',
        'country' => 'nullable|string',
        'state' => 'nullable|string',
        'city' => 'nullable|string',
        'address' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'birth_country' => 'nullable|string',
        'birth_state' => 'nullable|string',
        'birth_city' => 'nullable|string',
        'birth_latitude' => 'nullable|numeric',
        'birth_longitude' => 'nullable|numeric',
    ];

    /**
     * Validation rules for astrologer profile update
     */
    public const PROFILE_UPDATE_RULES = [
        'name' => 'nullable|string',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'nullable|string|unique:users,phone',
        'country_code' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'about_me' => 'nullable|string',
        'experience_years' => 'nullable|integer|min:0',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        'birth_date' => 'nullable|date',
        'birth_time' => 'nullable',
        'birth_place' => 'nullable|string',
        'country' => 'nullable|string',
        'state' => 'nullable|string',
        'city' => 'nullable|string',
        'address' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'birth_country' => 'nullable|string',
        'birth_state' => 'nullable|string',
        'birth_city' => 'nullable|string',
        'birth_latitude' => 'nullable|numeric',
        'birth_longitude' => 'nullable|numeric',
    ];

    /**
     * Validation rules for astrologer review
     */
    public const REVIEW_RULES = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    public function __construct(UserService $userService, AstrologerService $astrologerService)
    {
        $this->userService = $userService;
        $this->astrologerService = $astrologerService;
    }

    /**
     * Astrologer login with phone
     */
    public function login(Request $request)
    {
        $credentials = $this->validateRequest($request->all(), self::LOGIN_RULES);

        // Set default country code if not provided
        $credentials['country_code'] = $credentials['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $user = $this->userService->loginByPhone($credentials['phone'], $credentials['country_code']);

        if ($user) {
            // Check if user is an astrologer
            if (!$user->astrologer) {
                return $this->unauthorizedResponse('User is not registered as an astrologer. Please complete astrologer registration first.');
            }

            $user->load(User::ASTROLOGER_WITH_RELATIONS);

            // Revoke all existing tokens and create new one
            $token = $this->userService->refreshUserToken($user);

            return $this->successResponse([
                'token' => $token,
                'astrologer' => new AstrologerResource($user->astrologer)
            ], 'Login successful');
        }

        return $this->unauthorizedResponse('Astrologer not found');
    }

    /**
     * Send OTP for astrologer
     */
    public function sendOtp(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $phone = $data['phone'];
        $countryCode = $data['country_code'];

        // Check if there's already a pending OTP
        $hadPendingOtp = $this->userService->hasPendingOtp($phone, $countryCode);

        $code = $this->userService->sendOtp($phone, $countryCode);

        $message = $hadPendingOtp
            ? 'Previous OTP invalidated. New OTP sent successfully'
            : 'OTP sent successfully';

        return $this->successResponse(['otp' => $code, 'phone' => $phone], $message);
    }

    /**
     * Check OTP status for astrologer
     */
    public function checkOtpStatus(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $status = $this->userService->getOtpStatus($data['phone'], $data['country_code']);

        return $this->successResponse($status, $status['message']);
    }

    /**
     * Verify OTP for astrologer
     */
    public function verifyOtp(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_VERIFY_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $success = $this->userService->verifyOtp($data['phone'], $data['code'], $data['country_code']);

        if ($success) {
            // Get or create user after successful OTP verification with astrologer role
            [$user, $isNewUser] = $this->userService->getOrCreateUserByPhone($data['phone'], $data['country_code'], true, config('constants.ROLES.ASTROLOGER'));

            // If this is a new user or user doesn't have astrologer profile, create it
            if ($isNewUser || !$user->astrologer) {
                DB::beginTransaction();
                try {
                    // Create astrologer profile if it doesn't exist
                    if (!$user->astrologer) {
                        $astrologer = $this->astrologerService->create([
                            'user_id' => $user->id,
                            'about_me' => null,
                            'experience_years' => 0,
                            'status' => 'pending',
                            'is_online' => false,
                        ]);

                        // Create wallet for astrologer
                        $astrologer->wallet()->create(['balance' => 0]);

                        // Create pricing entries for all active services with 0 amount
                        $activeServices = Service::where('is_active', true)->get();
                        foreach ($activeServices as $service) {
                            AstrologerPricing::create([
                                'astrologer_id' => $astrologer->id,
                                'service_id' => $service->id,
                                'price_per_minute' => 0,
                                'offer_price' => 0,
                            ]);

                            $astrologer->services()->syncWithoutDetaching([$service->id => ['is_enabled' => true]]);
                        }
                    }
                    DB::commit();

                    // Refresh the user model to load the newly created astrologer relationship
                    $user->refresh();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse('Astrologer profile creation failed: ' . $e->getMessage(), 500);
                }
            }

            $user->load(User::ASTROLOGER_WITH_RELATIONS);

            // Revoke all existing tokens and create new one
            $token = $this->userService->refreshUserToken($user);

            return $this->successResponse([
                'verified' => true,
                'token' => $token,
                'astrologer' => new AstrologerResource($user->astrologer),
                'is_new_user' => $isNewUser,
            ], 'OTP verified successfully');
        }

        return $this->errorResponse('Invalid OTP', 400);
    }

    /**
     * Create astrologer profile
     */
    public function create(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::CREATE_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        // Handle name splitting logic
        $data = $this->processNameFields($data);

        DB::beginTransaction();
        try {
            // Create user
            $user = $this->userService->signup([
                'name' => $data['name'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'country_code' => $data['country_code'],
                'gender' => $data['gender'] ?? null,
                'role_id' => config('constants.ROLES.ASTROLOGER'),
            ]);

            // Create astrologer profile
            $astrologer = $this->astrologerService->create([
                'user_id' => $user->id,
                'about_me' => $data['about_me'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'status' => 'pending',
                'is_online' => false,
            ]);

            // Create wallet for astrologer
            $astrologer->wallet()->create(['balance' => 0]);

            // Create pricing entries for all active services with 0 amount
            $activeServices = Service::where('is_active', true)->get();
            foreach ($activeServices as $service) {
                AstrologerPricing::create([
                    'astrologer_id' => $astrologer->id,
                    'service_id' => $service->id,
                    'price_per_minute' => 0,
                    'offer_price' => 0,
                ]);

                $astrologer->services()->syncWithoutDetaching([$service->id => ['is_enabled' => true]]);
            }

            DB::commit();

            // Refresh the user model to load the newly created astrologer relationship
            $user->refresh();

            // Load relationships
            $user->load(User::ASTROLOGER_WITH_RELATIONS);

            // Create Passport token for the new astrologer
            $token = $user->createToken('API Token')->accessToken;

            return $this->createdResponse([
                'astrologer' => new AstrologerResource($user->astrologer),
                'token' => $token
            ], 'Astrologer registered successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Astrologer registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get astrologer profile
     */
    public function getProfile()
    {
        $user = auth()->user();

        if (!$user->astrologer) {
            return $this->errorResponse('User is not registered as an astrologer', 404);
        }

        $user->load(User::ASTROLOGER_WITH_RELATIONS);

        return $this->successResponse([
            'astrologer' => new AstrologerResource($user->astrologer)
        ], 'Profile retrieved successfully');
    }

    /**
     * Update astrologer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->astrologer) {
            return $this->errorResponse('User is not registered as an astrologer', 404);
        }

        // Create dynamic validation rules that include the user ID for unique checks
        $profileUpdateRules = self::PROFILE_UPDATE_RULES;
        $profileUpdateRules['email'] = 'nullable|email|unique:users,email,' . $user->id;
        $profileUpdateRules['phone'] = 'nullable|string|unique:users,phone,' . $user->id;

        $validated = $this->validateRequest($request->all(), $profileUpdateRules);

        // Handle name splitting logic
        $validated = $this->processNameFields($validated);

        DB::beginTransaction();
        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                try {
                    $azureStorageService = app(AzureStorageService::class);
                    $result = $azureStorageService->uploadImage(
                        $request->file('profile_image'),
                        'profile-images',
                        'astrologer_' . $user->id . '_profile_' . time()
                    );

                    if ($result['success']) {
                        $validated['profile_image'] = $result['file_url'];
                    } else {
                        return $this->errorResponse('Failed to upload profile image', 400);
                    }
                } catch (\Exception $e) {
                    return $this->errorResponse('Error uploading image: ' . $e->getMessage(), 400);
                }
            }

            // Update user fields
            $userFields = array_intersect_key($validated, array_flip(['name', 'first_name', 'last_name', 'email', 'phone', 'gender', 'profile_image']));
            if (!empty($userFields)) {
                $this->userService->updateProfile($user, $userFields);
            }

            // Update astrologer fields
            $astrologerFields = array_intersect_key($validated, array_flip(['about_me', 'experience_years']));
            if (!empty($astrologerFields)) {
                $this->astrologerService->update($user->astrologer, $astrologerFields);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Profile update failed: ' . $e->getMessage(), 500);
        }

        $user->load(User::ASTROLOGER_WITH_RELATIONS);

        return $this->updatedResponse([
            'astrologer' => new AstrologerResource($user->astrologer)
        ], 'Profile updated successfully');
    }

    /**
     * Logout astrologer
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->successResponse(null, 'Successfully logged out');
    }

    /**
     * Add or update a review for an astrologer by the authenticated user.
     * POST /api/astrologers/{astrologer}/review
     */
    public function addReview(Request $request, $astrologerId)
    {
        $user = Auth::user();
        $validated = $this->validateRequest($request->all(), self::REVIEW_RULES);
        $astrologer = Astrologer::findOrFail($astrologerId);
        $review = $this->astrologerService->addOrUpdateReview(
            $astrologer->id,
            $user->id,
            $validated['rating'],
            $validated['comment'] ?? null
        );
        return $this->successResponse($review, 'Review submitted successfully');
    }
}
