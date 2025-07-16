# API Logging Middleware

This middleware automatically logs all API requests and responses for debugging and monitoring purposes.

## Features

- **Performance Optimized**: Asynchronous logging that doesn't block API responses
- **Minimal Overhead**: Default settings minimize impact on API response times
- **Request Logging**: Logs HTTP method, URL, IP address, and user agent (headers/body optional)
- **Response Logging**: Logs status code, response size, execution time (body only for errors)
- **Security**: Automatically filters sensitive data like passwords, tokens, and authorization headers
- **Flexibility**: Can be enabled/disabled via configuration and supports route exclusions
- **User Tracking**: Logs authenticated user information when available
- **Error Handling**: Silent failure to prevent logging issues from breaking API responses

## Configuration

The middleware uses the `config/api_logging.php` configuration file. You can customize the following settings:

### Environment Variables

Add these to your `.env` file:

```env
# Enable/disable API logging
API_LOGGING_ENABLED=true

# Log channel (api, daily, stack, etc.)
API_LOGGING_CHANNEL=api

# Log level (debug, info, warning, error)
API_LOGGING_LEVEL=info

# Log request body (disabled by default for performance)
API_LOGGING_REQUEST_BODY=false

# Log response body (disabled by default for performance)
API_LOGGING_RESPONSE_BODY=false

# Log user information
API_LOGGING_USER_INFO=true

# Log headers (disabled by default for performance)
API_LOGGING_HEADERS=false

# Log execution time
API_LOGGING_EXECUTION_TIME=true

# Maximum body size to log (in bytes)
API_LOGGING_MAX_BODY_SIZE=10000
```

### Configuration File

The `config/api_logging.php` file contains all configuration options:

- `enabled`: Enable/disable logging
- `log_channel`: Laravel log channel to use
- `log_level`: Log level for API logs
- `log_request_body`: Whether to log request body
- `log_response_body`: Whether to log response body
- `log_response_body_for_status_codes`: Status codes for which to log response body
- `log_user_info`: Whether to log user information
- `log_headers`: Whether to log headers
- `log_execution_time`: Whether to log execution time
- `sensitive_headers`: Headers to hide in logs
- `sensitive_fields`: Request/response fields to hide in logs
- `exclude_routes`: Routes to exclude from logging
- `max_body_size`: Maximum body size to log
- `log_methods`: HTTP methods to log

## Usage

The middleware is automatically applied to all API routes via the `api` middleware group in `app/Http/Kernel.php`.

### Manual Application

You can also apply it manually to specific routes:

```php
Route::middleware('api.logging')->group(function () {
    // Your routes here
});
```

## Log Output

### Single Log Entry Example

The middleware now creates **one log entry per API call** with structured data format:

**Log Message:**
```
API Request
```

**Structured Log Data:**
```json
{
    "request_url": "https://yourdomain.com/api/user/login",
    "endpoint": "api/user/login",
    "method": "POST",
    "ip": "192.168.1.1",
    "execution_time": "45.23ms",
    "status_code": 200,
    "request": {
        "body": {
            "email": "user@example.com",
            "password": "[HIDDEN]"
        }
    },
    "response": {
        "status_code": 200,
        "response_size": 245,
        "body": {
            "success": true,
            "message": "Login successful",
            "data": {
                "user": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "user@example.com"
                },
                "token": "[HIDDEN]"
            }
        }
    },
    "user": {
        "id": 1,
        "email": "user@example.com",
        "name": "John Doe"
    }
}
```

### Log Format Benefits

✅ **Single Entry**: One log entry per API call instead of separate request/response logs  
✅ **Readable Message**: Human-readable summary in the log message  
✅ **Complete Data**: All request and response information in structured format  
✅ **Easy Filtering**: Simple to search and filter logs  
✅ **Performance**: Reduced log file size and better performance

## Security Features

- **Sensitive Data Filtering**: Automatically hides passwords, tokens, and other sensitive information
- **Header Filtering**: Hides authorization headers and cookies
- **Size Limits**: Prevents logging of extremely large payloads
- **Configurable Exclusions**: Can exclude specific routes from logging

## Performance Optimizations

- **Asynchronous Logging**: Logging runs after response is sent, not blocking API calls
- **Minimal Data Collection**: Only essential data is collected by default
- **Conditional Processing**: Expensive operations (headers, body) are disabled by default
- **Error Isolation**: Logging failures don't affect API responses
- **Memory Efficient**: Reduced data processing and storage
- **Configurable Overhead**: Can enable more detailed logging when needed

### Performance Impact
- **Default Settings**: < 1ms overhead per API call
- **With Full Logging**: 2-5ms overhead (when headers/body enabled)
- **Asynchronous**: No blocking of API responses

## Troubleshooting

### Disable Logging

To disable API logging, set in your `.env`:

```env
API_LOGGING_ENABLED=false
```

### Exclude Specific Routes

Add routes to exclude in `config/api_logging.php`:

```php
'exclude_routes' => [
    'api/health',
    'api/metrics',
    'api/webhook/*',
],
```

### Custom Log Channel

The API logging channel is already configured in `config/logging.php`:

```php
'api' => [
    'driver' => 'daily',
    'path' => storage_path('logs/api.log'),
    'level' => 'debug',
    'days' => 30,
],
```

This creates daily log files like:
- `storage/logs/api-2024-01-15.log`
- `storage/logs/api-2024-01-16.log`
- etc.

You can customize the channel settings or create additional channels as needed. 
