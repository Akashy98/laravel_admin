<?php

namespace App\Services;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    private const MASTER_OTP = '9999';

    /**
     * Default country code for phone numbers
     */
    public const DEFAULT_COUNTRY_CODE = '+91';

    public function signup($data)
    {
        $user = User::create([
            'name' => $data['name'] ?? '',
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'country_code' => $data['country_code'] ?? self::DEFAULT_COUNTRY_CODE,
            'password' => Hash::make('default_password'), // Set a default password
            'role_id' => $data['role_id'] ?? 2, // default to user
            'status' => 1,
        ]);

        // If gender is provided, also save it to the profile
        if (!empty($data['gender'])) {
            $this->saveProfile($user, ['gender' => $data['gender']]);
        }

        return $user;
    }

    public function loginByPhone($phone, $countryCode = self::DEFAULT_COUNTRY_CODE)
    {
        return User::where('phone', $phone)
                  ->where('country_code', $countryCode)
                  ->where('status', 1)
                  ->first();
    }

    public function updateProfile(User $user, $data)
    {
        $user->fill($data);
        $user->save();

        // If gender is being updated, also update it in the profile
        if (isset($data['gender'])) {
            $this->saveProfile($user, ['gender' => $data['gender']]);
        }

        return $user;
    }

    /**
     * Create a new user with basic data
     */
    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'] ?? '',
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'] ?? 2,
            'status' => 1,
        ]);

        // If gender is provided, also save it to the profile
        if (!empty($data['gender'])) {
            $this->saveProfile($user, ['gender' => $data['gender']]);
        }

        return $user;
    }

    /**
     * Update user basic data
     */
    public function updateUser(User $user, $data)
    {
        try {
            $user->fill($data);
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
            return $user;
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage(), ['data' => $data, 'user_id' => $user->id]);
            throw $e;
        }
    }

    /**
     * Save user addresses (create/update/delete)
     */
    public function saveAddresses(User $user, array $addresses = [])
    {
        // Get existing address IDs
        $existingIds = $user->addresses()->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($addresses as $addressData) {
            if (empty($addressData['address_type'])) continue;

            $addressData['user_id'] = $user->id;
            $addressData['is_active'] = $addressData['is_active'] ?? true;

            // Convert ID fields to string fields for database
            if (isset($addressData['country_id']) && $addressData['country_id']) {
                $country = \App\Models\Country::find($addressData['country_id']);
                $addressData['country'] = $country ? $country->name : null;
                unset($addressData['country_id']);
            }

            if (isset($addressData['state_id']) && $addressData['state_id']) {
                $state = \App\Models\State::find($addressData['state_id']);
                $addressData['state'] = $state ? $state->name : null;
                unset($addressData['state_id']);
            }

            if (isset($addressData['city_id']) && $addressData['city_id']) {
                $city = \App\Models\City::find($addressData['city_id']);
                $addressData['city'] = $city ? $city->name : null;
                unset($addressData['city_id']);
            }

            if (isset($addressData['id']) && $addressData['id']) {
                // Update existing address
                $address = $user->addresses()->find($addressData['id']);
                if ($address) {
                    $address->update($addressData);
                    $updatedIds[] = $address->id;
                }
            } else {
                // Create new address
                $address = $user->addresses()->create($addressData);
                $updatedIds[] = $address->id;
            }
        }

        // Delete addresses not in the updated list
        $addressesToDelete = array_diff($existingIds, $updatedIds);
        if (!empty($addressesToDelete)) {
            $user->addresses()->whereIn('id', $addressesToDelete)->delete();
        }

        return $user->addresses()->get();
    }

    /**
     * Save user contacts (create/update/delete)
     */
    public function saveContacts(User $user, array $contacts = [])
    {
        $existingIds = $user->contacts()->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($contacts as $contactData) {
            if (empty($contactData['contact_type'])) continue;

            $contactData['user_id'] = $user->id;
            $contactData['is_active'] = $contactData['is_active'] ?? true;

            if (isset($contactData['id']) && $contactData['id']) {
                // Update existing contact
                $contact = $user->contacts()->find($contactData['id']);
                if ($contact) {
                    $contact->update($contactData);
                    $updatedIds[] = $contact->id;
                }
            } else {
                // Check for existing contact with same phone number
                $existingContact = $user->contacts()->where('phone_number', $contactData['phone_number'])->first();
                if ($existingContact) {
                    $existingContact->update($contactData);
                    $updatedIds[] = $existingContact->id;
                } else {
                    // Create new contact
                    $contact = $user->contacts()->create($contactData);
                    $updatedIds[] = $contact->id;
                }
            }
        }

        // Delete contacts not in the updated list
        $contactsToDelete = array_diff($existingIds, $updatedIds);
        if (!empty($contactsToDelete)) {
            $user->contacts()->whereIn('id', $contactsToDelete)->delete();
        }

        return $user->contacts()->get();
    }

    /**
     * Save user profile data
     */
    public function saveProfile(User $user, array $profileData = [])
    {
        if (empty($profileData)) {
            return null;
        }

        $profileData['user_id'] = $user->id;
        $profileData['is_active'] = $profileData['is_active'] ?? true;

        // Update or create profile
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return $profile;
    }

    /**
     * Complete user save with all related data
     */
    public function saveUserWithRelatedData($userData, $addresses = [], $contacts = [], $profile = [])
    {
        $user = $this->createUser($userData);

        $this->saveAddresses($user, $addresses);
        $this->saveContacts($user, $contacts);
        $this->saveProfile($user, $profile);

        return $user;
    }

    /**
     * Complete user update with all related data
     */
    public function updateUserWithRelatedData(User $user, $userData, $addresses = [], $contacts = [], $profile = [])
    {
        $this->updateUser($user, $userData);

        $this->saveAddresses($user, $addresses);
        $this->saveContacts($user, $contacts);
        $this->saveProfile($user, $profile);

        return $user;
    }

    public function sendOtp($phone, $countryCode = self::DEFAULT_COUNTRY_CODE)
    {

        // Check if there's already a pending OTP
        $existingOtp = Verification::where('phone', $phone)
                                  ->where('country_code', $countryCode)
                                  ->where('status', 'pending')
                                  ->where('expired_at', '>', now())
                                  ->first();

        // For development/testing, always return the master OTP
        $code = self::MASTER_OTP;

        // Invalidate any existing pending OTPs for this phone number
        Verification::where('phone', $phone)
                   ->where('country_code', $countryCode)
                   ->where('status', 'pending')
                   ->update(['status' => 'expired']);

        // Create verification record
        Verification::createVerification($phone, $code, $countryCode);

        // In production, integrate with SMS provider here
        // For now, just return the master OTP
        return $code;
    }

    public function hasPendingOtp($phone, $countryCode = self::DEFAULT_COUNTRY_CODE)
    {
        return Verification::where('phone', $phone)
                          ->where('country_code', $countryCode)
                          ->where('status', 'pending')
                          ->where('expired_at', '>', now())
                          ->exists();
    }

    public function getOtpStatus($phone, $countryCode = self::DEFAULT_COUNTRY_CODE)
    {
        $verification = Verification::where('phone', $phone)
                                   ->where('country_code', $countryCode)
                                   ->where('status', 'pending')
                                   ->where('expired_at', '>', now())
                                   ->first();

        if (!$verification) {
            return [
                'has_pending' => false,
                'expires_in' => 0,
                'message' => 'No pending OTP found'
            ];
        }

        $expiresIn = now()->diffInSeconds($verification->expired_at, false);

        return [
            'has_pending' => true,
            'expires_in' => max(0, $expiresIn),
            'message' => $expiresIn > 0 ? 'OTP is still valid' : 'OTP has expired'
        ];
    }

    public function verifyOtp($phone, $code, $countryCode = self::DEFAULT_COUNTRY_CODE)
    {
        // Always check for a pending verification first
        $verification = Verification::where('phone', $phone)
            ->where('country_code', $countryCode)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->latest('id')
            ->first();

        if (!$verification) {
            // No pending verification exists
            return false;
        }

        // If master OTP, mark as verified
        if ($code === self::MASTER_OTP) {
            $verification->markAsVerified();
            return true;
        }

        // Check regular OTP verification
        if ($verification->code === $code) {
            $verification->markAsVerified();
            return true;
        }

        return false;
    }

    public function getOrCreateUserByPhone($phone, $countryCode = self::DEFAULT_COUNTRY_CODE, $returnIsNew = false, $roleId = 2)
    {
        $user = User::where('phone', $phone)
                   ->where('country_code', $countryCode)
                   ->first();

        $isNewUser = false;
        if (!$user) {
            // Create a new user with default name
            $user = User::create([
                'name' => 'User_' . substr($phone, -4), // Use last 4 digits as name
                'first_name' => 'User',
                'last_name' => substr($phone, -4),
                'phone' => $phone,
                'country_code' => $countryCode,
                'password' => Hash::make('default_password'),
                'role_id' => $roleId, // Use provided role_id
                'status' => 1,
            ]);

            // Create wallet for the new user
            if($roleId == config('constants.ROLES.CUSTOMER')) {
                $user->wallet()->create(['balance' => 0]);
            }

            $isNewUser = true;
        }

        if ($returnIsNew) {
            return [$user, $isNewUser];
        }
        return $user;
    }

    public function updateBirthDetails(User $user, $data)
    {
        $updateData = [];

        if (isset($data['birth_date'])) {
            $updateData['birth_date'] = $data['birth_date'];
        }

        if (isset($data['birth_time'])) {
            $updateData['birth_time'] = $data['birth_time'];
        }

        if (isset($data['birth_place'])) {
            $updateData['birth_place'] = $data['birth_place'];
        }

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $updateData
        );
        return $profile;
    }

    public function updateCurrentAddress(User $user, $data)
    {
        $updateData = [
            'is_primary' => true,
            'is_active' => true,
        ];

        if (isset($data['country'])) {
            $updateData['country'] = $data['country'];
        }

        if (isset($data['state'])) {
            $updateData['state'] = $data['state'];
        }

        if (isset($data['city'])) {
            $updateData['city'] = $data['city'];
        }

        if (isset($data['address'])) {
            $updateData['address'] = $data['address'];
        }

        if (isset($data['latitude'])) {
            $updateData['latitude'] = $data['latitude'];
        }

        if (isset($data['longitude'])) {
            $updateData['longitude'] = $data['longitude'];
        }

        $address = $user->addresses()->updateOrCreate(
            ['address_type' => 'current'],
            $updateData
        );
        return $address;
    }

    public function updateBirthAddress(User $user, $data)
    {
        $updateData = [
            'is_active' => true,
        ];
        if (isset($data['birth_country'])) {
            $updateData['country'] = $data['birth_country'];
        }
        if (isset($data['birth_state'])) {
            $updateData['state'] = $data['birth_state'];
        }
        if (isset($data['birth_city'])) {
            $updateData['city'] = $data['birth_city'];
        }
        if (isset($data['birth_latitude'])) {
            $updateData['latitude'] = $data['birth_latitude'];
        }
        if (isset($data['birth_longitude'])) {
            $updateData['longitude'] = $data['birth_longitude'];
        }
        $address = $user->addresses()->updateOrCreate(
            ['address_type' => 'birth'],
            $updateData
        );
        return $address;
    }

    /**
     * Revoke all existing tokens for a user and create a new one
     */
    public function refreshUserToken(User $user)
    {
        // Revoke all existing tokens for this user
        $user->tokens()->each(function($token) {
            $token->revoke();
        });

        // Create new token
        return $user->createToken('API Token')->accessToken;
    }

    /**
     * Delete user and all related data
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        try {
            // Revoke all tokens
            $user->tokens()->each(function($token) {
                $token->revoke();
            });

            // Delete related data
            $user->profile()->delete();
            $user->addresses()->delete();
            $user->contacts()->delete();
            $user->deviceTokens()->delete();

            // Soft delete the user
            $user->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage(), ['user_id' => $user->id]);
            throw $e;
        }
    }

    /**
     * Permanently delete user and all related data
     *
     * @param User $user
     * @return bool
     */
    public function forceDeleteUser(User $user)
    {
        try {
            // Delete related data
            if ($user->profile) {
                $user->profile->delete(); // Use forceDelete() if UserProfile uses SoftDeletes
            }
            if ($user->addresses) {
                $user->addresses()->delete(); // Use forceDelete() if addresses use SoftDeletes
            }
            if ($user->contacts) {
                $user->contacts()->delete(); // Use forceDelete() if contacts use SoftDeletes
            }
            if ($user->deviceTokens) {
                $user->deviceTokens()->delete(); // Use forceDelete() if deviceTokens use SoftDeletes
            }
            // Delete wallet and transactions if needed
            if (method_exists($user, 'wallet')) {
                $wallet = $user->wallet;
                if ($wallet) {
                    if (method_exists($wallet, 'transactions')) {
                        $wallet->transactions()->delete(); // Use forceDelete() if needed
                    }
                    $wallet->delete(); // Use forceDelete() if needed
                }
            }
            // Force delete the user
            $user->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error('User force deletion failed: ' . $e->getMessage(), ['user_id' => $user->id]);
            throw $e;
        }
    }
}
