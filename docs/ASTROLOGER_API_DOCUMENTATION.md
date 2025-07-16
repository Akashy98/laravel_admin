# Astrologer API Documentation

This document describes the API endpoints for astrologer authentication, profile management, and wallet functionality.

## Base URL
```
http://your-domain.com/api/astrologer
```

## Authentication
Most endpoints require authentication using Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

## Endpoints

### 1. Astrologer Registration
**POST** `/api/astrologer/create`

Creates a new astrologer account with wallet.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "country_code": "+91",
    "gender": "male",
    "about_me": "Experienced astrologer with 10 years of practice",
    "experience_years": 10
}
```

**Response:**
```json
{
    "success": true,
    "message": "Astrologer registered successfully",
    "data": {
        "astrologer": {
            "id": 1,
            "user_id": 1,
            "about_me": "Experienced astrologer with 10 years of practice",
            "experience_years": 10,
            "status": "pending",
            "is_online": false,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone": "9876543210",
                "country_code": "+91",
                "gender": "male",
                "status": 1
            },
            "wallet": {
                "balance": 0
            }
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
    },
    "status_code": 201
}
```

### 2. Send OTP
**POST** `/api/astrologer/send-otp`

Sends OTP to the specified phone number.

**Request Body:**
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

**Response:**
```json
{
    "success": true,
    "message": "OTP sent successfully",
    "data": {
        "otp": "9999"
    },
    "status_code": 200
}
```

### 3. Check OTP Status
**POST** `/api/astrologer/check-otp-status`

Checks if there's a pending OTP and its expiration time.

**Request Body:**
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

**Response:**
```json
{
    "success": true,
    "message": "OTP is still valid",
    "data": {
        "has_pending": true,
        "expires_in": 300,
        "message": "OTP is still valid"
    },
    "status_code": 200
}
```

### 4. Verify OTP
**POST** `/api/astrologer/verify-otp`

Verifies the OTP and logs in the astrologer.

**Request Body:**
```json
{
    "phone": "9876543210",
    "code": "9999",
    "country_code": "+91"
}
```

**Response:**
```json
{
    "success": true,
    "message": "OTP verified successfully",
    "data": {
        "verified": true,
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
        "astrologer": {
            "id": 1,
            "user_id": 1,
            "about_me": "Experienced astrologer with 10 years of practice",
            "experience_years": 10,
            "status": "pending",
            "is_online": false,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone": "9876543210",
                "country_code": "+91",
                "gender": "male",
                "status": 1
            },
            "wallet": {
                "balance": 0
            }
        },
        "is_new_user": false
    },
    "status_code": 200
}
```

### 5. Login with Phone
**POST** `/api/astrologer/login`

Logs in an existing astrologer using phone number.

**Request Body:**
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
        "astrologer": {
            "id": 1,
            "user_id": 1,
            "about_me": "Experienced astrologer with 10 years of practice",
            "experience_years": 10,
            "status": "pending",
            "is_online": false,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone": "9876543210",
                "country_code": "+91",
                "gender": "male",
                "status": 1
            },
            "wallet": {
                "balance": 0
            }
        }
    },
    "status_code": 200
}
```

### 6. Get Profile (Protected)
**GET** `/api/astrologer/profile`

Retrieves the authenticated astrologer's profile.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "data": {
        "astrologer": {
            "id": 1,
            "user_id": 1,
            "about_me": "Experienced astrologer with 10 years of practice",
            "experience_years": 10,
            "status": "pending",
            "is_online": false,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone": "9876543210",
                "country_code": "+91",
                "gender": "male",
                "status": 1
            },
            "wallet": {
                "balance": 0
            },
            "skills": [
                {
                    "id": 1,
                    "category_id": 1,
                    "category": {
                        "id": 1,
                        "name": "Vedic Astrology"
                    }
                }
            ],
            "languages": [
                {
                    "id": 1,
                    "language_id": 1,
                    "language": {
                        "id": 1,
                        "name": "Hindi"
                    }
                }
            ],
            "availability": [
                {
                    "id": 1,
                    "day_of_week": "monday",
                    "start_time": "09:00:00",
                    "end_time": "17:00:00"
                }
            ],
            "pricing": [
                {
                    "id": 1,
                    "service_id": 1,
                    "price_per_minute": 10.00,
                    "offer_price": 8.00,
                    "service": {
                        "id": 1,
                        "name": "Phone Consultation"
                    }
                }
            ]
        }
    },
    "status_code": 200
}
```

### 7. Update Profile (Protected)
**POST** `/api/astrologer/profile`

Updates the authenticated astrologer's profile.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "9876543210",
    "gender": "male",
    "about_me": "Updated about me section",
    "experience_years": 12
}
```

**Response:**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "astrologer": {
            "id": 1,
            "user_id": 1,
            "about_me": "Updated about me section",
            "experience_years": 12,
            "status": "pending",
            "is_online": false,
            "user": {
                "id": 1,
                "name": "John Doe Updated",
                "email": "john.updated@example.com",
                "phone": "9876543210",
                "country_code": "+91",
                "gender": "male",
                "status": 1
            },
            "wallet": {
                "balance": 0
            }
        }
    },
    "status_code": 200
}
```

### 8. Logout (Protected)
**POST** `/api/astrologer/logout`

Logs out the authenticated astrologer by revoking the token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Successfully logged out",
    "data": null,
    "status_code": 200
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "phone": ["The phone field is required."],
        "email": ["The email must be a valid email address."]
    },
    "status_code": 422
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "User is not registered as an astrologer",
    "status_code": 401
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Astrologer not found",
    "status_code": 404
}
```

## Wallet Integration

Every astrologer automatically gets a wallet created with:
- **Initial balance**: 0
- **Wallet type**: MorphOne relationship with User/Astrologer
- **Transactions**: Tracked through WalletTransaction model

## Important Notes

1. **OTP System**: Uses master OTP '9999' for development/testing
2. **Role Assignment**: Astrologers are assigned role_id = 3
3. **Default Country Code**: +91 if not provided
4. **Token Management**: All existing tokens are revoked on login
5. **Profile Status**: New astrologers start with 'pending' status
6. **Wallet Creation**: Automatic wallet creation for both user and astrologer profiles

## Testing

You can test these APIs using tools like Postman or curl. Remember to:
1. Use the correct base URL
2. Include proper headers for authenticated endpoints
3. Handle the Bearer token for protected routes
4. Use the master OTP '9999' for testing 
