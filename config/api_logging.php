<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Logging Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the API logging middleware.
    | You can customize the logging behavior by modifying these settings.
    |
    */

    // Enable or disable API logging
    'enabled' => env('API_LOGGING_ENABLED', true),

    // Log channel to use for API logs
    'log_channel' => env('API_LOGGING_CHANNEL', 'api'),

    // Log level for API requests and responses
    'log_level' => env('API_LOGGING_LEVEL', 'info'),

    // Whether to log request body
    'log_request_body' => env('API_LOGGING_REQUEST_BODY', true),

    // Whether to log response body
    'log_response_body' => env('API_LOGGING_RESPONSE_BODY', true),

    // Log response body for specific status codes (e.g., errors)
    'log_response_body_for_status_codes' => [400, 401, 403, 404, 422, 500, 502, 503],

    // Whether to log user information
    'log_user_info' => env('API_LOGGING_USER_INFO', true),

    // Whether to log headers (disabled by default for performance)
    'log_headers' => env('API_LOGGING_HEADERS', false),

    // Whether to log execution time
    'log_execution_time' => env('API_LOGGING_EXECUTION_TIME', true),

    // Sensitive headers that should be hidden in logs
    'sensitive_headers' => [
        'authorization',
        'cookie',
        'x-csrf-token',
        'x-api-key',
        'x-auth-token',
    ],

    // Sensitive fields in request/response body that should be hidden
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
        'access_token',
        'refresh_token',
        'otp',
        'verification_code',
    ],

    // Routes to exclude from logging (patterns)
    'exclude_routes' => [
        // 'api/health',
        // 'api/metrics',
        'api/home',
        'api/astrologers',
    ],

    // Maximum size of request/response body to log (in bytes)
    'max_body_size' => env('API_LOGGING_MAX_BODY_SIZE', 10000), // 10KB

    // Whether to log only specific HTTP methods
    'log_methods' => [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
    ],
];
