# Custom Validation API Documentation

## Overview

This documentation explains how to use the custom validation methods that return only error messages instead of detailed validation errors for API responses.

## Problem Solved

Previously, validation errors returned detailed error objects like:
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "gender": ["The selected gender is invalid."]
    },
    "status_code": 422
}
```

Now, validation errors return only the first error message:
```json
{
    "success": false,
    "message": "The selected gender is invalid.",
    "status_code": 422
}
```

## Available Methods

### 1. `validateRequest()` - Throws Exception
Use this method when you want the validation to throw an exception that will be caught by the global exception handler.

```php
public function updateProfile(Request $request)
{
    $user = Auth::user();
    $validated = $this->validateRequest($request->all(), [
        'name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
    ]);

    // If validation fails, an exception is thrown and caught by the global handler
    // If validation passes, $validated contains the validated data

    // Continue with your logic...
}
```

### 2. `validateWithCustomError()` - Returns Response or Data
Use this method when you want to handle validation errors manually in your controller.

```php
public function updateProfile(Request $request)
{
    $user = Auth::user();
    $result = $this->validateWithCustomError($request->all(), [
        'name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
    ]);

    // Check if validation failed
    if ($result instanceof JsonResponse) {
        return $result; // This is the error response
    }

    // $result contains the validated data
    $validated = $result;

    // Continue with your logic...
}
```

### 3. `validateData()` - Returns Data or False
Use this method when you want to handle validation errors manually and return false on failure.

```php
public function updateProfile(Request $request)
{
    $user = Auth::user();
    $validated = $this->validateData($request->all(), [
        'name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
    ]);

    if ($validated === false) {
        return $this->errorResponse('Validation failed', 422);
    }

    // $validated contains the validated data
    // Continue with your logic...
}
```

## Global Exception Handler

The global exception handler has been updated to automatically format validation errors. When using `validateRequest()`, any validation exception will be automatically caught and formatted to return only the first error message.

## Usage Examples

### Example 1: Simple Validation
```php
public function store(Request $request)
{
    $validated = $this->validateRequest($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string|unique:users',
    ]);

    // If validation fails, an exception is thrown and caught globally
    // If validation passes, continue with your logic
}
```

### Example 2: Custom Error Handling
```php
public function store(Request $request)
{
    $result = $this->validateWithCustomError($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ]);

    if ($result instanceof JsonResponse) {
        return $result; // Return the error response
    }

    $validated = $result;
    // Continue with your logic
}
```

### Example 3: Manual Error Handling
```php
public function store(Request $request)
{
    $validated = $this->validateData($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ]);

    if ($validated === false) {
        return $this->errorResponse('Please check your input and try again', 422);
    }

    // Continue with your logic
}
```

## Response Format

### Success Response
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        }
    },
    "status_code": 200
}
```

### Error Response (Validation)
```json
{
    "success": false,
    "message": "The selected gender is invalid.",
    "status_code": 422
}
```

## Migration Guide

### Before (Old Way)
```php
public function updateProfile(Request $request)
{
    $validated = $request->validate([
        'name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
    ]);

    // Continue with logic...
}
```

### After (New Way)
```php
public function updateProfile(Request $request)
{
    $validated = $this->validateRequest($request->all(), [
        'name' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,other',
    ]);

    // Continue with logic...
}
```

## Benefits

1. **Cleaner API Responses**: Only the first error message is returned
2. **Better User Experience**: Users see a single, clear error message
3. **Consistent Error Format**: All validation errors follow the same format
4. **Easy to Implement**: Just replace `$request->validate()` with `$this->validateRequest()`
5. **Flexible**: Multiple methods available for different use cases

## Notes

- The global exception handler automatically catches validation exceptions and formats them
- Only the first validation error is returned in the response
- All existing validation rules and custom messages work as before
- The methods are available in all controllers that extend `BaseController` 
