# API Resources Documentation

This document describes all the JSON resources used in the astrology API system.

## Core Resources

### 1. UserResource
**File:** `app/Http/Resources/UserResource.php`

Used for user data representation across the application.

**Fields:**
- `id` - User ID
- `role_id` - User role (1=admin, 2=user, 3=astrologer)
- `name` - Full name
- `first_name` - First name
- `last_name` - Last name
- `gender` - Gender (male/female/other)
- `phone` - Phone number
- `email` - Email address
- `country_code` - Country code
- `profile_image` - Profile image URL
- `status` - User status
- `email_verified_at` - Email verification timestamp
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `profile` - UserProfileResource (when loaded)
- `addresses` - UserAddressResource collection (when loaded)
- `contacts` - UserContactResource collection (when loaded)
- `device_tokens` - DeviceTokenResource collection (when loaded)
- `country` - CountryResource (when loaded)
- `state` - StateResource (when loaded)
- `city` - CityResource (when loaded)

### 2. AstrologerResource
**File:** `app/Http/Resources/AstrologerResource.php`

Main resource for astrologer data with all related information.

**Fields:**
- `id` - Astrologer ID
- `user_id` - Associated user ID
- `about_me` - Astrologer description
- `experience_years` - Years of experience
- `status` - Astrologer status (pending/approved/rejected)
- `is_online` - Online status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `user` - UserResource (when loaded)
- `wallet` - WalletResource (when loaded)
- `skills` - AstrologerSkillResource collection (when loaded)
- `languages` - AstrologerLanguageResource collection (when loaded)
- `availability` - AstrologerAvailabilityResource collection (when loaded)
- `pricing` - AstrologerPricingResource collection (when loaded)
- `documents` - AstrologerDocumentResource collection (when loaded)
- `bank_details` - AstrologerBankDetailResource collection (when loaded)
- `reviews` - AstrologerReviewResource collection (when loaded)

## Astrologer-Specific Resources

### 3. AstrologerSkillResource
**File:** `app/Http/Resources/AstrologerSkillResource.php`

Represents astrologer skills and their categories.

**Fields:**
- `id` - Skill ID
- `astrologer_id` - Astrologer ID
- `category_id` - Category ID
- `category` - CategoryResource (when loaded)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 4. AstrologerLanguageResource
**File:** `app/Http/Resources/AstrologerLanguageResource.php`

Represents languages spoken by astrologers.

**Fields:**
- `id` - Language ID
- `astrologer_id` - Astrologer ID
- `language_id` - Language ID
- `language` - LanguageResource (when loaded)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 5. AstrologerAvailabilityResource
**File:** `app/Http/Resources/AstrologerAvailabilityResource.php`

Represents astrologer availability schedules.

**Fields:**
- `id` - Availability ID
- `astrologer_id` - Astrologer ID
- `day_of_week` - Day of week
- `start_time` - Start time
- `end_time` - End time
- `is_active` - Active status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 6. AstrologerPricingResource
**File:** `app/Http/Resources/AstrologerPricingResource.php`

Represents astrologer pricing for different services.

**Fields:**
- `id` - Pricing ID
- `astrologer_id` - Astrologer ID
- `service_id` - Service ID
- `price_per_minute` - Price per minute
- `offer_price` - Offer price
- `is_active` - Active status
- `service` - ServiceResource (when loaded)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 7. AstrologerDocumentResource
**File:** `app/Http/Resources/AstrologerDocumentResource.php`

Represents astrologer documents.

**Fields:**
- `id` - Document ID
- `astrologer_id` - Astrologer ID
- `document_type` - Type of document
- `document_url` - Document URL
- `status` - Document status
- `remarks` - Admin remarks
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 8. AstrologerBankDetailResource
**File:** `app/Http/Resources/AstrologerBankDetailResource.php`

Represents astrologer bank details.

**Fields:**
- `id` - Bank detail ID
- `astrologer_id` - Astrologer ID
- `account_holder_name` - Account holder name
- `account_number` - Account number
- `ifsc_code` - IFSC code
- `bank_name` - Bank name
- `upi_id` - UPI ID
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 9. AstrologerReviewResource
**File:** `app/Http/Resources/AstrologerReviewResource.php`

Represents reviews given by users to astrologers.

**Fields:**
- `id` - Review ID
- `astrologer_id` - Astrologer ID
- `user_id` - User ID
- `rating` - Rating (1-5)
- `comment` - Review comment
- `status` - Review status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `user` - UserResource (when loaded)

## Common Resources

### 10. ServiceResource
**File:** `app/Http/Resources/ServiceResource.php`

Represents services offered by the platform.

**Fields:**
- `id` - Service ID
- `name` - Service name
- `description` - Service description
- `is_active` - Active status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 11. CategoryResource
**File:** `app/Http/Resources/CategoryResource.php`

Represents astrologer skill categories.

**Fields:**
- `id` - Category ID
- `name` - Category name
- `description` - Category description
- `is_active` - Active status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 12. LanguageResource
**File:** `app/Http/Resources/LanguageResource.php`

Represents languages supported by the platform.

**Fields:**
- `id` - Language ID
- `name` - Language name
- `code` - Language code
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 13. CountryResource
**File:** `app/Http/Resources/CountryResource.php`

Represents countries.

**Fields:**
- `id` - Country ID
- `name` - Country name
- `code` - Country code
- `phone_code` - Phone code
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 14. StateResource
**File:** `app/Http/Resources/StateResource.php`

Represents states/provinces.

**Fields:**
- `id` - State ID
- `name` - State name
- `country_id` - Country ID
- `country` - CountryResource (when loaded)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 15. CityResource
**File:** `app/Http/Resources/CityResource.php`

Represents cities.

**Fields:**
- `id` - City ID
- `name` - City name
- `state_id` - State ID
- `state` - StateResource (when loaded)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Wallet Resources

### 16. WalletResource
**File:** `app/Http/Resources/WalletResource.php`

General wallet resource for any entity (users, astrologers).

**Fields:**
- `id` - Wallet ID
- `owner_id` - Owner ID
- `owner_type` - Owner type (User/Astrologer)
- `balance` - Current balance
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `transactions` - WalletTransactionResource collection (when loaded)

### 17. WalletTransactionResource
**File:** `app/Http/Resources/WalletTransactionResource.php`

Represents wallet transactions.

**Fields:**
- `id` - Transaction ID
- `wallet_id` - Wallet ID
- `amount` - Transaction amount
- `type` - Transaction type (credit/debit/bonus)
- `description` - Transaction description
- `meta` - Additional metadata (JSON)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Usage Examples

### Using Resources in Controllers

```php
// Single resource
return new AstrologerResource($astrologer);

// Collection of resources
return AstrologerResource::collection($astrologers);

// With relationships loaded
$astrologer->load([
    'user',
    'wallet.transactions',
    'skills.category',
    'languages.language',
    'availability',
    'pricing.service',
    'documents',
    'bankDetails',
    'reviews.user'
]);
return new AstrologerResource($astrologer);
```

### Conditional Loading

Resources use `$this->when()` for conditional loading:

```php
'user' => $this->when($this->user, function () {
    return new UserResource($this->user);
}),
```

This ensures that the user data is only included when the relationship is loaded.

## Benefits of This Structure

1. **Modularity**: Each entity has its own dedicated resource
2. **Reusability**: Resources can be used across different endpoints
3. **Consistency**: Standardized JSON structure
4. **Performance**: Conditional loading prevents N+1 queries
5. **Maintainability**: Easy to modify individual resource structures
6. **Type Safety**: Proper resource typing for better IDE support

## Best Practices

1. **Always use resources** instead of hardcoding arrays
2. **Load relationships** before using resources
3. **Use conditional loading** with `$this->when()`
4. **Keep resources focused** on their specific entity
5. **Reuse common resources** like UserResource, ServiceResource, etc.
6. **Document relationships** in resource comments 
