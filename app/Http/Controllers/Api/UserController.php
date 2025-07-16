<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Traits\SharedHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends BaseController
{
    use SharedHelpers;

    protected $service;

    /**
     * Default country code for phone numbers
     */
    public const DEFAULT_COUNTRY_CODE = '+91';

    /**
     * Validation rules for signup
     */
    public const SIGNUP_RULES = [
        'name' => 'nullable|string',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'required|string|unique:users,phone',
        'country_code' => 'nullable|string',
    ];

    /**
     * Validation rules for login
     */
    public const LOGIN_RULES = [
        'phone' => 'required|string',
        'country_code' => 'nullable|string',
    ];

    /**
     * Validation rules for profile update
     */
    public const PROFILE_UPDATE_RULES = [
        'name' => 'nullable|string',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'nullable|string|unique:users,phone',
        'profile_image' => 'nullable|string|url',
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
     * Validation rules for birth details update
     */
    public const BIRTH_DETAILS_RULES = [
        'birth_date' => 'required|date',
        'birth_time' => 'required',
        'birth_place' => 'required|string',
    ];

    /**
     * Validation rules for address update
     */
    public const ADDRESS_RULES = [
        'country' => 'required|string',
        'state' => 'nullable|string',
        'city' => 'required|string',
        'address' => 'nullable|string',
    ];

    /**
     * Validation rules for device token storage
     */
    public const DEVICE_TOKEN_RULES = [
        'device_type' => 'required|string',
        'device_id' => 'required|string',
        'fcm_token' => 'required|string',
    ];

    /**
     * Validation rules for account deletion
     */
    public const DELETE_ACCOUNT_RULES = [
        'confirmation' => 'required|string|in:DELETE',
    ];

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function signup(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::SIGNUP_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        // Handle name splitting logic
        $data = $this->processNameFields($data);

        $user = $this->service->signup($data);
        $user->load(User::USER_WITH_RELATIONS);

        // Create Passport token for the new user
        $token = $user->createToken('API Token')->accessToken;

        return $this->createdResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'User registered successfully');
    }

    public function login(Request $request)
    {
        $credentials = $this->validateRequest($request->all(), self::LOGIN_RULES);

        // Set default country code if not provided
        $credentials['country_code'] = $credentials['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $user = $this->service->loginByPhone($credentials['phone'], $credentials['country_code']);

        if ($user) {
            $user->load(User::USER_WITH_RELATIONS);

            // Revoke all existing tokens and create new one
            $token = $this->service->refreshUserToken($user);
            return $this->successResponse([
                'token' => $token,
                'user' => new UserResource($user)
            ], 'Login successful');
        }

        return $this->unauthorizedResponse('User not found');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->successResponse(null, 'Successfully logged out');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Create dynamic validation rules that include the user ID for unique checks
        $profileUpdateRules = self::PROFILE_UPDATE_RULES;
        $profileUpdateRules['email'] = 'nullable|email|unique:users,email,' . $user->id;
        $profileUpdateRules['phone'] = 'nullable|string|unique:users,phone,' . $user->id;

        $validated = $this->validateRequest($request->all(), $profileUpdateRules);

        // Handle name splitting logic
        $validated = $this->processNameFields($validated);

        $updatedUser = $user;
        $updatedProfile = $user->profile;
        $updatedAddress = $user->addresses()->where('address_type', 'current')->first();

        DB::beginTransaction();
        try {
            // Update user fields (name, first_name, last_name, gender, email, phone, profile_image)
            $userFields = array_intersect_key($validated, array_flip(['name', 'first_name', 'last_name', 'gender', 'email', 'phone', 'profile_image']));
            if (!empty($userFields)) {
                $updatedUser = $this->service->updateProfile($user, $userFields);
            }

            // Update profile fields (gender goes to both user and profile tables)
            $profileFields = array_intersect_key($validated, array_flip(['gender']));
            if (!empty($profileFields)) {
                $updatedProfile = $this->service->saveProfile($user, $profileFields);
            }

            // Update birth details
            $birthFields = array_intersect_key($validated, array_flip(['birth_date', 'birth_time', 'birth_place']));
            if (!empty($birthFields)) {
                $updatedProfile = $this->service->updateBirthDetails($user, $birthFields);
            }

            // Update address
            $addressFields = array_intersect_key($validated, array_flip(['country', 'state', 'city', 'address', 'latitude', 'longitude']));
            if (!empty($addressFields)) {
                $updatedAddress = $this->service->updateCurrentAddress($user, $addressFields);
            }

            // Update birth address
            $birthAddressFields = array_intersect_key($validated, array_flip(['birth_country', 'birth_state', 'birth_city', 'birth_latitude', 'birth_longitude']));
            if (!empty($birthAddressFields)) {
                $this->service->updateBirthAddress($user, $birthAddressFields);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Profile update failed: ' . $e->getMessage(), 500, $e->getTrace());
        }

        $updatedUser->load(User::USER_WITH_RELATIONS);

        return $this->updatedResponse([
            'user' => new UserResource($updatedUser),
        ], 'Profile updated successfully');
    }

    public function sendOtp(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $phone = $data['phone'];
        $countryCode = $data['country_code'];

        // Check if there's already a pending OTP
        $hadPendingOtp = $this->service->hasPendingOtp($phone, $countryCode);

        $code = $this->service->sendOtp($phone, $countryCode);

        $message = $hadPendingOtp
            ? 'Previous OTP invalidated. New OTP sent successfully'
            : 'OTP sent successfully';

        return $this->successResponse(['otp' => $code, 'phone' => $phone], $message);
    }

    public function checkOtpStatus(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $status = $this->service->getOtpStatus($data['phone'], $data['country_code']);

        return $this->successResponse($status, $status['message']);
    }

    public function verifyOtp(Request $request)
    {
        $data = $this->validateRequest($request->all(), self::OTP_VERIFY_RULES);

        // Set default country code if not provided
        $data['country_code'] = $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE;

        $success = $this->service->verifyOtp($data['phone'], $data['code'], $data['country_code']);

        if ($success) {
            // Get or create user after successful OTP verification
            [$user, $isNewUser] = $this->service->getOrCreateUserByPhone($data['phone'], $data['country_code'], true);
            $user->load(User::USER_WITH_RELATIONS);

            // Revoke all existing tokens and create new one
            $token = $this->service->refreshUserToken($user);

            return $this->successResponse([
                'verified' => true,
                'token' => $token,
                'user' => new UserResource($user),
                'is_new_user' => $isNewUser,
            ], 'OTP verified successfully');
        }

        return $this->errorResponse('Invalid OTP', 400);
    }

    public function getProfile()
    {
        $user = auth()->user();
        $user->load(User::USER_WITH_RELATIONS);
        return $this->successResponse(['user' => new UserResource($user)], 'Profile retrieved successfully');
    }

    public function updateBirthDetails(Request $request)
    {
        $user = auth()->user();
        $data = $this->validateRequest($request->all(), self::BIRTH_DETAILS_RULES);
        $profile = $this->service->updateBirthDetails($user, $data);
        return $this->updatedResponse(['profile' => $profile], 'Birth details updated successfully');
    }

    public function updateCurrentAddress(Request $request)
    {
        $user = auth()->user();
        $data = $this->validateRequest($request->all(), self::ADDRESS_RULES);
        $address = $this->service->updateCurrentAddress($user, $data);
        return $this->updatedResponse(['address' => $address], 'Address updated successfully');
    }

    public function socialLogin(Request $request)
    {
        // Stub for Apple/Google login
        return $this->errorResponse('Social login not implemented yet.', 501);
    }

    public function storeDeviceToken(Request $request)
    {
        $user = Auth::user();
        $data = $this->validateRequest($request->all(), self::DEVICE_TOKEN_RULES);
        $token = $user->deviceTokens()->updateOrCreate(
            [
                'device_id' => $data['device_id'],
                'device_type' => $data['device_type'],
            ],
            [
                'fcm_token' => $data['fcm_token'],
            ]
        );
        return $this->createdResponse(['device_token' => $token], 'Device token stored successfully');
    }

    /**
     * Delete user account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Validate request
        $this->validateRequest($request->all(), self::DELETE_ACCOUNT_RULES, [
            'confirmation.in' => 'Please type DELETE to confirm account deletion'
        ]);

        try {
            // Delete user and all related data
            $this->service->deleteUser($user);

            return $this->successResponse(null, 'Account deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete account: ' . $e->getMessage(), 500);
        }
    }
}
