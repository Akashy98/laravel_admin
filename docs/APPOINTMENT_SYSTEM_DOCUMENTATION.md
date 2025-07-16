# Appointment System Documentation

## Overview

The Appointment System provides a comprehensive booking solution for astrologer consultations with support for both instant and scheduled appointments. The system includes advanced features like broadcast booking, flexible payment timing, and automated session management.

## Features

### 1. **Booking Types**
- **Instant Booking**: Immediate consultation with available astrologers
- **Scheduled Booking**: Pre-booked appointments at specific times

### 2. **Service Types**
- **Chat**: Text-based consultation
- **Call**: Voice call consultation  
- **Video Call**: Video consultation

### 3. **Duration Options**
- 10 minutes
- 15 minutes (default)
- 20 minutes

### 4. **Payment Options**
- **On Request**: Payment deducted when booking is created
- **On Accept**: Payment deducted when astrologer accepts

### 5. **Broadcast Booking**
- Send booking request to multiple astrologers
- Automatic assignment based on availability
- Configurable timeout and max astrologers

## Database Structure

### Appointments Table
```sql
CREATE TABLE appointments (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    astrologer_id BIGINT NULL,
    service_type ENUM('chat', 'call', 'video_call'),
    booking_type ENUM('instant', 'scheduled'),
    scheduled_at DATETIME NULL,
    duration_minutes INT DEFAULT 15,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL,
    started_at TIMESTAMP NULL,
    ended_at TIMESTAMP NULL,
    status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled', 'expired', 'no_astrologer'),
    base_amount DECIMAL(10,2),
    final_amount DECIMAL(10,2),
    amount_paid DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('pending', 'paid', 'refunded'),
    payment_timing ENUM('on_request', 'on_accept'),
    is_broadcast BOOLEAN DEFAULT FALSE,
    max_wait_time INT DEFAULT 300,
    cancellation_reason TEXT NULL,
    cancelled_by ENUM('user', 'astrologer', 'system') NULL,
    user_notes TEXT NULL,
    astrologer_notes TEXT NULL,
    rating INT NULL,
    review TEXT NULL,
    session_id VARCHAR(255) NULL,
    session_meta JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Appointment Settings Table
```sql
CREATE TABLE appointment_settings (
    id BIGINT PRIMARY KEY,
    `key` VARCHAR(255) UNIQUE,
    value TEXT,
    type VARCHAR(50) DEFAULT 'string',
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## API Endpoints

### 1. Get Appointment Settings
```
GET /api/appointments/settings
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment settings retrieved successfully",
  "data": {
    "instant_booking_enabled": true,
    "scheduled_booking_enabled": true,
    "payment_timing": "on_accept",
    "max_wait_time": 300,
    "available_durations": [10, 15, 20],
    "service_pricing": {
      "chat": {"base_price": 100, "per_minute": 10},
      "call": {"base_price": 150, "per_minute": 15},
      "video_call": {"base_price": 200, "per_minute": 20}
    },
    "discount_settings": {
      "enabled": true,
      "first_time_discount": 20,
      "bulk_discount": {
        "enabled": true,
        "threshold": 3,
        "discount": 10
      }
    },
    "broadcast_settings": {
      "enabled": true,
      "max_astrologers": 5,
      "auto_assign": true,
      "assignment_timeout": 60
    },
    "cancellation_settings": {
      "free_cancellation_hours": 24,
      "partial_refund_hours": 2,
      "no_refund_hours": 1
    },
    "rating_settings": {
      "enabled": true,
      "required": false,
      "auto_rating": 5,
      "rating_reminder_hours": 24
    }
  }
}
```

### 2. Create Instant Appointment
```
POST /api/appointments/instant
```

**Request Body:**
```json
{
  "service_type": "chat",
  "duration_minutes": 15,
  "is_broadcast": false,
  "user_notes": "Need help with career guidance"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Instant appointment created successfully",
  "data": {
    "appointment_id": 123,
    "status": "pending",
    "estimated_wait_time": 90,
    "available_astrologers_count": 3,
    "pricing": {
      "base_amount": 250.00,
      "final_amount": 200.00,
      "discount_amount": 50.00,
      "discount_percentage": 20.00
    }
  }
}
```

### 3. Create Scheduled Appointment
```
POST /api/appointments/scheduled
```

**Request Body:**
```json
{
  "astrologer_id": 456,
  "service_type": "video_call",
  "scheduled_at": "2025-01-20T14:00:00Z",
  "duration_minutes": 20,
  "user_notes": "Marriage compatibility analysis"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Scheduled appointment created successfully",
  "data": {
    "appointment_id": 124,
    "scheduled_at": "2025-01-20T14:00:00.000000Z",
    "pricing": {
      "base_amount": 500.00,
      "final_amount": 500.00,
      "discount_amount": 0.00,
      "discount_percentage": 0.00
    }
  }
}
```

### 4. Accept Appointment (Astrologer)
```
POST /api/appointments/astrologer/{id}/accept
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment accepted successfully",
  "data": {
    "appointment_id": 123,
    "status": "accepted",
    "payment_status": "paid"
  }
}
```

### 5. Start Appointment Session
```
POST /api/appointments/astrologer/{id}/start
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment session started",
  "data": {
    "appointment_id": 123,
    "session_id": "SESS_1234567890",
    "duration_minutes": 15,
    "started_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

### 6. Complete Appointment Session
```
POST /api/appointments/astrologer/{id}/complete
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment completed successfully",
  "data": {
    "appointment_id": 123,
    "session_duration": 900,
    "ended_at": "2025-01-15T10:45:00.000000Z"
  }
}
```

### 7. Cancel Appointment
```
POST /api/appointments/user/{id}/cancel
```

**Request Body:**
```json
{
  "reason": "Emergency came up"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment cancelled successfully",
  "data": {
    "appointment_id": 123,
    "refund_amount": 200.00,
    "cancellation_reason": "Emergency came up"
  }
}
```

### 8. Get User Appointments
```
GET /api/appointments/user?status=pending&per_page=10
```

**Response:**
```json
{
  "success": true,
  "message": "User appointments retrieved successfully",
  "data": {
    "appointments": [
      {
        "id": 123,
        "service_type": "chat",
        "booking_type": "instant",
        "status": "pending",
        "duration_minutes": 15,
        "requested_at": "2025-01-15T10:30:00.000000Z",
        "base_amount": 250.00,
        "final_amount": 200.00,
        "amount_paid": 0.00,
        "payment_status": "pending",
        "user_notes": "Need help with career guidance",
        "astrologer": {
          "id": 456,
          "name": "Dr. Sharma",
          "specialization": "Career",
          "experience": 15,
          "rating": 4.8,
          "profile_image": "https://example.com/profile.jpg"
        },
        "remaining_time": null,
        "session_duration": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1
    }
  }
}
```

### 9. Get Astrologer Appointments
```
GET /api/appointments/astrologer?status=pending&per_page=10
```

**Response:**
```json
{
  "success": true,
  "message": "Astrologer appointments retrieved successfully",
  "data": {
    "appointments": [
      {
        "id": 123,
        "service_type": "chat",
        "booking_type": "instant",
        "status": "pending",
        "duration_minutes": 15,
        "requested_at": "2025-01-15T10:30:00.000000Z",
        "base_amount": 250.00,
        "final_amount": 200.00,
        "amount_paid": 0.00,
        "payment_status": "pending",
        "user_notes": "Need help with career guidance",
        "user": {
          "id": 789,
          "name": "John Doe",
          "phone": "+919876543210",
          "profile_image": "https://example.com/user.jpg"
        },
        "remaining_time": null,
        "session_duration": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1
    }
  }
}
```

### 10. Rate Appointment
```
POST /api/appointments/user/{id}/rate
```

**Request Body:**
```json
{
  "rating": 5,
  "review": "Excellent consultation, very helpful advice"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment rated successfully",
  "data": {
    "appointment_id": 123,
    "rating": 5,
    "review": "Excellent consultation, very helpful advice"
  }
}
```

## Business Logic

### 1. **Pricing Calculation**
- Base price + (per minute rate × duration)
- Discounts applied based on settings
- First-time user discount (20% by default)
- Bulk booking discount (10% for 3+ bookings)

### 2. **Payment Processing**
- **On Request**: Deduct immediately when booking created
- **On Accept**: Deduct when astrologer accepts
- Automatic wallet balance check
- Refund processing for cancellations

### 3. **Astrologer Assignment**
- **Instant Booking**: Find available astrologers
- **Broadcast**: Send to multiple astrologers
- **Scheduled**: Check specific astrologer availability
- Auto-assignment with timeout

### 4. **Session Management**
- Start session when both parties ready
- Track session duration
- Auto-complete after duration expires
- Real-time remaining time calculation

### 5. **Cancellation & Refunds**
- **24+ hours**: Full refund
- **2-24 hours**: 50% refund
- **<2 hours**: No refund
- Automatic wallet credit

### 6. **Rating System**
- Optional rating after completion
- Auto 5-star if no rating given
- Rating reminders after 24 hours
- Review text support

## Configuration

### Appointment Settings
All settings are managed through the `appointment_settings` table:

```php
// Get settings
$instantEnabled = AppointmentSetting::getInstantBookingEnabled();
$pricing = AppointmentSetting::getServicePricing();
$discounts = AppointmentSetting::getDiscountSettings();

// Update settings
AppointmentSetting::setSetting('max_wait_time', 600, 'integer');
AppointmentSetting::setSetting('service_pricing', $newPricing, 'json');
```

### Default Settings
- **Instant Booking**: Enabled
- **Scheduled Booking**: Enabled
- **Payment Timing**: On Accept
- **Max Wait Time**: 5 minutes
- **Durations**: 10, 15, 20 minutes
- **Broadcast**: Enabled, max 5 astrologers
- **Cancellation**: 24h free, 2h partial, 1h none

## Error Handling

### Common Error Responses
```json
{
  "success": false,
  "message": "Insufficient wallet balance",
  "data": {
    "required_amount": 200.00,
    "available_balance": 150.00
  },
  "status_code": 400
}
```

### Validation Errors
```json
{
  "success": false,
  "message": "The service_type field is required.",
  "status_code": 422
}
```

## Security Features

1. **Authentication**: All endpoints require valid user/astrologer tokens
2. **Authorization**: Users can only access their own appointments
3. **Payment Verification**: Wallet balance checks before booking
4. **Session Security**: Unique session IDs for tracking
5. **Rate Limiting**: Configurable limits on booking frequency

## Performance Optimizations

1. **Database Indexes**: Optimized for common queries
2. **Caching**: Settings cached for faster access
3. **Pagination**: Large result sets handled efficiently
4. **Background Jobs**: Expired appointments cleanup
5. **Real-time Updates**: WebSocket support for live status

## Integration Points

### 1. **Wallet System**
- Automatic balance deduction
- Refund processing
- Transaction history

### 2. **Notification System**
- Booking confirmations
- Astrologer assignments
- Session reminders
- Rating prompts

### 3. **Real-time Communication**
- WebSocket connections
- Live session status
- Chat/video integration

### 4. **Analytics**
- Booking patterns
- Revenue tracking
- Astrologer performance
- User satisfaction

## Testing

### Unit Tests
- Pricing calculations
- Payment processing
- Status transitions
- Validation rules

### Integration Tests
- End-to-end booking flow
- Payment integration
- Real-time features
- Error scenarios

### Performance Tests
- Concurrent bookings
- Large appointment lists
- Real-time updates
- Database performance

## Deployment

### Environment Variables
```env
APPOINTMENT_MAX_WAIT_TIME=300
APPOINTMENT_PAYMENT_TIMING=on_accept
APPOINTMENT_BROADCAST_ENABLED=true
APPOINTMENT_MAX_ASTROLOGERS=5
```

### Database Migration
```bash
php artisan migrate
php artisan db:seed --class=AppointmentSettingSeeder
```

### Cache Configuration
```php
// Cache settings for performance
Cache::remember('appointment_settings', 3600, function () {
    return AppointmentSetting::all()->keyBy('key');
});
```

This appointment system provides a robust, scalable solution for astrologer consultations with comprehensive features for both users and astrologers. 
