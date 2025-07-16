<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if logging is enabled
        if (!config('api_logging.enabled', true)) {
            return $next($request);
        }

        // Check if route should be excluded
        if ($this->shouldExcludeRoute($request)) {
            return $next($request);
        }

        // Check if method should be logged
        if (!in_array($request->method(), config('api_logging.log_methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']))) {
            return $next($request);
        }

        $startTime = microtime(true);

        // Get the response first (don't block on logging)
        $response = $next($request);

        // Calculate execution time
        $executionTime = microtime(true) - $startTime;

        // Log asynchronously to avoid blocking the response
        $this->logApiCallAsync($request, $response, $executionTime);

        return $response;
    }

    /**
     * Check if the route should be excluded from logging
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function shouldExcludeRoute(Request $request)
    {
        $excludeRoutes = config('api_logging.exclude_routes', []);
        $currentPath = $request->path();

        foreach ($excludeRoutes as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $currentPath)) {
                    return true;
                }
            } elseif ($currentPath === $pattern) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the API call asynchronously to avoid blocking the response
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @param float $executionTime
     * @return void
     */
    private function logApiCallAsync(Request $request, $response, $executionTime)
    {
        // Use dispatch to run logging in background
        dispatch(function () use ($request, $response, $executionTime) {
            try {
                $this->performLogging($request, $response, $executionTime);
            } catch (\Exception $e) {
                // Silently fail logging to avoid breaking API responses
                Log::error('API Logging failed: ' . $e->getMessage());
            }
        })->afterResponse();
    }

    /**
     * Perform the actual logging operation
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @param float $executionTime
     * @return void
     */
    private function performLogging(Request $request, $response, $executionTime)
    {
        $logLevel = config('api_logging.log_level', 'info');
        $logChannel = config('api_logging.log_channel', 'api');

        // Create structured log data
        $logData = $this->createStructuredLogData($request, $response, $executionTime);

        Log::channel($logChannel)->$logLevel('API Request', $logData);
    }

    /**
     * Create structured log data with all required information
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @param float $executionTime
     * @return array
     */
    private function createStructuredLogData(Request $request, $response, $executionTime)
    {
        $data = [
            'request_url' => $request->fullUrl(),
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'execution_time' => round($executionTime * 1000, 2) . 'ms',
            'status_code' => $response->getStatusCode(),
            'request' => $this->getRequestData($request),
            'response' => $this->getResponseData($response),
        ];

        // Add user information if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $data['user'] = [
                'id' => $user->id,
                'email' => $user->email ?? null,
                'name' => $user->name ?? null,
            ];
        }

        return $data;
    }

    /**
     * Get request data
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function getRequestData(Request $request)
    {
        $data = [];

        // Add request body if enabled
        if (config('api_logging.log_request_body', true)) {
            $body = $request->all();
            if (strlen(json_encode($body)) <= config('api_logging.max_body_size', 10000)) {
                $data['body'] = $this->filterRequestBody($body);
            } else {
                $data['body'] = '[BODY_TOO_LARGE]';
            }
        }

        // Add headers if enabled
        if (config('api_logging.log_headers', false)) {
            $data['headers'] = $this->filterHeaders($request->headers->all());
        }

        return $data;
    }

    /**
     * Get response data
     *
     * @param \Illuminate\Http\Response $response
     * @return array
     */
    private function getResponseData($response)
    {
        $data = [
            'status_code' => $response->getStatusCode(),
            'response_size' => strlen($response->getContent()),
        ];

        // Add response body if enabled or for errors
        $shouldLogResponseBody = config('api_logging.log_response_body', true) ||
            in_array($response->getStatusCode(), config('api_logging.log_response_body_for_status_codes', [400, 401, 403, 404, 422, 500, 502, 503]));

        if ($shouldLogResponseBody) {
            $content = $response->getContent();
            if (strlen($content) <= config('api_logging.max_body_size', 10000)) {
                $data['body'] = $this->filterResponseBody($content);
            } else {
                $data['body'] = '[RESPONSE_TOO_LARGE]';
            }
        }

        return $data;
    }

    /**
     * Filter sensitive headers from logging
     *
     * @param array $headers
     * @return array
     */
    private function filterHeaders(array $headers)
    {
        $sensitiveHeaders = config('api_logging.sensitive_headers', ['authorization', 'cookie', 'x-csrf-token']);

        return collect($headers)->map(function ($value, $key) use ($sensitiveHeaders) {
            if (in_array(strtolower($key), $sensitiveHeaders)) {
                return '[HIDDEN]';
            }
            return $value;
        })->toArray();
    }

    /**
     * Filter sensitive data from request body
     *
     * @param array $data
     * @return array
     */
    private function filterRequestBody(array $data)
    {
        $sensitiveFields = config('api_logging.sensitive_fields', ['password', 'password_confirmation', 'token', 'api_key', 'secret']);

        return collect($data)->map(function ($value, $key) use ($sensitiveFields) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                return '[HIDDEN]';
            }

            // Recursively filter nested arrays
            if (is_array($value)) {
                return $this->filterRequestBody($value);
            }

            return $value;
        })->toArray();
    }

    /**
     * Filter sensitive data from response body
     *
     * @param string $content
     * @return string
     */
    private function filterResponseBody($content)
    {
        // Decode JSON to filter sensitive fields
        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $filtered = $this->filterRequestBody($decoded);
            return json_encode($filtered);
        }

        return $content;
    }
}
