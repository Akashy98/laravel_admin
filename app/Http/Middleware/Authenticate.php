<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the request is for admin routes
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }

            // For other routes, redirect to admin login as default
            return route('admin.login');
        }
    }
}
