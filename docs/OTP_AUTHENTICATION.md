# OTP-Based Authentication System

## Overview
The application now uses OTP-based authentication instead of password-based login. A static master OTP is used for development and testing purposes.

## Master OTP
- **Master OTP**: `9999`
- This OTP works for any phone number during development/testing
- In production, this should be replaced with actual SMS integration

## Authentication Flow

### 1. Send OTP
**Endpoint**: `POST /api/user/send-otp`

**Request Body**:
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```

**Response**:
```json
{
    "success": true,
    "data": {
        "otp": "9999"
    },
    "message": "OTP sent successfully"
}
```

### 2. Verify OTP and Login
**Endpoint**: `POST /api/user/verify-otp`

**Request Body**:
```json
{
    "phone": "1234567890",
    "code": "9999",
    "country_code": "+91"
}
```

**Response** (Success):
```json
{
    "success": true,
    "data": {
        "verified": true,
        "token": "access_token_here",
        "user": {
            "id": 1,
            "name": "User_7890",
            "phone": "1234567890",
            "country_code": "+91",
            // ... other user fields
        }
    },
    "message": "OTP verified successfully"
}
```

### 3. Direct Login (Alternative)
**Endpoint**: `POST /api/user/login`

**Request Body**:
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```

**Response** (if user exists):
```json
{
    "success": true,
    "data": {
        "token": "access_token_here",
        "user": {
            // user data
        }
    },
    "message": "Login successful"
}
```

## User Registration

### Signup
**Endpoint**: `POST /api/user/signup`

**Request Body**:
```json
{
    "name": "John Doe",
    "phone": "1234567890",
    "email": "john@example.com",
    "country_code": "+91"
}
```

## Key Features

1. **No Password Required**: Users can login with just their phone number
2. **Master OTP**: `9999` works for any phone number during development
3. **Auto User Creation**: If a user doesn't exist during OTP verification, they are automatically created
4. **Default Naming**: Auto-created users get a name like "User_7890" (last 4 digits of phone)
5. **Country Code Support**: Supports different country codes (defaults to +91)

## Production Considerations

1. **Replace Master OTP**: Implement actual SMS service integration
2. **OTP Expiry**: Current OTP expires in 10 minutes
3. **Rate Limiting**: Consider implementing rate limiting for OTP requests
4. **SMS Provider**: Integrate with services like Twilio, AWS SNS, etc.

## Testing

For testing purposes, you can use any phone number with the master OTP `9999`:

```bash
# Send OTP
curl -X POST /api/user/send-otp \
  -H "Content-Type: application/json" \
  -d '{"phone": "1234567890", "country_code": "+91"}'

# Verify OTP
curl -X POST /api/user/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"phone": "1234567890", "code": "9999", "country_code": "+91"}'
``` 
