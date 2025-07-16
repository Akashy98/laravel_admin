# API Response Structure

## Overview

All API endpoints in the AstroIndia backend follow a consistent response structure using the `ApiResponse` trait. This ensures uniform error handling and success responses across all controllers.

## Standard Response Format

### Success Response

All successful API calls return the following structure:

```json
{
  "success": true,
  "message": "Descriptive success message",
  "data": {
    // Response data here
  },
  "status_code": 200
}
```

### Error Response

All error responses follow this structure:

```json
{
  "success": false,
  "message": "Error description",
  "status_code": 400,
  "errors": {
    // Validation errors (if applicable)
  }
}
```

## Response Methods

The `ApiResponse` trait provides the following methods for controllers:

### Success Responses

- `successResponse($data, $message, $statusCode)` - Standard success response
- `createdResponse($data, $message)` - Resource created (201)
- `updatedResponse($data, $message)` - Resource updated (200)
- `deletedResponse($message)` - Resource deleted (200)

### Error Responses

- `errorResponse($message, $statusCode, $errors)` - Standard error response
- `validationErrorResponse($errors, $message)` - Validation errors (422)
- `notFoundResponse($message)` - Resource not found (404)
- `unauthorizedResponse($message)` - Unauthorized (401)
- `forbiddenResponse($message)` - Forbidden (403)
- `serverErrorResponse($message)` - Internal server error (500)

## Usage in Controllers

All API controllers extend `BaseController` which includes the `ApiResponse` trait:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;

class ExampleController extends BaseController
{
    public function index()
    {
        try {
            $data = ['items' => [1, 2, 3]];
            return $this->successResponse($data, 'Items retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving items: ' . $e->getMessage());
        }
    }
}
```

## Resource Integration

When using Laravel Resources, they should be wrapped in success responses:

```php
// Correct way
$data = UserResource::collection($users);
return $this->successResponse($data, 'Users retrieved successfully');

// Instead of returning resource directly
// return UserResource::collection($users);
```

## Validation

The trait provides custom validation methods:

```php
// Validate with custom error response
$data = $this->validateWithCustomError($request->all(), $rules);
if ($data instanceof JsonResponse) {
    return $data; // Validation failed
}

// Standard validation
$data = $this->validateRequest($request->all(), $rules);
```

## Status Codes

Common HTTP status codes used:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Examples

### Success Example

```json
{
  "success": true,
  "message": "User profile updated successfully",
  "data": {
    "id": 123,
    "name": "John Doe",
    "email": "john@example.com",
    "updated_at": "2025-01-15T10:30:00.000000Z"
  },
  "status_code": 200
}
```

### Error Example

```json
{
  "success": false,
  "message": "Validation failed",
  "status_code": 422,
  "errors": {
    "email": ["The email field is required."],
    "phone": ["The phone field must be a valid phone number."]
  }
}
```

### Not Found Example

```json
{
  "success": false,
  "message": "User not found",
  "status_code": 404
}
```

## Benefits

1. **Consistency**: All APIs follow the same response format
2. **Error Handling**: Standardized error responses across the application
3. **Frontend Integration**: Predictable response structure for frontend developers
4. **Debugging**: Clear success/error indicators and descriptive messages
5. **Validation**: Built-in validation error handling with custom formatting 
