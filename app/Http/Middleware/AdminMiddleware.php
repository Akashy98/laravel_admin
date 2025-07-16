<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Please login to access admin panel.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Access denied. Admin privileges required.');
        }

        // Check if user is active
        if (!Auth::user()->isActive()) {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
